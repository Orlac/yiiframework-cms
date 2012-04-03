<?php
/**
 * Tutorials controller Home page
 */
class TutorialsController extends SiteBaseController {
	
	const PAGE_SIZE = 20;
	
	/**
	 * Controller constructor
	 */
    public function init()
    {
        parent::init();

		// Add page breadcrumb and title
		$this->pageTitle[] = Yii::t('tutorials', 'Tutorials');
		$this->breadcrumbs[ Yii::t('tutorials', 'Tutorials') ] = array('tutorials/index');
    }

	/**
	 * Index action
	 */
    public function actionIndex() {
		
		$tutorials = Tutorials::model()->grabTutorialsByCats( array_keys(TutorialsCats::model()->getCatsForMember()), self::PAGE_SIZE);
	
        $this->render('view_category', array( 'tutorials' => $tutorials['tutorials'], 'pages' => $tutorials['pages'] ));
    }

	/**
	 * Pending Tutorials
	 */
	public function actionshowpending()
	{
		// Can we view hidden?
		if( !Yii::app()->user->checkAccess('op_tutorials_manage') )
		{
			throw new CHttpException(404, Yii::t('error', 'Sorry, We could not find that tutorial.'));
		}
		
		// Grab the language data
		$criteria = new CDbCriteria;
		$criteria->condition = 'status=0';
		$criteria->order = 'postdate DESC';

		$total = Tutorials::model()->count($criteria);
		$pages = new CPagination($total);
		$pages->pageSize = self::PAGE_SIZE;

		$pages->applyLimit($criteria);

		// Grab comments
		$rows = Tutorials::model()->findAll($criteria);
		
		// Add page breadcrumb and title
		$this->pageTitle[] = Yii::t('tutorials', '{count} Pending Tutorials', array('{count}'=>$total));
		$this->breadcrumbs[ Yii::t('tutorials', '{count} Pending Tutorials', array('{count}'=>$total)) ] = '';

        $this->render('view_category', array( 'tutorials' => $rows, 'pages' => $pages ));
	}
	
	/**
	 * view my Tutorials
	 */
	public function actionshowmytutorials()
	{		
		// Grab the language data
		$criteria = new CDbCriteria;
		$criteria->condition = 'authorid=:userid';
		$criteria->params = array(':userid'=>Yii::app()->user->id);
		$criteria->order = 'postdate DESC';

		$total = Tutorials::model()->count($criteria);
		$pages = new CPagination($total);
		$pages->pageSize = self::PAGE_SIZE;

		$pages->applyLimit($criteria);

		// Grab comments
		$rows = Tutorials::model()->findAll($criteria);
		
		// Add page breadcrumb and title
		$this->pageTitle[] = Yii::t('tutorials', 'My Tutorials');
		$this->breadcrumbs[ Yii::t('tutorials', 'My Tutorials') ] = '';

        $this->render('view_category', array( 'tutorials' => $rows, 'pages' => $pages ));
	}

	/**
	 * View a single category
	 */
	public function actionviewcategory()
	{
		if( isset($_GET['alias']) && ( $model = TutorialsCats::model()->find('alias=:alias', array(':alias'=> TutorialsCats::model()->getAlias( $_GET['alias'] ) )) ) )
		{
			// Can we view it?
			$cats = TutorialsCats::model()->getCatsForMember();
			
			if( !in_array( $model->id, array_keys( $cats ) ) )
			{
				throw new CHttpException(404, Yii::t('error', 'Sorry, We could not find that category.'));
			}

			$tutorials = Tutorials::model()->grabTutorialsByCats($model->id , 20);
			
			// Add in the meta keys and description if any
			if( $model->metadesc )
			{
				Yii::app()->clientScript->registerMetaTag( $model->metadesc, 'description' );
			}
			
			if( $model->metakeys )
			{
				Yii::app()->clientScript->registerMetaTag( $model->metakeys, 'keywords' );
			}
			
			//$model->alias = TutorialsCats::model()->getAlias( $model->alias );
			
			// Add page breadcrumb and title
			$this->pageTitle[] = $model->title;
			$this->breadcrumbs[ $model->title ] = '';

	        $this->render('view_category', array( 'model' => $model, 'tutorials' => $tutorials['tutorials'], 'pages' => $tutorials['pages'] ));
		}
		else
		{
			throw new CHttpException(404, Yii::t('error', 'Sorry, We could not find that category.'));
		}
	}
	
