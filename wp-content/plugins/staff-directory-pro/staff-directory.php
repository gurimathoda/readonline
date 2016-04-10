<?php
/*
Plugin Name: Company Directory
Plugin Script: staff-directory.php
Plugin URI: http://goldplugins.com/our-plugins/company-directory/
Description: Create a directory of your staff members and show it on your website!
Version: 1.7.4
Author: GoldPlugins
Author URI: http://goldplugins.com/
*/
require_once('gold-framework/plugin-base.php');
require_once('gold-framework/staff-directory-plugin.settings.page.class.php');
require_once('include/sd_kg.php');
require_once('include/staff_list_widget.php');
require_once('include/lib/csv_importer.php');
require_once('include/lib/csv_exporter.php');

class StaffDirectoryPlugin extends StaffDirectory_GoldPlugin
{
	var $plugin_title = 'Company Directory';
	var $prefix = 'staff_dir';
	var $proUser = false;
	var $postType;
	var $customFields;
	var $in_widget = false;
	var $search_atts = false;
	var $allowed_order_by_keys = array('first_name', 'last_name', 'title', 'phone', 'email', 'address', 'website', 'staff_category');
	
	function __construct()
	{	
		$this->setup_post_type_metadata();
		$this->create_post_types();
		$this->register_taxonomies();
		$this->add_hooks();
		$this->add_stylesheets_and_scripts();
		$this->SettingsPage = new StaffDirectoryPlugin_SettingsPage($this);
			
		// check the reg key
		$this->verify_registration_key();
		
		//add Custom CSS
		add_action( 'wp_head', array($this,'output_custom_css'));
		
		parent::__construct();
	}
	
	function add_hooks()
	{
		add_shortcode('staff_list', array($this, 'staff_list_shortcode'));
		add_shortcode('staff_member', array($this, 'staff_member_shortcode'));
		add_shortcode('search_staff_members', array($this, 'search_staff_members_shortcode'));
		add_action('init', array($this, 'remove_features_from_custom_post_type'));
				
		/* Allow the user to override the_content template for single staff members */
		add_filter('the_content', array($this, 'single_staff_content_filter'));
						
		/* Allow the user to override search form for staff members */
		add_filter('search_template', array($this, 'use_custom_search_template'));
				
		// add our custom meta boxes
		add_action( 'admin_menu', array($this, 'add_meta_boxes'));
		
		//flush rewrite rules - only do this once!
		register_activation_hook( __FILE__, array($this, 'rewrite_flush' ) );
		
		$plugin = plugin_basename(__FILE__);
		add_filter( "plugin_action_links_{$plugin}", array($this, 'add_settings_link_to_plugin_action_links') );
		add_filter( 'plugin_row_meta', array($this, 'add_custom_links_to_plugin_description'), 10, 2 );	
				
		// catch CSV import/export trigger
		add_action('admin_init', array($this, 'process_import_export'));
		
		add_action( 'save_post', array( &$this, 'update_name_fields' ), 1, 2 );
		
		//register sidebar widgets
		add_action( 'widgets_init', array( &$this, 'register_widgets') );
		
		/* Support custom field searches (Pro Only) */
		if ( $this->is_pro() ) {
			add_action( 'pre_get_posts', array($this, 'filter_search_query') );
			add_action( 'get_search_query', array($this, 'get_search_query') );
		}
		
		parent::add_hooks();
	}
	
	function register_widgets()
	{
		register_widget( 'GP_Staff_List_Widget' );
	}
	
	function setup_post_type_metadata()
	{
		$options = get_option( 'sd_options' );		
		$exclude_from_search = ( isset($options['include_in_search']) && $options['include_in_search'] == 0 );		
		$this->postType = array(
			'name' => 'Staff Member',
			'plural' => 'Staff Members',
			'slug' => 'staff-members',
			'exclude_from_search' => $exclude_from_search,
		);
		$this->customFields = array();
		$this->customFields[] = array('name' => 'first_name', 'title' => 'First Name', 'description' => 'Steven, Anna', 'type' => 'text');	
		$this->customFields[] = array('name' => 'last_name', 'title' => 'Last Name', 'description' => 'Example: Smith, Goldstein', 'type' => 'text');	
		$this->customFields[] = array('name' => 'title', 'title' => 'Title', 'description' => 'Example: Director of Sales, Customer Service Team Member, Project Manager', 'type' => 'text');	
		$this->customFields[] = array('name' => 'phone', 'title' => 'Phone', 'description' => 'Best phone number to reach this person', 'type' => 'text');
		$this->customFields[] = array('name' => 'email', 'title' => 'Email', 'description' => 'Email address for this person', 'type' => 'text');		
		$this->customFields[] = array('name' => 'address', 'title' => 'Mailing Address', 'description' => 'Mailing address for this person', 'type' => 'textarea');		
		$this->customFields[] = array('name' => 'website', 'title' => 'Website', 'description' => 'Website URL for this person', 'type' => 'text');		
	}
	
