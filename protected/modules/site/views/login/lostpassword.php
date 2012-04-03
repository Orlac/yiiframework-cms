<div id="formcenter">
	<h2><?php echo Yii::t('login', 'Lost Password Form'); ?></h2>

	<p><?php echo Yii::t('login', 'Please fill all required fields and hit the submit button once your done.'); ?></p>
	
	<p><?php echo Yii::t('login', 'This screen will guide you through the process of reseting you password.<br /><br />
									Your email is required as well as the security code to prevent from abusing the password reset feature.<br /><br />
									The form will be submitted when the email is validated and exists in our records and the security code entered is valid.<br /><br />
									You will receive an email with a password reset link that you will need to click in order to complete the password reset process.<br /><br />
									Once that is done you will receive another email with the actual new randomly generated password, That you will be able to change it at any time through your profile.'); ?></p>

	<?php if($model->hasErrors()): ?>
	<div class="errordiv">
		<?php echo CHtml::errorSummary($model); ?>
	</div>
	<?php endif; ?>
	
	<?php echo CHtml::form('', 'post', array('class'=>'frmcontact')); ?>
	
	<div>
		
		<?php echo CHtml::activeLabel($model, 'email'); ?>
		<?php echo CHtml::activeTextField($model, 'email', array( 'class' => 'textboxcontact tiptopfocus', 'title' => Yii::t('login', 'Enter your email address') )); ?>
		<?php echo CHtml::error($model, 'email', array( 'class' => 'errorfield' )); ?>

		<br />
		
		<?php echo CHtml::activeLabel($model, 'verifyCode'); ?>
		<?php echo CHtml::activeTextField($model, 'verifyCode', array( 'class' => 'textboxcontact tiptopfocus', 'title' => Yii::t('login', 'Enter the text displayed in the image below') )); ?>
		<?php echo CHtml::error($model, 'verifyCode', array( 'class' => 'errorfield' )); ?>
		<br />
		<?php $this->widget('CCaptcha'); ?>

		<br /><br /><br />
		
		<p>
			<?php echo CHtml::submitButton(Yii::t('global', 'Submit'), array('class'=>'submitcomment', 'name'=>'submit')); ?>
		</p>
		
	</div>
	
	<?php echo CHtml::endForm(); ?>
	
</div>