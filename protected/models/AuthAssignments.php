<?php
/**
 * auth item child model
 */
class AuthAssignments extends CActiveRecord
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
		return '{{authassignment}}';
	}	
}