	function create_post_types()
	{
		$this->add_custom_post_type($this->postType, $this->customFields);
		
		//adds single staff member shortcode to staff member list
		add_filter('manage_staff-member_posts_columns', array($this, 'column_head'), 10);  
		add_action('manage_staff-member_posts_custom_column', array($this, 'columns_content'), 10, 2); 
		
		//load list of current posts that have featured images	
		$supportedTypes = get_theme_support( 'post-thumbnails' );
		
		//none set, add them just to our type
		if( $supportedTypes === false ){
			add_theme_support( 'post-thumbnails', array( 'staff-member' ) );        
		}
		//specifics set, add our to the array
		elseif( is_array( $supportedTypes ) ){
			$supportedTypes[0][] = 'staff-member';
			add_theme_support( 'post-thumbnails', $supportedTypes[0] );
		}
	}
	
	function register_taxonomies()
	{
		$this->add_taxonomy('staff-member-category', 'staff-member', 'Staff Category', 'Staff Categories');
		
		//adds staff members by category shortcode displayed
		add_filter('manage_edit-staff-member-category_columns', array($this, 'cat_column_head'), 10);  
		add_action('manage_staff-member-category_custom_column', array($this, 'cat_columns_content'), 10, 3);
	}

	function add_meta_boxes(){
		add_meta_box( 'staff_member_shortcode', 'Shortcodes', array($this,'display_shortcodes_meta_box'), 'staff-member', 'side', 'default' );
	}
	
	/* Disable some of the normal WordPress features on the Staff Member custom post type (the editor, author, comments, excerpt) */
	function remove_features_from_custom_post_type()
	{
		//remove_post_type_support( 'staff-member', 'editor' );
		remove_post_type_support( 'staff-member', 'excerpt' );
		remove_post_type_support( 'staff-member', 'comments' );
		remove_post_type_support( 'staff-member', 'author' );
	}

	function add_stylesheets_and_scripts()
	{
		$cssUrl = plugins_url( 'assets/css/staff-directory.css' , __FILE__ );
		$this->add_stylesheet('staff-directory-css',  $cssUrl);		
	}
	
	function single_staff_content_filter($content)
	{
		if ( empty($this->in_widget) && is_single() && get_post_type() == 'staff-member' ) {
			global $staff_data;
			$staff_data = $this->get_staff_data_for_post();
			$template_content = $this->get_template_content('single-staff-member-content.php');
			return $template_content;
		}
		return $content;
	}
	
	function use_custom_search_template($original_template)
	{
		if ( !empty($_REQUEST['post_type']) && $_REQUEST['post_type'] == 'staff-member' ) {
			$custom_template = locate_template('search-staff-members.php');
			return !empty($custom_template) ? $custom_template : $original_template;
		} else {
			return $original_template;
		}
	}
	
	/* 
	 * Use a custom search form template if one was provided
	 * (Advanced search mode only)
	 */
	function use_custom_search_form_template($form)
	{
		$template_path = $this->get_template_path('search-staff-members-form.php');
		if ( file_exists($template_path) )
		{
			$vars = array(
				'staff_categories' => $this->get_all_staff_categories(),
			);
			return $this->render_template( $template_path, $vars );
		}
		else
		{
			return $form;
		}
	}
	
