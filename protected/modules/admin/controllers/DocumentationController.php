<?php
/**
 * Documentation controller Home page
 */
class DocumentationController extends AdminBaseController {
	/**
	 * records per page
	 */
	const PAGE_SIZE = 50;
	
	/**
	 * init
	 */
	public function init()
	{
		parent::init();
		
		$this->breadcrumbs[ Yii::t('admindocs', 'Documentations') ] = array('documentation/index');
		$this->pageTitle[] = Yii::t('admindocs', 'Documentations'); 
	}
	/**
	 * Index action
	 */
    public function actionIndex() {
	
		// Perms
		if( !Yii::app()->user->checkAccess('op_doc_edit_docs') )
		{
			throw new CHttpException(403, Yii::t('error', 'Sorry, You don\'t have the required permissions to enter this section'));
		}
		
		$this->importTopics();
		$this->importTopics('source');
		
		$topics = Documentation::model()->noSource()->with(array('updater'))->findAll();
		$_topics = array();
		if( count($topics) )
		{
			foreach($topics as $topic)
			{
				$_topics[ $topic->type ][] = $topic;
			}
		}
	
        $this->render('index', array('topics'=>$_topics));
    }

	/**
	 * Edit documentation topic
	 */
	public function actionedit()
	{
		// Perms
		if( !Yii::app()->user->checkAccess('op_doc_edit_docs') )
		{
			throw new CHttpException(403, Yii::t('error', 'Sorry, You don\'t have the required permissions to enter this section'));
		}
		
		if( isset($_GET['id']) && ($model = Documentation::model()->findByPk($_GET['id']) ) )
		{
			// Did we hit the submit button?
			if( isset( $_POST['Documentation'] ) && $_POST['Documentation'] )
			{
				$model->attributes = $_POST['Documentation'];
				$model->scenario = 'update';
				if( $model->save() )
				{
					// Save documentation to file
					file_put_contents( Yii::getPathOfAlias('application.documentation.'.$model->type.'.'.$model->language) . '/' . $model->mkey . '.txt', $model->content );
					
					Yii::app()->user->setFlash('success', Yii::t('admindocs', 'Topic Updated.'));
					$this->redirect(array('index'));
				}	
			}
			
			$this->breadcrumbs[ Yii::t('admindocs', 'Editing Topic') ] = array('documentation/index');
			$this->pageTitle[] = Yii::t('admindocs', 'Editing Topic');

			// Show editor
			$this->render('edit_topic', array( 'model' => $model ));
		}
		else
		{
			Yii::app()->user->setFlash('error', Yii::t('admindocs', 'Sorry, We could not find that topic in that documentation type.'));
			$this->redirect(array('index'));
		}
	}
	
