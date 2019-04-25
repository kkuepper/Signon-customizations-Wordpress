<?php

/**
 * Handle openid connect errors better by redirecting to the 'bccAuthDomain'
 */
add_action( 'wp_authenticate', 'handle_openid_error');
function handle_openid_error(){
	$error = $_GET["login-error"];
	switch ($error){
		case "unknown-error":
			wp_redirect(get_option('bcc_auth_domain') . '?message=consentrejected');
			exit;    
			break;
		case "missing-state":
			wp_redirect(home_url());
			exit;    
			break;
		default:
			break;
	}
}

?>