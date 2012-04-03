<?php
/**
 * Extensions controller Home page
 */
class ExtensionsController extends SiteBaseController {
	
	const PAGE_SIZE = 20;
	
	/**
	 * Controller constructor
	 */
    public function init()
    {
        parent::init();

		// Add page breadcrumb and title
		$this->pageTitle[] = Yii::t('extensions', 'Extensions');
		$this->breadcrumbs[ Yii::t('extensions', 'Extensions') ] = array('extensions/index');
    }

	/**
	 * Index action
	 */
    public function actionIndex() {
		
		$posts = Extensions::model()->grabPostsByCats( array_keys(ExtensionsCats::model()->getCatsForMember()), self::PAGE_SIZE);
	
        $this->render('view_category', array( 'posts' => $posts['posts'], 'pages' => $posts['pages'] ));
    }

	/**
	 * Pending posts
	 */
	public function actionshowpending()
	{
		// Can we view hidden?
		if( !Yii::app()->user->checkAccess('op_extensions_manage') )
		{
			throw new CHttpException(404, Yii::t('error', 'Sorry, We could not find that extension.'));
		}
		
		// Grab the language data
		$criteria = new CDbCriteria;
		$criteria->condition = 'status=0';
		$criteria->order = 'postdate DESC';

		$total = Extensions::model()->count($criteria);
		$pages = new CPagination($total);
		$pages->pageSize = self::PAGE_SIZE;

		$pages->applyLimit($criteria);

		// Grab comments
		$rows = Extensions::model()->findAll($criteria);
		
		// Add page breadcrumb and title
		$this->pageTitle[] = Yii::t('extensions', '{count} Pending Extensions', array('{count}'=>$total));
		$this->breadcrumbs[ Yii::t('extensions', '{count} Pending Extensions', array('{count}'=>$total)) ] = '';

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

		$total = Extensions::model()->count($criteria);
		$pages = new CPagination($total);
		$pages->pageSize = self::PAGE_SIZE;

		$pages->applyLimit($criteria);

		// Grab comments
		$rows = Extensions::model()->findAll($criteria);
		
		// Add page breadcrumb and title
		$this->pageTitle[] = Yii::t('extensions', 'My Extensions');
		$this->breadcrumbs[ Yii::t('extensions', 'My Extensions') ] = '';

        $this->render('view_category', array( 'posts' => $rows, 'pages' => $pages ));
	}

