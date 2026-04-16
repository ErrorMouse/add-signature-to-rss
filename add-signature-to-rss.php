<?php
/**
 * Plugin Name: 		Add signature to RSS
 * Description: 		Adds a custom signature to your RSS feed items.
 * Version: 			1.0
 * Requires at least: 	5.2
 * Requires PHP: 		7.2
 * Author: 				Err
 * Author URI: 			https://profiles.wordpress.org/nmtnguyen56/
 * License: 			GPLv2 or later
 * License URI: 		https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: 		add-signature-to-rss
 * Domain Path: 		/languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter('pre_get_posts', 'feedFilter');
function feedFilter($query){
    if ($query->is_feed) {
        add_filter('the_content', 'feedContentFilter');
    }
    return $query;
}

function feedContentFilter($content){
	$signature = sprintf(
		/* translators: 1: Home URL, 2: Blog name. */
		__( 'This post was published at <a href="%1$s" rel="dofollow">%1$s</a> and is owned by <a href="%1$s" rel="dofollow">%2$s</a>.', 'add-signature-to-rss' ),
		esc_url( home_url() ),
		esc_html( get_bloginfo('name') )
	);

	$content .= "<p>" . $signature . "</p>";
	return $content;
}