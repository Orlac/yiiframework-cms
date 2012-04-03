<div style='width: 600px;'>
	<h3><?php echo Yii::t('contactus', 'Viewing Message'); ?></h3>
 
	<p>
		<strong><?php echo Yii::app()->dateFormatter->formatDateTime($model->postdate); ?></strong> <?php echo Yii::t('contactus', 'By {name}', array( '{name}' => $model->name )); ?><br />
		<?php echo CHtml::encode($model->content); ?>
	</p>
	
	<?php echo CHtml::form('', 'post', array('onsubmit'=>"return false;")); ?>
		<?php echo CHtml::hiddenField('id', $model->id); ?>
		<h4><?php echo Yii::t('contactus', 'Reply'); ?></h4>
		
		<fieldset>
			<strong><?php echo Yii::t('contactus', 'Email'); ?></strong> <?php echo CHtml::textField('email', $model->email, array( 'class' => 'textarea' )); ?>
			<br /><strong><?php echo Yii::t('contactus', 'Message'); ?></strong><br />
			<?php echo CHtml::textArea('message', '', array( 'cols' => 79, 'rows' => 5, 'class' => 'textarea' )); ?>
		</fieldset>

		
		<fieldset>			
			<?php echo CHtml::submitButton( Yii::t('contactus', 'Send'), array( 'id' => 'sendmsg', 'class'=>'button') ); ?>
		</fieldset>
		
	<?php echo CHtml::endForm(); ?>
	
</div>