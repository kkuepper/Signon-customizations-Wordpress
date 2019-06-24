<?php 
/**
 * This function redirects non-authenticated users to the login page.
 * It is triggered each time a user navigates to a page.
 */

function bcc_privacy_settings() {
    global $wp;
	$url = home_url(add_query_arg(array($_GET), $wp->request));
	// Exceptions for AJAX, Cron, or WP-CLI requests
	if ( ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || ( defined( 'DOING_CRON' ) && DOING_CRON ) || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
		return;
	}
	// Redirect unauthorized visitors
	if ( !is_user_logged_in() ) {
		$no_query = preg_replace( '/\?.*/', '', $url );
		$login_urls = array(preg_replace( '/\?.*/', '', wp_login_url()), home_url('/openid-connect-authorize'));
		$unprotected_urls = array();
		$unprotected_urls = apply_filters('bcc_unprotected_url', $unprotected_urls);
        if ( !(in_array($no_query, $login_urls) || in_array( $url, $unprotected_urls)) ) {
			// Set the headers to prevent caching
			nocache_headers();
			// Redirect
			wp_safe_redirect( wp_login_url($url), 302 ); exit;
		}
    }
}
add_action( 'template_redirect', 'bcc_privacy_settings' );
 
?>