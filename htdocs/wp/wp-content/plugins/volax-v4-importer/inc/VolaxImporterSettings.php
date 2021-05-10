<?php

class Volax_Importer_Settings {
	
	private $vi_setting;
	/**
	 * Construct me
	 */
	public function __construct() {
		$this->vi_setting = get_option( 'vi_setting', '' );
		
		// register the checkbox
		add_action('admin_init', [$this, 'register_settings']);
	}
		
	/**
	 * Setup the settings
	 * 
	 * Add a single checkbox setting for Active/Inactive and a text field 
	 * just for the sake of our demo
	 * 
	 */
	public function register_settings() {
		register_setting( 'vi_setting', 'vi_setting', [$this, 'vi_validate_settings']);
		
		add_settings_section(
			'vi_settings_section',         // ID used to identify this section and with which to register options
			__( "Enable VI Templates", 'vibase' ),                  // Title to be displayed on the administration page
			array($this, 'vi_settings_callback'), // Callback used to render the description of the section
			'vi-plugin-base'                           // Page on which to add this section of options
		);
	
		add_settings_field(
			'vi_opt_in',                      // ID used to identify the field throughout the theme
			__( "Active: ", 'vibase' ),                           // The label to the left of the option interface element
			array( $this, 'vi_opt_in_callback' ),   // The name of the function responsible for rendering the option interface
			'vi-plugin-base',                          // The page on which this option will be displayed
			'vi_settings_section'         // The name of the section to which this field belongs
		);
		
		add_settings_field(
			'vi_sample_text',                      // ID used to identify the field throughout the theme
			__( "VI Sample: ", 'vibase' ),                           // The label to the left of the option interface element
			array( $this, 'vi_sample_text_callback' ),   // The name of the function responsible for rendering the option interface
			'vi-plugin-base',                          // The page on which this option will be displayed
			'vi_settings_section'         // The name of the section to which this field belongs
		);
	}
	
	public function vi_settings_callback() {
		echo _e( "Enable me", 'vibase' );
	}
	
	public function vi_opt_in_callback() {
		$enabled = false;
		$out = ''; 
		$val = false;
		
		// check if checkbox is checked
		if(! empty( $this->vi_setting ) && isset ( $this->vi_setting['vi_opt_in'] ) ) {
			$val = true;
		}
		
		if($val) {
			$out = '<input type="checkbox" id="vi_opt_in" name="vi_setting[vi_opt_in]" CHECKED  />';
		} else {
			$out = '<input type="checkbox" id="vi_opt_in" name="vi_setting[vi_opt_in]" />';
		}
		
		echo $out;
	}
	
	public function vi_sample_text_callback() {
		$out = '';
		$val = '';
		
		// check if checkbox is checked
		if(! empty( $this->vi_setting ) && isset ( $this->vi_setting['vi_sample_text'] ) ) {
			$val = $this->vi_setting['vi_sample_text'];
		}

		$out = '<input type="text" id="vi_sample_text" name="vi_setting[vi_sample_text]" value="' . $val . '"  />';
		
		echo $out;
	}
	
	/**
	 * Helper Settings function if you need a setting from the outside.
	 * 
	 * Keep in mind that in our demo the Settings class is initialized in a specific environment and if you
	 * want to make use of this function, you should initialize it earlier (before the base class)
	 * 
	 * @return boolean is enabled
	 */
	public function is_enabled() {
		if(! empty( $this->vi_setting ) && isset ( $this->vi_setting['vi_opt_in'] ) ) {
			return true;
		}
		return false;
	}
	
	/**
	 * Validate Settings
	 * 
	 * Filter the submitted data as per your request and return the array
	 * 
	 * @param array $input
	 */
	public function vi_validate_settings( $input ) {
		return $input;
	}
}
