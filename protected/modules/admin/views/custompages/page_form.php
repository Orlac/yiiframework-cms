<?php if( isset( $_POST['preview'] ) ): ?>
	<div class="content-box"><!-- Start Content Box -->

		<div class="content-box-header">
			<h3><?php echo Yii::t('admincustompages', 'Preview Page'); ?></h3>
		</div> <!-- End .content-box-header -->

		<div class="content-box-content">
		
		<?php echo $model->content; ?>
	
		</div> <!-- End .content-box-content -->

	</div> <!-- End .content-box -->
<?php endif; ?>

<div class="content-box"><!-- Start Content Box -->
	
	<div class="content-box-header">
		<h3><?php echo $label; ?></h3>
	</div> <!-- End .content-box-header -->
	
	<div class="content-box-content">

		<?php echo CHtml::form(); ?>
		
		<?php echo CHtml::label(Yii::t('admincustompages', 'Page Title'), ''); ?>
		<?php echo CHtml::activeTextField($model, 'title', array( 'class' => 'text-input medium-input' )); ?>
		<?php echo CHtml::error($model, 'title', array( 'class' => 'input-notification errorshow png_bg' )); ?>
		
		<?php echo CHtml::label(Yii::t('admincustompages', 'Page Alias'), ''); ?>
		<?php echo $this->createAbsoluteUrl('/', array('lang'=>false)) . '/' . CHtml::activeTextField($model, 'alias', array( 'class' => 'text-input medium-input' )); ?>
		<?php echo CHtml::error($model, 'alias', array( 'class' => 'input-notification errorshow png_bg' )); ?>
		
		<?php echo CHtml::label(Yii::t('admincustompages', 'Page Tags'), ''); ?>
		<?php echo CHtml::activeTextField($model, 'tags', array( 'class' => 'text-input medium-input' )); ?>
		<?php echo CHtml::error($model, 'tags', array( 'class' => 'input-notification errorshow png_bg' )); ?>
		
		<?php echo CHtml::label(Yii::t('admincustompages', 'Page Language'), ''); ?>
		<small><?php echo Yii::t('admincustompages', 'The language the page can be displayed in.'); ?></small><br />
		<?php echo CHtml::activeDropDownList($model, 'language', Yii::app()->params['languages'], array( 'prompt' => Yii::t('admincustompages', '-- Choose --'), 'class' => 'text-input medium-input' )); ?>
		<?php echo CHtml::error($model, 'language', array( 'class' => 'input-notification errorshow png_bg' )); ?>
		
		<?php echo CHtml::label(Yii::t('admincustompages', 'Page Meta Description'), ''); ?>
		<?php echo CHtml::activeTextArea($model, 'metadesc', array( 'class' => 'text-input medium-input' )); ?>
		<?php echo CHtml::error($model, 'metadesc', array( 'class' => 'input-notification errorshow png_bg' )); ?>
		
		<?php echo CHtml::label(Yii::t('admincustompages', 'Page Meta Keywords'), ''); ?>
		<?php echo CHtml::activeTextArea($model, 'metakeys', array( 'class' => 'text-input medium-input' )); ?>
		<?php echo CHtml::error($model, 'metakeys', array( 'class' => 'input-notification errorshow png_bg' )); ?>
		
		<?php echo CHtml::label(Yii::t('admincustompages', 'Page Visibility'), ''); ?>
		<small><?php echo Yii::t('admincustompages', 'User roles that can access this page (Defaults to everyone)'); ?></small><br />
		<?php echo CHtml::activeListBox($model, 'visible', $roles, array( 'size' => 20, 'prompt' => Yii::t('admincustompages', '-- ALL --'), 'multiple' => 'multiple', 'class' => 'text-input medium-input' )); ?>
		<?php echo CHtml::error($model, 'visible', array( 'class' => 'input-notification errorshow png_bg' )); ?>
		
		<?php echo CHtml::label(Yii::t('admincustompages', 'Page Status'), ''); ?>
		<?php echo CHtml::activeDropDownList($model, 'status', array( 0 => Yii::t('admincustompages', 'Hidden (Draft)'), 1 => Yii::t('admincustompages', 'Open (Published)') ), array( 'class' => 'text-input medium-input' )); ?>
		<?php echo CHtml::error($model, 'status', array( 'class' => 'input-notification errorshow png_bg' )); ?>
		
		<?php echo CHtml::label(Yii::t('admincustompages', 'Page Content'), ''); ?>
		<?php $this->widget('application.widgets.ckeditor.CKEditor', array( 'model' => $model, 'attribute' => 'content', 'editorTemplate' => 'full' )); ?>
		<?php echo CHtml::error($model, 'content', array( 'class' => 'input-notification errorshow png_bg' )); ?>
		
		<br />
		
		<p>
			<?php echo CHtml::submitButton(Yii::t('adminglobal', 'Submit'), array('class'=>'button', 'name'=>'submit')); ?>
			<?php echo CHtml::submitButton(Yii::t('adminglobal', 'Preview'), array('class'=>'button', 'name'=>'preview')); ?>
		</p>
		
		<?php echo CHtml::endForm(); ?>
		
	</div> <!-- End .content-box-content -->
	
</div> <!-- End .content-box -->
