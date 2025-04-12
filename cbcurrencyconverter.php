<?php
/**
 * @link              https://codeboxr.com
 * @since             1.0.0
 * @package           cbcurrencyconverter
 *
 * @wordpress-plugin
 * Plugin Name:       CBX Currency Converter
 * Plugin URI:        https://codeboxr.com/product/cbx-currency-converter-for-wordpress/
 * Description:       Currency Converter and rate display
 * Version:           3.1.8
 * Author:            codeboxr
 * Author URI:        https://codeboxr.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cbcurrencyconverter
 * Domain Path:       /languages
 * @copyright         codeboxr.com
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

defined( 'CBCURRENCYCONVERTER_NAME' ) or define( 'CBCURRENCYCONVERTER_NAME', 'cbcurrencyconverter' );
defined( 'CBCURRENCYCONVERTER_VERSION' ) or define( 'CBCURRENCYCONVERTER_VERSION', '3.1.8' );
defined( 'CBCURRENCYCONVERTER_ROOT_PATH' ) or define( 'CBCURRENCYCONVERTER_ROOT_PATH', plugin_dir_path( __FILE__ ) );
defined( 'CBCURRENCYCONVERTER_ROOT_URL' ) or define( 'CBCURRENCYCONVERTER_ROOT_URL', plugin_dir_url( __FILE__ ) );
defined( 'CBCURRENCYCONVERTER_BASE_NAME' ) or define( 'CBCURRENCYCONVERTER_BASE_NAME', plugin_basename( __FILE__ ) );


// Include the main class
if ( ! class_exists( 'CBCurrencyConverter', false ) ) {
	include_once CBCURRENCYCONVERTER_ROOT_PATH . 'includes/CBCurrencyConverter.php';
}

/**
 * Checking wp version
 *
 * @return bool
 */
function cbcurrencyconverter_compatible_wp_version($version = '5.3' ) {
	if ( version_compare( $GLOBALS['wp_version'], $version, '<' ) ) {
		return false;
	}

	// Add sanity checks for other version requirements here
	return true;
}//end function cbcurrencyconverter_compatible_wp_version

/**
 * Checking php version
 *
 * @return bool
 */
function cbcurrencyconverter_compatible_php_version($version = '7.4' ) {
	if ( version_compare( PHP_VERSION, $version, '<=' ) ) {
		return false;
	}

	return true;
}//end function cbcurrencyconverter_compatible_php_version

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/CBCurrencyConverterActivator.php
 */
function activate_cbcurrencyconverter() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/CBCurrencyConverterActivator.php';
	CBCurrencyConverterActivator::activate();
}//end method activate_cbcurrencyconverter

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/CBCurrencyConverterDeactivator.php
 */
function deactivate_cbcurrencyconverter() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/CBCurrencyConverterDeactivator.php';
	CBCurrencyConverterDeactivator::deactivate();
}//end method deactivate_cbcurrencyconverter

register_activation_hook( __FILE__, 'activate_cbcurrencyconverter' );
register_deactivation_hook( __FILE__, 'deactivate_cbcurrencyconverter' );


/**
 * Manually init the plugin
 *
 * @return CBCurrencyConverter|null
 */
function cbcurrencyconverter_core() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
	global $cbcurrencyconverter_core;
	if ( ! isset( $cbcurrencyconverter_core ) ) {
		$cbcurrencyconverter_core = run_cbcurrencyconverter_pro();
	}

	return $cbcurrencyconverter_core;
}//end method cbcurrencyconverter_core

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    3.0.7
 */
function run_cbcurrencyconverter() {
	return CBCurrencyConverter::instance();
}

$GLOBALS['cbcurrencyconverter_core'] = run_cbcurrencyconverter();