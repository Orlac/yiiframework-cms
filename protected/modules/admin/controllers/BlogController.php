<?php
/**
 * Blog controller Home page
 */
class BlogController extends AdminBaseController {
	/**
	 * Number of items per page
	 */
	const PAGE_SIZE = 50;
	/**
	 * init
	 */
	public function init()
	{
		parent::init();
		
		$this->breadcrumbs[ Yii::t('adminblog', 'Blog') ] = array('blog/index');
		$this->pageTitle[] = Yii::t('adminblog', 'Blog'); 
	}
	/**
	 * Index action
	 */
    public function actionIndex() {
	
		// Did we hit the submit button?
		if( isset( $_POST['submit'] ) && $_POST['submit'] )
		{
			// Perms
			if( !Yii::app()->user->checkAccess('op_blog_managecats') )
			{
				throw new CHttpException(403, Yii::t('error', 'Sorry, You don\'t have the required permissions to enter this section'));
			}
			
			if( isset($_POST['pos']) && count($_POST['pos']) )
			{
				foreach($_POST['pos'] as $id => $pos)
				{
					BlogCats::model()->updateByPk($id, array('position'=>$pos));
				}
				
				// Mark
				Yii::app()->user->setFlash('success', Yii::t('adminblog', 'Categories Reordered.'));
			}
		}
		
		$this->breadcrumbs[ Yii::t('adminblog', 'Categories') ] = '';
		$this->pageTitle[] = Yii::t('adminblog', 'Categories');
		
        $this->render('index');
    }

	/**
	 * Mark category as readonly or not
	 */
	public function actioncatreadonly()
	{
		// Perms
		if( !Yii::app()->user->checkAccess('op_blog_managecats') )
		{
			throw new CHttpException(403, Yii::t('error', 'Sorry, You don\'t have the required permissions to enter this section'));
		}
		
		if( isset($_GET['id']) && ( $model = BlogCats::model()->findByPk( $_GET['id'] ) ) )
		{
			$update = $model->readonly ? 0 : 1;
			$model->readonly = $update;
			$model->save();
			
			Yii::app()->user->setFlash('success', Yii::t('adminblog', 'Category status Updated.'));
			$this->redirect(array('index'));
		}
		else
		{
			Yii::app()->user->setFlash('error', Yii::t('adminblog', 'Category was not found.'));
			$this->redirect(array('index'));
		}
	}
	
	/**
	 * Add category action
	 */
	public function actionaddcategory()
	{
		// Perms
		if( !Yii::app()->user->checkAccess('op_blog_addcats') )
		{
			throw new CHttpException(403, Yii::t('error', 'Sorry, You don\'t have the required permissions to enter this section'));
		}
		
		$model = new BlogCats;
		
		if( isset($_POST['BlogCats']) )
		{
			$model->attributes = $_POST['BlogCats'];
			if( $model->save() )
			{
				Yii::app()->user->setFlash('success', Yii::t('adminblog', 'Category Added.'));
				$this->redirect(array('index'));
			}
		}
		
		// Adding sub?
		if( Yii::app()->request->getParam('parentid') )
		{
			$model->parentid = Yii::app()->request->getParam('parentid');
		}
		
		$roles = AuthItem::model()->findAll(array('order'=>'type DESC, name ASC'));
		$_roles = array();
		if( count($roles) )
		{
			foreach($roles as $role)
			{
				$_roles[ AuthItem::model()->types[ $role->type ] ][ $role->name ] = $role->name;
			}
		}
		
		// Parent list
		$parents = array();
		$parentlist = BlogCats::model()->getRootCats();
		if( count( $parentlist ) )
		{
			foreach($parentlist as $row)
			{
				$parents[ $row->id ] = $row->title;
			}
		}
	
		$this->breadcrumbs[ Yii::t('adminblog', 'Adding Category') ] = '';
		$this->pageTitle[] = Yii::t('adminblog', 'Adding Category');
		
		// Render
		$this->render('category_form', array('model'=>$model, 'parents' => $parents, 'roles' => $_roles, 'label'=>Yii::t('adminblog', 'Adding Category') ));
	}
	
