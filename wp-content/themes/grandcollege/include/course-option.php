<?php

	/*	
	*	Goodlayers Course Option File
	*	---------------------------------------------------------------------
	* 	@version	1.0
	* 	@author		Goodlayers
	* 	@link		http://goodlayers.com
	* 	@copyright	Copyright (c) Goodlayers
	*	---------------------------------------------------------------------
	*	This file create and contains the course post_type meta elements
	*	---------------------------------------------------------------------
	*/
	
	add_action( 'init', 'create_course' );
	function create_course() {
	
		$labels = array(
			'name' => _x('Course', 'Course General Name', 'gdl_back_office'),
			'singular_name' => _x('Course Item', 'Course Singular Name', 'gdl_back_office'),
			'add_new' => _x('Add New', 'Add New Course Name', 'gdl_back_office'),
			'add_new_item' => __('Course Name', 'gdl_back_office'),
			'edit_item' => __('Course Name', 'gdl_back_office'),
			'new_item' => __('New Course', 'gdl_back_office'),
			'view_item' => __('View Course','gdl_back_office'),
			'search_items' => __('Search Course', 'gdl_back_office'),
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
			'supports' => array('title','editor','author','thumbnail','excerpt')
		); 
		  
		register_post_type( 'course' , $args);
		
		register_taxonomy(
			"course-category", array("course"), array(
				"hierarchical" => true, 
				"label" => "Categories", 
				"singular_label" => "Categories", 
				"rewrite" => true));
		register_taxonomy_for_object_type('course-category', 'course');
		
		flush_rewrite_rules();
		
	}
	
	// add table column in edit page
	add_filter("manage_edit-course_columns", "show_course_column");	
	function show_course_column($columns){
		$columns = array(
			"cb" => "<input type=\"checkbox\" />",
			"title" => "Title",
			"author" => "Author",
			"course-category" => "Course Categories",
			"date" => "date");
		return $columns;
	}
	add_action("manage_posts_custom_column","course_custom_columns");
	function course_custom_columns($column){
		global $post;

		switch ($column) {
			case "course-category":
			echo get_the_term_list($post->ID, 'course-category', '', ', ','');
			break;
		}
	}
	
	$course_meta_boxes = array(
		"Sidebar Template" => array(
		'title'=> __('SIDEBAR TEMPLATE', 'gdl_back_office'), 
		'name'=>'post-option-sidebar-template', 
		'type'=>'radioimage', 
		'default'=>'no-sidebar',
		'hr'=>'none',
		'options'=>array(
			'1'=>array('value'=>'right-sidebar','default'=>'selected','image'=>'/include/images/right-sidebar.png'),
			'2'=>array('value'=>'left-sidebar','image'=>'/include/images/left-sidebar.png'),
			'3'=>array('value'=>'both-sidebar','image'=>'/include/images/both-sidebar.png'),
			'4'=>array('value'=>'no-sidebar','image'=>'/include/images/no-sidebar.png'))),

		"Choose Left Sidebar" => array(
			'title'=> __('CHOOSE LEFT SIDEBAR', 'gdl_back_office'),
			'name'=>'post-option-choose-left-sidebar',
			'type'=>'combobox',
			'hr'=>'none'
		),		
		
		"Choose Right Sidebar" => array(
			'title'=> __('CHOOSE RIGHT SIDEBAR', 'gdl_back_office'),
			'name'=>'post-option-choose-right-sidebar',
			'type'=>'combobox',
		),

		"Social Sharing" => array(
			'title'=> __('SOCIAL NETWORK SHARING', 'gdl_back_office'),
			'name'=>'post-option-social-enabled',
			'type'=>'combobox', 
			'options'=>array('0'=>'Yes','1'=>'No'),
			'description'=>'Show the social network sharing in the blog page.'),		
	);
	
	$course_meta_boxes = array_merge($course_meta_boxes, get_course_meta_value());
	
	add_action('add_meta_boxes', 'add_course_option');
	function add_course_option(){	
	
		add_meta_box('course-option', __('Course Option','gdl_back_office'), 'add_course_option_element',
			'course', 'normal', 'high');
			
	}
	
	function add_course_option_element(){
	
		global $post, $course_meta_boxes;
		
		$course_meta_boxes['Choose Left Sidebar']['options'] = get_sidebar_name();
		$course_meta_boxes['Choose Right Sidebar']['options'] = $course_meta_boxes['Choose Left Sidebar']['options'];		
		
		echo '<div id="gdl-overlay-wrapper">';
		
		?> <div class="course-option-meta" id="course-option-meta"> <?php
		
			set_nonce();
			
			foreach($course_meta_boxes as $meta_box){

				$meta_box['value'] = get_post_meta($post->ID, $meta_box['name'], true);
				print_meta($meta_box);
				
			}
			
		?> </div> <?php
		
		echo '</div>';
		
	}
	
	function save_course_option_meta($post_id){
	
		global $course_meta_boxes;
		$edit_meta_boxes = $course_meta_boxes;
		
		// save
		foreach ($edit_meta_boxes as $edit_meta_box){
		
			if(isset($_POST[$edit_meta_box['name']])){	
				$new_data = stripslashes($_POST[$edit_meta_box['name']]);		
			}else{
				$new_data = '';
			}
			
			$old_data = get_post_meta($post_id, $edit_meta_box['name'],true);
			save_meta_data($post_id, $new_data, $old_data, $edit_meta_box['name']);
			
		}
		
	}
?>