<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://https://shyamkbhandari.com.np/
 * @since      1.0.0
 *
 * @package    Wc_Integrate_Qr
 * @subpackage Wc_Integrate_Qr/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wc_Integrate_Qr
 * @subpackage Wc_Integrate_Qr/includes
 * @author     CNS <shyam.kumarc3@gmail.com>
 */
class Wc_Integrate_Qr_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wc-integrate-qr',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
