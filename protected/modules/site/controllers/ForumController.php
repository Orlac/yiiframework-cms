<?php
/**
 * Forum controller Home page
 */
class ForumController extends SiteBaseController {
	
	/**
	 * Page size constants
	 */
	const TOPIC_PAGE_SIZE = 50;
	const POST_PAGE_SIZE = 50;
	
	/**
	 * Controller constructor
	 */
    public function init()
    {
        parent::init();
		
		// Add page breadcrumb and title
		$this->pageTitle[] = Yii::t('forum', 'Forum');
		$this->breadcrumbs[ Yii::t('forum', 'Forum') ] = array('forum/index');
    }

	/**
	 * Index action
	 */
    public function actionIndex() {
	
		// Grab topics by date
		$criteria = new CDbCriteria;
		$criteria->condition = 'language=:lang AND (visible=:visible OR visible=:mod)';
		$criteria->params = array( ':lang' => Yii::app()->language, ':visible' => 1, ':mod' => Yii::app()->user->checkAccess('op_forum_post_topics') ? 0 : 1 );

		$count = ForumTopics::model()->count($criteria);
		$pages = new CPagination($count);
		$pages->pageSize = self::TOPIC_PAGE_SIZE;
		$pages->route = '/forum/index';
		$pages->params = array('lang'=>false);
	
		$pages->applyLimit($criteria);

		$rows = ForumTopics::model()->byDate()->with(array('postscount', 'author','lastauthor'))->findAll($criteria);
	
        $this->render('index', array('rows' => $rows, 'pages' => $pages ));
    }
	
	/**
	 * Add topic action
	 */
	public function actionaddtopic()
	{
		if( !Yii::app()->user->checkAccess('op_forum_post_topics') )
		{
			throw new CHttpException(403, Yii::t('forum', 'Sorry, You are not allowed to perform that operation.'));
		}
		
		$model = new ForumTopics;
		
		// Did we submit the form?
		if( isset($_POST['ForumTopics']) )
		{
			$model->attributes = $_POST['ForumTopics'];
			$model->visible = 1;
			if( $model->save() )
			{
				Yii::app()->user->setFlash('success', Yii::t('forum', 'Thank You. Your topic created.'));
				$this->redirect('index');
			}
		}
		
		// Add page breadcrumb and title
		$this->pageTitle[] = Yii::t('forum', 'Create A Topic');
		$this->breadcrumbs[ Yii::t('forum', 'Create A Topic') ] = '';
		
		// Render
		$this->render('addtopic', array('model'=>$model));
	}
	
