<div class="wrap">
	<div id="icon-plugins" class="icon32"></div>
	<h2>Volax v4 Importer</h2>

	<div class="volax-importer-page">
		<div class="content alignleft">
			
			<h2>On volax V4 database</h2>
			<p>
				<ul>
					<li>Posts: <?php 
						$info = $this->v4->get_posts_info();
						echo "{$info[0]} (ids {$info[1]} to {$info[2]})";
					?> (ids maintained)</li>
					<li>Pages: <?php 
						$info = $this->v4->get_pages_info();
						echo "{$info[0]} (ids {$info[1]} to {$info[2]})";
					?> (ids maintained)</li>
					<li>Categories: <?php 
						$info = $this->v4->get_categories_info();
						echo "{$info[0]} (ids {$info[1]} to {$info[2]})";
					?> (ids maintained)</li>
					<li>Tags: <?php 
						$info = $this->v4->get_tags_info();
						echo "{$info[0]} (ids {$info[1]} to {$info[2]})";
					?> (ids maintained)</li>
					<li>Media contents: <?php 
						$contents = $this->v4->get_v4_media_contents();
						echo implode(", ", $contents);
					?></li>
					<li><?php
							$data = $this->v4->load_user_mappings();
							$non_mapped = $this->v4->get_non_mapped_v4_usernames();
							if (count($non_mapped) > 0) {
								echo "<b style='color:#900;'>V4 users not mapped to WP</b>: " . implode(", ", $non_mapped);
								echo "<br>Please fix by editing \$aliases in VolaxV4.php.";
							} else {
								echo "All " . count($this->v4->get_user_name_mappings()) . " v4 users mapped to wp users";
							}
							//echo "<pre>Names: " . var_export($this->v4->get_user_name_mappings(), true) . "</pre>";
							//echo "<pre>IDs: " . var_export($this->v4->get_user_id_mappings(), true) . "</pre>";
					?></li>
				</ul>
			</p>
			
			<h2>On wordpress database</h2>
			<p>
				<ul>
					<li>Posts: <?php 
						$count = wp_count_posts('post'); 
						echo $count->publish + $count->draft;
					?></li>
					<li>Pages: <?php 
						$count = wp_count_posts('page'); 
						echo $count->publish + $count->draft;
					?></li></li>
					<li>Categories: <?php
						$count = wp_count_terms('category');
						echo (int)$count;
					?></li>
					<li>Tags: <?php
						$count = wp_count_terms('post_tag');
						echo (int)$count;
					?></li>
					<li>Media contents: <?php 
						$contents = $this->v4->get_wp_media_contents();
						echo implode(", ", $contents);
					?></li>
				</ul>
			</p>
			
			<p>&nbsp;</p>
			
			<h2>Import functionality</h2>
			<div>
				<form id="vi-ajax-import-form" action="options.php" method="POST">
					<table>
						<tr><td>What to import</td><td>
							<select id="what" name="what">
								<option value="posts">Posts</option>
								<option value="pages">Pages</option>
								<option value="categories">Categories</option>
								<option value="tags">Tags</option>
								<option value="media">Media</option>
							</select>
						</td></tr>
						<tr><td>Identities</td><td>
							<input id="identities" name="identities" size="10">
							(can be *=all, comma separated, range with a dash, e.g. 1097, 1260)
						</td></tr>
						<tr><td>&nbsp;</td><td>
							<input type="checkbox" id="skip-dry-run" name="skip-dry-run">
							Skip dry-run, enable writing
						</td></tr>
						<tr><td>&nbsp;</td><td>
							<input type="checkbox" id="overwrite" name="overwrite">
							Overwrite if already existing
						</td></tr>
						<tr><td colspan="2">
							<input type="submit" value="Import" />
							<?php $loader_img = plugin_dir_url(dirname(__FILE__)) . "images/ajax-loader.gif"  ?>
							<img id="ajax-loader" src="<?=$loader_img?>" />
						</td></tr>
					</table>
				</form>
				<div id="ajax-response">response</div>
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
