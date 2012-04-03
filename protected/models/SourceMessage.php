<?php
/**
 * Source message model
 */
class SourceMessage extends CActiveRecord
{
	/**
	 * @return SourceMessage
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
		return '{{SourceMessage}}';
	}
}