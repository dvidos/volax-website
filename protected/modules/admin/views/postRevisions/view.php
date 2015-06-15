<?php
	$caption = '';
	if ($model->post == null)
		$caption .= '(αγνωστη ανάρτηση)';
	else if (empty($model->post->title))
		$caption .= 'Ανάρτηση #' . $model->post->id;
	else
		$caption .= $model->post->title;
	$caption .= '/' . $model->revision_no;
	
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

	function presentDifferences($title, $revCaption, $username, $datetime, $diffs)
	{
		$html = CHtml::tag('h2', array(), $title);
		$html .= CHtml::tag('p', array(), $revCaption . ', ' . $username . ', ' . $datetime);
		
		if (count($diffs) == 0)
		{
			$html .= CHtml::tag('p', array(), 'Δεν βρέθηκαν διαφορές');
			return $html;
		}
		
		$html .= '<table class="bordered">';
		$html .= '<tr><td>Πεδίο</td><td>Πριν</td><td>Μετά</td></tr>';
		foreach ($diffs as $diff)
		{
			$html .= '<tr>' . 
				'<td>' . CHtml::encode($diff['caption']) . '</td>' .
				'<td>' . $diff['old'] . '</td>' .
				'<td>' . $diff['new'] . '</td>' .
			'</tr>';
		}
		$html .= '</table>';
		
		return $html;
	}
	
	// present previous if exists.
	if ($model->revision_no > 1)
	{
		$rev = PostRevision::model()->findByAttributes(array('post_id'=>$model->post_id, 'revision_no'=>$model->revision_no - 1));
		if ($rev != null)
		{
			echo presentDifferences(
				'Διαφορές με προηγούμενη αναθεώρηση',
				'Αρ. ' . $rev->revision_no, 
				$rev->user == null ? '(χρήστης #' . $rev->user_id . ')' : $rev->user->username,
				$rev->datetime,
				$rev->getDifferencesWithRevision($model)
			);
		}
	}
	
	// see if there is a next one
	$rev = PostRevision::model()->findByAttributes(array('post_id'=>$model->post_id, 'revision_no'=>$model->revision_no + 1));
	if ($rev != null)
	{
		echo presentDifferences(
			'Διαφορές με επόμενη αναθεώρηση',
			'Αρ. ' . $rev->revision_no, 
			$rev->user == null ? '(χρήστης #' . $rev->user_id . ')' : $rev->user->username,
			$rev->datetime,
			$model->getDifferencesWithRevision($rev)
		);
	}
	else if ($model->post != null)
	{
		echo presentDifferences(
			'Διαφορές με σημερινή έκδοση',
			$model->post == null ? '(αρθρο #' . $model->post_id . ')' : $model->post->title, 
			$model->post->author == null ? '(χρήστης #' . $model->post->author_id . ')' : $model->post->author->username,
			date('d-m-Y H:i', $model->post->update_time),
			$model->getDifferencesWithPost($model->post)
		);
	}
	




