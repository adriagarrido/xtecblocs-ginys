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
