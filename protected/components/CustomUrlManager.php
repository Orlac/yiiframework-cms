<?php
/**
 * Custom rules manager class
 *
 * Override to load the routes from the DB rather then a file
 *
 */
class CustomUrlManager extends CUrlManager {
    /**
     * Build the rules from the DB
     */
    protected function processRules() {
	
		$active_lang = implode('|', array_keys( Yii::app()->params['languages'] ));
		$domain = Yii::app()->params['current_domain'];
		
		if( ($urlrules = Yii::app()->cache->get('customurlrules')) === false )
		{
			$dbCommand = Yii::app()->db->createCommand("SELECT alias, language FROM {{custompages}}")->query();
			$urlRules = $dbCommand->readAll();
			$_more = array();
			foreach($urlRules as $rule)
			{
				$_more[ "http://<lang:({$rule['language']})>.{$domain}/<alias:({$rule['alias']})>" ] = array('site/custompages/index');
			}		
		
			// Do we use subdomains for languages or as directory seperators
			if( Yii::app()->params['subdomain_languages'] )
			{
				$this->rules = array(
				
					//-----------------------ADMIN--------------
				
					// Match by index/index
					"http://<lang:({$active_lang})>.{$domain}/admin" => array('admin/index/index'), 
					
					// Match by controller/index
					"http://<lang:({$active_lang})>.{$domain}/admin/<_c:([a-zA-z0-9-]+)>" => array('admin/<_c>/index/'),
				
					// Match by controller/action/* 
					"http://<lang:({$active_lang})>.{$domain}/admin/<_c:([a-zA-z0-9-_]+)>/<_a:([a-zA-z0-9-_]+)>/*" => array('admin/<_c>/<_a>/'),
				
					// Match by controller/action/* and more
					"http://<lang:({$active_lang})>.{$domain}/admin/<_c:([a-zA-z0-9-_]+)>/<_a:([a-zA-z0-9-_]+)>/*" => array('admin/<_c>/<_a>'),
				
					// Match by controller/index
					"http://<lang:({$active_lang})>.{$domain}/admin/<_c:([a-zA-z0-9-]+)>/*" => array('admin/<_c>/index/'),
					"http://<lang:({$active_lang})>.{$domain}/admin/<_c:([a-zA-z0-9-]+)>/*" => array('admin/<_c>/index'),
				
					//-----------------------SITE--------------
					
					// Site Map
					"http://<lang:({$active_lang})>.{$domain}/sitemap" => array('site/sitemap/index', 'urlSuffix'=>'.xml', 'caseSensitive'=>false),
					
					// Tutorials
					"http://<lang:({$active_lang})>.{$domain}/tutorials/category/<alias:(.*)>" => array('site/tutorials/viewcategory'),
					"http://<lang:({$active_lang})>.{$domain}/tutorials/view/<alias:(.*)>" => array('site/tutorials/viewtutorial'),
					
					// Blogs
					"http://<lang:({$active_lang})>.{$domain}/blog/category/<alias:(.*)>" => array('site/blog/viewcategory'),
					"http://<lang:({$active_lang})>.{$domain}/blog/view/<alias:(.*)>" => array('site/blog/viewpost'),
					
					// Extensions
					"http://<lang:({$active_lang})>.{$domain}/extensions/category/<alias:(.*)>" => array('site/extensions/viewcategory'),
					"http://<lang:({$active_lang})>.{$domain}/extensions/view/<alias:(.*)>" => array('site/extensions/viewpost'),
					"http://<lang:({$active_lang})>.{$domain}/extensions/download/<fileid:(\d+)>-<alias:(.*)>" => array('site/extensions/download'),
					
					// User profile
					"http://<lang:({$active_lang})>.{$domain}/user/<uid:(\d+)>-<alias:(.*)>" => array('site/users/viewprofile'),
					
					// Forum Topics
					"http://<lang:({$active_lang})>.{$domain}/forum/topic/<topicid:(\d+)>-<alias:(.*?)>/*" => array('site/forum/viewtopic'), 
				
					// Match by controller/index
					"http://<lang:({$active_lang})>.{$domain}/<_c:([a-zA-z0-9-]+)>" => array('site/<_c>/index'),
				
					// Match by controller/action/* 
					"http://<lang:({$active_lang})>.{$domain}/<_c:([a-zA-z0-9-_]+)>/<_a:([a-zA-z0-9-_]+)>/*" => array('site/<_c>/<_a>/'),
					// Match by controller/action/* and more
					"http://<lang:({$active_lang})>.{$domain}/<_c:([a-zA-z0-9-_]+)>/<_a:([a-zA-z0-9-_]+)>/*" => array('site/<_c>/<_a>'),
				
					// Match by index/index
					"http://<lang:({$active_lang})>.{$domain}" => array('site/index/index'),
					"http://<lang:({$active_lang})>.{$domain}/" => array('site/index/index'),
					
					
					// Match by controller index
					"http://<lang:({$active_lang})>.{$domain}/<_c:([a-zA-z0-9-_]+)>/*" => array('site/<_c>/index/'),
					"http://<lang:({$active_lang})>.{$domain}/<_c:([a-zA-z0-9-_]+)>/*" => array('site/<_c>/index'),
				
		            );
			}
			else
			{
				$this->rules = array(
				
					//-----------------------ADMIN--------------
					"<lang:({$active_lang})>/admin" => 'admin/index/index',
					"<lang:({$active_lang})>/admin/<_c:([a-zA-z0-9-]+)>" => 'admin/<_c>/index',
		            "<lang:({$active_lang})>/admin/<_c:([a-zA-z0-9-]+)>/<_a:([a-zA-z0-9-]+)>" => 'admin/<_c>/<_a>',
		            "<lang:({$active_lang})>/admin/<_c:([a-zA-z0-9-]+)>/<_a:([a-zA-z0-9-]+)>//*" => 'admin/<_c>/<_a>/',
					//-----------------------ADMIN--------------
				
					"<lang:({$active_lang})>/" => 'site/index/index', 
					"<lang:({$active_lang})>/<_c:([a-zA-z0-9-]+)>" => 'site/<_c>/index',
		            "<lang:({$active_lang})>/<_c:([a-zA-z0-9-]+)>/<_a:([a-zA-z0-9-]+)>" => 'site/<_c>/<_a>',
		            "<lang:({$active_lang})>/<_c:([a-zA-z0-9-]+)>/<_a:([a-zA-z0-9-]+)>//*" => 'site/<_c>/<_a>/',
	            
		            );
			}
		
			$urlrules = array_merge( $_more, $this->rules );
			Yii::app()->cache->set('customurlrules', $urlrules);
		}
		
		$this->rules = $urlrules;

        // Run parent
        parent::processRules();

    }

	/**
	 * Clear the url manager cache
	 */
	public function clearCache()
	{
		Yii::app()->cache->delete('customurlrules');
	}

    /**
     *
     * @see CUrlManager 
     *
     * Constructs a URL.
     * @param string the controller and the action (e.g. article/read)
     * @param array list of GET parameters (name=>value). Both the name and value will be URL-encoded.
     * If the name is '#', the corresponding value will be treated as an anchor
     * and will be appended at the end of the URL. This anchor feature has been available since version 1.0.1.
     * @param string the token separating name-value pairs in the URL. Defaults to '&'.
     * @return string the constructed URL
     */
    public function createUrl($route,$params=array(),$ampersand='&')
    {
        // We added this by default to all links to show
        // Content based on language - Add only when not excplicity set
		if( !isset($params['lang']) )
		{
			$params['lang'] = Yii::app()->language;
		}
		
		if( ( isset($params['lang']) && $params['lang'] === false ) )
		{
			unset($params['lang']);
		}

        // Use parent to finish url construction
        return parent::createUrl($route, $params, $ampersand);
    }
}
