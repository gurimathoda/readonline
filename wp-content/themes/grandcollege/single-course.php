<?php get_header(); ?>
	<?php
		// Check and get Sidebar Class
		$sidebar = get_post_meta($post->ID,'post-option-sidebar-template',true);
		$sidebar_class = '';
		if( $sidebar == "left-sidebar" || $sidebar == "right-sidebar"){
			$sidebar_class = "sidebar-included " . $sidebar;
		}else if( $sidebar == "both-sidebar" ){
			$sidebar_class = "both-sidebar-included";
		}

		// Translator words
		global $gdl_admin_translator;	
		if( $gdl_admin_translator == 'enable' ){
			$translator_client = get_option(THEME_SHORT_NAME.'_translator_client', 'Client');
			$translator_visit_website = get_option(THEME_SHORT_NAME.'_translator_visit_website', 'Visit Website');
			$translator_about_author = get_option(THEME_SHORT_NAME.'_translator_about_author', 'About the Author');
			$translator_social_share = get_option(THEME_SHORT_NAME.'_translator_social_shares', 'Social Share');
		}else{
			$translator_client =  __('Client','gdl_front_end');
			$translator_visit_website = __('Visit Website','gdl_front_end');		
			$translator_about_author = __('About the Author','gdl_front_end');
			$translator_social_share = __('Social Share','gdl_front_end');
		}		
		
	?>
	<div class="content-wrapper <?php echo $sidebar_class; ?>"> 
		<div class="clear"></div>
		<?php
			$left_sidebar = get_post_meta( $post->ID , "post-option-choose-left-sidebar", true);
			$right_sidebar = get_post_meta( $post->ID , "post-option-choose-right-sidebar", true);		

			if ( have_posts() ){
				while (have_posts()){
					the_post();

					echo '<div class="sixteen columns mb0">';	
					echo '<div class="single-course-header-title">';
					echo '<h1 class="gdl-page-title gdl-title title-color">';
					the_title();
					echo '</h1>';
					echo '<div class="gdl-page-caption gdl-divider" >';
					echo get_post_meta($post->ID, 'page-option-page-caption', true);
					echo '</div>';
					echo '</div>'; // single-course-header-title
					echo '</div>';
					
					echo "<div class='gdl-page-float-left'>";	
					echo "<div class='gdl-page-item'>";
					
					echo '<div class="sixteen columns">';
					
					// Inside Thumbnail
					if( $sidebar == "left-sidebar" || $sidebar == "right-sidebar" ){
						$item_size = "640x250";
					}else if( $sidebar == "both-sidebar" ){
						$item_size = "460x180";
					}else{
						$item_size = "940x375";
					} 

					$thumbnail_id = get_post_thumbnail_id();
					$thumbnail = wp_get_attachment_image_src( $thumbnail_id , $item_size );
					$thumbnail_full = wp_get_attachment_image_src( $thumbnail_id , 'full' );
					$alt_text = get_post_meta($thumbnail_id , '_wp_attachment_image_alt', true);
					
					if( !empty($thumbnail) ){
						echo '<div class="single-course-thumbnail-image">';
						echo '<a href="' . $thumbnail_full[0] . '" data-rel="prettyPhoto" title="' . get_the_title() . '" ><img src="' . $thumbnail[0] .'" alt="'. $alt_text .'"/></a>'; 
						echo '</div>';
					}		
												
					echo "<div class='clear'></div>";
					
					// Print Course Table
					global $search_custom_meta;
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
					echo "<tr class='odd'>";
					foreach ( $search_custom_meta as $custom_meta ){
						echo "<td>";
						if ( $custom_meta['show-on-table'] == 'Yes' ){
							echo get_post_meta($post->ID, $custom_meta['name'], true);
						}
						echo "</td>";
					}
					echo "</tr>";
					echo "</table>";					
					
					// Single content
					echo "<div class='single-course-content'>";
					echo the_content();
					echo "</div>";
					
					// Include Social Shares
					if(get_post_meta($post->ID, 'post-option-social-enabled', true) == "Yes"){
						echo "<div class='social-share-title gdl-link-title gdl-title'>";
						echo $translator_social_share;
						echo "</div>";
						include_social_shares();
						echo "<div class='clear'></div>";
					}		
					
					echo "<div class='mt30'></div>";
					
					echo "</div>"; // sixteen-column
					
				}
			}
		?>
			
		</div> <!-- gdl-page-item -->
		
		<?php 	
		
			get_sidebar('left');		
				
			echo "</div>"; // gdl-page-float-left	
			
			get_sidebar('right');
		?>
		
		<div class="clear"></div>
		
	</div> <!-- content-wrapper -->

<?php get_footer(); ?>