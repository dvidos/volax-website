<?php
	$this->pageTitle = CHtml::encode($model->title); 
?>

<h1><?php echo $this->pageTitle; ?></h1>

<?php echo $this->renderPartial('_tabs', array('model'=>$model)); ?>

<div class="tabs-page">
<?php
	
	if ($model->revisionCount == 0)
	{
		echo '<p>Δεν έχουν γίνει διορθώσεις από τον Ιούνιο του 2015 που ξεκίνησε να τηρείται ιστορικό</p>';
	}
	else
	{
		echo '<table class="bordered">';
		echo '<tr><th>Α/Α</th><th>Ημ/νία</th><th>Χρήστης</th><th>Ενέργεια</th><th>Σχόλιο</th><th>Αλλαγές</th></tr>';
		foreach ($model->revisions as $revision)
		{
			$changes = CHtml::link('Αλλαγές', array('/admin/posts/history', 'id'=>$model->id, 'revision_no'=>$revision->revision_no));
			
			echo '<tr>';
			echo '<td>' . $revision->revision_no . '</td>';
			echo '<td>' . $revision->friendlyDatetime . '</td>';
			echo '<td>' . ($revision->user == null ? "None" : $revision->user->username) . '</td>';
			echo '<td>' . $revision->friendlyAction . '</td>';
			echo '<td>' . CHtml::encode($revision->comment) . '</td>';
			echo '<td>' . $changes . '</td>';
			echo '</tr>';
		}
		echo '</table>';
	}
	
?>
</div>


