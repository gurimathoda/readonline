<?php 

	/*
	*	Goodlayers Blog Item File
	*	---------------------------------------------------------------------
	* 	@version	1.0
	* 	@author		Goodlayers
	* 	@link		http://goodlayers.com
	* 	@copyright	Copyright (c) Goodlayers
	*	---------------------------------------------------------------------
	*	This file contains the function that can print each blog item due to 
	*	different conditions.
	*	---------------------------------------------------------------------
	*/

	// size is when no sidebar, side2 is use when 1 sidebar, side 3 is use when 3 sidebar
	if( $gdl_is_responsive ){
		$blog_div_size_num_class = array(
			"Widget Style" => array("index"=>"0" ,"class"=>"", "size"=>"60x60", "size2"=>"60x60", "size3"=>"60x60"),
			"1/1 Medium Thumbnail" => array("index"=>"1", "class"=>"sixteen columns", "size"=>"460x180", "size2"=>"390x250", "size3"=>"450x150"),
			"1/1 Full Thumbnail" => array("index"=>"2", "class"=>"sixteen columns", "size"=>"930x300", "size2"=>"630x200", "size3"=>"450x150"));

	}else{
		$blog_div_size_num_class = array(
			"Widget Style" => array("index"=>"0" ,"class"=>"", "size"=>"60x60", "size2"=>"60x60", "size3"=>"60x60"),
			"1/1 Medium Thumbnail" => array("index"=>"1", "class"=>"sixteen columns", "size"=>"460x180", "size2"=>"210x135", "size3"=>"450x150"),
			"1/1 Full Thumbnail" => array("index"=>"2", "class"=>"sixteen columns", "size"=>"870x270", "size2"=>"570x230", "size3"=>"460x175"));
	}	
	
	// Print blog
	function print_blog_item($item_xml){

		wp_reset_query();	
		
		global $paged;
		global $sidebar;
		global $blog_div_size_num_class;
		
		if(empty($paged)){
			$paged = (get_query_var('page')) ? get_query_var('page') : 1; 
		}

		// get the blog meta value		
		$header = find_xml_value($item_xml, 'header');
		$num_fetch = find_xml_value($item_xml, 'num-fetch');
		$num_excerpt = find_xml_value($item_xml, 'num-excerpt');		
		$item_type = find_xml_value($item_xml, 'item-size');
		
		$category = find_xml_value($item_xml, 'category');
		$category = ( $category == 'All' )? '': $category;	
		if( !empty($category) ){
			$category_term = get_term_by( 'name', $category , 'category');
			$category = $category_term->slug;
		}
		
		// get the item class and size from array
		$item_class = $blog_div_size_num_class[$item_type]['class'];
		$item_index = $blog_div_size_num_class[$item_type]['index'];
		if( $sidebar == "no-sidebar" ){
			$item_size = $blog_div_size_num_class[$item_type]['size'];
		}else if ( $sidebar == "left-sidebar" || $sidebar == "right-sidebar" ){
			$item_size = $blog_div_size_num_class[$item_type]['size2'];
		}else{
			$item_size = $blog_div_size_num_class[$item_type]['size3'];
		}
		
		// Print Header
		if(!empty($header)){
				
			$dropcap_image = wp_get_attachment_image_src( find_xml_value($item_xml, 'header-icon') , 'full' );
			if( !empty( $dropcap_image ) ){
				echo '<div class="gdl-header-dropcap ml10">';
				echo '<div class="gdl-header-dropcap-center">';
				echo '<img src="' . $dropcap_image[0] . '" class="no-preload" alt="" />';
				echo '</div>';
				echo '</div>';
			}		
			echo '<h3 class="blog-header-title title-color gdl-title">' . $header . '</h3>';
			echo '<div class="clear"></div>';
		}
		
		query_posts(array('post_type'=>'post', 'paged'=>$paged, 
			'category_name'=>$category, 'posts_per_page'=>$num_fetch  ));		
		
		// Start printing blog
		echo '<div id="blog-item-holder" class="blog-item-holder">';
		
		if( $item_type == '1/1 Full Thumbnail' ){
			gdl_print_blog_full( $item_class, $item_size, $item_index, $num_excerpt );
		}else if( $item_type == 'Widget Style' ){
			gdl_print_blog_widget( $item_class, $item_size, $item_index, $num_excerpt );
		}
		
		echo '<div class="clear"></div>';
		echo '</div>'; // blog-item holder
		
		// Pagination
		if( find_xml_value($item_xml, "pagination") == "Yes" ){	
			pagination();
		}	
	
	}
	
	function gdl_print_blog_thumbnail( $post_id, $size ){
	
		$thumbnail_types = get_post_meta( $post_id, 'post-option-thumbnail-types', true);
		
		if( $thumbnail_types == "Image" || empty($thumbnail_types) ){
		
			$thumbnail_id = get_post_thumbnail_id( $post_id );
			$thumbnail = wp_get_attachment_image_src( $thumbnail_id , $size );
			$alt_text = get_post_meta($thumbnail_id , '_wp_attachment_image_alt', true);
			if( !empty($thumbnail) ){
				echo '<div class="blog-thumbnail-image">';
				echo '<a href="' . get_permalink() . '"><img src="' . $thumbnail[0] .'" alt="'. $alt_text .'"/></a></div>';
			}
		
		}else if( $thumbnail_types == "Video" ){
			
			$video_link = get_post_meta( $post_id, 'post-option-thumbnail-video', true); 
			echo '<div class="blog-thumbnail-video">';
			echo get_video($video_link, gdl_get_width($size), gdl_get_height($size));
			echo '</div>';
		
		}else if ( $thumbnail_types == "Slider" ){

			$slider_xml = get_post_meta( $post_id, 'post-option-thumbnail-xml', true); 
			$slider_xml_dom = new DOMDocument();
			$slider_xml_dom->loadXML($slider_xml);
			
			echo '<div class="blog-thumbnail-slider">';
			echo print_flex_slider($slider_xml_dom->documentElement, $size);
			echo '</div>';			
		
		}	
			
	}
	
	function gdl_print_single_thumbnail($post_id, $size){
	
		$thumbnail_types = get_post_meta( $post_id, 'post-option-inside-thumbnail-types', true);
		
		if( $thumbnail_types == "Image" ){
		
			$thumbnail_id = get_post_meta($post_id,'post-option-inside-thumbnial-image', true);
			$thumbnail = wp_get_attachment_image_src( $thumbnail_id , $size );
			$thumbnail_full = wp_get_attachment_image_src( $thumbnail_id , 'full' );
			$alt_text = get_post_meta($thumbnail_id , '_wp_attachment_image_alt', true);
			if( !empty($thumbnail) ){
				echo '<div class="blog-thumbnail-image">';
				echo '<a data-rel="prettyPhoto" href="' . $thumbnail_full[0] . '"><img src="' . $thumbnail[0] .'" alt="'. $alt_text .'"/></a></div>';
			}
		
		}else if( $thumbnail_types == "Video" ){
			
			$video_link = get_post_meta( $post_id, 'post-option-inside-thumbnail-video', true); 
			echo '<div class="blog-thumbnail-video">';
			echo get_video($video_link, gdl_get_width($size), gdl_get_height($size));
			echo '</div>';
		
		}else if ( $thumbnail_types == "Slider" ){

			$slider_xml = get_post_meta( $post_id, 'post-option-inside-thumbnail-xml', true); 
			$slider_xml_dom = new DOMDocument();
			$slider_xml_dom->loadXML($slider_xml);
			
			echo '<div class="blog-thumbnail-slider">';
			echo print_flex_slider($slider_xml_dom->documentElement, $size);
			echo '</div>';			
		
		}		
	
	}
	
	function gdl_print_blog_full( $item_class, $item_size, $item_index, $num_excerpt ){

		global $post, $sidebar;
		global $gdl_admin_translator;
		if( $gdl_admin_translator == 'enable' ){
			$translator_continue_reading = get_option(THEME_SHORT_NAME.'_translator_continue_reading', 'Continue Reading →');
		}else{
			$translator_continue_reading = __('Continue Reading →','gdl_front_end');
		}
	
		while( have_posts() ){
			the_post();
			//if( $post->post_type == 'course' ){ continue; }

			echo '<div class="blog-item' . $item_index . ' gdl-divider ' . $item_class . '">'; 
			
			if( $sidebar != 'both-sidebar' ){
				echo '<div class="blog-date-wrapper">';
				echo '<div class="blog-date-value">' . get_the_time('d') . '</div>';
				echo '<div class="blog-month-value">' . strtoupper(get_the_time('M')) . '</div>';
				echo '<div class="blog-year-value">' . get_the_time('Y') . '</div>';
				echo '</div>';
			}
			
			echo '<div class="blog-item-inside">';
			
			gdl_print_blog_thumbnail( $post->ID, $item_size );
			
			echo '<h2 class="blog-thumbnail-title post-title-color gdl-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h2>';
			echo '<div class="blog-thumbnail-info post-info-color gdl-divider">';
			if( $sidebar == 'both-sidebar' ){
				echo '<div class="blog-thumbnail-date">' . get_the_time('M d, Y') . '</div>';
			}
			echo '<div class="blog-thumbnail-author"> ' . __('by','gdl_front_end') . ' ' . get_the_author_link() . '</div>';	
			the_tags('<div class="blog-thumbnail-tag">', ', ' , '</div>');
			
			echo '<div class="blog-thumbnail-comment">';
			comments_popup_link( __('0 Comment','gdl_front_end'),
				__('1 Comment','gdl_front_end'),
				__('% Comments','gdl_front_end'), '',
				__('Comments are off','gdl_front_end') );
			echo '</div>';
			echo '<div class="clear"></div>';
			echo '</div>';
			
			echo '<div class="blog-thumbnail-context">';
			echo '<div class="blog-thumbnail-content">' . mb_substr( get_the_excerpt(), 0, $num_excerpt ) . '</div>';	
			echo '<a class="blog-continue-reading" href="' . get_permalink() . '"><em>' . $translator_continue_reading . '</em></a>';
			echo '</div>'; // blog-thumbnail-context
			
			echo '</div>'; // blog-item-inside
			echo '<div class="clear"></div>';
			echo '</div>'; // blog-item
		
		}
		
	}
	
	function gdl_print_blog_widget( $item_class, $item_size, $item_index, $num_excerpt ){

		global $post;
		global $gdl_admin_translator;
		if( $gdl_admin_translator == 'enable' ){
			$translator_posted_on = get_option(THEME_SHORT_NAME.'_translator_posted_on', 'Posted on');
		}else{
			$translator_posted_on = __('Posted on','gdl_front_end');
		}
	
		while( have_posts() ){
			
			the_post();

			echo '<div class="blog-item' . $item_index . ' gdl-divider ' . $item_class . ' mb15">'; 

			gdl_print_blog_thumbnail( $post->ID, $item_size );
			
			echo '<div class="blog-thumbnail-inside">';
			echo '<h2 class="blog-thumbnail-title post-widget-title-color gdl-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h2>';
			echo '<div class="blog-thumbnail-info post-widget-info-color gdl-divider">';
			echo '<div class="blog-thumbnail-date">' . $translator_posted_on . ' '  . get_the_time('d M Y') . '</div>';
			echo '<div class="clear"></div>';
			echo '</div>';
			
			echo '<div class="blog-thumbnail-context">';
			echo '<div class="blog-thumbnail-content">' . mb_substr( get_the_excerpt(), 0, $num_excerpt ) . '</div>';	
			echo '</div>'; // blog-thumbnail-context
			
			echo '<div class="clear"></div>';
			echo '</div>'; // blog-thumbnail-inside
			
			echo '</div>'; // blog-item
		
		}
	
	}

	$personnal_div_size_num_class = array(
		"1/4" => array("class"=>"four columns", "size"=>"220x121", "size2"=>"145x85", "size3"=>"220x135"), 
		"1/3" => array("class"=>"one-third column", "size"=>"300x180", "size2"=>"200x116", "size3"=>"220x135"), 
		"1/2" => array("class"=>"eight columns", "size"=>"460x290", "size2"=>"310x190", "size3"=>"220x135"), 
		"1/1 Full Width" => array("class"=>"sixteen columns", "size"=>"180x180", "size2"=>"180x180", "size3"=>"180x180"));
	
	function print_personnal_item($item_xml){
		global $personnal_div_size_num_class, $sidebar;
		
		wp_reset_query();
		
		$header = find_xml_value($item_xml, 'header');
		$num_fetch = find_xml_value($item_xml, 'num-fetch');
		$item_size = find_xml_value($item_xml, 'item-size');
		
		$category = find_xml_value($item_xml, 'category');
		$category_val = ( $category == 'All' )? '': $category;

		if( $sidebar == "no-sidebar" ){
			$sidebar_size = "size";
		}else if ( $sidebar == "left-sidebar" || $sidebar == "right-sidebar" ){
			$sidebar_size = "size2";
		}else{
			$sidebar_size = "size3";
		}
		
		if(!empty($header)){
			$dropcap_image = wp_get_attachment_image_src( find_xml_value($item_xml, 'header-icon') , 'full' );
			if( !empty( $dropcap_image ) ){
				echo '<div class="gdl-header-dropcap ml10">';
				echo '<div class="gdl-header-dropcap-center">';
				echo '<img src="' . $dropcap_image[0] . '" class="no-preload" alt="" />';
				echo '</div>';
				echo '</div>';
			}		
			echo '<h3 class="personnal-header-title title-color gdl-title">' . $header . '</h3>';
			echo '<div class="clear"></div>';
		}
		
		query_posts(array('post_type'=>'personnal', 'personnal-category'=>$category_val, 'posts_per_page'=>$num_fetch));
		
		echo '<div class="personal-item-holder">';
		
		$item_attr = $personnal_div_size_num_class[$item_size];
		if( find_xml_value($item_xml, 'item-size') == '1/1 Full Width' ){
			print_personnal_full( $item_attr, $sidebar_size );
		}else{
			print_personnal_small( $item_attr, $sidebar_size, $item_xml );
		}

		echo '</div>';
	}

	function print_personnal_small($item_attr, $sidebar_size, $item_xml){
		
		global $class_to_num;
		
		$inner_size = $class_to_num[find_xml_value($item_xml, 'item-size')];
		$outer_size = $class_to_num[find_xml_value($item_xml, 'size')];	
		$mod_num = (int) ($outer_size / $inner_size); 
		
		$count = 1;		
		echo '<div class="personnal-small">';
		while(have_posts()){
			the_post();

			$thumbnail_id = get_post_thumbnail_id();
			if( !empty($thumbnail_id) ){
				$thumbnail = wp_get_attachment_image_src( $thumbnail_id , $item_attr[$sidebar_size] );
				$thumbnail_full = wp_get_attachment_image_src( $thumbnail_id, 'full' );
				$alt_text = get_post_meta($thumbnail_id , '_wp_attachment_image_alt', true);			
				
				echo '<div class="personnal-item ' . $item_attr['class'] . '" >';
				
				echo '<div class="personnal-thumbnail-image">';
				echo '<a href="' . $thumbnail_full[0] . '" data-rel="prettyPhoto" title="' . get_the_title() . '">';
				echo '<img src="' . $thumbnail[0] . '" alt="' . $alt_text . '">';
				echo '</a>';
				echo '</div>';
			}
			
			echo '<div class="personnal-title gdl-title">';
			the_title();
			echo '</div>'; 
		
			echo '</div>'; // personnal item
			
			if( ($count % $mod_num)  == 0 ) echo '<div class="clear"></div>';
			$count++;
		}	
		echo '<div class="clear"></div>';
		echo '</div>';
	}
	
	function print_personnal_full( $item_attr, $sidebar_size ){
		echo '<div class="personnal-full">';
		while(have_posts()){
			the_post();	
			
			echo '<div class="personnal-item ' . $item_attr['class'] . ' mb20" >';

			$thumbnail_id = get_post_thumbnail_id();
			if( !empty($thumbnail_id) ){
				$thumbnail = wp_get_attachment_image_src( $thumbnail_id , $item_attr[$sidebar_size] );
				$thumbnail_full = wp_get_attachment_image_src( $thumbnail_id, 'full' );
				$alt_text = get_post_meta($thumbnail_id , '_wp_attachment_image_alt', true);	
				
				echo '<div class="personnal-thumbnail-image">';
				echo '<a href="' . $thumbnail_full[0] . '" data-rel="prettyPhoto" title="' . get_the_title() . '">';
				echo '<img src="' . $thumbnail[0] . '" alt="' . $alt_text . '">';
				echo '</a>';
				echo '</div>';
			}
			
			echo '<div class="personnal-content-wrapper">';
			echo '<div class="personnal-title gdl-title">';
			the_title();
			echo '</div>'; 
			echo '<div class="personnal-content">';
			the_content();
			echo '</div>'; 			
			echo '</div>'; // personnal-content-wrapper
		
			echo '<div class="clear"></div>';
			echo '</div>'; // personnal item			

		}			
		echo '</div>';
	}

?>