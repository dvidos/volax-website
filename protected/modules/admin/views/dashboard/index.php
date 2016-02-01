<?php
	$this->pageTitle = 'Διαχείριση'; 
?>

<?php echo $this->renderPartial('_tabs'); ?>
<div class="tabs-page">
	<?php echo $this->renderPartial('_dashboard_'.$tab); ?>
</div>




