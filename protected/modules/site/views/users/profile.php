<?php if($this->beginCache('userprofile_' . $model->id, array('duration'=>3600))) { ?>
<div id="maincontent">
	<div id="contentbig">				
				<div id='toprint'>
					
					<h2><?php echo Yii::t('users', 'Latest Submissions'); ?></h2>
					
					<h3><?php echo Yii::t('users', 'Tutorials'); ?></h3>
					
					<ul>
					<?php $tuts = Tutorials::model()->findAll('authorid=:uid AND status=1', array( ':uid' => $model->id )); ?>
					<?php if( is_array($tuts) && count($tuts) ): ?>
						<?php foreach($tuts as $tut): ?>							
							<li><?php echo Tutorials::model()->getLink( $tut->title, $tut->alias, array( 'title' => $tut->description ) ); ?></li>
						<?php endforeach; ?>	
					<?php else: ?>
						<li><?php echo Yii::t('users', 'No Tutorials Submitted.'); ?></li>
					<?php endif; ?>
					</ul>
					
					<h3><?php echo Yii::t('users', 'Extensions Posted'); ?></h3>
					
					<ul>
					<?php $extensions = Extensions::model()->findAll('authorid=:uid AND status=1', array( ':uid' => $model->id )); ?>
					<?php if( is_array($extensions) && count($extensions) ): ?>
						<?php foreach($extensions as $extension): ?>							
							<li><?php echo Extensions::model()->getLink( $extension->title, $extension->alias, array( 'title' => $extension->description ) ); ?></li>
						<?php endforeach; ?>	
					<?php else: ?>
						<li><?php echo Yii::t('users', 'No Extensions Submitted.'); ?></li>
					<?php endif; ?>
					</ul>
					
					<h3><?php echo Yii::t('users', 'Blog Posts'); ?></h3>
					
					<ul>
					<?php $posts = Blog::model()->findAll('authorid=:uid AND status=1', array( ':uid' => $model->id )); ?>
					<?php if( is_array($posts) && count($posts) ): ?>
						<?php foreach($posts as $post): ?>							
							<li><?php echo Blog::model()->getLink( $post->title, $post->alias, array( 'title' => $post->description ) ); ?></li>
						<?php endforeach; ?>	
					<?php else: ?>
						<li><?php echo Yii::t('users', 'No Blog Posts Submitted.'); ?></li>
					<?php endif; ?>
					</ul>
					
				</div>
				
				<div class="clear"></div><br />
				<h3 id="titlecomment"><?php echo Yii::t('users', 'Comments'); ?> (<?php echo $totalcomments; ?>)</h3>
				<ul id="listcomment">
					<?php if( count( $comments ) ): ?>
						<?php foreach($comments as $comment): ?>
							<li <?php if( $comment->visible == 0 ): ?>style='background-color:#FFCECE;'<?php endif; ?>>
								<a name='comment<?php echo $comment->id; ?>'></a>
								<span class='commentspan'><?php echo CHtml::link( '#' . $comment->id, array('/user/' . $model->id . '-' . $model->seoname, '#' => 'comment' . $comment->id, 'lang'=>false ) ); ?></span>
								<?php $this->widget('ext.VGGravatarWidget', array( 'size' => 50, 'email'=>$comment->author ? $comment->author->email : '','htmlOptions'=>array('class'=>'imgavatar','alt'=>'avatar'))); ?>
								<h4><?php echo $comment->author ? $comment->author->username : Yii::t('global', 'Unknown'); ?></h4>
								<span class="datecomment"><?php echo Yii::app()->dateFormatter->formatDateTime($comment->postdate, 'long', 'short'); ?></span>
								<div class="clear"></div>
								<p><?php echo $markdown->safeTransform($comment->comment); ?></p>
							    <?php if( Yii::app()->user->checkAccess('op_users_manage_comments') ): ?>
									<?php echo CHtml::link( CHtml::image( Yii::app()->themeManager->baseUrl . '/images/'. ($comment->visible ? 'cross_circle' : 'tick_circle') . '.png' ), array('users/togglestatus', 'id' => $comment->id), array( 'class' => 'tooltip', 'title' => Yii::t('users', 'Toggle comment status!') ) ); ?>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>	
					<?php else: ?>	
						<li><?php echo Yii::t('users', 'No comments posted yet. Be the first!'); ?></li>
					<?php endif; ?>	
				</ul>
				<?php $this->widget('CLinkPager', array('pages'=>$pages)); ?>
				<?php if( $addcomments ): ?>

				<?php echo CHtml::form('', 'post', array('id'=>'frmcomment')); ?>
					<div>
						<?php echo CHtml::label(Yii::t('extensions', 'Comment'), ''); ?>
						<?php $this->widget('widgets.markitup.markitup', array( 'model' => $commentsModel, 'attribute' => 'comment' )); ?>
						<?php echo CHtml::error($commentsModel, 'comment'); ?>
						<?php echo CHtml::submitButton(Yii::t('users', 'Post Comment'), array( 'class' => 'submitcomment' )); ?>
					</div>
				<?php echo CHtml::endForm(); ?>

				<?php else: ?>
				<div><?php echo Yii::t('global', 'You must be logged in to post comments.'); ?></div>
				<?php endif; ?>
	</div>
</div>

<?php $this->widget('ext.VGGravatarWidget', array( 'size' => 100, 'email'=>$model->email,'htmlOptions'=>array('class'=>'imgavatar tiptop', 'title' => $model->username, 'alt'=>'avatar'))); ?>
<div class="clear"></div>
<?php $this->endCache(); } ?>