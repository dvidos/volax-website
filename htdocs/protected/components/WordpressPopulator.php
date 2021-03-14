<?php

/*
 * Code to transfer content form Yii v4_ family of tables
 * into Wordpress wp_ family of tables.
 * Structure of tables and comments follow.
 * 
 * 
 * Yii Volax V4 Table Schema
 * -----------------------------------------------------
 * 
 * Posts:
 * - id                 int
 * - title              varchar(128)
 * - masthead           text
 * - category_id        int
 * - content            longtext
 * - processed_content  longtext
 * - layout             int (1010 rows with 0, 3 rows with 1)
 * - desired_width      int (1,2,3=narrow,med,wide, 355 1s, 560 2s, 96 3s)
 * - tags               text (comma separated list)
 * - status             int (1=draft, 2=published, 3=archived, 15 1s, 982 2s, 14 3s)
 * - in_home_page       int (119 0s, 892 1s) <-- i think it was for serious work not to go on home page...
 * - sticky             tinyint (15 1s, 996 0s)
 * - allow_comments     int 7 0s, 1004 1s)
 * - create_time        int (unix timestamp)
 * - update_time        int (unix timestamp)
 * - sort_time          int (unix timestamp)
 * - discussion         longtext
 * - author_id          int
 * 
 * Comments:
 * - id            int
 * - content       text
 * - status        int  (all 559 comments have a status of 2=published)
 * - create_time   int
 * - author        varchar(128)
 * - email         varchar(128)
 * - url           varchar(128)
 * - post_id       int
 * 
 * Category:
 * - id           int
 * - parent_id    int
 * - title        varchar(128)
 * - content      longtext (empty string in all rows)
 * - layout       int (we have values 0, 1 in prod)
 * - status       int (all have a 2=published in prod)
 * - discussion   longtext
 * - create_time  int
 * - update_time  int
 * - view_order   int
 * 
 * Tag:
 * - id        int
 * - name      varchar(128)
 * - frequency int (how many times it appears)
 * 
 * Page:
 * - id             int
 * - url_keyword    varchar(100)
 * - title          varchar(250)
 * - content        longtext
 * 
 * User:
 * - id         int
 * - username   varchar(128)
 * - password   varchar(128)
 * - email      varchar(128)
 * - fullname   varchar(128)
 * - initials   varchar(5)
 * - is_author  int
 * - is_admin   int
 * - profile    text
 * - image      varchar(256)
 * - registered_at    datetime
 * - last_login_at    datetime
 * - email_confirmed  int
 * - want_newsletter  int
 * - is_banned        int
 * 
 * In prod we have the following users
 * 1 - team
 * 2 - dvidos
 * 3 - jimel
 * 4 - paylos
 * 5 - nikaliamoutos
 * 6 - leopold
 * 7 - iranon
 * 8 - frGeorges
 * 9 - maggie
 * 10 - mix
 * 12 - jacques
 * 16 - loukia
 * 19 - giannisx
 * We could use this plugin to create multiple accounts with the same email: 
 * https://wordpress.org/plugins/allow-multiple-accounts/
 * 
 * For "archived" status, we can install this plugin:
 * https://wordpress.org/plugins/archived-post-status/
 * 
 * 
 * Wordpress
 * --------------------------------------------
 * Posts
 * ID 				bigint(20)
 * - post_author	bigint(20)
 * - post_date		datetime
 * - post_date_gmt	datetime
 * - post_content	longtext
 * - post_title		text
 * - post_excerpt	text
 * - post_status	varchar(20)
 * - comment_status	varchar(20)
 * - ping_status	varchar(20)
 * - post_password	varchar(255)
 * - post_name		varchar(200)
 * - to_ping		text
 * - pinged			text
 * - post_modified	datetime
 * - post_modified_gmt		datetime
 * - post_content_filtered	longtext
 * - post_parent	bigint(20)
 * - guid			varchar(255)
 * - menu_order		int(11)
 * - post_type		varchar(20)
 * - post_mime_type	varchar(100)
 * - comment_count	bigint(20)
 * 
 * Comments
 * - comment_ID			bigint
 * - comment_post_ID	bigint
 * - comment_author		tinytext
 * - comment_author_email	varchar(100)
 * - comment_author_url		varchar(100)
 * - comment_author_IP		varchar(100)
 * - comment date		datetime
 * - comment_date_gmt	datetime
 * - comment_content	text
 * - comment_karma		int
 * - comment_approved	varchar(20)
 * - comment_agent		varchar(255)
 * - comment_type		varchar(20)
 * - comment_parent		varchar(20)
 * - user_id			bigint
 * 
 * Terms (Categories at least, if not tags too)
 * - term_id       bigint         id of category
 * - name          varchar(200)   name
 * - slug          varchar(200)   (spaces converted to dashes)
 * - term_group    longint ???    zero, at least for categories
 * 
 * TermMeta (nothing so far)
 * - meta_id            bigint
 * - term_id            bigint
 * - meta_key           varchar(255)
 * - meta_value         longtext
 * 
 * Term_Relationships
 * - object_id            int
 * - term_taxonomy_id     int
 * - term_order           int
 * 
 * Term Taxonomy
 * - term_taxonomy_id     bigint
 * - term_id              bigint
 * - taxonomy             varchar(32)
 * - description          longtext
 * - parent               bigint
 * - count                bigint
 * 
 * 
 * Migration transformations
 * ---------------------------------------------
 * 
 * For a tag we need:
 * - insert in Terms
 * 		term_id = automatic
 * 		name = the name
 * 		slug = some slug
 * 		term_group = 0
 * - insert in TermTaxonomy
 * 		taxonomy_term = automatic
 * 		term_id = the tag's term_id
 * 		taxonomy = "post_tag"
 * 		description = tag description
 * 		parent = 0 (zero)
 * 		count = count of posts in tag
 *
 * For a category, we need:
 * - insert in Terms
 * 		term_id = auto incremented
 * 		name = name
 * 		slug = some generated slug
 * 		term_group = 0
 * - insert in TermTaxonomy (term_id = term_id
 * 		term_taxonomy_id = auto inc
 * 		term_id = the term id
 * 		taxonomy = 'category'
 * 		description = the category's text
 * 		parent =  term_id (not taxon_id) of the parent
 * 
 * For a post we need:
 * - insert in posts
 * 		post_id = auto incremented
 * 		post_author = id of author (translated in memory)
 * 		post_date = create time (local, Greece) "YYYY-MM-DD HH:MM:SS"
 * 		post_date_gmt = create time, GMT, same format.
 * 		post_content = the content (translations need to be applied for audios, videos, galeries etc)
 * 		post_title = post title
 * 		post_excerpt = something up to "more", maybe 55 characters (close tags!)
 * 		post_status = "draft", "publish" ("archived" through plugin)
 * 		comment_status = "open", "closed"
 * 		ping_status = "closed"
 * 		post_password = (enpty string)
 * 		post_name = maybe a slug
 * 		to_ping = empty string 
 * 		pinged = empty string
 * 		post_modified = modified time (local, Greece) "YYYY-MM-DD HH:MM:SS"
 * 		post_modified_gmt = modified time, GMT, same format.
 * 		post_content_filtered = empty string
 * 		post_parent = 0
 * 		guid = for RSS feeds. seems to be a url in the form: "http://localhost/volax_gr/wp-demo/?p=13"
 * 		menu_order = 0 (zero)
 * 		post_type = "post" (others might be "page", "nav_menu_item", "revision", "customize_changeset" etc)
 * 		post_mime_type = empty string
 * 		comment_count = the comment count
 * 
 * 
 * 
 * For a comment, we need:
 * - insert in comments
 * 		comment_ID = auto inc
 * 		comment_post_ID = post id.
 * 		comment_author = verbatim author
 * 		comment_author_email = email
 * 		comment_author_url = url
 * 		comment_author_IP = empty string
 * 		comment_date = date time GR timezone
 * 		comment_date_gmt = date time
 * 		comment_content = the text
 * 		comment_karma = 0 (zero)
 * 		comment_approved = 1
 * 		comment_agent = (user agent, i.e. "Mozila/5.0(X11...."
 * 		comment_type = "comment"
 * 		cooment_parent = 0 (zero)
 * 		user_id = the user id that made the comment, otherwise zero.
 * - insert in 
 * 
 * For a page we need:
 * - insert in posts
 * 		post_id = auto incremented
 * 		post_author = id of author (translated in memory)
 * 		post_date = create time (local, Greece) "YYYY-MM-DD HH:MM:SS"
 * 		post_date_gmt = create time, GMT, same format.
 * 		post_content = the content
 * 		post_title = page title
 * 		post_excerpt = empty string
 * 		post_status = "draft", "publish"
 * 		comment_status = "closed"
 * 		ping_status = "closed"
 * 		post_password = (enpty string)
 * 		post_name = the slug
 * 		to_ping = empty string 
 * 		pinged = empty string
 * 		post_modified = modified time (local, Greece) "YYYY-MM-DD HH:MM:SS"
 * 		post_modified_gmt = modified time, GMT, same format.
 * 		post_content_filtered = empty string
 * 		post_parent = 0
 * 		guid = for RSS feeds. seems to be a url in the form: "http://localhost/volax_gr/wp-demo/?p=13"
 * 		menu_order = 0 (zero)
 * 		post_type = "page"
 * 		post_mime_type = empty string
 * 		comment_count = 0 (ezro)
 */

