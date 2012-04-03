<div class="content-box"><!-- Start Content Box -->
	
	<div class="content-box-header">
		<h3><?php echo Yii::t('admindocs', 'Comments'); ?> (<?php echo Yii::app()->format->number($count); ?>)</h3>
	</div> <!-- End .content-box-header -->
	
	<div class="content-box-content">
			<?php echo CHtml::form(); ?>
			<table>
				<thead>
					<tr>
					   <th style='width: 5%;'><input class="check-all" type="checkbox" /></th>
					   <th style='width: 10%;'><?php echo Yii::t('admindocs', 'ID'); ?></th>
					   <th style='width: 20%;'><?php echo $sort->link('authorid', Yii::t('admindocs', 'Author'), array( 'class' => 'tooltip', 'title' => Yii::t('admindocs', 'Sort list by author') ) ); ?></th>
					   <th style='width: 20%;'><?php echo $sort->link('postdate', Yii::t('admindocs', 'Post Date'), array( 'class' => 'tooltip', 'title' => Yii::t('admindocs', 'Sort list by date') ) ); ?></th>
					   <th style='width: 10%;'><?php echo $sort->link('visible', Yii::t('admindocs', 'Status'), array( 'class' => 'tooltip', 'title' => Yii::t('admindocs', 'Sort list by visibility') ) ); ?></th>
					   <th style='width: 20%;'><?php echo Yii::t('admindocs', 'Comment'); ?></th>
					   <th style='width: 10%;'><?php echo Yii::t('admindocs', 'Options'); ?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="6">	
							<div class="bulk-actions align-left">
								<select name="bulkoperations">
									<option value=""><?php echo Yii::t('global', '-- Choose Action --'); ?></option>
									<option value="bulkdelete"><?php echo Yii::t('global', 'Delete Selected'); ?></option>
									<option value="bulkapprove"><?php echo Yii::t('global', 'Approve Selected'); ?></option>
									<option value="bulkunapprove"><?php echo Yii::t('global', 'Un-Approve Selected'); ?></option>
								</select>
								<?php echo CHtml::submitButton( Yii::t('global', 'Apply'), array( 'confirm' => Yii::t('adminglobal', 'Are you sure you would like to perform a bulk operation?'), 'class'=>'button')); ?>
							</div>
													
							<?php $this->widget('widgets.admin.pager', array( 'pages' => $pages )); ?>
							<div class="clear"></div>
						</td>
					</tr>
				</tfoot>
				<tbody>
				<?php if ( count($comments) ): ?>
					
					<?php foreach ($comments as $row): ?>
						<tr>
							<td><?php echo CHtml::checkbox( 'comment[' . $row->id.']' ); ?></td>
							<td><?php echo $row->id; ?></td>
							<td><?php echo $row->author ? $row->author->username : Yii::t('global', 'Unknown'); ?></td>
							<td class='tooltip' title='<?php echo Yii::t('admindocs', 'Joined Date'); ?>'><?php echo Yii::app()->dateFormatter->formatDateTime($row->postdate, 'short', 'short'); ?></td>
							<td>
								<?php echo CHtml::link( CHtml::image( Yii::app()->themeManager->baseUrl . '/images/icons/'. ($row->visible ? 'tick_circle' : 'cross') . '.png' ), array('documentation/togglestatus', 'id' => $row->id), array( 'class' => 'tooltip', 'title' => Yii::t('admindocs', 'Toggle comment status!') ) ); ?>
							</td>
							<td><?php echo CHtml::encode(wordwrap($row->comment, 20, '...')); ?></td>
							<td>
								<!-- Icons -->
								 <a href="<?php echo $this->createUrl('documentation/deletecomment', array( 'id' => $row->id )); ?>" title="<?php echo Yii::t('admindocs', 'Delete this comment!'); ?> "class='tooltip deletelink'><img src="<?php echo Yii::app()->themeManager->baseUrl; ?>/images/icons/cross.png" alt="Delete" /></a>
							</td>
						</tr>
					<?php endforeach ?>
					
				<?php else: ?>	
					<tr>
						<td colspan='7' style='text-align:center;'><?php echo Yii::t('admindocs', 'No Comments Found.'); ?></td>
					</tr>
				<?php endif; ?>
				</tbody>
			</table>
		<?php echo CHtml::endForm(); ?>
	</div> <!-- End .content-box-content -->
	
</div> <!-- End .content-box -->
