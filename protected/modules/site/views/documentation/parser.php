<?php
	Yii::app()->clientScript->registerCssFile( Yii::app()->themeManager->baseUrl . '/style/highlight.css', 'screen' );
?>

<?php echo $content; ?>