class WordpressPopulator extends CApplicationComponent
{
	/**
	 * Prefix for wordpress table names
	 */
	public $wpTablesPrefix = '';
	
	/**
	 * Mapping from v4 user id to wp user id
	 * Add comma separated v4 usernames 
	 * in the "Biography info" field of WP to set match.
	 * 
	 * [ volax_user_id => wp_user_id ]
	 */
	private $user_mappings = [];
	
	/**
	 * The first user id, for pages
	 */
	private $wp_admin_user_id = 0;
	
	/**
	 * Mapping from a v4 tag name to the WP term ID
	 * [ tag_name => wp_term_id ]
	 */
	private $tag_term_ids = [];
	
	/**
	 * Mapping from wp term_id to wp term_taxonomy_id
	 * Used in defining relationships between posts and categories and tags.
	 * [ term_id => term_taxonomy_id ]
	 */
	private $term_taxonomy_ids = [];
	
	/**
	 * To allow us selective migrations in prod
	 */
	private $desired_identifiers = [];
	
	/**
	 * Log entries to be presented to user / admin
	 */
	private $log_lines = [];
	
	
	public function run(
		$desired_posts = '',
		$desired_categories = '',
		$desired_tags = '',
		$desired_pages = ''
	) {
		// clear log
		$this->log_lines = [];
		
		// supported values: 'all', '1,5,10', '23-45', '77', ''
		$this->desired_identifiers = [
			'post' => $desired_posts,
			'category' => $desired_categories,
			'tag' => $desired_tags,
			'page' => $desired_pages,
		];
		
		// $this->log("Timestamps verification");
		// $ts = $this->getCurrentTimestamp();
		// $this->log("- Current UNIX timestamp", $ts);
		// $this->log("- Greek local time", $this->getDateTimeGreek($ts));
		// $this->log("- GMT time", $this->getDateTimeGMT($ts));
		
		$posts_count = Post::model()->count();
		$categories_count = Category::model()->count();
		$tags_count = Tag::model()->count();
		$comments_count = Comment::model()->count();
		$pages_count = Page::model()->count();
		
		$min_post_id = Yii::app()->db->createCommand('SELECT min(id) FROM v4_post')->queryScalar();
		$max_post_id = Yii::app()->db->createCommand('SELECT max(id) FROM v4_post')->queryScalar();
		$min_category_id = Yii::app()->db->createCommand('SELECT min(id) FROM v4_category')->queryScalar();
		$max_category_id = Yii::app()->db->createCommand('SELECT max(id) FROM v4_category')->queryScalar();
		$min_tag_id = Yii::app()->db->createCommand('SELECT min(id) FROM v4_tag')->queryScalar();
		$max_tag_id = Yii::app()->db->createCommand('SELECT max(id) FROM v4_tag')->queryScalar();
		$min_page_id = Yii::app()->db->createCommand('SELECT min(id) FROM v4_pages')->queryScalar();
		$max_page_id = Yii::app()->db->createCommand('SELECT max(id) FROM v4_pages')->queryScalar();
		
		$this->log("Yii tables statistics");
		$this->log("- Found $posts_count posts, ID {$min_post_id} to {$max_post_id} - IDs are maintained");
		$this->log("- Found $categories_count categories, ID {$min_category_id} to {$max_category_id} - IDs are maintained");
		$this->log("- Found $tags_count tags, ID {$min_tag_id} to {$max_tag_id} - IDs are maintained");
		$this->log("- Found $comments_count comments - new IDs will be assigned");
		$this->log("- Found $pages_count static pages, ID {$min_page_id} to {$max_page_id}");
		
		$posts_count = Yii::app()->db->createCommand("SELECT count(*) FROM {$this->wpTablesPrefix}posts")->queryScalar();
		$terms_count = Yii::app()->db->createCommand("SELECT count(*) FROM {$this->wpTablesPrefix}terms")->queryScalar();
		$min_post_id = Yii::app()->db->createCommand("SELECT min(ID) FROM {$this->wpTablesPrefix}posts")->queryScalar();
		$max_post_id = Yii::app()->db->createCommand("SELECT max(ID) FROM {$this->wpTablesPrefix}posts")->queryScalar();
		$min_term_id = Yii::app()->db->createCommand("SELECT min(term_id) FROM {$this->wpTablesPrefix}terms")->queryScalar();
		$max_term_id = Yii::app()->db->createCommand("SELECT max(term_id) FROM {$this->wpTablesPrefix}terms")->queryScalar();
		
		$this->log("WP tables statistics");
		$this->log("- Found $posts_count posts, ID {$min_post_id} to {$max_post_id}");
		$this->log("- Found $terms_count terms (categories+tags), ID {$min_term_id} to {$max_term_id}");
		
		if (!$this->load_user_mappings())
			return;
		
		// categories and posts are populated with the same ID they have on volax.gr
		
		$this->populate_categories();
		$this->populate_tags();
		
		$this->load_wp_tags_and_taxonomies();
		// $this->log("tag_term_ids", $this->tag_term_ids);
		// $this->log("term_taxonomy_ids", $this->term_taxonomy_ids);
		
		$this->populate_posts();
		
		// pages should be done after posts, for there is a risk of ID collision
		$this->populate_pages(); 
		
		$this->recalculate_counts();
	}
	
