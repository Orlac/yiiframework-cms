<div id="cse" style="width: 100%;"><?php echo Yii::t('search', 'Loading...'); ?></div>
<script src="http://www.google.com/jsapi" type="text/javascript"></script>
<script type="text/javascript">
  google.load('search', '1', {language : '<?php echo Yii::app()->language; ?>'});
  google.setOnLoadCallback(function() {
    var customSearchControl = new google.search.CustomSearchControl('003059621568850454275:stplygqsi9u');
    customSearchControl.setResultSetSize(google.search.Search.FILTERED_CSE_RESULTSET);
    var options = new google.search.DrawOptions();
    options.setAutoComplete(true);
    customSearchControl.draw('cse', options);
  }, true);
</script>
<link rel="stylesheet" href="http://www.google.com/cse/style/look/default.css" type="text/css" />


