<?php
	/*	
	*	Goodlayers Custom Style File (style-custom.php)
	*	---------------------------------------------------------------------
	*	This file fetch all style options in admin panel to generate the css
	*	to attach to header.php file
	*	---------------------------------------------------------------------
	*/

	header("Content-type: text/css;");
	
	$current_url = dirname(__FILE__);
	$wp_content_pos = strpos($current_url, 'wp-content');
	$wp_content = substr($current_url, 0, $wp_content_pos);

	require_once($wp_content . 'wp-load.php');
	
?>
/* Background
   ================================= */
<?php 
	$background_style = get_option(THEME_SHORT_NAME.'_background_style', 'Pattern');
	if($background_style == 'Pattern'){
		$background_pattern = get_option(THEME_SHORT_NAME.'_background_pattern', '1');
		?>
		
		html{ 
			background-image: url('<?php echo GOODLAYERS_PATH; ?>/images/pattern/pattern-<?php echo $background_pattern; ?>.png');
			background-repeat: repeat; 
		}
		
		<?php
	}
?>
   
/* Logo
   ================================= */
.logo-wrapper{ 
	margin-top: <?php echo get_option(THEME_SHORT_NAME . "_logo_top_margin", '16'); ?>px;
	margin-left: <?php echo get_option(THEME_SHORT_NAME . "_logo_left_margin", '0'); ?>px;
	margin-bottom: <?php echo get_option(THEME_SHORT_NAME . "_logo_bottom_margin", '36'); ?>px;
}  
  
/* Font Size
   ================================= */
h1{
	font-size: <?php echo get_option(THEME_SHORT_NAME . "_h1_size", '30'); ?>px;
}
h2{
	font-size: <?php echo get_option(THEME_SHORT_NAME . "_h2_size", '25'); ?>px;
}
h3{
	font-size: <?php echo get_option(THEME_SHORT_NAME . "_h3_size", '20'); ?>px;
}
h4{
	font-size: <?php echo get_option(THEME_SHORT_NAME . "_h4_size", '18'); ?>px;
}
h5{
	font-size: <?php echo get_option(THEME_SHORT_NAME . "_h5_size", '16'); ?>px;
}
h6{
	font-size: <?php echo get_option(THEME_SHORT_NAME . "_h6_size", '15'); ?>px;
}

/* Element Color
   ================================= */
   
html{
	background-color: <?php echo get_option(THEME_SHORT_NAME . "_body_background", '#f5f5f5'); ?>;
}
<?php $gdl_content_wrapper_shadow = get_option(THEME_SHORT_NAME . "_content_container_shadow_color", '#e5e5e5'); ?>
div.content-wrapper{
	background-color: <?php echo get_option(THEME_SHORT_NAME . "_content_background", '#ffffff'); ?>;
	
	-moz-box-shadow: 0px 0px 4px <?php echo $gdl_content_wrapper_shadow; ?>;
	-webkit-box-shadow: 0px 0px 4px <?php echo $gdl_content_wrapper_shadow; ?>;
	box-shadow: 0px 0px 4px <?php echo $gdl_content_wrapper_shadow; ?>; 	
}
div.content-bottom-gimmick{
	background-color: <?php echo get_option(THEME_SHORT_NAME . "_content_bottom_line", '#d2d2d2'); ?>;
}
div.divider{
	border-bottom: 1px solid <?php echo get_option(THEME_SHORT_NAME . "_divider_line", '#ececec'); ?>;
}
div.gdl-header-dropcap{
	background-color: <?php echo get_option(THEME_SHORT_NAME . "_header_icon_background", '#64A5C4'); ?>;
}
div.under-slider-right{
	background-color: <?php echo get_option(THEME_SHORT_NAME . "_bottom_slider_right_bg", '#3f93b9'); ?>;
}


/* Font Family 
  ================================= */
body{
	font-family: <?php echo substr(get_option(THEME_SHORT_NAME . "_content_font"), 2); ?>;
}
h1, h2, h3, h4, h5, h6, .gdl-title{
	font-family: <?php echo substr(get_option(THEME_SHORT_NAME . "_header_font"), 2); ?>;
}
h1.stunning-text-title{
	font-family: <?php echo substr(get_option(THEME_SHORT_NAME . "_stunning_text_font"), 2); ?>;
	color: <?php echo get_option(THEME_SHORT_NAME . "_stunning_text_title_color", '#333333'); ?>;
}
.stunning-text-caption{
	color: <?php echo get_option(THEME_SHORT_NAME . "_stunning_text_caption_color", '#666666'); ?>;
}
  
