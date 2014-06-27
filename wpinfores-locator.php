<?php
/*
Plugin Name: Infores Product Locator
Version: 0.1
Plugin URI: http://americanlicorice.com/
Author: Cory Pratt
Author URI: http://americanlicorice.com/
Description: An easy-to-use store locator plugin that uses Infores (IRI) data to display information directly on your WordPress site.
*/

/*
 * 	Assigning some variables
*/
$plugin_url = WP_PLUGIN_URL . '/wpinfores-locator';
$options = array();
/*
 * 	Add a link to our plugin in the admin menu
 * 	under Settings > Infores Locator
*/

function wpinfores_locator_menu() {
	/*
	 *	Use the add_options_page function
	 *	add_options_page( $page_title, $menu_title, $capability, $menu-slug, $function)
	*/

	add_options_page(
		'Infores Store Locator Plugin',
		'Infores Locator',
		'manage_options',
		'wpinfores-locator',
		'wpinfores_locator_options_page'
	);
}
add_action( 'admin_menu', 'wpinfores_locator_menu');


function wpinfores_locator_options_page() {
	if (! current_user_can('manage_options')) {

		wp_die( 'You do not have sufficient permission to access this page.' );
	}

	global $plugin_url;
	global $options;

	if( isset( $_POST['wpinfores_form_submitted'] ) ) {

		$hidden_field = esc_html($_POST['wpinfores_form_submitted'] );

		if ( $hidden_field == 'Y' ) {
			$wpinfores_clientid = esc_html( $_POST['wpinfores_clientid']);
			$wpinfores_productfamilyid = esc_html( $_POST['wpinfores_productfamilyid']);

			$wpinfores_locator_checkresults = wpinfores_locator_checkresults( $wpinfores_clientid, $wpinfores_productfamilyid );

			$options['wpinfores_clientid'] 				= $wpinfores_clientid;
			$options['wpinfores_productfamilyid'] 		= $wpinfores_productfamilyid;
			//$options['wpinfores_locator_checkresults']	= $wpinfores_locator_checkresults;
			$options['last_updates']					= time();

			update_option( 'wpinfores_locator', $options );
		}
	}

	$options = get_option( 'wpinfores_locator' );

	if ($options != '' ) {
		$wpinfores_clientid = $options['wpinfores_clientid'];
		$wpinfores_productfamilyid = $options['wpinfores_productfamilyid'];

	}

	
	print_r( (string)$wpinfores_locator_checkresults );

	require( 'inc/options-page-wrapper.php' );
}

function wpinfores_locator_checkresults( $wpinfores_clientid, $wpinfores_productfamilyid ) {
	$url      = 'http://productlocator.infores.com/productlocator/products/products.pli?client_id=' . $wpinfores_clientid .'&brand_id=' . $wpinfores_productfamilyid;
	$response = wp_remote_get($url);
	$body     = wp_remote_retrieve_body($response);

	$xml  = simplexml_load_string($body);

	$error = $xml->error->message;
	if ($error){
		return $error;
	} else {
		return $xml;
	}
}


?>