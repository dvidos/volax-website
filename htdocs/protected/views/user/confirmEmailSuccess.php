<?php
	$this->pageTitle='Επιβεβαίωση διεύθυνσης email';
?>

<h1>Επιβεβαίωση διεύθυνσης email</h1>

<p>Η διεύθυνσή σας επιβεβαιώθηκε με επιτυχία!</p>
<p>Σας ευχαριστούμε για την συνεργασία σας!</p>

<p>
	<?php echo CHtml::link('Αρχική σελίδα', Yii::app()->homeUrl, array('class'=>'button blue')); ?>
	&nbsp;
	<?php echo CHtml::link('Ο λογαριασμός μου', array('/user/myAccount'), array('class'=>'button blue')); ?>
</p>