	/**
	 * View Topic Action
	 */
	public function actionviewtopic()
	{
		if( isset($_GET['topicid']) && ( $model = ForumTopics::model()->findByPk($_GET['topicid']) ) )
		{
			// Make sure the alias matches to avoid duplicated content
			if( $model->alias != $model->getAlias($_GET['alias']) )
			{
				throw new CHttpException(404, Yii::t('forum', 'Sorry, We could not find that topic.'));
			}
			
			// Did we add a new post?
			$newPost = new ForumPosts;
			if( isset($_POST['ForumPosts']) )
			{
				// Make sure we have access
				if( !Yii::app()->user->checkAccess('op_forum_post_posts') )
				{
					throw new CHttpException(403, Yii::t('forum', 'Sorry, You are not allowed to perform that operation.'));
				}
				
				$newPost->attributes = $_POST['ForumPosts'];
				$newPost->topicid = $model->id;
				$newPost->visible = 1;
				if( $newPost->save() )
				{
					// Update last post time and author
					$model->lastpostdate = time();
					$model->lastpostauthorid = Yii::app()->user->id;
					$model->update();
					
					// Send notifications to the ones subscribed
					$topicSubscribtions = TopicSubs::model()->with(array('user', 'topic'))->findAll('topicid=:topicid', array( ':topicid' => $model->id ));
					
					// Loop and email
					if( $topicSubscribtions )
					{
						foreach( $topicSubscribtions as $sub )
						{
							$email = Yii::app()->email;
							
							// We skip the user that actually posted the new post
							if( $sub->userid == Yii::app()->user->id )
							{
								continue;
							}
							
							// Email to the users email address
							$email->subject = Yii::t('forum', "New post in a topic you are subscribed to '{title}'", array( '{title}' => $sub->topic->title ) );
							$email->to = $sub->user->email;
							$email->from = Yii::app()->params['emailout'];
							$email->replyTo = Yii::app()->params['emailout'];
							$email->message = Yii::t('forum', "Dear {user}, <br /><br />A new post was made by '{author}' in the topic '{topic}' you are subscribed to. To visit the topic please click the following link<br /><br />{link}<br /><br />
																		  <small>To unsubscribe from receiving updates for this topic please click the following link {unlink}</small>.<br /><br />
																		  Regards, The {name} Team.", 
																		  array( 
																		  		'{user}' => $sub->user->username, 
																		  		'{author}' => $newPost->author->username,
																		  		'{topic}' => CHtml::encode( $sub->topic->title ),
																		  		'{link}' => $this->createAbsoluteUrl('/forum/topic/' . $sub->topic->id . '-' . $sub->topic->alias, array('lang'=>false)),
																		  		'{unlink}' => $this->createAbsoluteUrl('/forum/unsubscribe', array( 'id' => $sub->topic->id, 'lang' => false ) ),
																		  		'{name}' => Yii::app()->name,
																		  		));
							$email->send();
						}
					}
					
					Yii::app()->user->setFlash( 'success', Yii::t('forum', 'Thank You. Your post was submitted.') );
					$this->redirect('/forum/topic/' . $model->id . '-' . $model->alias . '/page/' . $_POST['lastpage'] . '#post' . $newPost->id);
				}
			}
			
			// Increase the views count
			$model->views++;
			$model->update();
			
			// Grab posts
			$criteria = new CDbCriteria;
			$criteria->condition = 'topicid=:tid AND (visible=:visible OR visible=:mod)';
			$criteria->params = array( ':tid' => $model->id, ':visible' => 1, ':mod' => Yii::app()->user->checkAccess('op_forum_post_posts') ? 0 : 1  );

			$count = ForumPosts::model()->count($criteria);
			$pages = new CPagination($count);
			$pages->pageSize = self::POST_PAGE_SIZE;
			$pages->route = '/forum/topic/'.$model->id . '-' . $model->alias;
			$pages->params = array('lang'=>false);

			$pages->applyLimit($criteria);

			$posts = ForumPosts::model()->byDateAsc()->with(array('author'))->findAll($criteria);
			
			// Show titles and nav
			$this->pageTitle[] = Yii::t('forum', 'Viewing Topic: {title}', array('{title}'=>CHtml::encode($model->title)));
			$this->breadcrumbs[ Yii::t('forum', 'Viewing Topic: {title}', array('{title}'=>$model->title)) ] = '';
			
			$markdown = new MarkdownParser;
			
			// Are we subscribed into this topic?
			$subscribed = TopicSubs::model()->find('topicid=:topicid AND userid=:userid', array( ':topicid' => $model->id, ':userid' => Yii::app()->user->id ) );
			
			// Render
			$this->render('viewtopic', array( 'subscribed' => $subscribed, 'markdown' => $markdown, 'model' => $model, 'posts' => $posts, 'newPost' => $newPost, 'count' => $count, 'pages' => $pages ));
		}
		else
		{
			throw new CHttpException(404, Yii::t('forum', 'Sorry, We could not find that topic.'));
		}
	}
	
	/**
	 * Delete a topic from the list
	 * 
	 */
	public function actiondeletetopic()
	{
		// Make sure we are a valid user
		if( !Yii::app()->user->id )
		{
			throw new CHttpException(403, Yii::t('forum', 'Sorry, You are not allowed to perform that operation.'));
		}
		
		// Validate access token
		if( Yii::app()->request->getParam('k') != Yii::app()->request->csrfToken )
		{
			throw new CHttpException(403, Yii::t('forum', 'Sorry, You are not allowed to perform that operation.'));
		}
	
		if( isset($_GET['id']) && ( $model = ForumTopics::model()->findByPk($_GET['id']) ) )
		{			
			$model->delete();
		}
		
		Yii::app()->user->setFlash('success', Yii::t('forum', 'Thanks. Topic Deleted'));
		$this->redirect( Yii::app()->request->getUrlReferrer() );
	}
	
