<?php
	$this->pageTitle = CHtml::encode($model->title); 
?>

<h1><?php echo $this->pageTitle; ?></h1>

<?php echo $this->renderPartial('_tabs', array('model'=>$model)); ?>

<div class="tabs-page">
	<table class="bordered">
		<tr><td width="20%"><b>Α/Α αλλαγής</b></td><td><?php 
			if (($p = $revision->findNextRevision(false)) != null)
			{
				echo CHtml::link('&lt;&lt; Προηγούμενη', array('history', 'id'=>$revision->post_id, 'revision_no'=>$p->revision_no));
				echo ' &nbsp; &nbsp; &nbsp; ';
			}
			echo $revision->revision_no; 
			if (($n = $revision->findNextRevision(true)) != null)
			{
				echo ' &nbsp; &nbsp; &nbsp; ';
				echo CHtml::link('Επόμενη &gt;&gt;', array('history', 'id'=>$revision->post_id, 'revision_no'=>$n->revision_no));
			}
		?></td></tr>
		<tr><td width="20%"><b>Ημ/νία</b></td><td><?php echo $revision->friendlyDatetime; ?></td></tr>
		<tr><td width="20%"><b>Χρήστης</b></td><td><?php echo $revision->user == null ? '(χρήστης #'.$revision->user_id.')' : $revision->user->username; ?></td></tr>
		<tr><td width="20%"><b>Ενέργεια</b></td><td><?php echo $revision->friendlyAction; ?></td></tr>
		<tr><td width="20%"><b>Σχόλιο</b></td><td><?php echo CHtml::encode($revision->comment); ?></td></tr>
	</table>
	
	<?php
		if ($revision->was_created || $revision->was_deleted)
		{
			$title = 'Περιεχόμενο κατά την ' . ($revision->was_created ? 'Δημιουργία' : 'Διαγραφή');
			$changes = array(
				'title'=>$revision->title, 
				'masthead'=>$revision->masthead, 
				'content'=>$revision->content, 
				'category_id'=>$revision->category_id, 
				'tags'=>$revision->tags,
			);
		}
		else
		{
			$title = 'Αλλαγές που έγιναν';
			$changes = array('title'=>'', 'masthead'=>'', 'content'=>'', 'category_id'=>'', 'tags'=>'');
			$target = null;
			
			// if there is a next revision, compare to it. else, compare to actual current post.
			if (($nextRev = $revision->findNextRevision(true)) != null)
				$target = $nextRev;
			else if ($revision->post != null)
				$target = $revision->post;
			
			$changes['title'] = (strcmp($revision->title, $target->title) == 0) ? '-' : Yii::app()->textDiff->compare($revision->title, $target->title);
			$changes['masthead'] = (strcmp($revision->masthead, $target->masthead) == 0) ? '-' : Yii::app()->textDiff->compare($revision->masthead, $target->masthead);
			$changes['content'] = (strcmp($revision->content, $target->content) == 0) ? '-' : Yii::app()->textDiff->compare($revision->content, $target->content);
			$changes['category_id'] = ($revision->category_id == $target->category_id) ? '-' : Category::tryGetTitle($revision->category_id) . ' --&gt; ' . Category::tryGetTitle($target->category_id);
			$changes['tags'] = (strcmp($revision->tags, $target->tags) == 0) ? '-' : Yii::app()->textDiff->compare($revision->tags, $target->tags);
		}
		
		echo CHtml::tag('h2', array(), CHtml::encode($title)); 
		echo '<table class="bordered">';
		foreach ($changes as $key => $change)
		{
			echo '<tr>';
			echo '<td width="20%"><b>' . CHtml::encode($revision->getAttributeLabel($key)) . '</b></td>';
			echo '<td width="80%">' . $change . '</td>';
			echo '</tr>';
		}
		echo '</table>';
	?>
</div>


