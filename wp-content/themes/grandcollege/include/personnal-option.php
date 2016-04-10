<?php

	/*	
	*	Goodlayers Personnal Option File
	*	---------------------------------------------------------------------
	* 	@version	1.0
	* 	@author		Goodlayers
	* 	@link		http://goodlayers.com
	* 	@copyright	Copyright (c) Goodlayers
	*	---------------------------------------------------------------------
	*	This file create and contains the personnal post_type meta elements
	*	---------------------------------------------------------------------
	*/
	
	add_action( 'init', 'create_personnal' );
	function create_personnal() {
	
		$labels = array(
			'name' => _x('Personnal', 'Personnal General Name', 'gdl_back_office'),
			'singular_name' => _x('Personnal Item', 'Personnal Singular Name', 'gdl_back_office'),
			'add_new' => _x('Add New', 'Add New Personnal', 'gdl_back_office'),
			'add_new_item' => __('Personnal Name', 'gdl_back_office'),
			'edit_item' => __('Personnal Name', 'gdl_back_office'),
			'new_item' => __('New Personnal', 'gdl_back_office'),
			'view_item' => __('View Personnal','gdl_back_office'),
			'search_items' => __('Search Personnal', 'gdl_back_office'),
			'not_found' =>  __('Nothing found', 'gdl_back_office'),
			'not_found_in_trash' => __('Nothing found in Trash', 'gdl_back_office'),
			'parent_item_colon' => ''
		);
		
		$args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'menu_position' => 5,
			'exclude_from_search' => true,
			'supports' => array('title','editor','author','thumbnail','excerpt')
		); 
		  
		register_post_type( 'personnal' , $args);
		
		register_taxonomy(
			"personnal-category", array("personnal"), array(
				"hierarchical" => true, 
				"label" => "Categories", 
				"singular_label" => "Categories", 
				"rewrite" => true));
		register_taxonomy_for_object_type('personnal-category', 'personnal');
		
		flush_rewrite_rules();
		
	}
	
	// add table column in edit page
	add_filter("manage_edit-personnal_columns", "show_personnal_column");	
	function show_personnal_column($columns){
		$columns = array(
			"cb" => "<input type=\"checkbox\" />",
			"title" => "Title",
			"author" => "Author",
			"personnal-category" => "Personnal Categories",
			"date" => "date");
		return $columns;
	}
	add_action("manage_posts_custom_column","personnal_custom_columns");
	function personnal_custom_columns($column){
		global $post;

		switch ($column) {
			case "personnal-category":
			echo get_the_term_list($post->ID, 'personnal-category', '', ', ','');
			break;
		}
	}
?>