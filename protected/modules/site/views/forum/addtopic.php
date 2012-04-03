<div id="formcenter">
	<h2><?php echo Yii::t('forum', 'Create A Topic'); ?></h2>

	<p><?php echo Yii::t('forum', 'Please fill all required fields and hit the submit button once your done. It may take time for the topic to be displayed publicly.'); ?></p>

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