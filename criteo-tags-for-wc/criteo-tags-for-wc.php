<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @since             1.0.0
 * @package           Plugin_Name
 *
 * @wordpress-plugin
 * Plugin Name:       Criteo Tags for WooCommerce
 * Description:       Enables Criteo tags for WooCommerce sites
 * Version:           1.0.0
 * Author:            Josiah.us
 * Author URI:        http://josiah.us/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       plugin-name
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Check if WooCommerce is active

if ( ! class_exists( 'Criteo_Tags_for_WC' ) ) :
class Criteo_Tags_for_WC {
	/**
	* Construct the plugin.
	*/
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}
	/**
	* Initialize the plugin.
	*/
	public function init() {
		// Checks if WooCommerce is installed.
		if ( class_exists( 'WC_Integration' ) ) {
			// Include our integration class.
			include_once 'includes/criteo-tags-for-wc-integration.php';
			include_once 'includes/criteo-tags-for-wc-script.php';
			// Register the integration.
			add_filter( 'woocommerce_integrations', array( $this, 'add_integration' ) );
			add_action('woocommerce_after_shop_loop_item', 'list_prod_ids'); //for product listing pages
			add_action('wp_footer','criteo_tracking_code'); //place the code in the footer
		} else {
			// throw an admin error if you like
		}
	}
	/**
	 * Add a new integration to WooCommerce.
	 */
	public function add_integration( $integrations ) {
		$integrations[] = 'Criteo_Tags_Integration';
		return $integrations;
	}
/*
	function include_criteo_script() {
		include_once 'includes/criteo-tags-for-wc-script.php';
		add_action('woocommerce_after_shop_loop_item', 'list_prod_ids'); //for product listing pages
		add_action('wp_footer','criteo_tracking_code'); //place the code in the footer
	}
	*/

}
$Criteo_Tags_for_WC = new Criteo_Tags_for_WC( __FILE__ );
endif;
/*
// Add the integration to WooCommerce
function criteo_tags_for_wc_add_integration($integrations) {
		global $woocommerce;

		if (is_object($woocommerce)) {
				include_once( 'includes/criteo-tags-for-wc-integration.php' );
				$integrations[] = 'Criteo_Tags_Integration';
				return $integrations;
		}

}
add_filter('woocommerce_integrations', 'criteo_tags_for_wc_add_integration' );
*/
