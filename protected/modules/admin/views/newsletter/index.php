<?php if(isset($_POST['preview'])): ?>
<div class="content-box"><!-- Start Content Box -->
	
	<div class="content-box-header">
		<h3><?php echo Yii::t('newsletter', 'Preview'); ?></h3>
	</div> <!-- End .content-box-header -->
	
	<div class="content-box-content">

		
		<?php echo isset($_POST['content']) ? $_POST['content'] : ''; ?>
		
		
	</div> <!-- End .content-box-content -->
	
</div> <!-- End .content-box -->
<?php endif; ?>

<div class="content-box"><!-- Start Content Box -->
	
	<div class="content-box-header">
		<h3><?php echo Yii::t('adminnewsletter', 'Newsletter Emails'); ?> (<?php echo $count; ?>)</h3>
	</div> <!-- End .content-box-header -->
	
	<div class="content-box-content">
		<?php echo CHtml::form(); ?>
		<table>
			<thead>
				<tr>
				<th style='width: 5%;'><input class="check-all" type="checkbox" /></th>
				   <th style='width: 20%'><?php echo $sort->link('email', Yii::t('adminnewsletter', 'Email'), array( 'class' => 'tooltip', 'title' => Yii::t('adminnewsletter', 'Sort list by email') ) ); ?></th>
				   <th style='width: 20%'><?php echo $sort->link('joined', Yii::t('adminnewsletter', 'Joined'), array( 'class' => 'tooltip', 'title' => Yii::t('adminnewsletter', 'Sort list by date') ) ); ?></th>
				   <th style='width: 10%'><?php echo Yii::t('adminnewsletter', 'Options'); ?></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="6">	
						<div class="bulk-actions align-left">
							<select name="bulkoperations">
								<option value=""><?php echo Yii::t('global', '-- Choose Action --'); ?></option>
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
						<td><?php echo CHtml::encode($row->email); ?></td>
						<td><?php echo Yii::app()->dateFormatter->formatDateTime($row->joined); ?></td>
						<td>
							<a href="<?php echo $this->createUrl('delete', array( 'id' => $row->id )); ?>" title="<?php echo Yii::t('newsletter', 'Delete this item!'); ?> "class='tooltip deletelink'><img src="<?php echo Yii::app()->themeManager->baseUrl; ?>/images/icons/cross.png" alt="Delete" /></a>
						</td>
					</tr>
				<?php endforeach ?>

			<?php else: ?>	
				<tr>
					<td colspan='7' style='text-align:center;'><?php echo Yii::t('adminnewsletter', 'No items found.'); ?></td>
				</tr>
			<?php endif; ?>
			</tbody>
		</table>
		<?php echo CHtml::endForm(); ?>
	</div> <!-- End .content-box-content -->
	
</div> <!-- End .content-box -->

<div class="content-box"><!-- Start Content Box -->
	
	<div class="content-box-header">
		<h3><?php echo Yii::t('newsletter', 'Add Subscriber'); ?></h3>
	</div> <!-- End .content-box-header -->
	
	<div class="content-box-content">

		<?php echo CHtml::form(); ?>
		
		<?php echo CHtml::activeLabel($model, 'email'); ?>
		<?php echo CHtml::activeTextField($model, 'email', array( 'class' => 'text-input medium-input' )); ?>
		<?php echo CHtml::error($model, 'email', array( 'class' => 'input-notification errorshow png_bg' )); ?>
		
		<p>
			<?php echo CHtml::submitButton(Yii::t('adminglobal', 'Submit'), array('class'=>'button', 'name'=>'submit')); ?>
		</p>
		
		<?php echo CHtml::endForm(); ?>
		
	</div> <!-- End .content-box-content -->
	
</div> <!-- End .content-box -->

<div class="content-box"><!-- Start Content Box -->
	
	<div class="content-box-header">
		<h3><?php echo Yii::t('newsletter', 'Send Newsletter'); ?></h3>
	</div> <!-- End .content-box-header -->
	
	<div class="content-box-content">

		<?php echo CHtml::form(); ?>
		
		<?php echo CHtml::label(Yii::t('newsletter', 'Subject'), ''); ?>
		<?php echo CHtml::textField('subject', 'Newsletter', array( 'class' => 'text-input medium-input' )); ?>
		
		<br /><br />
		
		<?php $this->widget('application.widgets.ckeditor.CKEditor', array( 'name' => 'content', 'value' => isset($_POST['content']) ? $_POST['content'] : '', 'editorTemplate' => 'full' )); ?>
		
		<p>
			<?php echo CHtml::submitButton(Yii::t('adminglobal', 'Send!'), array('class'=>'button', 'name'=>'sendnewsletter')); ?>
			<?php echo CHtml::submitButton(Yii::t('adminglobal', 'Preview!'), array('class'=>'button', 'name'=>'preview')); ?>
		</p>
		
		<?php echo CHtml::endForm(); ?>
		
	</div> <!-- End .content-box-content -->
	
</div> <!-- End .content-box -->
