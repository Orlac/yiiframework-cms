<?php
/**
 * Admin base controller class
 */
class AdminBaseController extends BaseController {
	
	/**
	 * admins breadcrumbs
	 */
	public $breadcrumbs = array();
	
    /**
     * Class constructor
     *
     */
    public function init() {		
	
		// Add default page title which is the application name
        $this->pageTitle[] = Yii::t('global', Yii::app()->name);
		$this->pageTitle[] = Yii::t('global', 'Admin');
		
		// By default we register the robots to 'none'
        Yii::app()->clientScript->registerMetaTag( 'noindex, nofollow', 'robots' );

		// We add a meta 'language' tag based on the currently viewed language
		Yii::app()->clientScript->registerMetaTag( Yii::app()->language, 'language', 'content-language' );
		
		// Make sure we have access
		if( ( Yii::app()->user->role != 'admin' || !Yii::app()->user->checkAccess('op_acp_access') ) )
		{
			throw new CException(Yii::t('error', 'Sorry, You are not allowed to enter this section.') );
		}
		
		/* Run init */
        parent::init();
    }
}