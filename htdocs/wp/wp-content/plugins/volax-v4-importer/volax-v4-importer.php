<?php
/**
 * Plugin Name: Volax v4 Importer
 * Description: A plugin to import data from volax.gr v4, written with the YII framework)
 * Plugin URI: 
 * Author: dvidos
 * Author URI: 
 * Version: 1.0
 * Text Domain: volax-importer
 * License: none
 * 
 * Copyright 2021 dvidos (email : dvidos AT gmail DOT com)
 */

/**
 * Get some constants ready for paths when your plugin grows 
 * 
 */

define( 'VI_VERSION', '1.0' );
define( 'VI_PATH', dirname( __FILE__ ) );
define( 'VI_PATH_INCLUDES', dirname( __FILE__ ) . '/inc' );
define( 'VI_FOLDER', basename( VI_PATH ) );
define( 'VI_URL', plugins_url() . '/' . VI_FOLDER );
define( 'VI_URL_INCLUDES', VI_URL . '/inc' );

include_once(VI_PATH_INCLUDES . '/VolaxV4.php');


class Volax_Importer_Plugin {
	
	public $v4;
	
	/**
	 * 
	 * Assign everything as a call from within the constructor
	 */
	public function __construct() {
		//// add script and style calls the WP way 
		//// it's a bit confusing as styles are called with a scripts hook
		//// @blamenacin - http://make.wordpress.org/core/2011/12/12/use-wp_enqueue_scripts-not-wp_print_styles-to-enqueue-scripts-and-styles-for-the-frontend/
		
		// add scripts and styles
		add_action('wp_enqueue_scripts', [$this, 'vi_add_js']);
		add_action('wp_enqueue_scripts', [$this, 'vi_add_css']);
		add_action('admin_enqueue_scripts', [$this, 'vi_add_admin_js'] );
		add_action('admin_enqueue_scripts', [$this, 'vi_add_admin_css'] );
		
		// register admin menu pages for the plugin
		add_action('admin_menu', [$this, 'vi_register_admin_pages']);
		
		//// register meta boxes for Pages (could be replicated for posts and custom post types)
		//add_action( 'add_meta_boxes', array( $this, 'vi_meta_boxes_callback' ) );
		
		//// register save_post hooks for saving the custom fields
		//add_action( 'save_post', array( $this, 'vi_save_sample_field' ) );
		
		//// Register custom post types and taxonomies
		//add_action( 'init', array( $this, 'vi_custom_post_types_callback' ), 5 );
		//add_action( 'init', array( $this, 'vi_custom_taxonomies_callback' ), 6 );
		
		// Register activation and deactivation hooks
		register_activation_hook( __FILE__, 'vi_on_activate_callback' );
		register_deactivation_hook( __FILE__, 'vi_on_deactivate_callback' );
		
		//// Translation-ready
		//add_action( 'plugins_loaded', array( $this, 'vi_add_textdomain' ) );
		
		// Add earlier execution as it needs to occur before admin page display
		add_action('admin_init', [$this, 'vi_register_settings'], 5);
		
		//// Add a sample shortcode
		//add_action( 'init', array( $this, 'vi_sample_shortcode' ) );
		
		//// Add a sample widget
		//add_action( 'widgets_init', array( $this, 'vi_sample_widget' ) );
		
		
		// Add ajax callback actions. prefix "action" with "wp_ajax_"
		// use the wp_ajax_nopriv_ hook for non-logged users (handle guest actions)
 		add_action('wp_ajax_posted_ajax_form', [ $this, 'posted_ajax_form']);
 		
 		$this->v4 = new VolaxV4();
	}	

