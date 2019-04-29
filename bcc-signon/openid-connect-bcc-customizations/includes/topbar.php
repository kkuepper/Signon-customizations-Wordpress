<?php



if (get_option('bcc_topbar') == 1) {
	add_action('wp_head', 'add_topbar');
	/**
	* Add widgets as an audience when the user is logging in
	*/
	add_filter('openid-connect-generic-auth-url', function( $url ) {
		$url.= '&audience=https%3A%2F%2Fwidgets.brunstad.org';
		return $url;
	}); 
	/**
	* Add bcc widgets style
	*/
	add_action( 'wp_enqueue_scripts', 'register_bcc_widgets_styles' );
	function register_bcc_widgets_styles() {
		wp_register_style( 'bcc_widgets_stylesheet','https://widgets.bcc.no/styles/widgets.css');
		wp_enqueue_style( 'bcc_widgets_stylesheet' );
	}

}

/**
* Add topbar to the header
*/
function add_topbar(){
	$user_id = get_current_user_id();
	if ( empty( $user_id ) ){
		echo (getGuestTopbar()); 
		return;
	}
	$tokens = get_user_meta($user_id, 'openid-connect-generic-last-token-response', true);
	if (empty( $tokens )) {
		echo (getGuestTopbar()); 
		return;
  	}
	$access_token = $tokens['access_token'];
	if ( strlen($access_token) < 30 || empty( $access_token ) ) {
		echo "<script type='text/javascript'>window.top.location='https://auth.bcc.no/?message=signout';</script>";
		return;
  	}
  	echo ('<script type="text/javascript" id="script-bcc-topbar" src="https://widgets.bcc.no/widgets/topbarjs" data-authentication-type="inline-access-token" data-access-token=' . $access_token .'></script>');
};

/**
* Load guestTopbar when there is an error getting the access_token
*/
function getGuestTopbar(){
	return '<script type="text/javascript" id="script-bcc-topbar" src="https://widgets.bcc.no/widgets/topbarjs" data-authentication-type="none"></script>';
}




?>