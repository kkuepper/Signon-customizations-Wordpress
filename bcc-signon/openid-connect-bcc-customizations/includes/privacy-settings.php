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
		$unprotected_urls = apply_filters('bcc_unprotected_urls', $unprotected_urls);
        if ( !(in_array($no_query, $login_urls) || in_array( $url, $unprotected_urls)) ) {
			// Set the headers to prevent caching
			nocache_headers();
			// Redirect
			wp_safe_redirect( wp_login_url($url), 302 ); exit;
		}
    }
}
add_action( 'template_redirect', 'bcc_privacy_settings' );

/* DISABLE REST API FOR NON-LOGGED IN USERS */
remove_action('template_redirect', 'rest_output_link_header', 11);
remove_action('wp_head', 'rest_output_link_wp_head', 10);
remove_action('xmlrpc_rsd_apis', 'rest_output_rsd');

if (version_compare(get_bloginfo('version'), '4.7', '>=')) {
	add_filter('rest_authentication_errors', 'disable_wp_rest_api');
} else {
	disable_wp_rest_api_legacy();
}

function disable_wp_rest_api($access) {
	if (!is_user_logged_in()) {
		$message = apply_filters('disable_wp_rest_api_error', __('REST API restricted to authenticated users.', 'disable-wp-rest-api'));
		return new WP_Error('rest_login_required', $message, array('status' => rest_authorization_required_code()));
	}
	return $access;
}

function disable_wp_rest_api_legacy() {
    add_filter('json_enabled', '__return_false');
    add_filter('json_jsonp_enabled', '__return_false');
    add_filter('rest_enabled', '__return_false');
    add_filter('rest_jsonp_enabled', '__return_false');
}
?>