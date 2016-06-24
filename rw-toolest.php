<?php
/*
Plugin Name: RW Toolset
Description: Erlaubt zusätzliche Dateitypen. Setze übergeordnete Sprachdateien.
*/


add_action('init', 'reliwerk_interim_solution',20);

//http://www.paulund.co.uk/change-wordpress-upload-mime-types
add_filter('upload_mimes','add_custom_mime_types');
function add_custom_mime_types($mimes){
	return array_merge($mimes,array (
		'htm' => 'text/html',
		'html' => 'text/html',
		'ac3' => 'audio/ac3',
		'mpa' => 'audio/MPA',
		'flv' => 'video/x-flv',
		'svg' => 'image/svg+xml'
	));
}

function rw_fix_correct_plugin_textdomain_dir() {
	
	$locale = get_locale();
	
	$domains = array (
		  'bp-docs'
		, 'bp-group-hierarchy'
		, 'bp-ass'
	) ;
	
	foreach ($domains as $domain ){
		if ( $loaded = load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) .'plugins/' . $domain . '-' . $locale . '.mo' , true) ) {
			
		} else {
			load_plugin_textdomain( $domain, FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
		}	
	}
}
add_action( 'plugins_loaded', 'rw_fix_correct_plugin_textdomain_dir' );

function rw_fix_correct_theme_textdomain_dir() {
	
	$locale = get_locale();
	$domains = array (
		  'boss'
		, 'social-learner'
		
	) ;
	
	foreach ($domains as $domain ){
		if ( $loaded = load_theme_textdomain( $domain, trailingslashit( WP_LANG_DIR ) .'themes/' . $domain . '-' . $locale . '.mo' , true) ) {
			
		} else {
			load_theme_textdomain( $domain, FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
		}	
	}
}
add_action( 'after_setup_theme', 'rw_fix_correct_theme_textdomain_dir' );
