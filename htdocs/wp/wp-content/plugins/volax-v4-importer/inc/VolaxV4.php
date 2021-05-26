<?php

class VolaxV4 {
	
	// have a way to tell dev from prod.
	private $is_dev;
	
	// instance of global WPDB database access object
	private $wpdb;
	
	// keys: v4 user names, values: wp user names
	private $user_name_mappings = [];
	
	// keys: v4 user ids, values: wp user ids
	private $user_id_mappings = [];
	
	// see clear_log(), log(), get_log_entries()
	private $log_entries = [];
	
	
	public function __construct() {
		global $wpdb;
		$this->wpdb = $wpdb;
		
		$this->is_dev = $_SERVER['HTTP_HOST'] == 'localhost';
	}
	
	/*
	 * return array of [ count, min_id, max_id ]
	 */
	public function get_posts_info() {
		$count = intVal($this->wpdb->get_var("SELECT count(*) FROM v4_post"));
		$min = intVal($this->wpdb->get_var("SELECT min(id) FROM v4_post"));
		$max = intVal($this->wpdb->get_var("SELECT max(id) FROM v4_post"));
		
		return [$count, $min, $max];
	}
	
	/*
	 * return array of [ count, min_id, max_id ]
	 */
	public function get_pages_info() {
		$count = intVal($this->wpdb->get_var("SELECT count(*) FROM v4_pages"));
		$min = intVal($this->wpdb->get_var("SELECT min(id) FROM v4_pages"));
		$max = intVal($this->wpdb->get_var("SELECT max(id) FROM v4_pages"));
		
		return [$count, $min, $max];
	}
	
	/*
	 * return array of [ count, min_id, max_id ]
	 */
	public function get_categories_info() {
		$count = intVal($this->wpdb->get_var("SELECT count(*) FROM v4_category"));
		$min = intVal($this->wpdb->get_var("SELECT min(id) FROM v4_category"));
		$max = intVal($this->wpdb->get_var("SELECT max(id) FROM v4_category"));
		
		return [$count, $min, $max];
	}
	
	/*
	 * return array of [ count, min_id, max_id ]
	 */
	public function get_tags_info() {
		$count = intVal($this->wpdb->get_var("SELECT count(*) FROM v4_tag"));
		$min = intVal($this->wpdb->get_var("SELECT min(id) FROM v4_tag"));
		$max = intVal($this->wpdb->get_var("SELECT max(id) FROM v4_tag"));
		
		return [$count, $min, $max];
	}
	
	/**
	 * Returns object, null if not found
	 *
	 * Post
	 * -------------------------
	 * 'id' => '1260',
	 * 'title' => 'Χρόνια Πολλά! ',
	 * 'masthead' => '',
	 * 'category_id' => '124',
	 * 'content' => '<p class="x-style-alt-color"> ... </p></p>',
	 * 'processed_content' => '',
	 * 'layout' => '0',
	 * 'desired_width' => '3',
	 * 'tags' => 'επέτειος, διαδίκτυο, hello, αναμνήσεις',
	 * 'status' => '2',
	 * 'in_home_page' => '1',
	 * 'sticky' => '0',
	 * 'allow_comments' => '1',
	 * 'create_time' => '1530007452',
	 * 'update_time' => '1530560472',
	 * 'sort_time' => '0',
	 * 'discussion' => '',
	 * 'author_id' => '1',
	 * 'comments' => []
	 * 
	 * Comments (array)
	 * -------------------------
	 * 'id' => '599',
	 * 'content' => 'Πολλά μπράβο στην ομάδα Βωλάξ',
	 * 'status' => '2',
	 * 'create_time' => '1530496438',
	 * 'author' => 'Δημήτρης Βίδος',
	 * 'email' => 'dvidos@gmail.com',
	 * 'url' => '',
	 * 'post_id' => '1260',
	 */
	public function load_post($id) {
		$sql = "SELECT * FROM v4_post WHERE id = " . intVal($id);
		$post = $this->wpdb->get_row($sql);
		if (!$post)
			return $post;
			
		$sql = "SELECT * FROM v4_comment WHERE post_id = " . intVal($id);
		$comments = $this->wpdb->get_results($sql);
		$post->comments = $comments;
		
		return $post;
	}
	
