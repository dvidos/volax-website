<?php
	$this->pageTitle='Επιβεβαίωση λογαριασμού';
?>

<h1>Επιβεβαίωση λογαριασμού</h1>

<p>Μόλις αποστείλαμε ένα ενημερωτικό μήνυμα στον λογαριασμό σας (<?php echo $user->email; ?>).</p>

<p>Για να ολοκληρωθεί η εγγραφή σας, παρακαλούμε ακολουθήστε τις οδηγίες που αναφέρονται στο μήνυμα.</p>

<p>
	<?php echo CHtml::link('Αρχική σελίδα', Yii::app()->homeUrl, array('class'=>'button blue')); ?>
</p>

