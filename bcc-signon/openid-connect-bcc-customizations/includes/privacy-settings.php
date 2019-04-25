<?php 

function bcc_privacy_settings() {
    global $wp;
	$url = home_url(add_query_arg(array($_GET), $wp->request));
	// Exceptions for AJAX, Cron, or WP-CLI requests
	if ( ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || ( defined( 'DOING_CRON' ) && DOING_CRON ) || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
		return;
	}
	// Redirect unauthorized visitors
	if ( !is_user_logged_in() ) {
        $is_login_url = preg_replace( '/\?.*/', '', $url ) == preg_replace( '/\?.*/', '', wp_login_url());
        $unprotected_urls = array(home_url( get_private_link_feed()));
        if ( !$is_login_url  && !in_array( $url, $unprotected_urls) ) {
			// Set the headers to prevent caching
			nocache_headers();
			// Redirect
			wp_safe_redirect( wp_login_url(), 302 ); exit;
		}
    }
}
add_action( 'template_redirect', 'bcc_privacy_settings' );
 
?>