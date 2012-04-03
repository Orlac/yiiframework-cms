<div class="content-box"><!-- Start Content Box -->

	<div class="content-box-header">
		<h3><?php echo Yii::t('adminglobal', 'Error'); ?></h3>
	</div> <!-- End .content-box-header -->

	<div class="content-box-content">
	
	<p><?php echo $error['message']; ?></p>

	<?php if( YII_DEBUG ): ?>
		
		<table>
			<tbody>
				<tr>
					<td style='width: 5%;'><?php echo Yii::t('admindebug', 'File:'); ?></td>
					<td style='width: 95%;'><?php echo $error['file'] . '(<b>'. $error['line'] .'</b>)' ; ?></td>
				</tr>
				<tr>
					<td style='width: 5%;'><?php echo Yii::t('admindebug', 'Type:'); ?></td>
					<td style='width: 95%;'><?php echo $error['type'] . ' ' . $error['code']; ?></td>
				</tr>
				<?php if( $error['trace'] ): ?>
					<?php foreach( explode("\n", $error['trace']) as $trace ): ?>
						<tr>
							<td colspan='2'><?php echo $trace; ?></td>
						</tr>
					<?php endforeach; ?>	
				<?php endif; ?>
				<?php if( count($error['source']) ): ?>
					<tr>
						<td colspan='2'>
					<?php foreach( $error['source'] as $number => $line ): ?>
							<?php echo $number . $line; ?><br />
					<?php endforeach; ?>
						</td>
					</tr>	
				<?php endif; ?>
				
			</tbody>
		</table>
		
	<?php endif; ?>
	</div> <!-- End .content-box-content -->

</div>