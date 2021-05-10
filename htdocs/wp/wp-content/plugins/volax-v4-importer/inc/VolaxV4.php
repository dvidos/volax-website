<?php


class VolaxV4 {
	
	private $wpdb;
	
	public function __construct() {
		global $wpdb;
		$this->wpdb = $wpdb;
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
	
	/**
	 * Returns object, null if not found
	 *
	 * Post
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
	 * Returns array of objects
	 */
	public function load_categories_of($parent_id) {
		$sql = "SELECT * FROM v4_category WHERE parent_id = " . intVal($parent_id);
		$list = $this->wpdb->get_results($sql);
		
		return $list;
	}
	
	/**
	 * Returns object
	 * 
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
}
