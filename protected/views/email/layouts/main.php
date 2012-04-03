<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd$
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="<?php echo Yii::app()->language; ?>">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?php echo Yii::app()->charset; ?>">
</head>
<body <?php if( Yii::app()->locale->getOrientation() == 'rtl' ): ?>style='direction: rtl; text-align: right;'<?php endif; ?>>
<?php echo $content; ?>
</body>
</html>