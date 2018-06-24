<?php
$this->pageTitle='Σφάλμα';
$this->breadcrumbs=array(
	'Σφάλμα',
);
?>

<h2>Σφάλμα <?php echo $code; ?></h2>

<div class="error">
<?php echo CHtml::encode($message); ?>
</div>