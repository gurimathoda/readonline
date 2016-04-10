<div class="search-staff-members">
	<form role="search" method="get" id="searchform" class="searchform" action="<?php echo htmlentities( home_url( '/' ) ); ?>" >
		<div>
			<p>
				<label for="_search_directory_first_name"><?php echo htmlentities( __( 'First Name:' ) ); ?></label>
				<input type="text" value="<?php echo htmlentities( !empty($_REQUEST['_search_directory']['first_name']) ? $_REQUEST['_search_directory']['first_name'] : '' ); ?>" name="_search_directory[first_name]" id="_search_directory_first_name" />
			</p>
			<p>
				<label for="_search_directory_last_name"><?php echo htmlentities( __( 'Last Name:' ) ); ?></label>
				<input type="text" value="<?php echo htmlentities( !empty($_REQUEST['_search_directory']['last_name']) ? $_REQUEST['_search_directory']['first_name'] : '' ); ?>" name="_search_directory[last_name]" id="_search_directory_last_name" />
			</p>
			<p>
				<label for="_search_directory_staff_category"><?php echo htmlentities( __( 'Department:' ) ); ?></label><br />
				<select name="_search_directory[staff_category]" id="_search_directory_staff_category">
					<option value="-1">All Departments</option>
					<?php foreach ($staff_categories as $cat): ?>
					<?php $selected = (!empty($_REQUEST['_search_directory']['staff_category']) && $_REQUEST['_search_directory']['staff_category'] == $cat->term_id) ? 'selected="selected"' : ''; ?>
					<option value="<?php echo esc_attr($cat->term_id); ?>" <?php echo $selected?>><?php echo htmlentities($cat->name) ?></option>
					<?php endforeach; ?>
				</select>
			</p>
			<input type="submit" id="searchsubmit" value="<?php echo esc_attr__( 'Search' ); ?>" />
		</div>		
		<input type="hidden" value="" name="s" />
	</form>
</div>