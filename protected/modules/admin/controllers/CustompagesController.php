<?php
/**
 * custom pages controller Home page
 */
class CustompagesController extends AdminBaseController {
	/**
	 * Number of pages per page
	 */
	const PAGE_SIZE = 50;
	
	/**
	 * init
	 */
	public function init()
	{
		parent::init();
		
		$this->breadcrumbs[ Yii::t('admincustompages', 'Custom Pages') ] = array('custompages/index');
		$this->pageTitle[] = Yii::t('admincustompages', 'Custom Pages'); 
	}
	/**
	 * Index action
	 */
    public function actionIndex() {
        
		// Did we submit the form and selected items?
		if( isset($_POST['bulkoperations']) && $_POST['bulkoperations'] != '' )
		{
			// Perms
			if( !Yii::app()->user->checkAccess('op_custompages_managepages') )
			{
				throw new CHttpException(403, Yii::t('error', 'Sorry, You don\'t have the required permissions to enter this section'));
			}
			
			// Did we choose any values?
			if( isset($_POST['record']) && count($_POST['record']) )
			{
				// What operation we would like to do?
				switch( $_POST['bulkoperations'] )
				{
					case 'bulkdelete':
					
					// Perms
					if( !Yii::app()->user->checkAccess('op_custompages_deletepages') )
					{
						throw new CHttpException(403, Yii::t('error', 'Sorry, You don\'t have the required permissions to enter this section'));
					}
					
					// Load records and delete them
					$records = CustomPages::model()->deleteByPk(array_keys($_POST['record']));
					// Done
					Yii::app()->user->setFlash('success', Yii::t('admincustompages', '{count} pages deleted.', array('{count}'=>$records)));
					break;
					
					case 'bulkapprove':
					// Load records
					$records = CustomPages::model()->updateByPk(array_keys($_POST['record']), array('status'=>1));
					// Done
					Yii::app()->user->setFlash('success', Yii::t('admincustompages', '{count} pages approved.', array('{count}'=>$records)));
					break;
					
					case 'bulkunapprove':
					// Load records
					$records = CustomPages::model()->updateByPk(array_keys($_POST['record']), array('status'=>0));
					// Done
					Yii::app()->user->setFlash('success', Yii::t('admincustompages', '{count} pages Un-Approved.', array('{count}'=>$records)));
					break;
					
					default:
					// Nothing
					break;
				}
			}
		}

		// Load members and display
		$criteria = new CDbCriteria;

		$count = CustomPages::model()->count();
		$pages = new CPagination($count);
		$pages->pageSize = self::PAGE_SIZE;
		
		$pages->applyLimit($criteria);
		
		$sort = new CSort('CustomPages');
		$sort->defaultOrder = 'dateposted DESC';
		$sort->applyOrder($criteria);

		$sort->attributes = array(
		        'title'=>'title',
		        'alias'=>'alias',
				'author'=>'authorid',
		        'dateposted'=>'dateposted',
		        'language'=>'language',
				'status'=>'status',
		);
		
		$rows = CustomPages::model()->with(array('author','lastauthor'))->findAll($criteria);
	
        $this->render('index', array( 'count' => $count, 'rows' => $rows, 'pages' => $pages, 'sort' => $sort ) );
    }

	/**
	 * Add a new page action
	 */
	public function actionaddpage()
	{
		
		// Perms
		if( !Yii::app()->user->checkAccess('op_custompages_addpages') )
		{
			throw new CHttpException(403, Yii::t('error', 'Sorry, You don\'t have the required permissions to enter this section'));
		}
		
		$model = new CustomPages;
		
		if( isset( $_POST['CustomPages'] ) )
		{
			if( isset( $_POST['submit'] ) )
			{
				$model->attributes = $_POST['CustomPages'];
				if( $model->save() )
				{
					Yii::app()->user->setFlash('success', Yii::t('admincustompages', 'Page Added.'));
					$this->redirect(array('custompages/index'));
				}
			}
			else if( isset( $_POST['preview'] ) ) 
			{
				$model->attributes = $_POST['CustomPages'];
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
		
		$this->breadcrumbs[ Yii::t('admincustompages', 'Adding Custom Page') ] = '';
		$this->pageTitle[] = Yii::t('admincustompages', 'Adding Custom Page');
		
		// Display form
		$this->render('page_form', array( 'roles' => $_roles, 'model' => $model, 'label' => Yii::t('admincustompages', 'Adding Custom Page') ));
	}
	
	/**
	 * Edit page action
	 */
	public function actioneditpage()
	{	
		// Perms
		if( !Yii::app()->user->checkAccess('op_custompages_editpages') )
		{
			throw new CHttpException(403, Yii::t('error', 'Sorry, You don\'t have the required permissions to enter this section'));
		}
		
		if( isset($_GET['id']) && ( $model = CustomPages::model()->findByPk($_GET['id']) ) )
		{		
			if( isset( $_POST['CustomPages'] ) )
			{
				if( isset( $_POST['submit'] ) )
				{
					$model->attributes = $_POST['CustomPages'];
					if( $model->save() )
					{
						Yii::app()->user->setFlash('success', Yii::t('admincustompages', 'Page Edited.'));
						$this->redirect(array('custompages/index'));
					}
				}
				else if( isset( $_POST['preview'] ) ) 
				{
					$model->attributes = $_POST['CustomPages'];
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
			
			$model->language = explode(',', $model->language);
			$model->visible = explode(',', $model->visible);
		
			$this->breadcrumbs[ Yii::t('admincustompages', 'Editing Custom Page') ] = '';
			$this->pageTitle[] = Yii::t('admincustompages', 'Editing Custom Page');
		
			// Display form
			$this->render('page_form', array( 'roles' => $_roles, 'model' => $model, 'label' => Yii::t('admincustompages', 'Editing Custom Page') ));
		}
		else
		{
			Yii::app()->user->setFlash('error', Yii::t('adminerror', 'Could not find that ID.'));
			$this->redirect(array('custompages/index'));
		}
	}
	
	/**
	 * Change page visibility status
	 */
	public function actiontogglestatus()
	{		
		// Perms
		if( !Yii::app()->user->checkAccess('op_custompages_managepages') )
		{
			throw new CHttpException(403, Yii::t('error', 'Sorry, You don\'t have the required permissions to enter this section'));
		}
		
		if( isset($_GET['id']) && ( $model = CustomPages::model()->findByPk($_GET['id']) ) )
		{			
			$model->status = $model->status == 1 ? 0 : 1;
			$model->save();
			
			Yii::app()->user->setFlash('success', Yii::t('admincustompages', 'Status Updated.'));
			$this->redirect(array('custompages/index'));
		}
		else
		{
			$this->redirect(array('custompages/index'));
		}
	}
	
	/**
	 * Delete page action
	 */
	public function actiondeletepage()
	{
		// Perms
		if( !Yii::app()->user->checkAccess('op_custompages_deletepages') )
		{
			throw new CHttpException(403, Yii::t('error', 'Sorry, You don\'t have the required permissions to enter this section'));
		}
		
		if( isset($_GET['id']) && ( $model = CustomPages::model()->findByPk($_GET['id']) ) )
		{			
			$model->delete();
			
			Yii::app()->user->setFlash('success', Yii::t('admincustompages', 'Page Deleted.'));
			$this->redirect(array('custompages/index'));
		}
		else
		{
			$this->redirect(array('custompages/index'));
		}
	}
}