	private function load_user_mappings() {
		// let's map users. use the biographical data to map volax's username.
		$this->log("Finding user mappings. Use the biographical data in wordpress to map volax's username");
		
		$wp_users = Yii::app()->db->createCommand(
			"SELECT u.ID, u.user_login, d.meta_value as description FROM {$this->wpTablesPrefix}users u
				LEFT JOIN {$this->wpTablesPrefix}usermeta d
					ON d.user_id = u.ID AND d.meta_key = 'description'
				ORDER BY u.ID
			"
		)->queryAll();
		$this->log("wordpress users and aliases", array_map(function ($row) { return "{$row['ID']}: {$row['user_login']} ({$row['description']})"; }, $wp_users));
		if (count($wp_users) > 0) {
			$this->wp_admin_user_id = $wp_users[0]['ID'];
			$this->log("Admin WP user id (for static pages owner)", $this->wp_admin_user_id);
		}
		
		$v4_users = Yii::app()->db->createCommand(
			"SELECT p.author_id as id, u.username, count(p.id) as posts
				FROM v4_post p
				LEFT JOIN v4_user u on u.id = p.author_id
				GROUP BY p.author_id
			"
		)->queryAll();
		$this->log("volax users and posts count per user", array_map(function ($row) { return "{$row['id']}: {$row['username']} ({$row['posts']})"; }, $v4_users));
		
		$this->user_mappings = [];
		$all_users_mapped = true;
		foreach ($v4_users as $v4_user) {
			// find the v4.username in the wp.description 
			// (presented in the WP UI as "Biographical Info")
			$found = false;
			foreach ($wp_users as $wp_user) {
				if (strpos($wp_user['description'], $v4_user['username']) !== false) {
					$this->log("Volax User {$v4_user['username']} ==> WP user {$wp_user['user_login']}");
					$this->user_mappings[$v4_user['id']] = $wp_user['ID'];
					$found = true;
					break;
				}
			}
			if (!$found) {
				$this->log("Error: no mapping found for v4 user", $v4_user['username']);
				$all_users_mapped = false;
			}
		}
		
		$this->log("User mappings (from v4 id to wordpress id)", $this->user_mappings);
		return $all_users_mapped;
	}
	
