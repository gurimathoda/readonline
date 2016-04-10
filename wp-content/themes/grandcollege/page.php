<?php get_header(); ?>
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
				// Top Slider Part
				global $gdl_top_slider_type, $gdl_top_slider_xml;
				if ($gdl_top_slider_type != "No Slider" && $gdl_top_slider_type != ''){
					echo print_item_size('element1-1', 'mb0');
						
						$slider_xml = "<Slider>" . create_xml_tag('size', 'full-width');
						$slider_xml = $slider_xml . create_xml_tag('height', get_post_meta( $post->ID, 'page-option-top-slider-height', true) );
						$slider_xml = $slider_xml . create_xml_tag('width', 940);
						$slider_xml = $slider_xml . create_xml_tag('slider-type', $gdl_top_slider_type);
						$slider_xml = $slider_xml . $gdl_top_slider_xml;
						$slider_xml = $slider_xml . "</Slider>";
						$slider_xml_dom = new DOMDocument();
						$slider_xml_dom->loadXML($slider_xml);
						print_slider_item($slider_xml_dom->documentElement);

					echo "</div>";
					echo '<div class="clear"></div>';
				}
				
				$gdl_page_under_slider = get_post_meta( $post->ID , "page-option-enable-under-slider", true);
				$gdl_under_slider_left = get_post_meta( $post->ID , "page-option-under-slider-left", true);
				$gdl_under_slider_right = get_post_meta( $post->ID , "page-option-under-slider-right", true);
				if (($gdl_top_slider_type != "No Slider" && $gdl_top_slider_type != '') || $gdl_page_under_slider == 'Yes'){
					echo '<div class="sixteen columns wrapper overflow-hidden">';
					if ( $gdl_page_under_slider == 'Yes' ){
					
						echo '<div class="under-slider-left" id="under-slider-left">';
						echo do_shortcode($gdl_under_slider_left);
						echo "</div>";
						echo '<div class="under-slider-right" id="under-slider-right">';
						echo do_shortcode($gdl_under_slider_right);
						echo "</div>";
						echo '<div class="clear"></div>';
						
					}
					echo "</div>";
				}
				
				$left_sidebar = get_post_meta( $post->ID , "page-option-choose-left-sidebar", true);
				$right_sidebar = get_post_meta( $post->ID , "page-option-choose-right-sidebar", true);		

				// Page title and content
				$gdl_show_title = get_post_meta($post->ID, 'page-option-show-title', true);
				$gdl_show_content = get_post_meta($post->ID, 'page-option-show-content', true);
				if (have_posts()){
					while (have_posts()){ 
						the_post();
						
						$content = get_the_content();
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
					}
				}
				
				echo "<div class='gdl-page-float-left'>";
				
				echo "<div class='gdl-page-item'>";
				
				if( $gdl_show_content != 'No' && !empty($content) ){
					echo '<div class="sixteen columns mb20">';
					if( !empty($content) && $gdl_show_content != 'No' ){
						echo '<div class="gdl-page-content">';
						the_content();
						echo '</div>';
					}
					echo '</div>';
				}				
		
				global $gdl_item_row_size;
				$gdl_item_row_size = 0;
				// Page Item Part
				if(!empty($gdl_page_xml)){
					$page_xml_val = new DOMDocument();
					$page_xml_val->loadXML($gdl_page_xml);
					foreach( $page_xml_val->documentElement->childNodes as $item_xml){
						switch($item_xml->nodeName){
							case 'Accordion' :
								print_item_size(find_xml_value($item_xml, 'size'));
								print_accordion_item($item_xml);
								break;
							case 'Blog' :
								print_item_size(find_xml_value($item_xml, 'size'), 'wrapper mb0');
								print_blog_item($item_xml);
								break;
							case 'Contact-Form' :
								print_item_size(find_xml_value($item_xml, 'size'));
								print_contact_form($item_xml);
								break;
							case 'Column':
								print_item_size(find_xml_value($item_xml, 'size'));
								print_column_item($item_xml);
								break;
							case 'Course-Table':
								print_item_size(find_xml_value($item_xml, 'size'));
								print_course_item($item_xml);
								break;								
							case 'Content' :
								print_item_size(find_xml_value($item_xml, 'size'));
								print_content_item($item_xml);
								break;
							case 'Content-Slider' :
								print_item_size(find_xml_value($item_xml, 'size'));
								print_content_slider_item($item_xml);
								break;							
							case 'Divider' :
								print_item_size(find_xml_value($item_xml, 'size'));
								print_divider($item_xml);
								break;
							case 'Gallery' :
								print_item_size(find_xml_value($item_xml, 'size'), 'wrapper');
								print_gallery_item($item_xml);
								break;								
							case 'Message-Box' :
								print_item_size(find_xml_value($item_xml, 'size'));
								print_message_box($item_xml);
								break;
							case 'Page':
								print_item_size(find_xml_value($item_xml, 'size'), 'wrapper gdl-portfolio-item mt0');
								print_page_item($item_xml);
								break;
							case 'Personnal':
								print_item_size(find_xml_value($item_xml, 'size'), 'wrapper');
								print_personnal_item($item_xml);
								break;
							case 'Price-Item':
								print_item_size(find_xml_value($item_xml, 'size'), 'gdl-price-item');
								print_price_item($item_xml);
								break;
							case 'Portfolio' :
								print_item_size(find_xml_value($item_xml, 'size'), 'wrapper gdl-portfolio-item');
								print_portfolio($item_xml);
								break;
							case 'Slider' : 
								print_item_size(find_xml_value($item_xml, 'size'));
								print_slider_item($item_xml);
								break;
							case 'Search-Course' :
								print_item_size(find_xml_value($item_xml, 'size'));
								print_search_course($item_xml);
								break;							
							case 'Stunning-Text' :
								print_item_size(find_xml_value($item_xml, 'size'));
								print_stunning_text($item_xml);
								break;
							case 'Tab' :
								print_item_size(find_xml_value($item_xml, 'size'));
								print_tab_item($item_xml);
								break;
							case 'Testimonial' :
								print_item_size(find_xml_value($item_xml, 'size'), 'wrapper');
								print_testimonial($item_xml);
								break;
							case 'Toggle-Box' :
								print_item_size(find_xml_value($item_xml, 'size'));
								print_toggle_box_item($item_xml);
								break;
							default: 
								print_item_size(find_xml_value($item_xml, 'size'));
								break;
						}
						echo "</div>";
					}
				}
				echo "</div>"; // end of gdl-page-item
				
				get_sidebar('left');		
				
				echo "</div>"; // gdl-page-float-left	
				
				get_sidebar('right');
				
			?>
		
			<?php
				global $only_front_footer_slideshow;
				global $enable_footer_slideshow;
				
				wp_reset_query();
				
				if( $enable_footer_slideshow == 'enable' && ($only_front_footer_slideshow == 'disable' || is_front_page()) ){
				
					// Footer slide show
					print_item_size('element1-1', 'wrapper mb0');
					
					$header = get_option(THEME_SHORT_NAME.'_footer_slideshow_header_title', '');
					if(!empty($header)){
						$dripcap_id = get_option(THEME_SHORT_NAME.'_footer_slideshow_header_icon', '');
						$dropcap_image = wp_get_attachment_image_src( $dripcap_id , 'full' );
						if( !empty( $dropcap_image ) ){
							echo '<div class="gdl-header-dropcap ml10">';
							echo '<div class="gdl-header-dropcap-center">';
							echo '<img src="' . $dropcap_image[0] . '" class="no-preload" alt="" />';
							echo '</div>';
							echo '</div>';	
						}
						echo '<h3 class="gallery-header-title title-color gdl-title">' . $header . '</h3>';
						echo '<div class="clear"></div>';
					}				
					
					$gallery_page = get_option(THEME_SHORT_NAME.'_footer_gallery_slideshow', '');
					$gallery_post = get_posts(array('post_type' => 'gallery', 'name'=>$gallery_page, 'numberposts'=> 1));
					$slideshow_xml_string = get_post_meta($gallery_post[0]->ID,'post-option-gallery-xml', true);
					$slideshow_xml_dom = new DOMDocument();
					echo '<div class="sixteen columns">';
					if( !empty( $slideshow_xml_string ) ){
						$slideshow_xml_dom->loadXML($slideshow_xml_string);	
						
						echo '<div class="gallery-es-carousel gdl-divider">';
						echo '<div class="es-carousel">';							
						echo '<ul>';	
						foreach( $slideshow_xml_dom->documentElement->childNodes as $slideshow ){
							$title = find_xml_value($slideshow, 'title');
							$link_type = find_xml_value($slideshow, 'linktype');				
							$image_url = wp_get_attachment_image_src(find_xml_value($slideshow, 'image'), '135x110');
							$alt_text = get_post_meta(find_xml_value($slideshow, 'image') , '_wp_attachment_image_alt', true);		
							
							echo '<li class="slideshow-image">';
							if( $link_type == 'Link to URL' ){
								$link = find_xml_value( $slideshow, 'link');	
								echo '<a href="' . $link . '">';
								echo '<img class="gdl-gallery-image" src="' . $image_url[0] . '" alt="' . $alt_text . '" />';
								echo '</a>';
							}else if( $link_type == 'Lightbox' ){
								$image_full = wp_get_attachment_image_src(find_xml_value($slideshow, 'image'), 'full');
								echo '<a data-rel="prettyPhoto[gdcSlideshow]" href="' . $image_full[0] . '"  title="">';
								echo '<img class="gdl-gallery-image" src="' . $image_url[0] . '" alt="' . $alt_text . '" />';
								echo '</a>';
							}else{
								echo '<img class="gdl-gallery-image" src="' . $image_url[0] . '" alt="' . $alt_text . '" />';
							}
							echo '</li>';
						}
						echo '</ul>';
						echo "</div>"; // es-carousel
						echo '<div class="blog-item-slideshow-nav-left gdl-hover"></div>';	
						echo '<div class="blog-item-slideshow-nav-right gdl-hover"></div>';	
						echo "</div>"; // gallery-es-carousel	
					}		
					echo '<div class="clear"></div>';
					echo '</div>'; // sixteen columns
					echo '<div class="clear"></div>';
					echo '</div>'; // print-item-size
					
				} // if condition
			?>
			
			<br class="clear">
		</div>
	</div> <!-- content-wrapper -->
	
<?php get_footer(); ?>