/* Font Color
   ================================= */
body{
	color: <?php echo get_option(THEME_SHORT_NAME . "_content_color", '#666666'); ?> !important;
}
a{
	color: <?php echo get_option(THEME_SHORT_NAME . "_link_color", '#2a84ae'); ?>;
}
.footer-wrapper a{
	color: <?php echo get_option(THEME_SHORT_NAME . "_footer_link_color", '#475d68'); ?>;
}
.gdl-link-title{
	color: <?php echo get_option(THEME_SHORT_NAME . "_link_color", '#ef7f2c'); ?> !important;
}
a:hover{
	color: <?php echo get_option(THEME_SHORT_NAME . "_link_hover_color", '#475d68'); ?>;
}
.footer-wrapper a:hover{
	color: <?php echo get_option(THEME_SHORT_NAME . "_footer_link_hover_color", '#5e737d'); ?>;
}
.gdl-slider-title{
	color: <?php echo get_option(THEME_SHORT_NAME . "_slider_title_color", '#ffffff'); ?> !important;
}  
.gdl-slider-caption, .nivo-caption{
	color: <?php echo get_option(THEME_SHORT_NAME . "_slider_caption_color", '#bbbbbb'); ?> !important;
}  
h1, h2, h3, h4, h5, h6, .title-color{
	color: <?php echo get_option(THEME_SHORT_NAME.'_title_color', '#494949'); ?>;
}
h1.gdl-page-title{
	border-bottom: 1px solid <?php echo get_option(THEME_SHORT_NAME.'_header_title_bottom_line', '#65A2BE'); ?> !important;
}
.gdl-page-caption{
	color: <?php echo get_option(THEME_SHORT_NAME.'_caption_color', '#9a9a9a'); ?>;
}
.sidebar-title-color, custom-sidebar-title{
	color: <?php echo get_option(THEME_SHORT_NAME.'_sidebar_title_color', '#494949'); ?> !important;
}
div.right-sidebar-wrapper .custom-sidebar.gdl-divider .custom-sidebar-title,
div.left-sidebar-wrapper .custom-sidebar.gdl-divider .custom-sidebar-title{ 
	background-color: <?php echo get_option(THEME_SHORT_NAME.'_sidebar_title_background', '#f5f5f5'); ?> !important; 
	border-top: 1px solid <?php echo get_option(THEME_SHORT_NAME.'_sidebar_title_top_border', '#64a5c4'); ?> !important;
}

/* Post/Port Color
   ================================= */
   
.port-title-color, .port-title-color a{
	color: <?php echo get_option(THEME_SHORT_NAME.'_port_title_color', '#2A84AE'); ?> !important;
}
.port-title-color a:hover{
	color: <?php echo get_option(THEME_SHORT_NAME.'_port_title_hover_color', '#2A84AE'); ?> !important;
}
.post-title-color, .post-title-color a{
	color: <?php echo get_option(THEME_SHORT_NAME.'_post_title_color', '#338db7'); ?> !important;
}
.post-title-color a:hover{
	color: <?php echo get_option(THEME_SHORT_NAME.'_post_title_hover_color', '#338db7'); ?> !important;
}
.post-widget-title-color{
	color: <?php echo get_option(THEME_SHORT_NAME.'_post_widget_title_color', '#338db7'); ?> !important;
}
.post-widget-info-color{
	color: <?php echo get_option(THEME_SHORT_NAME.'_post_widget_info_color', '#9e9e9e'); ?> !important;
}
.post-info-color, div.custom-sidebar #twitter_update_list{
	color: <?php echo get_option(THEME_SHORT_NAME.'_post_info_color', '#aaaaaa'); ?> !important;
}
div.pagination a{ background-color: <?php echo get_option(THEME_SHORT_NAME.'_pagination_normal_state', '#f5f5f5'); ?>; }

.about-author-wrapper{
	background-color: <?php echo get_option(THEME_SHORT_NAME.'_post_about_author_color', '#f9f9f9'); ?> !important;
}

/* Stunning Text
   ================================= */
