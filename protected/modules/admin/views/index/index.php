<div class="content-box column-left">
	
	<div class="content-box-header">
		
		<h3><?php echo Yii::t('adminindex', 'Site Information'); ?></h3>
		
	</div> <!-- End .content-box-header -->
	
	<div class="content-box-content">
		
		<div class="tab-content default-tab">
		
			<table>
				<tr>
					<td><?php echo Yii::t('adminindex', 'Number Of Users'); ?></td>
					<td><?php echo Yii::app()->format->number( Members::model()->count() ); ?></td>
				</tr>
				<tr>
					<td><?php echo Yii::t('adminindex', 'Newsletter Signups'); ?></td>
					<td><?php echo Yii::app()->format->number( Newsletter::model()->count() ); ?></td>
				</tr>
				<tr>
					<td><?php echo Yii::t('adminindex', 'Last Registered'); ?></td>
					<td><?php echo Members::model()->find(array('order'=>'joined desc', 'limit'=>1))->getModelLink(); ?></td>
				</tr>
				<tr>
					<td><?php echo Yii::t('adminindex', 'User Comments'); ?></td>
					<td><?php echo Yii::app()->format->number( UserComments::model()->count() ); ?></td>
				</tr>
				<tr>
					<td><?php echo Yii::t('adminindex', 'Latest Comments'); ?></td>
					<td>
						<ul>
							<?php $lastusercomments = UserComments::model()->with(array('user'))->findAll(array('order'=>'postdate DESC', 'limit'=>5)); ?>
							<?php foreach($lastusercomments as $lastcomment): ?>
							<li><?php echo $lastcomment->user->getModelLink(); ?></li>
							<?php endforeach; ?>
						</ul>	
					</td>
				</tr>
			</table>	
			
		</div> <!-- End #tab3 -->        
		
	</div> <!-- End .content-box-content -->
	
</div> <!-- End .content-box -->

<div class="content-box column-right">
	
	<div class="content-box-header">
		
		<h3><?php echo Yii::t('adminindex', 'Documentation'); ?></h3>
		
	</div> <!-- End .content-box-header -->
	
	<div class="content-box-content">
		
		<div class="tab-content default-tab">
		
			<table>
				<tr>
					<td><?php echo Yii::t('adminindex', 'Documentation Views'); ?></td>
					<td><?php echo Yii::app()->format->number( Yii::app()->db->createCommand('SELECT SUM(views) as total FROM {{documentations}}')->queryScalar() ); ?></td>
				</tr>
				<tr>
					<td><?php echo Yii::t('adminindex', 'Documentation Comments'); ?></td>
					<td><?php echo Yii::app()->format->number( Yii::app()->db->createCommand('SELECT COUNT(id) as total FROM {{documentations_comments}}')->queryScalar() ); ?></td>
				</tr>
				<tr>
					<td><?php echo Yii::t('adminindex', 'Total Pending Comments'); ?></td>
					<td><?php echo Yii::app()->format->number( Yii::app()->db->createCommand('SELECT COUNT(id) as total FROM {{documentations_comments}} WHERE visible=0')->queryScalar() ); ?></td>
				</tr>
				<tr>
					<td><?php echo Yii::t('adminindex', 'Last Comments'); ?></td>
					<td>
						<ul>
							<?php $lastdoccomments = DocumentationComments::model()->with(array('doc'))->findAll(array('order'=>'postdate DESC', 'limit'=>5)); ?>
							<?php foreach($lastdoccomments as $doccomment): ?>
							<li><?php echo $doccomment->doc ? $doccomment->doc->getLink() : ''; ?></li>
							<?php endforeach; ?>
						</ul>
					</td>
				</tr>
			</table>
			
			
		</div> <!-- End #tab3 -->        
		
	</div> <!-- End .content-box-content -->
	
</div> <!-- End .content-box -->
<div class="clear"></div>

