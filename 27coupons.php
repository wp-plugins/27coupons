<?php
/*
Plugin Name: 27coupons
Plugin URI: http://www.diffion.com/widgets/
Description: This plugin will create a widget which will display latest discount coupons and deals of Indian e-commerce websites from 27coupons.com. You can customize the plugin to change the title and number of deals to be displayed. 
Version: 1.0
Author: diffion
Author URI: http://www.diffion.com
License: GPL2
*/
/*  Copyright 2012  27coupons  (email : contact@27coupons.com)

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
    $widget_ops = array('classname' => 'CouponsWidget', 'description' => 'Displays latest discount coupons and deals of Indian stores.' );
    $this->WP_Widget('CouponsWidget', 'Latest Discount Coupons & Deals', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
    $title = $instance['title'];
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
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
	$instance['num_coupons'] = $new_instance['num_coupons'];
	
    return $instance;
  }
  
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
	
	echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
	$num_coupons = empty($instance['num_coupons']) ? '7' : $instance['num_coupons'];
	
    if (!empty($title))
      echo $before_title . $title . $after_title;;
 
    // WIDGET CODE GOES HERE
	$api_url='http://www.27coupons.com/apps/widgets/latest_deals/get_wp_deals.php?referrer='.$_SERVER['HTTP_HOST'].'&widget_coupons='.$num_coupons;
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