.stunning-text-button{
	color: <?php echo get_option(THEME_SHORT_NAME.'_stunning_text_button_color', '#ffffff'); ?> !important;
	<?php $stunning_text_button_color = get_option(THEME_SHORT_NAME.'_stunning_text_button_background', '#2a84ae'); ?> 
	background-color: <?php echo $stunning_text_button_color ?> !important;
	border: 1px solid <?php echo $stunning_text_button_color ?> !important;
}

/* Footer Color
   ================================= */
div.footer-wrapper-gimmick{
	background: <?php echo get_option(THEME_SHORT_NAME . "_footer_top_bar", '#cfcfcf'); ?>;
}
.footer-widget-wrapper .custom-sidebar-title{ 
	color: <?php echo get_option(THEME_SHORT_NAME.'_footer_title_color', '#ffffff'); ?> !important;
}
.footer-blank-space{
	<?php
		$footer_top_margin = (int) get_option(THEME_SHORT_NAME.'_footer_top_margin', '-100');
		$blank_space_height = ( $footer_top_margin >= 0 )? 0: abs($footer_top_margin);
	?>	
	height: <?php echo $blank_space_height; ?>px;
}
.content-wrapper{
	min-height: <?php echo get_option(THEME_SHORT_NAME.'_container_min_height', '200'); ?>px;
}
.footer-wrapper{ 
	margin-top: <?php echo $footer_top_margin; ?>px;
	background-color: <?php echo get_option(THEME_SHORT_NAME.'_footer_background', '#64a5c4'); ?> !important;
}
.footer-wrapper .gdl-divider,
.footer-wrapper .custom-sidebar.gdl-divider div,
.footer-wrapper .custom-sidebar.gdl-divider ul li{
	border-color: <?php echo get_option(THEME_SHORT_NAME.'_footer_divider_color', '#5f92ab'); ?> !important;
}
.footer-wrapper, .footer-wrapper table th{
	color: <?php echo get_option(THEME_SHORT_NAME.'_footer_content_color', '#ffffff'); ?> !important;
}
.footer-wrapper .post-info-color, .footer-wrapper div.custom-sidebar #twitter_update_list{
	color: <?php echo get_option(THEME_SHORT_NAME.'_footer_content_info_color', '#d0e4ed'); ?> !important;
}
div.footer-wrapper div.contact-form-wrapper input[type="text"], 
div.footer-wrapper div.contact-form-wrapper input[type="password"], 
div.footer-wrapper div.contact-form-wrapper textarea, 
div.footer-wrapper div.custom-sidebar #search-text input[type="text"], 
div.footer-wrapper div.custom-sidebar .contact-widget-whole input, 
div.footer-wrapper div.custom-sidebar .contact-widget-whole textarea {
	color: <?php echo get_option(THEME_SHORT_NAME.'_footer_input_text', '#ffffff'); ?> !important; 
	background-color: <?php echo get_option(THEME_SHORT_NAME.'_footer_input_background', '#6392ab'); ?> !important;
	border: 1px solid <?php echo get_option(THEME_SHORT_NAME.'_footer_input_border', '#60737d'); ?> !important;
}
div.footer-wrapper a.button, div.footer-wrapper button, div.footer-wrapper button:hover {
	color: <?php echo get_option(THEME_SHORT_NAME.'_footer_button_text', '#454545'); ?> !important; 
	background-color: <?php echo get_option(THEME_SHORT_NAME.'_footer_button_color', '#ffffff'); ?> !important;
}
div.copyright-wrapper{ 
	color: <?php echo get_option(THEME_SHORT_NAME.'_copyright_text', '#ffffff'); ?> !important;
}
div.footer-wrapper div.custom-sidebar .recent-post-widget-thumbnail {  
	background-color: <?php echo get_option(THEME_SHORT_NAME.'_footer_frame_background', '#527485'); ?>; 
	border-color: <?php echo get_option(THEME_SHORT_NAME.'_footer_frame_border', '#385766'); ?>;
}

/* Divider Color
   ================================= */
.scroll-top{ 
	color: <?php echo get_option(THEME_SHORT_NAME.'_back_to_top_text_color', '#7c7c7c'); ?> !important;
}
.gdl-divider,
.custom-sidebar.gdl-divider div,
.custom-sidebar.gdl-divider .custom-sidebar-title,
.custom-sidebar.gdl-divider ul li{
	border-color: <?php echo get_option(THEME_SHORT_NAME . "_divider_line", '#ececec'); ?> !important;
}

