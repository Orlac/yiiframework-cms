<?php
/**
 * extensions cats model
 */
class ExtensionsCats extends CActiveRecord
{	
	/**
	 * @var string depth guide
	 */	
	public $depth = '--';
	
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
		return '{{extensionscats}}';
	}
	
	/**
	 * Relations
	 */
	public function relations()
	{
		return array(
		    'posts' => array(self::HAS_MANY, 'Extensions', 'catid'),
			'parent' => array(self::BELONGS_TO, 'ExtensionsCats', 'id'),
			'childs' => array(self::HAS_MANY, 'ExtensionsCats', 'parentid'),
			'count' => array(self::STAT, 'Extensions', 'catid'),
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
			'parentid' => Yii::t('extensions', 'Sub-Category'),
			'title' => Yii::t('extensions', 'Title'),
			'description' => Yii::t('extensions', 'Description'),
			'alias' => Yii::t('extensions', 'Alias'),
			'language' => Yii::t('extensions', 'Language'),
			'position' => Yii::t('extensions', 'Position'),
			'metadesc' => Yii::t('extensions', 'Meta Description'),
			'metakeys' => Yii::t('extensions', 'Meta Keywords'),
			'readonly' => Yii::t('extensions', 'ReadOnly Category'),
			'viewperms' => Yii::t('extensions', 'Viewing Permission'),
			'addpostsperms' => Yii::t('extensions', 'Adding Extensions Permission'),
			'addcommentsperms' => Yii::t('extensions', 'Adding Comments Permission'),
			'addfilesperms' => Yii::t('extensions', 'Adding Files Permission'),
			'autoaddperms' => Yii::t('extensions', 'Auto Approved Post Permission'),
		);
	}
	
	/**
	 * Before save handler
	 */
	public function beforeSave()
	{
		$this->alias = self::model()->getAlias( $this->alias );
		$this->language = ( is_array($this->language) && count($this->language) ) ? implode(',', $this->language) : $this->language;
		
		$this->viewperms = ( is_array($this->viewperms) && count($this->viewperms) ) ? implode(',', $this->viewperms) : $this->viewperms;
		$this->addpostsperms = ( is_array($this->addpostsperms) && count($this->addpostsperms) ) ? implode(',', $this->addpostsperms) : $this->addpostsperms;
		$this->addcommentsperms = ( is_array($this->addcommentsperms) && count($this->addcommentsperms) ) ? implode(',', $this->addcommentsperms) : $this->addcommentsperms;
		$this->addfilesperms = ( is_array($this->addfilesperms) && count($this->addfilesperms) ) ? implode(',', $this->addfilesperms) : $this->addfilesperms;
		$this->autoaddperms = ( is_array($this->autoaddperms) && count($this->autoaddperms) ) ? implode(',', $this->autoaddperms) : $this->autoaddperms;
		
		// Fix position
		if( $this->isNewRecord )
		{
			if( $this->parentid )
			{
				$max = Yii::app()->db->createCommand('SELECT MAX(position) AS max FROM '. $this->tableName() .' WHERE parentid = ' . $this->parentid)->queryRow();
			}
			else
			{
				$max = Yii::app()->db->createCommand('SELECT MAX(position) AS max FROM '. $this->tableName() .' WHERE parentid IS NULL')->queryRow();
			}
			
			$this->position = $max['max'] + 1;
		}
		
		return parent::beforeSave();
	}
	
	/**
	 * table data rules
	 *
	 * @return array
	 */
	public function rules()
	{
		return array(
			array( 'title, alias', 'required' ),
			array('alias', 'unique'),
			array('title, alias', 'length', 'min'=>3, 'max'=>55),
			array('alias', 'match', 'allowEmpty'=>false, 'pattern'=>'/[A-Za-z0-9-_\x80-\xFF]+$/'),
			array('metadesc, metakeys', 'length', 'max'=>155),
			array('position, readonly, parentid', 'numerical'),
			array('description, language, viewperms, addpostsperms, addcommentsperms, addfilesperms, autoaddperms', 'safe'),
			array('parentid', 'detectLoop', 'on'=>'update'),
		);
	}
	
	/**
	 * Get alias after clean
	 */
	public function getAlias( $alias=null )
	{
		return Yii::app()->func->makeAlias( $alias !== null ? $alias : $this->alias);
	}
	
	/**
	 * Scopes
	 */
	public function scopes()
	{
		return array(
		            'byPosition'=>array(
		                'order'=>'position ASC',
		            ),
		        );
	}
	
	/**
	 * detect loop
	 * If we are trying to put a category under one of it's parents
	 */
	public function detectLoop()
	{
		if( $this->parentid )
		{
			$ids   = $this->GetChildren( $this );
			$ids[] = $this->id;
		
			if ( in_array( $this->parentid, $ids ) )
			{
				$this->addError('parentid', Yii::t('extensions', "Category cannot be placed under one of it's child's"));
			}
		}
	}
	
	/**
	 * Grab all cats and filter according to the arguments passed
	 * 
	 */
	public function getCatsForMember( $lang=null, $perm='view', $memberid=null )
	{
		$categories = self::model()->getRootCats();
		
		$_temp = array();
		
		if( count( $categories ) )
		{
			foreach( $categories as $row )
			{
				$_temp[ $row->id ] = $row;
			}
		}
		
		$_loop = array();
		
		// Loop again
		foreach($_temp as &$r)
		{	
			// Grab child first
			$childs = self::model()->GetChildren( $r );
			
			
			// was language specified?
			if( $lang )
			{
				if( $r->language )
				{
					$specs = explode(',', $r->language);
					if( !in_array(Yii::app()->language, $specs) )
					{
						// Remove child as well and continue
						foreach( $childs as $child )
						{
							unset( $_temp[ $child->id ], $_loop[ $child->id ] );
						}
						
						unset( $_temp[ $r->id ], $_loop[ $r->id ] );
						
						continue;
					}
				}
			}
			
			// Perms
			switch( $perm )
			{	
				case 'add':
					$permission = 'addpostsperms';
				break;
				
				case 'view':
				default:
					$permission = 'viewperms';
				break;
			}
			
			// Check permissions
			if( $permission )
			{
				if( $r->$permission )
				{
					$specs = explode(',', $r->$permission);
					$userid = $memberid ? $memberid : Yii::app()->user->id;
					$pass = false;
				
					foreach($specs as $spec)
					{
						if( Yii::app()->authManager->checkAccess($spec, $userid) )
						{
							$pass = true;
							break;
						}
					}
				
					if( !$pass )
					{
						// Remove child as well and continue
						foreach( $childs as $child )
						{
							unset( $_temp[ $child->id ], $_loop[ $child->id ]);
						}
						unset( $_temp[ $r->id ], $_loop[ $r->id ] );

						continue;
					}
				}
			}
			
			$_loop[ $r->id ] = $r;
		}
		
		return $_loop;
		
	}
	
	/**
	 * Internal function to grab an array of children ids
	 */
	public function GetChildren( $model )
	{
		$data = array();
		foreach($model->childs as $child)
		{
			$data[] = $child;
			$data = array_merge($data, $this->GetChildren($child));
		}
		
		return $data;
	}
	
	/**
	 * Get root categories
	 */
	public function getRootCats()
	{
		$data = array();
        $models = self::model()->byPosition()->findAll('parentid IS NULL');
		if( count( $models ) )
		{
			foreach($models as $model) 
			{
				$data[] = $model;
				$data = array_merge($data, $this->getRecursiveCats($model));
            }
		}
           return $data;
    }
	
	/**
	 * Recursive function to get child categories
	 */
	public function getRecursiveCats($cat, $depth='--')
	{
		$data = array();
        foreach($cat->childs as $model) 
		{
			$model->title = '|' .$depth . ' ' . $model->title;
			$data[] = $model;
			$data = array_merge($data, $this->getRecursiveCats($model, $depth . $depth));
        }

		return $data;
	}
	
	/**
	 * Get root categories
	 */
	public function getRootCategories()
	{
			$data = array();
            $models = self::model()->byPosition()->findAll('parentid IS NULL');
			if( count( $models ) )
			{
				foreach($models as $model) 
				{
						$data[ $model->id ] = array_merge(
										$model->getAttributes(),
										array('children' => self::getChilds($model->id))
										);
	            }
			}
            return $data;
    }
	
	/**
	 * Get child categories
	 */
    public function getChilds($id, $depth = '--') 
	{
			$data = array();
			$childs = self::model()->findAll('parentid=:parent', array(':parent'=>$id));
			if( count($childs) )
			{
	            foreach($childs as $model) 
				{
						$model->title = $depth . $model->title;
	                    $data[ $model->id ] = array_merge(
										$model->getAttributes(),
										array('children' => self::getChilds($model->id, $depth . $depth))
										);
	            }
			}
            return $data;
    }
}