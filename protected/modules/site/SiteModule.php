<?php
/**
 * Site module class
 */
class SiteModule extends MasterModule {
    /**
     * Module constructor - Builds the initial module data
     *
     * @author vadim
     */
    public function init() {
	
		// Set theme url
        Yii::app()->themeManager->setBaseUrl( Yii::app()->theme->baseUrl );
        Yii::app()->themeManager->setBasePath( Yii::app()->theme->basePath );
	
        /* Make sure we run the master module init function */
        parent::init();
    }
}