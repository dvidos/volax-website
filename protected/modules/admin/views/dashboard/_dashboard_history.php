
<?php
	function postLink($post)
	{
		$title = ($post->title == '') ? '(#' . $post->id . ', χωρίς τίτλo)' : $post->title;
		return CHtml::link($title, array('/admin/posts/update', 'id'=>$post->id), array('title'=>'Διόρθωση', 'style'=>'font-weight:normal; '));
	}
?>

<h2>Πρόσφατη δραστηριότητα</h2>
<table class="compact">
<?php
	$revisions = PostRevision::model()->findAll(array(
		'select'=>'id, post_id, `datetime`, user_id, was_created, was_deleted',
		'condition'=>'',
		'order'=>'`datetime` DESC',
		'limit'=>50,
	));
	$presented = array();
	foreach ($revisions as $revision)
	{
		$key = $revision->user_id . '-' . $revision->post_id;
		if (in_array($key, $presented))
			continue;
		$presented[] = $key;
		
		echo '<tr>';
		echo '<td style="white-space: nowrap; padding-right: 2em;">' . $revision->friendlyDatetime . '</td>';
		echo '<td style="white-space: nowrap; padding-right: 2em;">' . str_replace(' ', '&nbsp;', User::tryGetFullName($revision->user_id)) . '</td>';
		echo '<td style="white-space: nowrap; padding-right: 2em;">' . $revision->friendlyAction . '</td>';
		echo '<td>' . ($revision->post == null ? '(ανάρτηση #'.$revision->post_id.')' : postLink($revision->post)) . '</td>';
		echo '</tr>';
	}
?>
</table>



