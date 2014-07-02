<?php

/*
 *	This page is used to cache and return the product groups when the page is loaded.
 *	We have to do this through PHP because CORS is not supported.
 *	It should stay up-to-date should your make changes in the rel.csv file with IRI
*/

require_once( '../../../../wp-blog-header.php' );
$options = get_option( 'wpinfores_locator' );

if ($options != '' ) {
	$wpinfores_clientid = $options['wpinfores_clientid'];
	$wpinfores_productfamilyid = $options['wpinfores_productfamilyid'];
	$wpinfores_locator_checkresults = $options['wpinfores_locator_checkresults'];
}
$cacheName = 'somefile.xml.cache';
// generate the cache version if it doesn't exist or it's too old!
$ageInSeconds = 3600; // one hour
if(!file_exists($cacheName) || filemtime($cacheName) > time() + $ageInSeconds) {
$xmlUrl = "http://productlocator.infores.com/productlocator/products/products.pli?client_id=" . $wpinfores_clientid . "&brand_id=" . $wpinfores_productfamilyid . "&prod_lvl=group";
  $contents = file_get_contents($xmlUrl);
  file_put_contents($cacheName, $contents);
}

$xml = simplexml_load_file($cacheName);

echo $xml->asXML();

?>