	/**
	 * View a single category
	 */
	public function actionviewcategory()
	{
		if( isset($_GET['alias']) && ( $model = ExtensionsCats::model()->find('alias=:alias', array(':alias'=> ExtensionsCats::model()->getAlias( $_GET['alias'] ) )) ) )
		{
			// Can we view it?
			$cats = ExtensionsCats::model()->getCatsForMember();
			
			if( !in_array( $model->id, array_keys( $cats ) ) )
			{
				throw new CHttpException(404, Yii::t('error', 'Sorry, We could not find that category.'));
			}

			$posts = Extensions::model()->grabPostsByCats($model->id , 20);
			
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
		if( isset($_GET['alias']) && ( $model = Extensions::model()->with(array('files', 'category', 'author', 'comments', 'commentscount'))->find('t.alias=:alias', array(':alias'=> Extensions::model()->getAlias( $_GET['alias'] ) )) ) )
		{
			// Can we view it?
			$cats = ExtensionsCats::model()->getCatsForMember();

			if( !in_array( $model->catid, array_keys( $cats ) ) )
			{
				throw new CHttpException(404, Yii::t('error', 'Sorry, We could not find that extension.'));
			}
			
			// Is it hidden?
			if( !$model->status )
			{
				// Can we view hidden?
				if( !Yii::app()->user->checkAccess('op_extensions_manage') )
				{
					throw new CHttpException(404, Yii::t('error', 'Sorry, We could not find that extension.'));
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

			$category = ExtensionsCats::model()->findByPk($model->catid);

			// Add in the meta keys and description if any
			if( $model->metadesc )
			{
				Yii::app()->clientScript->registerMetaTag( $model->metadesc, 'description' );
			}

			if( $model->metakeys )
			{
				Yii::app()->clientScript->registerMetaTag( $model->metakeys, 'keywords' );
			}
			
			$commentsModel = new ExtensionsComments;
			
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
				if( isset($_POST['ExtensionsComments']) )
				{
					$commentsModel->attributes = $_POST['ExtensionsComments'];
					$commentsModel->postid = $model->id;
					$commentsModel->visible = $autoaddcomments ? 1 : 0;
					if( $commentsModel->save() )
					{
						Yii::app()->user->setFlash('success', Yii::t('extensions', 'Comment Added.'));
						$commentsModel = new ExtensionsComments;
					}
				}
			}

			// Grab the language data
			$criteria = new CDbCriteria;
			$criteria->condition = 'postid=:postid AND visible=:visible';
			$criteria->params = array( ':postid' => $model->id, ':visible' => 1 );
			$criteria->order = 'postdate DESC';

			// Load only approved
			if( Yii::app()->user->checkAccess('op_extensions_comments')  )
			{
				$criteria->condition .= ' OR visible=0';
			}

			$totalcomments = ExtensionsComments::model()->count($criteria);
			$pages = new CPagination($totalcomments);
			$pages->pageSize = self::PAGE_SIZE;

			$pages->applyLimit($criteria);

			// Grab comments
			$comments = ExtensionsComments::model()->orderDate()->findAll($criteria);
			
			// Make sure we prepare it for the like button
			Yii::app()->clientScript->registerMetaTag( $model->title, 'og:title' );
			Yii::app()->clientScript->registerMetaTag( 'article', 'og:type' );
			Yii::app()->clientScript->registerMetaTag( Yii::app()->createAbsoluteUrl('/extensions/view/'.$model->alias, array('lang'=>false)), 'og:url' );
			Yii::app()->clientScript->registerMetaTag( Yii::app()->request->getBaseUrl(true) . Yii::app()->themeManager->baseUrl . '/images/logo.png', 'og:image' );
			Yii::app()->clientScript->registerMetaTag( Yii::app()->name, 'og:site_name' );
			Yii::app()->clientScript->registerMetaTag( $model->description, 'og:description' );

			// Add page breadcrumb and title
			$this->pageTitle[] = $category->title;
			$this->breadcrumbs[ $category->title ] = array('/extensions/category/' . $category->alias, 'lang'=>false);
			
			$this->pageTitle[] = $model->title;
			$this->breadcrumbs[ $model->title ] = '';
			
			// Load facebook
			Yii::import('ext.facebook.facebookLib');
			$facebook = new facebookLib(array( 'appId' => Yii::app()->params['facebookapikey'], 'secret' => Yii::app()->params['facebookapisecret'], 'cookie' => true, 'disableSSLCheck' => true ));

			// Load the extensions files model
			$fileModel = new ExtensionsFiles;
			
			if( Extensions::model()->canEditPost( $model ) )
			{
				if( isset( $_POST['ExtensionsFiles'] ) )
				{
					$fileModel->attributes = $_POST['ExtensionsFiles'];
					$fileModel->realname = CUploadedFile::getInstance($fileModel,'realname');
					$fileModel->extensionid = isset($_POST['extensionid']) ? $_POST['extensionid'] : $model->id;
					
					$fileModel->size = $fileModel->realname->size;
					$fileModel->type = $fileModel->realname->extensionName;
					$fileModel->mime = $fileModel->realname->type;
					$fileModel->alias = $fileModel->getAlias( $fileModel->realname->name );
					
					if( $fileModel->save() )
					{
						// Update location
						$fileModel->location = $fileModel->getAlias( $fileModel->id . '_' . $fileModel->realname->name );
						$fileModel->update();
						
						// Upload File
						$fileModel->realname->saveAs( Yii::getPathOfAlias('webroot.uploads.extensions') . '/' . $fileModel->location, true );
						
						// Reset
						$fileModel = new ExtensionsFiles;
						
						Yii::app()->user->setFlash('success', Yii::t('extensions', 'Success! Your extension was uploaded.') );
						
						// Refresh the page
						$this->refresh();
					}
				}
			}	

			$this->render('view_post',array( 'fileModel' => $fileModel, 'facebook' => $facebook, 'addcomments' => $addcomments, 'content'=>$content, 'model' => $model, 'pages' => $pages, 'markdown' => $markdown, 'commentsModel' => $commentsModel, 'totalcomments' => $totalcomments, 'comments'=>$comments));
		}
		else
		{
			throw new CHttpException(404, Yii::t('error', 'Sorry, We could not find that extension.'));
		}
	}
	
	/**
	 * Download extension
	 */
	public function actionDownload()
	{
		if( isset($_GET['fileid']) && ( $model = ExtensionsFiles::model()->findByPk($_GET['fileid']) ) )
		{			
			$model->downloads += 1;
			$model->update();
			
			// Download a file
			header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header('Pragma: no-cache');
			header("Content-Type: ".$model->mime."");
			header("Content-Disposition: attachment; filename=\"".$model->realname ."\";");
		    header("Content-Length: ".$model->size);
			echo file_get_contents( Yii::getPathOfAlias('webroot.uploads.extensions') . '/' . $model->location );
			exit;
			
		}
		else
		{
			throw new CHttpException(404, Yii::t('error', 'Sorry, We could not find that extension.'));
		}
	}
	
	/**
	 * Delete an extension file
	 */
	public function actionDeleteFile()
	{	
		if( isset($_GET['id']) && ( $model = ExtensionsFiles::model()->findByPk($_GET['id']) ) )
		{	
			if( ( Yii::app()->user->checkAccess('op_extensions_manage') || Yii::app()->user->id == $model->authorid ) )
			{	
				// Remove from the server
				@unlink( Yii::getPathOfAlias('webroot.uploads.extensions') . '/' . $model->location );
				
				// Remove from the DB
				$model->delete();
			
				Yii::app()->user->setFlash('success', Yii::t('extensions', 'File Deleted.'));
				$this->redirect( Yii::app()->request->getUrlReferrer() );
			}
			else
			{
				$this->redirect( Yii::app()->request->getUrlReferrer() );
			}
		}
		else
		{
			$this->redirect( Yii::app()->request->getUrlReferrer() );
		}
	}
	
	/**
	 * Are we allowed to add posts?
	 */
	public function actionaddpost()
	{
		if( !Yii::app()->user->checkAccess('op_extensions_addposts') )
		{
			throw new CHttpException(403, Yii::t('error', 'Sorry, You are not allowed to perform that action.'));
		}
		
		$model = new Extensions;
		
		if( isset($_POST['Extensions']) )
		{
			$model->attributes = $_POST['Extensions'];
			
			if( !Yii::app()->user->checkAccess('op_extensions_manage') )
			{
				// Can we auto approve posts for this category?
				$model->status = 0;
				$cat = ExtensionsCats::model()->findByPk($model->catid);
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
					Yii::app()->user->setFlash('success', Yii::t('extensions', 'Extension Added.'));
					$this->redirect(array('/extensions/view/' . $model->alias, 'lang' => false ));
				}
				else
				{
					Yii::app()->user->setFlash('success', Yii::t('extensions', 'Extension Added. It will be displayed once approved.'));
					$this->redirect('extensions/index');
				}
			}
		}
		
		// Grab cats that we can add posts to
		$cats = ExtensionsCats::model()->getCatsForMember(null, 'add');

		// Make a category selection
		$categories = array();
		
		foreach($cats as $cat)
		{
			$categories[ $cat->id ] = $cat->title;
		}
		
		// Add page breadcrumb and title
		$this->pageTitle[] = Yii::t('extensions', 'Adding Extension');
		$this->breadcrumbs[ Yii::t('extensions', 'Adding Extension') ] = '';
		
		$this->render('post_form', array( 'model' => $model, 'label' => Yii::t('extensions', 'Adding Extension'), 'categories' => $categories ));
	}
	
	/**
	 * Are we allowed to edit posts?
	 */
	public function actioneditpost()
	{
		if( !Yii::app()->user->checkAccess('op_extensions_editposts') )
		{
			throw new CHttpException(403, Yii::t('error', 'Sorry, You are not allowed to perform that action.'));
		}
		
		if( isset($_GET['id']) && ( $model = Extensions::model()->findByPk( $_GET['id'] ) ) )
		{
			// Make sure the author or a manager edits the post
			if( !Extensions::model()->canEditPost( $model ) )
			{
				throw new CHttpException(403, Yii::t('error', 'Sorry, You are not allowed to perform that action.'));
			}
			
			if( isset($_POST['Extensions']) )
			{
				$model->attributes = $_POST['Extensions'];
			
				if( !Yii::app()->user->checkAccess('op_extensions_manage') )
				{
					// Can we auto approve posts for this category?
					$model->status = 0;
					$cat = ExtensionsCats::model()->findByPk($model->catid);
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
						Yii::app()->user->setFlash('success', Yii::t('extensions', 'Extension Updated.'));
						$this->redirect(array('/extensions/view/' . $model->alias, 'lang' => false ));
					}
					else
					{
						Yii::app()->user->setFlash('success', Yii::t('extensions', 'Extension Updated. It will be displayed once approved.'));
						$this->redirect('extensions/index');
					}
				}
			}
		
			// Grab cats that we can add posts to
			$cats = ExtensionsCats::model()->getCatsForMember(null, 'add');

			// Make a category selection
			$categories = array();
		
			foreach($cats as $cat)
			{
				$categories[ $cat->id ] = $cat->title;
			}
		
			// Add page breadcrumb and title
			$this->pageTitle[] = Yii::t('extensions', 'Editing Extension');
			$this->breadcrumbs[ Yii::t('extensions', 'Editing Extension') ] = '';
		
			$this->render('post_form', array( 'model' => $model, 'label' => Yii::t('extensions', 'Editing Extension'), 'categories' => $categories ));
		
		}
		else
		{
			throw new CHttpException(404, Yii::t('error', 'Sorry, We could not find that Extension.'));
		}
	}
	