	/*
	 * Filters the search by our custom fields (first name, last name, category)
	 * if they are specified in the REQUEST params
	 */
	function filter_search_query($query)
	{
		// only filter the main query when advanced search params are specified
		if( !empty($_REQUEST['_search_directory']) && $query->is_main_query() )
		{
			// turn off Relevanssi filters to un-break search
			// http://www.relevanssi.com/knowledge-base/how-to-disable-relevanssi/			
			remove_filter('posts_request', 'relevanssi_prevent_default_request'); 
			remove_filter('the_posts', 'relevanssi_query');
			
			$meta_conditions = array();
			$tax_conditions = array();
			
			// add any keys present to either the taxonomy query or meta query
			foreach($this->allowed_order_by_keys as $key)
			{
				// skip any unset keys
				if ( empty($_REQUEST['_search_directory'][$key]) ) {
					continue;
				}
				
				if ( $key == 'staff_category' )
				{
					$val = $_REQUEST['_search_directory'][$key];
					if ($val == '-1') {
						continue;
					}
					else {
						$tax_conditions[] = array(
							'taxonomy' => 'staff-member-category',
							'key' => 'term_id',
							'terms' => array($val),
							'operator' => 'IN'
						);
					}
				}
				else				
				{
					$meta_conditions[] = array(
						'key' => '_ikcf_' . $key,
						'value' => $_REQUEST['_search_directory'][$key],
						'compare' => 'LIKE'
					);
				}
			}
			
			if ( !empty($meta_conditions) || !empty($tax_conditions) ) {				
				$query->set('s', ''); // s has to be set to *something* or no search will run
			}
						
			if ( !empty($meta_conditions) )
			{
				$query->set(
					'meta_query',
					array(
						'relation' => 'AND',
						$meta_conditions
					)
				);			
			}
			
			if ( !empty($tax_conditions) )
			{
				$query->set(
					'tax_query',
					array(
						'post_type' => 'staff-member',
						$tax_conditions
					)
				);			
			}
			
			// order by first or last name, depending on request
			$order_by = !empty($_REQUEST['_search_directory']['order_by']) && in_array($_REQUEST['_search_directory']['order_by'], $this->allowed_order_by_keys) ? $_REQUEST['_search_directory']['order_by'] : 'last_name';
			$order = !empty($_REQUEST['_search_directory']['order']) && in_array($_REQUEST['_search_directory']['order'], array('ASC', 'DESC')) ? $_REQUEST['_search_directory']['order'] : 'ASC';
			$meta_key = '_ikcf_' . $order_by;
			
			$query->set('meta_key', $meta_key);
			$query->set('orderby', 'meta_value');
			$query->set('order', $order);
			
		}
	}
	
	/* 
	 *	In the case of advanced searches, this function overrides the search query before it's displayed
	 *  so that it shows the First Name + Last Name, instead of a blank string
	 */
	function get_search_query($s)
	{
		if( (!empty($_REQUEST['_search_directory']['first_name']) || !empty($_REQUEST['_search_directory']['last_name'])) && empty($s) )
		{
			if ( !empty($_REQUEST['_search_directory']['first_name']) ) {
				$s .= $_REQUEST['_search_directory']['first_name'];				
			}
			
			if ( !empty($_REQUEST['_search_directory']['last_name']) ) {
				if ( !empty($s) ) {
					$s .= ' ';
				}
				$s .= $_REQUEST['_search_directory']['last_name'];
			}			
		}
		return $s;
	}
		
	/* Shortcodes */
	