	private function populate_categories() {
		$category_ids = Yii::app()->db->createCommand('SELECT id FROM v4_category WHERE parent_id = 0')->queryColumn();
		$this->log("Root categories", implode(',', $category_ids));
		foreach ($category_ids as $id) {
			if (!$this->is_desired('category', $id))
				continue;
			$category = Category::model()->findByPk($id);
			$this->populate_category($category);
		}
		$category_ids = Yii::app()->db->createCommand('SELECT id FROM v4_category WHERE parent_id IN (' . implode(",", $category_ids) . ")")->queryColumn();
		$this->log("Second categories", implode(',', $category_ids));
		foreach ($category_ids as $id) {
			if (!$this->is_desired('category', $id))
				continue;
			$category = Category::model()->findByPk($id);
			$this->populate_category($category);
		}
		$category_ids = Yii::app()->db->createCommand('SELECT id FROM v4_category WHERE parent_id IN (' . implode(",", $category_ids) . ")")->queryColumn();
		$this->log("Third categories", implode(',', $category_ids));
		foreach ($category_ids as $id) {
			if (!$this->is_desired('category', $id))
				continue;
			$category = Category::model()->findByPk($id);
			$this->populate_category($category);
		}
	}
	
	public function populate_category($category) {
		/**
		 * For a category, we need:
		 * - insert in Terms
		 * 		term_id = auto incremented
		 * 		name = name
		 * 		slug = some generated slug
		 * 		term_group = 0
		 * - insert in TermTaxonomy (term_id = term_id
		 * 		term_taxonomy_id = auto inc
		 * 		term_id = the term id
		 * 		taxonomy = 'category'
		 * 		description = the category's text
		 * 		parent =  term_id (not taxon_id) of the parent
		 * */
		
		$this->log("Populating category {$category->id}: {$category->title}");
		
		Yii::app()->db->createCommand()->delete("{$this->wpTablesPrefix}terms", 'term_id = :id', [':id'=>$category->id]);
		Yii::app()->db->createCommand()->delete("{$this->wpTablesPrefix}term_taxonomy", 'term_id = :id', [':id'=>$category->id]);
		
		Yii::app()->db->createCommand()->insert("{$this->wpTablesPrefix}terms", [
			'term_id' => $category->id,
			'name' => $category->title,
			'slug' => $this->slug($category->title, $category->id),
			'term_group' => 0
		]);
		Yii::app()->db->createCommand()->insert("{$this->wpTablesPrefix}term_taxonomy", [
			'term_id' => $category->id,
			'taxonomy' => 'category',
			'description' => $category->content,
			'parent' => $category->parent_id,
		]);
	}
	