<div class="content-box column-left">   
		
		<div class="content-box-header">

			<h3><?php echo Yii::t('adminindex', 'Blog Info'); ?></h3>

		</div> <!-- End .content-box-header -->

		<div class="content-box-content">

			<div class="tab-content default-tab">

				<table>
					<tr>
						<td><?php echo Yii::t('adminindex', 'Total Categories'); ?></td>
						<td><?php echo Yii::app()->format->number( Yii::app()->db->createCommand('SELECT COUNT(id) as total FROM {{blogcats}}')->queryScalar() ); ?></td>
					</tr>
					<tr>
						<td><?php echo Yii::t('adminindex', 'Total Posts'); ?></td>
						<td><?php echo Yii::app()->format->number( Yii::app()->db->createCommand('SELECT COUNT(id) as total FROM {{blogposts}}')->queryScalar() ); ?></td>
					</tr>
					<tr>
						<td><?php echo Yii::t('adminindex', 'Total Pending Posts'); ?></td>
						<td><?php echo Yii::app()->format->number( Yii::app()->db->createCommand('SELECT COUNT(id) as total FROM {{blogposts}} WHERE status = 0')->queryScalar() ); ?></td>
					</tr>
					<tr>
						<td><?php echo Yii::t('adminindex', 'Total Views'); ?></td>
						<td><?php echo Yii::app()->format->number( Yii::app()->db->createCommand('SELECT SUM(views) as total FROM {{blogposts}}')->queryScalar() ); ?></td>
					</tr>
					<tr>
						<td><?php echo Yii::t('adminindex', 'Total Comments'); ?></td>
						<td><?php echo Yii::app()->format->number( Yii::app()->db->createCommand('SELECT COUNT(id) as total FROM {{blogcomments}}')->queryScalar() ); ?></td>
					</tr>
					<tr>
						<td><?php echo Yii::t('adminindex', 'Total Pending Comments'); ?></td>
						<td><?php echo Yii::app()->format->number( Yii::app()->db->createCommand('SELECT COUNT(id) as total FROM {{blogcomments}} WHERE visible=0')->queryScalar() ); ?></td>
					</tr>
					<tr>
						<td><?php echo Yii::t('adminindex', 'Last Comments'); ?></td>
						<td>
							<ul>
								<?php $lastblogcomments = BlogComments::model()->with(array('post'))->findAll(array('order'=>'t.postdate DESC', 'limit'=>5)); ?>
								<?php foreach($lastblogcomments as $blogcomm): ?>
								<li><?php echo $blogcomm->post->getModelLink(); ?></li>
								<?php endforeach; ?>
							</ul>
						</td>
					</tr>
				</table>


			</div> <!-- End #tab3 -->     
		
	</div> <!-- End .content-box-content -->
	
</div> <!-- End .content-box -->

<div class="content-box column-right">
	
	     <div class="content-box-header">

			<h3><?php echo Yii::t('adminindex', 'Custom Pages'); ?></h3>

		</div> <!-- End .content-box-header -->

		<div class="content-box-content">

			<div class="tab-content default-tab">

				<table>
					<tr>
						<td><?php echo Yii::t('adminindex', 'Number Of Pages'); ?></td>
						<td><?php echo Yii::app()->format->number( Yii::app()->db->createCommand('SELECT COUNT(id) as total FROM {{custompages}}')->queryScalar() ); ?></td>
					</tr>
					<tr>
						<td><?php echo Yii::t('adminindex', 'Hidden Pages'); ?></td>
						<td><?php echo Yii::app()->format->number( Yii::app()->db->createCommand('SELECT COUNT(id) as total FROM {{custompages}} WHERE status=0')->queryScalar() ); ?></td>
					</tr>
				</table>	

			</div> <!-- End #tab3 --> 
		
	</div> <!-- End .content-box-content -->
	
</div> <!-- End .content-box -->
<div class="clear"></div>