	/**
	 * Edit category action
	 */
	public function actioneditcategory()
	{
		// Perms
		if( !Yii::app()->user->checkAccess('op_blog_editcats') )
		{
			throw new CHttpException(403, Yii::t('error', 'Sorry, You don\'t have the required permissions to enter this section'));
		}
		
		if( isset($_GET['id']) && ( $model = BlogCats::model()->findByPk( $_GET['id'] ) ) )
		{
			if( isset($_POST['BlogCats']) )
			{
				$model->attributes = $_POST['BlogCats'];
				if( $model->save() )
				{
					Yii::app()->user->setFlash('success', Yii::t('adminblog', 'Category Updated.'));
					$this->redirect(array('index'));
				}
			}
		
			$roles = AuthItem::model()->findAll(array('order'=>'type DESC, name ASC'));
			$_roles = array();
			if( count($roles) )
			{
				foreach($roles as $role)
				{
					$_roles[ AuthItem::model()->types[ $role->type ] ][ $role->name ] = $role->name;
				}
			}
		
			// Parent list
			$parents = array();
			$parentlist = BlogCats::model()->getRootCats();
			if( count( $parentlist ) )
			{
				foreach($parentlist as $row)
				{
					$parents[ $row->id ] = $row->title;
				}
			}
			
			// Parse language selections and perms
			$model->language = $model->language ? explode(',', $model->language) : $model->language;

			$model->viewperms = $model->viewperms ? explode(',', $model->viewperms) : $model->viewperms;
			$model->addpostsperms = $model->addpostsperms ? explode(',', $model->addpostsperms) : $model->addpostsperms;
			$model->addcommentsperms = $model->addcommentsperms ? explode(',', $model->addcommentsperms) : $model->addcommentsperms;
			$model->addfilesperms = $model->addfilesperms ? explode(',', $model->addfilesperms) : $model->addfilesperms;
			$model->autoaddperms = $model->autoaddperms ? explode(',', $model->autoaddperms) : $model->autoaddperms;
		
			$this->breadcrumbs[ Yii::t('adminblog', 'Editing Category') ] = '';
			$this->pageTitle[] = Yii::t('adminblog', 'Editing Category');
		
			// Render
			$this->render('category_form', array('model'=>$model, 'parents' => $parents, 'roles' => $_roles, 'label'=>Yii::t('adminblog', 'Editing Category') ));
		}
		else
		{
			Yii::app()->user->setFlash('error', Yii::t('adminblog', 'Category was not found.'));
			$this->redirect(array('index'));
		}
	}
	
	/**
	 * Delete category
	 */
	public function actiondeletecategory()
	{
		// Perms
		if( !Yii::app()->user->checkAccess('op_blog_deletecats') )
		{
			throw new CHttpException(403, Yii::t('error', 'Sorry, You don\'t have the required permissions to enter this section'));
		}
		
		if( isset($_GET['id']) && ( $model = BlogCats::model()->findByPk( $_GET['id'] ) ) )
		{
			// If we don't have any sub cats or blog then just go ahead and delete
			$posts = $model->posts;
			$childs = $model->childs;
			
			if( ( !count($posts) && !count($childs) ) )
			{
				$model->delete();
				Yii::app()->user->setFlash('success', Yii::t('adminblog', 'Category Deleted.'));
				$this->redirect(array('index'));
			}
			
			// Remove the category we are deleting and the ones beneth it
			$removecats = array();
			$removecats[] = $model->id;
			$subcats = BlogCats::model()->getRecursiveCats($model);
			if( count($subcats) )
			{
				foreach($subcats as $data)
				{
					$removecats[] = $data->id;
				}
			}
			
			// Parent list
			$parents = array();
			$parentlist = BlogCats::model()->getRootCats();
			if( count( $parentlist ) )
			{
				foreach($parentlist as $row)
				{
					if( in_array($row->id, $removecats) )
					{
						continue;
					}
					$parents[ $row->id ] = $row->title;
				}
			}
			
			// Did we submit the form?
			if( isset( $_POST['submit'] ) && $_POST['submit'] )
			{
				$movecatid = $_POST['catsmoveto'];
				$movetutid = $_POST['catsmovetuts'];
				
				// Category is invalid
				if( ( !in_array($movecatid, array_keys($parents)) || !in_array($movetutid, array_keys($parents)) ) )
				{
					Yii::app()->user->setFlash('error', Yii::t('adminblog', 'You must specify a valid category to move the items.'));
				}
				else
				{
					// Update cats
					BlogCats::model()->updateAll( array('parentid'=>$movecatid), 'parentid=:parent', array(':parent'=>$model->id) );
					
					// Update post
					Blog::model()->updateAll( array('catid'=>$movetutid), 'catid=:cat', array(':cat'=>$model->id) );
					
					// Delete cat
					$model->delete();
					
					Yii::app()->user->setFlash('success', Yii::t('adminblog', 'Category Deleted.'));
					$this->redirect(array('index'));
				}
				
			}
			
			$this->breadcrumbs[ Yii::t('adminblog', 'Delete Category') ] = '';
			$this->pageTitle[] = Yii::t('adminblog', 'Delete Category');
			
			// Show render
			$this->render('delete_form', array('model'=>$model, 'childs' => $childs, 'blog' => $blog, 'parents' => $parents, 'label'=>Yii::t('adminblog', 'Delete Category')));
		}
		else
		{
			//Yii::app()->user->setFlash('error', Yii::t('adminblog', 'Category was not found.'));
			$this->redirect(array('index'));
		}
	}
	
