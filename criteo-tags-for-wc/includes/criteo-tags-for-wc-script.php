<?php
/**
 * @package  Criteo_Tags_for_WC
 * @category Integration
 * @author   Josiah Robinson
 *
if ( ! class_exists( 'Criteo_Tags_Script' ) ) :
class Criteo_Tags_Script {
 */

	//add_action('woocommerce_after_shop_loop_item', 'list_prod_ids'); //for product listing pages
	function list_prod_ids() {
	  global $product, $list_of_product_ids;
	  $list_of_product_ids[] = '"'.$product->id.'"';
	  echo '<!--' . $product->id . '-->';
	}

	add_action('wp_footer','criteo_tracking_code'); //place the code in the footer
	function criteo_tracking_code($order_id) {
		global $woocommerce, $user_email, $this_is_my_id;
    get_currentuserinfo();

		//Set email for cross-device tracking
		if ($user_email) {
			$processed_address = strtolower($user_email); //convert address to lower case
			$processed_address = trim($processed_address); //trimming leading and trailing spaces
			$processed_address = mb_convert_encoding($processed_address, "UTF-8", "ISO-8859-1"); //conversion from ISO-8859-1 to UTF-8 (replace "ISO-8859-1" with the source encoding of your string)
			$processed_address = md5($processed_address); //hash address with MD5 algorithm
			$criteo_user = '{ event: "setEmail", email: "'.$processed_address.'" },';
		} else {
			$criteo_user = '{ event: "setEmail", email: "" },';
		} endif;

		//Set up the criteo tracking code;
		$code = '<script type="text/javascript" src="<!--//static.criteo.net/js/ld/ld.js-->" async="true"></script>
		<script type="text/javascript">
		window.criteo_q = window.criteo_q || [];
		var deviceType = /iPad/.test(navigator.userAgent) ? "t" : /Mobile|iP(hone|od)|Android|BlackBerry|IEMobile|Silk/.test(navigator.userAgent) ? "m" : "d";
		window.criteo_q.push(
		 { event: "setAccount", account: '.$criteo_account.' },
		 { event: "setSiteType", type: deviceType },
		 { event: "setEmail", email: "'.$processed_address.'" },
		 '. $criteo_tracking_event . '
		);
		</script>';

		if (is_home()) {
		$criteo_tracking_event = '{ event: "viewHome" }';

		} elseif (is_shop() || is_product_category() || is_product_tag() ) {
			$_ids_list = implode(', ', $list_of_product_ids);
			$criteo_tracking_event = '{ event: "viewList", item:[ ' . $_ids_list . ']}';

		} elseif (is_product()) {
			global $product;
			$criteo_tracking_event = '{ event: "viewItem", item: "' . $product->id . '" }';

		} elseif (is_cart()) {
			$items = $woocommerce->cart->get_cart();
			$criteo_cart_items = array();
			foreach ( $items as $item => $values ) {
				$_product = $values['data']->post;
				$_id = $values['data']->post->ID;
				$_quantity = $values['quantity'];
				$_price = $values['line_subtotal'] / $_quantity;

				$criteo_cart_items[] = '
				{ id: "' . $_id . '", price: ' . $_price . ', quantity: ' . $_quantity . '}';

			}
			$criteo_tracking_event = '{ event: "viewBasket", item: [
				' . implode(', ', $criteo_cart_items); . '
				]}';

		} elseif (is_order_received_page()) {
			global $wp;
			$order_id = isset( $wp->query_vars['order-received'] ) ? intval( $wp->query_vars['order-received'] ) : 0;
			$order = new WC_Order( $order_id );
			if ( $order && ! $order->has_status( 'failed' ) ) :
				// This is how to grab line items from the order
				$line_items = $order->get_items();

				// This loops over line items
				foreach ( $line_items as $item ) {
					// This will be a product
					$product = $order->get_product_from_item( $item );

					$_id = $product->id;
					$_quantity = $item[qty];
					$_price = $item[line_subtotal] / $_quantity;

					// Line item subtotal (before discounts)
					$subtotal = $order->get_line_subtotal( $item );

					$criteo_order_items[] = '
					{ id: "' . $_id . '", price: ' . $_price . ', quantity: ' . $_quantity . '}';

				}
				$criteo_order_items_list = implode(', ', $criteo_order_items);

				$criteo_tracking_event = '{ event: "trackTransaction", id: ' . $order_id . ', item: [' . $criteo_order_items_list . '
					]}';
			endif;
		}
		echo $code;
		echo '<!-- criteo success! -->';
	}

//}
//endif;