	/**
	 * View tutorial
	 */
	public function actionviewtutorial()
	{
		if( isset($_GET['alias']) && ( $model = Tutorials::model()->with(array('category', 'author', 'comments', 'commentscount'))->find('t.alias=:alias', array(':alias'=> Tutorials::model()->getAlias( $_GET['alias'] ) )) ) )
		{
			// Can we view it?
			$cats = TutorialsCats::model()->getCatsForMember();
			
			// Make sure we can view tutorials in this category
			if( !in_array( $model->catid, array_keys( $cats ) ) )
			{
				throw new CHttpException(404, Yii::t('error', 'Sorry, We could not find that tutorial.'));
			}
			
			// Is it hidden?
			if( !$model->status )
			{
				// Are we the author of this tutorial?
				if( !Tutorials::model()->canEditTutorial( $model ) )
				{
					throw new CHttpException(404, Yii::t('error', 'Sorry, We could not find that tutorial.'));
				}
			}
			
			// Update views
			$model->views++;
			$model->save();
			
			//$model->alias = Tutorials::model()->getAlias( $model->alias );

			// Cache parsed output
			if( ( $content = Yii::app()->cache->get('tutorialid_'.$model->id) ) === false )
			{
				// Grab file contents and parse them
				$content = $model->content;
				$markdown = new MarkdownParser;
				$content = $markdown->safeTransform($content);

				$content = preg_replace_callback('/href="\/doc\/guide\/(.*?)\/?"/',array($this,'replaceGuideLink'),$content);
				$content = preg_replace('/href="(\/doc\/api\/.*?)"/','href="http://www.yiiframework.com$1" target="_blank"',$content);

				Yii::app()->cache->get('tutorialid_'.$model->id, $content, 3600);
			}

			$category = TutorialsCats::model()->findByPk($model->catid);

			// Add in the meta keys and description if any
			if( $model->metadesc )
			{
				Yii::app()->clientScript->registerMetaTag( $model->metadesc, 'description' );
			}

			if( $model->metakeys )
			{
				Yii::app()->clientScript->registerMetaTag( $model->metakeys, 'keywords' );
			}
			
			$commentsModel = new TutorialsComments;
			
			// Can add comments?
			$addcomments = false;
			$autoaddcomments = false;
			if( $category->addcommentsperms )
			{
				$perms = explode(',', $category->addcommentsperms);
				
				foreach($perms as $perm)
				{
					if( Yii::app()->user->checkAccess($perm) )
					{
						$addcomments = true;
						break;
					}
				}
			}
			else
			{
				$addcomments = true;
			}
			
			if( $category->autoaddperms )
			{
				$perms = explode(',', $category->autoaddperms);
				
				foreach($perms as $permc)
				{
					if( Yii::app()->user->checkAccess($permc) )
					{
						$autoaddcomments = true;
						break;
					}
				}
			}
			else
			{
				$autoaddcomments = true;
			}
			
			// Override to add comments to users by default
			if( Yii::app()->user->id )
			{
				$autoaddcomments = true;
			}			

			if( $addcomments )
			{
				if( isset($_POST['TutorialsComments']) )
				{
					$commentsModel->attributes = $_POST['TutorialsComments'];
					$commentsModel->tutorialid = $model->id;
					$commentsModel->visible = $autoaddcomments ? 1 : 0;
					if( $commentsModel->save() )
					{
						Yii::app()->user->setFlash('success', Yii::t('tutorials', 'Comment Added.'));
						$commentsModel = new TutorialsComments;
					}
				}
			}

			// Grab the language data
			$criteria = new CDbCriteria;
			$criteria->condition = 'tutorialid=:tutorialid AND visible=:visible';
			$criteria->params = array( ':tutorialid' => $model->id, ':visible' => 1 );
			$criteria->order = 'postdate DESC';

			// Load only approved
			if( Yii::app()->user->checkAccess('op_tutorials_comments')  )
			{
				$criteria->condition .= ' OR visible=0';
			}

			$totalcomments = TutorialsComments::model()->count($criteria);
			$pages = new CPagination($totalcomments);
			$pages->pageSize = self::PAGE_SIZE;

			$pages->applyLimit($criteria);

			// Grab comments
			$comments = TutorialsComments::model()->orderDate()->findAll($criteria);
			
			// Make sure we prepare it for the like button
			Yii::app()->clientScript->registerMetaTag( $model->title, 'og:title' );
			Yii::app()->clientScript->registerMetaTag( 'article', 'og:type' );
			Yii::app()->clientScript->registerMetaTag( Yii::app()->createAbsoluteUrl('/tutorials/view/'.$model->alias, array('lang'=>false)), 'og:url' );
			Yii::app()->clientScript->registerMetaTag( Yii::app()->request->getBaseUrl(true) . Yii::app()->themeManager->baseUrl . '/images/logo.png', 'og:image' );
			Yii::app()->clientScript->registerMetaTag( Yii::app()->name, 'og:site_name' );
			Yii::app()->clientScript->registerMetaTag( $model->description, 'og:description' );

			// Add page breadcrumb and title
			$this->pageTitle[] = $category->title;
			$this->breadcrumbs[ $category->title ] = array('/tutorials/category/' . $category->alias, 'lang'=>false);
			
			$this->pageTitle[] = $model->title;
			$this->breadcrumbs[ $model->title ] = '';
			
			// Load facebook
			Yii::import('ext.facebook.facebookLib');
			$facebook = new facebookLib(array( 'appId' => Yii::app()->params['facebookapikey'], 'secret' => Yii::app()->params['facebookapisecret'], 'cookie' => true, 'disableSSLCheck' => true ));

			$this->render('view_tutorial',array( 'facebook' => $facebook, 'addcomments' => $addcomments, 'content'=>$content, 'model' => $model, 'pages' => $pages, 'markdown' => $markdown, 'commentsModel' => $commentsModel, 'totalcomments' => $totalcomments, 'comments'=>$comments));
		}
		else
		{
			throw new CHttpException(404, Yii::t('error', 'Sorry, We could not find that tutorial.'));
		}
	}
	
