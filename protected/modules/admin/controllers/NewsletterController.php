<?php
/**
 * Newsletter controller Home page
 */
class NewsletterController extends AdminBaseController {
	/**
	 * total items per page
	 */
	const PAGE_SIZE = 50;
	/**
	 * init
	 */
	public function init()
	{
		parent::init();
		
		$this->breadcrumbs[ Yii::t('adminnewsletter', 'News Letter') ] = array('newsletter/index');
		$this->pageTitle[] = Yii::t('adminnewsletter', 'News Letter');
	}
	/**
	 * Index action
	 */
    public function actionIndex() 
	{				
		// New subscriber form
		$model = new Newsletter;
		
		if( isset($_POST['Newsletter']) )
		{
			$model->attributes = $_POST['Newsletter'];
			if( $model->save() )
			{
				Yii::app()->user->setFlash('success', Yii::t('newsletter', 'Email Added.'));
			}
		}
		
		// Send newsletter
		if( isset($_POST['sendnewsletter']) && $_POST['sendnewsletter'] )
		{
			// Make sure content exists
			if( isset($_POST['content']) && $_POST['content'] != '' )
			{
				// Make sure there are enough email
				$emails = Newsletter::model()->findAll();
				if( $emails )
				{
					$sent = 0;
					// Loop and send
					foreach($emails as $row)
					{
						$email = Yii::app()->email;
						$email->subject = isset($_POST['subject']) ? $_POST['subject'] : 'Newsletter';
						$email->to = $row->email;
						$email->from = Yii::app()->params['emailout'];
						$email->replyTo = Yii::app()->params['emailout'];
						$email->message = $_POST['content'];
						$email->send();
						
						$sent++;
					}
					
					Yii::app()->user->setFlash('success', Yii::t('newsletter', '{count} Newsletter emails sent.', array('{count}'=>$sent)));
				}
				else
				{
					Yii::app()->user->setFlash('error', Yii::t('newsletter', 'There are no subscribers to send the newsletter to.'));
				}
			}
			else
			{
				Yii::app()->user->setFlash('error', Yii::t('newsletter', 'You must provide a valid content to email.'));
			}
		}
	
		// Did we submit the form and selected items?
		if( isset($_POST['bulkoperations']) && $_POST['bulkoperations'] != '' )
		{			
			// Did we choose any values?
			if( isset($_POST['record']) && count($_POST['record']) )
			{
				// What operation we would like to do?
				switch( $_POST['bulkoperations'] )
				{									
					case 'delete':
					// Load records
					$records = Newsletter::model()->deleteByPk(array_keys($_POST['record']));
					// Done
					Yii::app()->user->setFlash('success', Yii::t('newsletter', '{count} items deleted.', array('{count}'=>$records)));
					break;
				
					default:
					// Nothing
					break;
				}
			}
		}
	
		// Load items and display
		$criteria = new CDbCriteria;

		$count = Newsletter::model()->count();
		$pages = new CPagination($count);
		$pages->pageSize = self::PAGE_SIZE;
		
		$pages->applyLimit($criteria);
		
		$sort = new CSort('Newsletter');
		$sort->defaultOrder = 'joined DESC';
		$sort->applyOrder($criteria);

		$sort->attributes = array(
		        'email'=>'email',
				'joined' =>'joined',
		);
		
		$items = Newsletter::model()->findAll($criteria);
	
        $this->render('index', array( 'model' => $model, 'rows' => $items, 'pages' => $pages, 'sort' => $sort, 'count' => $count ));
    }
	
	/**
	 * Send the reply
	 */
	public function actionsend()
	{
		if( isset($_POST['id']) && ( $model = ContactUs::model()->findByPk($_POST['id']) ) )
		{
			// Add the new message
			$message = Yii::t('contactus', "You have received a new reply from <b>{replyername}</b><br /><br />
											 =====================<br />
											 {msg}<br />
											 =====================<br /><br />
											 Regards, The {team} Team.<br /><br />", array(
																				'{replyername}' => Yii::app()->user->username,
																				'{msg}' => $_POST['message'],
																				'{team}' => Yii::app()->name,
																				));
																				
			// Build Old Message
			$message .= Yii::t('contactus', "New Contact Us Form Submitted<br /><br />
										    Id: {id}<br />
											By: {name}<br />
											Email: {email}<br />
											Subject: {subject}<br />
											========================<br />
											{msg}<br />
											========================<br /><br />
											Regards, the {team} Team.", array(
																				'{id}' => $model->id,
												 								'{name}' => $model->name,
																				'{email}' => $model->email,
																				'{subject}' => $model->subject,
																				'{msg}' => $model->content,
																				'{team}' => Yii::app()->name,
												 							  ));
			
												
			$email = Yii::app()->email;
			$email->subject = Yii::t('contactus', 'Re: {subject}', array( '{subject}' => $model->subject ));
			$email->to = $_POST['email'] ? $_POST['email'] : $model->email;
			$email->from = Yii::app()->params['emailout'];
			$email->replyTo = Yii::app()->params['emailout'];
			$email->message = $message;
			$email->send();
		}
		else
		{
			exit;
		}
	}
	
	/**
	 * Delete an item
	 */
	public function actiondelete()
	{
		if( isset($_GET['id']) && ( $model = Newsletter::model()->findByPk($_GET['id']) ) )
		{
			$model->delete();
			
			Yii::app()->user->setFlash('success', Yii::t('newsletter', 'Item Deleted.'));
			$this->redirect(array('index'));
		}
		else
		{
			$this->redirect(array('index'));
		}
	}
}