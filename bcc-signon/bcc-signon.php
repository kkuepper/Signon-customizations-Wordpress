<?php

/*
Plugin Name: BCC Signon
Description: Integration to BCC's Login System.
Version: $_PluginVersion_$
Author: BCC IT
*/

require  dirname( __FILE__) . '/openid-connect-bcc-customizations/bcc-signon.php';
require  dirname( __FILE__) . '/openid-connect-generic-client/openid-connect-generic.php';
require dirname( __FILE__)  . '/plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
    'https://developer.bcc.no/api/updates/bcc-signon.json',
     __FILE__,
    'bcc-signon');
?>