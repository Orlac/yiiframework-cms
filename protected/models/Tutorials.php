<?php
/**
 * Tutorials model
 */
class Tutorials extends CActiveRecord
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
		return '{{tutorials}}';
	}
	
	/**
	 * Relations
	 */
	public function relations()
	{
		return array(
		    'category' => array(self::BELONGS_TO, 'TutorialsCats', 'catid'),
			'author' => array(self::BELONGS_TO, 'Members', 'authorid'),
			'comments' => array(self::HAS_MANY, 'TutorialsComments', 'tutorialid'),
			'lastauthor' => array(self::BELONGS_TO, 'Members', 'last_updated_author'),
			'commentscount' => array(self::STAT, 'TutorialsComments', 'tutorialid'),
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
			'catid' => Yii::t('tutorials', 'Category'),
			'title' => Yii::t('tutorials', 'Title'),
			'description' => Yii::t('tutorials', 'Description'),
			'content' => Yii::t('tutorials', 'Content'),
			'alias' => Yii::t('tutorials', 'Alias'),
			'language' => Yii::t('tutorials', 'Language'),
			'metadesc' => Yii::t('tutorials', 'Meta Description'),
			'metakeys' => Yii::t('tutorials', 'Meta Keywords'),
			'status' => Yii::t('tutorials', 'Tutorial Approved'),
		);
	}
	
	/**
	 * Make sure we delete any comments
	 */
	public function beforeDelete()
	{
		foreach($this->comments as $comment)
		{
			$comment->delete();
		}
		
		return parent::beforeDelete();
	}
	
	/**
	 * Work the rating and return
	 */
	public function getRating()
	{
		return $this->rating ? ceil($this->rating/$this->totalvotes) : 0;
	}
	
	/**
	 * Get link to tutorial
	 */
	public function getLink( $name, $alias, $htmlOptions=array() )
	{
		return CHtml::link( CHtml::encode($name), array('/tutorials/view/' . $alias, 'lang'=>false), $htmlOptions );
	}
	
	/**
	 * Get link to tutorial
	 */
	public function getModelLink( $htmlOptions=array() )
	{
		return $this->getLink( $this->title, $this->alias, $htmlOptions );
	}
	
	/**
	 * Scopes
	 */
	public function scopes()
	{
		return array(
		            'byDate'=>array(
		                'order'=>'postdate DESC',
		            ),
					'limitIndex'=>array(
						'limit' => 10,
					),
					'byLang'=>array(
						'condition' => 't.language = :lang',
						'params' => array(':lang'=>Yii::app()->language),
					),
		        );
	}
	
	/**
	 * Grab tutorials from the database by categories
	 */
	public function grabTutorialsByCats( $cats, $limit=10 )
	{
		// Grab the language data
		$criteria = new CDbCriteria;
		if( is_array($cats) && count($cats) )
		{
			$criteria->addInCondition('catid', $cats);
		}
		else
		{
			$criteria->addCondition('catid='.intval($cats));
			
		}
		
		// Can we see hidden tutorials?
		if( !Yii::app()->user->checkAccess('op_tutorials_manage') )
		{
			$criteria->addCondition('status=1');
		}
		
		// Order by post date
		$criteria->order = 'postdate DESC';
		
		
		$count = self::model()->count($criteria);
		$pages = new CPagination($count);
		$pages->pageSize = $limit;
		
		$pages->applyLimit($criteria);
		
		$tutorials = self::model()->byLang()->with(array('commentscount', 'author', 'category'))->findAll($criteria);
		
		return array( 'tutorials' => $tutorials, 'pages' => $pages );
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
		else
		{
			$this->last_updated_date = time();
			$this->last_updated_author = Yii::app()->user->id;
		}
		
		if( $this->alias )
		{
			$this->alias = self::model()->getAlias( $this->alias );
		}
		else
		{
			$this->alias = self::model()->getAlias( $this->title );
		}
		
		// Check to see if it exists
		if( $this->isNewRecord )
		{
			$find = self::model()->find('alias=:alias', array(':alias'=>$this->alias));
			
			if( $find )
			{
				$this->addError('alias', Yii::t('admintuts', 'Sorry, That alias is already in use.'));
				return;
			}
		}
		else
		{
			$find = self::model()->find('alias=:alias AND id != :id', array(':alias'=>$this->alias, ':id'=>$this->id));
			
			if( $find )
			{
				$this->addError('alias', Yii::t('admintuts', 'Sorry, That alias is already in use.'));
				return;
			}
		}
		
		if( $this->isNewRecord )
		{
			if( !$this->language )
			{
				$this->language = Yii::app()->language;
			}
		}
		
		// Don't post to a category that is readonly
		if( $this->catid )
		{
			$find = TutorialsCats::model()->findByPk($this->catid);
			
			if( ( $find && $find->readonly ) )
			{
				$this->addError('catid', Yii::t('tutorials', 'Sorry, That category is readonly.'));
				return;
			}
		}
		
		$this->language = ( is_array($this->language) && count($this->language) ) ? implode(',', $this->language) : $this->language;
		
		return parent::beforeSave();
	}
	
	/**
	 * Check if a user can edit a tutorial
	 */
	public function canEditTutorial( $model )
	{
		if( Yii::app()->user->checkAccess('op_tutorials_manage') )
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
	 * table data rules
	 *
	 * @return array
	 */
	public function rules()
	{
		return array(
			array('title, description, content, catid', 'required' ),
			array('title', 'length', 'min'=>3, 'max'=>88),
			array('catid, status', 'numerical'),
			array('alias', 'safe'),
			//array('title', 'match', 'allowEmpty'=>false, 'pattern'=>'/[A-Za-z0-9\x80-\xFF]+$/'),
			array('metadesc, metakeys', 'length', 'max'=>550),
			array('language', 'safe'),
		);
	}
}