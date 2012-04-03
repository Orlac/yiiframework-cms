
<?php 
	Yii::app()->clientScript->registerCssFile( Yii::app()->themeManager->baseUrl . '/style/highlight.css', 'screen' );
	Yii::app()->clientScript->registerScriptFile( Yii::app()->themeManager->baseUrl . '/script/jquery.printElement.min.js', CClientScript::POS_END );
?>

<div id="maincontent">
	<div id="contentbig">
				<a href="#titlecomment" class="linkcomment"><strong><?php echo $totalcomments; ?></strong> <?php echo Yii::t('tutorials', 'Comments'); ?></a>
				&nbsp;
				<a href="#" class="linkcomment"><strong><?php echo $model->views; ?></strong> <?php echo Yii::t('tutorials', 'Views'); ?></a>
				&nbsp;
				<?php $this->widget('CStarRating',array(
					'htmlOptions'=>array('class'=>'linkcomment','style'=>'padding-left: 4px; text-align:left; direction:ltr;'), 
				    'name'=>'rating',
					'value' => $model->getRating(),
					'readOnly'=>Yii::app()->user->isGuest,
					'allowEmpty'=>false,
					'starCount'=>5,
				    'ratingStepSize'=>1,
				    'maxRating'=>10,
				    'minRating'=>1,
				    'callback'=>'
				        function(){
				        $.ajax({
				            type: "POST",
				            url: "'.$this->createUrl('tutorials/rating').'",
				            data: "'.Yii::app()->request->csrfTokenName . '=' . Yii::app()->request->csrfToken .'&id='.$model->id.'&rate=" + $(this).val(),
				            success: function(msg){
				                alert("'.Yii::t('global', 'Rating Added.').'");
				        }})}'
				));?>
				
				<?php if( Tutorials::model()->canEditTutorial( $model ) ): ?>
					<?php echo CHtml::link(  CHtml::image( Yii::app()->themeManager->baseUrl . '/images/icons/pencil.png' ), array('tutorials/edittutorial', 'id'=>$model->id), array('class'=>'linkcomment') ); ?>
				<?php endif; ?>	
				
				<?php if( Yii::app()->user->checkAccess('op_tutorials_manage') ): ?>
				
					<?php if( $model->status ): ?>
						<?php echo CHtml::link(  CHtml::image( Yii::app()->themeManager->baseUrl . '/images/icons/cross_circle.png' ), array('tutorials/toggletutorial', 'id'=>$model->id), array('class'=>'linkcomment') ); ?>
					<?php else: ?>
						<?php echo CHtml::link(  CHtml::image( Yii::app()->themeManager->baseUrl . '/images/icons/tick_circle.png' ), array('tutorials/toggletutorial', 'id'=>$model->id), array('class'=>'linkcomment') ); ?>
					<?php endif; ?>	
				
				<?php endif; ?>
				
				<div class="clear"></div>
				<br />
				
				<p class="postinfo"><?php echo Yii::t('blog', 'Posted by <strong>{by}</strong> in {in} on {on}', array( '{by}' => $model->author ? $model->author->getProfileLink() : Yii::t('global', 'Guest'), '{on}' => Yii::app()->dateFormatter->formatDateTime($model->postdate, 'short', 'short'), '{in}' => CHtml::link( $model->category->title, array('/tutorials/category/' . $model->category->alias, 'lang' => false ) ) )); ?></p>
				<div class="clear"></div>
				
				<div id='toprint'>
					<?php echo $content; ?>
				</div>
				
				<div class='clear'></div>
				<a href="#" id='optionsbutton' class="linkcomment"><?php echo Yii::t('global', 'Options'); ?></a>
				<div class='clear'></div>
				<div id='pageoptions'>
					<ul>
						<li><?php echo CHtml::link( Yii::t('global', 'Print'), '#', array('id'=>'printdocument') ); ?></li>
						<li><?php echo CHtml::link( Yii::t('global', 'PDF'), array('tutorials/pdf', 'id'=>$model->id) ); ?></li>
						<li><?php echo CHtml::link( Yii::t('global', 'Word'), array('tutorials/word', 'id'=>$model->id) ); ?></li>
						<li><?php echo CHtml::link( Yii::t('global', 'Text'), array('tutorials/text', 'id'=>$model->id) ); ?></li>
					</ul>
				</div>
				
				<div id='sharingoptions'>
					<?php echo $facebook->showLike( Yii::app()->createAbsoluteUrl('/tutorials/view/'.$model->alias, array('lang'=>false)) ); ?>
					<script type="text/javascript" src="http://tweetmeme.com/i/scripts/button.js"></script>
					

					<div class='floatleft'>&nbsp;</div>
					
					<div class='clear'></div>
				</div>
				
				
		<div class="clear"></div><br />
		<h3 id="titlecomment"><?php echo Yii::t('tutorials', 'Comments'); ?> (<?php echo $totalcomments; ?>)</h3>
		<ul id="listcomment">
			<?php if( count( $comments ) ): ?>
				<?php foreach($comments as $comment): ?>
					<li <?php if( $comment->visible == 0 ): ?>style='background-color:#FFCECE;'<?php endif; ?>>
						<a name='comment<?php echo $comment->id; ?>'></a>
						<span class='commentspan'><?php echo CHtml::link( '#' . $comment->id, array('/tutorials/view/' . $model->alias, '#' => 'comment' . $comment->id, 'lang'=>false ) ); ?></span>
						<?php $this->widget('ext.VGGravatarWidget', array( 'size' => 50, 'email'=>$comment->author ? $comment->author->email : '','htmlOptions'=>array('class'=>'imgavatar','alt'=>'avatar'))); ?>
						<h4><?php echo $comment->author ? $comment->author->username : Yii::t('global', 'Unknown'); ?></h4>
						<span class="datecomment"><?php echo Yii::app()->dateFormatter->formatDateTime($comment->postdate, 'short', 'short'); ?></span>
						<div class="clear"></div>
						<p><?php echo $markdown->safeTransform($comment->comment); ?></p>
					    <?php if( Yii::app()->user->checkAccess('op_tutorials_comments') ): ?>
							<?php echo CHtml::link( CHtml::image( Yii::app()->themeManager->baseUrl . '/images/'. ($comment->visible ? 'cross_circle' : 'tick_circle') . '.png' ), array('tutorials/togglestatus', 'id' => $comment->id), array( 'class' => 'tooltip', 'title' => Yii::t('tutorials', 'Toggle comment status!') ) ); ?>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>	
			<?php else: ?>	
				<li><?php echo Yii::t('tutorials', 'No comments posted yet. Be the first!'); ?></li>
			<?php endif; ?>	
		</ul>
		<?php $this->widget('CLinkPager', array('pages'=>$pages)); ?>
		<?php if( $addcomments ): ?>
			
		<?php echo CHtml::form('', 'post', array('id'=>'frmcomment')); ?>
			<div>
				<?php echo CHtml::label(Yii::t('tutorials', 'Comment'), ''); ?>
				<?php $this->widget('widgets.markitup.markitup', array( 'model' => $commentsModel, 'attribute' => 'comment' )); ?>
				<?php echo CHtml::error($commentsModel, 'comment'); ?>
				<?php echo CHtml::submitButton(Yii::t('tutorials', 'Post Comment'), array( 'class' => 'submitcomment' )); ?>
			</div>
		<?php echo CHtml::endForm(); ?>
		
		<?php else: ?>
		<div><?php echo Yii::t('global', 'You must be logged in to post comments.'); ?></div>
		<?php endif; ?>	
	</div>
</div>

<script>
$(document).ready(function() {

         $("#printdocument").click(function() {	
 			$('#toprint').printElement({ printMode: 'popup', pageTitle: '<?php echo CHtml::encode($model->title); ?>', overrideElementCSS: ["<?php echo Yii::app()->themeManager->baseUrl . '/style/highlight.css'; ?>"] });
         });

     });
</script>

<?php echo $facebook->includeScript( Yii::app()->params['facebookappid'] ); ?>

<?php $this->widget('widgets.tutorialsidebar'); ?>
<div class="clear"></div>