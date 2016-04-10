<form method="get" id="searchform" action="<?php  echo home_url(); ?>/">
	<div id="search-text">
		<?php 	
			$default_value = __("Type your keywords...", "gdl_front_end");
			$search_value = get_search_query(); 
			$search_value = (empty($search_value))? $default_value: $search_value;
		?>
		<input type="text" value="<?php echo $search_value; ?>" name="s" id="s" autocomplete="off" data-default="<?php echo $default_value; ?>" />
	</div>
	<input type="submit" id="searchsubmit" value="<?php _e("Search","gdl_front_end"); ?>"/>
	<br class="clear">
</form>
