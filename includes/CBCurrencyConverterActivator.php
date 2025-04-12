<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Fired during plugin activation
 *
 * @link       https://codeboxr.com
 * @since      1.0.0
 *
 * @package    CBCurrencyConverter
 * @subpackage CBCurrencyConverter/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    CBCurrencyConverter
 * @subpackage CBCurrencyConverter/includes
 * @author     codeboxr <info@codeboxr.com>
 */
class CBCurrencyConverterActivator {
	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		do_action( 'cbcurrencyconverter_plugin_activate' );

		$wp_version  = '5.3';
		$php_version = '7.4';

		if ( ! cbcurrencyconverter_compatible_wp_version( $wp_version ) ) {
			deactivate_plugins( plugin_basename( __FILE__ ) );
			//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			wp_die( sprintf( esc_html__( 'CBX Currency Converter plugin requires WordPress %$1s or higher!', 'cbcurrencyconverter' ), $wp_version ) );
		}

		if ( ! cbcurrencyconverter_compatible_php_version( $php_version ) ) {
			deactivate_plugins( plugin_basename( __FILE__ ) );
			//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			wp_die( sprintf( esc_html__( 'CBX Currency Converter plugin requires PHP %$1s or higher!', 'cbcurrencyconverter' ), $php_version ) );
		}

		set_transient( 'cbcurrencyconverter_activated_notice', 1 );

		// Update the saved version
		update_option( 'cbcurrencyconverter_version', CBCURRENCYCONVERTER_VERSION );

		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}

		if ( in_array( 'cbcurrencyconverteraddon/cbcurrencyconverteraddon.php', apply_filters( 'active_plugins',
				get_option( 'active_plugins' ) ) ) || defined( 'CBCURRENCYCONVERTERADDON_NAME' ) ) {
			//plugin is activated

			$pro_plugin_version = CBCURRENCYCONVERTERADDON_VERSION;


			if ( version_compare( $pro_plugin_version, '1.7.0', '<' ) ) {
				deactivate_plugins( 'cbcurrencyconverteraddon/cbcurrencyconverteraddon.php' );
				set_transient( 'cbcurrencyconverteraddon_forcedactivated_notice', 1 );
			}
		}
	}//end activate
}//end class CBCurrencyConverterActivator