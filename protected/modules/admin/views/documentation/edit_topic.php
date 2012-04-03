<div class="content-box">
	
	<div class="content-box-header">
		
		<h3><?php echo Yii::t('admindocs', 'Editing "{topic}"', array('{topic}'=>$model->name)); ?></h3>
		
		<div class="clear"></div>
		
	</div>
	
	<div class="content-box-content">
		
		<?php echo CHtml::form(); ?>
		
		<?php echo CHtml::activeLabel($model, 'name'); ?>
		<?php echo CHtml::activeTextField($model, 'name', array(  'class' => 'text-input medium-input' )); ?>
		
		<?php echo CHtml::activeLabel($model, 'description'); ?>
		<?php echo CHtml::activeTextArea($model, 'description'); ?>
		
		<?php echo CHtml::activeLabel($model, 'content'); ?>
		<?php $this->widget('widgets.markitup.markitup', array( 'model' => $model, 'attribute' => 'content' )); ?>
		
		<?php echo CHtml::submitButton(Yii::t('admindocs', 'Save'), array('name'=>'submit')); ?>
		
		<?php echo CHtml::endForm(); ?>
		
	</div>
	
</div>