	/**
	 * Callback for registering pages
	 */
	public function vi_register_admin_pages() {
		add_menu_page(
			__( "Volax Importer", 'vibase' ),
			__( "Volax Importer", 'vibase' ), 
			'edit_themes', 
			'volax-importer', 
			[$this, 'vi_plugin_main_page']
		);
		add_submenu_page(
			'volax-importer',
			'Εισαγωγή στατικών σελίδων',
			'Εισαγωγή στατικών σελίδων',
			'edit_themes',
			'volax-importer-static-pages',
			[$this, 'vi_import_static_pages']
		);
		add_submenu_page(
			'volax-importer',
			'Εισαγωγή κατηγοριών',
			'Εισαγωγή κατηγοριών',
			'edit_themes',
			'volax-importer-categories',
			[$this, 'vi_import_categories']
		);
		add_submenu_page(
			'volax-importer',
			'Εισαγωγή ετικεττών',
			'Εισαγωγή ετικεττών',
			'edit_themes',
			'volax-importer-tags',
			[$this, 'vi_import_tags']
		);
		add_submenu_page(
			'volax-importer',
			'Εισαγωγή αναρτήσεων',
			'Εισαγωγή αναρτήσεων',
			'edit_themes',
			'volax-importer-posts',
			[$this, 'vi_import_posts']
		);
		add_submenu_page(
			'volax-importer',
			'Εισαγωγή πολυμέσων',
			'Εισαγωγή πολυμέσων',
			'edit_themes',
			'volax-importer-media',
			[$this, 'vi_import_media']
		);
		add_submenu_page(
			'volax-importer', 
			__( "Remote Subpage", 'vibase' ), 
			__( "Remote Subpage", 'vibase' ), 
			'edit_themes', 
			'vi-remote-subpage', 
			array( $this, 'vi_plugin_side_access_page' )
		);
	}
	
	/**
	 * Adding JavaScript scripts and CSS styles
	 */
	public function vi_add_js() {
		wp_enqueue_script( 'jquery' );
		wp_register_script( 'vi-front-end', plugins_url( '/js/front-end.js' , __FILE__ ), ['jquery'], '1.0', true );
		wp_enqueue_script( 'vi-front-end' );
	}
	public function vi_add_css() {
		wp_register_style( 'vi-front-end', plugins_url( '/css/front-end.css', __FILE__ ), [], '1.0', 'screen' );
		wp_enqueue_style( 'vi-front-end' );
	}
	public function vi_add_admin_js( $hook ) {
		wp_enqueue_script( 'jquery' );
		wp_register_script( 'vi-admin', plugins_url( '/js/admin.js' , __FILE__ ), ['jquery'], '1.0', true );
		wp_enqueue_script( 'vi-admin' );
	}
	public function vi_add_admin_css( $hook ) {
		wp_register_style( 'vi-admin', plugins_url( '/css/admin.css', __FILE__ ), [], '1.0', 'screen' );
		wp_enqueue_style( 'vi-admin' );
	}
	
	
	
	public function vi_plugin_main_page() {
		include_once( VI_PATH_INCLUDES . '/main-page.php' );
	}
	
	public function vi_plugin_side_access_page() {
		include_once( VI_PATH_INCLUDES . '/remote-page-template.php' );
	}
	
	public function vi_import_static_pages() {
		include_once( VI_PATH_INCLUDES . '/import-static-pages.php' );
	}
	
	public function vi_import_categories() {
		include_once( VI_PATH_INCLUDES . '/import-categories.php' );
	}
	
	public function vi_import_posts() {
		include_once( VI_PATH_INCLUDES . '/import-posts.php' );
	}
	
	public function vi_import_tags() {
		include_once( VI_PATH_INCLUDES . '/import-tags.php' );
	}
	
	public function vi_import_media() {
		include_once( VI_PATH_INCLUDES . '/import-media.php' );
	}
	
	/**
	 * 
	 *  Adding right and bottom meta boxes to Pages
	 *   
	 */
	public function vi_meta_boxes_callback() {
		// register side box
		add_meta_box( 
		        'vi_side_meta_box',
		        __( "DX Side Box", 'vibase' ),
		        array( $this, 'vi_side_meta_box' ),
		        'pluginbase', // leave empty quotes as '' if you want it on all custom post add/edit screens
		        'side',
		        'high'
		    );
		    
		// register bottom box
		add_meta_box(
		    	'vi_bottom_meta_box',
		    	__( "vi Bottom Box", 'vibase' ), 
		    	array( $this, 'vi_bottom_meta_box' ),
		    	'' // leave empty quotes as '' if you want it on all custom post add/edit screens or add a post type slug
		    );
	}
	
