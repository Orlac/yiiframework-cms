<?php

/**
* Documentation Controller
* 
* Display the related documentation categories
* Full documentation, Blog Documentation, Tutorials, API Link
*
**/
class DocumentationController extends SiteBaseController {	
	/**
	 * Comments per page
	 */
	const PAGE_SIZE = 20;
	/**
	 * @var array - list of topics
	 */
	private $_topics;
	
	/**
	 * @var string - currently active language
	 */
	private $_language;
	
	/**
	 * @var array - currently active translations
	 */
	private $_languages;
	
	/**
	 * Controller constructor
	 */
	public function init()
	{	
		parent::init();
		
		$this->breadcrumbs[ Yii::t('docs', 'Documentations') ] = array('index');
		$this->pageTitle[] = Yii::t('docs', 'Documentations');
	}
	
	/**
	 * Documentation index page
	 * Displays list of documentation 
	 * Latest comments from docs etc...
	 */
	public function actionindex()
	{
		$this->render('index');
	}
	
	/**
	 * Controller guide action
	 * By default displays the documentation from the protected/documentation/guide
	 * folder based on the language currently viewed
	 */
	public function actionguide()
	{
		$topic = $this->getTopic('guide');
		$model = Documentation::model()->find('mkey=:mkey AND type=:type AND language=:lang', array( ':type' => 'guide', ':mkey' => $topic, ':lang' => $this->language ));
		if( !$model )
		{
			$model = Documentation::model()->find('mkey=:mkey AND type=:type AND language=:lang', array( ':type' => 'guide', ':mkey' => $topic, ':lang' => 'source' ));
		}
		if( !strcasecmp($topic,'toc') || !$model )
		{
			// @todo Fix this
			throw new CHttpException(404,'The page you looked for does not exist.');
		}
		
		// Update views
		$model->views++;
		$model->save();
		
		// Cache parsed output
		if( ( $content = Yii::app()->cache->get('docbyid_'.$model->id) ) === false )
		{
			// Grab file contents and parse them
			$content = $model->content;
			$markdown = new MarkdownParser;
			$content = $markdown->safeTransform($content);
		
			// Manually convert items such as images, doc api links and guide links
			$imageUrl = Yii::app()->assetManager->publish(Yii::getPathOfAlias("application.documentation.guide.source.images"));
			$content = preg_replace('/<p>\s*<img(.*?)src="(.*?)"\s+alt="(.*?)"\s*\/>\s*<\/p>/',
				"<div class=\"image\"><p>\\3</p><img\\1src=\"$imageUrl/\\2\" alt=\"\\3\" /></div>",$content);

			$content = preg_replace_callback('/href="\/doc\/guide\/(.*?)\/?"/',array($this,'replaceGuideLink'),$content);
			$content = preg_replace('/href="(\/doc\/api\/.*?)"/','href="http://www.yiiframework.com$1" target="_blank"',$content);
			
			Yii::app()->cache->get('docbyid_'.$model->id, $content, 3600);
		}

		// Add title
		$this->pageTitle[] = Yii::t('documentation', 'Complete Documentation');
		$this->breadcrumbs[ Yii::t('docs', 'Complete Documentation') ] = '';
		
		// Add to the title stack if this page is not the index page
		if($topic!=='index' && preg_match('/<h1[^>]*>(.*?)</',$content,$matches))
		{
			$this->pageTitle[] = CHtml::encode($matches[1]);
			$this->breadcrumbs[ Yii::t('docs', CHtml::encode($matches[1])) ] = '';
		}
		
		// What we do here is use robots meta tag to prevent from search engines 
		// indexing and crawling through the page of the guide while it's viewed in English
		// Since this is not an original content search engines will not like this
		if( Yii::app()->language == 'en' || $model->language == 'source' )
		{
			Yii::app()->clientScript->registerMetaTag( 'noindex, nofollow', 'robots' );
		}
		
		$commentsModel = new DocumentationComments;
		
		if( Yii::app()->user->checkAccess('op_doc_add_comments') )
		{
			if( isset($_POST['DocumentationComments']) )
			{
				$commentsModel->attributes = $_POST['DocumentationComments'];
				$commentsModel->docid = $model->id;
				$commentsModel->visible = Yii::app()->user->checkAccess('op_doc_add_comments') ? 1 : 0;
				if( $commentsModel->save() )
				{
					Yii::app()->user->setFlash('success', Yii::t('docs', 'Comment Added.'));
					$commentsModel = new DocumentationComments;
				}
			}
		}
		
		// Grab the language data
		$criteria = new CDbCriteria;
		$criteria->condition = 'docid=:docid AND (visible=:visible OR visible=:mod)';
		$criteria->params = array( ':docid' => $model->id, ':visible' => 1, ':mod' => Yii::app()->user->checkAccess('op_doc_manage_comments') ? 0 : 1 );
		$criteria->order = 'postdate DESC';

		$totalcomments = DocumentationComments::model()->count($criteria);
		$pages = new CPagination($totalcomments);
		$pages->pageSize = self::PAGE_SIZE;
		
		$pages->applyLimit($criteria);
		
		// Grab comments
		$comments = DocumentationComments::model()->orderDate()->findAll($criteria);
		
		// Render
		$this->render('view',array('content'=>$content, 'model' => $model, 'pages' => $pages, 'markdown' => $markdown, 'type'=>'guide', 'commentsModel' => $commentsModel, 'totalcomments' => $totalcomments, 'comments'=>$comments));
	}
	
