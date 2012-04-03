<table class="tblcompare">
	<thead>
		<tr>
			<th scope="col" style='width: 10%;'>&nbsp;</th>
			<th scope="col" style='width: 40%;'><?php echo $sort->link('username', Yii::t('users', 'Username'), array( 'class' => 'tiptop', 'title' => Yii::t('users', 'Sort list by username') ) ); ?></th>
			<th scope="col" style='width: 20%;'><?php echo $sort->link('joined', Yii::t('users', 'Joined'), array( 'class' => 'tiptop', 'title' => Yii::t('users', 'Sort list by joined date') ) ); ?></th>
			<th scope="col" style='width: 20%;'><?php echo $sort->link('role', Yii::t('users', 'Role'), array( 'class' => 'tiptop', 'title' => Yii::t('users', 'Sort list by role') ) ); ?></th>
		</tr>
	</thead>
	<tfoot>
	    <tr>
	        <td colspan='4' style='text-align:left;'><?php $this->widget('CLinkPager', array('pages'=>$pages)); ?></td>
	    </tr>
	</tfoot>
	<tbody>
		<?php if( is_array($rows) && count($rows) ): ?>
			<?php foreach($rows as $row): ?>
			<tr>
	        	<td><?php $this->widget('ext.VGGravatarWidget', array( 'size' => 40, 'email'=>$row->email,'htmlOptions'=>array('class'=>'imgavatar tiptop', 'title' => $row->username, 'alt'=>'avatar'))); ?></td>
				<td><?php echo Members::model()->getLink( $row->username, $row->id, $row->seoname ); ?></td>
				<td><?php echo Yii::app()->dateFormatter->formatDateTime($row->joined, 'short', 'short'); ?></td>
				<td><?php echo Yii::t('users', ucfirst($row->role) ); ?></td>
	    	</tr>
			<?php endforeach; ?>
		<?php endif; ?>
	</tbody>
</table>