	/**
	 * 
	 * Init right side meta box here 
	 * @param post $post the post object of the given page 
	 * @param metabox $metabox metabox data
	 */
	public function vi_side_meta_box( $post, $metabox) {
		_e("<p>Side meta content here</p>", 'vibase');
		
		// Add some test data here - a custom field, that is
		$vi_test_input = '';
		if ( ! empty ( $post ) ) {
			// Read the database record if we've saved that before
			$vi_test_input = get_post_meta( $post->ID, 'vi_test_input', true );
		}
		?>
		<label for="vi-test-input"><?php _e( 'Test Custom Field', 'vibase' ); ?></label>
		<input type="text" id="vi-test-input" name="vi_test_input" value="<?php echo $vi_test_input; ?>" />
		<?php
	}
	
	/**
	 * Save the custom field from the side metabox
	 * @param $post_id the current post ID
	 * @return post_id the post ID from the input arguments
	 * 
	 */
	public function vi_save_sample_field( $post_id ) {
		// Avoid autosaves
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		$slug = 'pluginbase';
		// If this isn't a 'book' post, don't update it.
		if ( ! isset( $_POST['post_type'] ) || $slug != $_POST['post_type'] ) {
			return;
		}
		
		// If the custom field is found, update the postmeta record
		// Also, filter the HTML just to be safe
		if ( isset( $_POST['vi_test_input']  ) ) {
			update_post_meta( $post_id, 'vi_test_input',  esc_html( $_POST['vi_test_input'] ) );
		}
	}
	
	/**
	 * 
	 * Init bottom meta box here 
	 * @param post $post the post object of the given page 
	 * @param metabox $metabox metabox data
	 */
	public function vi_bottom_meta_box( $post, $metabox) {
		_e( "<p>Bottom meta content here</p>", 'vibase' );
	}
	
	/**
	 * Register custom post types
     *
	 */
	public function vi_custom_post_types_callback() {
		register_post_type( 'pluginbase', array(
			'labels' => array(
				'name' => __("Base Items", 'vibase'),
				'singular_name' => __("Base Item", 'vibase'),
				'add_new' => _x("Add New", 'pluginbase', 'vibase' ),
				'add_new_item' => __("Add New Base Item", 'vibase' ),
				'edit_item' => __("Edit Base Item", 'vibase' ),
				'new_item' => __("New Base Item", 'vibase' ),
				'view_item' => __("View Base Item", 'vibase' ),
				'search_items' => __("Search Base Items", 'vibase' ),
				'not_found' =>  __("No base items found", 'vibase' ),
				'not_found_in_trash' => __("No base items found in Trash", 'vibase' ),
			),
			'description' => __("Base Items for the demo", 'vibase'),
			'public' => true,
			'publicly_queryable' => true,
			'query_var' => true,
			'rewrite' => true,
			'exclude_from_search' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'menu_position' => 40, // probably have to change, many plugins use this
			'supports' => array(
				'title',
				'editor',
				'thumbnail',
				'custom-fields',
				'page-attributes',
			),
			'taxonomies' => array( 'post_tag' )
		));	
	}
	
	
	/**
	 * Register custom taxonomies
     *
	 */
	public function vi_custom_taxonomies_callback() {
		register_taxonomy( 'pluginbase_taxonomy', 'pluginbase', array(
			'hierarchical' => true,
			'labels' => array(
				'name' => _x( "Base Item Taxonomies", 'taxonomy general name', 'vibase' ),
				'singular_name' => _x( "Base Item Taxonomy", 'taxonomy singular name', 'vibase' ),
				'search_items' =>  __( "Search Taxonomies", 'vibase' ),
				'popular_items' => __( "Popular Taxonomies", 'vibase' ),
				'all_items' => __( "All Taxonomies", 'vibase' ),
				'parent_item' => null,
				'parent_item_colon' => null,
				'edit_item' => __( "Edit Base Item Taxonomy", 'vibase' ), 
				'update_item' => __( "Update Base Item Taxonomy", 'vibase' ),
				'add_new_item' => __( "Add New Base Item Taxonomy", 'vibase' ),
				'new_item_name' => __( "New Base Item Taxonomy Name", 'vibase' ),
				'separate_items_with_commas' => __( "Separate Base Item taxonomies with commas", 'vibase' ),
				'add_or_remove_items' => __( "Add or remove Base Item taxonomy", 'vibase' ),
				'choose_from_most_used' => __( "Choose from the most used Base Item taxonomies", 'vibase' )
			),
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => true,
		));
		
		register_taxonomy_for_object_type( 'pluginbase_taxonomy', 'pluginbase' );
	}
	
