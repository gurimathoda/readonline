<?php
/*
Plugin Name: Scrolling Notice Board
Description: This plugin works with sidebar widgets. You can add a scrolling notice board with latest published 5 posts along with excerpt of first 100 characters with this plugin. A sparkling "NEW" banner is shown before each title link. After the title link, the timestamp is shown in a creative way. When you hover your mouse over the widget area, the scrolling stops so that the title link can be clicked easily.
Version: 1.2.1
Author: Sultan Mustafijul Hoque
Plugin URI: http://www.freestylepost.com/scrolling-notice-board/
Author URI: http://www.freestylepost.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/


class hosts_widget extends WP_Widget {

function __construct() {
parent::__construct(

'hosts_widget', 

__('Scrolling Notice Board', 'hosts_widget_domain'), 

array( 'description' => __( 'This plugin works with sidebar widgets. You can add a scrolling notice board with latest published 5 posts along with excerpt of first 100 characters with this plugin. A sparkling "NEW" banner is shown before each title link. After the title link, the timestamp is shown in a creative way. When you hover your mouse over the widget area, the scrolling stops so that the title link can be clicked easily.', 'hosts_widget_domain' ), ) 
);
}

public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );
?>

<?php
global $post;
$tmp_post = $post;
                
$myposts = get_posts( $args );
foreach( $myposts as $post ) : setup_postdata($post); ?>

<?php endforeach; ?>
<?php $post = $tmp_post; ?>

<table style="height: 250px; width:100%; padding-top: 8px; padding-bottom: 15px; margin: 0 auto 20px auto; background: #f9fffd; border: 1px solid green; border-radius: 15px;  -moz-border-radius: 15px;  -webkit-border-radius: 15px;">
<tdbody>
<tr>
<td>
<H2><center><?php if ( $title )
 echo $title; ?></center></H2>
<hr /><marquee behavior="scroll" loop="infinite" direction="up" onmouseover="this.stop()" onmouseout="this.start()" scrollamount="1" scrolldelay=".0001" height="250">
<ul>
<?php $the_query = new WP_Query( 'showposts=5' ); ?>

<?php while ($the_query -> have_posts()) : $the_query -> the_post(); ?>
 <li><img src='/wp-content/plugins/scrolling-notice-board/icons/new.gif' /> <a href="<?php the_permalink() ?>"><?php the_title(); ?></a> <span class="time"><small><font color="green"><em>(<?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago'; ?>)</em></font></small></span></li>

 <li><?php echo substr(strip_tags($post->post_content), 0, 100);?> [...]</li>
 <?php endwhile;?>
</ul></marquee>
</td>
</tr>
</tdbody>
</table>

<?php
} 

public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
return $instance;
}


    function form($instance) {  

    $posttypes = get_post_types('', 'objects');
  
        $title = esc_attr($instance['title']);
        
        ?>
         <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
    
        <?php 
    }

} 


function hosts_load_widget() {
  register_widget( 'hosts_widget' );
}
add_action('widgets_init', 'hosts_load_widget', create_function('', 'return register_widget("hosts_load_widget");'));


?>