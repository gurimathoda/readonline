<?php get_header(); 

global $gdl_admin_translator;
if( $gdl_admin_translator == 'enable' ){
	$translator_not_found = get_option(THEME_SHORT_NAME.'_search_not_found', 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.');
}else{
	$translator_not_found = __('Sorry, but nothing matched your search criteria. Please try again with some different keywords.','gdl_front_end');		
}	

?>
	<?php
	
		$sidebar = get_option(THEME_SHORT_NAME.'_search_archive_sidebar','no-sidebar');
		$sidebar_class = '';
		if( $sidebar == "left-sidebar" || $sidebar == "right-sidebar"){
			$sidebar_class = "sidebar-included " . $sidebar;
		}else if( $sidebar == "both-sidebar" ){
			$sidebar_class = "both-sidebar-included";
		}

	?>
	<div class="content-wrapper <?php echo $sidebar_class; ?>">
		<div class="page-wrapper archive-wrapper">
		
			<?php
				$left_sidebar = "Search/Archive Left Sidebar";
				$right_sidebar = "Search/Archive Right Sidebar";		
				$num_excerpt = get_option(THEME_SHORT_NAME.'_search_archive_num_excerpt', 200);
				
				// 1: full-width 2: one-sidebar 3: both-sidebar
				if( $sidebar == "left-sidebar" || $sidebar == "right-sidebar" ){
					$image_size = "570x230";
				}else if( $sidebar == "both-sidebar" ){
					$image_size = "460x172";
				}else{
					$image_size = "870x270";
				}  
			
				if( have_posts() ){
					
					echo "<div class='gdl-page-float-left'>";
					echo "<div class='gdl-page-item'>";
					
					echo '<div id="blog-item-holder" class="blog-item-holder">';
					
					gdl_print_blog_full('sixteen columns', $image_size, '2', $num_excerpt);
					
					echo "</div>"; // blog-item-holder
					
					echo '<div class="clear"></div>';
			
					pagination();
				
				}else if( get_query_var('s') == 'gdl-course' ){

					echo '<div class="sixteen columns mt10 mb20">';
					echo '<h1 class="gdl-page-title gdl-title title-color">' . __('Search Course','gdl_front_end') . '</h1>';
					echo '<div class="gdl-page-caption gdl-divider" ></div>';	
					echo '</div>';

					echo "<div class='gdl-page-float-left'>";
					echo "<div class='gdl-page-item'>";
					
					global $wpdb;
					global $search_custom_meta;
					
					$meta_key = $_GET['meta_key'];
					$meta_value = $_GET['meta_value'];
					
					$search_sql = 'SELECT post_id FROM ' . $wpdb->postmeta ;
					$search_sql = $search_sql . ' WHERE meta_key = \'' . $meta_key . '\'';
					$search_sql = $search_sql . ' AND meta_value LIKE \'%' . $meta_value . '%\';';
					
					$search_query = $wpdb->get_results( $search_sql );

					echo '<div class="sixteen columns">';
					echo '<div class="gdl-page-content">';
					
					$row_class = "odd"; 
					echo "<table class='course-table'>";
					echo "<tr class='header'>";
					foreach ( $search_custom_meta as $custom_meta ){
						if( !empty($custom_meta['width']) ){
							$course_width = ' style="width: ' . $custom_meta['width'] . ';" '; 
						}else{
							$course_width = '';
						}
						echo "<th " . $course_width .">";
						if ( $custom_meta['show-on-table'] == 'Yes' ){
							echo $custom_meta['title_show'];
						}
						echo "</th>";
					}
					echo "</tr>";		
					foreach( $search_query as $search_course ){
						$course_id = $search_course->post_id;
						echo "<tr class='" . $row_class . "'>";
						foreach ( $search_custom_meta as $custom_meta ){
							echo "<td>";
							if ( $custom_meta['show-on-table'] == 'Yes' ){
								if( $custom_meta['link'] == 'Yes' ){
									echo '<a href="' . get_permalink( $course_id ) . '">';
									echo get_post_meta($course_id, $custom_meta['name'], true);
									echo '</a>';
								}else{
									echo get_post_meta($course_id, $custom_meta['name'], true);
								}
							}
							echo "</td>";
						}
						echo "</tr>";
						$row_class = ( $row_class == 'odd' )? 'even': 'odd';
					}
					echo "</table>";					

					echo '</div>'; // gdl-page-content		
					echo '</div>';		
					
				}else{
				
					echo '<div class="sixteen columns mt10 mb20">';
					echo '<h1 class="gdl-page-title gdl-title title-color">' . __('Search','gdl_front_end') . '</h1>';
					echo '<div class="gdl-page-caption gdl-divider" ></div>';	
					echo '</div>';
					
					echo "<div class='gdl-page-float-left'>";
					echo "<div class='gdl-page-item'>";
					
					echo '<div class="sixteen columns">';
					echo '<div class="gdl-page-content">';
					echo $translator_not_found;
					echo '</div>';				
					echo '</div>';		
				
				}
				
				echo "</div>"; // gdl-page-item
				
				get_sidebar('left');		
				
				echo "</div>"; // gdl-page-float-left
				
				get_sidebar('right');	
			?>
			<br class="clear">
		</div>
	</div> <!-- content-wrapper -->

<?php get_footer(); ?>
