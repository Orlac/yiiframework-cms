<div class='floatright'>
	<?php echo CHtml::link(Yii::t('adminroles', 'Add Auth Item Child'), array('roles/addauthitemchild', 'parent'=>$_GET['parent']), array( 'class' => 'button' )); ?>
</div>

<div class="content-box"><!-- Start Content Box -->
	
	<div class="content-box-header">
		<h3><?php echo Yii::t('adminroles', 'Child Auth Items'); ?> (<?php echo $count; ?>)</h3>
	</div> <!-- End .content-box-header -->
	
	<div class="content-box-content">
		
		<table>
			<thead>
				<tr>
				   <th style='width: 20%'><?php echo $sort->link('parent', Yii::t('adminroles', 'Parent'), array( 'class' => 'tooltip', 'title' => Yii::t('adminroles', 'Sort list by parent') ) ); ?></th>
				   <th style='width: 50%'><?php echo $sort->link('child', Yii::t('adminroles', 'Child'), array( 'class' => 'tooltip', 'title' => Yii::t('adminroles', 'Sort list by child') ) ); ?></th>
				   <th style='width: 15%'><?php echo Yii::t('adminroles', 'Options'); ?></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="6">													
						<?php $this->widget('widgets.admin.pager', array( 'pages' => $pages )); ?>
						<div class="clear"></div>
					</td>
				</tr>
			</tfoot>
			<tbody>
			<?php if ( count($rows) ): ?>
				
				<?php foreach ($rows as $row): ?>
					<tr>
						<td><?php echo CHtml::encode($row->parent); ?></td>
						<td><a href="<?php echo $this->createUrl('roles/viewauthitem', array( 'parent' => $row->child )); ?>" title="<?php echo Yii::t('adminroles', 'View Auth Item'); ?>" class='tooltip'><?php echo CHtml::encode($row->child); ?></a></td>
						<td>
							<!-- Icons -->
							 <a href="<?php echo $this->createUrl('roles/deleteauthitemchild', array( 'parent' => $row->parent, 'child' => $row->child )); ?>" title="<?php echo Yii::t('adminroles', 'Delete this relationship!'); ?> "class='tooltip deletelink'><img src="<?php echo Yii::app()->themeManager->baseUrl; ?>/images/icons/cross.png" alt="Delete" /></a>
						</td>
					</tr>
				<?php endforeach ?>

			<?php else: ?>	
				<tr>
					<td colspan='5' style='text-align:center;'><?php echo Yii::t('adminroles', 'No items found.'); ?></td>
				</tr>
			<?php endif; ?>
			</tbody>
		</table>
	
	</div> <!-- End .content-box-content -->
	
</div> <!-- End .content-box -->
