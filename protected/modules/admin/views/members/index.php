<div class='floatright'>
	<?php echo CHtml::link(Yii::t('adminmembers', 'Add User'), array('members/adduser'), array( 'class' => 'button' )); ?>
</div>

<div class="content-box"><!-- Start Content Box -->
	
	<div class="content-box-header">
		<h3><?php echo Yii::t('adminmembers', 'Users'); ?> (<?php echo Yii::app()->format->number($count); ?>)</h3>
	</div> <!-- End .content-box-header -->
	
	<div class="content-box-content">
			<?php echo CHtml::form(); ?>
			<table>
				<thead>
					<tr>
					   <th style='width: 5%;'><input class="check-all" type="checkbox" /></th>
					   <th style='width: 5%'>&nbsp;</th>
					   <th style='width: 20%;'><?php echo $sort->link('username', Yii::t('adminmembers', 'Username'), array( 'class' => 'tooltip', 'title' => Yii::t('adminmembers', 'Sort user list by username') ) ); ?></th>
					   <th style='width: 25%;'><?php echo $sort->link('email', Yii::t('adminmembers', 'Email'), array( 'class' => 'tooltip', 'title' => Yii::t('adminmembers', 'Sort user list by email') ) ); ?></th>
					   <th style='width: 10%;'><?php echo $sort->link('role', Yii::t('adminmembers', 'Role'), array( 'class' => 'tooltip', 'title' => Yii::t('adminmembers', 'Sort user list by role') ) ); ?></th>
					   <th style='width: 20%;'><?php echo $sort->link('joined', Yii::t('adminmembers', 'Joined'), array( 'class' => 'tooltip', 'title' => Yii::t('adminmembers', 'Sort user list by joined date') ) ); ?></th>
					   <th style='width: 15%;'><?php echo Yii::t('adminmembers', 'Options'); ?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="7">	
							<div class="bulk-actions align-left">
								<select name="bulkoperations">
									<option value=""><?php echo Yii::t('global', '-- Choose Action --'); ?></option>
									<option value="bulkdelete"><?php echo Yii::t('global', 'Delete Selected'); ?></option>
								</select>
								<?php echo CHtml::submitButton( Yii::t('global', 'Apply'), array( 'confirm' => Yii::t('adminmembers', 'Are you sure you would like to perform a bulk operation?'), 'class'=>'button')); ?>
							</div>
													
							<?php $this->widget('widgets.admin.pager', array( 'pages' => $pages )); ?>
							<div class="clear"></div>
						</td>
					</tr>
				</tfoot>
				<tbody>
				<?php if ( count($members) ): ?>
					
					<?php foreach ($members as $row): ?>
						<tr>
							<td><?php echo CHtml::checkbox( 'user[' . $row->id.']' ); ?></td>
							<td><?php $this->widget('application.extensions.VGGravatarWidget', array( 'email' => $row->email, 'size' => 20 )); ?></td>
							<td><a href="<?php echo $this->createUrl('members/viewuser', array( 'id' => $row->id )); ?>" title="<?php echo Yii::t('adminmembers', 'View User'); ?>" class='tooltip'><?php echo CHtml::encode($row->username); ?></a></td>
							<td><?php echo CHtml::encode($row->email); ?></td>
							<td><?php echo CHtml::encode($row->role); ?></td>
							<td class='tooltip' title='<?php echo Yii::t('adminmembers', 'Joined Date'); ?>'><?php echo Yii::app()->dateFormatter->formatDateTime($row->joined, 'short', 'short'); ?></td>
							<td>
								<!-- Icons -->
								 <a href="<?php echo $this->createUrl('members/edituser', array( 'id' => $row->id )); ?>" title="<?php echo Yii::t('adminmembers', 'Edit this member'); ?>" class='tooltip'><img src="<?php echo Yii::app()->themeManager->baseUrl; ?>/images/icons/pencil.png" alt="Edit" /></a>
								 <a href="<?php echo $this->createUrl('members/deleteuser', array( 'id' => $row->id )); ?>" title="<?php echo Yii::t('adminmembers', 'Delete this member!'); ?> "class='tooltip deletelink'><img src="<?php echo Yii::app()->themeManager->baseUrl; ?>/images/icons/cross.png" alt="Delete" /></a>
							</td>
						</tr>
					<?php endforeach ?>
					
				<?php else: ?>	
					<tr>
						<td colspan='6' style='text-align:center;'><?php echo Yii::t('adminmembers', 'No Members Found.'); ?></td>
					</tr>
				<?php endif; ?>
				</tbody>
			</table>
		<?php echo CHtml::endForm(); ?>
	</div> <!-- End .content-box-content -->
	
</div> <!-- End .content-box -->
