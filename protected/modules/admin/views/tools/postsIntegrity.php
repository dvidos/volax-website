<h1>Ελεγχος ακεραιότητας</h1>

<?php
	if (count($errors) == 0)
	{
		echo CHtml::tag('p', array(), 'Συγχαρητήρια, δεν βρέθηκαν σφάλματα!!!');
	}
	else
	{
		
		echo '<table class="bordered">';
		echo '<tr>';
		echo CHtml::tag('th', array(), 'Τύπος');
		echo CHtml::tag('th', array(), 'Τίτλος');
		echo CHtml::tag('th', array(), 'Κατάσταση');
		echo CHtml::tag('th', array(), 'Σφάλμα');
		echo '</tr>';
		foreach ($errors as $err)
		{
			echo '<tr>';
			echo CHtml::tag('td', array(), CHtml::encode($err['type']));
			echo CHtml::tag('td', array(), CHtml::link(CHtml::encode($err['title']), $err['url']));
			echo CHtml::tag('td', array(), CHtml::encode($err['status']));
			echo CHtml::tag('td', array(), CHtml::encode($err['error']));
			echo '</tr>' . "\r\n";
		}
		echo '</table>';
	}
	
?>

