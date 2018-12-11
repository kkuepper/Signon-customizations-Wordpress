/* Disable Admin ToolBar for non-administrators */
add_filter('show_admin_bar', 'disable_admin_bar');
function disable_admin_bar() {
	if (!current_user_can('administrator') && is_admin()) {
		return false;
	}
}