	/**
	 * Are we allowed to add tutorials?
	 */
	public function actionaddtutorial()
	{
		if( !Yii::app()->user->checkAccess('op_tutorials_addtutorials') )
		{
			throw new CHttpException(403, Yii::t('error', 'Sorry, You are not allowed to perform that action.'));
		}
		
		$model = new Tutorials;
		
		if( isset($_POST['Tutorials']) )
		{
			$model->attributes = $_POST['Tutorials'];
			
			if( !Yii::app()->user->checkAccess('op_tutorials_manage') )
			{
				// Can we auto approve tutorials for this category?
				$model->status = 0;
				$cat = TutorialsCats::model()->findByPk($model->catid);
				if( $cat )
				{
					if( $cat->autoaddperms )
					{
						$perms = explode(',', $cat->autoaddperms);
						if( count($perms) )
						{
							foreach($perms as $perm)
							{
								if( Yii::app()->user->checkAccess($perm) )
								{
									$model->status = 1;
									break;
								}
							}
						}
					}
				}
			}
			
			if( $model->save() )
			{
				if( $model->status )
				{
					Yii::app()->user->setFlash('success', Yii::t('tutorials', 'Tutorial Added.'));
					$this->redirect(array('/tutorials/view/' . $model->alias, 'lang' => false ));
				}
				else
				{
					Yii::app()->user->setFlash('success', Yii::t('tutorials', 'Tutorial Added. It will be displayed once approved.'));
					$this->redirect('tutorials/index');
				}
			}
		}
		
		// Grab cats that we can add tutorials to
		$cats = TutorialsCats::model()->getCatsForMember(null, 'add');

		// Make a category selection
		$categories = array();
		
		foreach($cats as $cat)
		{
			$categories[ $cat->id ] = $cat->title;
		}
		
		// Add page breadcrumb and title
		$this->pageTitle[] = Yii::t('tutorials', 'Adding Tutorial');
		$this->breadcrumbs[ Yii::t('tutorials', 'Adding Tutorial') ] = '';
		
		$this->render('tutorial_form', array( 'model' => $model, 'label' => Yii::t('tutorials', 'Adding Tutorial'), 'categories' => $categories ));
	}
	
