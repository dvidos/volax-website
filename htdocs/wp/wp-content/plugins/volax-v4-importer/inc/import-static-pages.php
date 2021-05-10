<div class="wrap">
	<div id="icon-edit" class="icon32 icon32-base-template"><br></div>
	<h2>Εισαγωγή στατικών σελίδων</h2>
	
	<p>Παράγραφος</p>
	
	<form id="volax-importer-ajax-form" action="options.php" method="POST">
		<input id="submit" type="submit" value="Αποθήκευση" />
		
		<?php $loader_img = plugin_dir_url(dirname(__FILE__)) . "images/ajax-loader.gif"  ?>
		<img id="ajax-loader" style="display: none;" src="<?=$loader_img?>" />
		
	</form>
	
	<!-- ajax response will be placed in the following placeholder -->
	<p id="ajax-response"></p>
	
</div>