	/* output a list of all staff members */
	function staff_list_shortcode($atts, $content = '')
	{
		// merge any settings specified by the shortcode with our defaults
		$defaults = array(	'caption' => '',
							'show_photos' => 'true',
							'show_name' => 'true',
							'show_title' => 'true',
							'show_bio' => 'true',
							'show_photo' => 'true',
							'show_phone' => 'true',
							'show_email' => 'true',
							'show_address' => 'true',
							'show_website' => 'true',
							'style' => 'list',
							'columns' => 'name,title,email,phone',
							'category' => false,
							'group_by_category' => false,
							'category_order' 	=> 'ASC',
							'category_orderby' 	=> 'name',
							'category_heading_tag' 	=> 'h3',
							'count' => -1,
							'in_widget' => false,
							'order_by' => 'last_name',
							'order' => 'ASC',
							'per_page' => -1
						);
		$atts = shortcode_atts($defaults, $atts);
		$atts['columns'] = array_map('trim', explode(',', $atts['columns']));
		$this->in_widget = !empty($atts['in_widget']) ? true : false;
		$vars['pagination_link_template'] = $this->get_pagination_link_template( 'staff_page');
		$vars['current_page'] = !empty($_REQUEST['staff_page']) && intval($_REQUEST['staff_page']) > 0 ? intval($_REQUEST['staff_page']) : 1;
		$html = '';
		
		// get a Custom Loop for the staff custom post type, and pass it to the template
		if (!$atts['group_by_category'])
		{
			// do not group by category (default)
			$vars['staff_loop'] = $this->get_staff_members_loop($atts['count'], $atts['category'], false, $atts['order_by'], $atts['order'], $atts['per_page']);
			$html = $this->render_staff_list($atts, $vars);
		}
		else
		{
			// group by category
			$all_cats = $this->get_all_staff_categories($atts['category_order'], $atts['category_orderby']);
			foreach($all_cats as $term) {
				
				// add heading
				$heading_template = sprintf('<%s class="staff_category_heading" id="staff-category-heading-%%d">%%s</%s>', $atts['category_heading_tag'], $atts['category_heading_tag']);
				$html .= sprintf($heading_template, $term->term_id, $term->name);
			
				// add loop html
				$vars['staff_loop'] = $this->get_staff_members_loop($atts['count'], $term->slug, false, $atts['order_by'], $atts['order'], $atts['per_page']);
				$html .= $this->render_staff_list($atts, $vars);
			}
		}
		
		// always reset in_widget to false
		$this->in_widget = false;
		
		return $html;
	}
	
	// Get a list of all staff-member-category terms
	// Pass $hide_empty = true to exclude empty categories
	function get_all_staff_categories($orderby = 'name', $order = 'ASC', $hide_empty = false)
	{
		$taxonomies = array( 
			'staff-member-category',
		);

		$args = array(
			'orderby'           => $orderby, 	// default: 'name'
			'order'             => $order,		// default: 'ASC'
			'hide_empty'        => $hide_empty, // default: false
		); 

		return get_terms($taxonomies, $args);
	}
	
	function render_staff_list($atts, $vars)
	{
		//only pro version of plugin can use styles other than List
		if(!$this->is_pro()){
			$atts['style'] = 'list';
		}
		
		$vars['options'] = $atts;
		
		// render the 'template-staff-list.php' file (can be overridden by a file with the same name in the active theme)
		switch ($atts['style'])
		{
			case 'grid':
				$templatePath = plugin_dir_path( __FILE__ ) . 'templates/staff-list-grid.php';
			break;

			case 'table':
				$templatePath = plugin_dir_path( __FILE__ ) . 'templates/staff-list-table.php';
				$vars['columns'] = $atts['columns'];
			break;
			
			default:
			case 'list':
				$templatePath = plugin_dir_path( __FILE__ ) . 'templates/staff-list.php';
			break;
		}
		$html = $this->render_template($templatePath, $vars);
		return $html;
	}
	
	/* output a single staff members */
	function staff_member_shortcode($atts, $content = '')
	{
		// merge any settings specified by the shortcode with our defaults
		$defaults = array(	'caption' => '',
							'show_photos' => 'true',
							'style' => 'list',
							'columns' => 'name,title,email,phone',
							'category' => false,
							'id' => false,
							'count' => -1
						);
						
		$atts = shortcode_atts($defaults, $atts);
		
		$html = '';
		
		if(!$atts['id']){
			//forgot to pass an ID!
			//do nothing!
		} else {		
			$atts['columns'] = array_map('trim', explode(',', $atts['columns']));
			
			//load up the staff data for this ID
			global $staff_data;
			$staff_data = $this->get_staff_data_for_this_post($atts['id']);
			
			//build html using loaded data
			$template_content = $this->get_template_content('single-staff-member-content.php');
			
			$html = $template_content;
		}
		
		return $html;
	}		
	
	// returns a list of all staff members in the database, sorted by the title, ascending
	private function get_all_staff_members()
	{
		$conditions = array('post_type' => 'staff-member',
							'post_count' => -1,
							'orderby' => 'meta_value',
							'meta_key' => '_ikcf_last_name',
							'order' => 'ASC',
					);
		$all = get_posts($conditions);	
		return $all;
	}
	
	function normalize_truthy_value($input)
	{
		$input = strtolower($input);
		$truthy_values = array('yes', 'y', '1', 1, 'true', true);
		return in_array($input, $truthy_values);
	}
	
