<?php

	$search_custom_meta = array(
		"course-id" => array(
			'title'=> __('COURSE ID', 'gdl_back_office'),
			'title_show'=> __('Course ID', 'gdl_front_end'),
			'name'=>'course-option-id',
			'show-on-table'=> 'Yes'),
		"course-name" => array(
			'title'=> __('COURSE NAME', 'gdl_back_office'),
			'title_show'=> __('Course Name', 'gdl_front_end'),
			'name'=>'course-option-name',
			'show-on-table'=> 'Yes',
			'link'=>'Yes'),		
		"instructor" => array(
			'title'=> __('INSTRUCTOR', 'gdl_back_office'),
			'title_show'=> __('Instructor', 'gdl_front_end'),
			'name'=>'course-option-instructor',
			'show-on-table'=> 'Yes'),	
		"room-no" => array(
			'title'=> __('ROOM NUMBER', 'gdl_back_office'),
			'title_show'=> __('Room Number', 'gdl_front_end'),
			'name'=>'course-option-room-no',
			'show-on-table'=> 'Yes'),			
		"time" => array(
			'title'=> __('TIME', 'gdl_back_office'),
			'title_show'=> __('Time', 'gdl_front_end'),
			'name'=>'course-option-time',
			'show-on-table'=> 'Yes'),	
	);

	function get_course_meta_value(){
		global $search_custom_meta;
		$course_meta_value = $search_custom_meta;

		foreach( $course_meta_value as $key => $value ){
			if( empty( $course_meta_value[$key]['type'] ) ){
				$course_meta_value[$key]['type'] = 'inputtext';	
			}	
		}

		return $course_meta_value;
	}
	
	function get_search_course_combobox(){
		global $search_custom_meta;
		$search_combobox = '<select name="meta_key">';
		foreach( $search_custom_meta as $key => $value ){
			$search_combobox = $search_combobox . '<option value="' . $value['name'] . '">';
			$search_combobox = $search_combobox . $value['title_show'];
			$search_combobox = $search_combobox . '</option>';
		}
		$search_combobox = $search_combobox . '</select>';
		return $search_combobox;
	}
	
	add_filter('pre_get_posts','search_course_filter');
	function search_course_filter($query) {
		if ( $query->is_search && get_query_var('s') == 'gdl-course' ) { $query = ''; }
		return $query;
	}


?>