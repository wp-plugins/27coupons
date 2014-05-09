<?php
/*
Plugin Name: 27coupons
Plugin URI: http://www.27coupons.com/wp-widget/
Description: You can create a widgets which will display latest discount coupons and deals of Indian e-commerce websites from 27coupons.com.
Version: 3.0
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
  var $default_css='.CouponsWidget{	
width:100%; 
line-height:18px; 
font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
font-size:14px;
vertical-align:middle;
min-height: 20px;
margin-top:0px;
margin-bottom: 10px;
border-radius: 4px;
-webkit-border-radius: 4px;
-moz-border-radius: 4px;
}
.CouponsWidget h3{font-size:100%; margin-bottom:5px !important; font-weight:bold; line-height:24px;}
.CouponsWidget h3{font-size:100%; margin-bottom:5px !important; font-weight:bold; line-height:24px;}
.widget_27box{ }
.small_27{font-size:10px; display:block;  text-align:center; font-weight:bold; padding-top:10px;	}
.small_27 img{ vertical-align:middle; }
.c-deals{padding-top:5px;padding-bottom:5px; display:block; font-weight:normal;}
.c-store, .c-store a:link, .c-store a:visited {text-decoration:none; font-weight:normal;}
.c-store a:hover{ text-decoration:none; font-weight:bold;}
.c-coupon, .c-coupon a:link, .c-coupon a:visited {text-decoration:none; font-weight:normal; }
.c-coupon a:hover{ text-decoration:none; font-weight:bold;}';
    
  function CouponsWidget()
  {
    $widget_ops = array('classname' => 'CouponsWidget', 'description' => 'Displays latest discount coupons of Indian shopping websites from 27coupons.com.' );
    $this->WP_Widget('CouponsWidget', 'Latest Coupons', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '' ), array( 'api_key' => '' ) );
    //$title = $instance['title'];
	$api_key = $instance['api_key'];
    
    $title = empty($instance['title']) ? 'Latest Coupons' : $instance['title'];
    
    $css_style = empty($instance['coupons27box_css']) ? $this->default_css : $instance['coupons27box_css'];
    
?>
  <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: </label><input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></p>
  
  <p><label for="<?php echo $this->get_field_id( 'num_coupons' ); ?>"><?php _e('Number of Coupons:', 'widgetcoupons'); ?></label> 
			<select id="<?php echo $this->get_field_id( 'num_coupons' ); ?>" name="<?php echo $this->get_field_name( 'num_coupons' ); ?>" class="widefat" style="width:100%;">
				<option <?php if ( '4' == $instance['num_coupons'] ) echo 'selected="selected"'; ?>>5</option>
				<option <?php if ( '7' == $instance['num_coupons'] ) echo 'selected="selected"'; ?>>7</option>
				<option <?php if ( '10' == $instance['num_coupons'] ) echo 'selected="selected"'; ?>>10</option>
				<option <?php if ( '15' == $instance['num_coupons'] ) echo 'selected="selected"'; ?>>15</option>
	</select>
	</p>
	
	<p><label for="<?php echo $this->get_field_id('api_key'); ?>">API Key: </label><input class="widefat" id="<?php echo $this->get_field_id('api_key'); ?>" name="<?php echo $this->get_field_name('api_key'); ?>" type="text" value="<?php echo attribute_escape($api_key); ?>" /></p>
    
    <p><label for="<?php echo $this->get_field_id('coupons27box_css'); ?>">CSS: </label><textarea class="widefat" id="<?php echo $this->get_field_id('coupons27box_css'); ?>" name="<?php echo $this->get_field_name('coupons27box_css'); ?>" rows="10"><?php echo $css_style; ?></textarea></p>
	
	<a style="text-decoration:none;" href="http://www.27coupons.com/wp-widget/" target="_blank">Click here to monetize this widget.</a>
	
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
	$instance['api_key'] = $new_instance['api_key'];
	$instance['num_coupons'] = $new_instance['num_coupons'];
    $instance['coupons27box_css'] = $new_instance['coupons27box_css'];
	
    return $instance;
  }
  
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
	
    $title = empty($instance['title']) ? 'Latest Coupons' : apply_filters('widget_title', $instance['title']);
	$num_coupons = empty($instance['num_coupons']) ? '7' : $instance['num_coupons'];
	$api_key = empty($instance['api_key']) ? 'ba718b134ff2817bd41235061f6bcfcc73fa' : $instance['api_key'];
    $css_style = empty($instance['coupons27box_css']) ? $this->default_css : $instance['coupons27box_css'];
    
    echo '<style type="text/css">'.$css_style.'</style>';	
    
    echo $before_widget;
    
    if (!empty($title))
      echo $before_title . $title . $after_title;

    // WIDGET CODE GOES HERE
    $api_url='http://apis.27coupons.com/v1.0/wp/get-coupons/?key='.$api_key.'&limit='.$num_coupons;
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