<?php
/**
 * error controller Home page
 */
class ErrorController extends AdminBaseController {
	/**
	 * init
	 */
	public function init()
	{
		parent::init();
		
		$this->breadcrumbs[ Yii::t('adminglobal', 'Error') ] = array('index/index');
		$this->pageTitle[] = Yii::t('adminglobal', 'Error'); 
	}
	/**
	 * Index action
	 */
    public function actionError() {
		$error = Yii::app()->errorHandler->error;
        $this->render('error', array('error'=>$error));
    }
}