<?php

class markitup extends CInputWidget
{
	    public function run(){
	        parent::run();

	        list($name, $id) = $this->resolveNameID();

	        $baseDir = dirname(__FILE__);
	        $assets = Yii::app()->getAssetManager()->publish($baseDir.DIRECTORY_SEPARATOR.'markitup'.DIRECTORY_SEPARATOR.'markitup');

	        $cs = Yii::app()->getClientScript();

	        $cs->registerScriptFile($assets.'/jquery.markitup.js', CClientScript::POS_END );
			$cs->registerScriptFile($assets.'/sets/markdown/set.js', CClientScript::POS_END);
			
			$cs->registerCssFile($assets.'/skins/simple/style.css');
			$cs->registerCssFile($assets.'/sets/markdown/style.css');
			
			if($this->hasModel()) 
			{
	            $html = CHtml::activeTextArea($this->model, $this->attribute, $this->htmlOptions);
	        }
			else 
			{
	            $html = CHtml::textArea($name, $this->value, $this->htmlOptions);
	        }

	        $js =<<<EOF
				$('#{$id}').markItUp(mySettings);
EOF;
	        $cs->registerScript('Yii.'.get_class($this).'#'.$id, $js, CClientScript::POS_READY);

	        echo $html;

	    }
}