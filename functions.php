<?php
/**
 * Theme Functions
 *
 * @package Wordpress
 * @subpackage daltons
 */

/**
 * Enqueue styles
 */
function daltons_styles() {
	wp_enqueue_style( 'bootstrap-css', get_template_directory_uri() . '/css/bootstrap.min.css', array(), '3.1.1' );
    wp_enqueue_style( 'icons', '//netdna.bootstrapcdn.com/font-awesome/3.2.0/css/font-awesome.css' );
    wp_enqueue_style( 'font', 'http://fonts.googleapis.com/css?family=Lato' );
    wp_enqueue_style( 'daltons-css', get_stylesheet_uri(), array('bootstrap-css'), '1.0' );
}
add_action( 'wp_enqueue_scripts', 'daltons_styles' );

/**
 * Enqueue scripts
 */
function daltons_scripts() {
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'bootstrap-js', get_template_directory_uri() . '/js/bootstrap.min.js', array('jquery'), '3.1.1' );
    wp_enqueue_script( 'main-js', get_template_directory_uri() . '/js/main.js', array('jquery','bootstrap-js') );
}
add_action( 'wp_enqueue_scripts', 'daltons_scripts' );

function daltons_admin_init() {
    wp_enqueue_style( 'dashicons' );
    wp_enqueue_script( 'jquery' );
    wp_enqueue_style('thickbox');
    wp_enqueue_script('media-upload');
    wp_enqueue_script('thickbox');    
    wp_enqueue_script( 'admin-js', get_template_directory_uri() . '/admin/admin.js', array('jquery') );
    wp_enqueue_media();
}
add_action('admin_enqueue_scripts','daltons_admin_init');
