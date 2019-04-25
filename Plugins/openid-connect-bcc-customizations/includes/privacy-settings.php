<?php 

function privacy_settings() {
    global $wp;
    global $private_newsfeed_link;

    $url = home_url($wp->request);
	// Exceptions for AJAX, Cron, or WP-CLI requests
	if ( ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || ( defined( 'DOING_CRON' ) && DOING_CRON ) || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
		return;
	}
	// Redirect unauthorized visitors
	if ( ! is_user_logged_in() ) {
        $is_login_url = preg_replace( '/\?.*/', '', $url ) == preg_replace( '/\?.*/', '', wp_login_url());
        $unprotected_urls = [ home_url( '/feed/?id=' . $private_newsfeed_link )];
        if ( !$is_login_url  && ! in_array( $url, $unprotected_urls) ) {
			// Set the headers to prevent caching
			nocache_headers();
			// Redirect
			wp_safe_redirect( wp_login_url( wp_login_url() ), 302 ); exit;
		}
    }
}
add_action( 'template_redirect', 'privacy_settings' );