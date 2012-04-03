<div class="content-box"><!-- Start Content Box -->
	
	<div class="content-box-header">
		<h3><?php echo $label; ?></h3>
	</div> <!-- End .content-box-header -->
	
	<div class="content-box-content">

		<?php echo CHtml::form(); ?>

		<?php echo CHtml::label( Yii::t('admintuts', 'Move Categories To'), '' ); ?>
		<small><?php echo Yii::t('admintuts', 'Choose a category to move all subcategories into'); ?></small><br />
		<?php echo CHtml::dropDownList('catsmoveto', (isset($_POST['catsmoveto'])) ? $_POST['catsmoveto'] : '', $parents, array( 'prompt' => Yii::t('admintuts', '-- Choose --'), 'class' => 'text-input medium-input' )); ?>
		
		
		<?php echo CHtml::label( Yii::t('admintuts', 'Move Tutorials To'), '' ); ?>
		<small><?php echo Yii::t('admintuts', 'Choose a category to move all tutorials into'); ?></small><br />
		<?php echo CHtml::dropDownList('catsmovetuts', (isset($_POST['catsmovetuts'])) ? $_POST['catsmovetuts'] : '', $parents, array( 'prompt' => Yii::t('admintuts', '-- Choose --'), 'class' => 'text-input medium-input' )); ?>
		
		
		<br />
		
		<p>
			<?php echo CHtml::submitButton(Yii::t('adminglobal', 'Submit'), array('class'=>'button', 'name'=>'submit')); ?>
		</p>
		
		<?php echo CHtml::endForm(); ?>
		
	</div> <!-- End .content-box-content -->
	
</div> <!-- End .content-box -->
