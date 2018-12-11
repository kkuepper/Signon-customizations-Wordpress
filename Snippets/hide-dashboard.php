/* Disable Dashboard for non-administrators */
add_action('admin_init', 'disable_dashboard');
function disable_dashboard() {
	if (!is_user_logged_in()) {
		return null;
	}
	if (!current_user_can('administrator') && is_admin()) {
		wp_redirect(home_url());
		exit;
	}
}
