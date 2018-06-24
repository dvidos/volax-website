<?php $this->beginContent('/layouts/main'); ?>
	<?php 
		// we have to have a controller layout file, different from the main layout declared in the config.
		// i don't remember why, but when i merged them into one file, something did not work...
		echo $content; 
	?>
<?php $this->endContent(); ?>