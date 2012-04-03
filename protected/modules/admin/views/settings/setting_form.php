<div class="content-box"><!-- Start Content Box -->
	
	<div class="content-box-header">
		<h3><?php echo $label; ?></h3>
	</div> <!-- End .content-box-header -->
	
	<div class="content-box-content">

		<?php echo CHtml::form(); ?>
		
		<?php echo CHtml::label(Yii::t('adminsettings', 'Setting Title'), ''); ?>
		<?php echo CHtml::activeTextField($model, 'title', array( 'class' => 'text-input medium-input' )); ?>
		<?php echo CHtml::error($model, 'title', array( 'class' => 'input-notification errorshow png_bg' )); ?>
		
		<?php echo CHtml::label(Yii::t('adminsettings', 'Setting Description'), ''); ?>
		<?php echo CHtml::activeTextArea($model, 'description', array( 'rows' => 5, 'class' => 'text-input medium-input' )); ?>
		<?php echo CHtml::error($model, 'description', array( 'class' => 'input-notification errorshow png_bg' )); ?>
		
		<?php echo CHtml::label(Yii::t('adminsettings', 'Setting Key'), ''); ?>
		<?php echo CHtml::activeTextField($model, 'settingkey', array( 'class' => 'text-input medium-input' )); ?>
		<?php echo CHtml::error($model, 'settingkey', array( 'class' => 'input-notification errorshow png_bg' )); ?>
		
		<?php echo CHtml::label(Yii::t('adminsettings', 'Setting Group'), ''); ?>
		<?php echo CHtml::activeDropDownList($model, 'category', Settings::model()->getGroups(), array( 'prompt' => Yii::t('adminglobal', '-- Choose Value --'), 'class' => 'small-input' )); ?>
		<?php echo CHtml::error($model, 'category', array( 'class' => 'input-notification errorshow png_bg' )); ?>
		
		<?php echo CHtml::label(Yii::t('adminsettings', 'Setting Type'), ''); ?>
		<?php echo CHtml::activeDropDownList($model, 'type', Settings::model()->getTypes(), array( 'prompt' => Yii::t('adminglobal', '-- Choose Value --'), 'class' => 'small-input' )); ?>
		<?php echo CHtml::error($model, 'type', array( 'class' => 'input-notification errorshow png_bg' )); ?>
		
		<?php echo CHtml::label(Yii::t('adminsettings', 'Setting Default Value'), ''); ?>
		<?php echo CHtml::activeTextArea($model, 'default_value', array( 'rows' => 5, 'class' => 'text-input medium-input' )); ?>
		<?php echo CHtml::error($model, 'default_value', array( 'class' => 'input-notification errorshow png_bg' )); ?>
		
		<?php echo CHtml::label(Yii::t('adminsettings', 'Setting Current Value'), ''); ?>
		<?php echo CHtml::activeTextArea($model, 'value', array( 'rows' => 5, 'class' => 'text-input medium-input' )); ?>
		<?php echo CHtml::error($model, 'value', array( 'class' => 'input-notification errorshow png_bg' )); ?>
		
		<?php echo CHtml::label(Yii::t('adminsettings', 'Setting Extra'), ''); ?>
		<?php echo CHtml::activeTextArea($model, 'extra', array( 'rows' => 5, 'class' => 'text-input medium-input' )); ?>
		<?php echo CHtml::error($model, 'extra', array( 'class' => 'input-notification errorshow png_bg' )); ?>
		
		<?php echo CHtml::label(Yii::t('adminsettings', 'Setting PHP Code'), ''); ?>
		<?php echo CHtml::activeTextArea($model, 'php', array( 'rows' => 5, 'class' => 'text-input medium-input' )); ?>
		<?php echo CHtml::error($model, 'php', array( 'class' => 'input-notification errorshow png_bg' )); ?>
		
		
		<p><?php echo CHtml::submitButton(Yii::t('adminglobal', 'Submit'), array('class'=>'button')); ?></p>
		
		<?php echo CHtml::endForm(); ?>
		
	</div> <!-- End .content-box-content -->
	
</div> <!-- End .content-box -->
