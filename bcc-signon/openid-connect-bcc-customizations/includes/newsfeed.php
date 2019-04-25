<?php

/**
 * Computes the private feed link
 */
function get_private_link_feed(){
	return '/feed/?id=' . get_option('private_newsfeed_link');
 }

/**
 * Checks if the private link feed is registered within this Wordpress instance
 */
function private_link_feed() {
	if($_SERVER['REQUEST_URI'] != get_private_link_feed()){
	wp_die(__('No feed available'));  
	}
}

/**
 * Adds feed if private newsfeeds is checked
 */
if (get_option('private_newsfeeds')) {
	add_action('do_feed', 'private_link_feed', 1);
	add_action('do_feed_rdf', 'private_link_feed', 1);
	add_action('do_feed_rss', 'private_link_feed', 1);
	add_action('do_feed_rss2', 'private_link_feed', 1);
	add_action('do_feed_atom', 'private_link_feed', 1);

	// Remove feed links from header
	remove_action( 'wp_head', 'feed_links_extra', 3 ); 
	remove_action( 'wp_head', 'feed_links', 2 );
}


?>