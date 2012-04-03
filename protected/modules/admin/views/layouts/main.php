<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
 
	<head>
		
		<meta http-equiv="Content-Type" content="text/html; charset=<?php echo Yii::app()->charset; ?>" />
		
		<title><?php echo implode( ' - ', $this->pageTitle ); ?></title>
		
		<!--CSS-->
	  
		<!-- Reset Stylesheet -->
		<?php Yii::app()->clientScript->registerCssFile( Yii::app()->themeManager->baseUrl . '/css/reset.css', 'screen' ); ?>
		<?php Yii::app()->clientScript->registerCssFile( Yii::app()->themeManager->baseUrl . '/css/style.css', 'screen' ); ?>
		<?php Yii::app()->clientScript->registerCssFile( Yii::app()->themeManager->baseUrl . '/css/invalid.css', 'screen' ); ?>
			
		<?php //Yii::app()->clientScript->registerCssFile( Yii::app()->themeManager->baseUrl . '/css/blue.css', 'screen' ); ?>
		<?php //Yii::app()->clientScript->registerCssFile( Yii::app()->themeManager->baseUrl . '/css/red.css', 'screen' ); ?>

		<!-- Internet Explorer Fixes Stylesheet -->
		
		<!--[if lte IE 7]>
			<link rel="stylesheet" href="<?php echo Yii::app()->themeManager->baseUrl; ?>/css/ie.css" type="text/css" media="screen" />
		<![endif]-->
		
		<!-- Javascripts -->
  
		<!-- jQuery -->
		<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
		
		<script type="text/javascript">
		var themeUrl = '<?php echo Yii::app()->themeManager->baseUrl; ?>';
		var _languages = {
			'deletePrompt': '<?php echo Yii::t('adminglobal', 'Are you sure you want to delete this item?\nThis action cannot be undone!'); ?>',
			'deleteAborted': '<?php echo Yii::t('adminglobal', 'OK! Action Cancled.'); ?>'
		};
		</script>
		
		<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->themeManager->baseUrl . '/scripts/simpla.jquery.configuration.js', CClientScript::POS_END ); ?>
		<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->themeManager->baseUrl . '/scripts/facebox.js', CClientScript::POS_END ); ?>
		<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->themeManager->baseUrl . '/scripts/easyTooltip.js', CClientScript::POS_END ); ?>
		<?php //Yii::app()->clientScript->registerScriptFile( Yii::app()->themeManager->baseUrl . '/scripts/jquery.wysiwyg.js', CClientScript::POS_END ); ?>
		
		<?php
		
		if( Yii::app()->locale->getOrientation() == 'rtl' )
		{
			Yii::app()->clientScript->registerCssFile( Yii::app()->themeManager->baseUrl . '/css/rtl.css', 'screen' );
		}
		
		?>
		
		<!-- Internet Explorer .png-fix -->
		
		<!--[if IE 6]>
			<script type="text/javascript" src="<?php echo Yii::app()->themeManager->baseUrl; ?>/scripts/DD_belatedPNG_0.0.7a.js"></script>
			<script type="text/javascript">
				DD_belatedPNG.fix('.png_bg, img, li');
			</script>
		<![endif]-->
		
	</head>
  
	<body>
		
		<div id="body-wrapper"> <!-- Wrapper for the radial gradient background -->
		
		<div id="sidebar">
			<div id="sidebar-wrapper"> <!-- Sidebar with logo and menu -->
			
			<h1 id="sidebar-title"><a class='tooltip' title='<?php echo Yii::t('adminglobal', 'Admin Panel'); ?>' href="<?php echo $this->createUrl('index'); ?>"><?php echo Yii::t('adminglobal', 'Admin Panel'); ?></a></h1>
		  
			<!-- Sidebar Profile links -->
			<div id="profile-links">
				<?php echo Yii::t('adminglobal', 'Hello,'); ?> <a href="<?php echo $this->createUrl('members/edituser', array('id'=>intval(Yii::app()->user->id))); ?>" title="<?php echo Yii::t('adminglobal', 'Edit your profile'); ?>"><?php echo Yii::app()->user->name; ?></a>, <?php echo Yii::t('adminglobal', 'You have'); ?> <a href="#messages" rel="modal" title="<?php echo Yii::t('adminglobal', '{n} Messages', array('{n}'=>0)); ?>"><?php echo Yii::t('adminglobal', '{n} Messages', array('{n}'=>0)); ?></a><br />
				<br />
				<a href="<?php echo Yii::app()->urlManager->createUrl('site/index/index'); ?>" target='_blank' title="<?php echo Yii::t('adminglobal', 'View the Site'); ?>"><?php echo Yii::t('adminglobal', 'View the Site'); ?></a> | <a href="<?php echo Yii::app()->createUrl('logout', array('lang'=>false)); ?>" title="<?php echo Yii::t('adminglobal', 'Sign Out'); ?>"><?php echo Yii::t('adminglobal', 'Sign Out'); ?></a>
			</div>        
			
			<!-- Start #main-nav -->
			<?php $this->widget('widgets.admin.sidebar'); ?>
			<!-- End #main-nav -->
			
			<!-- Start #messages -->
			<?php $this->widget('widgets.admin.messagesmodal'); ?>
			<!-- End #messages -->
			
		</div></div> <!-- End #sidebar -->
		
		<div id="main-content"> <!-- Main Content Section with everything -->
			
			<noscript> <!-- Show a notification if the user has disabled javascript -->
				<div class="notification error png_bg">
					<div>
						<?php echo Yii::t('adminglobal', 'Javascript is disabled or is not supported by your browser. Please <a href="http://browsehappy.com/" title="Upgrade to a better browser">upgrade</a> your browser or <a href="http://www.google.com/support/bin/answer.py?answer=23852" title="Enable Javascript in your browser">enable</a> Javascript to navigate the interface properly.'); ?>
					</div>
				</div>
			</noscript>
			
			<!-- Start Notifications -->
			<?php if( Yii::app()->user->hasFlash('error') ): ?>
				<div class="notification errorshow png_bg">
					<a href="#" class="close"><img src="<?php echo Yii::app()->themeManager->baseUrl; ?>/images/icons/cross_grey_small.png" title="<?php echo Yii::t('adminglobal', 'Close this notification'); ?>" alt="close" /></a>
					<div><?php echo Yii::app()->user->getFlash('error'); ?></div>
				</div>
			<?php endif; ?>
			
			<?php if( Yii::app()->user->hasFlash('attention') ): ?>
				<div class="notification attention png_bg">
					<a href="#" class="close"><img src="<?php echo Yii::app()->themeManager->baseUrl; ?>/images/icons/cross_grey_small.png" title="<?php echo Yii::t('adminglobal', 'Close this notification'); ?>" alt="close" /></a>
					<div><?php echo Yii::app()->user->getFlash('attention'); ?></div>
				</div>
			<?php endif; ?>
			
			<?php if( Yii::app()->user->hasFlash('information') ): ?>
				<div class="notification information png_bg">
					<a href="#" class="close"><img src="<?php echo Yii::app()->themeManager->baseUrl; ?>/images/icons/cross_grey_small.png" title="<?php echo Yii::t('adminglobal', 'Close this notification'); ?>" alt="close" /></a>
					<div><?php echo Yii::app()->user->getFlash('information'); ?></div>
				</div>
			<?php endif; ?>
			
			<?php if( Yii::app()->user->hasFlash('success') ): ?>
				<div class="notification success png_bg">
					<a href="#" class="close"><img src="<?php echo Yii::app()->themeManager->baseUrl; ?>/images/icons/cross_grey_small.png" title="<?php echo Yii::t('adminglobal', 'Close this notification'); ?>" alt="close" /></a>
					<div><?php echo Yii::app()->user->getFlash('success'); ?></div>
				</div>
			<?php endif; ?>			
			<!-- End Notifications -->
			
			<!-- Start .shortcut-buttons-set -->
			<?php //$this->widget('widgets.admin.shortcuticons'); ?>
			<!-- End .shortcut-buttons-set -->
			
			<div class="clear"></div> <!-- End .clear -->
			<!-- Page Head -->
			
			<?php
			
			$this->widget('zii.widgets.CBreadcrumbs', array(
				'id' => 'breadcrumbs',
				'homeLink' => CHtml::link(Yii::t('adminglobal', 'Home'), '/admin'),
			    'links'=>$this->breadcrumbs
			));
			
			
			?>

			<?php echo $content; ?>

			<div class="clear"></div>
			
			<div id="footer"><small><?php echo Yii::t('adminglobal', '{sitename} &copy; 2010 All rights reserved.', array( '{sitename}' => Yii::app()->name )); ?></small></div><!-- End #footer -->
			
		</div> <!-- End #main-content -->
		
	</div></body>
  
</html>
