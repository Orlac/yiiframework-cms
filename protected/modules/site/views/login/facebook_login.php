<div class='notification information png_bg'>
	<div>
		<table>
			<tr>
				<td style='width:5%;padding:5px;'><img src="https://graph.facebook.com/<?php echo $info['id']; ?>/picture"></td>
				<td style='width:95%;padding:5px;'><?php echo Yii::t('login', '<b>{name}</b>, Your almost there! Just complete one of the forms below to finish the login process. If you have already signed up to this website in the past and have an active account that you would like to associate with your facebook account please fill in the <i>Associate Account Form</i>, If you never signed-up to this website and would like to use your facebook account here just complete the <i>Sign-Up Form</i>. Once you have completed one of the forms, Just hit the submit button in the form you have chosen.', array( '{name}' => $info['name'] )); ?></td>
			</tr>	
		</table>
	</div>
</div>

<div id="formcenter">
<div id='facebooklogin'>

<div class='floatleft' style='width:45%'>	
	<h2><?php echo Yii::t('login', 'Associate Account Form'); ?></h2>
	<p><?php echo Yii::t('login', 'Associate an account with your facebook account.'); ?></p>
	
	<?php echo CHtml::form(); ?>
	<table>
		<tr>
			<td><?php echo CHtml::activeLabel($facebookForm, 'email'); ?></td>
			<td>
				<?php echo CHtml::activeTextField($facebookForm, 'email', array( 'class' => 'textboxcontact tiptopfocus', 'title' => Yii::t('login', 'Enter your email address') )); ?>
				<?php echo CHtml::error($facebookForm, 'email', array( 'class' => 'errorfield' )); ?>
			</td>
		</tr>
		
		<tr>
			<td><?php echo CHtml::activeLabel($facebookForm, 'password'); ?></td>
			<td>
				<?php echo CHtml::activePasswordField($facebookForm, 'password', array( 'class' => 'textboxcontact tiptopfocus', 'title' => Yii::t('login', 'Enter your password') )); ?>
				<?php echo CHtml::error($facebookForm, 'password', array( 'class' => 'errorfield' )); ?>
			</td>	
		</tr>
		<tr>
			<td style='text-align:center;' colspan='2'><?php echo CHtml::submitButton( Yii::t('global', 'Submit') ); ?></td>
		</tr>		
	</table>		
	
	<?php echo CHtml::endForm(); ?>
	
</div>

<div class='floatleft paddright' style='width:45%;'>	
	<h2><?php echo Yii::t('login', 'Sign-Up Form'); ?></h2>
	<p><?php echo Yii::t('login', 'Signup using your facebook account information'); ?></p>
	
	<?php echo CHtml::form(); ?>
	<table>
		<tr>
			<td><?php echo CHtml::activeLabel($facebookSignForm, 'username'); ?></td>
			<td>
				<?php echo CHtml::activeTextField($facebookSignForm, 'username', array( 'class' => 'textboxcontact tiptopfocus', 'title' => Yii::t('login', 'Enter your username') )); ?>
				<?php echo CHtml::error($facebookSignForm, 'username', array( 'class' => 'errorfield' )); ?>
			</td>
		</tr>
		
		<tr>
			<td><?php echo CHtml::activeLabel($facebookSignForm, 'email'); ?></td>
			<td>
				<?php echo CHtml::activeTextField($facebookSignForm, 'email', array( 'class' => 'textboxcontact tiptopfocus', 'title' => Yii::t('login', 'Enter your email address') )); ?>
				<?php echo CHtml::error($facebookSignForm, 'email', array( 'class' => 'errorfield' )); ?>
			</td>
		</tr>
		
		<tr>
			<td><?php echo CHtml::activeLabel($facebookSignForm, 'password'); ?></td>
			<td>
				<?php echo CHtml::activePasswordField($facebookSignForm, 'password', array( 'class' => 'textboxcontact tiptopfocus', 'title' => Yii::t('login', 'Enter your password') )); ?>
				<?php echo CHtml::error($facebookSignForm, 'password', array( 'class' => 'errorfield' )); ?>
			</td>	
		</tr>
		<tr>
			<td style='text-align:center;' colspan='2'><?php echo CHtml::submitButton( Yii::t('global', 'Submit') ); ?></td>
		</tr>		
	</table>		
	
	<?php echo CHtml::endForm(); ?>
	
</div>

<div class='floatright' style='width:10%'>
	&nbsp;
</div>

<div class="clear"></div><br />

</div>
</div>
