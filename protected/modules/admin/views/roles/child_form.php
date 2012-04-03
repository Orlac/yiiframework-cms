<div class="content-box"><!-- Start Content Box -->
	
	<div class="content-box-header">
		<h3><?php echo $label; ?></h3>
	</div> <!-- End .content-box-header -->
	
	<div class="content-box-content">

		<?php echo CHtml::form(); ?>
		
		<?php echo CHtml::label(Yii::t('adminroles', 'Auth Item Parent'), ''); ?>
		<?php echo CHtml::activeDropDownList($model, 'parent', $roles, array( 'prompt' => Yii::t('global', '-- Choose Value --'), 'class' => 'text-input medium-input' )); ?>
		<?php echo CHtml::error($model, 'parent', array( 'class' => 'input-notification errorshow png_bg' )); ?>
		
		<?php echo CHtml::label(Yii::t('adminroles', 'Auth Item Child'), ''); ?>
		<?php echo CHtml::activeListBox($model, 'child', $roles, array( 'size' => 20, 'multiple' => 'multiple', 'prompt' => Yii::t('global', '-- Choose Value --'), 'class' => 'text-input medium-input' )); ?>
		<?php echo CHtml::error($model, 'child', array( 'class' => 'input-notification errorshow png_bg' )); ?>
		
		<p><?php echo CHtml::submitButton(Yii::t('adminglobal', 'Submit'), array('class'=>'button')); ?></p>
		
		<?php echo CHtml::endForm(); ?>
		
	</div> <!-- End .content-box-content -->
	
</div> <!-- End .content-box -->
