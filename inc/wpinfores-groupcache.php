<?php
require_once('../../../../wp-config.php');
$options = get_option( 'wpinfores_locator' );
if ($options != '' ) { $wpinfores_clientid = $options['wpinfores_clientid']; $wpinfores_productfamilyid = $options['wpinfores_productfamilyid'];} 
$cacheName = 'somefile.xml.cache';
$ageInSeconds = 3600;
if(!file_exists($cacheName) || filemtime($cacheName) > time() + $ageInSeconds) {
$xmlUrl = "http://productlocator.infores.com/productlocator/products/products.pli?client_id=" . $wpinfores_clientid . "&brand_id=" . $wpinfores_productfamilyid . "&prod_lvl=group";
  $contents = file_get_contents($xmlUrl);
  file_put_contents($cacheName, $contents);
}
$xml = simplexml_load_file($cacheName);
echo $xml->asXML();
?>