	function get_template_content($template_name, $default_content = '')
	{	
		$template_path = $this->get_template_path($template_name);
		if (file_exists($template_path)) {
			// load template by including it in an output buffer, so that variables and PHP will be run
			ob_start();
			include($template_path);
			$content = ob_get_contents();
			ob_end_clean();
			return $content;
		}
		// couldn't find a matching template file, so return the default content instead
		return $default_content;
	}
	
	function get_template_path($template_name)
	{
		// checks if the file exists in the theme first,
		// otherwise serve the file from the plugin
		if ( $theme_file = locate_template( array ( $template_name ) ) ) {
			$template_path = $theme_file;
		} else {
			$template_path = plugin_dir_path( __FILE__ ) . 'templates/' . $template_name;
		}
		return $template_path;
	}
	
	/* Loads the meta data for a given staff member (name, phone, email, title, etc) and returns it as an array */
	function get_staff_metadata($post_id)
	{
		$ret = array();
		$staff = get_post($post_id);
		$ret['ID'] = $staff->ID;
		$ret['full_name'] = $staff->post_title;
		$ret['content'] = $staff->post_content;
		$ret['phone'] = $this->get_option_value($staff->ID, 'phone','');
		$ret['email'] = $this->get_option_value($staff->ID, 'email','');
		$ret['title'] = $this->get_option_value($staff->ID, 'title','');
		$ret['address'] = $this->get_option_value($staff->ID, 'address','');
		$ret['website'] = $this->get_option_value($staff->ID, 'website','');
		$ret['first_name'] = $this->get_option_value($staff->ID, 'first_name','');
		$ret['last_name'] = $this->get_option_value($staff->ID, 'last_name','');
		return $ret;
	}
	
	//loads staff data for a specific post, when already inside a loop (such as viewing a single staff member)
	function get_staff_data_for_post()
	{
		global $post;
		$staff_data = $this->get_staff_metadata($post->ID);
		//do anything to the data needed here, before returning to template
		return $staff_data;
	}
	
	//loads staff data for a specific post, when passed an ID for that post
	function get_staff_data_for_this_post($id = false)
	{
		$staff_data = $this->get_staff_metadata($id);
		//do anything to the data needed here, before returning to template
		return $staff_data;
	}

	// returns a list of all staff members in the database, sorted by the title, ascending
	// TBD: provide options to control how staff members are ordered
	private function get_staff_members_loop($count = -1, $taxonomy = false, $id = false, $order_by = 'last_name', $order = 'ASC', $per_page = -1)
	{
		// ensure $order_by is one of the allowed keys
		if ( !in_array( $order_by, $this->allowed_order_by_keys ) ) {
			$order_by ='last_name'; 
		}
		
		// ensure $order_by is one of the allowed keys
		if ( !in_array( $order, array('ASC', 'DESC') ) ) {
			$order ='ASC'; 
		}
		
		$meta_key = '_ikcf_' . $order_by;
		$nopaging = ($per_page <= 0);

		//setup conditions based upon parameters
		//no id, no taxonomy passed
		if(!$taxonomy && !$id){
			$conditions = array('post_type' => 'staff-member',
								'post_count' => $count,
								'orderby' => 'meta_value',
								'meta_key' => $meta_key,
								'order' => $order
			);
		//no taxonomy passed
		//id passed
		} elseif(!$taxonomy){			
			$conditions = array('post_type' => 'staff-member',
								'p' => $id
			);
		//no id passed
		//category passed
		} elseif(!$id){			
			$conditions = array('post_type' => 'staff-member',
								'post_count' => $count,
								'orderby' => 'meta_value',
								'meta_key' => $meta_key,
								'order' => $order,
								'tax_query' => array(
									array(
										'taxonomy' => 'staff-member-category',
										'field'    => 'slug',
										'terms'    => $taxonomy,
									),
								),
			);
		}
		
		// handle paging
		$paged = !empty($_REQUEST['staff_page']) && intval($_REQUEST['staff_page']) > 0 ? intval($_REQUEST['staff_page']) : 1;
		if ($nopaging) {
			$conditions['nopaging'] = true;
		}
		else {
			// NOTE: if $nopaging is false, we can assume that per_page > 0
			$conditions['posts_per_page'] = $per_page;
			$conditions['paged'] = $paged;
		}
		
		return new WP_Query($conditions);
	}
	
