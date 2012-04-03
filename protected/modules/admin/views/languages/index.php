<div class="content-box"><!-- Start Content Box -->
	
	<div class="content-box-header">
		<h3><?php echo Yii::t('adminlang', 'Langauges'); ?></h3>
	</div> <!-- End .content-box-header -->
	
	<div class="content-box-content">
			<table>
				<thead>
					<tr>
					   <th style='width: 5%;'>&nbsp;</th>
					   <th style='width: 20%;'><?php echo Yii::t('adminlang', 'Language Key'); ?></th>
					   <th style='width: 20%;'><?php echo Yii::t('adminlang', 'Language Title'); ?></th>
					   <th style='width: 20%;'><?php echo Yii::t('adminlang', 'Source Language'); ?></th>
					   <th style='width: 20%;'><?php echo Yii::t('adminlang', '# Strings'); ?></th>
					   <th style='width: 15%;'><?php echo Yii::t('adminlang', 'Options'); ?></th>
					</tr>
				</thead>
				<tbody>
					
					<?php foreach( Yii::app()->params['languages'] as $key => $value ): ?>
						<tr>
							<td>&nbsp;</td>
							<td><?php echo $key; ?></td>
							<td><?php echo $value; ?></td>
							<td>
								<?php if( $key == Yii::app()->sourceLanguage ): ?>
									<img class='tooltip' title='<?php echo Yii::t('adminlang', 'Source Language'); ?>' src="<?php echo Yii::app()->themeManager->baseUrl; ?>/images/icons/tick_circle.png" alt="Source Language" />
								<?php else: ?>
									<img class='tooltip' title='<?php echo Yii::t('adminlang', 'Not Source Language'); ?>' src="<?php echo Yii::app()->themeManager->baseUrl; ?>/images/icons/cross_circle.png" alt="Not Source Language" />
								<?php endif; ?>		
							</td>
						    <td>
								<?php if( $key == Yii::app()->sourceLanguage ): ?>
									<?php echo Yii::app()->format->formatNumber( $totalStringsInSource ); ?>
								<?php else: ?>
									<?php echo $this->getStringTranslationDifference( $key ) . ' / ' . Yii::app()->format->formatNumber( Message::model()->count('language=:key', array(':key'=>$key)) ); ?>
								<?php endif; ?>		
							</td>
							<td>
								<!-- Icons -->
								
								<?php if($key == Yii::app()->sourceLanguage): ?>
									<img class='tooltip' title='<?php echo Yii::t('adminlang', 'Source cannot be translated'); ?>' src="<?php echo Yii::app()->themeManager->baseUrl; ?>/images/icons/information.png" alt="Translate" />
								<?php else: ?>
									<a href="<?php echo $this->createUrl('languages/translate', array( 'id' => $key )); ?>" title="<?php echo Yii::t('adminlang', 'Translate this language'); ?>" class='tooltip'>
										<img src="<?php echo Yii::app()->themeManager->baseUrl; ?>/images/icons/pencil.png" alt="Translate" />
									 </a>
									 
									 <a href="<?php echo $this->createUrl('languages/translateneeded', array( 'id' => $key )); ?>" title="<?php echo Yii::t('adminlang', 'Translate only the strings that were not translated yet.'); ?>" class='tooltip'>
										<img src="<?php echo Yii::app()->themeManager->baseUrl; ?>/images/icons/pencil.png" alt="Translate" />
									 </a>
									
									<a href="<?php echo $this->createUrl('languages/copystrings', array( 'id' => $key )); ?>" title="<?php echo Yii::t('adminlang', 'Copy missing language strings from source into this language'); ?>" class='tooltip'>
										<img src="<?php echo Yii::app()->themeManager->baseUrl; ?>/images/icons/copy.png" alt="copy" />
									 </a>
								<?php endif; ?>	
								
								 
							</td>
						</tr>	
					<?php endforeach; ?>	
					
				</tbody>
			</table>
	</div> <!-- End .content-box-content -->
	
</div> <!-- End .content-box -->
