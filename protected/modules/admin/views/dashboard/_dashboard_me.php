




<?php
	function postLink($post)
	{
		$title = ($post->title == '') ? '(#' . $post->id . ', χωρίς τίτλo)' : $post->title;
		return CHtml::link($title, array('/admin/posts/update', 'id'=>$post->id), array('style'=>'font-weight:normal;'));
	}
?>

<table><tr><td width="40%">
	

	<h2>Πρόσφατες αναρτήσεις</h2>
	<p><?php 
		$posts = Post::model()->findAll(array(
			'condition'=>'status='.Post::STATUS_PUBLISHED.' AND author_id = :aid',
			'params'=>array(':aid'=>Yii::app()->user->id),
			'order'=>'update_time DESC',
			'limit'=>15,
		));
		if (count($posts) == 0)
			echo '<p>Δεν βρέθηκαν αναρτήσεις</p>';
		else
		{
			foreach ($posts as $post)
				echo postLink($post) . '<br />';
			echo CHtml::link('Ολες...', array('/admin/posts', 'Post[author_id]'=>Yii::app()->user->user->id, 'Post[status]'=>Post::STATUS_PUBLISHED), array('style'=>'font-weight:normal;')) . '<br />';
		}
	?>
	</p>
	
	
</td><td width="40%">
	
	<h2>Πρόχειρες αναρτήσεις</h2>
	<p><?php 
		$posts = Post::model()->findAll(array(
			'condition'=>'status='.Post::STATUS_DRAFT.' AND author_id = :aid',
			'params'=>array(':aid'=>Yii::app()->user->id),
			'order'=>'update_time DESC',
			'limit'=>15,
		));
		if (count($posts) == 0)
			echo '<p>Δεν βρέθηκαν αναρτήσεις</p>';
		else
		{
			foreach ($posts as $post)
				echo postLink($post) . '<br />';
			echo CHtml::link('Ολες...', array('/admin/posts', 'Post[author_id]'=>Yii::app()->user->user->id, 'Post[status]'=>Post::STATUS_DRAFT), array('style'=>'font-weight:normal;')) . '<br />';
		}
	?></p>
		
</td><td width="1%">&nbsp;</td><td width="20%">
	
	<p><?php echo CHtml::link('Νέα ανάρτηση', array('/admin/posts/create'), array('class'=>'button')); ?></p>
	
	<h2>Αναρτήσεις</h2>
	<table class="compact">
		<tr>
			<td><?php echo CHtml::link('Δημοσιευμένες', array('/admin/posts', 'Post[author_id]'=>Yii::app()->user->user->id, 'Post[status]'=>Post::STATUS_PUBLISHED), array('style'=>'font-weight:normal;')); ?></td>
			<td style="text-align:right;"><?php echo Post::model()->count('author_id='.Yii::app()->user->user->id . ' AND status='.Post::STATUS_PUBLISHED); ?></td>
		</tr><tr>
			<td><?php echo CHtml::link('Πρόχειρες', array('/admin/posts', 'Post[author_id]'=>Yii::app()->user->user->id, 'Post[status]'=>Post::STATUS_DRAFT), array('style'=>'font-weight:normal;')); ?></td>
			<td style="text-align:right;"><?php echo Post::model()->count('author_id='.Yii::app()->user->user->id . ' AND status='.Post::STATUS_DRAFT); ?></td>
		</tr><tr>
			<td><?php echo CHtml::link('Αρχειοθετημένες', array('/admin/posts', 'Post[author_id]'=>Yii::app()->user->user->id, 'Post[status]'=>Post::STATUS_ARCHIVED), array('style'=>'font-weight:normal;')); ?></td>
			<td style="text-align:right;"><?php echo Post::model()->count('author_id='.Yii::app()->user->user->id . ' AND status='.Post::STATUS_ARCHIVED); ?></td>
		</tr>
	</table>
	
	<h2>Οδηγίες</h2>
	<?php 
		echo CHtml::link('Για συντάκτες', array('dashboard/viewPage', 'url_keyword'=>'editorNotes'), array('style'=>'font-weight:normal;')) . '<br />';
		echo CHtml::link('Μακροεντολές', array('dashboard/viewPage', 'url_keyword'=>'shortcodes'), array('style'=>'font-weight:normal;')) . '<br />';
	?>
	
</td></tr>
</table>


