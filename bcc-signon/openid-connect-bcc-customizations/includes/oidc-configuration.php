<?php

add_filter('openid-connect-generic-default-settings', 'oidc_default_settings');
function oidc_default_settings($settings){
	$default_settings = array(
		// oauth client settings
		'login_type'        => 'button',
		'client_id'         => '',
		'client_secret'     => '',
		'scope'             => 'email openid profile',
		'endpoint_login'    => 'https://bcc-sso.eu.auth0.com/authorize',
		'endpoint_userinfo' => 'https://bcc-sso.eu.auth0.com/userinfo',
		'endpoint_token'    => 'https://bcc-sso.eu.auth0.com/oauth/token',
		'endpoint_end_session' => 'https://bcc-sso.eu.auth0.com/v2/logout',

		// non-standard settings
		'no_sslverify'    => 0,
		'http_request_timeout' => 5,
		'identity_key'    => 'preferred_username',
		'nickname_key'    => 'preferred_username',
		'email_format'       => '{email}',
		'displayname_format' => '{given_name} {family_name}',
		'identify_with_username' => false,
		'state_time_limit' => 180,

		// plugin settings
		'enforce_privacy' => 0,
		'alternate_redirect_uri' => 0,
		'link_existing_users' => 1,
		'redirect_user_back' => 1,
		'redirect_on_logout' => 1,
		'enable_logging'  => 1,
		'log_limit'       => 100,
	);
	$settings = new OpenID_Connect_Generic_Option_Settings('openid_connect_generic_settings', $default_settings);
	return $settings;
}

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