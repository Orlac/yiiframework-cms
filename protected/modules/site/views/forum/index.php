<div id='custompagecontent'>

	<?php if( Yii::app()->user->checkAccess('op_forum_post_topics') ): ?>
		<a href="<?php echo $this->createUrl('addtopic'); ?>" class="linkcomment"><strong><?php echo Yii::t('forum', 'Create Topic'); ?></strong></a>
	<?php endif; ?>	
		
	<?php if( is_array($rows) && count($rows) ): ?>
		<h2><?php echo Yii::t('forum', 'Topics'); ?></h2>
		<br />
		<table id='forumTopics' class='data'>
			<thead>
				<tr>
					<th style='width:35%;' class='header'><?php echo Yii::t('forum', 'Title'); ?></th>
					<th style='width:15%;' class='header'><?php echo Yii::t('forum', 'Author'); ?></th>
					<th style='width:10%;' class='header center'><?php echo Yii::t('forum', 'Date'); ?></th>
					<th style='width:10%;' class='header center'><?php echo Yii::t('forum', 'Views'); ?></th>
					<th style='width:10%;' class='header center'><?php echo Yii::t('forum', 'Replies'); ?></th>
					<th style='width:20%;' class='header'><?php echo Yii::t('forum', 'Last Post'); ?></th>
					<?php if( Yii::app()->user->checkAccess('op_forum_topics') ): ?>
						<th style='width:5%;' class='header'><?php echo Yii::t('forum', 'Manage'); ?></th>
					<?php endif; ?>
				</tr>
			</thead>
			<tbody>
				<?php foreach($rows as $row): ?>
					<tr>
						<td><?php echo $row->getLink(); ?> <?php if( $row->visible == 0 ): ?><?php echo Yii::t('forum', 'Hidden'); ?><?php endif; ?></td>
						<td><?php echo $row->author ? $row->author->getModelLink() : '--'; ?></td>
						<td><?php echo Yii::app()->dateFormatter->formatDateTime( $row->dateposted, 'short', 'short' ); ?></td>
						<td class='center'><strong><?php echo Yii::app()->format->number( $row->views ); ?></strong></td>
						<td class='center'><strong><?php echo Yii::app()->format->number( $row->postscount ); ?></strong></td>
						<td><?php echo Yii::t('forum', 'By {by}<br />On {on}', array( '{by}' => $row->lastauthor ? $row->lastauthor->getModelLink() : '--', '{on}' => $row->lastpostdate ? Yii::app()->dateFormatter->formatDateTime( $row->lastpostdate, 'short', 'short' ) : '--'  )); ?></td>
						<?php if( Yii::app()->user->checkAccess('op_forum_topics') ): ?>
							<td>
								<?php echo CHtml::link( CHtml::image( Yii::app()->themeManager->baseUrl . '/images/'. ($row->visible ? 'cross_circle' : 'tick_circle') . '.png' ), array('forum/toggletopic', 'id' => $row->id), array( 'class' => 'tooltip', 'title' => Yii::t('forum', 'Toggle topic status!') ) ); ?>
								<?php echo CHtml::link( CHtml::image( Yii::app()->themeManager->baseUrl . '/images/cross_circle.png' ), array('deletetopic', 'id' => $row->id, 'k' => Yii::app()->request->csrfToken ), array( 'class' => 'tooltip', 'title' => Yii::t('forum', 'Delete Topic') ) ); ?>
							</td>
						<?php endif; ?>
					</tr>
				<?php endforeach; ?>
			</tbody>	
			</table>
	<?php else: ?>		
		<h2><?php echo Yii::t('forum', 'There are not topics posted yet. Be the first to post.'); ?></h2>
	<?php endif; ?>
	<br />
	<?php $this->widget('CLinkPager', array('pages'=>$pages)); ?>
</div>

<script type='text/javascript'>
$('#forumTopics tbody tr:odd').addClass('odd');
$('#forumTopics tbody tr:even').addClass('even');
</script>
