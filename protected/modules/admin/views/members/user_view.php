<div class="content-box"><!-- Start Content Box -->
	
	<div class="content-box-header">
		<h3><?php echo $model->username; ?></h3>
	</div> <!-- End .content-box-header -->
	
	<div class="content-box-content">
				
	<div style='float:left;'><?php $this->widget('application.extensions.VGGravatarWidget', array( 'email' => $model->email, 'size' => 120 )); ?></div>
	<div style='margin-left: 125px;'>
		
		<table>
			
			<tr>
				<td><b><?php echo Yii::t('adminmembers', 'Username:'); ?></b></td>
				<td><?php echo $model->username; ?></td>
				
				<td><b><?php echo Yii::t('adminmembers', 'Email:'); ?></b></td>
				<td><?php echo $model->email; ?></td>
			</tr>

			<tr>
				<td><b><?php echo Yii::t('adminmembers', 'Joined:'); ?></b></td>
				<td><?php echo Yii::app()->dateFormatter->formatDateTime($model->joined, 'short', 'short'); ?></td>
				
				<td><b><?php echo Yii::t('adminmembers', 'Role:'); ?></b></td>
				<td><?php echo $model->role; ?></td>
			</tr>
			
			<tr>
				<td><b><?php echo Yii::t('adminmembers', 'IP:'); ?></b></td>
				<td><?php echo $model->ipaddress; ?></td>
				
				<td><b><?php echo Yii::t('adminmembers', 'Options'); ?></b></td>
				<td>
					<a href="<?php echo $this->createUrl('members/edituser', array( 'id' => $model->id )); ?>" title="<?php echo Yii::t('adminmembers', 'Edit this member'); ?>" class='tooltip'><img src="<?php echo Yii::app()->themeManager->baseUrl; ?>/images/icons/pencil.png" alt="Edit" /></a>
					 <a href="<?php echo $this->createUrl('members/deleteuser', array( 'id' => $model->id )); ?>" title="<?php echo Yii::t('adminmembers', 'Delete this member!'); ?> "class='tooltip deletelink'><img src="<?php echo Yii::app()->themeManager->baseUrl; ?>/images/icons/cross.png" alt="Delete" /></a>
				</td>
			</tr>
			
		</table>	

	</div>					
	<div class='clear'></div>		
		
	</div> <!-- End .content-box-content -->
	
</div> <!-- End .content-box -->


<div class="content-box column-left">
	
	<div class="content-box-header">
		
		<h3><?php echo Yii::t('adminmembers', 'Personal Information'); ?></h3>
		
	</div> <!-- End .content-box-header -->
	
	<div class="content-box-content">
		
		<div class="tab-content default-tab">
		
			<h6>INFO HERE</h6>
			
		</div> <!-- End #tab3 -->        
		
	</div> <!-- End .content-box-content -->
	
</div> <!-- End .content-box -->

<div class="content-box column-right">
	
	<div class="content-box-header"> <!-- Add the class "closed" to the Content box header to have it closed by default -->
		
		<h3><?php echo Yii::t('adminmembers', 'Site Activity Information'); ?></h3>
		
	</div> <!-- End .content-box-header -->
	
	<div class="content-box-content">
		
		<div class="tab-content default-tab">
		
			<h6>INFO HERE</h6>
			
			
		</div> <!-- End #tab3 -->        
		
	</div> <!-- End .content-box-content -->
	
</div> <!-- End .content-box -->
<div class="clear"></div>