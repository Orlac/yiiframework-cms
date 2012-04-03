<div class='floatright'>
	<?php echo CHtml::link(Yii::t('adminsettings', 'Add Setting'), array('settings/addsetting', 'cid' => $_GET['id']), array( 'class' => 'button' )); ?>
</div>

<div class="content-box"><!-- Start Content Box -->
	
	<div class="content-box-header">
		<h3><?php echo Yii::t('adminsettings', 'Configure Settings'); ?></h3>
	</div> <!-- End .content-box-header -->
	
	<div class="content-box-content">
		<?php echo CHtml::form(); ?>
		<table>
			
			<?php if( count($settings) ): ?>
				
				<?php foreach ($settings as $row): ?>
				
					<tr>
						<td style='width: 30%;'>
							<span<?php if( CHtml::encode($row->description) ): ?> class='tooltip' title='<?php echo CHtml::encode($row->description); ?>'<?php endif; ?>>
								<?php echo CHtml::encode($row->title); ?>
							</span>
							<?php if( $row->value && $row->default_value != $row->value ): ?><span style='color:red;'><?php echo Yii::t('adminsettings', '(Setting Changed)'); ?></span><?php endif; ?>
						</td>
						<td style='width: 60%;'><?php $this->parseSetting( $row ); ?></td>
						<td style='width: 10%;'>
							<!-- Icons -->
							<?php if( $row->value && $row->default_value != $row->value ): ?>
								<a href="<?php echo $this->createUrl('settings/revertsetting', array( 'id' => $row->id )); ?>" title="<?php echo Yii::t('adminglobal', 'Revert setting value to the default value.'); ?>" class='tooltip'><img src="<?php echo Yii::app()->themeManager->baseUrl; ?>/images/icons/revert.png" alt="Revert" /></a>
							<?php endif; ?>
							 <a href="<?php echo $this->createUrl('settings/editsetting', array( 'id' => $row->id )); ?>" title="<?php echo Yii::t('adminglobal', 'Edit this setting'); ?>" class='tooltip'><img src="<?php echo Yii::app()->themeManager->baseUrl; ?>/images/icons/pencil.png" alt="Edit" /></a>
							 <a href="<?php echo $this->createUrl('settings/deletesetting', array( 'id' => $row->id )); ?>" title="<?php echo Yii::t('adminglobal', 'Delete this setting!'); ?> "class='tooltip deletelink'><img src="<?php echo Yii::app()->themeManager->baseUrl; ?>/images/icons/cross.png" alt="Delete" /></a>
						</td>
					</tr>	
					
				<?php endforeach; ?>
				
			<?php else: ?>
				
				<tr>
					<td style='text-align:center;'><?php echo Yii::t('adminsetings', 'No Settings Added Yet.'); ?></td>
				</tr>
					
			<?php endif; ?>	
		</table>	
		<?php if( count($settings) ): ?>
		<p style='text-align:center;'><?php echo CHtml::submitButton(Yii::t('adminglobal', 'Save'), array( 'name' => 'submit', 'class'=>'button')); ?></p>
		<?php endif; ?>
		<?php echo CHtml::endForm(); ?>
		
	</div> <!-- End .content-box-content -->
	
</div> <!-- End .content-box -->