<?php 
/**
 * Template Name: Standard Page
 */

get_header(); ?>
	<?php 
		
		$sidebar = get_post_meta($post->ID,'page-option-sidebar-template',true);
		$sidebar_class = '';
		if( $sidebar == "left-sidebar" || $sidebar == "right-sidebar"){
			$sidebar_class = "sidebar-included " . $sidebar;
		}else if( $sidebar == "both-sidebar" ){
			$sidebar_class = "both-sidebar-included";
		}

	?>
	<div class="content-wrapper <?php echo $sidebar_class; ?>">
			
		<div class="page-wrapper">
			<?php
				
				$left_sidebar = get_post_meta( $post->ID , "page-option-choose-left-sidebar", true);
				$right_sidebar = get_post_meta( $post->ID , "page-option-choose-right-sidebar", true);		

				// Page title and content
				$gdl_show_title = get_post_meta($post->ID, 'page-option-show-title', true);
				$gdl_show_content = get_post_meta($post->ID, 'page-option-show-content', true);
				
				if( $gdl_show_title == 'Yes' ){
					echo '<div class="sixteen columns mt10 mb20">';
					echo '<h1 class="gdl-page-title gdl-title title-color">';
					the_title();
					echo '</h1>';
					echo '<div class="gdl-page-caption gdl-divider" >';
					echo get_post_meta($post->ID, 'page-option-page-caption', true);
					echo '</div>';
					echo '</div>';
				}				

				echo "<div class='gdl-page-float-left'>";
				
				echo "<div class='gdl-page-item'>";
				
				if (have_posts()){
					while (have_posts()){ 
						the_post();

						if( $gdl_show_content != 'No' ){
							echo '<div class="sixteen columns mb20">';
								echo '<div class="gdl-page-content">';
								the_content();
								echo '</div>';
							echo '</div>';
						}							

					}
				}

				global $gdl_item_row_size;
				$gdl_item_row_size = 0;

				echo "</div>"; // end of gdl-page-item
				
				get_sidebar('left');		
				
				echo "</div>"; // gdl-page-float-left	
				
				get_sidebar('right');
				
			?>
			
			<div class="clear"></div>
		</div>
	</div> <!-- content-wrapper -->
	
<?php get_footer(); ?>