	/**
	 * view category action
	 */
    public function actionviewcategory() 
	{
		// Perms
		if( !Yii::app()->user->checkAccess('op_blog_manage') )
		{
			throw new CHttpException(403, Yii::t('error', 'Sorry, You don\'t have the required permissions to enter this section'));
		}
		
		if( isset($_GET['id']) && ( $model = BlogCats::model()->findByPk( $_GET['id'] ) ) )
		{
			// Did we submit the form and selected items?
			if( isset($_POST['bulkoperations']) && $_POST['bulkoperations'] != '' )
			{			
				// Perms
				if( !Yii::app()->user->checkAccess('op_blog_manage') )
				{
					throw new CHttpException(403, Yii::t('error', 'Sorry, You don\'t have the required permissions to enter this section'));
				}
				
				// Did we choose any values?
				if( isset($_POST['record']) && count($_POST['record']) )
				{
					// What operation we would like to do?
					switch( $_POST['bulkoperations'] )
					{					
						case 'bulkapprove':
						// Load records
						$records = Blog::model()->updateByPk(array_keys($_POST['record']), array('status'=>1));
						// Done
						Yii::app()->user->setFlash('success', Yii::t('adminblog', '{count} blog approved.', array('{count}'=>$records)));
						break;
					
						case 'bulkunapprove':
						// Load records
						$records = Blog::model()->updateByPk(array_keys($_POST['record']), array('status'=>0));
						// Done
						Yii::app()->user->setFlash('success', Yii::t('adminblog', '{count} blog Un-Approved.', array('{count}'=>$records)));
						break;
					
						default:
						// Nothing
						break;
					}
				}
			}

			// Load members and display
			$criteria = new CDbCriteria;
			$criteria->condition = 'catid=:cat';
			$criteria->params = array( ':cat' => $model->id );

			$count = Blog::model()->count($criteria);
			$pages = new CPagination($count);
			$pages->pageSize = self::PAGE_SIZE;
		
			$pages->applyLimit($criteria);
		
			$sort = new CSort('Blog');
			$sort->defaultOrder = 'postdate DESC';
			$sort->applyOrder($criteria);

			$sort->attributes = array(
			        'title'=>'title',
			        'alias'=>'alias',
					'author'=>'authorid',
			        'postdate'=>'postdate',
			        'language'=>'language',
					'status'=>'status',
			);
		
			$rows = Blog::model()->with(array('author','lastauthor'))->findAll($criteria);
			
			// Add breadcrumbs and title
			$this->breadcrumbs[ Yii::t('adminblog', 'Viewing Category') ] = '';
			$this->pageTitle[] = Yii::t('adminblog', 'Viewing Category');
	
        	$this->render('posts', array( 'model' => $model, 'count' => $count, 'rows' => $rows, 'pages' => $pages, 'sort' => $sort ) );
		}
		else
		{
			Yii::app()->user->setFlash('error', Yii::t('adminblog', 'Category was not found.'));
			$this->redirect(array('index'));
		}	
    }

