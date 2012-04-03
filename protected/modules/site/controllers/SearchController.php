<?php
/**
 * Search controller Home page
 */
class SearchController extends SiteBaseController {
	/**
	 * Controller constructor
	 */
    public function init()
    {
        parent::init();

		// Add page breadcrumb and title
		$this->pageTitle[] = Yii::t('search', 'Search');
		$this->breadcrumbs[ Yii::t('search', 'Search') ] = array('search/index');
    }

	/**
	 * Index action
	 */
    public function actionIndex() {
        $this->render('index');
    }
}