	/**
	 * Are we allowed to add tutorials?
	 */
	public function actionedittutorial()
	{
		if( isset($_GET['id']) && ( $model = Tutorials::model()->findByPk( $_GET['id'] ) ) )
		{
			// Make sure the author or a manager edits the tutorial
			if( !Tutorials::model()->canEditTutorial( $model ) )
			{
				throw new CHttpException(403, Yii::t('error', 'Sorry, You are not allowed to perform that action.'));
			}
			
			if( isset($_POST['Tutorials']) )
			{
				$model->attributes = $_POST['Tutorials'];
			
				if( !Yii::app()->user->checkAccess('op_tutorials_manage') )
				{
					// Can we auto approve tutorials for this category?
					$model->status = 0;
					$cat = TutorialsCats::model()->findByPk($model->catid);
					if( $cat )
					{
						if( $cat->autoaddperms )
						{
							$perms = explode(',', $cat->autoaddperms);
							if( count($perms) )
							{
								foreach($perms as $perm)
								{
									if( Yii::app()->user->checkAccess($perm) )
									{
										$model->status = 1;
										break;
									}
								}
							}
						}
					}
				}
			
				if( $model->save() )
				{
					if( $model->status )
					{
						Yii::app()->user->setFlash('success', Yii::t('tutorials', 'Tutorial Updated.'));
						$this->redirect(array('/tutorials/view/' . $model->alias, 'lang' => false ));
					}
					else
					{
						Yii::app()->user->setFlash('success', Yii::t('tutorials', 'Tutorial Updated. It will be displayed once approved.'));
						$this->redirect('tutorials/index');
					}
				}
			}
		
			// Grab cats that we can add tutorials to
			$cats = TutorialsCats::model()->getCatsForMember(null, 'add');

			// Make a category selection
			$categories = array();
		
			foreach($cats as $cat)
			{
				$categories[ $cat->id ] = $cat->title;
			}
		
			// Add page breadcrumb and title
			$this->pageTitle[] = Yii::t('tutorials', 'Editing Tutorial');
			$this->breadcrumbs[ Yii::t('tutorials', 'Editing Tutorial') ] = '';
		
			$this->render('tutorial_form', array( 'model' => $model, 'label' => Yii::t('tutorials', 'Editing Tutorial'), 'categories' => $categories ));
		
		}
		else
		{
			throw new CHttpException(404, Yii::t('error', 'Sorry, We could not find that tutorial.'));
		}
	}
	
	/**
	 * Change comment visibility status
	 */
	public function actiontogglestatus()
	{
		if( !Yii::app()->user->checkAccess('op_tutorials_comments')  )
		{
			$this->redirect( Yii::app()->request->getUrlReferrer() );
		}
		
		if( isset($_GET['id']) && ( $model = TutorialsComments::model()->findByPk($_GET['id']) ) )
		{			
			$model->visible = $model->visible == 1 ? 0 : 1;
			$model->save();
			
			Yii::app()->user->setFlash('success', Yii::t('global', 'Comment Updated.'));
			$this->redirect( Yii::app()->request->getUrlReferrer() );
		}
		else
		{
			$this->redirect( Yii::app()->request->getUrlReferrer() );
		}
	}
	
	/**
	 * Approve un approve tutorial
	 */
	public function actiontoggletutorial()
	{
		if( !Yii::app()->user->checkAccess('op_tutorials_manage')  )
		{
			$this->redirect( Yii::app()->request->getUrlReferrer() );
		}
		
		if( isset($_GET['id']) && ( $model = Tutorials::model()->findByPk($_GET['id']) ) )
		{			
			$model->status = $model->status == 1 ? 0 : 1;
			$model->save();
			
			$msg = $model->status ? 'Tutorial Approved' : 'Tutorial UnApproved';
			
			Yii::app()->user->setFlash('success', Yii::t('global', Yii::t('tutorials', $msg)));
			$this->redirect( Yii::app()->request->getUrlReferrer() );
		}
		else
		{
			$this->redirect( Yii::app()->request->getUrlReferrer() );
		}
	}
	