	/**
	 * Add post action
	 */
	public function actionaddpost()
	{
		// Perms
		if( !Yii::app()->user->checkAccess('op_blog_addposts') )
		{
			throw new CHttpException(403, Yii::t('error', 'Sorry, You don\'t have the required permissions to enter this section'));
		}
		
		$model = new Blog;
		
		if( isset($_POST['Blog']) )
		{
			$model->attributes = $_POST['Blog'];
			if( $model->save() )
			{
				Yii::app()->user->setFlash('success', Yii::t('adminblog', 'Post Added.'));
				$this->redirect(array('viewcategory', 'id'=>$model->catid));
			}
		}
		
		// Adding by cat?
		if( Yii::app()->request->getParam('catid') )
		{
			$model->catid = Yii::app()->request->getParam('catid');
		}
		
		// cat list
		$parents = array();
		$parentlist = BlogCats::model()->getRootCats();
		if( count( $parentlist ) )
		{
			foreach($parentlist as $row)
			{
				$parents[ $row->id ] = $row->title;
			}
		}
	
		$this->breadcrumbs[ Yii::t('adminblog', 'Adding Post') ] = '';
		$this->pageTitle[] = Yii::t('adminblog', 'Adding Post');
		
		// Render
		$this->render('post_form', array('model'=>$model, 'parents' => $parents, 'label'=>Yii::t('adminblog', 'Adding Post') ));
	}
	
	/**
	 * edit post action
	 */
	public function actioneditpost()
	{
		// Perms
		if( !Yii::app()->user->checkAccess('op_blog_editposts') )
		{
			throw new CHttpException(403, Yii::t('error', 'Sorry, You don\'t have the required permissions to enter this section'));
		}
		
		if( isset($_GET['id']) && ( $model = Blog::model()->findByPk( $_GET['id'] ) ) )
		{
			if( isset($_POST['Blog']) )
			{
				$model->attributes = $_POST['Blog'];
				if( $model->save() )
				{
					Yii::app()->user->setFlash('success', Yii::t('adminblog', 'Post Updated.'));
					$this->redirect(array('viewcategory', 'id'=>$model->catid));
				}
			}
		
			// cat list
			$parents = array();
			$parentlist = BlogCats::model()->getRootCats();
			if( count( $parentlist ) )
			{
				foreach($parentlist as $row)
				{
					$parents[ $row->id ] = $row->title;
				}
			}
	
			// language
			$model->language = !is_array($model->language) ? explode(',', $model->language) : $model->language;
	
			$this->breadcrumbs[ Yii::t('adminblog', 'Editing Post') ] = '';
			$this->pageTitle[] = Yii::t('adminblog', 'Editing Post');
		
			// Render
			$this->render('post_form', array('model'=>$model, 'parents' => $parents, 'label'=>Yii::t('adminblog', 'Editing Post') ));
		}
		else
		{
			Yii::app()->user->setFlash('error', Yii::t('adminblog', 'Post was not found.'));
			$this->redirect(array('index'));
		}
	}
	
	/**
	 * Toggle post status
	 */
	public function actiontogglepost()
	{
		// Perms
		if( !Yii::app()->user->checkAccess('op_blog_manage') )
		{
			throw new CHttpException(403, Yii::t('error', 'Sorry, You don\'t have the required permissions to enter this section'));
		}
		
		if( isset($_GET['id']) && ( $model = Blog::model()->findByPk( $_GET['id'] ) ) )
		{
			$update = $model->status ? 0 : 1;
			$model->status = $update;
			$model->save();
			
			Yii::app()->user->setFlash('success', Yii::t('adminblog', 'Post status Updated.'));
			$this->redirect(array('viewcategory', 'id'=>$model->catid));
		}
		else
		{
			Yii::app()->user->setFlash('error', Yii::t('adminblog', 'Post was not found.'));
			$this->redirect(array('index'));
		}
	}
	
	/**
	 * Delete post action
	 */
	public function actiondeletepost()
	{
		// Perms
		if( !Yii::app()->user->checkAccess('op_blog_deleteposts') )
		{
			throw new CHttpException(403, Yii::t('error', 'Sorry, You don\'t have the required permissions to enter this section'));
		}
		
		if( isset($_GET['id']) && ( $model = Blog::model()->findByPk($_GET['id']) ) )
		{			
			$catid = $model->catid;
			
			$model->delete();
			
			Yii::app()->user->setFlash('success', Yii::t('adminblog', 'Post Deleted.'));
			$this->redirect(array('viewcategory', 'id'=>$catid));
		}
		else
		{
			$this->redirect(array('index'));
		}
	}
	