	/**
	 * Change comment visibility status
	 */
	public function actiontogglestatus()
	{
		if( !Yii::app()->user->checkAccess('op_extensions_comments')  )
		{
			$this->redirect( Yii::app()->request->getUrlReferrer() );
		}
		
		if( isset($_GET['id']) && ( $model = ExtensionsComments::model()->findByPk($_GET['id']) ) )
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
		if( !Yii::app()->user->checkAccess('op_extensions_manage')  )
		{
			$this->redirect( Yii::app()->request->getUrlReferrer() );
		}
		
		if( isset($_GET['id']) && ( $model = Extensions::model()->findByPk($_GET['id']) ) )
		{			
			$model->status = $model->status == 1 ? 0 : 1;
			$model->save();
			
			$msg = $model->status ? 'Extension Approved' : 'Extension UnApproved';
			
			Yii::app()->user->setFlash('success', Yii::t('global', Yii::t('extensions', $msg)));
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
			
			$model = Extensions::model()->findByPk($id);
			
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
		if( isset($_GET['id']) && ( $model = Extensions::model()->findByPk($_GET['id']) ) )
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
		if( isset($_GET['id']) && ( $model = Extensions::model()->findByPk($_GET['id']) ) )
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
		if( isset($_GET['id']) && ( $model = Extensions::model()->findByPk($_GET['id']) ) )
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
		
		if( isset($_GET['id']) && ( $model = ExtensionsCats::model()->findByPk($_GET['id']) ) )
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
						'link' => Yii::app()->createAbsoluteUrl('/extensions/view/' . $r->alias, array('lang'=>false)),
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
						'title' => isset($model) ? $model->title : Yii::t('extensions', 'Extensions RSS Feed'), 
						'link' => isset($model) ? Yii::app()->createAbsoluteUrl('/extensions/category/' . $model->alias, array('lang'=>false)) : Yii::app()->createAbsoluteUrl('extensions', array('lang'=>false)),
						'charset' => Yii::app()->charset,
						'description' => isset($model) ? $model->description : Yii::t('extensions', 'Extensions'),
						'author' => Yii::app()->name,
					    'generator' => Yii::app()->name,
					    'language'  => Yii::app()->language,
					    'ttl'    => 10,
						'entries' => $rows
						);
		Yii::app()->func->displayRss($data);
	}
}