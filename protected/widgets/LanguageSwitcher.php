<?php

/**
* Switch languages widget
*
*
**/
class LanguageSwitcher extends CWidget
{
	/**
	* Display languages to choose From
	*
	*
	**/
	public function run()
	{
		$links=array();
		foreach(Yii::app()->params['languages'] as $id=>$language)
		{
			$links[]=CHtml::link(Yii::t('global', $language), array('index/index', 'lang'=>$id));
		}
		echo implode(' | ',$links);
	}
	
}