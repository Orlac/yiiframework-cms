<?php
/**
 * Extensions Files model
 */
class ExtensionsFiles extends CActiveRecord
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
		return '{{extensionsfiles}}';
	}
	
	/**
	 * Relations
	 */
	public function relations()
	{
		return array(
		    'extension' => array(self::BELONGS_TO, 'Extensions', 'extensionid'),
			'author' => array(self::BELONGS_TO, 'Members', 'authorid'),
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
			'description' => Yii::t('extensions', 'Description'),
			'realname' => Yii::t('extensions', 'File'),
		);
	}
	
	/**
	 * Before save operations
	 */
	public function beforeSave()
	{
		if( $this->isNewRecord )
		{
			$this->postdate = time();
			$this->authorid = Yii::app()->user->id;
		}
		
		return parent::beforeSave();
	}
	
	/**
	 * Get alias after clean
	 */
	public function getAlias( $alias=null )
	{
		return Yii::app()->func->makeAlias( $alias !== null ? $alias : $this->alias );
	}
	
	/**
	 * Get link to extension
	 */
	public function getLink( $name, $alias, $htmlOptions=array() )
	{
		return CHtml::link( $name, array('/extensions/download/' . $this->id . '-' . $alias, 'lang'=>false), $htmlOptions );
	}
	
	/**
	 * table data rules
	 *
	 * @return array
	 */
	public function rules()
	{
		return array(
			array('realname', 'file', 'types'=>'tar, zip, rar, gz, tgz', 'maxSize' => 5300000 ),
			array('description', 'length', 'max'=>55),
		);
	}
}