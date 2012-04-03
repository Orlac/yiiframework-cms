
<?php 
	Yii::app()->clientScript->registerCssFile( Yii::app()->themeManager->baseUrl . '/style/highlight.css', 'screen' );
?>



<div id="maincontent">
	<div id="contentbig">
				<a href="#titlecomment" class="linkcomment"><strong><?php echo $totalcomments; ?></strong> <?php echo Yii::t('docs', 'Comments'); ?></a>
				&nbsp;
				<a href="#" class="linkcomment"><strong><?php echo $model->views; ?></strong> <?php echo Yii::t('docs', 'Views'); ?></a>
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
				            url: "'.$this->createUrl('documentation/rating').'",
				            data: "'.Yii::app()->request->csrfTokenName . '=' . Yii::app()->request->csrfToken .'&id='.$model->id.'&rate=" + $(this).val(),
				            success: function(msg){
				                alert("'.Yii::t('global', 'Rating Added.').'");
				        }})}'
				));?>
				
				<div class="clear"></div>

				<div id='toprint'>
					<?php echo $content; ?>
				</div>
				
		<div class="clear"></div><br />
		<h3 id="titlecomment"><?php echo Yii::t('docs', 'Comments'); ?> (<?php echo $totalcomments; ?>)</h3>
		<ul id="listcomment">
			<?php if( count( $comments ) ): ?>
				<?php foreach($comments as $comment): ?>
					<li <?php if( $comment->visible == 0 ): ?>style='background-color:#FFCECE;'<?php endif; ?>>
						<a name='comment<?php echo $comment->id; ?>'></a>
						<span class='commentspan'><?php echo CHtml::link( '#' . $comment->id, array('documentation/'.$type, 'topic'=>$this->getTopic($type), '#' => 'comment' . $comment->id ) ); ?></span>
						<?php $this->widget('ext.VGGravatarWidget', array( 'size' => 50, 'email'=>$comment->author ? $comment->author->email : '','htmlOptions'=>array('class'=>'imgavatar','alt'=>'avatar'))); ?>
						<h4><?php echo $comment->author ? $comment->author->username : Yii::t('global', 'Unknown'); ?></h4>
						<span class="datecomment"><?php echo Yii::app()->dateFormatter->formatDateTime($comment->postdate, 'long', 'short'); ?></span>
						<div class="clear"></div>
						<p><?php echo $markdown->safeTransform($comment->comment); ?></p>
					    <?php if( Yii::app()->user->checkAccess('op_doc_manage_comments') ): ?>
							<?php echo CHtml::link( CHtml::image( Yii::app()->themeManager->baseUrl . '/images/'. ($comment->visible ? 'cross_circle' : 'tick_circle') . '.png' ), array('documentation/togglestatus', 'id' => $comment->id), array( 'class' => 'tooltip', 'title' => Yii::t('admindocs', 'Toggle comment status!') ) ); ?>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>	
			<?php else: ?>	
				<li><?php echo Yii::t('docs', 'No comments posted yet. Be the first!'); ?></li>
			<?php endif; ?>	
		</ul>
		<?php $this->widget('CLinkPager', array('pages'=>$pages)); ?>
		<?php if( Yii::app()->user->checkAccess('op_doc_add_comments') ): ?>
			
		<?php echo CHtml::form('', 'post', array('id'=>'frmcomment')); ?>
			<div>
				<?php echo CHtml::label(Yii::t('docs', 'Comment'), ''); ?>
				<?php $this->widget('widgets.markitup.markitup', array( 'model' => $commentsModel, 'attribute' => 'comment' )); ?>
				<?php echo CHtml::error($commentsModel, 'comment'); ?>
				<?php echo CHtml::submitButton(Yii::t('docs', 'Post Comment'), array( 'class' => 'submitcomment' )); ?>
			</div>
		<?php echo CHtml::endForm(); ?>
		
		<?php else: ?>
		<div><?php echo Yii::t('docs', 'You must be logged in to post comments.'); ?></div>
		<?php endif; ?>	
	</div>
</div>

<div id="nav">
	<div class="boxnavnoborder">	
		<ul class='menunav toc first'>
		<?php
		foreach($this->getTopics($type) as $title=>$topics)
		{
			echo '<li><b>'.$title."</b></li>\n\t<ul>\n";
			foreach($topics as $path=>$text)
			{
				if($path===$this->topic)
					echo "\t<li class=\"selected\">";
				else
					echo "\t<li>";
				echo CHtml::link(CHtml::encode($text),array($type,'topic'=>$path));
				echo "</li>\n";
			}
			echo "\t</ul>\n";
		}
		?>
		</ul>
		
	</div>
</div>
<div class="clear"></div>

<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->themeManager->baseUrl . '/script/inlineReport.js' , CClientScript::POS_END ); ?>
<?php Yii::app()->clientScript->registerCssFile( Yii::app()->themeManager->baseUrl . '/style/inlineReport.css' ); ?>


<script>
// Documentation Menu Accordion //
$(function(){
	$(".toc > li").click(function(){
		$('.toc > li').next().slideUp(300);
		$(this).next().slideToggle(300);
	});
	$('.toc > li').next().hide();
	$('.toc > ul > li.selected').parent().show();
	

	$('#inlinereport').inlineReport({ 'email':'support@yiiframework.co.il', 'CloseImageLink': '<?php echo Yii::app()->themeManager->baseUrl; ?>/images/close_pop.png' });  
	
});
</script>