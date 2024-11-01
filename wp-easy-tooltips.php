<?php
/*
Plugin Name: WP Easy Tooltips
Plugin URI: http://www.meow.fr
Description: Simple but efficient wordpress plugin that allows you to simply create nice looking tooltips in your posts.
Version: 0.0.1
Author: Thomas KIM
Author URI: http://www.meow.fr
Text Domain: wp-easy-tooltips
Domain Path: /languages
*/

function wpetp_init() {
    add_action( 'wp_enqueue_scripts', 'wpetp_scripts' );
    add_action( 'wp_enqueue_scripts', 'wpetp_style' );
    add_shortcode( 'wpetp', 'wpetp_shortcode' );
}
add_action( 'init', 'wpetp_init' );

function wpetp_scripts() {
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'wpetp-js', plugin_dir_url( __FILE__ ) . 'js/wpetp.js', array( 'jquery' ), '0.0.1', true );
}

function wpetp_style() {
    wp_enqueue_style( 'wpetp-css', plugin_dir_url( __FILE__ ) . 'css/wpetp.css' );
}

function wpetp_shortcode( $atts, $content = null ) {
    $a = shortcode_atts( array(
            'title' => 'Default Text',
            'image' => 'Image URL',
        ), $atts );
    $short_content = wpetp_truncate($content);
    if (preg_match('/(\.jpg|\.png|\.bmp)$/', $a['image'])) {
        $image = imagecreatefromjpeg ( $a['image'] );
        if($image == false) {
            $image = imagecreatefrompng ( $a['image'] );
            if($image == false) {
                $image = imagecreatefromgif ( $a['image'] );
            }
        }
        $image_height = imagesy($image);
        $image_width = imagesx($image);
    }
    else {
        $image = false;
    }
    if($image_height == FALSE) {
        // No image
        return "<span class='wpetp-tooltip wpetp-tooltip-effect'><span class='wpetp-tooltip-item'>".$a['title']."</span><span class='wpetp-tooltip-content clearfix'><span class='wpetp-tooltip-text full-width'>".$content."</span></span></span>";
    }
    if($image_height >= $image_width) {
        // Portrait
    	return "<span class='wpetp-tooltip wpetp-tooltip-effect'><span class='wpetp-tooltip-item'>".$a['title']."</span><span class='wpetp-tooltip-content clearfix'><img src='".$a['image']."' class='wpetp-portrait'/><span class='wpetp-tooltip-text'>".$short_content."</span></span></span>";
    }
    if($image_height < $image_width) {
        // Portrait
    	return "<span class='wpetp-tooltip wpetp-tooltip-effect'><span class='wpetp-tooltip-item'>".$a['title']."</span><span class='wpetp-tooltip-content clearfix'><img src='".$a['image']."' class='wpetp-landscape'/><span class='wpetp-tooltip-text full-width'>".$content."</span></span></span>";
    }
}

function wpetp_truncate($string,$length=240) {
  $append = " [...]";
  $string = trim($string);

  if(strlen($string) > $length) {
    $string = wordwrap($string, $length);
    $string = explode("\n", $string, 2);
    $string = $string[0] . $append;
  }

  return $string;
}
?>
