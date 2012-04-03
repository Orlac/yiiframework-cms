<div id="formcenter">
	<h2><?php echo Yii::t('login', 'Login Form'); ?></h2>

	<p><?php echo Yii::t('login', 'Please fill all required fields and hit the submit button once your done.'); ?></p>
	
	<p><?php echo CHtml::link( Yii::t('login', 'Lost Password?'), array('lostpassword') ); ?></p>

	<?php if($model->hasErrors()): ?>
	<div class="errordiv">
		<?php echo CHtml::errorSummary($model); ?>
	</div>
	<?php endif; ?>
	
	<div id='facebookloginbutton'>
		<?php echo CHtml::link( CHtml::image('http://static.ak.fbcdn.net/rsrc.php/zB6N8/hash/4li2k73z.gif', ''), 'javascript:void(0);', array( 'title' => Yii::t('login', 'Login With Facebook'), 'onClick' => "return showFaceBookAuth();" ) ); ?>
	</div>
	
	
	<?php echo CHtml::form('', 'post', array('class'=>'frmcontact')); ?>
	
	<div id='loginform'>
		
		<?php echo CHtml::activeLabel($model, 'email'); ?>
		<?php echo CHtml::activeTextField($model, 'email', array( 'class' => 'textboxcontact tiptopfocus', 'title' => Yii::t('login', 'Enter your email address') )); ?>
		<?php echo CHtml::error($model, 'email', array( 'class' => 'errorfield' )); ?>

		<br />
		
		<?php echo CHtml::activeLabel($model, 'password'); ?>
		<?php echo CHtml::activePasswordField($model, 'password', array( 'class' => 'textboxcontact tiptopfocus', 'title' => Yii::t('login', 'Enter your password') )); ?>
		<?php echo CHtml::error($model, 'password', array( 'class' => 'errorfield' )); ?>
		
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

<script>
function showOAuth()
{
	window.open('<?php echo $this->createUrl( 'facebooklogin' ); ?>');
}
function showFaceBookAuth()
{
	window.open('<?php echo $facebookLink; ?>', 'Facebook Login',"status=1,height=600,width=700");
}
</script>
