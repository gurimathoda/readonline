<?php
class StaffDirectoryPlugin_Importer
{
	var $root;
	var $last_error = '';
	var $records_imported = 0;
	static $csv_headers = array('Full Name','Body','First Name','Last Name','Title','Phone','Email','Address','Website','Categories','Photo');
	
    public function __construct($root)
    {
		$this->root = $root;
	}	

	public static function get_csv_headers()
	{
		return self::$csv_headers;
	}

	public static function output_form()
	{
		echo '<form method="POST" action="" enctype="multipart/form-data">';
		
		// Load Importer API
		require_once ABSPATH . 'wp-admin/includes/import.php';

		if ( !class_exists( 'WP_Importer' ) ) {
			$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
			if ( file_exists( $class_wp_importer ) )
				require_once $class_wp_importer;
		}
		
		echo "<p>Please select a .CSV file from your computer to import. The first line of your CSV will need to match the example headers below, or the import will not work.</p>";
		echo "<p><strong>CSV Headers (required):</strong></p>";
		printf ("<p><code>%s</code></p>", "'" . implode("','",self::$csv_headers) . "'" );
		echo '<div class="gp_upload_file_wrapper">';
		wp_import_upload_form( add_query_arg('step', 1) );
		echo '<input type="hidden" name="_company_dir_do_import" value="_company_dir_do_import" />';
		echo "<p><strong>Note: </strong> Depending on your server settings, you may need to run the import several times if your script times out.</p> <p><strong>Staff Member Import supports Photos!</strong> If you include the path to a photo, that is available online, in the Photo column of your CSV we will attempt to upload and attach it to the Staff Member.</p>";
		echo '</div>';
		echo '</form>';
	}
	
	public function process_import()
	{
		$errors = array();
		
		// Load Importer API
		require_once ABSPATH . 'wp-admin/includes/import.php';

		if ( !class_exists( 'WP_Importer' ) ) {
			$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
			if ( file_exists( $class_wp_importer ) )
				require_once $class_wp_importer;
		}		
		
		if(!empty($_FILES))
		{
			$file = wp_import_handle_upload();

			if ( isset( $file['error'] ) ) {
				$this->last_error = sprintf('<p><strong>Sorry, there has been an error.</strong><br />%s</p>', esc_html( $file['error'] ));
				return false;
			} else if ( ! file_exists( $file['file'] ) ) {
				$err_msg = sprintf('The export file could not be found at <code>%s</code>. It is likely that this was caused by a permissions problem.', esc_html( $file['file'] ) );
				$this->last_error = sprintf('<p><strong>Sorry, there has been an error.</strong><br />%s</p>', $err_msg );
				return false;
			}
			
			$file_id = (int) $file['id'];
			$file_name = get_attached_file($file_id);
			
			if (file_exists($file_name)) {
				$result = $this->import_posts_from_csv($file_name);
			} else {
				$this->last_error = sprintf('<p><strong>Sorry, there has been an unknown error. Please try again.</strong></p>');
				return false;
			}			
		}
		
		// all worked!
		return $result;
	}
	