/* Table */
table th{
	color: <?php echo get_option(THEME_SHORT_NAME.'_table_text_title', '#666666'); ?>;
	background-color: <?php echo get_option(THEME_SHORT_NAME.'_table_title_background', '#f7f7f7'); ?>;
}
table, table tr, table tr td, table tr th{
	border-color: <?php echo get_option(THEME_SHORT_NAME . "_table_border", '#e5e5e5'); ?>;
}
table.course-table, table.course-table tr, table.course-table tr td, table.course-table tr th{
	border-color: <?php echo get_option(THEME_SHORT_NAME . "_course_table_border", '#e5e5e5'); ?>;
}
table.course-table th{
	color: <?php echo get_option(THEME_SHORT_NAME.'_course_table_text_title', '#404040'); ?>;
	background-color: <?php echo get_option(THEME_SHORT_NAME.'_course_table_title_background', '#f2f2f2'); ?>;
}
table.course-table tr.odd{
	color: <?php echo get_option(THEME_SHORT_NAME.'_course_odd_row_color', '#9e9e9e'); ?>;
	background-color: <?php echo get_option(THEME_SHORT_NAME.'_course_odd_row_background', '#ffffff'); ?>;
}
table.course-table tr.odd a, table.course-table tr.odd a:hover{
	color: <?php echo get_option(THEME_SHORT_NAME.'_course_odd_row_link_color', '#9e9e9e'); ?>;
}
table.course-table tr.even{
	color: <?php echo get_option(THEME_SHORT_NAME.'_course_even_row_color', '#9e9e9e'); ?>;
	background-color: <?php echo get_option(THEME_SHORT_NAME.'_course_even_row_background', '#f9f9f9'); ?>;
}
table.course-table tr.even a, table.course-table tr.even a:hover{
	color: <?php echo get_option(THEME_SHORT_NAME.'_course_even_row_link_color', '#9e9e9e'); ?>;
}

/* Testimonial Color
   ================================= */
.testimonial-content{
	color: <?php echo get_option(THEME_SHORT_NAME.'_testimonial_text', '#848484'); ?> !important;
}
.testimonial-author-name{
	color: <?php echo get_option(THEME_SHORT_NAME.'_testimonial_author', '#494949'); ?> !important;
}
.testimonial-author-position{
	color: <?php echo get_option(THEME_SHORT_NAME.'_testimonial_position', '#8d8d8d'); ?> !important;
}

/* Tabs Color
   ================================= */
<?php $gdl_tab_border = get_option(THEME_SHORT_NAME.'_tab_border_color', '#dddddd'); ?>
ul.tabs{
	border-color: <?php echo $gdl_tab_border; ?> !important;
}
ul.tabs li a {
	color: <?php echo get_option(THEME_SHORT_NAME.'_tab_text_color', '#666666'); ?> !important;
	background-color: <?php echo get_option(THEME_SHORT_NAME.'_tab_background_color', '#f5f5f5'); ?> !important;
	border-color: <?php echo $gdl_tab_border; ?> !important;
}
ul.tabs li a.active {
	color: <?php echo get_option(THEME_SHORT_NAME.'_tab_active_text_color', '#111111'); ?> !important;
	background-color: <?php echo get_option(THEME_SHORT_NAME.'_tab_active_background_color', '#fff'); ?> !important;
}

/* Navigation Color
   ================================= */
