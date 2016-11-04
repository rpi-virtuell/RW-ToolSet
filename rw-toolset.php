<?php
/*
 * Plugin Name: RW Toolset
 * Plugin Uri:  https://github.com/rpi-virtuell/RW-ToolSet
 * Description: rpi-virtuell Hacks für Buddypress Instanzen. Erlaubt zusätzliche Dateitypen. Setze übergeordnete Sprachdateien. Fixed verschiedene Plugin Probleme
 * Author:      Joachim Happel
 * Author URI:  https://bbpress.org
 * Version:     0.0.2
 /

/**
* Fixes Loading BP-Docs-Foldercontent via Ajax
* issue: in Ajax  Buddypress-Grpoups-Component was not overriden by the Groupshierarchy component 
*        the result was: folder contents left empty in child groups
* this little hack correct the missing group_id
*/

add_filter('bp_get_current_group_id','bp_get_current_group_id_in_group_hierarchy',999,2);
function bp_get_current_group_id_in_group_hierarchy($current_group_id, $current_group){
		
	if( $_SERVER['HTTP_X_REQUESTED_WITH'] && isset($_GET['group_id']) && isset($_GET['action']) && $_GET['action'] == 'bp_docs_get_folder_content' ){
		return $_GET['group_id'];
	}
	
	return $current_group_id;
}

/**
* Description: Erlaubt den Upload zusätzlicher Dateitypen. Setze übergeordnete Sprachdateien.
*/

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

/**
 * Setze übergeordnete Sprachdateien für plugins
 * Sprachdateien in wp-content/languages/plugins werden bevorzugt
 */
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

/**
 * Setze übergeordnete Sprachdateien für themes
 * Sprachdateien in wp-content/languages/themes werden bevorzugt
 */

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
