
<?php 
	Yii::app()->clientScript->registerCssFile( Yii::app()->themeManager->baseUrl . '/style/highlight.css', 'screen' );
	Yii::app()->clientScript->registerScriptFile( Yii::app()->themeManager->baseUrl . '/script/jquery.printElement.min.js', CClientScript::POS_END );
?>

<div id="maincontent">
	<div id="contentbig">
				<a href="#titlecomment" class="linkcomment"><strong><?php echo $totalcomments; ?></strong> <?php echo Yii::t('extensions', 'Comments'); ?></a>
				&nbsp;
				<a href="#" class="linkcomment"><strong><?php echo $model->views; ?></strong> <?php echo Yii::t('extensions', 'Views'); ?></a>
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
				            url: "'.$this->createUrl('extensions/rating').'",
				            data: "'.Yii::app()->request->csrfTokenName . '=' . Yii::app()->request->csrfToken .'&id='.$model->id.'&rate=" + $(this).val(),
				            success: function(msg){
				                alert("'.Yii::t('global', 'Rating Added.').'");
				        }})}'
				));?>
				
				<?php if( Extensions::model()->canEditPost( $model ) ): ?>
					<?php echo CHtml::link(  CHtml::image( Yii::app()->themeManager->baseUrl . '/images/icons/pencil.png' ), array('extensions/editpost', 'id'=>$model->id), array('class'=>'linkcomment') ); ?>
				<?php endif; ?>	
				
				<?php if( Yii::app()->user->checkAccess('op_extensions_manage') ): ?>
				
					<?php if( $model->status ): ?>
						<?php echo CHtml::link(  CHtml::image( Yii::app()->themeManager->baseUrl . '/images/icons/cross_circle.png' ), array('extensions/togglepost', 'id'=>$model->id), array('class'=>'linkcomment') ); ?>
					<?php else: ?>
						<?php echo CHtml::link(  CHtml::image( Yii::app()->themeManager->baseUrl . '/images/icons/tick_circle.png' ), array('extensions/togglepost', 'id'=>$model->id), array('class'=>'linkcomment') ); ?>
					<?php endif; ?>	
				
				<?php endif; ?>
				
				<div class="clear"></div>
				<br />
				
				<p class="postinfo"><?php echo Yii::t('extensions', 'Posted by <strong>{by}</strong> in {in} on {on}', array( '{by}' => $model->author ? $model->author->getProfileLink() : Yii::t('global', 'Guest'), '{on}' => Yii::app()->dateFormatter->formatDateTime($model->postdate, 'short', 'short'), '{in}' => CHtml::link( $model->category->title, array('/extensions/category/' . $model->category->alias, 'lang' => false ) ) )); ?></p>
				<div class="clear"></div>
				
				<div id='toprint'>
					<?php echo $content; ?>
				</div>
				
				<div class='clear'></div>
				<div class='extensiondownload'>
					<?php if( Extensions::model()->canEditPost( $model ) ): ?>					
					
					<div id="formcenter" class='extensionsdownloadform'>
						<h2 id='openform' style='cursor:pointer;'><?php echo Yii::t('extensions', 'Upload A File'); ?></h2>

						<div id='uploadform' style='display:none;'>
						<p><?php echo Yii::t('extensions', 'Please choose a file to upload and enter a description for that file. Then press the submit button.'); ?></p>

						<a name='uploads'></a>
						<?php if($fileModel->hasErrors()): ?>
						<div class="errordiv">
							<?php echo CHtml::errorSummary($fileModel); ?>
						</div>
						<?php endif; ?>

						<?php echo CHtml::form('#uploads', 'post', array( 'enctype' => 'multipart/form-data' )); ?>
						<?php echo CHtml::hiddenField('extensionid', $model->id); ?>
						
						<div>

							<?php echo CHtml::activeLabel($fileModel, 'realname', array('class' => 'inputfile')); ?>
							<?php echo CHtml::activeFileField($fileModel, 'realname', array( 'class' => 'file' )); ?>
							<?php echo CHtml::error($fileModel, 'realname', array( 'class' => 'errorfield' )); ?>
							
							<br />
							
							<?php echo CHtml::activeLabel($fileModel, 'description'); ?>
							<?php echo CHtml::activeTextField($fileModel, 'description', array( 'class' => 'textboxcontact' )); ?>
							<?php echo CHtml::error($fileModel, 'description', array( 'class' => 'errorfield' )); ?>
							
							<br /><br />

							<p>
								<?php echo CHtml::submitButton(Yii::t('global', 'Submit'), array('class'=>'submitcomment', 'name'=>'submit')); ?>
							</p>

						</div>

						<?php echo CHtml::endForm(); ?>
						
						</div>
						<h2><?php echo Yii::t('extensions', 'Download A File'); ?></h2>
						<table width='100%'>
							<tr>
								<th><?php echo Yii::t('extensions', 'File Name'); ?></th>
								<th><?php echo Yii::t('extensions', 'File Description'); ?></th>
								<th><?php echo Yii::t('extensions', 'File Size'); ?></th>
								<th><?php echo Yii::t('extensions', 'File Type'); ?></th>
								<th><?php echo Yii::t('extensions', 'Uploaded Date'); ?></th>
								<th><?php echo Yii::t('extensions', 'Downloads'); ?></th>
								<?php if( Extensions::model()->canEditPost( $model ) ): ?>
								<th><?php echo Yii::t('extensions', 'Delete'); ?></th>
								<?php endif; ?>	
							</tr>
							<?php if( count($model->files) ): ?>
								
								<?php foreach($model->files as $file): ?>
									<tr>
										<td><?php echo $file->getLink( $file->realname, $file->alias ); ?></td>
										<td><?php echo CHtml::encode($file->description); ?></td>
										<td><?php echo Yii::app()->func->bytesToSize( $file->size ); ?></td>
										<td><?php echo $file->mime; ?></td>
										<td><?php echo Yii::app()->dateFormatter->formatDateTime($file->postdate, 'short', 'short'); ?></td>
										<td><?php echo $file->downloads; ?></td>
										<?php if( Extensions::model()->canEditPost( $model ) ): ?>
										<td><?php echo CHtml::link( CHtml::image( Yii::app()->themeManager->baseUrl . '/images/icons/cross_circle.png' ), array('extensions/deletefile', 'id'=>$file->id) ); ?></td>
										<?php endif; ?>
									</tr>
								<?php endforeach; ?>
								
							<?php else: ?>
								<tr>
									<td colspan='<?php if( Extensions::model()->canEditPost( $model ) ): ?>7<?php else: ?>6<?php endif; ?>'><?php echo Yii::t('extensions', 'No Files Uploaded Yet.'); ?></td>
								</tr>
							<?php endif; ?>
						</table>	
						
					</div>
					
					<?php endif; ?>
					
				</div>
				
				<br />
				
				<div class='clear'></div>
				<a href="#" id='optionsbutton' class="linkcomment"><?php echo Yii::t('global', 'Options'); ?></a>
				<div class='clear'></div>
				<div id='pageoptions'>
					<ul>
						<li><?php echo CHtml::link( Yii::t('global', 'Print'), '#', array('id'=>'printdocument') ); ?></li>
						<li><?php echo CHtml::link( Yii::t('global', 'PDF'), array('extensions/pdf', 'id'=>$model->id) ); ?></li>
						<li><?php echo CHtml::link( Yii::t('global', 'Word'), array('extensions/word', 'id'=>$model->id) ); ?></li>
						<li><?php echo CHtml::link( Yii::t('global', 'Text'), array('extensions/text', 'id'=>$model->id) ); ?></li>
					</ul>
				</div>
				
				<div id='sharingoptions'>
					<?php echo $facebook->showLike( Yii::app()->createAbsoluteUrl('/extensions/view/'.$model->alias, array('lang'=>false)) ); ?>
					<script type="text/javascript" src="http://tweetmeme.com/i/scripts/button.js"></script>
					

					<div class='floatleft'>&nbsp;</div>
					
					<div class='clear'></div>
				</div>
				
				
		<div class="clear"></div><br />
		<h3 id="titlecomment"><?php echo Yii::t('extensions', 'Comments'); ?> (<?php echo $totalcomments; ?>)</h3>
		<ul id="listcomment">
			<?php if( count( $comments ) ): ?>
				<?php foreach($comments as $comment): ?>
					<li <?php if( $comment->visible == 0 ): ?>style='background-color:#FFCECE;'<?php endif; ?>>
						<a name='comment<?php echo $comment->id; ?>'></a>
						<span class='commentspan'><?php echo CHtml::link( '#' . $comment->id, array('/extensions/view/' . $model->alias, '#' => 'comment' . $comment->id, 'lang'=>false ) ); ?></span>
						<?php $this->widget('ext.VGGravatarWidget', array( 'size' => 50, 'email'=>$comment->author ? $comment->author->email : '','htmlOptions'=>array('class'=>'imgavatar','alt'=>'avatar'))); ?>
						<h4><?php echo $comment->author ? $comment->author->username : Yii::t('global', 'Unknown'); ?></h4>
						<span class="datecomment"><?php echo Yii::app()->dateFormatter->formatDateTime($comment->postdate, 'short', 'short'); ?></span>
						<div class="clear"></div>
						<p><?php echo $markdown->safeTransform($comment->comment); ?></p>
					    <?php if( Yii::app()->user->checkAccess('op_extensions_comments') ): ?>
							<?php echo CHtml::link( CHtml::image( Yii::app()->themeManager->baseUrl . '/images/'. ($comment->visible ? 'cross_circle' : 'tick_circle') . '.png' ), array('extensions/togglestatus', 'id' => $comment->id), array( 'class' => 'tooltip', 'title' => Yii::t('extensions', 'Toggle comment status!') ) ); ?>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>	
			<?php else: ?>	
				<li><?php echo Yii::t('extensions', 'No comments posted yet. Be the first!'); ?></li>
			<?php endif; ?>	
		</ul>
		<?php $this->widget('CLinkPager', array('pages'=>$pages)); ?>
		<?php if( $addcomments ): ?>
			
		<?php echo CHtml::form('', 'post', array('id'=>'frmcomment')); ?>
			<div>
				<?php echo CHtml::label(Yii::t('extensions', 'Comment'), ''); ?>
				<?php $this->widget('widgets.markitup.markitup', array( 'model' => $commentsModel, 'attribute' => 'comment' )); ?>
				<?php echo CHtml::error($commentsModel, 'comment'); ?>
				<?php echo CHtml::submitButton(Yii::t('extensions', 'Post Comment'), array( 'class' => 'submitcomment' )); ?>
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

		$('#openform').click(function (){
			if( $('#uploadform').is(':visible') )
			{
				$('#uploadform').fadeOut();
			}
			else
			{
				$('#uploadform').fadeIn();
			}
		});
		
		<?php if($fileModel->hasErrors()): ?>
			$('#uploadform').show();
		<?php endif; ?>

     });
</script>

<?php echo $facebook->includeScript( Yii::app()->params['facebookappid'] ); ?>

<?php $this->widget('widgets.extensionssidebar'); ?>
<div class="clear"></div>