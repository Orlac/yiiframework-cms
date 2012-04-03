<div class="content-box"><!-- Start Content Box -->
	
	<div class="content-box-header">
		<h3><?php echo Yii::t('adminlang', 'Language Strings'); ?> (<?php echo $count; ?>)</h3>
	</div> <!-- End .content-box-header -->
	
	<div class="content-box-content">
			<?php $this->widget('widgets.admin.pager', array( 'pages' => $pages )); ?>
			<?php echo CHtml::form(); ?>
			<table>
				<thead>
					<tr>
					   <th style='width: 5%;'><?php echo $sort->link('id', Yii::t('adminlang', 'ID'), array( 'class' => 'tooltip', 'title' => Yii::t('adminlang', 'Sort by string id') ) ); ?></th>
					   <th style='width: 40%;'><?php echo Yii::t('adminlang', 'Original String'); ?></th>
					   <th style='width: 40%;'><?php echo $sort->link('translation', Yii::t('adminlang', 'Translation'), array( 'class' => 'tooltip', 'title' => Yii::t('adminlang', 'Sort by translation') ) ); ?></th>
					   <th style='width: 10%;'><?php echo Yii::t('adminlang', 'Options'); ?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="4">					
							<?php $this->widget('widgets.admin.pager', array( 'pages' => $pages )); ?>
							<div class="clear"></div>
						</td>
					</tr>
				</tfoot>
				<tbody>
					
					<?php if( count($strings) ): ?>
						
						<?php foreach( $strings as $string ): ?>
							<?php $orig = SourceMessage::model()->findByPk($string->id); ?>
							<tr>
								<td><?php echo $string->id; ?></td>
								<td style='vertical-align:top;'><?php echo CHtml::encode($orig->message); ?> <br /><small>(<?php echo $orig->category; ?>)</small></td>
								<td><?php echo CHtml::textArea("strings[{$string->id}]", $string->translation, array( 'rows' => 10, 'cols' => 50 )); ?></td>
							    <td>&nbsp;
							    	<?php if( $string->translation != $orig->message ): ?>
										<a href="<?php echo $this->createUrl('languages/revert', array( 'id' => $string->language, 'string' => $string->id )); ?>" title="<?php echo Yii::t('adminlang', 'Revert translation to original'); ?>" class='tooltip'>
											<img src="<?php echo Yii::app()->themeManager->baseUrl; ?>/images/icons/revert.png" alt="revert" />
										 </a>
									<?php endif; ?>
							    </td>
							</tr>	
						<?php endforeach; ?>
					
					<tr>
						<td colspan='4' style='text-align:center;'><?php echo CHtml::submitButton(Yii::t('adminlang', 'Submit'), array('name'=>'submit')); ?></td>
					</tr>
					
					<?php else: ?>	
					<tr>
						<td colspan='4' style='text-align:center;'><?php echo Yii::t('adminlang', 'No strings found.'); ?></td>
					</tr>
					<?php endif; ?>
					
				</tbody>
			</table>
			
			<?php echo CHtml::endForm(); ?>
	</div> <!-- End .content-box-content -->
	
</div> <!-- End .content-box -->
