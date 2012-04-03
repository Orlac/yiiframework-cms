<?php
/**
 * auth item model
 */
class AuthItem extends CActiveRecord
{
	/**
	 * array of auth item types
	 */
	public $types = array( CAuthItem::TYPE_OPERATION => 'Operation', CAuthItem::TYPE_TASK => 'Task', CAuthItem::TYPE_ROLE => 'Role' );
	
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
		return '{{authitem}}';
	}
	
	/**
	 * Attribute values
	 *
	 * @return array
	 */
	public function attributeLabels()
	{
		return array(
			'name' => Yii::t('adminroles', 'Auth Item Name'),
			'description' => Yii::t('adminroles', 'Description'),
			'type' => Yii::t('adminroles', 'Auth Item Type'),
			'bizrule' => Yii::t('adminroles', 'Auth Item bizRule'),
			'data' => Yii::t('adminroles', 'Auth Item Data'),
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
			array('name, type, description', 'required' ),
			array('name', 'match', 'allowEmpty'=>false, 'pattern'=>'/^[A-Za-z0-9_]+$/'),
			array('name', 'unique', 'on'=>'insert'),
			array('name', 'length', 'min' => 3, 'max' => 55 ),
			array('description', 'length', 'min' => 1, 'max' => 125 ),
			array('bizrule', 'safe'),
			array('data', 'safe'),
		);
	}
}