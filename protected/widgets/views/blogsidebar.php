<?php

$options = array();

if( Yii::app()->user->checkAccess('op_blog_addposts') )
{
	$options[ Yii::t('blog', 'Add Post') ] = array('blog/addpost');
}

if( Yii::app()->user->checkAccess('op_blog_manage') )
{
	$pending = Blog::model()->count('status=0');
	$options[ Yii::t('blog', '{count} Pending Posts', array('{count}'=>$pending)) ] = array('blog/showpending');
}

if( Yii::app()->user->id )
{
	$options[ Yii::t('blog', 'My Posts') ] = array('blog/showmyposts');
}

?>


<div id="nav">
	<div class="boxnavnoborder">	
		<ul class='menunav'>
			<h4><?php echo Yii::t('blog', 'Categories'); ?></h4>
		<?php foreach( BlogCats::model()->getCatsForMember(Yii::app()->language) as $category ): ?>
			<li><?php echo CHtml::link( $category->title, array( '/blog/category/' . $category->alias, 'lang' => false ) ); ?> - ( <?php echo $category->count; ?> )</li>
		<?php endforeach; ?>	
		</ul>
		
		<?php if( count($options) ): ?>
		<ul class='menunav'>
			<h4><?php echo Yii::t('blog', 'Options'); ?></h4>
		<?php foreach($options as $key => $value): ?>
			<li><?php echo CHtml::link( $key, $value ); ?></li>
		<?php endforeach; ?>	
		</ul>
		<?php endif; ?>
		
	</div>
</div>