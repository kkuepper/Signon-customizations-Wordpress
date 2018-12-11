function handle_openid_error(){
	$error = $_GET["login-error"];
	switch ($error){
		case "unknown-error":
			wp_redirect("https://bcc-sso.azurewebsites.net/?message=consentrejected");
			exit;    
			break;
		case "missing-state":
			wp_redirect("https://developer.bcc.no");
			exit;    
			break;
		default:
			break;
	}
}
add_action( 'wp_authenticate', 'handle_openid_error');