	/**
	 * Change comment visibility status
	 */
	public function actiontogglestatus()
	{
		if( !Yii::app()->user->checkAccess('op_doc_manage_comments')  )
		{
			$this->redirect( Yii::app()->request->getUrlReferrer() );
		}
		
		if( isset($_GET['id']) && ( $model = DocumentationComments::model()->findByPk($_GET['id']) ) )
		{			
			$model->visible = $model->visible == 1 ? 0 : 1;
			$model->save();
			
			Yii::app()->user->setFlash('success', Yii::t('docs', 'Comment Updated.'));
			$this->redirect( Yii::app()->request->getUrlReferrer() );
		}
		else
		{
			$this->redirect( Yii::app()->request->getUrlReferrer() );
		}
	}
	
	/**
	 * Controller blog action
	 * By default displays the documentation from the protected/documentation/blog
	 * folder based on the language currently viewed
	 */
	public function actionblog()
	{
		$topic = $this->getTopic('blog');
		$model = Documentation::model()->find('mkey=:mkey AND type=:type AND language=:lang', array( ':type' => 'blog', ':mkey' => $topic, ':lang' => $this->language ));
		if( !$model )
		{
			$model = Documentation::model()->find('mkey=:mkey AND type=:type AND language=:lang', array( ':type' => 'blog', ':mkey' => $topic, ':lang' => 'source' ));
		}
		if( !strcasecmp($topic,'toc') || !$model )
		{
			// @todo Fix this
			throw new CHttpException(404,'The page you looked for does not exist.');
		}
		
		// Update views
		$model->views++;
		$model->save();
		
		// Cache parsed output
		if( ( $content = Yii::app()->cache->get('docbyid_'.$model->id) ) === false )
		{
			// Grab file contents and parse them
			$content = $model->content;
			$markdown = new MarkdownParser;
			$content = $markdown->safeTransform($content);
		}

		// Manually convert items such as images, doc api links and guide links
		$imageUrl = Yii::app()->assetManager->publish(Yii::getPathOfAlias("application.documentation.blog.source.images"));
		$content = preg_replace('/<p>\s*<img(.*?)src="(.*?)"\s+alt="(.*?)"\s*\/>\s*<\/p>/',
			"<div class=\"image\"><p>\\3</p><img\\1src=\"$imageUrl/\\2\" alt=\"\\3\" /></div>",$content);

		$content = preg_replace_callback('/href="\/doc\/blog\/(.*?)\/?"/',array($this,'replaceBlogLink'),$content);
		$content = preg_replace('/href="(\/doc\/api\/.*?)"/','href="http://www.yiiframework.com$1"',$content);

		// Add title
		$this->pageTitle[] = Yii::t('documentation', 'The Blog Tutorial');
		$this->breadcrumbs[ Yii::t('documentation', 'The Blog Tutorial') ] = '';
		
		// Add to the title stack if this page is not the index page
		if($topic!=='index' && preg_match('/<h1[^>]*>(.*?)</',$content,$matches))
		{
			$this->pageTitle[] = CHtml::encode($matches[1]);
			$this->breadcrumbs[ Yii::t('docs', CHtml::encode($matches[1])) ] = '';
		}
		
		// What we do here is use robots meta tag to prevent from search engines 
		// indexing and crawling through the page of the guide while it's viewed in English
		// Since this is not an original content search engines will not like this
		if( Yii::app()->language == 'en' || $model->language == 'source' )
		{
			Yii::app()->clientScript->registerMetaTag( 'noindex, nofollow', 'robots' );
		}
		
		$commentsModel = new DocumentationComments;
		
		if( Yii::app()->user->checkAccess('op_doc_add_comments') )
		{
			if( isset($_POST['DocumentationComments']) )
			{
				$commentsModel->attributes = $_POST['DocumentationComments'];
				$commentsModel->docid = $model->id;
				$commentsModel->visible = Yii::app()->user->checkAccess('op_doc_add_comments') ? 1 : 0;
				if( $commentsModel->save() )
				{
					Yii::app()->user->setFlash('success', Yii::t('docs', 'Comment Added.'));
					$commentsModel = new DocumentationComments;
				}
			}
		}
		
		// Grab the language data
		$criteria = new CDbCriteria;
		$criteria->condition = 'docid=:docid';
		$criteria->params = array( ':docid' => $model->id );
		$criteria->order = 'postdate DESC';
		
		$totalcomments = DocumentationComments::model()->count($criteria);
		$pages = new CPagination($totalcomments);
		$pages->pageSize = self::PAGE_SIZE;
		
		$pages->applyLimit($criteria);
		
		// Grab comments
		$comments = DocumentationComments::model()->orderDate()->findAll($criteria);
		
		// Render
		$this->render('view',array('content'=>$content, 'model' => $model, 'pages' => $pages, 'markdown' => $markdown, 'type'=>'blog', 'commentsModel' => $commentsModel, 'totalcomments' => $totalcomments, 'comments'=>$comments));
	}
	
