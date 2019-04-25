<?php
/**
 * The file that defines the core plugin class
 */

class OIDC_BCC {
	
	protected $bcc_auth_domain;
	
	protected $private_newsfeed_link;

	protected $private_newsfeeds;

	public function __construct() {
		$this->bcc_auth_domain = esc_attr( get_option('bcc_auth_domain') );
		if ($bcc_auth_domain == "") {
		  $bcc_auth_domain = "https://auth.bcc.no/";
		  update_option('bcc_auth_domain', $bcc_auth_domain);
		}

		$this->private_newsfeed_link = esc_attr( get_option('private_newsfeed_link') );
		if ($private_newsfeed_link == "") {
			$private_newsfeed_link = strtolower(str_replace("-","",trim(com_create_guid(), '{}')));
			update_option('private_newsfeed_link', $private_newsfeed_link);
		}

		$this->private_newsfeeds = get_option('private_newsfeeds');

		$this->load_dependencies();
	}

	/**
	 * Load the required dependencies for this plugin.
	 */
	private function load_dependencies() {
		include_once plugin_dir_path( dirname( __FILE__) ) . 'includes/single-signout.php';
		include_once plugin_dir_path( dirname( __FILE__) ) . 'includes/newsfeed.php';
		include_once plugin_dir_path( dirname( __FILE__) ) . 'includes/oidc-configuration.php';
		include_once plugin_dir_path( dirname( __FILE__) ) . 'includes/privacy-settings.php';
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 */
	public function run() {
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
			include_once 'settings-display.php';
		}
	}
}


?>