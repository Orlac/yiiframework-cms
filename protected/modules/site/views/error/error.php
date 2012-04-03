<div><!-- Start Content Box -->

	<div>
		<h3><?php echo Yii::t('global', 'Error'); ?></h3>
	</div> <!-- End .content-box-header -->

	<div>
	
		<?php if( $error['code'] == 404 ): ?>
			<p><?php echo Yii::t('error', 'Sorry, But the page you were looking for was not found.'); ?></p>
		<?php elseif( $error['code'] == 403 ): ?>	
			<p><?php echo Yii::t('error', 'Sorry, But you are not authorized to view this page.'); ?></p>
		<?php else: ?>	
			<p><?php echo $error['message']; ?></p>
		<?php endif; ?>

	<?php if( YII_DEBUG ): ?>
		
		<div style='text-align:left; direction: ltr;'>
			<div><?php echo Yii::t('admindebug', 'File:'); ?></div>
			<div><?php echo $error['file'] . '(<b>'. $error['line'] .'</b>)' ; ?></div>


			<div><?php echo Yii::t('admindebug', 'Type:'); ?></div>
			<div><?php echo $error['type'] . ' ' . $error['code']; ?></div>
		
			<?php if( $error['trace'] ): ?>
				<?php foreach( explode("\n", $error['trace']) as $trace ): ?>
					<div><?php echo $trace; ?></div>
				<?php endforeach; ?>	
			<?php endif; ?>
			<?php if( isset($error['source']) && count($error['source']) ): ?>
				<div>
				<?php foreach( $error['source'] as $number => $line ): ?>
						<?php echo $number . $line; ?><br />
				<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	</div> <!-- End .content-box-content -->

</div>