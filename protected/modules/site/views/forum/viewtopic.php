<?php 
	Yii::app()->clientScript->registerCssFile( Yii::app()->themeManager->baseUrl . '/style/highlight.css', 'screen' );
?>

<?php if( Yii::app()->user->id ): ?>

<div class='floatright'>
<?php if( $subscribed ): ?>
		<a href="<?php echo $this->createUrl('unsubscribe', array('id' => $model->id ) ); ?>" class="linkcomment" title='<?php echo Yii::t('forum', 'Un-Subscribe for topic updates.'); ?>'><strong><?php echo Yii::t('forum', 'Unsubscribe'); ?></strong></a>
<?php else: ?>
		<a href="<?php echo $this->createUrl('subscribe', array('id' => $model->id ) ); ?>" class="linkcomment" title='<?php echo Yii::t('forum', 'Subscribe for topic updates.'); ?>'><strong><?php echo Yii::t('forum', 'Subscribe'); ?></strong></a>
<?php endif; ?>	
</div>
<div class='floatleft'><!-- None --></div>
<div style='clear:both;'></div>
<br style='clear:both;' />

<?php endif; ?>

<?php $this->widget('ext.VGGravatarWidget', array( 'size' => 50, 'email'=>$model->author ? $model->author->email : '','htmlOptions'=>array('class'=>'imgavatar','alt'=>'avatar'))); ?>
<div class='forumtopicpost'><?php echo $markdown->safeTransform($model->content); ?></div>

<div class="clear"></div><br />
<h3 id="titlecomment"><?php echo Yii::t('forum', 'Posts'); ?> (<?php echo $count; ?>)</h3>
<ul id="listcomment">
	<?php if( count( $posts ) ): ?>
		<?php foreach($posts as $post): ?>
			<li <?php if( $post->visible == 0 ): ?>style='background-color:#FFCECE;'<?php endif; ?>>
				<a name='post<?php echo $post->id; ?>'></a>
				<span class='commentspan'><?php echo CHtml::link( '#' . $post->id, array('/forum/topic/' . $model->id . '-' . $model->alias, '#' => 'post' . $post->id, 'page' => $pages->getCurrentPage(), 'lang'=>false ) ); ?></span>
				<?php $this->widget('ext.VGGravatarWidget', array( 'size' => 50, 'email'=>$post->author ? $post->author->email : '','htmlOptions'=>array('class'=>'imgavatar','alt'=>'avatar'))); ?>
				<h4><?php echo $post->author ? $post->author->username : Yii::t('global', 'Unknown'); ?></h4>
				<span class="datecomment"><?php echo Yii::app()->dateFormatter->formatDateTime($post->dateposted, 'short', 'short'); ?></span>
				<div class="clear"></div>
				<p><?php echo $markdown->safeTransform($post->content); ?></p>
			    <?php if( Yii::app()->user->checkAccess('op_forum_posts') ): ?>
					<?php echo CHtml::link( CHtml::image( Yii::app()->themeManager->baseUrl . '/images/'. ($post->visible ? 'cross_circle' : 'tick_circle') . '.png' ), array('forum/togglepost', 'id' => $post->id), array( 'class' => 'tooltip', 'title' => Yii::t('forum', 'Toggle post status!') ) ); ?>
				<?php endif; ?>
			</li>
			<hr />
		<?php endforeach; ?>	
	<?php else: ?>	
		<li><?php echo Yii::t('forum', 'No posted posted yet. Be the first!'); ?></li>
	<?php endif; ?>	
</ul>
<?php $this->widget('CLinkPager', array('pages'=>$pages)); ?>
<?php if( Yii::app()->user->checkAccess('op_forum_post_posts') ): ?>
	
<?php echo CHtml::form('', 'post', array('id'=>'frmcomment')); ?>
<?php echo CHtml::hiddenField('lastpage', $pages->pageCount); ?>
	<div>
		<?php echo CHtml::label(Yii::t('forum', 'Post'), ''); ?>
		<?php $this->widget('widgets.markitup.markitup', array( 'model' => $newPost, 'attribute' => 'content' )); ?>
		<?php echo CHtml::error($newPost, 'comment'); ?>
		<?php echo CHtml::submitButton(Yii::t('forum', 'Post Reply'), array( 'class' => 'submitcomment' )); ?>
	</div>
<?php echo CHtml::endForm(); ?>

<?php else: ?>
<div><?php echo Yii::t('global', 'You must be logged in to post.'); ?></div>
<?php endif; ?>