<div class="content-box column-left">   
		
		<div class="content-box-header">

			<h3><?php echo Yii::t('adminindex', 'Tutorials Info'); ?></h3>

		</div> <!-- End .content-box-header -->

		<div class="content-box-content">

			<div class="tab-content default-tab">

				<table>
					<tr>
						<td><?php echo Yii::t('adminindex', 'Total Categories'); ?></td>
						<td><?php echo Yii::app()->format->number( Yii::app()->db->createCommand('SELECT COUNT(id) as total FROM {{tutorialscats}}')->queryScalar() ); ?></td>
					</tr>
					<tr>
						<td><?php echo Yii::t('adminindex', 'Total Tutorials'); ?></td>
						<td><?php echo Yii::app()->format->number( Yii::app()->db->createCommand('SELECT COUNT(id) as total FROM {{tutorials}}')->queryScalar() ); ?></td>
					</tr>
					<tr>
						<td><?php echo Yii::t('adminindex', 'Total Pending Tutorials'); ?></td>
						<td><?php echo Yii::app()->format->number( Yii::app()->db->createCommand('SELECT COUNT(id) as total FROM {{tutorials}} WHERE status = 0')->queryScalar() ); ?></td>
					</tr>
					<tr>
						<td><?php echo Yii::t('adminindex', 'Total Views'); ?></td>
						<td><?php echo Yii::app()->format->number( Yii::app()->db->createCommand('SELECT SUM(views) as total FROM {{tutorials}}')->queryScalar() ); ?></td>
					</tr>
					<tr>
						<td><?php echo Yii::t('adminindex', 'Total Comments'); ?></td>
						<td><?php echo Yii::app()->format->number( Yii::app()->db->createCommand('SELECT COUNT(id) as total FROM {{tutorialscomments}}')->queryScalar() ); ?></td>
					</tr>
					<tr>
						<td><?php echo Yii::t('adminindex', 'Total Pending Comments'); ?></td>
						<td><?php echo Yii::app()->format->number( Yii::app()->db->createCommand('SELECT COUNT(id) as total FROM {{tutorialscomments}} WHERE visible=0')->queryScalar() ); ?></td>
					</tr>
					<tr>
						<td><?php echo Yii::t('adminindex', 'Last Comments'); ?></td>
						<td>
							<ul>
								<?php $lasttutcomments = TutorialsComments::model()->with(array('tutorial'))->findAll(array('order'=>'t.postdate DESC', 'limit'=>5)); ?>
								<?php foreach($lasttutcomments as $tutcomm): ?>
								<li><?php echo $tutcomm->tutorial->getModelLink(); ?></li>
								<?php endforeach; ?>
							</ul>
						</td>
					</tr>
				</table>


			</div> <!-- End #tab3 -->     
		
	</div> <!-- End .content-box-content -->
	
</div> <!-- End .content-box -->

<div class="content-box column-right">
	
	     <div class="content-box-header">

			<h3><?php echo Yii::t('adminindex', 'Extensions'); ?></h3>

		</div> <!-- End .content-box-header -->

		<div class="content-box-content">

			<div class="tab-content default-tab">

				<table>
					<tr>
						<td><?php echo Yii::t('adminindex', 'Total Categories'); ?></td>
						<td><?php echo Yii::app()->format->number( Yii::app()->db->createCommand('SELECT COUNT(id) as total FROM {{extensionscats}}')->queryScalar() ); ?></td>
					</tr>
					<tr>
						<td><?php echo Yii::t('adminindex', 'Total Extensions'); ?></td>
						<td><?php echo Yii::app()->format->number( Yii::app()->db->createCommand('SELECT COUNT(id) as total FROM {{extensions}}')->queryScalar() ); ?></td>
					</tr>
					<tr>
						<td><?php echo Yii::t('adminindex', 'Total Pending Extensions'); ?></td>
						<td><?php echo Yii::app()->format->number( Yii::app()->db->createCommand('SELECT COUNT(id) as total FROM {{extensions}} WHERE status = 0')->queryScalar() ); ?></td>
					</tr>
					<tr>
						<td><?php echo Yii::t('adminindex', 'Total Views'); ?></td>
						<td><?php echo Yii::app()->format->number( Yii::app()->db->createCommand('SELECT SUM(views) as total FROM {{extensions}}')->queryScalar() ); ?></td>
					</tr>
					<tr>
						<td><?php echo Yii::t('adminindex', 'Total Comments'); ?></td>
						<td><?php echo Yii::app()->format->number( Yii::app()->db->createCommand('SELECT COUNT(id) as total FROM {{extensionscomments}}')->queryScalar() ); ?></td>
					</tr>
					<tr>
						<td><?php echo Yii::t('adminindex', 'Total Pending Comments'); ?></td>
						<td><?php echo Yii::app()->format->number( Yii::app()->db->createCommand('SELECT COUNT(id) as total FROM {{extensionscomments}} WHERE visible=0')->queryScalar() ); ?></td>
					</tr>
					<tr>
						<td><?php echo Yii::t('adminindex', 'Total Files'); ?></td>
						<td><?php echo Yii::app()->format->number( Yii::app()->db->createCommand('SELECT COUNT(id) as total FROM {{extensionsfiles}}')->queryScalar() ); ?></td>
					</tr>
					<tr>
						<td><?php echo Yii::t('adminindex', 'Last Comments'); ?></td>
						<td>
							<ul>
								<?php $lastextcomments = ExtensionsComments::model()->with(array('extension'))->findAll(array('order'=>'t.postdate DESC', 'limit'=>5)); ?>
								<?php foreach($lastextcomments as $extcomm): ?>
								<li><?php echo $extcomm->extension->getModelLink(); ?></li>
								<?php endforeach; ?>
							</ul>
						</td>
					</tr>
				</table>	

			</div> <!-- End #tab3 --> 
		
	</div> <!-- End .content-box-content -->
	
