<?php get_header(); ?>
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

				echo "<div class='gdl-page-float-left'>";
				
				echo "<div class='gdl-page-item'>";
				
				echo '<div id="blog-item-holder" class="blog-item-holder">';

				gdl_print_blog_full('sixteen columns', $image_size, '2', $num_excerpt);
				
				echo "</div>"; // blog-item-holder
				
				echo '<div class="clear"></div>';
		
				pagination();
				
				echo "</div>"; // gdl-page-item
				
				get_sidebar('left');		
				
				echo "</div>"; // gdl-page-float-left				
				
				get_sidebar('right');	
			?>
			<br class="clear">
		</div>
	</div> <!-- content-wrapper -->

<?php get_footer(); ?>
