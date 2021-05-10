<div class="wrap">
	<div id="icon-edit" class="icon32 icon32-base-template"><br></div>
	<h2>Εισαγωγή κατηγοριών</h2>
	
	<p>Παράγραφος</p>
	
	<form id="volax-importer-ajax-form" action="options.php" method="POST">
		Φόρμα που πάει στο options.php.
		
		<?php settings_fields( 'dx_setting' ) ?>
		<?php do_settings_sections( 'dx-plugin-base' ) ?>
			
		<input type="submit" value="Αποθήκευση" />
	</form> <!-- end of #dxtemplate-form -->
</div>
