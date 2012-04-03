<div class='floatright'>
	<?php echo CHtml::link(Yii::t('adminsettings', 'Add Setting Group'), array('settings/addgroup'), array( 'class' => 'button' )); ?>
	<?php echo CHtml::link(Yii::t('adminsettings', 'Add Setting'), array('settings/addsetting'), array( 'class' => 'button' )); ?>
</div>

<div class="content-box"><!-- Start Content Box -->
	
	<div class="content-box-header">
		<h3><?php echo Yii::t('adminsettings', 'Settings Categories'); ?></h3>
	</div> <!-- End .content-box-header -->
	
	<div class="content-box-content">
			<table>
				<thead>
					<tr>
					   <th style='width: 20%'><?php echo Yii::t('adminsettings', 'Title'); ?></th>
					   <th style='width: 50%'><?php echo Yii::t('adminsettings', 'Description'); ?></th>
					   <th style='width: 10%'><?php echo Yii::t('adminsettings', 'Key'); ?></th>
					   <th style='width: 5%'><?php echo Yii::t('adminsettings', 'Count'); ?></th>
					   <th style='width: 15%'><?php echo Yii::t('adminsettings', 'Options'); ?></th>
					</tr>
				</thead>
				<tbody>
				<?php if ( count($settings) ): ?>
					
					<?php foreach ($settings as $row): ?>
						<tr>
							<td><a href="<?php echo $this->createUrl('settings/viewgroup', array( 'id' => $row->id )); ?>" title="<?php echo Yii::t('adminsettings', 'View category settings'); ?>" class='tooltip'><?php echo CHtml::encode($row->title); ?></a></td>
							<td><?php echo CHtml::encode($row->description); ?></td>
							<td><?php echo CHtml::encode($row->groupkey); ?></td>
							<td class='tooltip' title='<?php echo Yii::t('adminsettings', 'Total Settings'); ?>'><?php echo $row->count; ?></td>
							<td>
								<!-- Icons -->
								<a href="<?php echo $this->createUrl('settings/addsetting', array( 'cid' => $row->id )); ?>" title="<?php echo Yii::t('adminglobal', 'Add setting to this group'); ?>" class='tooltip'><img src="<?php echo Yii::app()->themeManager->baseUrl; ?>/images/icons/add.png" alt="add" /></a>
								 <a href="<?php echo $this->createUrl('settings/editgroup', array( 'id' => $row->id )); ?>" title="<?php echo Yii::t('adminglobal', 'Edit this group'); ?>" class='tooltip'><img src="<?php echo Yii::app()->themeManager->baseUrl; ?>/images/icons/pencil.png" alt="Edit" /></a>
								 <a href="<?php echo $this->createUrl('settings/deletegroup', array( 'id' => $row->id )); ?>" title="<?php echo Yii::t('adminglobal', 'Delete this group!'); ?> "class='tooltip deletelink'><img src="<?php echo Yii::app()->themeManager->baseUrl; ?>/images/icons/cross.png" alt="Delete" /></a>
							</td>
						</tr>
					<?php endforeach ?>
	
				<?php else: ?>	
					<tr>
						<td colspan='5' style='text-align:center;'><?php echo Yii::t('adminsettings', 'No categories found.'); ?></td>
					</tr>
				<?php endif; ?>
				</tbody>
			</table>
		
	</div> <!-- End .content-box-content -->
	
</div> <!-- End .content-box -->