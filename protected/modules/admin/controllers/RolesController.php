<?php
/**
 * Role controller Home page
 */
class RolesController extends AdminBaseController {
	/**
	 * total items per page
	 */
	const PAGE_SIZE = 100;
	/**
	 * init
	 */
	public function init()
	{
		parent::init();
		
		$this->breadcrumbs[ Yii::t('adminroles', 'Roles') ] = array('roles/index');
		$this->pageTitle[] = Yii::t('adminroles', 'Roles');
	}
	/**
	 * Index action
	 */
    public function actionIndex() {
		
		// Perms
		if( !Yii::app()->user->checkAccess('op_roles_add_auth') )
		{
			throw new CHttpException(403, Yii::t('error', 'Sorry, You don\'t have the required permissions to enter this section'));
		}
		
		// Load members and display
		$criteria = new CDbCriteria;

		$count = AuthItem::model()->count();
		$pages = new CPagination($count);
		$pages->pageSize = self::PAGE_SIZE;
		
		$pages->applyLimit($criteria);
		
		$sort = new CSort('AuthItem');
		$sort->defaultOrder = 'type DESC, name ASC';
		$sort->applyOrder($criteria);

		$sort->attributes = array(
		        'name'=>'name',
		        'description'=>'description',
		        'type'=>'type',
		);
		
		$roles = AuthItem::model()->findAll($criteria);
	
        $this->render('index', array( 'rows' => $roles, 'pages' => $pages, 'sort' => $sort, 'count' => $count ));
    }

	/**
	 * Add role action
	 */
	public function actionaddauthitem()
	{
		// Perms
		if( !Yii::app()->user->checkAccess('op_roles_add_auth') )
		{
			throw new CHttpException(403, Yii::t('error', 'Sorry, You don\'t have the required permissions to enter this section'));
		}
		
		$model = new AuthItem;
		
		if( isset( $_POST['AuthItem'] ) )
		{
			
			$model->attributes = $_POST['AuthItem'];
			if( $model->validate() )
			{
				// Create an auth item based on those parameters
				Yii::app()->authManager->createAuthItem( $model->name, $model->type, $model->description, $model->bizrule, $model->data ? $model->data : null );
				
				Yii::app()->user->setFlash('success', Yii::t('adminroles', 'Role Added.'));
				$this->redirect(array('roles/index'));
			}
		}
	
		$this->breadcrumbs[ Yii::t('adminroles', 'Adding Role') ] = '';
		$this->pageTitle[] = Yii::t('adminroles', 'Adding Role');
		
		$this->render('authitem_form', array( 'model' => $model, 'label' => Yii::t('adminroles', 'Adding Auth Item') ));
	}
	
	/**
	 * Edit auth item action
	 */
	public function actioneditauthitem()
	{
		// Perms
		if( !Yii::app()->user->checkAccess('op_roles_edit_auth') )
		{
			throw new CHttpException(403, Yii::t('error', 'Sorry, You don\'t have the required permissions to enter this section'));
		}
		
		if( isset($_GET['id']) && ($model = AuthItem::model()->find('name=:name', array(':name'=>$_GET['id'])) ) )
		{
			if( isset( $_POST['AuthItem'] ) )
			{
				$old_name = $model->name;
				$model->attributes = $_POST['AuthItem'];
				if( $model->save() )
				{
					
					// Update parent name and child name in the auth child table
					AuthItemChild::model()->updateAll(array( 'parent' => $model->name ), 'parent=:name', array(':name'=>$old_name));
					AuthItemChild::model()->updateAll(array( 'child' => $model->name ), 'child=:name', array(':name'=>$old_name));	
					AuthAssignments::model()->updateAll(array( 'bizrule' => $model->bizrule, 'data' => $model->data,  'itemname' => $model->name ), 'itemname=:name', array(':name'=>$old_name));
					Members::model()->updateAll(array('role'=>$model->name), 'role=:name', array(':name'=>$old_name));
					
					Yii::app()->user->setFlash('success', Yii::t('adminroles', 'Auth Item Updated.'));
					$this->redirect(array('roles/index'));
				}
			}
			
			$this->breadcrumbs[ Yii::t('adminroles', 'Editing auth item') ] = '';
			$this->pageTitle[] = Yii::t('adminroles', 'Editing auth item');

			// Display form
			$this->render('authitem_form', array( 'model' => $model, 'label' => Yii::t('adminroles', 'Editing auth item') ));
		}
		else
		{
			Yii::app()->user->setFlash('error', Yii::t('adminerror', 'Could not find that ID.'));
			$this->redirect(array('roles/index'));
		}
	}
	
