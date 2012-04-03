<div class='floatright'>
	<?php echo CHtml::link(Yii::t('adminblog', 'Add Category'), array('blog/addcategory'), array( 'class' => 'button' )); ?>
	<?php echo CHtml::link(Yii::t('adminblog', 'Add Post'), array('blog/addpost'), array( 'class' => 'button' )); ?>
</div>

<div class="content-box"><!-- Start Content Box -->
	
	<div class="content-box-header">
		<h3><?php echo Yii::t('adminblog', 'Categories'); ?></h3>
	</div> <!-- End .content-box-header -->
	
	<div class="content-box-content">
			<?php echo CHtml::form(); ?>
			<table>
				<thead>
					<tr>
						<th style='width: 5%;'><?php echo Yii::t('adminblog', 'Position'); ?></th>
					   	<th style='width: 20%;'><?php echo Yii::t('adminblog', 'Title'); ?></th>
					  	<th style='width: 10%;'><?php echo Yii::t('adminblog', 'Alias'); ?></th>
						<th style='width: 25%;'><?php echo Yii::t('adminblog', 'Description'); ?></th>
						<th style='width: 10%;'><?php echo Yii::t('adminblog', 'Language'); ?></th>
						<th style='width: 5%;'><?php echo Yii::t('adminblog', 'Read'); ?></th>
						<th style='width: 10%;'><?php echo Yii::t('adminblog', 'Posts'); ?></th>
					   	<th style='width: 15%;'><?php echo Yii::t('adminblog', 'Options'); ?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="8">	
							<div class="bulk-actions align-left">
								<?php echo CHtml::submitButton( Yii::t('global', 'Reorder'), array( 'name'=> 'submit', 'class'=>'button')); ?>
							</div>
							<div class="clear"></div>
						</td>
					</tr>
				</tfoot>
				<tbody>
				<?php if ( count( BlogCats::model()->getRootCats() ) ): ?>
					
					<?php foreach (BlogCats::model()->getRootCats() as $row): ?>
						<tr>
							<td><?php echo CHtml::textField( 'pos[' . $row->id.']', $row->position, array('size'=>1) ); ?></td>
							<td><a href="<?php echo $this->createUrl('blog/viewcategory', array( 'id' => $row->id )); ?>" title="<?php echo Yii::t('adminblog', 'View Posts'); ?>" class='tooltip'><?php echo CHtml::encode($row->title); ?></a></td>
							<td><?php echo CHtml::encode($row->alias); ?></td>
							<td><?php echo CHtml::encode($row->description); ?></td>
							<td><?php echo Message::model()->getLanguageNames( $row->language ); ?></td>
							<td><?php echo Yii::app()->func->adminYesNoImage($row->readonly, array('blog/catreadonly', 'id' => $row->id )); ?></td>
							<td><?php echo Yii::app()->format->number( $row->count ); ?></td>
							<td>
								<!-- Icons -->
								<a href="<?php echo Yii::app()->urlManager->createUrl('blog/category/' . $row->alias , array( 'lang' => false )); ?>" title="<?php echo Yii::t('adminblog', 'View category'); ?>" class='tooltip'><img src="<?php echo Yii::app()->themeManager->baseUrl; ?>/images/icons/preview.png" alt="View" /></a>
								<a href="<?php echo $this->createUrl('blog/addpost', array( 'catid' => $row->id )); ?>" title="<?php echo Yii::t('adminblog', 'Add post to this category'); ?>" class='tooltip'><img src="<?php echo Yii::app()->themeManager->baseUrl; ?>/images/icons/add.png" alt="Add" /></a>
								<a href="<?php echo $this->createUrl('blog/addcategory', array( 'parentid' => $row->id )); ?>" title="<?php echo Yii::t('adminblog', 'Add sub category to this category'); ?>" class='tooltip'><img src="<?php echo Yii::app()->themeManager->baseUrl; ?>/images/icons/addsub.png" alt="Add" /></a>
								 <a href="<?php echo $this->createUrl('blog/editcategory', array( 'id' => $row->id )); ?>" title="<?php echo Yii::t('adminblog', 'Edit this category'); ?>" class='tooltip'><img src="<?php echo Yii::app()->themeManager->baseUrl; ?>/images/icons/pencil.png" alt="Edit" /></a>
								 <a href="<?php echo $this->createUrl('blog/deletecategory', array( 'id' => $row->id )); ?>" title="<?php echo Yii::t('adminblog', 'Delete this category!'); ?> "class='tooltip deletelink'><img src="<?php echo Yii::app()->themeManager->baseUrl; ?>/images/icons/cross.png" alt="Delete" /></a>
							</td>
						</tr>
					<?php endforeach ?>
					
				<?php else: ?>	
					<tr>
						<td colspan='8' style='text-align:center;'><?php echo Yii::t('adminglobal', 'No Items Found.'); ?></td>
					</tr>
				<?php endif; ?>
				</tbody>
			</table>
		<?php echo CHtml::endForm(); ?>
	</div> <!-- End .content-box-content -->
	
</div> <!-- End .content-box -->
