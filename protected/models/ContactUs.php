<?php
/**
 * contact us form model class
 */
class ContactUs extends CActiveRecord
{	
	public $verifyCode;
								
	/**
	 * @return contact us
	 */
	public static function model()
	{
		return parent::model(__CLASS__);
	}

	/**
	 * @return string Table name
	 */
	public function tableName()
	{
		return '{{contactus}}';
	}						
	
	/**
	 * table data rules
	 *
	 * @return array
	 */
	public function rules()
	{
		return array(
			array('email, subject, content, name', 'required'),
			array('email', 'email'),
			array('verifyCode', 'captcha'),
		);
	}
	
	/**
	 * Attribute values
	 *
	 * @return array
	 */
	public function attributeLabels()
	{
		return array(
			'name' => Yii::t('contactus', 'Name'),
			'email' => Yii::t('contactus', 'Email'),
			'subject' => Yii::t('contactus', 'Subject'),
			'content' => Yii::t('contactus', 'Message'),
			'verifyCode' => Yii::t('contactus', 'Security Code'),
		);
	}
	
	/**
	 * Before save method
	 */
	public function beforeSave()
	{
		if( $this->isNewRecord )
		{
			$this->postdate = time();
		}
		
		return parent::beforeSave();
	}
	
	/**
	 * Get topics for the subject drop down
	 */
	public function getTopics()
	{
		$topics = array();
		$topics[''] = Yii::t('contactus', '-- Choose --');
		if( isset(Yii::app()->params['contactustopics']) && Yii::app()->params['contactustopics'] )
		{
			$explode = explode("\n", Yii::app()->params['contactustopics']);
			
			// Loop to translate
			foreach($explode as $topic)
			{
				$topics[ $topic ] = Yii::t('contactus', $topic);
			}
		}
		
		return $topics;
	}
	
}