<?php
/**
 * Site map controller
 */
class SitemapController extends SiteBaseController {

	/**
	 * How long do we want to cache the results
	 */
	const CACHE_TIME = 600;
	
	public function init()
	{
		// Disable layout
		$this->layout = false;
		
		return parent::init();
	}
	
	/**
	 * Index action
	 */
    public function actionindex() {
    	header("content-type: text/xml");
    	
    	// Grab rows
    	$time = time();
    	$cache = Yii::app()->cache->get('sitemap');
    	if( $cache )
    	{
    		$rows = $cache;
    		$time = Yii::app()->cache->get('sitemaptime');
    	}
    	else
    	{
    		$rows = $this->getRows();
    		Yii::app()->cache->set( 'sitemap', $rows, self::CACHE_TIME );
    		Yii::app()->cache->set('sitemaptime', time(), self::CACHE_TIME);
    	}
    	
		echo $this->renderPartial('sitemap', array( 'rows' => $rows, 'time' => Yii::app()->format->formatDateTime( $time, 'full', 'short' )  ) );
		Yii::app()->end();
    }
    
    /**
     * Get the rows for the sitemap
     */
    protected function getRows()
    {
    	$_rows = array();
    	
    	// Grab blog cats
    	$blogCats = BlogCats::model()->findAll('language=:langauge', array(':langauge'=>Yii::app()->language) );
    	if( count( $blogCats ) )
    	{
    		foreach( $blogCats as $blogCat )
    		{
    			$_rows[] = $this->makeData( $this->getFullUrl( '/blog/category/' . $blogCat->alias ), time(), 'monthly', 0.1 );
    		}
    	}
    	
    	// Grab blog rows
    	$blogRows = Blog::model()->findAll('language=:langauge', array(':langauge'=>Yii::app()->language) );
    	if( count( $blogRows ) )
    	{
    		foreach( $blogRows as $blogRow )
    		{
    			$_rows[] = $this->makeData( $this->getFullUrl( '/blog/view/' . $blogRow->alias ), $blogRow->postdate, 'weekly', 1 );
    		}
    	}
    	
    	// Grab tutorials cats
    	$tutorialsCats = TutorialsCats::model()->findAll('language=:langauge', array(':langauge'=>Yii::app()->language) );
    	if( count( $tutorialsCats ) )
    	{
    		foreach( $tutorialsCats as $tutorialsCat )
    		{
    			$_rows[] = $this->makeData( $this->getFullUrl( '/tutorials/category/' . $tutorialsCat->alias ), time(), 'monthly', 0.1 );
    		}
    	}
    	
    	// Grab tutorials rows
    	$tutorialsRows = Tutorials::model()->findAll('language=:langauge', array(':langauge'=>Yii::app()->language) );
    	if( count( $tutorialsRows ) )
    	{
    		foreach( $tutorialsRows as $tutorialsRow )
    		{
    			$_rows[] = $this->makeData( $this->getFullUrl( '/tutorials/view/' . $tutorialsRow->alias ), $tutorialsRow->postdate, 'weekly', 1 );
    		}
    	}
    	
    	// Grab extensions cats
    	$extensionsCats = ExtensionsCats::model()->findAll('language=:langauge', array(':langauge'=>Yii::app()->language) );
    	if( count( $extensionsCats ) )
    	{
    		foreach( $extensionsCats as $extensionsCat )
    		{
    			$_rows[] = $this->makeData( $this->getFullUrl( '/extensions/category/' . $extensionsCat->alias ), time(), 'monthly', 0.1 );
    		}
    	}
    	
    	// Grab extensions rows
    	$extensionsRows = Extensions::model()->findAll('language=:langauge', array(':langauge'=>Yii::app()->language) );
    	if( count( $extensionsRows ) )
    	{
    		foreach( $extensionsRows as $extensionsRow )
    		{
    			$_rows[] = $this->makeData( $this->getFullUrl( '/extensions/view/' . $extensionsRow->alias ), $extensionsRow->postdate, 'weekly', 1 );
    		}
    	}
    	
    	// Grab users rows
    	$usersRows = Members::model()->findAll();
    	if( count( $usersRows ) )
    	{
    		foreach( $usersRows as $usersRow )
    		{
    			$_rows[] = $this->makeData( $this->getFullUrl( '/user/' . $usersRow->id . '-' . $usersRow->seoname ), $usersRow->joined, 'monthly', 1 );
    		}
    	}
    	
    	// Grab forum topics rows
    	$forumTopics = ForumTopics::model()->findAll('language=:langauge', array(':langauge'=>Yii::app()->language) );
    	if( count( $forumTopics ) )
    	{
    		foreach( $forumTopics as $forumTopic )
    		{
    			$_rows[] = $this->makeData( $this->getFullUrl( '/forum/topic/' . $forumTopic->id . '-' . $forumTopic->alias ), $forumTopic->dateposted, 'daily', 1 );
    		}
    	}
    	
    	// Grab custom pages
    	$customPages = CustomPages::model()->findAll('language=:langauge', array(':langauge'=>Yii::app()->language) );
    	if( count( $customPages ) )
    	{
    		foreach( $customPages as $customPage )
    		{
    			$_rows[] = $this->makeData( $this->getFullUrl( '/' . $forumTopic->alias ), $customPage->dateposted, 'weekly', 1 );
    		}
    	}
    	
    	// Grab documentation pages
    	$documentations = Documentation::model()->findAll('language=:langauge', array(':langauge'=>Yii::app()->language) );
    	if( count( $documentations ) )
    	{
    		foreach( $documentations as $documentation )
    		{
    			$_rows[] = $this->makeData( $this->getFullUrl( '/documentation/guide/' . $documentation->type . '/topic/' . $documentation->mkey ), $documentation->last_updated, 'weekly', 1 );
    		}
    	}
    	
    	
    	// Return array
    	return $_rows;
    }
    
    /**
     * Create full url
     */
    protected function getFullUrl( $route )
    {
    	return Yii::app()->createAbsoluteUrl($route, array( 'lang' => false ) );
    }
    
    /**
     * Based on the incoming data, Construct an array with the information required
     * to build the xml schema
     *
     * @param string $location
     * @param int $time
     * @param string $change
     * @param int $freq
     * @return array
     */
    protected function makeData( $location, $time, $change, $freq )
    {
    	return array(
    				'loc' => $location,
    				'lastmod' => Yii::app()->dateFormatter->format( 'yyyy-MM-dd', $time ),
    				'changefreq' => $change,
    				'priority' => (float) $freq,
    				);
    }
    
}