<?php if(get_option(THEME_SHORT_NAME.'_main_navigation_gradient', 'enable') == 'enable'){ ?>
div.navigation-wrapper, .sf-menu li{
	background: url('<?php echo GOODLAYERS_PATH; ?>/images/navigation-shadow.png') repeat-x; 
}
<?php } ?>
.top-navigation-wrapper{
	<?php $gdl_top_navigation_text_color = get_option(THEME_SHORT_NAME.'_top_navigation_text', '#7c7c7c'); ?>
	color: <?php echo $gdl_top_navigation_text_color; ?> !important;
}
.top-navigation-left li a{ 
	<?php $gdl_top_navigation_text_color = '#' . hexDarker(substr($gdl_top_navigation_text_color, 1)); ?>
	border-right: 1px solid <?php echo $gdl_top_navigation_text_color; ?> !important;
}
.navigation-wrapper{
	<?php $gdl_border_top_bottom = get_option(THEME_SHORT_NAME.'_main_navigation_border_top_bottom', '#5391ae'); ?>
	border-top: 1px solid <?php echo $gdl_border_top_bottom; ?> !important;
	border-bottom: 1px solid <?php echo $gdl_border_top_bottom; ?> !important;
}
.navigation-wrapper .sf-menu ul,
.navigation-wrapper .sf-menu ul li{
	border-color: <?php echo get_option(THEME_SHORT_NAME.'_sub_navigation_border', '#e9e9e9'); ?> !important;
}
.navigation-wrapper, .sf-menu li{
	background-color: <?php echo get_option(THEME_SHORT_NAME.'_main_navigation_background', '#66a3bf'); ?> !important;
}
.sf-menu li li{
	
	background-color: <?php echo get_option(THEME_SHORT_NAME.'_sub_navigation_background', '#ffffff'); ?> !important;
}
.navigation-wrapper .sf-menu li a{
	<?php $gdl_current_navi_text = get_option(THEME_SHORT_NAME.'_main_navigation_text', '#ffffff'); ?>
	color: <?php echo $gdl_current_navi_text; ?> !important;
	
	<?php
		$gdl_nav_border_right = get_option(THEME_SHORT_NAME.'_main_navigation_border_right', '#7eadc3');
		$gdl_nav_border_left = get_option(THEME_SHORT_NAME.'_main_navigation_border_left', '#4c87a1');
	?>
	border-right: 1px solid <?php echo $gdl_nav_border_right; ?> !important;
	border-left: 1px solid <?php echo $gdl_nav_border_left; ?> !important;
}
.navigation-wrapper .sf-menu li li a{
	<?php $gdl_current_navi_text = get_option(THEME_SHORT_NAME.'_sub_navigation_text', '#999999'); ?>
	color: <?php echo $gdl_current_navi_text; ?> !important;
}
.navigation-wrapper #menu-main.sf-menu{
	border-right: 1px solid <?php echo $gdl_nav_border_left; ?> !important;
}
.navigation-wrapper .sf-menu a:hover, 
.navigation-wrapper .sf-menu .current-menu-item a:hover{
	color: <?php echo get_option(THEME_SHORT_NAME.'_main_navigation_text_hover', '#3d3d3d'); ?> !important;
} 
.navigation-wrapper .sf-menu li li a:hover, 
.navigation-wrapper .sf-menu .current-menu-item li a:hover,
.navigation-wrapper .sf-menu .current-menu-item li li a:hover{
	color: <?php echo get_option(THEME_SHORT_NAME.'_sub_navigation_text_hover', '#666666'); ?> !important;
} 
.navigation-wrapper .sf-menu .current-menu-item a {
	color: <?php echo get_option(THEME_SHORT_NAME.'_main_navigation_text_current', '#3d3d3d'); ?> !important;
 }
.navigation-wrapper .sf-menu ul .current-menu-item a {
color: <?php echo get_option(THEME_SHORT_NAME.'_sub_navigation_text_current', '#999999'); ?> !important;
}
.navigation-wrapper .sf-menu .current-menu-item li a {
 	color: <?php echo $gdl_current_navi_text; ?> !important;
 }
 
 /* Search */
.search-wrapper #search-text input[type="text"]{
	background-color: <?php echo get_option(THEME_SHORT_NAME.'_search_box_background', '#ffffff'); ?> !important;
	color: <?php echo get_option(THEME_SHORT_NAME.'_search_box_text', '#b7b7b7'); ?> !important;
	border-color: <?php echo get_option(THEME_SHORT_NAME.'_search_box_border', '#e3e3e3'); ?> !important;
}
div.gdl-combobox-text,
#courseform input[type="text"]{
	background-color: <?php echo get_option(THEME_SHORT_NAME.'_search_course_box_background', '#ffffff'); ?> !important;
	color: <?php echo get_option(THEME_SHORT_NAME.'_search_course_box_text', '#b7b7b7'); ?> !important;
}
#courseform{ 
	background-color: <?php echo get_option(THEME_SHORT_NAME.'_search_course_background', '#f7f7f7'); ?> !important; 
}
div.gdl-combobox-button,
div.gdl-combobox-text,
#courseform input[type="text"],
div.search-attribute.gdl-combobox{
	border-color: <?php echo get_option(THEME_SHORT_NAME.'_search_course_box_border', '#e3e3e3'); ?> !important;
}
div.gdl-combobox-button,
#courseform input[type="submit"]{
	background-color: <?php echo get_option(THEME_SHORT_NAME.'_search_course_button_background', '#5996b2'); ?> !important;
	color: <?php echo get_option(THEME_SHORT_NAME.'_search_course_button_text_color', '#ffffff'); ?> !important;
}
div.search-wrapper input[type="submit"]{
	background-color: <?php echo get_option(THEME_SHORT_NAME.'_search_button_background', '#5996b2'); ?> !important;
	color: <?php echo get_option(THEME_SHORT_NAME.'_search_button_text_color', '#ffffff'); ?> !important;
}
.search-wrapper{
	margin-top: <?php echo get_option(THEME_SHORT_NAME.'_search_top_margin', '28'); ?>px;
}

