<div class="wrap">
	<div id="icon-plugins" class="icon32"></div>
	<h2>Volax v4 Importer</h2>

	<div class="dx-help-page">
		<div class="content alignleft">
			<h2 class='page-welcome'>Welcome to <span>Volax Importer!</span></h2>

			<p>
				<ul>
					<li>Posts: <?php 
						$info = $this->v4->get_posts_info();
						echo "{$info[0]} (id {$info[1]} to {$info[2]})";
					?></li>
					<li>Pages: <?php 
						$info = $this->v4->get_pages_info();
						echo "{$info[0]} (id {$info[1]} to {$info[2]})";
					?></li>
					<li>Categories: <?php 
						$info = $this->v4->get_categories_info();
						echo "{$info[0]} (id {$info[1]} to {$info[2]})";
					?></li>
				</ul>
				<pre>
					<?php
						$post = $this->v4->load_page(1);
						echo htmlspecialchars(var_export($post, true));
					?>
				</pre>
			</p>
			<div id="dx-help-content">
					<p>This form goes to options.php</p>
					
					<form id="dx-plugin-base-form" action="options.php" method="POST">
						<?php settings_fields( 'vi_setting' ) ?>
						<?php do_settings_sections( 'vi-plugin-base' ) ?>
						<input type="submit" value="Αποθήκευση" />
					</form>
			</div>

			<footer class='dx-footer'>
			</footer>

		</div>
		<div class="sidebar alignright">
			<h2>Side bar</h2>
			<p>This plugin is built by Dimitri Vidos .</p>
		</div>
	</div>
	
</div>
