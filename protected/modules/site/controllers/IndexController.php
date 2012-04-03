<?php
/**
 * Index controller Home page
 */
class IndexController extends SiteBaseController {
	/**
	 * Controller constructor
	 */
    public function init()
    {
        parent::init();
    }

	/**
	 * Index action
	 */
    public function actionindex() {
	
		$model = new Newsletter;
		$sent = false;
		if( isset($_POST['Newsletter']) )
		{
			$model->attributes = $_POST['Newsletter'];
			if( $model->save() )
			{
				$sent = true;
				Yii::app()->user->setFlash('success', Yii::t('index', 'Thank you. You are now subscribed to our newsletter.'));
			}
		}
		
		// Load facebook
		Yii::import('ext.facebook.facebookLib');
		$facebook = new facebookLib(array( 'appId' => Yii::app()->params['facebookappid'], 'secret' => Yii::app()->params['facebookapisecret'], 'cookie' => true, 'disableSSLCheck' => false ));
		facebookLib::$CURL_OPTS[CURLOPT_CAINFO] = Yii::getPathOfAlias('ext.facebook') . '/ca-bundle.crt';
	
        $this->render('index', array('model'=>$model, 'facebook' => $facebook, 'sent'=>$sent));
    }
}