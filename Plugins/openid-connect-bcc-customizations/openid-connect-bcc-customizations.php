<?php
/*
Plugin Name: BCC signon customizations
Description: customizations for the BCC signon system (support: it@bcc.no).
Version: 1.0.0
Author: BCC
*/

$bcc_auth_domain = esc_attr( get_option('bcc_auth_domain') );
if ($bcc_auth_domain == "") {
  $bcc_auth_domain = "https://auth.bcc.no/";
  update_option('bcc_auth_domain', $bcc_auth_domain);
}

$private_newsfeeds = get_option('private_newsfeeds');

$private_newsfeed_link = esc_attr( get_option('private_newsfeed_link') );
if ($private_newsfeed_link == "") {
  $private_newsfeed_link = strtolower(str_replace("-","",trim(com_create_guid(), '{}')));
  update_option('private_newsfeed_link', $private_newsfeed_link);
}

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

/**
 * Checks if the private link feed is registered within this Wordpress instance
 */
function private_link_feed() {
  global $private_newsfeed_link;
  if($_SERVER['REQUEST_URI'] != '/feed/?id=' . $private_newsfeed_link){
    wp_die(__('No feed available'));  
  }
}

/**
 * Adds feed if private newsfeeds is checked
 */
if ($private_newsfeeds) {
  add_action('do_feed', 'private_link_feed', 1);
  add_action('do_feed_rdf', 'private_link_feed', 1);
  add_action('do_feed_rss', 'private_link_feed', 1);
  add_action('do_feed_rss2', 'private_link_feed', 1);
  add_action('do_feed_atom', 'private_link_feed', 1);

  // Remove feed links from header
  remove_action( 'wp_head', 'feed_links_extra', 3 ); 
  remove_action( 'wp_head', 'feed_links', 2 );
}


/**
 * Registers the Settings menu
 */
add_action('admin_menu', 'bcc_signon_plugin_create_menu');
function bcc_signon_plugin_create_menu() {
  add_options_page('BCC Signon', 'BCC Signon', 'manage_options', 'bcc_signon_settings_page', 'bcc_signon_settings_page');
  add_action( 'admin_init', 'register_bcc_signon_plugin_settings' );
}

/**
 * Registers the fields in the settings page
 */
function register_bcc_signon_plugin_settings() {
  register_setting( 'bcc-signon-plugin-settings-group', 'bcc_auth_domain' );
  register_setting( 'bcc-signon-plugin-settings-group', 'private_newsfeeds' );
}

/**
 * Creates the settings page
 */
function bcc_signon_settings_page() {
    global $bcc_auth_domain;
    global $private_newsfeeds;
    global $private_newsfeed_link;
?>

<div class="wrap">
<h1>BCC Signon Settings</h1>

<form method="post" action="options.php">

  <?php settings_fields( 'bcc-signon-plugin-settings-group' ); ?>
  <?php do_settings_sections( 'bcc-signon-plugin-settings-group' ); ?>

  <table class="form-table">
    <tr valign="top">
      <th scope="row">BCC signon URL</th>
      <td><input type="text" class="large-text" name="bcc_auth_domain" value="<?php echo $bcc_auth_domain; ?>" size="65" /></td>
    </tr>
     
    <tr valign="top">
      <th scope="row">Enable private newsfeeds</th>
      <td><input type="checkbox" value="1" <?php checked($private_newsfeeds); ?> name="private_newsfeeds" />
        <p class="description">
          This makes the newsfeed of your site only accessible via the <code>Private newsfeed link</code> (including the genreated <code>id</code> in the query-string).
        </p>
      </td>
    </tr>
    
    <tr valign="top">
      <th scope="row">Private newsfeed link</th>
      <td><input type="text" class="large-text" name="private_newsfeed_link" value="<?php if ($private_newsfeeds) { echo get_site_url() . '/feed/?id=' . $private_newsfeed_link;}?>"readonly />
        <p class="description">
          Please share this URL with BCC to integrate your news into the BCC Portal.
        </p>
      </td>
    </tr>
  </table>
  
  <?php submit_button(); ?>

</form>
</div>

<?php } ?>