	/**
	 * Manage comments
	 */
	public function actioncomments()
	{
		// Perms
		if( !Yii::app()->user->checkAccess('op_doc_manage_comments') )
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
					if( !Yii::app()->user->checkAccess('op_doc_edit_comments') )
					{
						throw new CHttpException(403, Yii::t('error', 'Sorry, You don\'t have the required permissions to enter this section'));
					}
					
					// Load comments and delete them
					$comments_deleted = DocumentationComments::model()->deleteByPk(array_keys($_POST['comment']));
					// Done
					Yii::app()->user->setFlash('success', Yii::t('admindocs', '{count} comments deleted.', array('{count}'=>$comments_deleted)));
					break;
					
					case 'bulkapprove':
					// Load comments
					$comments = DocumentationComments::model()->updateByPk(array_keys($_POST['comment']), array('visible'=>1));
					// Done
					Yii::app()->user->setFlash('success', Yii::t('admindocs', '{count} comments approved.', array('{count}'=>$comments)));
					break;
					
					case 'bulkunapprove':
					// Load comments
					$comments = DocumentationComments::model()->updateByPk(array_keys($_POST['comment']), array('visible'=>0));
					// Done
					Yii::app()->user->setFlash('success', Yii::t('admindocs', '{count} comments Un-Approved.', array('{count}'=>$comments)));
					break;
					
					default:
					// Nothing
					break;
				}
			}
		}
		
		// Grab the language data
		$criteria = new CDbCriteria;
		
		$count = DocumentationComments::model()->count($criteria);
		$pages = new CPagination($count);
		$pages->pageSize = self::PAGE_SIZE;
		
		$pages->applyLimit($criteria);
		
		$sort = new CSort('DocumentationComments');
		
		$sort->defaultOrder = 'postdate DESC';
		$sort->applyOrder($criteria);
		$sort->attributes = array(
		        'tid' => 't.id',
				'authorid' => 'authorid',
				'postdate' => 'postdate',
				'visible' => 'visible',
		);
		
		$comments = DocumentationComments::model()->with(array('author'))->findAll($criteria);
		
		$this->breadcrumbs[ Yii::t('admindocs', 'Manage Comments') ] = array('documentation/comments');
		$this->pageTitle[] = Yii::t('admindocs', 'Manage Comments');
		
		$this->render('comments', array( 'comments' => $comments, 'sort'=>$sort, 'pages'=>$pages, 'count' => $count ));
	}
	
	/**
	 * Change comment visibility status
	 */
	public function actiontogglestatus()
	{
		// Perms
		if( !Yii::app()->user->checkAccess('op_doc_manage_comments') )
		{
			throw new CHttpException(403, Yii::t('error', 'Sorry, You don\'t have the required permissions to enter this section'));
		}
		
		if( isset($_GET['id']) && ( $model = DocumentationComments::model()->findByPk($_GET['id']) ) )
		{			
			$model->visible = $model->visible == 1 ? 0 : 1;
			$model->save();
			
			Yii::app()->user->setFlash('success', Yii::t('admindocs', 'Comment Updated.'));
			$this->redirect(array('documentation/comments'));
		}
		else
		{
			$this->redirect(array('documentation/comments'));
		}
	}
	
	/**
	 * Delete comment action
	 */
	public function actiondeletecomment()
	{
		// Perms
		if( !Yii::app()->user->checkAccess('op_doc_delete_comments') )
		{
			throw new CHttpException(403, Yii::t('error', 'Sorry, You don\'t have the required permissions to enter this section'));
		}
		
		if( isset($_GET['id']) && ( $model = DocumentationComments::model()->findByPk($_GET['id']) ) )
		{			
			$model->delete();
			
			Yii::app()->user->setFlash('success', Yii::t('adminmembers', 'Comment Deleted.'));
			$this->redirect(array('documentation/comments'));
		}
		else
		{
			$this->redirect(array('documentation/comments'));
		}
	}
	
	/**
	 * Import the topics to the DB
	 */
	public function importTopics($lang='he')
	{
		// Topics
		$guides = Documentation::model()->documentationFolders;
		
		if( count($guides) )
		{
			foreach($guides as $key => $guide)
			{
				// Load the guides files
				$files = $this->grabTopics($key, $lang);
				
				if( count( $files ) )
				{
					foreach( $files as $file )
					{
						// make a short name
						$name = str_replace('.txt', '', end(explode('/', $file)));
						
						// Check if we have one in the DB
						$exists = Documentation::model()->exists('mkey=:key AND type=:type AND language=:lang', array( ':lang' => $lang, ':key' => $name, ':type' => $key ));
						if( $exists )
						{
							continue;
						}
						
						$contents = file_get_contents( $file );
						
						$contents = str_replace('«', '<', $contents);
						$contents = str_replace('»', '>', $contents);
						
						// Add
						$save = new Documentation;
						$save->name = $name;
						$save->mkey = $name;
						$save->language = $lang;
						$save->type = $key;
						$save->content = $contents;
						$save->save();
					}
				}
			}
		}
		
	}

	/**
	 * Grab topics from the directory
	 */
	public function grabTopics( $doc='guide', $lang='he' )
	{
		$return = array();
		
		$path = Yii::getPathOfAlias('application.documentation.' . $doc . '.' . $lang);
		
		if( !is_dir( $path ) )
		{
			return $return;
		}
		
		// Grab files
		$files = CFileHelper::findFiles($path, array( 'fileTypes' => array('txt') ));
		
		if( !count($files) )
		{
			return $return;
		}
		
		asort($files);
		
		return $files;
		
	}
}