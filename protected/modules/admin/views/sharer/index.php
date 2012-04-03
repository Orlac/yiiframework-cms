<div class="content-box"><!-- Start Content Box -->
	
	<div class="content-box-header">
		<h3><?php echo Yii::t('sharer', 'Share Item On Social Networks'); ?></h3>
	</div> <!-- End .content-box-header -->
	
	<div class="content-box-content">
		
		<?php echo CHtml::form(); ?>

		
		<?php echo CHtml::textArea('content', '', array( 'class' => 'text-input medium-input' ));  ?>
		
		<p>
			<?php echo CHtml::submitButton(Yii::t('sharer', 'Submit'), array('name'=>'share')); ?>
		</p>
		
		<?php echo CHtml::endForm(); ?>
		
	</div> <!-- End .content-box-content -->
	
</div> <!-- End .content-box -->