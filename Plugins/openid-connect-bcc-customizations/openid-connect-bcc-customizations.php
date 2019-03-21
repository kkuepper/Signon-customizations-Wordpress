<?php
/*
Plugin Name: OpenID Connect Generic (BCC signon customization)
Description: customazations for the BCC signon system. (support: it@bcc.no)
Version: 1.0.0
Author: BCC
*/

$bcc_auth_domain = "https://auth.bcc.no/";

/**
 * Allow logout without confirmation
 */
add_action('check_admin_referer', 'logout_without_confirm', 10, 2);
function logout_without_confirm($action, $result)
{
    if ($action == "log-out" && !isset($_GET['_wpnonce']) && isset($_GET['signoutiframe']) ) {
        $redirect_to = isset($_REQUEST['redirect_to']) ? $_REQUEST['redirect_to'] : '';
        $location = str_replace('&amp;', '&', wp_logout_url($redirect_to));;
        header("Location: $location");
        die;
    }
}

/* Add script tag for single signout */
add_action('wp_footer', 'add_signout_script');
function add_signout_script(){
	global $bcc_auth_domain;

	echo ('<script type="text/javascript" src="' . $bcc_auth_domain . 'signout/js" signout-path="/wp-login.php?action=logout&signoutiframe=1"></script>');
};

/**
 * Handle openid connect errors better by redirecting to the 'bccAuthDomain'
 */
add_action( 'wp_authenticate', 'handle_openid_error');
function handle_openid_error(){
  	global $bcc_auth_domain;

  	$error = $_GET["login-error"];
  	switch ($error){
		case "unknown-error":
	    	wp_redirect($bcc_auth_domain . '?message=consentrejected');
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