	/**
	 * Rate a tutorial action
	 */
	public function actionrating()
	{
		// Accept only post requests
		if( Yii::app()->request->isPostRequest )
		{
			$rating = intval( $_POST['rate'] );
			$id = intval( $_POST['id'] );
			
			$model = Tutorials::model()->findByPk($id);
			
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
	 * Download tutorial as text
	 */
	public function actiontext()
	{
		if( isset($_GET['id']) && ( $model = Tutorials::model()->findByPk($_GET['id']) ) )
		{			
			Yii::app()->func->downloadAs( $model->title, $model->alias, $model->content );
		}
		else
		{
			$this->redirect( Yii::app()->request->getUrlReferrer() );
		}
	}
	
	/**
	 * Download tutorial as pdf
	 */
	public function actionpdf()
	{
		if( isset($_GET['id']) && ( $model = Tutorials::model()->findByPk($_GET['id']) ) )
		{			
			$markdown = new MarkdownParser;
			$model->content = $markdown->safeTransform($model->content);
			$this->layout = false;
			$content = $this->render('index', array('content'=>$model->content), true);
			
			Yii::app()->func->downloadAs( $model->title, $model->alias, $model->content, 'pdf' );
		}
		else
		{
			$this->redirect( Yii::app()->request->getUrlReferrer() );
		}
	}
	
	/**
	 * Download tutorial as pdf
	 */
	public function actionword()
	{
		if( isset($_GET['id']) && ( $model = Tutorials::model()->findByPk($_GET['id']) ) )
		{			
			$markdown = new MarkdownParser;
			$model->content = $markdown->safeTransform($model->content);
			$this->layout = false;
			$content = $this->render('index', array('content'=>$model->content), true);
			
			Yii::app()->func->downloadAs( $model->title, $model->alias, $content, 'word' );
		}
		else
		{
			$this->redirect( Yii::app()->request->getUrlReferrer() );
		}
	}
	
	/**
	 * Tutorials & Category RSS
	 */
	public function actionrss()
	{
		$criteria = new CDbCriteria;
		
		if( isset($_GET['id']) && ( $model = TutorialsCats::model()->findByPk($_GET['id']) ) )
		{
			$criteria->condition = 'catid=:catid AND status=:status';
			$criteria->params = array( ':catid' => $model->id, ':status' => 1 );
		}
		else
		{
			$criteria->condition = 'status=:status';
			$criteria->params = array( ':status' => 1 );
		}
		
		$rows = array();
		
		// Load some tutorials
		$criteria->order = 'postdate DESC';
		$criteria->limit = 50;
		$tutorials = Tutorials::model()->with(array('author'))->findAll($criteria);
		
		$markdown = new MarkdownParser;
		
		if( $tutorials )
		{
			foreach($tutorials as $r)
			{
				$r->content = $markdown->safeTransform($r->content);
				
				$rows[] = array(
						'title' => $r->title, 
						'link' => Yii::app()->createAbsoluteUrl('/tutorials/view/' . $r->alias, array('lang'=>false)),
						'charset' => Yii::app()->charset,
						'description' => $r->description,
						'author' => $r->author ? $r->author->username : Yii::app()->name,
					    'generator' => Yii::app()->name,
					    'language'  => Yii::app()->language,
						'guid' => $r->id,
						'content' => $r->content,
					);
			}
		}
		
		$data = array(
						'title' => isset($model) ? $model->title : Yii::t('tutorials', 'Tutorials RSS Feed'), 
						'link' => isset($model) ? Yii::app()->createAbsoluteUrl('/tutorials/category/' . $model->alias, array('lang'=>false)) : Yii::app()->createAbsoluteUrl('tutorials', array('lang'=>false)),
						'charset' => Yii::app()->charset,
						'description' => isset($model) ? $model->description : Yii::t('tutorials', 'Tutorials'),
						'author' => Yii::app()->name,
					    'generator' => Yii::app()->name,
					    'language'  => Yii::app()->language,
					    'ttl'    => 10,
						'entries' => $rows
						);
		Yii::app()->func->displayRss($data);
	}
}