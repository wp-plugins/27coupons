<?php
/*
Plugin Name: 27coupons
Plugin URI: http://www.27coupons.com/wp-widget/
Description: You can create a widgets which will display latest discount coupons and deals of Indian e-commerce websites from 27coupons.com. You can also monetize this widget.
Version: 2.1
Author: diffion
Author URI: http://www.diffion.com
License: GPL2
*/
/*  Copyright 2012  27coupons  (email : cs@27coupons.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
class CouponsWidget extends WP_Widget
{
  function CouponsWidget()
  {
    $widget_ops = array('classname' => 'CouponsWidget', 'description' => 'Displays latest discount coupons and deals of Indian stores from 27coupons.com.' );
    $this->WP_Widget('CouponsWidget', 'Latest Discount Coupons & Deals', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '' ), array( 'api_key' => '' ) );
    $title = $instance['title'];
	$api_key = $instance['api_key'];
?>
  <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
  
  <p><label for="<?php echo $this->get_field_id( 'num_coupons' ); ?>"><?php _e('Number of Deals:', 'widgetcoupons'); ?></label> 
			<select id="<?php echo $this->get_field_id( 'num_coupons' ); ?>" name="<?php echo $this->get_field_name( 'num_coupons' ); ?>" class="widefat" style="width:100%;">
				<option <?php if ( '4' == $instance['num_coupons'] ) echo 'selected="selected"'; ?>>5</option>
				<option <?php if ( '7' == $instance['num_coupons'] ) echo 'selected="selected"'; ?>>7</option>
				<option <?php if ( '10' == $instance['num_coupons'] ) echo 'selected="selected"'; ?>>10</option>
				<option <?php if ( '15' == $instance['num_coupons'] ) echo 'selected="selected"'; ?>>15</option>
	</select>
	</p>
	
	<p><label for="<?php echo $this->get_field_id('api_key'); ?>">API Key: <input class="widefat" id="<?php echo $this->get_field_id('api_key'); ?>" name="<?php echo $this->get_field_name('api_key'); ?>" type="text" value="<?php echo attribute_escape($api_key); ?>" /></label></p>
	
	<a style="text-decoration:none;" href="http://www.27coupons.com/wp-widget/" target="_blank">Click here to monetize this widget.</a>
	
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
	$instance['api_key'] = $new_instance['api_key'];
	$instance['num_coupons'] = $new_instance['num_coupons'];
	
    return $instance;
  }
  
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
	
	echo $before_widget;
    $title = empty($instance['title']) ? ' Latest Coupons & Deals ' : apply_filters('widget_title', $instance['title']);
	$num_coupons = empty($instance['num_coupons']) ? '7' : $instance['num_coupons'];
	$api_key = empty($instance['api_key']) ? '615e26b167eba5788883d18e8dfef0329013daa3d7f9619e92b6da2c1a3a32a8b5106bb8e792d78aa2e6cfb0069e1147a0ba718b134ff2817bd41235061f6bcf' : $instance['api_key'];

    if (!empty($title))
      echo $before_title . $title . $after_title;

    // WIDGET CODE GOES HERE
	$api_url='http://api.27coupons.com/v1.0/wp/get-coupons/?key='.$api_key.'&limit='.$num_coupons;
	$ch = curl_init($api_url);
	curl_setopt($ch,  CURLOPT_HTTPGET, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$coupons_html_code = curl_exec($ch);

	echo  $coupons_html_code;
 
    echo $after_widget;
  }
}
add_action( 'widgets_init', create_function('', 'return register_widget("CouponsWidget");') );?>