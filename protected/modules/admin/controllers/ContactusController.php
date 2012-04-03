<?php
/**
 * Contact us controller Home page
 */
class ContactusController extends AdminBaseController {
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
		
		$this->breadcrumbs[ Yii::t('admincontactus', 'Contact Us') ] = array('contactus/index');
		$this->pageTitle[] = Yii::t('admincontactus', 'Contact Us');
	}
	/**
	 * Index action
	 */
    public function actionIndex() {		
	
		// Did we submit the form and selected items?
		if( isset($_POST['bulkoperations']) && $_POST['bulkoperations'] != '' )
		{			
			// Did we choose any values?
			if( isset($_POST['record']) && count($_POST['record']) )
			{
				// What operation we would like to do?
				switch( $_POST['bulkoperations'] )
				{					
					case 'read':
					// Load records
					$records = ContactUs::model()->updateByPk(array_keys($_POST['record']), array('sread'=>1));
					// Done
					Yii::app()->user->setFlash('success', Yii::t('contactus', '{count} items marked as read.', array('{count}'=>$records)));
					break;
					
					case 'notread':
					// Load records
					$records = ContactUs::model()->updateByPk(array_keys($_POST['record']), array('sread'=>0));
					// Done
					Yii::app()->user->setFlash('success', Yii::t('contactus', '{count} items marked as not read.', array('{count}'=>$records)));
					break;
				
					case 'delete':
					// Load records
					$records = ContactUs::model()->deleteByPk(array_keys($_POST['record']));
					// Done
					Yii::app()->user->setFlash('success', Yii::t('contactus', '{count} items deleted.', array('{count}'=>$records)));
					break;
				
					default:
					// Nothing
					break;
				}
			}
		}
	
		// Load items and display
		$criteria = new CDbCriteria;

		$count = ContactUs::model()->count();
		$pages = new CPagination($count);
		$pages->pageSize = self::PAGE_SIZE;
		
		$pages->applyLimit($criteria);
		
		$sort = new CSort('ContactUs');
		$sort->defaultOrder = 'postdate DESC';
		$sort->applyOrder($criteria);

		$sort->attributes = array(
		        'name'=>'name',
		        'email'=>'email',
		        'postdate'=>'postdate',
				'subject' => 'subject',
				'sread'=>'sread',
		);
		
		$items = ContactUs::model()->findAll($criteria);
	
        $this->render('index', array( 'rows' => $items, 'pages' => $pages, 'sort' => $sort, 'count' => $count ));
    }

	/**
	 * View contact us item
	 */
	public function actionview()
	{
		$this->layout = false;
		
		if( isset($_GET['id']) && ( $model = ContactUs::model()->findByPk($_GET['id']) ) )
		{	
			ContactUs::model()->updateByPk($model->id, array('sread'=>1));
			
			echo $this->render('view', array('model'=>$model), true);
		}
		else
		{
			echo Yii::t('contactus', 'Message Was Not Found.');
		}
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
		if( isset($_GET['id']) && ( $model = ContactUs::model()->findByPk($_GET['id']) ) )
		{
			$model->delete();
			
			Yii::app()->user->setFlash('success', Yii::t('contactus', 'Item Deleted.'));
			$this->redirect(array('index'));
		}
		else
		{
			$this->redirect(array('index'));
		}
	}
}