	/**
	 * Rate a topic action
	 */
	public function actionrating()
	{
		// Accept only post requests
		if( Yii::app()->request->isPostRequest )
		{
			$rating = intval( $_POST['rate'] );
			$id = intval( $_POST['id'] );
			
			$model = Documentation::model()->findByPk($id);
			
			if( $model )
			{
				$model->totalvotes++;
				$model->rating = $model->rating + $rating;
				$model->save();
				
				echo $model->rating;
				Yii::app()->end();
			}
		}
	}
	
	/**
	 * Markit up ajax parser callback
	 */
	public function actionparser()
	{
		// Grab file contents and parse them
		$content = $_POST['dontvalidate'];
		$markdown = new MarkdownParser;
		$content = $markdown->safeTransform($content);
		
		$this->layout = false;
		
		echo $this->render( 'parser', array( 'content' => $content ), true );
		
		Yii::app()->end();
	}

	/**
	 * internal function to replace guide links
	 */
	protected function replaceGuideLink($matches)
	{
		if(($pos=strpos($matches[1],'#'))!==false)
		{
			$anchor=substr($matches[1],$pos);
			$matches[1]=substr($matches[1],0,$pos);
		}
		else
		{
			$anchor='';
		}
		return 'href="'.$this->createUrl('guide',array('topic'=>$matches[1])).$anchor.'"';
	}
	
	/**
	 * internal function to replace guide links
	 */
	protected function replaceBlogLink($matches)
	{
		if(($pos=strpos($matches[1],'#'))!==false)
		{
			$anchor=substr($matches[1],$pos);
			$matches[1]=substr($matches[1],0,$pos);
		}
		else
		{
			$anchor='';
		}
		return 'href="'.$this->createUrl('blog',array('topic'=>$matches[1])).$anchor.'"';
	}

	/**
	 * Internal function to get the currently (trying) viewed topic
	 */
	public function getTopic($type='guide')
	{
		if(!isset($_GET['topic']) || empty($_GET['topic']))
			return $type == 'guide' ? 'index' : 'start.overview';
		else
			return str_replace(array('/','\\'),'',trim($_GET['topic']));
	}

	/**
	 * Grab all topics from the toc.txt file to display them as a list
	 */
	public function getTopics($type='guide')
	{
		if($this->_topics===null)
		{
			$model = Documentation::model()->find('mkey=:mkey AND type=:type AND language=:lang', array( ':type' => $type, ':mkey' => 'toc', ':lang' => $this->language ));

			if(!$model)
			{
				$model = Documentation::model()->find('mkey=:mkey AND type=:type AND language=:lang', array( ':type' => $type, ':mkey' => 'toc', ':lang' => 'source' ));
			}
			$lines=explode("\n", $model->content);
			$chapter='';
			foreach($lines as $line)
			{
				if(($line=trim($line))==='')
					continue;
				if($line[0]==='*')
					$chapter=trim($line,'* ');
				else if($line[0]==='-' && preg_match('/\[(.*?)\]\((.*?)\)/',$line,$matches))
					$this->_topics[$chapter][$matches[2]]=$matches[1];
			}
		}
		return $this->_topics;
	}

	/**
	 * Grab the currently active language
	 */
	public function getLanguage()
	{
		if($this->_language===null)
		{
			if(isset($_GET['lang']) && preg_match('/^[a-z_]+$/',$_GET['lang']))
				$this->_language=$_GET['lang'];
			else
				$this->_language='en';
		}
		return $this->_language;
	}

	/**
	 * Get all active languages to display a list of 
	 * available languages to see the guide in them
	 */
	public function getLanguages($type='guide')
	{
		if($this->_languages===null)
		{
			$basePath=Yii::getPathOfAlias('application.documentation.'.$type);
			$dir=opendir($basePath);
			$this->_languages=array('en'=>'English');
			while(($file=readdir($dir))!==false)
			{
				if(!is_dir($basePath.DIRECTORY_SEPARATOR.$file) || $file==='.' || $file==='..' || $file==='source')
					continue;
				if(isset(Yii::app()->params['languages'][$file]))
					$this->_languages[$file]=Yii::app()->params['languages'][$file];
			}
			ksort($this->_languages);
		}
		return $this->_languages;
	}
}