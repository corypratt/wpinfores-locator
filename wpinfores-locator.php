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

/*
 *	Displaying the options page
*/
function wpinfores_locator_options_page() {

	/*
	 *	Fail if permissions aren't high enough
	*/
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
			$options['wpinfores_locator_checkresults']	= $wpinfores_locator_checkresults;
			$options['last_updates']					= time();

			update_option( 'wpinfores_locator', $options );
		}
	}
	$options = get_option( 'wpinfores_locator' );

	if ($options != '' ) {
		$wpinfores_clientid = $options['wpinfores_clientid'];
		$wpinfores_productfamilyid = $options['wpinfores_productfamilyid'];
		$wpinfores_locator_checkresults = $options['wpinfores_locator_checkresults'];
	}

	require( 'inc/options-page-wrapper.php' );
}

/*
 *	A pretty basic check to see if information is returned.
*/
function wpinfores_locator_checkresults( $wpinfores_clientid, $wpinfores_productfamilyid ) {

	$url      = 'http://productlocator.infores.com/productlocator/products/products.pli?client_id=' . $wpinfores_clientid .'&brand_id=' . $wpinfores_productfamilyid;
	$response = wp_remote_get($url);
	$body     = wp_remote_retrieve_body($response);

	$xml  = simplexml_load_string($body);
	$upc = $xml->upc_code;

	$error = $xml->error->message;
	
	/*
	 *	Basic error checking, only to see if a number was perhaps entered wrong
	*/
	if ($error !=''){
		$html =  '<div class="error"><p>';
		$html .= 'It looks like something went wrong. Perhaps this error will help? - ' . $error;
		$html .= '</p></div>';
		
		echo $html;
		 return false;
	} else {
		$html =  '<div class="updated"><p>';
		$html .= 'Looks like we have made a solid connection!';
		$html .= '</p></div>';
		
		echo $html;
	 return true;
	}

}


/*
 *	This is the shortcode.
*/
function wpinfores_show_locator( $atts ){
	extract( $atts );

/*
 *	Code for the form
*/
	//$form_display = ob_get_clean();
	$form_display .= '<div id="inforessearchForm">';
	$form_display .= '<div class="error" style="display:none;"></div>';
	$form_display .= '<div class="form-group"><label for="zip">Enter Your Zip Code</label><input id="zip" autocomplete="off" name="zipline" type="text" placeholder="Enter your Zipcode" /></div>';
	$form_display .= '<div class="form-group"><label for="searchRadius">Find Stores Within:</label>
					  <select id="searchRadius">
					  <option value="10">10 Miles</option>
					  <option value="15">15 Miles</option>
					  <option value="20">20 Miles</option>
					  <option value="50">50 Miles</option>
					  </select></div>';
	$form_display .= '<div class="form-group"><label for="productList">Please Choose A Brand</label>
					  <select id="productList"><option>Loading List</option></select></div>';
	$form_display .= '<button type="submit" value="Search" onClick="brandLocator()">Search</button>';
	$form_display .= '</div>';
	$form_display .= '<div class="inforsearch-loader" style="display:none;">Loading...</div>';
	$form_display .= '<ul class="shop-results" style="display: block;"></ul>';


	return $form_display;
}
add_shortcode('infores', 'wpinfores_show_locator');


/*
 *	Enqueue our scripts and styles
*/
function enqueue_scripts() {
	wp_enqueue_style( 'wpinfores-style', plugins_url( '/inc/wpinfores-style.css' , __FILE__ ) );
	wp_enqueue_script( 'wpinfores_js', plugins_url( '/js/wpinfores-locator.js' , __FILE__ ), false, false, true );
	
	/*
	 *	Sending plugin options to our scripts.
	*/
	global $options;
	$options = get_option( 'wpinfores_locator' );

	if ($options != '' ) {
		$wpinfores_clientid = $options['wpinfores_clientid'];
		$wpinfores_productfamilyid = $options['wpinfores_productfamilyid'];
		$wpinfores_locator_checkresults = $options['wpinfores_locator_checkresults'];
	}

	/*
	 *	Link to the PHP page that will cache the groups for the select field to be used by the ajax script.
	*/
	$data = array('url' => plugins_url( '/inc/wpinfores-groupcache.php' , __FILE__ ) );
 	wp_localize_script( 'wpinfores_js', 'wpinfores_group_info', $data );
}

add_action( 'wp_enqueue_scripts', 'enqueue_scripts' );





?>