/* Button Color
   ================================= */
<?php
	$gdl_button_color = get_option(THEME_SHORT_NAME.'_button_background_color', '#f1f1f1');
	$gdl_button_border = get_option(THEME_SHORT_NAME.'_button_border_color', '#dedede');
	$gdl_button_text = get_option(THEME_SHORT_NAME.'_button_text_color', '#7a7a7a');
	$gdl_button_hover = get_option(THEME_SHORT_NAME.'_button_text_hover_color', '#7a7a7a');
?>
a.button, button, input[type="submit"], input[type="reset"], input[type="button"],
a.gdl-button{
	background-color: <?php echo $gdl_button_color; ?>;
	color: <?php echo $gdl_button_text; ?>;
	border: 1px solid <?php echo $gdl_button_border; ?>
}

a.button:hover, button:hover, input[type="submit"]:hover, input[type="reset"]:hover, input[type="button"]:hover,
a.gdl-button:hover{
	color: <?php echo $gdl_button_hover; ?>;
}
   
/* Price Item
   ================================= */   
div.gdl-price-item .gdl-divider{ 
	border-color: <?php echo get_option(THEME_SHORT_NAME.'_price_item_border', '#ececec'); ?> !important;
}
div.gdl-price-item .price-title{
	background-color: <?php echo get_option(THEME_SHORT_NAME.'_price_item_price_title_background', '#e9e9e9'); ?> !important;
	color: <?php echo get_option(THEME_SHORT_NAME.'_price_item_price_title_color', '#3a3a3a'); ?> !important;
}
div.gdl-price-item .price-item.active .price-title{ 
	background-color: <?php echo get_option(THEME_SHORT_NAME.'_price_item_best_price_title_background', '#5f5f5f'); ?> !important;
	color: <?php echo get_option(THEME_SHORT_NAME.'_price_item_best_price_title_color', '#ffffff'); ?> !important;
}
div.gdl-price-item .price-tag{
	color: <?php echo get_option(THEME_SHORT_NAME.'_price_item_price_color', '#3a3a3a'); ?> !important;
}
div.gdl-price-item .price-item.active .price-tag{
	<?php $gdl_best_price_color = get_option(THEME_SHORT_NAME.'_price_item_best_price_color', '#66a3bf'); ?>
	color: <?php echo $gdl_best_price_color; ?> !important;
}
div.gdl-price-item .price-item.active{
	border-top: 1px solid <?php echo $gdl_best_price_color; ?> !important;
}
/* Contact Form
   ================================= */
<?php
	$gdl_contact_form_frame = get_option(THEME_SHORT_NAME.'_contact_form_frame_color', '#f8f8f8');
	$gdl_contact_form_shadow = get_option(THEME_SHORT_NAME.'_contact_form_inner_shadow', '#ececec');
 ?>
