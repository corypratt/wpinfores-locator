<?php
/*
 *	This page is used to find stores and is called by a jquery script when the submit button is pressed.
*/

require_once( '../../../../wp-blog-header.php' );

$options = get_option( 'wpinfores_locator' );

if ($options != '' ) {
	$wpinfores_clientid = $options['wpinfores_clientid'];
	$wpinfores_productfamilyid = $options['wpinfores_productfamilyid'];
}

	$url      = 'http://productlocator.infores.com/productlocator/servlet/ProductLocatorEngine?clientid=' . $wpinfores_clientid .'&productfamilyid=' . $wpinfores_productfamilyid . '&productid=' . $_GET['productid'] . '&zip=' . $_GET['zip'] . '&searchradius=' . $_GET['searchradius'];
	$response = wp_remote_get($url);
	$body     = wp_remote_retrieve_body($response);

	$xml  = simplexml_load_string($body, 'SimpleXMLElement', LIBXML_NOCDATA);

	echo json_encode($xml);

?>