<?php
/**
 * Undocumented file.
 *
 * @package category
 */

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
function xg_register_fontawesome() {

	wp_enqueue_script(
		'font-awesome',
		plugins_url( 'xtec-ginys/js/all.js' ),
		array( 'jquery' ),
		'1',
		false
	);

}
// Register css.
add_action( 'wp_enqueue_scripts', 'xg_register_fontawesome' );

/**
 * Load and register all the plugins in the widgets variable.
 *
 * @return void
 */
function xg_register_widgets() {

	$widgets = array(
		'XTEC_Most_Active'  => 'class-xtec-most-active.php',
		'XTEC_My_Blocs'     => 'class-xtec-my-blocs.php',
		'XTEC_My_Favorites' => 'class-xtec-my-favorites.php',
	);

	foreach ( $widgets as $name => $file ) {
		include_once plugin_dir_path( __FILE__ ) . $file;
		register_widget( $name );
	}

}
// Register widgets.
add_action( 'widgets_init', 'xg_register_widgets' );

/**
 * Undocumented function
 *
 * @return void
 */
function xg_check_actions() {

	$referer = isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : '';
	$action  = isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : '';
	$blog_id = $_REQUEST['blog_id'];

	// switch ( $action ) {
	// 	case 'delete_favorite':
	// 		XTEC_My_Favorites::delete_favorite( $blog_id );
	// 		wp_safe_redirect( $referer );
	// 		exit;
	// }

	if ( 'delete_favorite' === $action ) {
		XTEC_My_Favorites::xg_delete_favorite( $blog_id );
		wp_safe_redirect( $referer );
		exit;
	}

}
// Check url's for actions.
add_action( 'init', 'xg_check_actions' );
