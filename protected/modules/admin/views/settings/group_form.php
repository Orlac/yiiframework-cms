<div class="content-box"><!-- Start Content Box -->
	
	<div class="content-box-header">
		<h3><?php echo $label; ?></h3>
	</div> <!-- End .content-box-header -->
	
	<div class="content-box-content">

		<?php echo CHtml::form(); ?>
		
		<?php echo CHtml::label(Yii::t('adminsettings', 'Title'), ''); ?>
		<?php echo CHtml::activeTextField($model, 'title', array( 'class' => 'text-input medium-input' )); ?>
		<?php echo CHtml::error($model, 'title', array( 'class' => 'input-notification errorshow png_bg' )); ?>
		
		<?php echo CHtml::label(Yii::t('adminsettings', 'Description'), ''); ?>
		<?php echo CHtml::activeTextArea($model, 'description', array( 'rows' => 5, 'class' => 'text-input medium-input' )); ?>
		<?php echo CHtml::error($model, 'description', array( 'class' => 'input-notification errorshow png_bg' )); ?>
		
		<?php echo CHtml::label(Yii::t('adminsettings', 'Key'), ''); ?>
		<?php echo CHtml::activeTextField($model, 'groupkey', array( 'class' => 'text-input medium-input' )); ?>
		<?php echo CHtml::error($model, 'groupkey', array( 'class' => 'input-notification errorshow png_bg' )); ?>
		
		<p><?php echo CHtml::submitButton(Yii::t('adminglobal', 'Submit'), array('class'=>'button')); ?></p>
		
		<?php echo CHtml::endForm(); ?>
		
	</div> <!-- End .content-box-content -->
	
</div> <!-- End .content-box -->
