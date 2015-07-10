<?php
	$this->pageTitle='Σφάλμα επιβεβαίωσης';
?>

<h1>Σφάλμα επιβεβαίωσης</h1>

<p>Εσφαλμένος κωδικός επιβεβαίωσης (token) για την διεύθυνση <?php echo CHtml::encode($email); ?></p>

<p>Παρακαλούμε επικοινωνήστε με τον διαχειριστή του ιστότοπου στο 
	<?php echo CHtml::link(Yii::app()->params['adminEmail'], 'mailto:' . Yii::app()->params['adminEmail']); ?>.
</p>
