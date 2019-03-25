function private_link_feed() {
	if($_SERVER['REQUEST_URI'] != '/feed/?id=4397c38e45c64987b7831f918e4ae9a7'){
 		wp_die(__('No feed available'));	
	}
}

//Disable RSS Feeds functions
add_action('do_feed', 'private_link_feed', 1);
add_action('do_feed_rdf', 'private_link_feed', 1);
add_action('do_feed_rss', 'private_link_feed', 1);
add_action('do_feed_rss2', 'private_link_feed', 1);
add_action('do_feed_atom', 'private_link_feed', 1);
// Remove feed links from header
remove_action( 'wp_head', 'feed_links_extra', 3 ); 
remove_action( 'wp_head', 'feed_links', 2 );