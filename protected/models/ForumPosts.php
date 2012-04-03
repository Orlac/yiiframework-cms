<?php
/**
 * Forum Posts model
 */
class ForumPosts extends CActiveRecord
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
		return '{{forumposts}}';
	}
	
	/**
	 * Relations
	 */
	public function relations()
	{
		return array(
			'author' => array(self::BELONGS_TO, 'Members', 'authorid'),
			'topic' => array(self::BELONGS_TO, 'ForumTopics', 'topicid'),
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
			'content' => Yii::t('forum', 'Content'),
		);
	}
	
	/**
	 * Scopes
	 */
	public function scopes()
	{
		return array(
		            'byDate'=>array(
		                'order'=>'dateposted DESC',
		            ),
					'byDateAsc'=>array(
		                'order'=>'dateposted ASC',
		            ),
					'limitIndex'=>array(
						'limit' => 10,
					),
		        );
	}
	
	/**
	 * Before save operations
	 */
	public function beforeSave()
	{
		if( $this->isNewRecord )
		{
			$this->dateposted = time();
			$this->authorid = Yii::app()->user->id;
		}
		
		return parent::beforeSave();
	}
	
	/**
	 * Check if a user can edit a post
	 */
	public function canEditPost( $model )
	{
		if( Yii::app()->user->checkAccess('op_forum_manage') )
		{
			return true;
		}
		
		if( Yii::app()->user->id == $model->authorid )
		{
			return true;
		}
		
		return false;
	}
	
	/**
	 * Get link to forum post
	 */
	public function getLink( $topicid, $postid, $name, $alias, $htmlOptions=array() )
	{
		return CHtml::link( $name, array('/forum/topic/' . $topicid . '-' . $alias . '#post' . $postid, 'lang'=>false), $htmlOptions );
	}
	
	/**
	 * table data rules
	 *
	 * @return array
	 */
	public function rules()
	{
		return array(
			array('content', 'required' ),
		);
	}
}