	/**
	 * Returns object
	 * 
	 * Category
	 * -------------------------
	 * 'id' => '29',
	 * 'parent_id' => '4',
	 * 'title' => 'ΠΗΓΑΔΙ-ΠΟΤΙΣΤΡΕΣ',
	 * 'content' => '',
	 * 'layout' => '1',
	 * 'status' => '2',
	 * 'discussion' => '',
	 * 'create_time' => '1389030265',
	 * 'update_time' => '1434914503',
	 * 'view_order' => '40',	
	 */
	public function load_category($id) {
		$sql = "SELECT * FROM v4_category WHERE id = " . intVal($id);
		$category = $this->wpdb->get_row($sql);
		
		return $category;
	}
	
	/**
	 * Returns array of objects, see load_category() for properties
	 */
	public function load_categories_of($parent_id) {
		$sql = "SELECT * FROM v4_category WHERE parent_id = " . intVal($parent_id);
		$list = $this->wpdb->get_results($sql);
		
		return $list;
	}
	
	/**
	 * Returns object
	 * 
	 * Page:
	 * -------------------------
	 * 'id' => '1',
	 * 'url_keyword' => 'whoweare',
	 * 'title' => 'Ποιοί είμαστε',
	 * 'content' => '<p>Tα όνειρα, δυστυχώς, δεν κρατούν πολύ.</p>',
	 */
	public function load_page($id) {
		$sql = "SELECT * FROM v4_pages WHERE id = " . intVal($id);
		$page = $this->wpdb->get_row($sql);
		
		return $page;
	}
	
	/**
	 * Returns array of objects, all 1K tags of them
	 * 
	 * Each tag:
	 * -------------------------
	 * 'id' => '1238',
	 * 'name' => 'blog',
	 * 'frequency' => '61',
	 */
	public function load_tags() {
		$page = $this->wpdb->get_results("SELECT * FROM v4_tag");
		
		return $page;
	}
	
	/**
	 * Returns array of objects
	 * 
	 * Each tag:
	 * -------------------------
	 * 'id' => '1',
	 * 'username' => 'team',
	 * 'password' => '$2a$10$BTm9pBGT1sYf0hgOAyuHMejyvHIt9dhUF7bhLLrY22f.tC0TKAaEq',
	 * 'email' => 'dvidos@gmail.com',
	 * 'fullname' => 'Ομάδα volax.gr',
	 * 'initials' => 'ΟΜΔ',
	 * 'is_author' => '1',
	 * 'is_admin' => '0',
	 * 'profile' => '',
	 * 'image' => '',
	 * 'registered_at' => '2015-07-06 00:00:00',
	 * 'last_login_at' => '0000-00-00 00:00:00',
	 * 'email_confirmed' => '1',
	 * 'want_newsletter' => '0',
	 * 'is_banned' => '0',
	 */
	public function load_users() {
		$page = $this->wpdb->get_results("SELECT * FROM wp_users");
		
		return $page;
	}
	
	public function get_v4_media_contents() {
		$dir = __FILE__;
		$dir = dirname(dirname(dirname(dirname(dirname(dirname($dir)))))) . "/uploads";
		$entries = glob($dir . "/*");
		$entries = array_map(function ($p) { return basename($p); }, $entries);
		return $entries;
	}
	
	public function get_wp_media_contents() {
		$dir = __FILE__;
		$dir = dirname(dirname(dirname(dirname(dirname($dir))))) . "/wp-content/uploads";
		$entries = glob($dir . "/*");
		$entries = array_map(function ($p) { return basename($p); }, $entries);
		return $entries;
	}
	
