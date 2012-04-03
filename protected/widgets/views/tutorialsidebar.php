<?php

$options = array();

if( Yii::app()->user->checkAccess('op_tutorials_addtutorials') )
{
	$options[ Yii::t('tutorials', 'Add Tutorial') ] = array('tutorials/addtutorial');
}

if( Yii::app()->user->checkAccess('op_tutorials_manage') )
{
	$pending = Tutorials::model()->count('status=0');
	$options[ Yii::t('tutorials', '{count} Pending Tutorials', array('{count}'=>$pending)) ] = array('tutorials/showpending');
}

if( Yii::app()->user->id )
{
	$options[ Yii::t('tutorials', 'My Tutorials') ] = array('tutorials/showmytutorials');
}

?>


<div id="nav">
	<div class="boxnavnoborder">	
		<ul class='menunav'>
			<h4><?php echo Yii::t('tutorials', 'Categories'); ?></h4>
		<?php foreach( TutorialsCats::model()->getCatsForMember(Yii::app()->language) as $category ): ?>
			<li><?php echo CHtml::link( $category->title, array( '/tutorials/category/' . $category->alias, 'lang' => false ) ); ?> - ( <?php echo $category->count; ?> )</li>
		<?php endforeach; ?>	
		</ul>
		
		<?php if( count($options) ): ?>
		<ul class='menunav'>
			<h4><?php echo Yii::t('tutorials', 'Options'); ?></h4>
		<?php foreach($options as $key => $value): ?>
			<li><?php echo CHtml::link( $key, $value ); ?></li>
		<?php endforeach; ?>	
		</ul>
		<?php endif; ?>
		
	</div>
</div>