	//process data from CSV import
	private function import_posts_from_csv($posts_file)
	{
		//increase execution time before beginning import, as this could take a while
		set_time_limit(0);		
		
		$posts = $this->csv_to_array($posts_file);
		$messages = array();
		$success_count = 0;
		$fail_count = 0;
		
		foreach($posts as $post)
		{
			// title and body are always required
			$full_name = isset($post['Full Name']) ? $post['Full Name']  : '';
			$the_body = isset($post['Body']) ? $post['Body']  : '';
			
			// look for a staff member with the same full name, to prevent duplicates
			$find_dupe = get_page_by_title( $full_name, OBJECT, 'staff-member' );
			
			// if no one with that name was found, continue with inserting the new staff member
			if( empty($find_dupe) )
			{
				$new_post = array(
					'post_title'    => $full_name,
					'post_content'  => $the_body,
					'post_status'   => 'publish',
					'post_type'     => 'staff-member'
				);
				
				$new_id = wp_insert_post($new_post);

				// assign Staff Member Categories if any were specified
				// NOTE: we are using wp_set_object_terms instead of adding a tax_input key to wp_insert_posts, because 
				// it is less likely to fail b/c of permissions and load order (i.e., taxonomy may not have been created yet)
				if (!empty($post['Categories'])) {
					$post_cats = explode(',', $post['Categories']);
					$post_cats = array_map('intval', $post_cats); // sanitize to ints
					wp_set_object_terms($new_id, $post_cats, 'staff-member-category');
				}
				
				// Save the custom fields. Default everything to empty strings
				$first_name = isset($post['First Name']) ? $post['First Name'] : '';
				$last_name = isset($post['Last Name']) ? $post['Last Name'] : '';
				$title = isset($post['Title']) ? $post['Title'] : "";
				$phone = isset($post['Phone']) ? $post['Phone'] : "";
				$email = isset($post['Email']) ? $post['Email'] : "";
				$address = isset($post['Address']) ? $post['Address'] : "";
				$website = isset($post['Website']) ? $post['Website'] : "";
								
				update_post_meta( $new_id, '_ikcf_first_name', $first_name );
				update_post_meta( $new_id, '_ikcf_last_name', $last_name );
				update_post_meta( $new_id, '_ikcf_title', $title );
				update_post_meta( $new_id, '_ikcf_phone', $phone );
				update_post_meta( $new_id, '_ikcf_email', $email );
				update_post_meta( $new_id, '_ikcf_address', $address );
				update_post_meta( $new_id, '_ikcf_website', $website );
				
				// Look for a photo path on CSV
				// If found, try to import this photo and attach it to this staff member
				$this->import_staff_photo($new_id, $post['Photo']);				
				
				// Successfully added the post! Update success_count and continue.
				$messages[] = sprintf("Successfully imported '%s!'", $full_name);
				$success_count++;
			}
			else {
				// Rejected as duplicate. Update fail_count and continue.
				$messages[] = sprintf("Could not import '%s'; rejected as duplicate.", $full_name);
				$fail_count++;				
			}
		}
		return array(
			'imported' => $success_count,
			'failed' => $fail_count,
			'messages' => $messages,
		);
	}
	
	function import_staff_photo($post_id = '', $photo_source = ''){	
		//used for overriding specific attributes inside media_handle_sideload
		$post_data = array();
		
		//set attributes in override array
		$post_data = array(
			'post_title' => '', //photo title
			'post_content' => '', //photo description
			'post_excerpt' => '', //photo caption
		);
	
		require_once( ABSPATH . 'wp-admin/includes/image.php');
		require_once( ABSPATH . 'wp-admin/includes/media.php' );//need this for media_handle_sideload
		require_once( ABSPATH . 'wp-admin/includes/file.php' );//need this for the download_url function
		
		$desc = ''; // photo description
		
		$picture = urldecode($photo_source);
		
		// Download file to temp location
		$tmp = download_url( $picture);
		
		// Set variables for storage
		// fix file filename for query strings
		preg_match('/[^\?]+\.(jpg|JPG|jpe|JPE|jpeg|JPEG|gif|GIF|png|PNG)/', $picture, $matches);
		$file_array['name'] = isset($matches[0]) ? basename($matches[0]) : basename($picture);
		$file_array['tmp_name'] = $tmp;

		// If error storing temporarily, unlink
		if ( is_wp_error( $tmp ) ) {
			//$error_string = $tmp->get_error_message();
			//echo '<div id="message" class="error"><p>' . $error_string . '</p></div>';
			
			@unlink($file_array['tmp_name']);
			$file_array['tmp_name'] ='';
		}
		
		$id = media_handle_sideload( $file_array, $post_id, $desc, $post_data );

		// If error storing permanently, unlink
		if ( is_wp_error($id) ) {
			//$error_string = $id->get_error_message();
			//echo '<div id="message" class="error"><p>' . $error_string . '</p></div>';
			
			@unlink($file_array['tmp_name']);
		}
		
		//add as the post thumbnail
		if($post_id > 0){
			add_post_meta($post_id, '_thumbnail_id', $id, true);
		}
	}
	
	//convert CSV to array
	private function csv_to_array($filename='', $delimiter=','){
		if(!file_exists($filename) || !is_readable($filename))
			return FALSE;

		$header = NULL;
		$data = array();
		
		if (($handle = fopen($filename, 'r')) !== FALSE)
		{
			while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
			{
				if(!$header){
					$header = $row;
				} else {
					if (count($header) == count($row)) {
						$data[] = array_combine($header, $row);
					}
				}
			}
			fclose($handle);
		}
		return $data;
	}
	
}