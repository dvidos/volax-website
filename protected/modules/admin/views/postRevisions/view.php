<?php
	$caption = '';
	if ($model->post == null)
		$caption .= '(αγνωστη ανάρτηση)';
	else if (empty($model->post->title))
		$caption .= 'Ανάρτηση #' . $model->post->id;
	else
		$caption .= $model->post->title;
	$caption .= ', r.' . $model->revision_no;
	
	$this->pageTitle = $caption;
?>

<h1><?php echo $caption; ?></h1>

<table class="bordered">
	<tr><td>Ημ/νία, Ωρα</td><td><?php echo $model->datetime; ?></td></tr>
	<tr><td>Ανάρτηση</td><td><?php echo $model->post == null ? '(καμμία)' : $model->post->title; ?></td></tr>
	<tr><td>Αρ. αναθεώρησης</td><td><?php echo $model->revision_no; ?></td></tr>
	<tr><td>Χρήστης</td><td><?php echo $model->user == null ? '(χρήστης #'.$model->user_id.')' : $model->user->username; ?></td></tr>
</table>


<?php
	// each revision is saved after a post update. 
	// therefore it always has a difference with the "next", 
	// whether this next is another revision or the post.
	

	if ($model->was_deleted)
	{
		// post was deleted at this revision, present last content.
		$title = 'Δεδομένα πριν την διαγραφή';
		$result = array('title'=>$model->title, 'masthead'=>$model->masthead, 'content'=>$model->content);
	}
	else
	{
		$left = array('title'=>$model->title, 'masthead'=>$model->masthead, 'content'=>$model->content);
		$right = array('title'=>'', 'masthead'=>'', 'content'=>'');
		
		$nextRevision = PostRevision::model()->findByAttributes(array('post_id'=>$model->post_id, 'revision_no'=>$model->revision_no + 1));
		if ($nextRevision != null)
		{
			$title = 'Αλλαγές με επόμενη έκδοση';
			$right['title'] = $nextRevision->title;
			$right['masthead'] = $nextRevision->masthead;
			$right['content'] = $nextRevision->content;
		}
		else if ($model->post != null)
		{
			$title = 'Αλλαγές με τρέχουσα έκδοση';
			$right['title'] = $model->post->title;
			$right['masthead'] = $model->post->masthead;
			$right['content'] = $model->post->content;
		}
		
		// now compare
		$result = array(
			'title'=>Yii::app()->textDiff->compare($left['title'], $right['title']),
			'masthead'=>Yii::app()->textDiff->compare($left['masthead'], $right['masthead']),
			'content'=>Yii::app()->textDiff->compare($left['content'], $right['content']),
		);
	}
	
	echo CHtml::tag('h2', array(), $title);
	echo '<table class="bordered">';
	echo '<tr><td>Τίτλος</td><td>' . $result['title'] . '</td></tr>';
	echo '<tr><td>Υπέρτιτλος</td><td>' . $result['masthead'] . '</td></tr>';
	echo '<tr><td>Κείμενο</td><td>' . $result['content'] . '</td></tr>';
	echo '</table>';

?>

