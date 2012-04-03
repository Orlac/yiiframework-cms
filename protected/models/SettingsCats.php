<?php
/**
 * Settings groups model
 */
class SettingsCats extends CActiveRecord
{
	/**
	 * @return object
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
		return '{{settingscats}}';
	}
	
	/**
	 * Attribute values
	 *
	 * @return array
	 */
	public function attributeLabels()
	{
		return array(
			'title' => Yii::t('adminsettings', 'Title'),
			'description' => Yii::t('adminsettings', 'Description'),
			'groupkey' => Yii::t('adminsettings', 'Group Unique Key'),
		);
	}
	
	/**
	 * before save
	 */
	public function beforeSave()
	{
		$this->groupkey = Yii::app()->format->text( str_replace(' ', '', $this->groupkey) );
		
		return parent::beforeSave();
	}
	
	/**
	 * table data rules
	 *
	 * @return array
	 */
	public function rules()
	{
		return array(
			array('title', 'required' ),
			array('groupkey, title', 'match', 'allowEmpty'=>false, 'pattern'=>'/[A-Za-z0-9]+$/'),
			array('groupkey', 'unique', 'on'=>'insert'),
			array('title', 'length', 'min' => 3, 'max' => 55 ),
			array('description', 'length', 'min' => 0, 'max' => 155 ),
		);
	}
	
	public function relations()
	{
		return array(
		    'settings' => array(self::HAS_MANY, 'Settings', 'category'),
			'count' => array(self::STAT, 'Settings', 'category','condition'=>'category'),
		);
	}
}