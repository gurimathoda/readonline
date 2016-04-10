<?php get_header(); ?>
	<?php
		// Check and get Sidebar Class
		$sidebar = get_post_meta($post->ID,'post-option-sidebar-template',true);
		global $default_post_sidebar;
		if( empty( $sidebar ) ){ $sidebar = $default_post_sidebar; }
		$sidebar_class = '';
		if( $sidebar == "left-sidebar" || $sidebar == "right-sidebar"){
			$sidebar_class = "sidebar-included " . $sidebar;
		}else if( $sidebar == "both-sidebar" ){
			$sidebar_class = "both-sidebar-included";
		}

		// Translator words
		if( $gdl_admin_translator == 'enable' ){
			$translator_about_author = get_option(THEME_SHORT_NAME.'_translator_about_author', 'About the Author');
			$translator_social_share = get_option(THEME_SHORT_NAME.'_translator_social_shares', 'Social Share');
		}else{
			$translator_about_author = __('About the Author','gdl_front_end');
			$translator_social_share = __('Social Share','gdl_front_end');
		}
	
	?>
	<div class="content-wrapper <?php echo $sidebar_class; ?>">  
		<div class="clear"></div>
		<?php
			$left_sidebar = get_post_meta( $post->ID , "post-option-choose-left-sidebar", true);
			$right_sidebar = get_post_meta( $post->ID , "post-option-choose-right-sidebar", true);
			global $default_post_left_sidebar, $default_post_right_sidebar;
			if( empty( $left_sidebar )){ $left_sidebar = $default_post_left_sidebar; } 
			if( empty( $right_sidebar )){ $right_sidebar = $default_post_right_sidebar; } 
			
			echo "<div class='gdl-page-float-left'>";

			// Inside Thumbnail
			if( $sidebar == "left-sidebar" || $sidebar == "right-sidebar" ){
				$item_size = "570x230";
			}else if( $sidebar == "both-sidebar" ){
				$item_size = "460x175";
			}else{
				$item_size = "870x270";
			} 
			
		?>
		
		<div class='gdl-page-item'>
		<div class='blog-item-holder'>
		<?php 
			if ( have_posts() ){
				while (have_posts()){
					the_post();

					echo '<div class="sixteen columns blog-item2 gdl-divider">';	
					
					if( $sidebar != "both-sidebar" ){
						echo '<div class="blog-date-wrapper">';
						echo '<div class="blog-date-value">' . get_the_time('d') . '</div>';
						echo '<div class="blog-month-value">' . strtoupper(get_the_time('M')) . '</div>';
						echo '<div class="blog-year-value">' . get_the_time('Y') . '</div>';
						echo '</div>';
					}
					
					
					echo '<div class="blog-item-inside">';
					
					gdl_print_single_thumbnail( $post->ID, $item_size );
					
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
					the_content();
					echo '</div>'; // blog-thumbnail-context
			
					echo '<div class="clear"></div>';
					
					// About Author
					if(get_post_meta($post->ID, 'post-option-author-info-enabled', true) == "Yes"){
						echo "<div class='about-author-wrapper'>";
						echo "<div class='about-author-avartar'>" . get_avatar( get_the_author_meta('ID'), 90 ) . "</div>";
						echo "<div class='about-author-info'>";
						echo "<div class='about-author-title gdl-link-title gdl-title'>" . $translator_about_author . "</div>";
						echo get_the_author_meta('description');
						echo "</div>";
						echo "<div class='clear'></div>";
						echo "</div>";
					}
					
					// Include Social Shares
					if(get_post_meta($post->ID, 'post-option-social-enabled', true) == "Yes"){
						echo "<div class='social-share-title gdl-link-title gdl-title'>";
						echo $translator_social_share;
						echo "</div>";
						include_social_shares();
						echo "<div class='clear'></div>";
					}
				
					echo '<div class="comment-wrapper">';
					comments_template(); 
					echo '</div>';

					echo '</div>'; // blog-item-inside					
					echo "</div>"; // sixteen-columns
				}
			}
		?>
		</div> <!-- blog-item-holder -->
		</div> <!-- gdl-page-item -->
		
		<?php 	
			get_sidebar('left');	
			
			echo "</div>";
			get_sidebar('right');
		?>
		
		<div class="clear"></div>
		
	</div> <!-- content-wrapper -->

<?php get_footer(); ?>