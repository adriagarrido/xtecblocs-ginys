<?php
/*
Plugin Name: Ginys XTECBLOCS
Plugin URI: https://github.com/projectestac/xtecblocs
Description: This plugin create widgets for XTECBLOCS.
Author: Adrià Garrido
Version: 1.0
Author URI:
*/

/**
 * Undocumented function
 *
 * @param [type] $hook_suffix
 * @return void
 */
function mi_script_enqueue_color_picker( $hook_suffix ) {

	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'mi-script', plugins_url( 'js/script.js', __FILE__ ), array( 'wp-color-picker' ), false, true );

}

// add js
add_action( 'admin_enqueue_scripts', 'mi_script_enqueue_color_picker' );

/**
 * Register and enqueue FontAwesome css.
 *
 * @return void
 */
function register_fontawesome() {

	wp_register_style(
		'font-awesome',
		plugins_url( 'xtec-ginys/css/all.min.css' ),
		array(),
		'1'
	);
	wp_enqueue_style( 'font-awesome' );

}

// Register css.
add_action( 'wp_enqueue_scripts', 'register_fontawesome' );

/**
 * Load and register all the plugins in the widgets variable.
 *
 * @return void
 */
function register_widgets() {

	$widgets = array(
		'XTEC_My_Blocs' => 'class-xtec-my-blocs.php',
	);

	foreach ( $widgets as $name => $file ) {
		include_once plugin_dir_path( __FILE__ ) . $file;
		register_widget( $name );
	}

}

// Register widgets.
add_action( 'widgets_init', 'register_widgets' );