	/**
	 * Subscribe to a topic
	 *
	 */
	public function actionsubscribe()
	{
		// Make sure we are a valid user
		if( !Yii::app()->user->id )
		{
			throw new CHttpException(403, Yii::t('forum', 'Sorry, You are not allowed to perform that operation.'));
		}
	
		if( isset($_GET['id']) && ( $model = ForumTopics::model()->findByPk($_GET['id']) ) )
		{
			// Make sure we are not subscribed already
			$subscribed = TopicSubs::model()->find('topicid=:topicid AND userid=:userid', array( ':topicid' => $model->id, ':userid' => Yii::app()->user->id ) );
			
			// Error out
			if( $subscribed )
			{
				Yii::app()->user->setFlash('error', Yii::t('forum', 'Sorry, You already subscribed to this topic.'));
				$this->redirect( Yii::app()->request->getUrlReferrer() );
			}
			
			// Add it
			$sub = new TopicSubs;
			$sub->topicid = $model->id;
			$sub->userid = Yii::app()->user->id;
			$sub->save();
			
			Yii::app()->user->setFlash('success', Yii::t('forum', 'Thanks. You are now subscribed to this topic.'));
			$this->redirect( Yii::app()->request->getUrlReferrer() );
		}
		else
		{
			$this->redirect( Yii::app()->request->getUrlReferrer() );
		}
	}
	
	/**
	 * UnSubscribe to a topic
	 *
	 */
	public function actionunsubscribe()
	{
		// Make sure we are a valid user
		if( !Yii::app()->user->id )
		{
			throw new CHttpException(403, Yii::t('forum', 'Sorry, You are not allowed to perform that operation.'));
		}
	
		if( isset($_GET['id']) && ( $model = ForumTopics::model()->findByPk($_GET['id']) ) )
		{
			// Make sure we are not subscribed already
			$subscribed = TopicSubs::model()->find('topicid=:topicid AND userid=:userid', array( ':topicid' => $model->id, ':userid' => Yii::app()->user->id ) );
			
			// Delete if found
			if( $subscribed )
			{
				$subscribed->delete();
			}
			
			Yii::app()->user->setFlash('success', Yii::t('forum', 'Thanks. You are now unsubscribed from this topic.'));
			$this->redirect( Yii::app()->request->getUrlReferrer() );
		}
		else
		{
			$this->redirect( Yii::app()->request->getUrlReferrer() );
		}
	}
	
	/**
	 * Toggle post status
	 */
	public function actiontogglepost()
	{
		if( !Yii::app()->user->checkAccess('op_forum_posts') )
		{
			throw new CHttpException(403, Yii::t('forum', 'Sorry, You are not allowed to perform that operation.'));
		}
		
		if( isset($_GET['id']) && ( $model = ForumPosts::model()->findByPk($_GET['id']) ) )
		{
			$model->visible = $model->visible == 1 ? 0 : 1;
			$model->update();
			
			Yii::app()->user->setFlash('success', Yii::t('forum', 'Post Status Changed.'));
			$this->redirect( Yii::app()->request->getUrlReferrer() );
		}
		else
		{
			$this->redirect( Yii::app()->request->getUrlReferrer() );
		}
	}
	
	/**
	 * Toggle topic status
	 */
	public function actiontoggletopic()
	{
		if( !Yii::app()->user->checkAccess('op_forum_topics') )
		{
			throw new CHttpException(403, Yii::t('forum', 'Sorry, You are not allowed to perform that operation.'));
		}
		
		if( isset($_GET['id']) && ( $model = ForumTopics::model()->findByPk($_GET['id']) ) )
		{
			$model->visible = $model->visible == 1 ? 0 : 1;
			$model->update();
			
			Yii::app()->user->setFlash('success', Yii::t('forum', 'Topics Status Changed.'));
			$this->redirect( Yii::app()->request->getUrlReferrer() );
		}
		else
		{
			$this->redirect( Yii::app()->request->getUrlReferrer() );
		}
	}

}