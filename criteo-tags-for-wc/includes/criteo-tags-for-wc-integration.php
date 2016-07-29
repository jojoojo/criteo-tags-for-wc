<?php
/**
 * @package  Criteo_Tags_for_WC
 * @category Integration
 * @author   Josiah Robinson
 */
if ( ! class_exists( 'Criteo_Tags_Integration' ) ) :
class Criteo_Tags_Integration extends WC_Integration {
	/**
	 * Init and hook in the integration.
	 */
	public function __construct() {
		global $woocommerce;
		$this->id                 = 'criteo-tags-for-wc';
		$this->method_title       = __( 'Criteo Tracking', 'criteo-tags-for-wc' );
		$this->method_description = __( 'Add criteo tag script to WooCommerce.', 'criteo-tags-for-wc' );
		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();
		// Define user set variables.
		$this->criteo_account          = $this->get_option( 'criteo_account' );
		$this->product_identifier      = $this->get_option( 'product_identifier' );
		// Actions.
		add_action( 'woocommerce_update_options_integration_' .  $this->id, array( $this, 'process_admin_options' ) );
	}
	/**
	 * Initialize integration settings form fields.
	 */
	public function init_form_fields() {
		$this->form_fields = array(
			'criteo_account' => array(
				'title'             => __( 'Criteo Account', 'criteo-tags-for-wc' ),
				'type'              => 'text',
				'description'       => __( 'Enter with your Criteo Account Number.', 'criteo-tags-for-wc' ),
				'desc_tip'          => true,
				'default'           => ''
			),
			'product_identifier' => array(
				'title'             => __( 'product_identifier', 'criteo-tags-for-wc' ),
				'type'              => 'select',
				'label'             => __( 'Select which is used for Unique Product IDs', 'criteo-tags-for-wc' ),
        'options'           => array(
                      					'id'            => __( 'Product ID', 'criteo-tags-for-wc' ),
                      					'sku'       => __( 'Product SKU', 'criteo-tags-for-wc' ),
                      				),
				'default'           => 'id'
			),
		);
	}
}
endif;
