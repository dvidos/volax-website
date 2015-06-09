

<table>
<tr><td width="33%" valign="top">

	<h2>Αναρτήσεις</h2>
	<p>Σύνολο <?php echo Post::model()->count('author_id='.Yii::app()->user->id); ?>,
	δημοσιευμένες <?php echo Post::model()->count('status<>'.Post::STATUS_DRAFT.' AND author_id='.Yii::app()->user->id); ?>,
	πρόχειρες <?php echo Post::model()->count('status='.Post::STATUS_DRAFT.' AND author_id='.Yii::app()->user->id); ?></p>

	<p><?php echo CHtml::link('Νέα ανάρτηση', array('/author/posts/create'), array(
		'class'=>'button', 
		'style'=>'text-align: center;'
	)); ?></p>

	
	<h2>Οδηγίες</h2>
	<?php 
		$this->widget('zii.widgets.CMenu', array(
			'items'=>array(
				array('label'=>'Για συντάκτες', 'url'=>array('dashboard/viewPage', 'url_keyword'=>'editorNotes')),
			),
		));
	?>
	
	
</td><td width="33%" valign="top">

	<h2>Πρόχειρες</h2>
	<?php 
		$posts = Post::model()->findAll(array(
			'condition'=>'status='.Post::STATUS_DRAFT.' AND author_id='.Yii::app()->user->id,
			'order'=>'update_time DESC',
			'limit'=>7,
		));
		echo '<ul>';
		foreach ($posts as $post)
		{
			$title = ($post->title == '') ? '#' . $post->id . ' (χωρίς τίτλo)' : $post->title;
			echo '<li>' . CHtml::link($title, array('/author/posts/update', 'id'=>$post->id)) . '</li>';
		}
		echo '</ul>';
	?>

</td><td width="33%" valign="top">

	<h2>Πρόσφατες</h2>
	<?php 
		$posts = Post::model()->findAll(array(
			'condition'=>'status<>'.Post::STATUS_DRAFT.' AND author_id='.Yii::app()->user->id,
			'order'=>'update_time DESC',
			'limit'=>7,
		));
		echo '<ul>';
		foreach ($posts as $post)
		{
			$title = ($post->title == '') ? '#' . $post->id . ' (χωρίς τίτλo)' : $post->title;
			echo '<li>' . CHtml::link($title, array('/author/posts/update', 'id'=>$post->id)) . '</li>';
		}
		echo '</ul>';
	?>

</td></tr>
</table>



