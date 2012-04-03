<div id='indexcontent'>
<div id="maincontent">
			<div id="contentleft">
				<h2><?php echo Yii::t('index', 'What is Yii?'); ?></h2>
				<p class="textintro"><?php echo Yii::t('index', 'Yii is a high-performance  component-based PHP framework for developing large-scale Web applications.'); ?></p>

				<p><?php echo Yii::t('index', 'Yii enables maximum reusability in Web programming and can significantly accelerate the development process. The name Yii (pronounced as Yee or [ji:]) stands for easy, efficient and extensible.'); ?></p>
				
				<h4><?php echo Yii::t('index', 'Main Feature List'); ?></h4>

				<ul>
					<li><?php echo Yii::t('index', 'MVC design pattern'); ?></li>
					<li><?php echo Yii::t('index', 'DAO and Active Record'); ?></li>
					<li><?php echo Yii::t('index', 'jQuery-based JavaScript support'); ?></li>
					<li><?php echo Yii::t('index', 'I18N and L10N'); ?></li>
					<li><?php echo Yii::t('index', 'Page, fragment and data caching'); ?></li>
					<li><?php echo Yii::t('index', 'Error handling and logging'); ?></li>
					<li><?php echo Yii::t('index', 'Theming'); ?></li>
					<li><?php echo Yii::t('index', 'Web services'); ?></li>
					<li><?php echo Yii::t('index', 'Console applications'); ?></li>
					<li><?php echo Yii::t('index', 'Authentication and authorization'); ?></li>
					<li><?php echo Yii::t('index', 'Web 2.0 widgets'); ?></li>
					<li><?php echo Yii::t('index', 'Form input and validation'); ?></li>
					<li><?php echo CHtml::link(Yii::t('index', 'View complete feature list'), array('/features', 'lang'=>false)); ?></li>
				</ul>
				
				<!--<h2><?php echo Yii::t('index', 'Latest Extensions'); ?></h2>
				<ul class="listmenusmall">-->
					
					<?php echo $facebook->showLikeBox('128360757178028', 292, 300, 10, 'false'); ?>
					
					<?php /*if($this->beginCache('indexextensions_' . Yii::app()->language, array('duration'=>3600))) { ?>
					<?php $exts = Extensions::model()->byDate()->byLang()->limitIndex()->findAll('status=1'); ?>
					<?php if( is_array($exts) && count($exts) ): ?>
						<?php foreach($exts as $ext): ?>							
							<li><?php echo Extensions::model()->getLink( $ext->title, $ext->alias, array( 'title' => $ext->description ) ); ?></li>
						<?php endforeach; ?>	
					<?php else: ?>
						<li><?php echo Yii::t('index', 'No Extensions Available.'); ?></li>
					<?php endif; ?>
					<?php $this->endCache(); }*/ ?>
					
				<!--</ul>-->
			</div>
			<div id="contentright">
				<h2><?php echo Yii::t('index', 'Latest Tutorials'); ?></h2>
				<ul class="listmenusmall">
					
					<?php if($this->beginCache('indextutorials_' . Yii::app()->language, array('duration'=>3600))) { ?>
					<?php $tuts = Tutorials::model()->byDate()->byLang()->limitIndex()->findAll('status=1'); ?>
					<?php if( is_array($tuts) && count($tuts) ): ?>
						<?php foreach($tuts as $tut): ?>							
							<li><?php echo Tutorials::model()->getLink( $tut->title, $tut->alias, array( 'title' => $tut->description ) ); ?></li>
						<?php endforeach; ?>	
					<?php else: ?>
						<li><?php echo Yii::t('index', 'No Tutorials Available.'); ?></li>
					<?php endif; ?>
					<?php $this->endCache(); } ?>
					
				</ul>
				<h3><?php echo Yii::t('index', 'Why should you use Yii?'); ?></h3>
				<ul class="listicon">
					<li class="icon9">

						<strong><?php echo Yii::t('index', 'Easy'); ?></strong><br />
						<?php echo Yii::t('index', 'Yii is easy to learn and use. You only need to know PHP and object-oriented programming. You are not forced to learn a new configuration or templating language.'); ?>
					</li>
					<li class="icon9">
						<strong><?php echo Yii::t('index', 'Well Documented'); ?></strong><br />
						<?php echo Yii::t('index', 'Yii has very detailed {doc}. From the definitive guide to class reference, Yii has every information you need to quickly learn and master it.', array('{doc}'=>CHtml::link( Yii::t('index', 'Documentation'), array('/documentation', 'lang'=>false) ))); ?>
					</li>
					<li class="icon9">
						<strong><?php echo Yii::t('index', 'Feature Rich'); ?></strong><br />
						<?php echo Yii::t('index', 'Yii comes with a rich set of features. From MVC, DAO/ActiveRecord, to theming, internationalization and localization, Yii provides nearly every feature needed by today\'s Web 2.0 application development.'); ?>
					</li>
					<li class="icon9">
						<strong><?php echo Yii::t('index', 'Free!'); ?></strong><br />
						<?php echo Yii::t('index', 'Last but not least, Yii is free! Yii uses the new BSD license, and it also ensures that the third-party work it integrates with use BSD-compatible licenses. This means it is both financially and lawfully free for you to use Yii to develop either open source or proprietary applications.'); ?>
					</li>	
				</ul>
			</div>
			<div class="clear"></div>
		</div>
		<div id="nav">
			<div class="boxnav">
				<h2><?php echo Yii::t('index', 'Yii Key Features'); ?></h2>

				<ul class="menuiconnav">
					<li class="icon11"><a href="<?php echo Yii::app()->createUrl('documentation/guide', array( 'topic' => 'basics.mvc', 'lang'=>false)); ?>"><?php echo Yii::t('index', 'MVC Architecture'); ?></a></li>
					<li class="icon12"><a href="<?php echo Yii::app()->createUrl('documentation/guide', array( 'topic' => 'database.overview', 'lang'=>false)); ?>"><?php echo Yii::t('index', 'Database, DAO/ActiveRecord'); ?></a></li>
					<li class="icon3"><a href="<?php echo Yii::app()->createUrl('documentation/guide', array( 'topic' => 'topics.i18n', 'lang'=>false)); ?>"><?php echo Yii::t('index', 'Multilingual, I18N/L10N'); ?></a></li>
					<li class="icon13"><a href="<?php echo Yii::app()->createUrl('documentation/guide', array( 'topic' => 'basics.view', 'lang'=>false)); ?>"><?php echo Yii::t('index', 'Widgets Management'); ?></a></li>
					<li class="icon14"><a href="<?php echo Yii::app()->createUrl('documentation/guide', array( 'topic' => 'topics.theming', 'lang'=>false)); ?>"><?php echo Yii::t('index', 'Theme Control'); ?></a></li>

				</ul>
			</div>
			<div class="boxnavnoborder">
				<h2><?php echo Yii::t('index', 'About Us'); ?></h2>
				<p><?php echo Yii::t('index', '<strong>{name}</strong> is an <b>un</b>official Hebrew support site for the {yii}, It was built to provide the local users with some information, Documentation, Tutorials and extensions that are already written and/or translated into Hebrew.', array( '{name}'=>Yii::app()->name, '{yii}'=> CHtml::link('Yii Framework', 'http://yiiframework.com') )); ?></p>
				<p><?php echo Yii::t('index', 'The majority of the content provided here is written by the community and is provided totally free of charge.'); ?></p>

				<a href="<?php echo Yii::app()->createUrl('/about-us', array('lang'=>false)); ?>" class="linklearnmore"><?php echo Yii::t('index', 'Read More'); ?></a>
				<div class="clear"></div>
			</div>
			<br />
			<div class="boxnavnoborder">
				<h2><?php echo Yii::t('index', 'Newsletter'); ?></h2>
				<p><?php echo Yii::t('index', 'Signup to our newsletter, And stay up-to-date with the latest information, Documentation, Tutorials and extensions submissions.'); ?></p>
				
				<a name='newsletterform'></a>
				<?php echo CHtml::form('#newsletterform'); ?>
				<?php echo CHtml::activeLabel($model, 'email'); ?><br />
				<?php echo CHtml::activeTextField($model, 'email', array( 'class'=>'textboxcontact', 'style' => 'width:auto;', 'onfocus'=>"this.value='';" )); ?>
				<?php echo CHtml::error($model, 'email'); ?>
				<?php if( $sent ): ?>
					<br /><span style='color:green;'><?php echo Yii::t('index', 'Thank you. You are now subscribed to our newsletter.'); ?></span>
				<?php endif; ?>	
				<br /><br /><p><?php echo CHtml::submitButton( Yii::t('index', 'Subscribe'), array('name'=>'newsletter') ); ?></p>
				<?php echo CHtml::endForm(); ?>
				
				
				<div class="clear"></div>
			</div>
		</div>
</div>
		<div class="clear"></div>
		
<?php echo $facebook->includeScript( Yii::app()->params['facebookappid'] ); ?>		