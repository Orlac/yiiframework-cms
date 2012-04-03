<?php
/**
 * Login controller class 
 * Used for authenticate a member
 * 
 */
class LoginController extends SiteBaseController
{
	/**
	 * Controller constructor
	 */
	public function init()
	{		
		// Do not allow logged in users here
		if( Yii::app()->user->id )
		{
			$this->redirect('index/index');
		}
		
		// Add page breadcrumb and title
		$this->pageTitle[] = Yii::t('global', 'Login');
		$this->breadcrumbs[ Yii::t('global', 'Login') ] = array('login/index');
		
		parent::init();
	}
	
	/**
	 * List of available actions
	 */
	public function actions()
	{
	   return array(
	      'captcha' => array(
	         'class' => 'CCaptchaAction',
	         'backColor' => 0xFFFFFF,
		     'minLength' => 3,
		     'maxLength' => 7,
			 'testLimit' => 3,
			 'padding' => array_rand( range( 2, 10 ) ),
	      ),
	   );
	}
	
	/**
	 * Login action
	 */
	public function actionindex()
	{
		$model = new LoginForm;
		
		if( isset($_POST['LoginForm']) )
		{
			$model->attributes = $_POST['LoginForm'];
			if( $model->validate() )
			{
				// Login
				$identity = new InternalIdentity($model->email, $model->password);
				if($identity->authenticate())
				{
					// Member authenticated, Login
					Yii::app()->user->setFlash('success', Yii::t('login', 'Thanks. You are now logged in.'));
					Yii::app()->user->login($identity, (Yii::app()->params['loggedInDays'] * 60 * 60 * 24 ));
				}
					
				// Redirect
				$this->redirect('index/index');
			}
		}
		
		// Load facebook
		Yii::import('ext.facebook.facebookLib');
		$facebook = new facebookLib(array( 'appId' => Yii::app()->params['facebookappid'], 'secret' => Yii::app()->params['facebookapisecret'], 'cookie' => true, 'disableSSLCheck' => false ));
		facebookLib::$CURL_OPTS[CURLOPT_CAINFO] = Yii::getPathOfAlias('ext.facebook') . '/ca-bundle.crt';
		
		// Facebook link
		$facebookLink = $facebook->getLoginUrl(array('req_perms' => 'read_stream,email,offline_access', 'next' => Yii::app()->createAbsoluteUrl('/login/facebooklogin', array( 'lang' => false ) ), 'display'=>'popup') );
		
		$this->render('index', array('model'=>$model, 'facebookLink' => $facebookLink, 'facebook'=>$facebook));
	}
	
