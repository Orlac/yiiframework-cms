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
		<?php echo $this->createAbsoluteUrl('/extensions/view/', array('lang'=>false)) . '/' . CHtml::activeTextField($model, 'alias', array( 'class' => 'text-input medium-input' )); ?>
		<?php echo CHtml::error($model, 'alias', array( 'class' => 'input-notification errorshow png_bg' )); ?>
		
		<?php echo CHtml::activeLabel($model, 'catid'); ?>
		<small><?php echo Yii::t('extensions', 'Choose a category for this post'); ?></small><br />
		<?php echo CHtml::activeDropDownList($model, 'catid', $parents, array( 'prompt' => Yii::t('extensions', '-- Choose --'), 'class' => 'text-input medium-input' )); ?>
		<?php echo CHtml::error($model, 'catid', array( 'class' => 'input-notification errorshow png_bg' )); ?>
		
		<?php echo CHtml::activeLabel($model, 'language'); ?>
		<small><?php echo Yii::t('extensions', 'The language the post can be displayed in.'); ?></small><br />
		<?php echo CHtml::activeDropDownList($model, 'language', Yii::app()->params['languages'], array( 'prompt' => Yii::t('adminglobal', '-- ALL --'), 'class' => 'text-input medium-input' )); ?>
		<?php echo CHtml::error($model, 'language', array( 'class' => 'input-notification errorshow png_bg' )); ?>
		
		<?php echo CHtml::activeLabel($model, 'metadesc'); ?>
		<?php echo CHtml::activeTextArea($model, 'metadesc', array( 'class' => 'text-input medium-input' )); ?>
		<?php echo CHtml::error($model, 'metadesc', array( 'class' => 'input-notification errorshow png_bg' )); ?>
		
		<?php echo CHtml::activeLabel($model, 'metakeys'); ?>
		<?php echo CHtml::activeTextArea($model, 'metakeys', array( 'class' => 'text-input medium-input' )); ?>
		<?php echo CHtml::error($model, 'metakeys', array( 'class' => 'input-notification errorshow png_bg' )); ?>
		
		<?php echo CHtml::activeLabel($model, 'status'); ?>
		<?php echo CHtml::activeDropDownList($model, 'status', array( 0 => Yii::t('extensions', 'Hidden (Draft)'), 1 => Yii::t('extensions', 'Open (Published)') ), array( 'class' => 'text-input medium-input' )); ?>
		<?php echo CHtml::error($model, 'status', array( 'class' => 'input-notification errorshow png_bg' )); ?>
		
		<?php echo CHtml::activeLabel($model, 'content'); ?>
		<?php $this->widget('widgets.markitup.markitup', array( 'model' => $model, 'attribute' => 'content' )); ?>
		<?php echo CHtml::error($model, 'content', array( 'class' => 'input-notification errorshow png_bg' )); ?>
		
		<br />
		
		<p>
			<?php echo CHtml::submitButton(Yii::t('adminglobal', 'Submit'), array('class'=>'button', 'name'=>'submit')); ?>
		</p>
		
		<?php echo CHtml::endForm(); ?>
		
	</div> <!-- End .content-box-content -->
	
</div> <!-- End .content-box -->