	private function populate_tags() {
		$tag_ids = Yii::app()->db->createCommand('SELECT id FROM v4_tag')->queryColumn();
		foreach ($tag_ids as $id) {
			if (!$this->is_desired('tag', $id))
				continue;
			
			$tag = Tag::model()->findByPk($id);
			$this->log("Populating tag {$tag->id} {$tag->name}");
			
			$cond = 'term_id = :id';
			$params = [':id' => $tag->id];
			Yii::app()->db->createCommand()->delete("{$this->wpTablesPrefix}terms", $cond, $params);
			Yii::app()->db->createCommand()->delete("{$this->wpTablesPrefix}term_taxonomy", $cond, $params);
			
			Yii::app()->db->createCommand()->insert("{$this->wpTablesPrefix}terms", [
				'term_id' => $tag->id,
				'name' => $tag->name,
				'slug' => $this->slug($tag->name, $tag->id),
				'term_group' => 0
			]);
			Yii::app()->db->createCommand()->insert("{$this->wpTablesPrefix}term_taxonomy", [
				'term_id' => $tag->id,
				'taxonomy' => 'post_tag',
				'description' => '',
				'parent' => 0,
			]);
		}
	}
	
	private function load_wp_tags_and_taxonomies() {
		// read tags for our mapping array when persisting posts
		$rows = Yii::app()->db->createCommand("
			SELECT m.term_id, m.name
				FROM {$this->wpTablesPrefix}terms m
				INNER JOIN {$this->wpTablesPrefix}term_taxonomy x 
					ON x.term_id = m.term_id
				WHERE x.taxonomy = 'post_tag'
		")->queryAll();
		$this->tag_term_ids = [];
		foreach ($rows as $row) {
			$key = $this->lower_greek_no_accents($row['name']);
			$this->tag_term_ids[$key] = intval($row['term_id']);
		}
		
		$rows = Yii::app()->db->createCommand("
			SELECT t.term_taxonomy_id, t.term_id
				FROM {$this->wpTablesPrefix}term_taxonomy t
				WHERE t.taxonomy IN ('category', 'post_tag')
		")->queryAll();
		$this->term_taxonomy_ids = [];
		foreach ($rows as $row) {
			$this->term_taxonomy_ids[intval($row['term_id'])] = intval($row['term_taxonomy_id']);
		}
	}
	
	private function populate_pages() {
		$page_ids = Yii::app()->db->createCommand('SELECT id FROM v4_pages')->queryColumn();
		foreach ($page_ids as $id) {
			if (!$this->is_desired('page', $id))
				continue;
				
			$page = Page::model()->findByPk($id);
			$this->populate_page($page);
		}
	}
	
	private function populate_page($page) {
		/*
		 * - id             int
		 * - url_keyword    varchar(100)
		 * - title          varchar(250)
		 * - content        longtext
		 * 
		 * For pages migration we need:
		 * - insert in posts
		 * 		post_id = auto incremented
		 * 		post_author = id of author (translated in memory)
		 * 		post_date = create time (local, Greece) "YYYY-MM-DD HH:MM:SS"
		 * 		post_date_gmt = create time, GMT, same format.
		 * 		post_content = the content
		 * 		post_title = page title
		 * 		post_excerpt = empty string
		 * 		post_status = "draft", "publish"
		 * 		comment_status = "closed"
		 * 		ping_status = "closed"
		 * 		post_password = (enpty string)
		 * 		post_name = the slug
		 * 		to_ping = empty string 
		 * 		pinged = empty string
		 * 		post_modified = modified time (local, Greece) "YYYY-MM-DD HH:MM:SS"
		 * 		post_modified_gmt = modified time, GMT, same format.
		 * 		post_content_filtered = empty string
		 * 		post_parent = 0
		 * 		guid = for RSS feeds. seems to be a url in the form: "http://localhost/volax_gr/wp-demo/?p=13"
		 * 		menu_order = 0 (zero)
		 * 		post_type = "page"
		 * 		post_mime_type = empty string
		 * 		comment_count = 0 (ezro)
		 */
		$this->log("Populating page {$page->id} {$page->title}");
		
		// pages are IDs 1-7, we'll try the "space" 81-87.
		$cond = "post_type = 'page' AND (ID = :id OR post_name = :slug)";
		$params = [':id' => (80 + $page->id), ':slug' => $page->url_keyword];
		Yii::app()->db->createCommand()->delete("{$this->wpTablesPrefix}posts", $cond, $params);
		
		Yii::app()->db->createCommand()->insert("{$this->wpTablesPrefix}posts", [
			'ID' => (80 + $page->id),
			'post_author' => $this->wp_admin_user_id,
			'post_date' => $this->getDateTimeGreek(),
			'post_date_gmt' => $this->getDateTimeGMT(),
			'post_content' => $page->content,
			'post_title' => $page->title,
			'post_excerpt' => '',
			'post_status' => 'publish',
			'comment_status' => 'closed',
			'ping_status' => 'closed',
			'post_password' => '',
			'post_name' => $page->url_keyword,
			'to_ping' => '',
			'pinged' => '',
			'post_modified' => $this->getDateTimeGreek(),
			'post_modified_gmt' => $this->getDateTimeGMT(),
			'post_content_filtered' => '',
			'post_parent' => 0,
			'guid' => 'http://volax.gr/pages/' . $page->url_keyword,
			'menu_order' => 0,
			'post_type' => "page",
			'post_mime_type' => '',
			'comment_count' => 0,
		]);
	}
	
	private function populate_posts() {
		$page_ids = Yii::app()->db->createCommand('SELECT id FROM v4_post ORDER BY id')->queryColumn();
		$count = 0;
		foreach ($page_ids as $id) {
			if (!$this->is_desired('post', $id))
				continue;
			
			$page = Post::model()->findByPk($id);
			$this->populate_post($page);
			
			$count += 1;
			if ($count > 10000) break;
		}
	}
	
	public function populate_post($post) {
		/*
		 * - insert in posts
		 * 		post_id = auto incremented
		 * 		post_author = id of author (translated in memory)
		 * 		post_date = create time (local, Greece) "YYYY-MM-DD HH:MM:SS"
		 * 		post_date_gmt = create time, GMT, same format.
		 * 		post_content = the content (translations need to be applied for audios, videos, galeries etc)
		 * 		post_title = post title
		 * 		post_excerpt = something up to "more", maybe 55 characters (close tags!)
		 * 		post_status = "draft", "publish" ("archived" through plugin)
		 * 		comment_status = "open", "closed"
		 * 		ping_status = "closed"
		 * 		post_password = (enpty string)
		 * 		post_name = maybe a slug
		 * 		to_ping = empty string 
		 * 		pinged = empty string
		 * 		post_modified = modified time (local, Greece) "YYYY-MM-DD HH:MM:SS"
		 * 		post_modified_gmt = modified time, GMT, same format.
		 * 		post_content_filtered = empty string
		 * 		post_parent = 0
		 * 		guid = for RSS feeds. seems to be a url in the form: "http://localhost/volax_gr/wp-demo/?p=13"
		 * 		menu_order = 0 (zero)
		 * 		post_type = "post" (others might be "page", "nav_menu_item", "revision", "customize_changeset" etc)
		 * 		post_mime_type = empty string
		 * 		comment_count = the comment count
		 */
		$this->log("Populating post {$post->id} {$post->title}");
		
		$cond = "ID = :id";
		$params = [':id' => $post->id];
		Yii::app()->db->createCommand()->delete("{$this->wpTablesPrefix}posts", $cond, $params);
		$cond = "object_id = :object_id";
		$params = [':object_id' => $post->id];
		Yii::app()->db->createCommand()->delete("{$this->wpTablesPrefix}term_relationships", $cond, $params);
		$cond = "post_id = :post_id";
		$params = [':post_id' => $post->id];
		Yii::app()->db->createCommand()->delete("{$this->wpTablesPrefix}postmeta", $cond, $params);
		$cond = "comment_post_ID = :comment_post_ID";
		$params = [':comment_post_ID' => $post->id];
		Yii::app()->db->createCommand()->delete("{$this->wpTablesPrefix}comments", $cond, $params);
		
		// TODO: investigate galleries, videos etc.
		// TODO: fix or extract excerpt (and close tags)
		// TODO: find way for custom CSS (low-text etc)
		// TODO: set post associated picture
		
		// DONE: store masthead in metadata
		// DONE: fix post category and tags
		
		$wp_statuses = [
			1 => 'draft',
			2 => 'publish',
			3 => 'archived',
		];
		
		Yii::app()->db->createCommand()->insert("{$this->wpTablesPrefix}posts", [
			'ID' => $post->id,
			'post_author' => $this->user_mappings[$post->author_id],
			'post_date' => $this->getDateTimeGreek($post->create_time),
			'post_date_gmt' => $this->getDateTimeGMT($post->create_time),
			'post_content' => $post->content,
			'post_title' => $post->title,
			'post_excerpt' => '',
			'post_status' => $wp_statuses[$post->status],
			'comment_status' => ($post->allow_comments ? 'open' : 'closed'),
			'ping_status' => 'closed',
			'post_password' => '',
			'post_name' => $this->slug($post->title, $post->id),
			'to_ping' => '',
			'pinged' => '',
			'post_modified' => $this->getDateTimeGreek($post->update_time),
			'post_modified_gmt' => $this->getDateTimeGMT($post->update_time),
			'post_content_filtered' => '',
			'post_parent' => 0,
			'guid' => 'http://volax.gr/posts/' . $this->slug($post->title, $post->id),
			'menu_order' => 0,
			'post_type' => "post",
			'post_mime_type' => '',
			'comment_count' => 0,
		]);
		
		// insert category relationship
		$term_id = $post->category_id; // we have the same
		$taxonomy_id = $this->term_taxonomy_ids[$term_id];
		Yii::app()->db->createCommand()->insert("{$this->wpTablesPrefix}term_relationships", [
			'object_id' => $post->id,
			'term_taxonomy_id' => $taxonomy_id,
			'term_order' => 0
		]);
		
		// insert all tags relationships
		$parts = explode(",", $post->tags);
		foreach ($parts as $tag) {
			$tag = trim($tag);
			if (strlen($tag) == 0)
				continue;
			$tag = $this->lower_greek_no_accents($tag);
			if (!array_key_exists($tag, $this->tag_term_ids)) {
				$this->log("Warning: tag not found!", $tag);
				continue;
			}
			$term_id = $this->tag_term_ids[$tag];
			$taxonomy_id = $this->term_taxonomy_ids[$term_id];
			Yii::app()->db->createCommand()->insert("{$this->wpTablesPrefix}term_relationships", [
				'object_id' => $post->id,
				'term_taxonomy_id' => $taxonomy_id,
				'term_order' => 0
			]);
		}
		
		// save masthead in meta
		if ($post->masthead) {
			Yii::app()->db->createCommand()->insert("{$this->wpTablesPrefix}postmeta", [
				'post_id' => $post->id,
				'meta_key' => '_volax_masthead',
				'meta_value' => $post->masthead
			]);
		}
		
		// insert comments
		/*
		 * For a comment, we need:
		 * - insert in comments
		 * 		comment_ID = auto inc
		 * 		comment_post_ID = post id.
		 * 		comment_author = verbatim author
		 * 		comment_author_email = email
		 * 		comment_author_url = url
		 * 		comment_author_IP = empty string
		 * 		comment_date = date time GR timezone
		 * 		comment_date_gmt = date time
		 * 		comment_content = the text
		 * 		comment_karma = 0 (zero)
		 * 		comment_approved = 1
		 * 		comment_agent = (user agent, i.e. "Mozila/5.0(X11...."
		 * 		comment_type = "comment"
		 * 		comment_parent = 0 (zero)
		 * 		user_id = the user id that made the comment, otherwise zero.
		 */
		foreach ($post->comments as $comment) {
			Yii::app()->db->createCommand()->insert("{$this->wpTablesPrefix}comments", [
				'comment_post_ID' => $post->id,
				'comment_author' => $comment->author,
				'comment_author_email' => $comment->email,
				'comment_author_url' => $comment->url,
				'comment_author_IP' => '',
				'comment_date' => $this->getDateTimeGreek($comment->create_time),
				'comment_date_gmt' => $this->getDateTimeGMT($comment->create_time),
				'comment_content' => $comment->content,
				'comment_karma' => 0,
				'comment_approved' => ($comment->status == 2) ? 1 : 0, // 2=approved in v4
				'comment_agent' => '',
				'comment_type' => 'comment',
				'comment_parent' => 0,
				'user_id' => 0,
			]);
		}
	}
	
	private function recalculate_counts() {
		$this->log("Recalculating posts per category & tag, comments per post");
		$sql = "UPDATE {$this->wpTablesPrefix}term_taxonomy SET count = (
				SELECT COUNT(*) FROM {$this->wpTablesPrefix}term_relationships rel 
					JOIN {$this->wpTablesPrefix}posts po ON (po.ID = rel.object_id) 
				WHERE rel.term_taxonomy_id = {$this->wpTablesPrefix}term_taxonomy.term_taxonomy_id 
				  AND {$this->wpTablesPrefix}term_taxonomy.taxonomy NOT IN ('link_category')
                  AND po.post_status IN ('publish', 'future')
		)";
		Yii::app()->db->createCommand($sql)->execute();
		
		$sql = "update `wp_posts` set comment_count = 
				(
					select count(*) from wp_comments 
					WHERE `comment_post_ID` = `ID` 
					and comment_approved = '1'
				)";
		Yii::app()->db->createCommand($sql)->execute();
	}
	
	private function getCurrentTimestamp() {
		return (int)(new DateTime())->getTimestamp();
	}
	
	private function getDateTimeGreek($timestamp = null) {
		if ($timestamp == null)
			$timestamp = $this->getCurrentTimestamp();
		return (new DateTime('@'.$timestamp))->setTimeZone(new DateTimeZone('+0200'))->format('Y-m-d H:i:s');
	}
	
	private function getDateTimeGMT($timestamp = null) {
		if ($timestamp == null)
			$timestamp = $this->getCurrentTimestamp();
		return (new DateTime('@'.$timestamp))->format('Y-m-d H:i:s');
	}
	
	private function slug($text, $id) {
		$t = Yii::app()->stringTools->urlFriendly($text);
		return strlen($t) == 0 ? $id : $t;
	}
	
	private function is_desired($type, $id) {
		$desired_ids = $this->desired_identifiers[$type];
		
		if ($desired_ids == 'all')
			return true;
		if (empty($desired_ids))
			return false;
		
		if (strpos($desired_ids, ',') !== false) {
			$discrete_ids = explode(',', $desired_ids);
			if (in_array($id, $discrete_ids))
				return true;
		}
		else if (strpos($desired_ids, '-') !== false) {
			$boundaries = explode('-', $desired_ids, 2);
			if (count($boundaries) != 2)
				return false;
			if ($id >= $boundaries[0] && $id <= $boundaries[1])
				return true;
		}
		else if (is_numeric($desired_ids)) {
			if ($id == $desired_ids)
				return true;
		}
		
		// anything else (e.g. a false value)
		return false;
	}
	
	private function log($str, $value = 0xDEADBEEF) {
		$extra = ($value == 0xDEADBEEF) ? '' : ': ' . var_export($value, true);
		
		Yii::log($str . $extra, "info", "WordpressPopulator");
		$this->log_lines[] = $str . $extra;
	}
	
	public function getLog() {
		return implode("\n", $this->log_lines);
	}
	
	private function lower_greek_no_accents($str) {
		$with_tones = ['ά','έ','ί','ό','ή','ώ', 'ς'];
		$no_tones = ['α','ε','ι','ο','η','ω', 'σ'];
		$str = mb_strtolower($str);
		$str = str_replace($with_tones, $no_tones, $str);
		return $str;
	}
}