	/**
	 * Facebook login page
	 */
	public function actionFacebookLogin()
	{
		
		// Load facebook
		Yii::import('ext.facebook.facebookLib');
		$facebook = new facebookLib(array( 'appId' => Yii::app()->params['facebookappid'], 'secret' => Yii::app()->params['facebookapisecret'], 'cookie' => true, 'disableSSLCheck' => false ));
		facebookLib::$CURL_OPTS[CURLOPT_CAINFO] = Yii::getPathOfAlias('ext.facebook') . '/ca-bundle.crt';
		
		// Do we have an access token?
		if( ( $session = $facebook->getSession() ) || ( isset($_GET['session']) && $_GET['session'] ) )
		{	
			$info = array( 'id' => 0, 'email' => '' );
			
			$info = $facebook->getInfo(null, array('access_token'=>$session['access_token']));
			
			// Did we submit the authenticate form?
			$facebookForm = new facebookForm;
			
			if( isset($_POST['facebookForm']) )
			{
				$facebookForm->attributes = $_POST['facebookForm'];
				if( $facebookForm->validate() )
				{
					// Member authenticated
					
					$identity = new InternalIdentity($facebookForm->email, $facebookForm->password);
					if($identity->authenticate())
					{
						// Member authenticated, Login
						Yii::app()->user->login($identity, (Yii::app()->params['loggedInDays'] * 60 * 60 * 24 ));
					}
					else
					{
						Yii::app()->user->setFlash( 'success', $identity->errorMessage );
					}
					
					// Update the fbuid and update the token
					// We got through save the a new token
					Members::model()->updateByPk( $identity->getId() , array( 'fbuid' => $info['id'], 'fbtoken' => $session['access_token'] ) );
					
					// Login & redirect
					Yii::app()->user->setFlash( 'success', Yii::t('login', 'Thank You. You are now logged in.') );
					//$this->render('facebookdone', array( 'link' => $this->createUrl('/index', array( 'lang' => false ) ) ) );
					$this->redirect('/index');
				}
			}
			
			// Did we submit the signup form?
			$facebookSignForm = new Members;
			
			if( isset($_POST['Members']) )
			{
				$facebookSignForm->attributes = $_POST['Members'];
				$facebookSignForm->role =  'member';
				$facebookSignForm->scenario = 'register';
				if( $facebookSignForm->save() )
				{
					$identity = new InternalIdentity($facebookSignForm->email, $_POST['Members']['password']);
					if($identity->authenticate())
					{
						// Member authenticated, Login
						Yii::app()->user->login($identity, (Yii::app()->params['loggedInDays'] * 60 * 60 * 24 ));
					}
					else
					{
						Yii::app()->user->setFlash( 'success', $identity->errorMessage );
					}
					
					// Update the fbuid and update the token
					// We got through save the a new token
					Members::model()->updateByPk( $facebookSignForm->id, array( 'fbuid' => $info['id'], 'fbtoken' => $session['access_token'] ) );
					
					// Login & redirect
					Yii::app()->user->setFlash( 'success', Yii::t('login', 'Thank You. You are now logged in.') );
					//$this->render('facebookdone', array( 'link' => $this->createUrl('/index', array( 'lang' => false ) ) ) );
					$this->redirect('/index');
				}
			}
			
			// Authenticate
			$identity = new facebookIdentity($info['id'], $info['email']);
			$auth = $identity->authenticate();
			
			// What did we discover?
			if( $identity->errorCode == facebookIdentity::ERROR_UNKNOWN_IDENTITY )
			{
				// fbuid was not found in the DB
				Yii::app()->user->setFlash( 'attention', Yii::t('login', 'We could not find any user associated with that facebook account in our records.') );
			}
			else if ( $identity->errorCode == facebookIdentity::ERROR_USERNAME_INVALID )
			{
				// Email addresses did not match
				Yii::app()->user->setFlash( 'attention', Yii::t('login', 'We found a user account associated with your facebook account, But the email used there is different, Please complete the form below to login as that user.') );
			}
			else
			{
				// We got through save the a new token
				Yii::app()->user->login($identity, (Yii::app()->params['loggedInDays'] * 60 * 60 * 24 ));
				Members::model()->updateByPk( $identity->getId(), array( 'fbtoken' => $session['access_token'] ) );
				Yii::app()->user->setFlash( 'success', Yii::t('login', 'Thank You. You are now logged in.') );
				$this->render('facebookdone', array( 'link' => $this->createUrl('/index', array( 'lang' => false ) ) ) );
				//$this->redirect('/index');
			}
			
			// Redirect if haven't done so
			if( !isset( $_GET['facebookRedirected'] ) )
			{
				$_GET['facebookRedirected'] = 'true';
				$this->render('facebookdone', array( 'link' => $this->createUrl('/login/facebooklogin', array_merge( $_GET, array( 'lang' => false ) ) ) ) );
			}
			
			// Default values
			$facebookForm->email = $facebookForm->email ? $facebookForm->email : $info['email'];
			$facebookSignForm->email = $facebookSignForm->email ? $facebookSignForm->email : $info['email'];
			$facebookSignForm->username = $facebookSignForm->username ? $facebookSignForm->username : $info['name'];
			
			$this->render('facebook_login', array( 'facebookSignForm' => $facebookSignForm, 'facebookForm' => $facebookForm,  'info' => $info ));
		}
		else
		{
			$this->redirect('/login');
		}
	}
	
