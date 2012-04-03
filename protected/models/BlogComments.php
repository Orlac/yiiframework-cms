<?php
/**
 * blog comments model
 */
class BlogComments extends CActiveRecord
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
		return '{{blogcomments}}';
	}
	
	/**
	 * Relations
	 */
	public function relations()
	{
		return array(
		    'post' => array(self::BELONGS_TO, 'Blog', 'postid'),
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
			'comment' => Yii::t('blog', 'Comment'),
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
	 * Scopes
	 */
	public function scopes()
	{
		return array(
					'orderDate'=>array(
		                'order'=>'postdate DESC',
		            ),
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
			array('comment', 'required' ),
		);
	}
}