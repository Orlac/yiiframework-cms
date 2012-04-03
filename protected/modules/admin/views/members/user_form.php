<div class="content-box"><!-- Start Content Box -->
	
	<div class="content-box-header">
		<h3><?php echo $label; ?></h3>
	</div> <!-- End .content-box-header -->
	
	<div class="content-box-content">

		<?php echo CHtml::form(); ?>
		
		<?php echo CHtml::label(Yii::t('adminmembers', 'Username'), ''); ?>
		<?php echo CHtml::activeTextField($model, 'username', array( 'class' => 'text-input medium-input' )); ?>
		<?php echo CHtml::error($model, 'username', array( 'class' => 'input-notification errorshow png_bg' )); ?>
		
		<?php echo CHtml::label(Yii::t('adminmembers', 'Email Address'), ''); ?>
		<?php echo CHtml::activeTextField($model, 'email', array( 'class' => 'text-input medium-input' )); ?>
		<?php echo CHtml::error($model, 'email', array( 'class' => 'input-notification errorshow png_bg' )); ?>
		
		<?php echo CHtml::label(Yii::t('adminmembers', 'Password'), ''); ?>
		<?php echo CHtml::activeTextField($model, 'password', array( 'class' => 'text-input medium-input' )); ?>
		<?php echo CHtml::error($model, 'password', array( 'class' => 'input-notification errorshow png_bg' )); ?>
		
		<?php echo CHtml::label(Yii::t('adminmembers', 'Default Role'), ''); ?>
		<?php echo CHtml::activeDropDownList($model, 'role', $items[ CAuthItem::TYPE_ROLE ], array( 'prompt' => Yii::t('global', '-- Choose Value --'), 'class' => 'text-input medium-input' )); ?>
		<?php echo CHtml::error($model, 'role', array( 'class' => 'input-notification errorshow png_bg' )); ?>
		
		<?php echo CHtml::label(Yii::t('adminmembers', 'Other Assigned Roles'), ''); ?>
		<?php echo CHtml::listBox('roles', isset($_POST['roles']) ? $_POST['roles'] : isset($items_selected[ CAuthItem::TYPE_ROLE ]) ? $items_selected[ CAuthItem::TYPE_ROLE ] : '', $items[ CAuthItem::TYPE_ROLE ], array( 'size' => 20, 'multiple' => 'multiple', 'class' => 'text-input medium-input' )); ?>
		
		<?php echo CHtml::label(Yii::t('adminmembers', 'Other Assigned Tasks'), ''); ?>
		<?php echo CHtml::listBox('tasks', isset($_POST['tasks']) ? $_POST['tasks'] : isset($items_selected[ CAuthItem::TYPE_TASK ]) ? $items_selected[ CAuthItem::TYPE_TASK ] : '', $items[ CAuthItem::TYPE_TASK ], array( 'size' => 20, 'multiple' => 'multiple', 'class' => 'text-input medium-input' )); ?>
		
		<?php echo CHtml::label(Yii::t('adminmembers', 'Other Assigned Operations'), ''); ?>
		<?php echo CHtml::listBox('operations', isset($_POST['operations']) ? $_POST['operations'] : isset($items_selected[ CAuthItem::TYPE_OPERATION ]) ? $items_selected[ CAuthItem::TYPE_OPERATION ] : '', $items[ CAuthItem::TYPE_OPERATION ], array( 'size' => 20, 'multiple' => 'multiple', 'class' => 'text-input medium-input' )); ?>
		
		
		<p><?php echo CHtml::submitButton(Yii::t('adminglobal', 'Submit'), array('class'=>'button')); ?></p>
		
		<?php echo CHtml::endForm(); ?>
		
	</div> <!-- End .content-box-content -->
	
</div> <!-- End .content-box -->