	/**
	 * Lost password screen
	 */
	public function actionlostpassword()
	{	
		$model = new LostPasswordForm;
		
		if(isset($_POST['LostPasswordForm']))
	    {
	        $model->attributes=$_POST['LostPasswordForm'];
	        if($model->validate())
			{					
				// Grab the member data
				$member = Members::model()->findByAttributes(array('email' => $model->email));
	
				// Create secret reset link
				$random = $member->hashPassword( $member->email . $member->username , microtime(true) );
				$link = $this->createAbsoluteUrl('/login/reset', array( 'q' => $random, 'lang' => false ));
				
				$message = Yii::t('login', "Dear {username},<br /><br />You've asked a reset for your password.<br /><br /> 
											Please click the link below in order to perform the reset and get a new password emailed to you.<br /><br />
											The reset link is:<br /><br />
											----------------------<br />
											{link}<br />
											----------------------<br /><br /><br />
											<em>If you did not request this reset then please ignore this email.</em>",
											array( '{username}' => $member->username, '{link}' => $link ));
											
				$message .= Yii::t('global', '<br /><br />----------------<br />Regards,<br />The {team} Team.<br />', array('{team}'=>Yii::app()->name));							
				
				$email = Yii::app()->email;
				$email->subject = Yii::t('login', 'Password Reset Request');
				$email->to = $member->email;
				$email->from = Yii::app()->params['emailin'];
				$email->replyTo = Yii::app()->params['emailout'];
				$email->message = $message;
				$email->send();
				
				// Save the key for this member
				$member->passwordreset = $random;
				
				$member->update();
				
				Yii::app()->user->setFlash('success', Yii::t('login', 'Thank You. Check your email for the password reset link.'));
				$model = new LostPasswordForm();
			}
	    }
	
		// Add page breadcrumb and title
		$this->pageTitle[] = Yii::t('login', 'Lost Password');
		$this->breadcrumbs[ Yii::t('login', 'Lost Password') ] = '';
		
		$this->render('lostpassword', array( 'model' => $model ));
	}
	
	/**
	 * Check the var in the password form and if it is ok 
	 * then reset the password and email the member the new one.
	 */
	public function actionreset()
	{
		$q = Yii::app()->format->text( $_GET['q'] );
		
		// Search for this in the DB
		$member = Members::model()->findByAttributes(array('passwordreset'=>$q));
		
		if( !$member )
		{
			Yii::app()->user->setFlash('error', Yii::t('login', 'Sorry, Nothing was found for that reset link.'));
        	$this->redirect('index/index');
		}
		
		// We matched so now reset the reset link,
		// Create a new password and save it for that member
		// Email and redirect
		
		// Create secret reset link
		$password = $member->generatePassword(5, 10);
		$hashedPassword = $member->hashPassword( $password, $member->email );
		
		$message = Yii::t('login', "Dear {username},<br /><br />
									We have reseted your password successfully.<br /><br />
									You new password is: <b>{password}</b><br />",
									array( '{username}' => $member->username, '{password}' => $password ));
									
		$message .= Yii::t('global', '<br /><br />----------------<br />Regards,<br />The {team} Team.<br />', array('{team}'=>Yii::app()->name));							
		
		$email = Yii::app()->email;
		$email->subject = Yii::t('login', 'Password Reset Completed');
		$email->to = $member->email;
		$email->from = Yii::app()->params['emailin'];
		$email->replyTo = Yii::app()->params['emailout'];
		$email->message = $message;
		$email->send();
		
		// Save the key for this member
		$member->passwordreset = '';
		$member->password = $hashedPassword;
		$member->scenario = 'lostpassword';
		
		$member->save();
		
		Yii::app()->user->setFlash('success', Yii::t('login', 'Thank You. Your password was reset. Please check your email for you new generated password.'));
    	$this->redirect('index/index');
	}
	
}