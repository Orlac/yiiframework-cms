<?php
/**
 * Extensions controller Home page
 */
class ExtensionsController extends AdminBaseController {
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
		
		$this->breadcrumbs[ Yii::t('extensions', 'Extensions') ] = array('extensions/index');
		$this->pageTitle[] = Yii::t('extensions', 'Extensions'); 
	}
	/**
	 * Index action
	 */
    public function actionIndex() {
	
		// Did we hit the submit button?
		if( isset( $_POST['submit'] ) && $_POST['submit'] )
		{
			// Perms
			if( !Yii::app()->user->checkAccess('op_extensions_managecats') )
			{
				throw new CHttpException(403, Yii::t('error', 'Sorry, You don\'t have the required permissions to enter this section'));
			}
			
			if( isset($_POST['pos']) && count($_POST['pos']) )
			{
				foreach($_POST['pos'] as $id => $pos)
				{
					ExtensionsCats::model()->updateByPk($id, array('position'=>$pos));
				}
				
				// Mark
				Yii::app()->user->setFlash('success', Yii::t('extensions', 'Categories Reordered.'));
			}
		}
		
		$this->breadcrumbs[ Yii::t('extensions', 'Categories') ] = '';
		$this->pageTitle[] = Yii::t('extensions', 'Categories');
		
        $this->render('index');
    }

	/**
	 * Mark category as readonly or not
	 */
	public function actioncatreadonly()
	{
		// Perms
		if( !Yii::app()->user->checkAccess('op_extensions_managecats') )
		{
			throw new CHttpException(403, Yii::t('error', 'Sorry, You don\'t have the required permissions to enter this section'));
		}
		
		if( isset($_GET['id']) && ( $model = ExtensionsCats::model()->findByPk( $_GET['id'] ) ) )
		{
			$update = $model->readonly ? 0 : 1;
			$model->readonly = $update;
			$model->save();
			
			Yii::app()->user->setFlash('success', Yii::t('extensions', 'Category status Updated.'));
			$this->redirect(array('index'));
		}
		else
		{
			Yii::app()->user->setFlash('error', Yii::t('extensions', 'Category was not found.'));
			$this->redirect(array('index'));
		}
	}
	
	/**
	 * Add category action
	 */
	public function actionaddcategory()
	{
		// Perms
		if( !Yii::app()->user->checkAccess('op_extensions_addcats') )
		{
			throw new CHttpException(403, Yii::t('error', 'Sorry, You don\'t have the required permissions to enter this section'));
		}
		
		$model = new ExtensionsCats;
		
		if( isset($_POST['ExtensionsCats']) )
		{
			$model->attributes = $_POST['ExtensionsCats'];
			if( $model->save() )
			{
				Yii::app()->user->setFlash('success', Yii::t('extensions', 'Category Added.'));
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
		$parentlist = ExtensionsCats::model()->getRootCats();
		if( count( $parentlist ) )
		{
			foreach($parentlist as $row)
			{
				$parents[ $row->id ] = $row->title;
			}
		}
	
		$this->breadcrumbs[ Yii::t('extensions', 'Adding Category') ] = '';
		$this->pageTitle[] = Yii::t('extensions', 'Adding Category');
		
		// Render
		$this->render('category_form', array('model'=>$model, 'parents' => $parents, 'roles' => $_roles, 'label'=>Yii::t('extensions', 'Adding Category') ));
	}
	
	/**
	 * Edit category action
	 */
	public function actioneditcategory()
	{
		// Perms
		if( !Yii::app()->user->checkAccess('op_extensions_editcats') )
		{
			throw new CHttpException(403, Yii::t('error', 'Sorry, You don\'t have the required permissions to enter this section'));
		}
		
		if( isset($_GET['id']) && ( $model = ExtensionsCats::model()->findByPk( $_GET['id'] ) ) )
		{
			if( isset($_POST['ExtensionsCats']) )
			{
				$model->attributes = $_POST['ExtensionsCats'];
				if( $model->save() )
				{
					Yii::app()->user->setFlash('success', Yii::t('extensions', 'Category Updated.'));
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
			$parentlist = ExtensionsCats::model()->getRootCats();
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
		
			$this->breadcrumbs[ Yii::t('extensions', 'Editing Category') ] = '';
			$this->pageTitle[] = Yii::t('extensions', 'Editing Category');
		
			// Render
			$this->render('category_form', array('model'=>$model, 'parents' => $parents, 'roles' => $_roles, 'label'=>Yii::t('extensions', 'Editing Category') ));
		}
		else
		{
			Yii::app()->user->setFlash('error', Yii::t('extensions', 'Category was not found.'));
			$this->redirect(array('index'));
		}
	}
	
	/**
	 * Delete category
	 */
	public function actiondeletecategory()
	{
		// Perms
		if( !Yii::app()->user->checkAccess('op_extensions_deletecats') )
		{
			throw new CHttpException(403, Yii::t('error', 'Sorry, You don\'t have the required permissions to enter this section'));
		}
		
		if( isset($_GET['id']) && ( $model = ExtensionsCats::model()->findByPk( $_GET['id'] ) ) )
		{
			// If we don't have any sub cats or blog then just go ahead and delete
			$posts = $model->posts;
			$childs = $model->childs;
			
			if( ( !count($posts) && !count($childs) ) )
			{
				$model->delete();
				Yii::app()->user->setFlash('success', Yii::t('extensions', 'Category Deleted.'));
				$this->redirect(array('index'));
			}
			
			// Remove the category we are deleting and the ones beneth it
			$removecats = array();
			$removecats[] = $model->id;
			$subcats = ExtensionsCats::model()->getRecursiveCats($model);
			if( count($subcats) )
			{
				foreach($subcats as $data)
				{
					$removecats[] = $data->id;
				}
			}
			
			// Parent list
			$parents = array();
			$parentlist = ExtensionsCats::model()->getRootCats();
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
					Yii::app()->user->setFlash('error', Yii::t('extensions', 'You must specify a valid category to move the items.'));
				}
				else
				{
					// Update cats
					ExtensionsCats::model()->updateAll( array('parentid'=>$movecatid), 'parentid=:parent', array(':parent'=>$model->id) );
					
					// Update post
					Extensions::model()->updateAll( array('catid'=>$movetutid), 'catid=:cat', array(':cat'=>$model->id) );
					
					// Delete cat
					$model->delete();
					
					Yii::app()->user->setFlash('success', Yii::t('extensions', 'Category Deleted.'));
					$this->redirect(array('index'));
				}
				
			}
			
			$this->breadcrumbs[ Yii::t('extensions', 'Delete Category') ] = '';
			$this->pageTitle[] = Yii::t('extensions', 'Delete Category');
			
			// Show render
			$this->render('delete_form', array('model'=>$model, 'childs' => $childs, 'extensions' => $posts, 'parents' => $parents, 'label'=>Yii::t('extensions', 'Delete Category')));
		}
		else
		{
			//Yii::app()->user->setFlash('error', Yii::t('extensions', 'Category was not found.'));
			$this->redirect(array('index'));
		}
	}
	
	/**
	 * view category action
	 */
    public function actionviewcategory() 
	{
		// Perms
		if( !Yii::app()->user->checkAccess('op_extensions_manage') )
		{
			throw new CHttpException(403, Yii::t('error', 'Sorry, You don\'t have the required permissions to enter this section'));
		}
		
		if( isset($_GET['id']) && ( $model = ExtensionsCats::model()->findByPk( $_GET['id'] ) ) )
		{
			// Did we submit the form and selected items?
			if( isset($_POST['bulkoperations']) && $_POST['bulkoperations'] != '' )
			{			
				// Perms
				if( !Yii::app()->user->checkAccess('op_extensions_manage') )
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
						$records = Extensions::model()->updateByPk(array_keys($_POST['record']), array('status'=>1));
						// Done
						Yii::app()->user->setFlash('success', Yii::t('extensions', '{count} extensions approved.', array('{count}'=>$records)));
						break;
					
						case 'bulkunapprove':
						// Load records
						$records = Extensions::model()->updateByPk(array_keys($_POST['record']), array('status'=>0));
						// Done
						Yii::app()->user->setFlash('success', Yii::t('extensions', '{count} extensions Un-Approved.', array('{count}'=>$records)));
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

			$count = Extensions::model()->count($criteria);
			$pages = new CPagination($count);
			$pages->pageSize = self::PAGE_SIZE;
		
			$pages->applyLimit($criteria);
		
			$sort = new CSort('Extensions');
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
		
			$rows = Extensions::model()->with(array('author','lastauthor'))->findAll($criteria);
			
			// Add breadcrumbs and title
			$this->breadcrumbs[ Yii::t('extensions', 'Viewing Category') ] = '';
			$this->pageTitle[] = Yii::t('extensions', 'Viewing Category');
	
        	$this->render('posts', array( 'model' => $model, 'count' => $count, 'rows' => $rows, 'pages' => $pages, 'sort' => $sort ) );
		}
		else
		{
			Yii::app()->user->setFlash('error', Yii::t('extensions', 'Category was not found.'));
			$this->redirect(array('index'));
		}	
    }

	/**
	 * Add post action
	 */
	public function actionaddpost()
	{
		// Perms
		if( !Yii::app()->user->checkAccess('op_extensions_addposts') )
		{
			throw new CHttpException(403, Yii::t('error', 'Sorry, You don\'t have the required permissions to enter this section'));
		}
		
		$model = new Extensions;
		
		if( isset($_POST['Extensions']) )
		{
			$model->attributes = $_POST['Extensions'];
			if( $model->save() )
			{
				Yii::app()->user->setFlash('success', Yii::t('extensions', 'Extension Added.'));
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
		$parentlist = ExtensionsCats::model()->getRootCats();
		if( count( $parentlist ) )
		{
			foreach($parentlist as $row)
			{
				$parents[ $row->id ] = $row->title;
			}
		}
	
		$this->breadcrumbs[ Yii::t('extensions', 'Adding Extension') ] = '';
		$this->pageTitle[] = Yii::t('extensions', 'Adding Extension');
		
		// Render
		$this->render('post_form', array('model'=>$model, 'parents' => $parents, 'label'=>Yii::t('extensions', 'Adding Extension') ));
	}
	
	/**
	 * edit post action
	 */
	public function actioneditpost()
	{
		// Perms
		if( !Yii::app()->user->checkAccess('op_extensions_editposts') )
		{
			throw new CHttpException(403, Yii::t('error', 'Sorry, You don\'t have the required permissions to enter this section'));
		}
		
		if( isset($_GET['id']) && ( $model = Extensions::model()->findByPk( $_GET['id'] ) ) )
		{
			if( isset($_POST['Extensions']) )
			{
				$model->attributes = $_POST['Extensions'];
				if( $model->save() )
				{
					Yii::app()->user->setFlash('success', Yii::t('extensions', 'Extension Updated.'));
					$this->redirect(array('viewcategory', 'id'=>$model->catid));
				}
			}
		
			// cat list
			$parents = array();
			$parentlist = ExtensionsCats::model()->getRootCats();
			if( count( $parentlist ) )
			{
				foreach($parentlist as $row)
				{
					$parents[ $row->id ] = $row->title;
				}
			}
	
			// language
			$model->language = !is_array($model->language) ? explode(',', $model->language) : $model->language;
	
			$this->breadcrumbs[ Yii::t('extensions', 'Editing Extension') ] = '';
			$this->pageTitle[] = Yii::t('extensions', 'Editing Extension');
		
			// Render
			$this->render('post_form', array('model'=>$model, 'parents' => $parents, 'label'=>Yii::t('extensions', 'Editing Extension') ));
		}
		else
		{
			Yii::app()->user->setFlash('error', Yii::t('extensions', 'Extension was not found.'));
			$this->redirect(array('index'));
		}
	}
	
	/**
	 * Toggle post status
	 */
	public function actiontogglepost()
	{
		// Perms
		if( !Yii::app()->user->checkAccess('op_extensions_manage') )
		{
			throw new CHttpException(403, Yii::t('error', 'Sorry, You don\'t have the required permissions to enter this section'));
		}
		
		if( isset($_GET['id']) && ( $model = Extensions::model()->findByPk( $_GET['id'] ) ) )
		{
			$update = $model->status ? 0 : 1;
			$model->status = $update;
			$model->save();
			
			Yii::app()->user->setFlash('success', Yii::t('extensions', 'Extension status Updated.'));
			$this->redirect(array('viewcategory', 'id'=>$model->catid));
		}
		else
		{
			Yii::app()->user->setFlash('error', Yii::t('extensions', 'Extension was not found.'));
			$this->redirect(array('index'));
		}
	}
	
	/**
	 * Delete post action
	 */
	public function actiondeletepost()
	{
		// Perms
		if( !Yii::app()->user->checkAccess('op_extensions_deleteposts') )
		{
			throw new CHttpException(403, Yii::t('error', 'Sorry, You don\'t have the required permissions to enter this section'));
		}
		
		if( isset($_GET['id']) && ( $model = Extensions::model()->findByPk($_GET['id']) ) )
		{			
			$catid = $model->catid;
			
			$model->delete();
			
			Yii::app()->user->setFlash('success', Yii::t('extensions', 'Extension Deleted.'));
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
		if( !Yii::app()->user->checkAccess('op_extensions_comments') )
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
					if( !Yii::app()->user->checkAccess('op_extensions_deletecomments') )
					{
						throw new CHttpException(403, Yii::t('error', 'Sorry, You don\'t have the required permissions to enter this section'));
					}
					
					// Load comments and delete them
					$comments_deleted = ExtensionsComments::model()->deleteByPk(array_keys($_POST['comment']));
					// Done
					Yii::app()->user->setFlash('success', Yii::t('extensions', '{count} comments deleted.', array('{count}'=>$comments_deleted)));
					break;
					
					case 'bulkapprove':
					// Load comments
					$comments = ExtensionsComments::model()->updateByPk(array_keys($_POST['comment']), array('visible'=>1));
					// Done
					Yii::app()->user->setFlash('success', Yii::t('extensions', '{count} comments approved.', array('{count}'=>$comments)));
					break;
					
					case 'bulkunapprove':
					// Load comments
					$comments = ExtensionsComments::model()->updateByPk(array_keys($_POST['comment']), array('visible'=>0));
					// Done
					Yii::app()->user->setFlash('success', Yii::t('extensions', '{count} comments Un-Approved.', array('{count}'=>$comments)));
					break;
					
					default:
					// Nothing
					break;
				}
			}
		}
		
		// Grab the language data
		$criteria = new CDbCriteria;
		
		$count = ExtensionsComments::model()->count($criteria);
		$pages = new CPagination($count);
		$pages->pageSize = self::PAGE_SIZE;
		
		$pages->applyLimit($criteria);
		
		$sort = new CSort('ExtensionsComments');
		
		$sort->defaultOrder = 'postdate DESC';
		$sort->applyOrder($criteria);
		$sort->attributes = array(
		        'tid' => 't.id',
				'authorid' => 'authorid',
				'postdate' => 'postdate',
				'visible' => 'visible',
		);
		
		$comments = ExtensionsComments::model()->with(array('author'))->findAll($criteria);
		
		$this->breadcrumbs[ Yii::t('extensions', 'Manage Comments') ] = array('extensions/comments');
		$this->pageTitle[] = Yii::t('extensions', 'Manage Comments');
		
		$this->render('comments', array( 'comments' => $comments, 'sort'=>$sort, 'pages'=>$pages, 'count' => $count ));
	}
	
	/**
	 * Change comment visibility status
	 */
	public function actiontogglecommentstatus()
	{
		// Perms
		if( !Yii::app()->user->checkAccess('op_extensions_comments') )
		{
			throw new CHttpException(403, Yii::t('error', 'Sorry, You don\'t have the required permissions to enter this section'));
		}
		
		if( isset($_GET['id']) && ( $model = ExtensionsComments::model()->findByPk($_GET['id']) ) )
		{			
			$model->visible = $model->visible == 1 ? 0 : 1;
			$model->save();
			
			Yii::app()->user->setFlash('success', Yii::t('extensions', 'Comment Updated.'));
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
		if( !Yii::app()->user->checkAccess('op_extensions_deletecomments') )
		{
			throw new CHttpException(403, Yii::t('error', 'Sorry, You don\'t have the required permissions to enter this section'));
		}
		
		if( isset($_GET['id']) && ( $model = ExtensionsComments::model()->findByPk($_GET['id']) ) )
		{			
			$model->delete();
			
			Yii::app()->user->setFlash('success', Yii::t('extensions', 'Comment Deleted.'));
			$this->redirect(array('comments'));
		}
		else
		{
			$this->redirect(array('comments'));
		}
	}
}