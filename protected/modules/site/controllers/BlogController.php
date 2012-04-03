<?php
/**
 * Blog controller Home page
 */
class BlogController extends SiteBaseController {
	
	const PAGE_SIZE = 20;
	
	/**
	 * Controller constructor
	 */
    public function init()
    {
        parent::init();

		// Add page breadcrumb and title
		$this->pageTitle[] = Yii::t('blog', 'Blog');
		$this->breadcrumbs[ Yii::t('blog', 'Blog') ] = array('blog/index');
    }

	/**
	 * Index action
	 */
    public function actionIndex() {
		
		$posts = Blog::model()->grabPostsByCats( array_keys(BlogCats::model()->getCatsForMember()), self::PAGE_SIZE);
	
        $this->render('view_category', array( 'posts' => $posts['posts'], 'pages' => $posts['pages'] ));
    }

	/**
	 * Pending posts
	 */
	public function actionshowpending()
	{
		// Can we view hidden?
		if( !Yii::app()->user->checkAccess('op_blog_manage') )
		{
			throw new CHttpException(404, Yii::t('error', 'Sorry, We could not find that post.'));
		}
		
		// Grab the language data
		$criteria = new CDbCriteria;
		$criteria->condition = 'status=0';
		$criteria->order = 'postdate DESC';

		$total = Blog::model()->count($criteria);
		$pages = new CPagination($total);
		$pages->pageSize = self::PAGE_SIZE;

		$pages->applyLimit($criteria);

		// Grab comments
		$rows = Blog::model()->findAll($criteria);
		
		// Add page breadcrumb and title
		$this->pageTitle[] = Yii::t('blog', '{count} Pending Posts', array('{count}'=>$total));
		$this->breadcrumbs[ Yii::t('blog', '{count} Pending Posts', array('{count}'=>$total)) ] = '';

        $this->render('view_category', array( 'posts' => $rows, 'pages' => $pages ));
	}
	
	/**
	 * view my Posts
	 */
	public function actionshowmyposts()
	{		
		// Grab the language data
		$criteria = new CDbCriteria;
		$criteria->condition = 'authorid=:userid';
		$criteria->params = array(':userid'=>Yii::app()->user->id);
		$criteria->order = 'postdate DESC';

		$total = Blog::model()->count($criteria);
		$pages = new CPagination($total);
		$pages->pageSize = self::PAGE_SIZE;

		$pages->applyLimit($criteria);

		// Grab comments
		$rows = Blog::model()->findAll($criteria);
		
		// Add page breadcrumb and title
		$this->pageTitle[] = Yii::t('blog', 'My Posts');
		$this->breadcrumbs[ Yii::t('blog', 'My Posts') ] = '';

        $this->render('view_category', array( 'posts' => $rows, 'pages' => $pages ));
	}

	/**
	 * View a single category
	 */
	public function actionviewcategory()
	{
		if( isset($_GET['alias']) && ( $model = BlogCats::model()->find('alias=:alias', array(':alias'=> BlogCats::model()->getAlias( $_GET['alias'] ) )) ) )
		{
			// Can we view it?
			$cats = BlogCats::model()->getCatsForMember();
			
			if( !in_array( $model->id, array_keys( $cats ) ) )
			{
				throw new CHttpException(404, Yii::t('error', 'Sorry, We could not find that category.'));
			}

			$posts = Blog::model()->grabPostsByCats($model->id , 20);
			
			// Add in the meta keys and description if any
			if( $model->metadesc )
			{
				Yii::app()->clientScript->registerMetaTag( $model->metadesc, 'description' );
			}
			
			if( $model->metakeys )
			{
				Yii::app()->clientScript->registerMetaTag( $model->metakeys, 'keywords' );
			}
			
			//$model->alias = BlogCats::model()->getAlias( $model->alias );
			
			// Add page breadcrumb and title
			$this->pageTitle[] = $model->title;
			$this->breadcrumbs[ $model->title ] = '';

	        $this->render('view_category', array( 'model' => $model, 'posts' => $posts['posts'], 'pages' => $posts['pages'] ));
		}
		else
		{
			throw new CHttpException(404, Yii::t('error', 'Sorry, We could not find that category.'));
		}
	}
	