	/**
	 * Manage comments
	 */
	public function actioncomments()
	{
		// Perms
		if( !Yii::app()->user->checkAccess('op_blog_comments') )
		{
			throw new CHttpException(403, Yii::t('error', 'Sorry, You don\'t have the required permissions to enter this section'));
		}
		
		// Did we submit the form and selected items?
		if( isset($_POST['bulkoperations']) && $_POST['bulkoperations'] != '' )
		{
			// Did we choose any values?
			if( isset($_POST['comment']) && count($_POST['comment']) )
			{
				// What operation we would like to do?
				switch( $_POST['bulkoperations'] )
				{
					case 'bulkdelete':
					
					// Perms
					if( !Yii::app()->user->checkAccess('op_blog_deletecomments') )
					{
						throw new CHttpException(403, Yii::t('error', 'Sorry, You don\'t have the required permissions to enter this section'));
					}
					
					// Load comments and delete them
					$comments_deleted = BlogComments::model()->deleteByPk(array_keys($_POST['comment']));
					// Done
					Yii::app()->user->setFlash('success', Yii::t('adminblog', '{count} comments deleted.', array('{count}'=>$comments_deleted)));
					break;
					
					case 'bulkapprove':
					// Load comments
					$comments = BlogComments::model()->updateByPk(array_keys($_POST['comment']), array('visible'=>1));
					// Done
					Yii::app()->user->setFlash('success', Yii::t('adminblog', '{count} comments approved.', array('{count}'=>$comments)));
					break;
					
					case 'bulkunapprove':
					// Load comments
					$comments = BlogComments::model()->updateByPk(array_keys($_POST['comment']), array('visible'=>0));
					// Done
					Yii::app()->user->setFlash('success', Yii::t('adminblog', '{count} comments Un-Approved.', array('{count}'=>$comments)));
					break;
					
					default:
					// Nothing
					break;
				}
			}
		}
		
		// Grab the language data
		$criteria = new CDbCriteria;
		
		$count = BlogComments::model()->count($criteria);
		$pages = new CPagination($count);
		$pages->pageSize = self::PAGE_SIZE;
		
		$pages->applyLimit($criteria);
		
		$sort = new CSort('BlogComments');
		
		$sort->defaultOrder = 'postdate DESC';
		$sort->applyOrder($criteria);
		$sort->attributes = array(
		        'tid' => 't.id',
				'authorid' => 'authorid',
				'postdate' => 'postdate',
				'visible' => 'visible',
		);
		
		$comments = BlogComments::model()->with(array('author'))->findAll($criteria);
		
		$this->breadcrumbs[ Yii::t('adminblog', 'Manage Comments') ] = array('blog/comments');
		$this->pageTitle[] = Yii::t('adminblog', 'Manage Comments');
		
		$this->render('comments', array( 'comments' => $comments, 'sort'=>$sort, 'pages'=>$pages, 'count' => $count ));
	}
	
	/**
	 * Change comment visibility status
	 */
	public function actiontogglecommentstatus()
	{
		// Perms
		if( !Yii::app()->user->checkAccess('op_blog_comments') )
		{
			throw new CHttpException(403, Yii::t('error', 'Sorry, You don\'t have the required permissions to enter this section'));
		}
		
		if( isset($_GET['id']) && ( $model = BlogComments::model()->findByPk($_GET['id']) ) )
		{			
			$model->visible = $model->visible == 1 ? 0 : 1;
			$model->save();
			
			Yii::app()->user->setFlash('success', Yii::t('adminblog', 'Comment Updated.'));
			$this->redirect(array('comments'));
		}
		else
		{
			$this->redirect(array('comments'));
		}
	}
	
	/**
	 * Delete comment action
	 */
	public function actiondeletecomment()
	{
		// Perms
		if( !Yii::app()->user->checkAccess('op_blog_deletecomments') )
		{
			throw new CHttpException(403, Yii::t('error', 'Sorry, You don\'t have the required permissions to enter this section'));
		}
		
		if( isset($_GET['id']) && ( $model = BlogComments::model()->findByPk($_GET['id']) ) )
		{			
			$model->delete();
			
			Yii::app()->user->setFlash('success', Yii::t('adminblog', 'Comment Deleted.'));
			$this->redirect(array('comments'));
		}
		else
		{
			$this->redirect(array('comments'));
		}
	}
}