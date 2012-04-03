<?php
/**
 * Forum Topics model
 */
class ForumTopics extends CActiveRecord
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
		return '{{forumtopics}}';
	}
	
	/**
	 * Relations
	 */
	public function relations()
	{
		return array(
			'author' => array(self::BELONGS_TO, 'Members', 'authorid'),
			'posts' => array(self::HAS_MANY, 'ForumPosts', 'topicid'),
			'subs' => array(self::HAS_MANY, 'TopicSubs', 'topicid'),
			'lastauthor' => array(self::BELONGS_TO, 'Members', 'lastpostauthorid'),
			'postscount' => array(self::STAT, 'ForumPosts', 'topicid', 'condition' => 'visible=:visible OR visible=:mod', 'params' => array(':visible'=>1, ':mod'=>Yii::app()->user->checkAccess('op_forum_post_topics') ? 0 : 1)),
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
			'title' => Yii::t('forum', 'Title'),
			'content' => Yii::t('forum', 'Content'),
		);
	}
	
	/**
	 * Make sure we delete any posts
	 */
	public function beforeDelete()
	{
		foreach($this->posts as $post)
		{
			$post->delete();
		}
		
		foreach($this->subs as $sub)
		{
			$sub->delete();
		}
		
		return parent::beforeDelete();
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
					'limitIndex'=>array(
						'limit' => 10,
					),
					'byLang'=>array(
						'condition' => 'language = :lang',
						'params' => array(':lang'=>Yii::app()->language),
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
		
		$this->alias = self::model()->getAlias( $this->title );	

		if( $this->isNewRecord )
		{
			$this->language = Yii::app()->language;
		}
		
		return parent::beforeSave();
	}
	
	/**
	 * Check if a user can edit a topic
	 */
	public function canEditTopic( $model )
	{
		if( Yii::app()->user->checkAccess('op_forum_topics') )
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
	 * Get alias after clean
	 */
	public function getAlias( $alias=null )
	{
		return Yii::app()->func->makeAlias( $alias !== null ? $alias : $this->alias );
	}
	
	/**
	 * Get link to forum topic
	 */
	public function getLink( $htmlOptions=array() )
	{
		return CHtml::link( CHtml::encode($this->title), array('/forum/topic/' . $this->id . '-' . $this->alias, 'lang'=>false), $htmlOptions );
	}
	
	/**
	 * table data rules
	 *
	 * @return array
	 */
	public function rules()
	{
		return array(
			array('title, content', 'required' ),
			array('title', 'length', 'min'=>3, 'max'=>50),
		);
	}
}