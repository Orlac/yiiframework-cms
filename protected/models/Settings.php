<?php
/**
 * Settings model
 */
class Settings extends CActiveRecord
{
	/**
	 * Supported setting types
	 */
	public $types = array(
							'text' => 'Text Field',
							'textarea' => 'Text Area',
							'dropdown' => 'Select Box',
							'multi' => 'Multi Select Box',
							//'checkbox' => 'Checkbox',
							'yesno' => 'Yes/No',
							//'radio' => 'Radio Button',
							'editor' => 'HTML Editor',
						);
	
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
		return '{{settings}}';
	}
	
	/**
	 * Relations
	 */
	public function relations()
	{
		return array(
		    'group' => array(self::BELONGS_TO, 'SettingsCats', 'id'),
		);
	}
	
	/**
	 * Get groups array
	 */
	public function getGroups()
	{
		$groups = SettingsCats::model()->findAll();
		$_temp = array();
		if( count($groups) )
		{
			foreach ($groups as $value) 
			{
				$_temp[ $value->id ] = $value->title;
			}
		}
		
		return $_temp;
	}
	
	/**
	 * Get setting types
	 */
	public function getTypes()
	{
		$_temp = array();
		if( count($this->types) )
		{
			foreach ($this->types as $key => $value) 
			{
				$_temp[ $key ] = Yii::t('adminsettings', $value);
			}
		}
		
		return $_temp;
	}
	
	/**
	 * before save
	 */
	public function beforeSave()
	{
		$this->settingkey = Yii::app()->format->text( str_replace(' ', '', $this->settingkey) );
		
		if( $this->value == '' )
		{
			$this->value = null;
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
			'title' => Yii::t('adminsettings', 'Setting Title'),
			'description' => Yii::t('adminsettings', 'Setting Description'),
			'category' => Yii::t('adminsettings', 'Setting Group'),
			'type' => Yii::t('adminsettings', 'Setting Type'),
			'default_value' => Yii::t('adminsettings', 'Setting Default Value'),
			'value' => Yii::t('adminsettings', 'Setting Value'),
			'extra' => Yii::t('adminsettings', 'Setting Extra'),
			'php' => Yii::t('adminsettings', 'Setting PHP Code'),
			'settingkey' => Yii::t('adminsettings', 'Setting Unique Key'),
		);
	}
	
	/**
	 * table data rules
	 *
	 * @return array
	 */
	public function rules()
	{
		return array(
			array('title, type, category, default_value', 'required' ),
			array('title', 'length', 'min' => 3, 'max' => 55 ),
			array('category', 'numerical', 'integerOnly' => true ),
			array('type', 'in', 'range' => array_keys($this->types) ),
			array('extra, php, value, description', 'safe'),
			array('settingkey', 'match', 'allowEmpty'=>false, 'pattern'=>'/[A-Za-z0-9]+$/'),
			array('settingkey', 'unique', 'on'=>'insert'),
		);
	}
}