	/**
	 * Populate the two arrays: $user_name_mappings and $user_id_mappings
	 */
	public function load_user_mappings() {
		/*
			Finding user mappings. Use the biographical data in wordpress to map volax's username
			wordpress users and aliases: array (
			  0 => '1: boston (dvidos)',
			  1 => '2: jimel (jimel)',
			  2 => '3: mix (mix)',
			  3 => '4: nikaliamoutos (nikaliamoutos)',
			  4 => '5: payloskal (payloskal)',
			  5 => '6: avidalis ()',
			  6 => '7: axenopoulos ()',
			  7 => '8: rpiperi ()',
			  8 => '9: gpiperi ()',
			  9 => '10: lsigala (loukia)',
			  10 => '11: maggie (maggie)',
			  11 => '12: giannisx (giannisx)',
			  12 => '17: leopold (leopold)',
			  13 => '18: iranon (iranon)',
			  14 => '19: jacques (jacques)',
			  15 => '20: fr_georges (frGeorges)',
			  16 => '21: team (team)',
			)
			Admin WP user id (for static pages owner): '1'
			volax users and posts count per user: array (
			  0 => '1: team (142)',
			  1 => '2: dvidos (55)',
			  2 => '3: jimel (513)',
			  3 => '4: payloskal (1)',
			  4 => '5: nikaliamoutos (61)',
			  5 => '6: leopold (112)',
			  6 => '7: iranon (72)',
			  7 => '8: frGeorges (21)',
			  8 => '9: maggie (3)',
			  9 => '10: mix (13)',
			  10 => '12: jacques (13)',
			  11 => '16: loukia (6)',
			  12 => '19: giannisx (1)',
			)
			Volax User team          ==> WP user team
			Volax User dvidos        ==> WP user boston
			Volax User jimel         ==> WP user jimel
			Volax User payloskal     ==> WP user payloskal
			Volax User nikaliamoutos ==> WP user nikaliamoutos
			Volax User leopold       ==> WP user leopold
			Volax User iranon        ==> WP user iranon
			Volax User frGeorges     ==> WP user fr_georges
			Volax User maggie        ==> WP user maggie
			Volax User mix           ==> WP user mix
			Volax User jacques       ==> WP user jacques
			Volax User loukia        ==> WP user lsigala
			Volax User giannisx      ==> WP user giannisx
			
			User mappings (from v4 id to wordpress id): array (
			  1 => '21',
			  2 => '1',
			  3 => '2',
			  4 => '5',
			  5 => '4',
			  6 => '17',
			  7 => '18',
			  8 => '20',
			  9 => '11',
			  10 => '3',
			  12 => '19',
			  16 => '10',
			  19 => '12',
			)
		*/
		/* each wp_user:
		 * 'ID' => '1',
		 * 'user_login' => 'dvidos',
		 * 'user_pass' => '$P$BmsGW/23KnBE7zWcLi1g.cEceH9fPU/',
		 * 'user_nicename' => 'dvidos',
		 * 'user_email' => 'dvidos@gmail.com',
		 * 'user_url' => 'http://localhost/~dimitris/wordpress',
		 * 'user_registered' => '2021-02-23 22:04:02',
		 * 'user_activation_key' => '',
		 * 'user_status' => '0',
		 * 'display_name' => 'dvidos',
		 */
		$users = $this->wpdb->get_results("SELECT * FROM wp_users");
		$wp_users_per_username = [];
		foreach ($users as $user)
			$wp_users_per_username[$user->user_login] = $user->ID;
			
		$users = $this->wpdb->get_results("SELECT * FROM v4_user");
		$v4_users_per_username = [];
		foreach ($users as $user)
			$v4_users_per_username[$user->username] = $user->id;
		
		if ($this->is_dev) {
			$aliases = [
				'dvidos' => 'dvidos',
				'jimel' => 'jimmel',
				'payloskal' => 'jimmel',
				'nikaliamoutos' => 'jimmel',
				'leopold' => 'jimmel',
				'iranon' => 'jimmel',
				'maggie' => 'jimmel',
				'mix' => 'jimmel',
				'jacques' => 'jimmel',
				'eva' => 'dvidos',
				'Maria' => 'dvidos',
				'frGeorges' => 'dvidos',
				'Sidirm' => 'dvidos',
				'giannisx' => 'dvidos',
				'loukia' => 'dvidos',
			];
		} else {
			$aliases = [
				'dvidos' => 'boston',
				'frGeorges' => 'fr_georges',
				'loukia' => 'lsigala',
			];
		}
		
		$this->user_name_mappings = [];
		$this->user_id_mappings = [];
		
		foreach ($v4_users_per_username as $v4_username => $v4_user_id) {
			$target = $v4_username;
			if (array_key_exists($v4_username, $aliases))
				$target = $aliases[$v4_username];
			
			// if user exists, populate
			if (array_key_exists($target, $wp_users_per_username)) {
				$wp_username = $target;
				$wp_user_id = $wp_users_per_username[$target];
			} else {
				$wp_username = null;
				$wp_user_id = null;
			}
			
			// always create a key, a null value denotes a non-mapping user
			$this->user_name_mappings[$v4_username] = $wp_username;
			$this->user_id_mappings[$v4_user_id] = $wp_user_id;
		}
	}
	
