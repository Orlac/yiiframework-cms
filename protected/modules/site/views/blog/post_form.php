<div id="formcenter">
	<h2><?php echo $label; ?></h2>

	<p><?php echo Yii::t('blog', 'Please fill all required fields and hit the submit button once your done. It may take time for the post to be displayed publicly.'); ?></p>

	<?php if($model->hasErrors()): ?>
	<div class="errordiv">
		<?php echo CHtml::errorSummary($model); ?>
	</div>
	<?php endif; ?>
	
	<?php echo CHtml::form('', 'post', array('class'=>'frmcontact')); ?>
	
	<div>
		
		<?php echo CHtml::activeLabel($model, 'title'); ?>
		<?php echo CHtml::activeTextField($model, 'title', array( 'class' => 'textboxcontact' )); ?>
		<?php echo CHtml::error($model, 'title', array( 'class' => 'errorfield' )); ?>
		<br />
		<?php echo CHtml::activeLabel($model, 'description'); ?>
		<?php echo CHtml::activeTextArea($model, 'description', array( 'class' => 'textareacontact' )); ?>
		<?php echo CHtml::error($model, 'description', array( 'class' => 'errorfield' )); ?>

		<br />
		<?php echo CHtml::activeLabel($model, 'catid'); ?>
		<small><?php echo Yii::t('admintuts', 'Choose a category for this post'); ?></small><br />
		<?php echo CHtml::activeDropDownList($model, 'catid', $categories, array( 'prompt' => Yii::t('global', '-- Choose --'), 'class' => 'textboxcontact' )); ?>
		<?php echo CHtml::error($model, 'catid', array( 'class' => 'errorfield' )); ?>

		<br />
		<?php echo CHtml::activeLabel($model, 'metadesc'); ?>
		<?php echo CHtml::activeTextArea($model, 'metadesc', array( 'class' => 'textareacontact' )); ?>
		<?php echo CHtml::error($model, 'metadesc', array( 'class' => 'errorfield' )); ?>
		<br />
		<?php echo CHtml::activeLabel($model, 'metakeys'); ?>
		<?php echo CHtml::activeTextArea($model, 'metakeys', array( 'class' => 'textareacontact' )); ?>
		<?php echo CHtml::error($model, 'metakeys', array( 'class' => 'errorfield' )); ?>
		<br />
		
		<?php if( Yii::app()->user->checkAccess('op_blog_manage') ):  ?>
		
		<?php echo CHtml::activeLabel($model, 'status'); ?>
		<?php echo CHtml::activeDropDownList($model, 'status', array( 0 => Yii::t('global', 'Hidden (Draft)'), 1 => Yii::t('global', 'Open (Published)') ), array( 'class' => 'textboxcontact' )); ?>
		<?php echo CHtml::error($model, 'status', array( 'class' => 'errorfield' )); ?>
		<br />
		
		<?php endif; ?>
		
		<?php echo CHtml::activeLabel($model, 'content'); ?><br />
		<?php $this->widget('widgets.markitup.markitup', array( 'model' => $model, 'attribute' => 'content' )); ?>
		<?php echo CHtml::error($model, 'content', array( 'class' => 'errorfield' )); ?>
		
		<br />
		
		<p>
			<?php echo CHtml::submitButton(Yii::t('adminglobal', 'Submit'), array('class'=>'submitcomment', 'name'=>'submit')); ?>
		</p>
		
	</div>
	
	<?php echo CHtml::endForm(); ?>
	
</div>