	/**
	 * Delete auth item action
	 */
	public function actiondeleteauthitem()
	{
		// Perms
		if( !Yii::app()->user->checkAccess('op_roles_delete_auth') )
		{
			throw new CHttpException(403, Yii::t('error', 'Sorry, You don\'t have the required permissions to enter this section'));
		}
		
		if( isset($_GET['id']) && ($model = AuthItem::model()->find('name=:name', array(':name'=>$_GET['id'])) ) )
		{
			// Remove relationships between children
			$children = Yii::app()->authManager->getItemChildren($_GET['id']);
			if( count( $children ) )
			{
				foreach($children as $child)
				{
					Yii::app()->authManager->removeItemChild($_GET['id'], $child->name);
				}
			}
			
			// Delete auth item
			Yii::app()->authManager->removeAuthItem( $_GET['id'] );
			
			Yii::app()->user->setFlash('success', Yii::t('adminroles', 'Auth Item Deleted.'));
			$this->redirect(array('roles/index'));
			
		}
		else
		{
			$this->redirect(array('roles/index'));
		}
	}
	/**
	 * adding auth item child relationships
	 */
	public function actionaddauthitemchild()
	{
		// Perms
		if( !Yii::app()->user->checkAccess('op_roles_add_authchild') )
		{
			throw new CHttpException(403, Yii::t('error', 'Sorry, You don\'t have the required permissions to enter this section'));
		}
		
		$model = new AuthItemChild;
		
		$roles = AuthItem::model()->findAll(array('order'=>'type DESC, name ASC'));
		$_roles = array();
		if( count($roles) )
		{
			foreach($roles as $role)
			{
				$_roles[ AuthItem::model()->types[ $role->type ] ][ $role->name ] = $role->description . ' (' . $role->name . ')';
			}
		}
		
		// Did we choose a parent already?
		if( isset($_GET['parent']) && $_GET['parent'] != '' )
		{
			$model->parent = $_GET['parent'];
		}
		
		if( isset( $_POST['AuthItemChild'] ) )
		{
			if( isset($_POST['AuthItemChild']['child']) && count($_POST['AuthItemChild']['child']) )
			{
				
				// We need to delete all child items selected up until now
				$existsalready = AuthItemChild::model()->findAll('parent=:parent', array(':parent'=>$model->parent));
				if( count($existsalready) )
				{
					foreach($existsalready as $existitem)
					{
						Yii::app()->authManager->removeItemChild( $existitem->parent, $existitem->child );
					}
				}
				
				
				$added = 0;
				foreach($_POST['AuthItemChild']['child'] as $childItem)
				{
					$model->child = $childItem;
					if( $model->validate() )
					{
						$added++;
					}
				}
				
				Yii::app()->user->setFlash('success', Yii::t('adminroles', '{number} Child item(s) Added.', array('{number}'=>$added)));
				$this->redirect(array('roles/index'));
			}
		}
		
		// Selected values
		$selected = AuthItemChild::model()->findAll('parent=:parent', array(':parent'=>$model->parent));
		$_selected = array();
		if( count($selected) )
		{
			foreach($selected as $select)
			{
				$_selected[] = $select->child;
			}
		}
		
		$model->child = $_selected;
		
		$this->breadcrumbs[ Yii::t('adminroles', 'Adding auth item child') ] = '';
		$this->pageTitle[] = Yii::t('adminroles', 'Adding auth item child');
		
		$this->render('child_form', array( 'model' => $model, 'roles' => $_roles, 'label' => Yii::t('adminroles', 'Add Auth Item Child') ));
	}
	
	/**
	 * View auth item action
	 */
	public function actionviewauthitem()
	{
		// Perms
		if( !Yii::app()->user->checkAccess('op_roles_add_auth') )
		{
			throw new CHttpException(403, Yii::t('error', 'Sorry, You don\'t have the required permissions to enter this section'));
		}
		
		$parent = isset($_GET['parent']) && $_GET['parent'] != '' ? $_GET['parent'] : null;
		
		if( !$parent )
		{
			Yii::app()->user->setFlash('error', Yii::t('adminroles', 'Could not find that item.'));
			$this->redirect(array('roles/index'));
		}
		
		// Load members and display
		$criteria = new CDbCriteria;
		$criteria->condition = "parent='{$parent}'";
		
		$count = AuthItemChild::model()->count("parent='{$parent}'");
		$pages = new CPagination($count);
		$pages->pageSize = self::PAGE_SIZE;
		
		$pages->applyLimit($criteria);
		
		$sort = new CSort('AuthItemChild');
		$sort->defaultOrder = 'parent ASC';
		$sort->applyOrder($criteria);

		$sort->attributes = array(
		        'parent'=>'parent',
		        'child'=>'child',
		);
		
		$roles = AuthItemChild::model()->findAll($criteria);
		
		$this->breadcrumbs[ Yii::t('adminroles', 'Viewing Child Items') ] = '';
		$this->pageTitle[] = Yii::t('adminroles', 'Viewing Child Items');
	
        $this->render('item_childs', array( 'rows' => $roles, 'pages' => $pages, 'sort' => $sort, 'count' => $count ));
	}
	/**
	 * Delete auth item child action
	 */
	public function actiondeleteauthitemchild()
	{
		// Perms
		if( !Yii::app()->user->checkAccess('op_roles_delete_auth') )
		{
			throw new CHttpException(403, Yii::t('error', 'Sorry, You don\'t have the required permissions to enter this section'));
		}
		
		$parent = $_GET['parent'];
		$child = $_GET['child'];	
		
		if( ( !$parent || !$child ) )
		{
			Yii::app()->user->setFlash('error', Yii::t('adminroles', 'item parent or child name missing.'));
			$this->redirect(array('roles/index'));
		}	
		
		Yii::app()->authManager->removeItemChild( $parent, $child );
			
		Yii::app()->user->setFlash('success', Yii::t('adminroles', 'Auth Item Deleted.'));
		$this->redirect(array('roles/viewauthitem', 'parent'=>$parent));
	}
}