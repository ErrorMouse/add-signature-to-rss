<?php
/**
 * Plugin Name: 		Add signature to RSS
 * Plugin URI: 			https://err-mouse.id.vn
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

add_action( 'admin_enqueue_scripts', 'errplugin_add_signature_to_rss_enqueue_admin_scripts' );
function errplugin_add_signature_to_rss_enqueue_admin_scripts( $hook_suffix ) {

	$is_plugins_page  = ( 'plugins.php' === $hook_suffix );

	if ( ! $is_plugins_page ) {
		return;
	}

	// Styles for the donate link on the plugins page.
	if ( $is_plugins_page ) {
		$donate_css = "
            .err-donate-link {
                font-weight: bold;
                background: linear-gradient(90deg, #0066ff, #00a1ff, rgb(255, 0, 179), #0066ff);
                background-size: 200% auto;
                color: #fff;
                -webkit-background-clip: text;
                -moz-background-clip: text;
                background-clip: text;
                -webkit-text-fill-color: transparent;
                animation: errGradientText 2s linear infinite;
            }
            @keyframes errGradientText {
                to { background-position: -200% center; }
            }";
		wp_add_inline_style( 'wp-admin', $donate_css );
	}
}

/* Donate */
function errplugin_add_signature_to_rss_donate_link_html() {
	$donate_url = 'https://err-mouse.id.vn/donate';
	printf(
		'<a href="%1$s" target="_blank" rel="noopener noreferrer" class="err-donate-link" aria-label="%2$s"><span>%3$s 🚀</span></a>',
		esc_url( $donate_url ),
		esc_attr__( 'Donate to support this plugin', 'add-signature-to-rss' ),
		esc_html__( 'Donate', 'add-signature-to-rss' )
	);
}

add_filter( 'plugin_row_meta', 'errplugin_add_signature_to_rss_plugin_row_meta', 10, 2 );
function errplugin_add_signature_to_rss_plugin_row_meta( $links, $file ) {
	if ( plugin_basename( __FILE__ ) === $file ) {
		ob_start();
		errplugin_add_signature_to_rss_donate_link_html();
		$links['donate'] = ob_get_clean();
	}
	return $links;
}