div.contact-form-wrapper input[type="text"], 
div.contact-form-wrapper input[type="password"],
div.contact-form-wrapper textarea,
div.custom-sidebar #search-text input[type="text"],
div.custom-sidebar .contact-widget-whole input, 
div.comment-wrapper input[type="text"], input[type="password"], div.comment-wrapper textarea,
div.custom-sidebar .contact-widget-whole textarea,
span.wpcf7-form-control-wrap input[type="text"], 
span.wpcf7-form-control-wrap input[type="password"], 
span.wpcf7-form-control-wrap textarea{
	color: <?php echo get_option(THEME_SHORT_NAME.'_contact_form_text_color', '#888888'); ?>;
	background-color: <?php echo get_option(THEME_SHORT_NAME.'_contact_form_background_color', '#fff'); ?>;
	border: 1px solid <?php echo get_option(THEME_SHORT_NAME.'_contact_form_border_color', '#cfcfcf'); ?>;

	-webkit-box-shadow: <?php echo $gdl_contact_form_shadow; ?> 0px 1px 4px inset, <?php echo $gdl_contact_form_frame; ?> -5px -5px 0px 0px, <?php echo $gdl_contact_form_frame; ?> 5px 5px 0px 0px, <?php echo $gdl_contact_form_frame; ?> 5px 0px 0px 0px, <?php echo $gdl_contact_form_frame; ?> 0px 5px 0px 0px, <?php echo $gdl_contact_form_frame; ?> 5px -5px 0px 0px, <?php echo $gdl_contact_form_frame; ?> -5px 5px 0px 0px;
	box-shadow: <?php echo $gdl_contact_form_shadow; ?> 0px 1px 4px inset, <?php echo $gdl_contact_form_frame; ?> -5px -5px 0px 0px, <?php echo $gdl_contact_form_frame; ?> 5px 5px 0px 0px, <?php echo $gdl_contact_form_frame; ?> 5px 0px 0px 0px, <?php echo $gdl_contact_form_frame; ?> 0px 5px 0px 0px, <?php echo $gdl_contact_form_frame; ?> 5px -5px 0px 0px, <?php echo $gdl_contact_form_frame; ?> -5px 5px 0px 0px;
}

/* Icon Type (dark/light)
   ================================= */
<?php global $gdl_icon_type; ?>

div.single-port-next-nav .right-arrow{ background: url('<?php echo GOODLAYERS_PATH; ?>/images/icon/<?php echo $gdl_icon_type; ?>/arrow-right.png') no-repeat; }
div.single-port-prev-nav .left-arrow{ background: url('<?php echo GOODLAYERS_PATH; ?>/images/icon/<?php echo $gdl_icon_type; ?>/arrow-left.png') no-repeat; }

div.single-thumbnail-author,
div.archive-wrapper .blog-item .blog-thumbnail-author,
div.blog-item-holder .blog-item2 .blog-thumbnail-author{ background: url('<?php echo GOODLAYERS_PATH; ?>/images/icon/<?php echo $gdl_icon_type; ?>/author.png') no-repeat 0px 0px; }

div.single-thumbnail-date,
div.custom-sidebar .recent-post-widget-date,
div.archive-wrapper .blog-item .blog-thumbnail-date,
div.blog-item-holder .blog-item1 .blog-thumbnail-date,
div.blog-item-holder .blog-item2 .blog-thumbnail-date{ background: url('<?php echo GOODLAYERS_PATH; ?>/images/icon/<?php echo $gdl_icon_type; ?>/calendar.png') no-repeat 0px 0px; }

div.single-thumbnail-comment,
div.archive-wrapper .blog-item .blog-thumbnail-comment,
div.blog-item-holder .blog-item1 .blog-thumbnail-comment,
div.blog-item-holder .blog-item2 .blog-thumbnail-comment,
div.custom-sidebar .recent-post-widget-comment-num{ background: url('<?php echo GOODLAYERS_PATH; ?>/images/icon/<?php echo $gdl_icon_type; ?>/comment.png') no-repeat 0px 0px; }

div.single-thumbnail-tag,
div.archive-wrapper .blog-item .blog-thumbnail-tag,
div.blog-item-holder .blog-item2 .blog-thumbnail-tag{ background: url('<?php echo GOODLAYERS_PATH; ?>/images/icon/<?php echo $gdl_icon_type; ?>/tag.png') no-repeat; }

div.single-port-visit-website{ background: url('<?php echo GOODLAYERS_PATH; ?>/images/icon/<?php echo $gdl_icon_type; ?>/link-small.png') no-repeat 0px 2px; }

span.accordion-head-image.active,
span.toggle-box-head-image.active{ background: url('<?php echo GOODLAYERS_PATH; ?>/images/icon/<?php echo $gdl_icon_type; ?>/minus-24px.png'); }
span.accordion-head-image,
span.toggle-box-head-image{ background: url('<?php echo GOODLAYERS_PATH; ?>/images/icon/<?php echo $gdl_icon_type; ?>/plus-24px.png'); }

div.jcarousellite-nav .prev, 
div.jcarousellite-nav .next{ background-image: url('<?php echo GOODLAYERS_PATH; ?>/images/icon/<?php echo $gdl_icon_type; ?>/navigation-20px.png'); } 

div.blog-item-slideshow-nav-right,
div.blog-item-slideshow-nav-left{ background-image: url('<?php echo GOODLAYERS_PATH; ?>/images/icon/<?php echo $gdl_icon_type; ?>/slideshow-navigation.png'); } 

