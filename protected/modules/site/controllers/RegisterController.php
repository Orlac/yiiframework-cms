<?php
/**
 * Register controller class
 */
class RegisterController extends SiteBaseController
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
		$this->pageTitle[] = Yii::t('global', 'Register');
		$this->breadcrumbs[ Yii::t('global', 'Register') ] = array('register/index');
		
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
	 * Register action
	 */
	public function actionindex()
	{
		$model = new RegisterForm;
		
		if( isset($_POST['RegisterForm']) )
		{
			$model->attributes = $_POST['RegisterForm'];
			if( $model->validate() )
			{
				// Save the member and redirect
				$user = new Members;
				$user->scenario = 'register';
				$user->role = 'member';
				$user->attributes = $_POST['RegisterForm'];
				$user->save();
				
				// Redirect
				Yii::app()->user->setFlash('success', Yii::t('register', 'Registration Completed. Please sign in.'));
				$this->redirect('login/index');
			}
		}
		
		$this->render('index', array('model'=>$model));
	}
}