<?php
/**
 * Sharer controller Home page
 */
class SharerController extends AdminBaseController {
	/**
	 * init
	 */
	public function init()
	{
		parent::init();
		
		$this->breadcrumbs[ Yii::t('adminglobal', 'Sharer') ] = array('sharer/index');
		$this->pageTitle[] = Yii::t('adminglobal', 'Sharer'); 
	}
	/**
	 * Index action
	 */
    public function actionIndex() {	
	
		
        $this->render('index');
    }
}