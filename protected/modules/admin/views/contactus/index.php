<div class="content-box"><!-- Start Content Box -->
	
	<div class="content-box-header">
		<h3><?php echo Yii::t('admincontactus', 'Contact Us Submitted Forms'); ?> (<?php echo $count; ?>)</h3>
	</div> <!-- End .content-box-header -->
	
	<div class="content-box-content">
		<?php echo CHtml::form(); ?>
		<table>
			<thead>
				<tr>
				<th style='width: 5%;'><input class="check-all" type="checkbox" /></th>
				   <th style='width: 20%'><?php echo $sort->link('name', Yii::t('admincontactus', 'Name'), array( 'class' => 'tooltip', 'title' => Yii::t('admincontactus', 'Sort list by name') ) ); ?></th>
				   <th style='width: 20%'><?php echo $sort->link('email', Yii::t('admincontactus', 'Email'), array( 'class' => 'tooltip', 'title' => Yii::t('admincontactus', 'Sort list by email') ) ); ?></th>
				   <th style='width: 20%'><?php echo $sort->link('subject', Yii::t('admincontactus', 'Subject'), array( 'class' => 'tooltip', 'title' => Yii::t('admincontactus', 'Sort list by subject') ) ); ?></th>
				   <th style='width: 20%'><?php echo $sort->link('postdate', Yii::t('admincontactus', 'Date'), array( 'class' => 'tooltip', 'title' => Yii::t('admincontactus', 'Sort list by date') ) ); ?></th>
				   <th style='width: 10%'><?php echo $sort->link('sread', Yii::t('admincontactus', 'Read'), array( 'class' => 'tooltip', 'title' => Yii::t('admincontactus', 'Sort list by read status') ) ); ?></th>
				   <th style='width: 10%'><?php echo Yii::t('admincontactus', 'Options'); ?></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="6">	
						<div class="bulk-actions align-left">
							<select name="bulkoperations">
								<option value=""><?php echo Yii::t('global', '-- Choose Action --'); ?></option>
								<option value="read"><?php echo Yii::t('contactus', 'Mark As Read'); ?></option>
								<option value="notread"><?php echo Yii::t('contactus', 'Mark As Not Read'); ?></option>
								<option value="delete"><?php echo Yii::t('global', 'Delete Selected'); ?></option>
							</select>
							<?php echo CHtml::submitButton( Yii::t('global', 'Apply'), array( 'confirm' => Yii::t('adminglobal', 'Are you sure you would like to perform a bulk operation?'), 'class'=>'button')); ?>
						</div>
												
						<?php $this->widget('widgets.admin.pager', array( 'pages' => $pages )); ?>
						<div class="clear"></div>
					</td>
				</tr>
			</tfoot>
			<tbody>
			<?php if ( count($rows) ): ?>
				
				<?php foreach ($rows as $row): ?>
					<tr>
						<td><?php echo CHtml::checkbox( 'record[' . $row->id.']' ); ?></td>
						<td><a href="<?php echo $this->createUrl('contactus/view', array( 'id' => $row->id )); ?>" rel='modal' title="<?php echo Yii::t('admincontactus', 'View Item'); ?>" class='tooltip'><?php echo CHtml::encode($row->name); ?></a></td>
						<td><?php echo CHtml::encode($row->email); ?></td>
						<td><?php echo CHtml::encode($row->subject); ?></td>
						<td><?php echo Yii::app()->dateFormatter->formatDateTime($row->postdate); ?></td>
						<td><?php echo CHtml::image( Yii::app()->themeManager->baseUrl . '/images/icons/'. ($row->sread ? 'tick_circle' : 'cross') . '.png' ); ?></td>
						<td>
							<a href="<?php echo $this->createUrl('delete', array( 'id' => $row->id )); ?>" title="<?php echo Yii::t('contactus', 'Delete this item!'); ?> "class='tooltip deletelink'><img src="<?php echo Yii::app()->themeManager->baseUrl; ?>/images/icons/cross.png" alt="Delete" /></a>
						</td>
					</tr>
				<?php endforeach ?>

			<?php else: ?>	
				<tr>
					<td colspan='7' style='text-align:center;'><?php echo Yii::t('admincontactus', 'No items found.'); ?></td>
				</tr>
			<?php endif; ?>
			</tbody>
		</table>
		<?php echo CHtml::endForm(); ?>
	</div> <!-- End .content-box-content -->
	
</div> <!-- End .content-box -->

<?php 
$msg = Yii::t('contactus', 'Reply Sent.');
CHtml::ajaxSubmitButton(Yii::t('contactus', 'Send'), 
								   array('send'), 
								   array( 'success' => "function () { alert('{$msg}'); jQuery(document).trigger('close.facebox'); }" ), 
								   array('class'=>'button','id'=>'sendmsg')); ?>