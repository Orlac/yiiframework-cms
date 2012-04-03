<?php
/**
 * User controller Home page
 */
class UsersController extends SiteBaseController {
	
	const PAGE_SIZE = 50;
	
	/**
	 * Controller constructor
	 */
    public function init()
    {
        parent::init();

		// Add page breadcrumb and title
		$this->pageTitle[] = Yii::t('users', 'Users');
		$this->breadcrumbs[ Yii::t('users', 'Users') ] = array('users/index');
    }

	/**
	 * Show all users
	 */
	public function actionIndex()
	{
		// Load members and display
		$criteria = new CDbCriteria;

		$count = Members::model()->count($criteria);
		$pages = new CPagination($count);
		$pages->pageSize = self::PAGE_SIZE;
	
		$pages->applyLimit($criteria);
	
		$sort = new CSort('Members');
		$sort->defaultOrder = 'joined DESC';
		$sort->applyOrder($criteria);

		$sort->attributes = array(
		        'username'=>'username',
		        'joined'=>'joined',
				'role'=>'role',
		);
		
		$sort->route = '/users/index';
		$sort->params = array('lang'=>false);
	
		$rows = Members::model()->findAll($criteria);
		
		$this->render('index', array('rows'=>$rows, 'sort' => $sort, 'pages' => $pages));
	}

	/**
	 * Profile action
	 */
    public function actionviewprofile() 
	{
		if( ( isset($_GET['uid']) && ( $model = Members::model()->findByPk($_GET['uid']) ) ) )
		{
			
			$commentsModel = new UserComments;
			
			// Can add comments?
			$addcomments = false;
			$autoaddcomments = false;
			if( Yii::app()->user->id )
			{
				$addcomments = true;
			}		

			if( $addcomments )
			{
				if( isset($_POST['UserComments']) )
				{
					$commentsModel->attributes = $_POST['UserComments'];
					$commentsModel->userid = $model->id;
					$commentsModel->visible = 1;
					if( $commentsModel->save() )
					{
						Yii::app()->user->setFlash('success', Yii::t('users', 'Comment Added.'));
						$commentsModel = new UserComments;
					}
				}
			}

			// Grab the language data
			$criteria = new CDbCriteria;
			$criteria->condition = 'userid=:postid AND visible=:visible';
			$criteria->params = array( ':postid' => $model->id, ':visible' => 1 );
			$criteria->order = 'postdate DESC';

			// Load only approved
			if( Yii::app()->user->checkAccess('op_users_manage_comments')  )
			{
				$criteria->condition .= ' OR visible=0';
			}

			$totalcomments = UserComments::model()->count($criteria);
			$pages = new CPagination($totalcomments);
			$pages->pageSize = self::PAGE_SIZE;

			$pages->applyLimit($criteria);

			// Grab comments
			$comments = UserComments::model()->orderDate()->findAll($criteria);
			
			// Markdown
			$markdown = new MarkdownParser;
			
			// Add page breadcrumb and title
			$this->pageTitle[] = Yii::t('users', 'Viewing {name} Profile', array('{name}'=>$model->username));
			$this->breadcrumbs[ Yii::t('users', 'Viewing {name} Profile', array('{name}'=>$model->username)) ] = '';
			
			$this->render('profile', array( 'model' => $model, 'markdown' => $markdown, 'addcomments' => $addcomments, 'pages' => $pages, 'commentsModel' => $commentsModel, 'totalcomments' => $totalcomments, 'comments'=>$comments ));
		}
		else
		{
			throw new CHttpException(404, Yii::t('users', 'Sorry, But we could not find that user.') );
		}
    }

	/**
	 * Change comment visibility status
	 */
	public function actiontogglestatus()
	{
		if( !Yii::app()->user->checkAccess('op_users_manage_comments')  )
		{
			$this->redirect( Yii::app()->request->getUrlReferrer() );
		}
		
		if( isset($_GET['id']) && ( $model = UserComments::model()->findByPk($_GET['id']) ) )
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
}