	public function get_user_name_mappings() {
		return $this->user_name_mappings;
	}
	
	public function get_user_id_mappings() {
		return $this->user_id_mappings;
	}
	
	// get keys with null values in $this->user_name_mappings
	public function get_non_mapped_v4_usernames() {
		$non_mapped = [];
		foreach ($this->user_name_mappings as $name => $id)
			if (empty($id))
				$non_mapped[] = $name;
		return $non_mapped;
	}
	
	
	public function clear_log() {
		$this->log_entries = [];
	}
	
	public function log($message = "", $var = null) {
		if ($var !== null) {
			$message .= " " . var_export($var, true);
		}
		$this->log_entries[] = $message;
	}
	
	public function get_log_entries() {
		return $this->log_entries;
	}
	
	/**
	 * Called by Ajax when importing
	 */
	public function doRequestedImports($type, $identities, $skipDryRun, $overwrite) {
		$log = [];
		$this->log("doRequestedImports() arguments");
		$this->log("- Type = " . $type);
		$this->log("- Identities = " . $identities);
		$this->log("- Skip Dry Run = " . ($skipDryRun ? 1 : 0));
		$this->log("- Overwrite = " . ($overwrite ? 1 : 0));
		
		if ($type == "posts") {
			$this->doPostsImports($identities, $skipDryRun, $overwrite);
		}
	}
	
	private function id_passes($id, $filter): bool {
		if ($filter == "") 
			return false;
		if ($filter == "*" || $filter == "all")
			return true;
		if (strpos($filter, "-") !== false) {
			$parts = explode("-", $filter, 2);
			return $id >= $parts[0] && $id <= $parts[1];
		}
		if (strpos($filter, ",") !== false) {
			$ids = explode(",", $filter);
			return in_array($id, $ids);
		}
		// non empty passed, taken as literal
		return $id == $filter;
	}
	
	private function doPostsImports($identities, $skipDryRun, $overwrite) {
		$v4_identities = $this->wpdb->get_col("SELECT id FROM v4_post");
		$wp_identities = $this->wpdb->get_col("SELECT ID FROM wp_posts");
		$this->log("Loaded " . count($v4_identities) . " v4 identities");
		$this->log("Loaded " . count($wp_identities) . " WP identities");
		
		foreach ($v4_identities as $v4_id) {
			if (!$this->id_passes($v4_id, $identities))
				continue;
			
			if (in_array($v4_id, $wp_identities) && !$overwrite) {
				$this->log("Post ID $v4_id already exists, skipping. Turn on overwrite if desired");
				continue;
			}
			
			$this->importPost($v4_id, $skipDryRun);
		}
	}
	
	private function importPost($post_id, $skipDryRun) {
		$post = $this->wpdb->get_row("SELECT * FROM v4_post WHERE id = " . $post_id);
		$post->comments = $this->wpdb->get_results("SELECT * FROM v4_comment WHERE post_id = " . $post_id);
		
		
		// $this->log("post: ", $post);
		$post->content = $this->cleanUpContent($post->content);
		$this->log("cleaned content: ", $post->content);
		
		//$this->log("content images: ", $this->getContentImages($post->content));
		//$this->log("galleries: ", $this->extractShortcodes($post->content, 'gallery'));
		//$this->log("audios: ", $this->extractShortcodes($post->content, 'audio'));
		
		$media = [];
		$images = $this->getContentImages($post->content);
		$galleries = $this->extractShortcodes($post->content, 'gallery');
		foreach ($images as $image)
			$media[] = $image;
		foreach ($galleries as $gallery) {
			$folder = trim($gallery['folder'], "/");
			if (is_array($gallery['img'])) {
				foreach ($gallery['img'] as $img)
					$media[] = "/$folder/$img";
			} else {
				$media[] = "/$folder/{$gallery['img']}";
			}
		}
		$this->log("post's media:", $media);
		
		# things to do:
		# - import media
		# - fix excerpt or "more"
		# - set associated picture
		# - save masthead in _masthead post metadata
		# - convert shortcodes (gallery, audio, video)
		# - import comments
		# - import tags (as needed)
		# - clean useless spans
	}
	
