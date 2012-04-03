<?php
/**
 * auth item child model
 */
class AuthItemChild extends CActiveRecord
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
		return '{{authitemchild}}';
	}
	
	/**
	 * Attribute values
	 *
	 * @return array
	 */
	public function attributeLabels()
	{
		return array(
			'parent' => Yii::t('adminroles', 'Auth Item Parent'),
			'child' => Yii::t('adminroles', 'Auth Item Child'),
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
			array('parent, child', 'required' ),
			array('parent', 'CheckLoop'),
		);
	}
	/**
	 * Check we are not violating anything
	 */
	public function checkLoop()
	{
		if( $this->parent == $this->child )
		{
			$this->addError('child', Yii::t('adminroles', 'Cannot add child as an item of itself.'));
		}
		
		try
		{
			if( !Yii::app()->authManager->hasItemChild($this->parent, $this->child) )
			{
				// Create an auth item based on those parameters
				Yii::app()->authManager->addItemChild( $this->parent, $this->child );
			}
		}
		catch (CException $e)
		{
			$this->addError('child', $e->getMessage());
		}
	}
	
}