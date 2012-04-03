<?php
/**
 * News letter model class
 */
class Newsletter extends CActiveRecord
{								
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
		return '{{newsletter}}';
	}						
	
	/**
	 * table data rules
	 *
	 * @return array
	 */
	public function rules()
	{
		return array(
			array('email', 'required'),
			array('email', 'email'),
			array('email', 'unique', 'message' => Yii::t('newsletter', 'That email address is already subscribed.')),
		);
	}
	
	/**
	 * Before save method
	 */
	public function beforeSave()
	{
		if( $this->isNewRecord )
		{
			$this->joined = time();
		}
		
		return parent::beforeSave();
	}
	
	/**
	 * Attribute values
	 *
	 * @return array
	 */
	public function attributeLabels()
	{
		return array(
			'email' => Yii::t('newsletter', 'Email'),
		);
	}
}