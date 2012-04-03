<div class="content-box">
	
	<div class="content-box-header">
		
		<h3><?php echo Yii::t('admindocs', 'Documentations'); ?></h3>
		
		<ul class="content-box-tabs">
			<?php if( count( $topics ) ): ?>
				<?php foreach( $topics as $key => $data ): ?>
					<li><a href="#tab_<?php echo $key; ?>"<?php echo $key == 'guide' ? ' class="default-tab"' : '' ?>><?php echo Yii::t('admindocs', ucfirst($key)); ?></a></li>
				<?php endforeach; ?>	
			<?php endif; ?>
		</ul>
		
		<div class="clear"></div>
		
	</div>
	
	<div class="content-box-content">
		
		<?php if( count( $topics ) ): ?>
			<?php foreach( $topics as $key => $data ): ?>
				<div class="tab-content<?php echo $key == 'guide' ? ' default-tab' : '' ?>" id="tab_<?php echo $key; ?>">

		
				<h4><?php echo ucfirst($key); ?></h4>
				
				<table>
					<thead>
						<tr>
						   <th style='width: 15%;'><?php echo Yii::t('admindocs', 'Name'); ?></th>
						   <th style='width: 15%;'><?php echo Yii::t('admindocs', 'key'); ?></th>
						   <th style='width: 10%;'><?php echo Yii::t('admindocs', 'Language'); ?></th>
						   <th style='width: 20%;'><?php echo Yii::t('admindocs', 'Last Modified'); ?></th>
						   <th style='width: 20%;'><?php echo Yii::t('admindocs', 'Last Modifier'); ?></th>
						   <th style='width: 10%;'><?php echo Yii::t('admindocs', 'Views'); ?></th>
						   <th style='width: 10%;'><?php echo Yii::t('admindocs', 'Options'); ?></th>
						</tr>
					</thead>
					<tbody>
					<?php if(count($data)): ?>
						<?php foreach($data as $topic): ?>
							<tr>
								<td><?php echo $topic->name; ?></td>
								<td><?php echo $topic->mkey; ?></td>
								<td><?php echo Yii::app()->params['languages'][$topic->language]; ?></td>
								<td><?php echo Yii::app()->dateFormatter->formatDateTime($topic->last_updated, 'short', 'short'); ?></td>
								<td><?php echo $topic->updater ? $topic->updater->username : '--'; ?></td>
								<td><?php echo Yii::app()->format->number($topic->views); ?></td>
								<td>
									<a href="<?php echo $this->createUrl('documentation/edit', array( 'id' => $topic->id )); ?>" title="<?php echo Yii::t('admindocs', 'Edit this topic'); ?>" class='tooltip'>
										<img src="<?php echo Yii::app()->themeManager->baseUrl; ?>/images/icons/pencil.png" alt="Edit" />
									</a>
									
									<a href="<?php echo Yii::app()->urlManager->createUrl('documentation/'.$key, array( 'page' => $topic->mkey )); ?>" target='_blank' title="<?php echo Yii::t('admindocs', 'View this topic'); ?>" class='tooltip'>
										<img src="<?php echo Yii::app()->themeManager->baseUrl; ?>/images/icons/preview.png" alt="Preview" />
									</a>
								</td>
							</tr>	
						<?php endforeach; ?>
					<?php else: ?>
						<tr>
							<td colspan='6' style='text-align:center;'><?php echo Yii::t('admindocs', 'No files found.'); ?></td>
						</tr>		
					<?php endif; ?>	
					</tbody>
				</table>
				
				</div>
			<?php endforeach; ?>	
		<?php endif; ?>      
		
	</div>
	
</div>