</div> <!-- End .content-box -->
<div class="clear"></div>

<div class="content-box column-left">   
		
		<div class="content-box-header">

			<h3><?php echo Yii::t('adminindex', 'Contact Us Info'); ?></h3>

		</div> <!-- End .content-box-header -->

		<div class="content-box-content">

			<div class="tab-content default-tab">

				<table>
					<tr>
						<td><?php echo Yii::t('adminindex', 'Total Sent'); ?></td>
						<td><?php echo Yii::app()->format->number( Yii::app()->db->createCommand('SELECT COUNT(id) as total FROM {{contactus}}')->queryScalar() ); ?></td>
					</tr>
				</table>


			</div> <!-- End #tab3 -->     
		
	</div> <!-- End .content-box-content -->
	
</div> <!-- End .content-box -->

<div class="content-box column-right">
	
	     <div class="content-box-header">

			<h3><?php echo Yii::t('adminindex', 'Forum'); ?></h3>

		</div> <!-- End .content-box-header -->

		<div class="content-box-content">

			<div class="tab-content default-tab">

				<table>
					<tr>
						<td><?php echo Yii::t('adminindex', 'Total Topics'); ?></td>
						<td><?php echo Yii::app()->format->number( Yii::app()->db->createCommand('SELECT COUNT(id) as total FROM {{forumtopics}}')->queryScalar() ); ?></td>
					</tr>
					<tr>
						<td><?php echo Yii::t('adminindex', 'Total Posts'); ?></td>
						<td><?php echo Yii::app()->format->number( Yii::app()->db->createCommand('SELECT COUNT(id) as total FROM {{forumposts}}')->queryScalar() ); ?></td>
					</tr>
					<tr>
						<td><?php echo Yii::t('adminindex', 'Total Pending Topics'); ?></td>
						<td><?php echo Yii::app()->format->number( Yii::app()->db->createCommand('SELECT COUNT(id) as total FROM {{forumtopics}} WHERE visible = 0')->queryScalar() ); ?></td>
					</tr>
					<tr>
						<td><?php echo Yii::t('adminindex', 'Total Pending Posts'); ?></td>
						<td><?php echo Yii::app()->format->number( Yii::app()->db->createCommand('SELECT COUNT(id) as total FROM {{forumposts}} WHERE visible = 0')->queryScalar() ); ?></td>
					</tr>
					<tr>
						<td><?php echo Yii::t('adminindex', 'Last Posts'); ?></td>
						<td>
							<ul>
								<?php $lastforumposts = ForumPosts::model()->with(array('topic'))->findAll(array('order'=>'t.dateposted DESC', 'limit'=>5)); ?>
								<?php foreach($lastforumposts as $postcomm): ?>
								<li><?php echo $postcomm->topic->getLink(); ?></li>
								<?php endforeach; ?>
							</ul>
						</td>
					</tr>
				</table>

			</div> <!-- End #tab3 --> 
		
	</div> <!-- End .content-box-content -->
	
</div> <!-- End .content-box -->
<div class="clear"></div>