<?php

/**
* Add widgets as an audience when the user is logging in
*/
add_filter('openid-connect-generic-auth-url', function( $url ) {
    $url.= '&audience=https%3A%2F%2Fwidgets.brunstad.org';
    return $url;
}); 
/**
* Add bcc widgets style
*/
add_action( 'wp_enqueue_scripts', 'register_bcc_widgets_styles' );
function register_bcc_widgets_styles() {
    wp_register_style( 'bcc_widgets_stylesheet','https://widgets.bcc.no/styles/widgets.css');
    wp_enqueue_style( 'bcc_widgets_stylesheet' );
}

if (get_option('bcc_topbar') == 1) {
	add_action('wp_head', 'add_topbar');
}



/** Widgets */

/** Topbar */
function add_topbar(){
	$access_token = BCC_Signon::get_access_token();

	if(empty( $access_token )){
		echo '<script type="text/javascript" id="script-bcc-topbar" src="https://widgets.bcc.no/widgets/topbarjs" data-authentication-type="none"></script>';
		return;
	}

	if ( strlen($access_token) < 30) {
		echo "<script type='text/javascript'>window.top.location='https://auth.bcc.no/?message=signout';</script>";
		return;
	}
	  
  	echo ('<script type="text/javascript" id="script-bcc-topbar" src="https://widgets.bcc.no/widgets/topbarjs" data-authentication-type="inline-access-token" data-access-token=' . $access_token .'></script>');
};


/** Week calendar */
add_shortcode( 'bcc-widgets-week-calendar', function ($attributes) {
    $access_token = BCC_Signon::get_access_token();

    // normalize attribute keys, lowercase
    $attributes = array_change_key_case((array)$attributes, CASE_LOWER);

    $html =  '<div id="bcc-calendar-week"></div>';
    $html .= '<script id="script-bcc-calendar-week" data-authentication-type="inline-access-token" data-access-token="' . $access_token .'" ';
    $html .= 'data-language="' . $attributes['language'] . '" data-maxdays="' .  $attributes['maxdays'] . '" data-maxappointments="' . $attributes['maxappointments'] . '" ';
    $html .= 'data-calendars="' . $attributes['calendars'] .'" data-fullcalendarurl="' .  $attributes['fullcalendarurl'] .'" ';
    $html .= 'src="https://widgets.bcc.no/widgets/CalendarWeekJs"></script>';

    return $html;

} );

/** Month calendar */
add_shortcode( 'bcc-widgets-month-calendar', function ($attributes) {
    $access_token = BCC_Signon::get_access_token();
    
    // normalize attribute keys, lowercase
    $attributes = array_change_key_case((array)$attributes, CASE_LOWER);

    $html =  '<div id="bcc-calendar-month"></div>';
    $html .= '<script id="script-bcc-calendar-month" data-authentication-type="inline-access-token" data-access-token="' . $access_token .'" ';
    $html .= 'data-language="' . $attributes['language'] . '"';
    $html .= 'data-calendars="' . $attributes['calendars'] .'" ';
    $html .= 'src="https://widgets.bcc.no/widgets/CalendarMonthJs"></script>';
    
    return $html;
} );