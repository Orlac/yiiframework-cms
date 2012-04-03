<div class="content-box"><!-- Start Content Box -->
	
	<div class="content-box-header">
		<h3><?php echo $label; ?></h3>
	</div> <!-- End .content-box-header -->
	
	<div class="content-box-content">

		<?php echo CHtml::form(); ?>
		
		<?php echo CHtml::activeLabel($model, 'title'); ?>
		<?php echo CHtml::activeTextField($model, 'title', array( 'class' => 'text-input medium-input' )); ?>
		<?php echo CHtml::error($model, 'title', array( 'class' => 'input-notification errorshow png_bg' )); ?>
		
		<?php echo CHtml::activeLabel($model, 'description'); ?>
		<?php echo CHtml::activeTextArea($model, 'description', array( 'class' => 'text-input medium-input' )); ?>
		<?php echo CHtml::error($model, 'description', array( 'class' => 'input-notification errorshow png_bg' )); ?>
		
		<?php echo CHtml::activeLabel($model, 'alias'); ?>
		<?php echo $this->createAbsoluteUrl('/tutorials/category/', array('lang'=>false)) . '/' . CHtml::activeTextField($model, 'alias', array( 'class' => 'text-input medium-input' )); ?>
		<?php echo CHtml::error($model, 'alias', array( 'class' => 'input-notification errorshow png_bg' )); ?>
		
		<?php echo CHtml::activeLabel($model, 'parentid'); ?>
		<small><?php echo Yii::t('admintuts', 'Choose a category that this category will be a child of.'); ?></small><br />
		<?php echo CHtml::activeDropDownList($model, 'parentid', $parents, array( 'prompt' => Yii::t('admintuts', '-- Root --'), 'class' => 'text-input medium-input' )); ?>
		<?php echo CHtml::error($model, 'parentid', array( 'class' => 'input-notification errorshow png_bg' )); ?>
		
		<?php echo CHtml::activeLabel($model, 'language'); ?>
		<small><?php echo Yii::t('admintuts', 'The language the category can be displayed in.'); ?></small><br />
		<?php echo CHtml::activeDropDownList($model, 'language', Yii::app()->params['languages'], array( 'prompt' => Yii::t('adminglobal', '-- ALL --'), 'class' => 'text-input medium-input' )); ?>
		<?php echo CHtml::error($model, 'language', array( 'class' => 'input-notification errorshow png_bg' )); ?>
		
		<?php echo CHtml::activeLabel($model, 'metadesc'); ?>
		<?php echo CHtml::activeTextArea($model, 'metadesc', array( 'class' => 'text-input medium-input' )); ?>
		<?php echo CHtml::error($model, 'metadesc', array( 'class' => 'input-notification errorshow png_bg' )); ?>
		
		<?php echo CHtml::activeLabel($model, 'metakeys'); ?>
		<?php echo CHtml::activeTextArea($model, 'metakeys', array( 'class' => 'text-input medium-input' )); ?>
		<?php echo CHtml::error($model, 'metakeys', array( 'class' => 'input-notification errorshow png_bg' )); ?>
		
		<?php echo CHtml::activeLabel($model, 'readonly'); ?>
		<?php echo CHtml::activeCheckBox($model, 'readonly'); ?>
		<?php echo CHtml::error($model, 'readonly', array( 'class' => 'input-notification errorshow png_bg' )); ?>
		
		<?php echo CHtml::activeLabel($model, 'viewperms'); ?>
		<small><?php echo Yii::t('admintuts', 'User roles that can view this category (Defaults to nobody)'); ?></small><br />
		<?php echo CHtml::activeListBox($model, 'viewperms', $roles, array( 'size' => 20, 'multiple' => 'multiple', 'class' => 'text-input medium-input' )); ?>
		<?php echo CHtml::error($model, 'viewperms', array( 'class' => 'input-notification errorshow png_bg' )); ?>
		
		<?php echo CHtml::activeLabel($model, 'addtutorialperms'); ?>
		<small><?php echo Yii::t('admintuts', 'User roles that can add tutorials to this category (Defaults to nobody)'); ?></small><br />
		<?php echo CHtml::activeListBox($model, 'addtutorialperms', $roles, array( 'size' => 20, 'multiple' => 'multiple', 'class' => 'text-input medium-input' )); ?>
		<?php echo CHtml::error($model, 'addtutorialperms', array( 'class' => 'input-notification errorshow png_bg' )); ?>
		
		<?php echo CHtml::activeLabel($model, 'addcommentsperms'); ?>
		<small><?php echo Yii::t('admintuts', 'User roles that can add tutorial comments to this category (Defaults to nobody)'); ?></small><br />
		<?php echo CHtml::activeListBox($model, 'addcommentsperms', $roles, array( 'size' => 20, 'multiple' => 'multiple', 'class' => 'text-input medium-input' )); ?>
		<?php echo CHtml::error($model, 'addcommentsperms', array( 'class' => 'input-notification errorshow png_bg' )); ?>
		
		<?php echo CHtml::activeLabel($model, 'addfilesperms'); ?>
		<small><?php echo Yii::t('admintuts', 'User roles that can add tutorial files to this category (Defaults to nobody)'); ?></small><br />
		<?php echo CHtml::activeListBox($model, 'addfilesperms', $roles, array( 'size' => 20, 'multiple' => 'multiple', 'class' => 'text-input medium-input' )); ?>
		<?php echo CHtml::error($model, 'addfilesperms', array( 'class' => 'input-notification errorshow png_bg' )); ?>
		
		<?php echo CHtml::activeLabel($model, 'autoaddperms'); ?>
		<small><?php echo Yii::t('admintuts', 'User roles that can add tutorials this category without the need of manual approval (Defaults to nobody)'); ?></small><br />
		<?php echo CHtml::activeListBox($model, 'autoaddperms', $roles, array( 'size' => 20, 'multiple' => 'multiple', 'class' => 'text-input medium-input' )); ?>
		<?php echo CHtml::error($model, 'autoaddperms', array( 'class' => 'input-notification errorshow png_bg' )); ?>
		
		<br />
		
		<p>
			<?php echo CHtml::submitButton(Yii::t('adminglobal', 'Submit'), array('class'=>'button', 'name'=>'submit')); ?>
		</p>
		
		<?php echo CHtml::endForm(); ?>
		
	</div> <!-- End .content-box-content -->
	
</div> <!-- End .content-box -->
