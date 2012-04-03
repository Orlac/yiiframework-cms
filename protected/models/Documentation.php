<?php
/**
 * Documentation model
 */
class Documentation extends CActiveRecord
{	
	/**
	 * Documentations directories
	 */
	public $documentationFolders = array( 'guide' => 'Guide', 'blog' => 'Blog' );
	
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
		return '{{documentations}}';
	}
	
	/**
	 * Relations
	 */
	public function relations()
	{
		return array(
		    'updater' => array(self::BELONGS_TO, 'Members', 'last_updated_member'),
			'comments' => array(self::HAS_MANY, 'DocumentationComments', 'docid'),
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
			'name' => Yii::t('docs', 'Documentation Name'),
			'mkey' => Yii::t('docs', 'Documentation Key'),
			'description' => Yii::t('docs', 'Docmentation Description'),
			'content' => Yii::t('docs', 'Documentation Content'),
			'type' => Yii::t('docs', 'Documentation Type'),
		);
	}
	
	/**
	 * Before save operations
	 */
	public function beforeSave()
	{
		$this->last_updated = time();
		$this->last_updated_member = Yii::app()->user->id;
		
		return parent::beforeSave();
	}
	
	/**
	 * Work the rating and return
	 */
	public function getRating()
	{
		return $this->rating ? ceil($this->rating/$this->totalvotes) : 0;
	}
	
	/**
	 * Scopes
	 */
	public function scopes()
	{
		return array(
		            'noSource'=>array(
		                'condition'=>'language != :source ',
						'params' => array(':source' => 'source'),
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
			array('name, mkey, content, type', 'required' ),
			array('name, mkey', 'length', 'min' => 3, 'max' => 55 ),
			array('language, last_updated, last_updated_member, views, rating, description', 'safe'),
			array('type', 'in', 'range' => array_keys($this->documentationFolders) ),
			array('mkey', 'uniqueMkey', 'on'=>'insert'),
		);
	}
	
	/**
	 * Get link to tutorial
	 */
	public function getLink( $htmlOptions=array() )
	{
		return CHtml::link( CHtml::encode($this->name), array('/documentation/guide/' . $this->type . '/topic/' . $this->mkey , 'lang'=>false), $htmlOptions );
	}
	
	/**
	 * Make sure the ( mkey, type ) is a unique combination
	 */
	public function uniqueMkey()
	{
		$exists = self::model()->exists('mkey=:key AND type=:type AND language=:lang', array( ':lang' => $this->language, ':key' => $this->mkey, ':type' => $this->type ));
		if( $exists )
		{
			$this->addError('mkey', Yii::t('admindocs', 'There is already a topic with that key in this documentation type.'));
		}
	}
}