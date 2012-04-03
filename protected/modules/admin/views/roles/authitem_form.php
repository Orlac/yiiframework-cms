<div class="content-box"><!-- Start Content Box -->
	
	<div class="content-box-header">
		<h3><?php echo $label; ?></h3>
	</div> <!-- End .content-box-header -->
	
	<div class="content-box-content">

		<?php echo CHtml::form(); ?>
		
		<?php echo CHtml::label(Yii::t('adminroles', 'Auth Item Name'), ''); ?>
		<?php echo CHtml::activeTextField($model, 'name', array( 'class' => 'text-input medium-input' )); ?>
		<?php echo CHtml::error($model, 'name', array( 'class' => 'input-notification errorshow png_bg' )); ?>
		
		<?php echo CHtml::label(Yii::t('adminroles', 'Auth Item Description'), ''); ?>
		<?php echo CHtml::activeTextField($model, 'description', array( 'class' => 'text-input medium-input' )); ?>
		<?php echo CHtml::error($model, 'description', array( 'class' => 'input-notification errorshow png_bg' )); ?>
		
		<?php echo CHtml::label(Yii::t('adminroles', 'Auth Item Type'), ''); ?>
		<?php echo CHtml::activeDropDownList($model, 'type', AuthItem::model()->types, array( 'prompt' => Yii::t('global', '-- Choose Value --'), 'tabindex'=>3,  'class' => 'text-input medium-input' )); ?>
		<?php echo CHtml::error($model, 'type', array( 'class' => 'input-notification errorshow png_bg' )); ?>
		
		<?php echo CHtml::label(Yii::t('adminroles', 'Auth Item bizRule'), ''); ?>
		<?php echo CHtml::activeTextArea($model, 'bizrule', array( 'class' => 'text-input medium-input' )); ?>
		<?php echo CHtml::error($model, 'bizrule', array( 'class' => 'input-notification errorshow png_bg' )); ?>
		
		<?php echo CHtml::label(Yii::t('adminroles', 'Auth Item Data'), ''); ?>
		<?php echo CHtml::activeTextArea($model, 'data', array( 'class' => 'text-input medium-input' )); ?>
		<?php echo CHtml::error($model, 'data', array( 'class' => 'input-notification errorshow png_bg' )); ?>
		
		<p><?php echo CHtml::submitButton(Yii::t('adminglobal', 'Submit'), array('class'=>'button')); ?></p>
		
		<?php echo CHtml::endForm(); ?>
		
	</div> <!-- End .content-box-content -->
	
</div> <!-- End .content-box -->
