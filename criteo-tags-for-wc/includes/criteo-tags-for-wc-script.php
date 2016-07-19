<?php
/**
 * @package  Criteo_Tags_for_WC
 * @category Integration
 * @author   Josiah Robinson
 */
if ( ! class_exists( 'Criteo_Tags_Script' ) ) :
class Criteo_Tags_Script {

	function criteo_tracking_code($order_id) {

		global $woocommerce, $current_user;
	      get_currentuserinfo();

		function get_this_products_parent_id($product) {
			if (isset($product->variation_data)) {
				$criteo_product_id = wp_get_post_parent_id( $product->id );
			} else {
				$criteo_product_id = $product->id;
			}
		}

		//Set email for cross-device tracking
		if ($user_ID) {
			$source_address = $current_user>user_email;
			$processed_address = strtolower($source_address); //convert address to lower case
			$processed_address = trim($processed_address); //trimming leading and trailing spaces
			$processed_address = mb_convert_encoding($processed_address, "UTF-8", "ISO-8859-1"); //conversion from ISO-8859-1 to UTF-8 (replace "ISO-8859-1" with the source encoding of your string)
			$processed_address = md5($processed_address); //hash address with MD5 algorithm
			$criteo_user = '{ event: "setEmail", email: "'.$processed_address.'" },';
		} endif;
	}
}
endif;
