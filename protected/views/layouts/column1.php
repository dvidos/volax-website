<?php $this->beginContent('/layouts/main'); ?>

	<?php $this->widget('zii.widgets.CBreadcrumbs', array(
		'links'=>$this->breadcrumbs,
	)); ?><!-- breadcrumbs -->


	<div id="content">
		<?php echo $content; ?>
	</div><!-- content -->
	
<?php $this->endContent(); ?>