	/* 
	 * Returns an URL template that can be passed as the 'base' param 
	 * to WP's paginate_links function
	 * 
	 * Note: This function is based on WordPress' get_pagenum_link. 
	 * It allows the query string argument to changed from 'paged'
	 */
	function get_pagination_link_template( $arg = 'staff_page' )
	{
		$request = remove_query_arg( $arg );
		
		$home_root = parse_url(home_url());
		$home_root = ( isset($home_root['path']) ) ? $home_root['path'] : '';
		$home_root = preg_quote( $home_root, '|' );

		$request = preg_replace('|^'. $home_root . '|i', '', $request);
		$request = preg_replace('|^/+|', '', $request);

		$base = trailingslashit( get_bloginfo( 'url' ) );

		$result = add_query_arg( $arg, '%#%', $base . $request );
		$result = apply_filters( 'sd_get_pagination_link_template', $result );
		
		return esc_url_raw( $result );
	}	
	
	// check the reg key, and set $this->isPro to true/false reflecting whether the Pro version has been registered
	function verify_registration_key()
	{
        $this->options = get_option( 'sd_options' );
		if (isset($this->options['api_key']) && 
			isset($this->options['registration_email'])) {
				
				// check the key
				$keychecker = new S_D_KeyChecker();
				$correct_key = $keychecker->computeKeyEJ($this->options['registration_email']);
				if (strcmp($this->options['api_key'], $correct_key) == 0) {
					$this->proUser = true;
				} else {
					$this->proUser = false;
				}
		
		} else {
			// keys not set, so can't be valid.
			$this->proUser = false;
			
		}
		
		// look for the Pro plugin - this is also a way to be validated
		$plugin = "company-directory-pro/company-directory-pro.php";
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );			
		if(is_plugin_active($plugin)){
			$this->proUser = true;
		}	
	}
	
	function is_pro(){
		return $this->proUser;
	}

	//only do this once
	function rewrite_flush() {		
		//we need to manually create the CPT right now, so that we have something to flush the rewrite rules with!
		$gpcpt = new GoldPlugins_StaffDirectory_CustomPostType($this->postType, $this->customFields);
		$gpcpt->registerPostTypes();
		flush_rewrite_rules();
	}
	
	//this is the heading of the new column we're adding to the staff member posts list
	function column_head($defaults) {  
		$defaults = array_slice($defaults, 0, 2, true) +
		array("single_shortcode" => "Shortcode") +
		array_slice($defaults, 2, count($defaults)-2, true);
		return $defaults;  
	}  

	//this content is displayed in the staff member post list
	function columns_content($column_name, $post_ID) {  
		if ($column_name == 'single_shortcode') {  
			echo "<input type=\"text\" value=\"[staff_member id={$post_ID}]\" />";
		}  
	} 

	//this is the heading of the new column we're adding to the staff member category list
	function cat_column_head($defaults) {  
		$defaults = array_slice($defaults, 0, 2, true) +
		array("single_shortcode" => "Shortcode") +
		array_slice($defaults, 2, count($defaults)-2, true);
		return $defaults;  
	}  

	//this content is displayed in the staff member category list
	function cat_columns_content($value, $column_name, $tax_id) {  

		$category = get_term_by('id', $tax_id, 'staff-member-category');
		
		return "<input type=\"text\" value=\"[staff_list category='{$category->slug}']\" />"; 
	} 
	
	// Displays a meta box with the shortcodes to display the current Staff member
	function display_shortcodes_meta_box() {
		global $post;
		echo "Add this shortcode to any page where you'd like to <strong>display</strong> this Staff Member:<br />";
		echo "<textarea>[staff_member id=\"{$post->ID}\"]</textarea>";
	}//add Custom CSS
	
	function output_custom_css() {
		//use this to track if css has been output
		global $sd_footer_css_output;
		
		if($sd_footer_css_output){
			return;
		} else {
			$this->options = get_option( 'sd_options' );
			
			echo '<style type="text/css" media="screen">' . $this->options['custom_css'] . "</style>";
			$easy_t_footer_css_output = true;
		}
	}
	
	//add an inline link to the settings page, before the "deactivate" link
	function add_settings_link_to_plugin_action_links($links) { 
	  $settings_link = '<a href="admin.php?page=staff_dir-settings">Settings</a>';
	  array_unshift($links, $settings_link); 
	  return $links; 
	}

	// add inline links to our plugin's description area on the Plugins page
	function add_custom_links_to_plugin_description($links, $file) {

		/** Get the plugin file name for reference */
		$plugin_file = plugin_basename( __FILE__ );
	 
		/** Check if $plugin_file matches the passed $file name */
		if ( $file == $plugin_file )
		{
			$new_links['settings_link'] = '<a href="admin.php?page=staff_dir-settings">Settings</a>';
			$new_links['support_link'] = '<a href="https://goldplugins.com/contact/?utm-source=plugin_menu&utm_campaign=support&utm_banner=company-directory-plugin-menu" target="_blank">Get Support</a>';
				
			if(!$this->is_pro()){
				$new_links['upgrade_to_pro'] = '<a href="https://goldplugins.com/our-plugins/company-directory-pro/upgrade-to-company-directory-pro/?utm_source=plugin_menu&utm_campaign=upgrade" target="_blank">Upgrade to Pro</a>';
			}
			
			$links = array_merge( $links, $new_links);
		}
		return $links; 
	}
	
	/* Import / Export */
		
	/* Looks for a special POST value, and if its found, outputs a CSV of all Staff Members */
	function process_import_export()
	{
		// look for an Export command
		if ( isset($_POST['_company_dir_do_export']) && $_POST['_company_dir_do_export'] == '_company_dir_do_export' ) {
			$exporter = new StaffDirectoryPlugin_Exporter();
			$exporter->process_export();
			exit();
		}
		// look for an Import command
		else if (isset($_POST['_company_dir_do_import']) && $_POST['_company_dir_do_import'] == '_company_dir_do_import' && !empty($_FILES) ) {
			$importer = new StaffDirectoryPlugin_Importer($this);
			$this->import_result = $importer->process_import();
			if ( $this->import_result !== false ) {
				add_action( 'admin_notices', array( $this, 'display_import_notice' ) );
			}
		}
	}
	
	public function display_import_notice() {
		if ( $this->import_result['failed'] > 0 ) {
			$msg = sprintf("Successfully imported %d entries. %s entries rejected as duplicate.", $this->import_result['imported'], $this->import_result['failed']);
			printf ("<div class='updated'><p>%s</p></div>", $msg);
		}
		else {
			$msg = sprintf("Successfully imported %d entries.", $this->import_result['imported']);
			printf ("<div class='updated'><p>%s</p></div>", $msg);
		}
	}

	function search_staff_members_shortcode($atts, $content = '')
	{
		$defaults = array(
			'mode' 		=> 'basic', // basic || advanced
			'order_by' 	=> 'last_name', // see $this->allowed_order_by_keys for allowed values
			'order' 	=> 'ASC' // ASC || DESC
		);
		$atts = shortcode_atts($defaults, $atts);
		$atts['order'] = strtoupper($atts['order']);
		
		// if advanced mode was specified and user is PRO, override the search form template
		$use_custom_form = $this->is_pro() && (strtolower($atts['mode']) == 'advanced');
		if ($use_custom_form) {
			add_filter('get_search_form', array($this, 'use_custom_search_form_template'));
		}

		// Add our search params as hidden fields
		// NOTE: uses a member variable to send attributes to callback function
		$this->search_atts = $atts;
		add_filter('get_search_form', array($this, 'add_extra_fields_to_search_form'), 10);

		// run WordPress built in function to get the search form HTML,
		// which will be affected by the callbacks we just added
		$search_html = get_search_form( false );

		// clear out the member variable and filters, now that the callback has run
		$this->search_atts = false;
		remove_filter('get_search_form', array($this, 'add_extra_fields_to_search_form'));
		
		if ($use_custom_form) {
			remove_filter('get_search_form', array($this, 'use_custom_search_form_template'));
		}
		
		return $search_html;
	}
	
	function add_extra_fields_to_search_form($search_html)
	{
		// restrict_search_to_custom_post_type
		$post_type = 'staff-member';
		$replace_with = sprintf('<input type="hidden" name="post_type" value="%s">', $post_type);
		
		// add order_by and order fields, if specified
		if ( !empty($this->search_atts) ) {
			$order_by = !empty($this->search_atts['order_by']) && in_array($this->search_atts['order_by'], $this->allowed_order_by_keys) ? $this->search_atts['order_by'] : 'last_name';
			$order = !empty($this->search_atts['order']) && in_array($this->search_atts['order'], array('ASC', 'DESC')) ? $this->search_atts['order'] : 'ASC';
			$replace_with .= sprintf('<input type="hidden" name="_search_directory[order_by]" value="%s">', $order_by);
			$replace_with .= sprintf('<input type="hidden" name="_search_directory[order]" value="%s">', $order);
		}

		$replace_with = $replace_with . '</form>';
		$search_html = str_replace('</form>', $replace_with, $search_html);		
		return $search_html;
	}
	
	/* If the user did not specify a first and/or last name field, set those fields now */
	function update_name_fields($post_id, $post)
	{
		/* Only run on OUR custom post type */
		if ($post->post_type !== 'staff-member') {
			return;
		}
	
		/* Only run when the user actually clicks save, NOT on auto saves or ajax */
		if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) 
			 || (defined('DOING_AJAX') && DOING_AJAX)
			 || ($post->post_status === 'auto-draft')
		) {
			return;
		}
		
		$first_name = get_post_meta($post_id, '_ikcf_first_name', true);
		$last_name = get_post_meta($post_id, '_ikcf_last_name', true);
		$full_name = get_the_title($post_id);
		
		/* Bail if the post has no title */
		if (empty($full_name)) {
			return;
		}
		
		/* If no First Name is set, set it to the FIRST word in the post's title
		 * NOTE: If the title has no spaces, this field will not be set
		 */
		if (empty($first_name)) {
			$first_space_pos = strpos($full_name, ' ');
			$new_first_name = ($first_space_pos !== FALSE) ? substr($full_name, 0, $first_space_pos) : '';
			if (!empty($new_first_name)) {
				update_post_meta($post_id, '_ikcf_first_name', $new_first_name);
			}
		}

		/* If no Last Name is set, set it to the LAST word in the post's title		
		 * NOTE: If the title has no spaces, set Last Name to the full title
		 */
		if (empty($last_name)) {
			$last_space_pos = strrpos($full_name, ' ');			
			$new_last_name = ($last_space_pos !== FALSE) ? substr($full_name, $last_space_pos + 1) : $full_name;
			if (!empty($new_last_name)) {
				update_post_meta($post_id, '_ikcf_last_name', $new_last_name);
			}
		}
	}
	
}
$gp_sdp = new StaffDirectoryPlugin();