div.testimonial-icon{ background: url("<?php echo GOODLAYERS_PATH; ?>/images/icon/<?php echo $gdl_icon_type; ?>/quotes-18px.png"); }

div.custom-sidebar ul li{ background: url('<?php echo GOODLAYERS_PATH; ?>/images/icon/<?php echo $gdl_icon_type; ?>/arrow4.png') no-repeat 0px 14px; }

div.gdl-content-slider div.anythingSlider .anythingControls ul a{
	background: url('<?php echo GOODLAYERS_PATH; ?>/images/icon/<?php echo $gdl_icon_type; ?>/content-slider-nav.png'); 
}

ul.twitter-shortcode li{
	background: url('<?php echo GOODLAYERS_PATH; ?>/images/icon/<?php echo $gdl_icon_type; ?>/twitter-shortcode.png') no-repeat 0px 50%; 
}
div.twitter-shortcode-wrapper .jcarousellite-nav .prev, 
div.twitter-shortcode-wrapper .jcarousellite-nav .next {
	background-image: url('<?php echo GOODLAYERS_PATH; ?>/images/icon/<?php echo $gdl_icon_type; ?>/twitter-shortcode-nav.png');
}

div.custom-sidebar #searchsubmit { background: url('<?php echo GOODLAYERS_PATH; ?>/images/icon/<?php echo $gdl_icon_type; ?>/find-17px.png') no-repeat center; }

/* Footer Icon Type
   ================================= */
<?php global $gdl_footer_icon_type; ?>
div.footer-wrapper div.custom-sidebar ul li { background: url('<?php echo GOODLAYERS_PATH; ?>/images/icon/<?php echo $gdl_footer_icon_type; ?>/arrow4.png') no-repeat 0px 14px; }
div.footer-wrapper div.custom-sidebar #searchsubmit { background: url('<?php echo GOODLAYERS_PATH; ?>/images/icon/<?php echo $gdl_footer_icon_type; ?>/find-17px.png') no-repeat center; }
div.footer-wrapper div.custom-sidebar .recent-post-widget-comment-num { background: url('<?php echo GOODLAYERS_PATH; ?>/images/icon/<?php echo $gdl_footer_icon_type; ?>/comment.png') no-repeat 0px 1px; }
div.footer-wrapper div.custom-sidebar .recent-post-widget-date{ background: url('<?php echo GOODLAYERS_PATH; ?>/images/icon/<?php echo $gdl_footer_icon_type; ?>/calendar.png') no-repeat 0px 1px; }

/* Elements Shadow
   ================================= */
<?php $gdl_element_shadow = get_option(THEME_SHORT_NAME.'_elements_shadow','#ececec'); ?>

a.button, button, input[type="submit"], input[type="reset"], input[type="button"], 
a.gdl-button{
	-moz-box-shadow: 1px 1px 3px <?php echo $gdl_element_shadow; ?>;
	-webkit-box-shadow: 1px 1px 3px <?php echo $gdl_element_shadow; ?>;
	box-shadow: 1px 1px 3px <?php echo $gdl_element_shadow; ?>; 
}

div.gdl-price-item .price-item.active{ 
	-moz-box-shadow: 0px 0px 3px <?php echo $gdl_element_shadow; ?>;
	-webkit-box-shadow: 0px 0px 3px <?php echo $gdl_element_shadow; ?>;
	box-shadow: 0px 0px 3px <?php echo $gdl_element_shadow; ?>;
}

div.single-course-header-title{ margin-bottom: 20px; }
div.single-course-content{ margin-top: 22px; }
div.personnal-small .personnal-title{ text-align: center; padding-top: 10px; font-size: 14px; }
div.personnal-full .personnal-title{ padding-top: 4px; padding-bottom: 6px; font-size: 14px; }
div.personnal-full .personnal-thumbnail-image{ float: left; margin-right: 15px; }
div.personnal-full .personnal-content{ overflow: hidden; }
div.top-navigation-wrapper{ min-height: 11px; }
div.custom-sidebar #searchsubmit { text-indent: -10000px; }

div.gdl-header-dropcap{ position: relative; }
div.gdl-header-dropcap-center{ position: absolute; left: 50%; top: 50%; }
div.gdl-header-dropcap-center img{ margin-top: -50%; margin-left: -50%; display: block; } 