	/**
	 * Initialize the Settings class
	 * Register a settings section with a field for a secure WordPress admin option creation.
	 */
	public function vi_register_settings() {
		require_once( VI_PATH . '/inc/VolaxImporterSettings.php' );
		new Volax_Importer_Settings();
	}
	
	/**
	 * Register a sample shortcode to be used
	 * 
	 * First parameter is the shortcode name, would be used like: [dxsampcode]
	 * 
	 */
	public function vi_sample_shortcode() {
		add_shortcode( 'visampcode', array( $this, 'vi_sample_shortcode_body' ) );
	}
	
	/**
	 * Returns the content of the sample shortcode, like [visamplcode]
	 * @param array $attr arguments passed to array, like [visamcode attr1="one" attr2="two"]
	 * @param string $content optional, could be used for a content to be wrapped, such as [visamcode]somecontnet[/visamcode]
	 */
	public function vi_sample_shortcode_body( $attr, $content = null ) {
		/*
		 * Manage the attributes and the content as per your request and return the result
		 */
		return __( 'Sample Output', 'vibase');
	}
	
	/**
	 * Add textdomain for plugin
	 */
	public function vi_add_textdomain() {
		load_plugin_textdomain( 'vibase', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
	}
	
	/**
	 * Callback for saving a simple AJAX option with no page reload
	 */
	public function store_ajax_value() {
		if( isset( $_POST['data'] ) && isset( $_POST['data']['vi_option_from_ajax'] ) ) {
			update_option( 'vi_option_from_ajax' , $_POST['data']['vi_option_from_ajax'] );
		}	
		die();
	}
	
	/**
	 * Callback for getting a URL and fetching it's content in the admin page
	 */
	public function fetch_ajax_url_http() {
		if( isset( $_POST['data'] ) && isset( $_POST['data']['vi_url_for_ajax'] ) ) {
			$ajax_url = $_POST['data']['vi_url_for_ajax'];
			
			$response = wp_remote_get( $ajax_url );
			
			if( is_wp_error( $response ) ) {
				echo json_encode( __( 'Invalid HTTP resource', 'vibase' ) );
				die();
			}
			
			if( isset( $response['body'] ) ) {
				if( preg_match( '/<title>(.*)<\/title>/', $response['body'], $matches ) ) {
					echo json_encode( $matches[1] );
					die();
				}
			}
		}
		echo json_encode( __( 'No title found or site was not fetched properly', 'vibase' ) );
		die();
	}
	
	/**
	 * Called via ajax, from js/admin.js, from various forms in inc/*page.php
	 * Make sure to register the action first, it is not matched by naming convention.
	 * can also echo json_encode(["any" => "array"]);
	 */
	public function posted_ajax_form() {
		$what = @$_POST['data']['what'];
		$identities = @$_POST['data']['identities'];
		$skip_dry_run = @$_POST['data']['skip_dry_run'];
		
		if (empty($identities)) {
			echo "Identities are empty - aborting.";
			die;
		}
		
		$out = $this->v4->doImport($what, $idetities, $skip_dry_run);
		echo $out;
		die();
	}
}


/**
 * Activation hook
 */
function vi_on_activate_callback() {
	// do something on activation
}

/**
 * Deactivation hook
 */
function vi_on_deactivate_callback() {
	// do something when deactivated
}

// Initialize everything
$vi_plugin_base = new Volax_Importer_Plugin();
