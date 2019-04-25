<?php 
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
