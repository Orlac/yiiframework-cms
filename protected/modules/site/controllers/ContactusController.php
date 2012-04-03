<?php
/**
 * Contact Us Controller
 */
class ContactusController extends SiteBaseController {
	/**
	 * initialize
	 */
    public function init()
    {
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
	 * Show Form
	 */
    public function actionIndex() {
	
		$model = new ContactUs;
		
		if( isset($_POST['ContactUs']) )
		{
			$model->attributes = $_POST['ContactUs'];
			if( $model->save() )
			{
				// Do we need to email?
				if( Yii::app()->params['contactusemail'] )
				{
					// Build Message
					$message = Yii::t('contactus', "New Contact Us Form Submitted<br /><br />
												    Id: {id}<br />
													By: {name}<br />
													Email: {email}<br />
													Subject: {subject}<br />
													========================<br />
													{msg}<br />
													========================<br /><br />
													Regards, the {team} Team.", array(
																						'{id}' => $model->id,
														 								'{name}' => $model->name,
																						'{email}' => $model->email,
																						'{subject}' => $model->subject,
																						'{msg}' => $model->content,
																						'{team}' => Yii::app()->name,
														 							  ));
					$email = Yii::app()->email;
					$email->subject = Yii::t('contactus', 'New Contact Us Form: {subject}', array( '{subject}' => $model->subject ));
					$email->to = Yii::app()->params['emailout'];
					$email->from = $model->email;
					$email->replyTo = Yii::app()->params['emailout'];
					$email->message = $message;
					$email->send();
				}
				
				Yii::app()->user->setFlash('success', Yii::t('contactus', 'Thank You. The form submitted successfully.') );
				$model = new ContactUs;
			}
		}
		
		// If we are a member then fill in
		if( Yii::app()->user->id )
		{
			$user = Members::model()->findByPk(Yii::app()->user->id);
			if( $user )
			{
				$model->name = $user->username;
				$model->email = $user->email;
			}
		}
	
        $this->render('index', array( 'model' => $model ));
    }
}