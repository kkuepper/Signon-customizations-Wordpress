<?php
/*
Plugin Name: BCC signon customizations
Description: customizations for the BCC signon system (support: it@bcc.no).
Version: 1.0.0
Author: BCC
*/
require plugin_dir_path( __FILE__ ) . 'includes/class-oidc-bcc.php';

function run_oidc_bcc() {

	$plugin = new OIDC_BCC();
	$plugin->run();
}


run_oidc_bcc();
?>

