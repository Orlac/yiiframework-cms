<?php
/**
 * Base controller for all controllers under this application
 */
class BaseController extends CController {
	
	/**
	* This is the general page title, We use this instead of the 
	* applications pageTitle since it's not designed to be a string
	* But rather an array so we could reverse it later for SEO improvements
	*
	* @var string
	**/
	public $pageTitle = array();
	
	/**
	 * @var array - array of {@link CBreadCrumbs} link
	 */
	public $breadcrumbs = array();
	
    /**
     * Class constructor
     *
     */
    public function init() {
        /* Run init */
        parent::init();
    }
}