function cd_get_staff_search_form($advanced = false, $order_by = 'last_name', $order = 'ASC')
{
	// TODO: share this var with the corresponding member variable
	$allowed_order_by_keys = array('first_name', 'last_name', 'title', 'phone', 'email', 'address', 'website', 'staff_category');
	$mode 		= ($advanced) ? 'advanced' : 'basic';
	$order_by 	= in_array($order_by, $allowed_order_by_keys) ? $order_by : 'last_name';
	$order 		= in_array(strtoupper($order), array('ASC', 'DESC')) ? strtoupper($order) : 'ASC';
	$sc 		= sprintf('[search_staff_members mode="%s" order_by="%s" order="%s"]', $mode, $order_by, $order);
	return do_shortcode($sc);
}

function cd_get_staff_metadata($id, $options = array())
{
		
	$r['my_phone'] = get_post_meta($id, '_ikcf_phone', true);
	$r['my_email'] = get_post_meta($id, '_ikcf_email', true);
	$r['my_title'] = get_post_meta($id, '_ikcf_title', true);
	$r['my_website'] = htmlspecialchars( get_post_meta($id, '_ikcf_website', true) );
	$r['my_address'] = htmlspecialchars( get_post_meta($id, '_ikcf_address', true) );
	$r['show_title'] = isset($options['show_title']) ? $options['show_title'] : true;
	$r['show_address'] = isset($options['show_address']) ? $options['show_address'] : true;
	$r['show_phone'] = isset($options['show_phone']) ? $options['show_phone'] : true;
	$r['show_name'] = isset($options['show_bio']) ? $options['show_name'] : true;
	$r['show_bio'] = isset($options['show_bio']) ? $options['show_bio'] : true;
	$r['show_photo'] = isset($options['show_photo']) ? $options['show_photo'] : true;
	$r['show_email'] = isset($options['show_email']) ? $options['show_email'] : true;
	$r['show_website'] = isset($options['show_website']) ? $options['show_website'] : true;
	
	return $r;
}