	private function cleanUpContent($content) {
		// sample: "<p><span>[audio src="/uploads/</span>nikaliamoutos/2016/kalaman2016(1)valenapioume.mp3<span>"]</span></p>"
		// the question mark turns the greedy matching into a lazy one
		$content = preg_replace('/<span\>(.*?)\<\/span\>/is', '\1', $content);
		
		// sample "τώρα με χαρά <span style="color:rgb(34, 34, 34)">βλέπει κανείς"
		$content = preg_replace('/<span style="color:rgb\(34, 34, 34\)"\>(.*?)\<\/span\>/is', '\1', $content);
		$content = preg_replace('/<span style="color:rgb\(35, 35, 35\)"\>(.*?)\<\/span\>/is', '\1', $content);
		$content = preg_replace('/<span style="font-size:10pt"\>(.*?)\<\/span\>/is', '\1', $content);
		$content = preg_replace('/<span style="background-color:transparent"\>(.*?)\<\/span\>/is', '\1', $content);
		
		// sample: "κορυφώθηκαν το&nbsp;προηγούμενο βράδυ"
		// we happen to convert simple empty paragraphs with the above "\S" (non-whitespace)
		$content = preg_replace('/(\S)&nbsp;(\S)/', '\1 \2', $content);
		$content = str_replace('<p> </p>', '<p>&nbsp;</p>', $content);
		
		// use WP more tag
		$content = str_replace('<p>[more]</p>', '<!--more-->', $content);
		$content = str_replace('[more]', '<!--more-->', $content);
		
		return $content;
	}
	
	private function getContentImages($content) {
		$media = [];
		$matches = [];
		/**
		 * using PREG_PATTERN_ORDER,
		 *   $matches[0] contains an array of all the full matches,
		 *   $matches[1] contains an array of all the first parentheses
		 *   $matches[2] contains an array of all the second parentheses
		 * and so forth
		 */
		preg_match_all("/\<img[^\>]+src=\"([^\"]+)\"/si", $content, $matches, PREG_PATTERN_ORDER);
		foreach ($matches[1] as $src)
			$media[] = $src;
		
		return $media;
	}
	
	private function extractShortcodes($content, $shortcode) {
		$matches = [];
		preg_match_all("/\[$shortcode([^\]]+)\]/si", $content, $matches, PREG_PATTERN_ORDER);
		$shortcodes = [];
		foreach ($matches[1] as $code) {
			$code = strip_tags($code);
			$code = str_replace(["\r", "\n", "&nbsp;"], [' ', ' ', ' '], $code);
			$code = htmlspecialchars_decode($code);
			//$this->log("shortcode is [$code]");
			$a = [];
			$parts = explode(" ", $code);
			//$this->log("parts are", $parts);
			foreach ($parts as $part) {
				$part = trim($part);
				if (strlen($part) == 0)
					continue;
				[$name, $value] = explode("=", $part, 2);
				$name = trim($name);
				$value = trim($value, " \r\n\t\0'\"");
				if (empty($name) || empty($value))
					continue;
				//$this->log("part <$part> --> <$name> = <$value>");
				if (array_key_exists($name, $a)) {
					if (!is_array($a[$name])) {
						$a[$name] = [ $a[$name] ];
					}
					$a[$name][] = $value;
				} else {
					$a[$name] = $value;
				}
			}
			//$this->log("parsed shortcode is", $a);
			$shortcodes[] = $a;
		}
		
		return $shortcodes;
	}
}