	/**
	 * View post
	 */
	public function actionviewpost()
	{
		if( isset($_GET['alias']) && ( $model = Blog::model()->with(array('category', 'author', 'comments', 'commentscount'))->find('t.alias=:alias', array(':alias'=> Blog::model()->getAlias( $_GET['alias'] ) )) ) )
		{
			// Can we view it?
			$cats = BlogCats::model()->getCatsForMember();

			if( !in_array( $model->catid, array_keys( $cats ) ) )
			{
				throw new CHttpException(404, Yii::t('error', 'Sorry, We could not find that post.'));
			}
			
			// Is it hidden?
			if( !$model->status )
			{
				// Can we view hidden?
				if( !Yii::app()->user->checkAccess('op_blog_manage') )
				{
					throw new CHttpException(404, Yii::t('error', 'Sorry, We could not find that post.'));
				}
			}
			
			// Update views
			$model->views++;
			$model->save();
			
			//$model->alias = Blog::model()->getAlias( $model->alias );

			// Cache parsed output
			if( ( $content = Yii::app()->cache->get('postid_'.$model->id) ) === false )
			{
				// Grab file contents and parse them
				$content = $model->content;
				$markdown = new MarkdownParser;
				$content = $markdown->safeTransform($content);

				$content = preg_replace_callback('/href="\/doc\/guide\/(.*?)\/?"/',array($this,'replaceGuideLink'),$content);
				$content = preg_replace('/href="(\/doc\/api\/.*?)"/','href="http://www.yiiframework.com$1" target="_blank"',$content);

				Yii::app()->cache->get('postid_'.$model->id, $content, 3600);
			}

			$category = BlogCats::model()->findByPk($model->catid);

			// Add in the meta keys and description if any
			if( $model->metadesc )
			{
				Yii::app()->clientScript->registerMetaTag( $model->metadesc, 'description' );
			}

			if( $model->metakeys )
			{
				Yii::app()->clientScript->registerMetaTag( $model->metakeys, 'keywords' );
			}
			
			$commentsModel = new BlogComments;
			
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
				if( isset($_POST['BlogComments']) )
				{
					$commentsModel->attributes = $_POST['BlogComments'];
					$commentsModel->postid = $model->id;
					$commentsModel->visible = $autoaddcomments ? 1 : 0;
					if( $commentsModel->save() )
					{
						Yii::app()->user->setFlash('success', Yii::t('blog', 'Comment Added.'));
						$commentsModel = new BlogComments;
					}
				}
			}

			// Grab the language data
			$criteria = new CDbCriteria;
			$criteria->condition = 'postid=:postid AND visible=:visible';
			$criteria->params = array( ':postid' => $model->id, ':visible' => 1 );
			$criteria->order = 'postdate DESC';

			// Load only approved
			if( Yii::app()->user->checkAccess('op_blog_comments')  )
			{
				$criteria->condition .= ' OR visible=0';
			}

			$totalcomments = BlogComments::model()->count($criteria);
			$pages = new CPagination($totalcomments);
			$pages->pageSize = self::PAGE_SIZE;

			$pages->applyLimit($criteria);

			// Grab comments
			$comments = BlogComments::model()->orderDate()->findAll($criteria);
			
			// Make sure we prepare it for the like button
			Yii::app()->clientScript->registerMetaTag( $model->title, 'og:title' );
			Yii::app()->clientScript->registerMetaTag( 'article', 'og:type' );
			Yii::app()->clientScript->registerMetaTag( Yii::app()->createAbsoluteUrl('/blog/view/'.$model->alias, array('lang'=>false)), 'og:url' );
			Yii::app()->clientScript->registerMetaTag( Yii::app()->request->getBaseUrl(true) . Yii::app()->themeManager->baseUrl . '/images/logo.png', 'og:image' );
			Yii::app()->clientScript->registerMetaTag( Yii::app()->name, 'og:site_name' );
			Yii::app()->clientScript->registerMetaTag( $model->description, 'og:description' );

			// Add page breadcrumb and title
			$this->pageTitle[] = $category->title;
			$this->breadcrumbs[ $category->title ] = array('/blog/category/' . $category->alias, 'lang'=>false);
			
			$this->pageTitle[] = $model->title;
			$this->breadcrumbs[ $model->title ] = '';
			
			// Load facebook
			Yii::import('ext.facebook.facebookLib');
			$facebook = new facebookLib(array( 'appId' => Yii::app()->params['facebookapikey'], 'secret' => Yii::app()->params['facebookapisecret'], 'cookie' => true, 'disableSSLCheck' => true ));

			$this->render('view_post',array( 'facebook' => $facebook, 'addcomments' => $addcomments, 'content'=>$content, 'model' => $model, 'pages' => $pages, 'markdown' => $markdown, 'commentsModel' => $commentsModel, 'totalcomments' => $totalcomments, 'comments'=>$comments));
		}
		else
		{
			throw new CHttpException(404, Yii::t('error', 'Sorry, We could not find that post.'));
		}
	}
	
	/**
	 * Are we allowed to add posts?
	 */
	public function actionaddpost()
	{
		if( !Yii::app()->user->checkAccess('op_blog_addposts') )
		{
			throw new CHttpException(403, Yii::t('error', 'Sorry, You are not allowed to perform that action.'));
		}
		
		$model = new Blog;
		
		if( isset($_POST['Blog']) )
		{
			$model->attributes = $_POST['Blog'];
			
			if( !Yii::app()->user->checkAccess('op_blog_manage') )
			{
				// Can we auto approve posts for this category?
				$model->status = 0;
				$cat = BlogCats::model()->findByPk($model->catid);
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
					Yii::app()->user->setFlash('success', Yii::t('blog', 'Post Added.'));
					$this->redirect(array('/blog/view/' . $model->alias, 'lang' => false ));
				}
				else
				{
					Yii::app()->user->setFlash('success', Yii::t('blog', 'Post Added. It will be displayed once approved.'));
					$this->redirect('blog/index');
				}
			}
		}
		
		// Grab cats that we can add posts to
		$cats = BlogCats::model()->getCatsForMember(null, 'add');

		// Make a category selection
		$categories = array();
		
		foreach($cats as $cat)
		{
			$categories[ $cat->id ] = $cat->title;
		}
		
		// Add page breadcrumb and title
		$this->pageTitle[] = Yii::t('blog', 'Adding Post');
		$this->breadcrumbs[ Yii::t('blog', 'Adding Post') ] = '';
		
		$this->render('post_form', array( 'model' => $model, 'label' => Yii::t('blog', 'Adding Post'), 'categories' => $categories ));
	}
	
	/**
	 * Are we allowed to edit posts?
	 */
	public function actioneditpost()
	{
		if( !Yii::app()->user->checkAccess('op_blog_editposts') )
		{
			throw new CHttpException(403, Yii::t('error', 'Sorry, You are not allowed to perform that action.'));
		}
		
		
		if( isset($_GET['id']) && ( $model = Blog::model()->findByPk( $_GET['id'] ) ) )
		{
			// Make sure the author or a manager edits the post
			if( !Blog::model()->canEditPost( $model ) )
			{
				throw new CHttpException(403, Yii::t('error', 'Sorry, You are not allowed to perform that action.'));
			}
			
			if( isset($_POST['Blog']) )
			{
				$model->attributes = $_POST['Blog'];
			
				if( !Yii::app()->user->checkAccess('op_blog_manage') )
				{
					// Can we auto approve posts for this category?
					$model->status = 0;
					$cat = BlogCats::model()->findByPk($model->catid);
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
						Yii::app()->user->setFlash('success', Yii::t('blog', 'Post Updated.'));
						$this->redirect(array('/blog/view/' . $model->alias, 'lang' => false ));
					}
					else
					{
						Yii::app()->user->setFlash('success', Yii::t('blog', 'Post Updated. It will be displayed once approved.'));
						$this->redirect('blog/index');
					}
				}
			}
		
			// Grab cats that we can add posts to
			$cats = BlogCats::model()->getCatsForMember(null, 'add');

			// Make a category selection
			$categories = array();
		
			foreach($cats as $cat)
			{
				$categories[ $cat->id ] = $cat->title;
			}
		
			// Add page breadcrumb and title
			$this->pageTitle[] = Yii::t('blog', 'Editing Post');
			$this->breadcrumbs[ Yii::t('blog', 'Editing Post') ] = '';
		
			$this->render('post_form', array( 'model' => $model, 'label' => Yii::t('blog', 'Editing Post'), 'categories' => $categories ));
		
		}
		else
		{
			throw new CHttpException(404, Yii::t('error', 'Sorry, We could not find that post.'));
		}
	}
	
	/**
	 * Change comment visibility status
	 */
	public function actiontogglestatus()
	{
		if( !Yii::app()->user->checkAccess('op_blog_comments')  )
		{
			$this->redirect( Yii::app()->request->getUrlReferrer() );
		}
		
		if( isset($_GET['id']) && ( $model = BlogComments::model()->findByPk($_GET['id']) ) )
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
	 * Approve un-approve post
	 */
	public function actiontogglepost()
	{
		if( !Yii::app()->user->checkAccess('op_blog_manage')  )
		{
			$this->redirect( Yii::app()->request->getUrlReferrer() );
		}
		
		if( isset($_GET['id']) && ( $model = Blog::model()->findByPk($_GET['id']) ) )
		{			
			$model->status = $model->status == 1 ? 0 : 1;
			$model->save();
			
			$msg = $model->status ? 'Post Approved' : 'Post UnApproved';
			
			Yii::app()->user->setFlash('success', Yii::t('global', Yii::t('blog', $msg)));
			$this->redirect( Yii::app()->request->getUrlReferrer() );
		}
		else
		{
			$this->redirect( Yii::app()->request->getUrlReferrer() );
		}
	}
	
	/**
	 * Rate a post action
	 */
	public function actionrating()
	{
		// Accept only post requests
		if( Yii::app()->request->isPostRequest )
		{
			$rating = intval( $_POST['rate'] );
			$id = intval( $_POST['id'] );
			
			$model = Blog::model()->findByPk($id);
			
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
	 * Download post as text
	 */
	public function actiontext()
	{
		if( isset($_GET['id']) && ( $model = Blog::model()->findByPk($_GET['id']) ) )
		{			
			Yii::app()->func->downloadAs( $model->title, $model->alias, $model->content );
		}
		else
		{
			$this->redirect( Yii::app()->request->getUrlReferrer() );
		}
	}
	
	/**
	 * Download post as pdf
	 */
	public function actionpdf()
	{
		if( isset($_GET['id']) && ( $model = Blog::model()->findByPk($_GET['id']) ) )
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
	 * Download post as pdf
	 */
	public function actionword()
	{
		if( isset($_GET['id']) && ( $model = Blog::model()->findByPk($_GET['id']) ) )
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
	 * Posts & Category RSS
	 */
	public function actionrss()
	{
		$criteria = new CDbCriteria;
		
		if( isset($_GET['id']) && ( $model = BlogCats::model()->findByPk($_GET['id']) ) )
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
		
		// Load some posts
		$criteria->order = 'postdate DESC';
		$criteria->limit = 50;
		$posts = Blog::model()->with(array('author'))->findAll($criteria);
		
		$markdown = new MarkdownParser;
		
		if( $posts )
		{
			foreach($posts as $r)
			{
				$r->content = $markdown->safeTransform($r->content);
				
				$rows[] = array(
						'title' => $r->title, 
						'link' => Yii::app()->createAbsoluteUrl('/blog/view/' . $r->alias, array('lang'=>false)),
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
						'title' => isset($model) ? $model->title : Yii::t('blog', 'Blog RSS Feed'), 
						'link' => isset($model) ? Yii::app()->createAbsoluteUrl('/blog/category/' . $model->alias, array('lang'=>false)) : Yii::app()->createAbsoluteUrl('blog', array('lang'=>false)),
						'charset' => Yii::app()->charset,
						'description' => isset($model) ? $model->description : Yii::t('blog', 'Blog'),
						'author' => Yii::app()->name,
					    'generator' => Yii::app()->name,
					    'language'  => Yii::app()->language,
					    'ttl'    => 10,
						'entries' => $rows
						);
		Yii::app()->func->displayRss($data);
	}
}