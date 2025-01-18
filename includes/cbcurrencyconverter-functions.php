<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Wrapper for deprecated functions so we can apply some extra logic.
 *
 * @param  string  $function
 * @param  string  $version
 * @param  string  $replacement
 *
 * @since  2.2.0
 *
 */
function cbcurrencyconverter_deprecated_function( $function, $version, $replacement = null ) {
	if ( defined( 'DOING_AJAX' ) ) {
		do_action( 'deprecated_function_run', $function, $replacement, $version );
		$log_string = "The {$function} function is deprecated since version {$version}."; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		$log_string .= $replacement ? " Replace with {$replacement}." : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if(defined('WP_DEBUG') && WP_DEBUG && defined('WP_DEBUG_LOG') && WP_DEBUG_LOG){
			error_log( $log_string );//phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
		}
	} else {
		_deprecated_function( $function, $version, $replacement ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}//end function cbcurrencyconverter_deprecated_function

if ( ! function_exists( 'cbcurrencyconverter_get_rate' ) ) {
	/**
	 * Get currency rate
	 *
	 * @param $price
	 * @param $convertfrom
	 * @param $convertto
	 * @param  int  $decimal_point
	 *
	 * @return mixed|void
	 */
	function cbcurrencyconverter_get_rate( $price, $convertfrom, $convertto, $decimal_point = 2 ) {
		return CBCurrencyConverterHelper::cbcurrencyconverter_get_rate( $price, $convertfrom, $convertto, $decimal_point );
	}//end function cbcurrencyconverter_get_rate
}

if ( ! function_exists( 'cbxcccalcview' ) ):
	/**
	 * Shows the calculator form
	 *
	 * @param  string  $reference  shortcode or widget
	 * @param  array  $instance
	 *
	 * @return string
	 *
	 */
	function cbxcccalcview( $reference = 'shortcode', $instance = [] ) {
		cbcurrencyconverter_deprecated_function( 'cbxcccalcview function', '2.2', 'CBCurrencyConverterHelper::cbxcccalcview' );

		return CBCurrencyConverterHelper::cbxcccalcview( $reference = 'shortcode', $instance );
	}//end function cbxcccalcview
endif;


if ( ! function_exists( 'cbxcclistview' ) ):
	/**
	 * currencly list layout
	 *
	 * @param  string  $reference  shortcode or widget
	 * @param  array  $instance
	 *
	 * @return string
	 */
	function cbxcclistview( $reference = 'shortcode', $instance = [] ) {
		cbcurrencyconverter_deprecated_function( 'cbxcclistview function', '2.2', 'CBCurrencyConverterHelper::cbxcclistview' );

		return CBCurrencyConverterHelper::cbxcclistview( $reference = 'shortcode', $instance );
	}//end function cbxcclistview
endif;

/**
 * Shows the calculator in woocommerce
 *
 * @param  string  $reference
 * @param  array  $instance
 *
 * @return string
 */
if ( ! function_exists( 'cbxcccalcinline' ) ):
	function cbxcccalcinline( $reference = 'shortcode', $instance = [] ) {
		cbcurrencyconverter_deprecated_function( 'cbxcccalcinline function', '2.2', 'CBCurrencyConverterHelper::cbxcccalcinline' );

		return CBCurrencyConverterHelper::cbxcccalcinline( $reference = 'shortcode', $instance );
	}//end function cbxcccalcinline
endif;

if ( ! function_exists( 'cbcurrencyconverter_first_value' ) ) {
	function cbcurrencyconverter_first_value( $arr ) {
		//return array_shift(array_values(&$arr));;

		$first_key = array_key_first( $arr );
		if ( $first_key === false ) {
			return '';
		}

		return $arr[ $first_key ];
	}//end function cbcurrencyconverter_first_value
}

if ( ! function_exists( 'cbcurrencyconverter_get_symbols' ) ) {
	/**
	 * All currency symbols
	 *
	 * @return mixed|null
	 * @since 3.0.9
	 *
	 */
	function cbcurrencyconverter_get_symbols() {
		return CBCurrencyConverterHelper::getCurrencySymbols();
	}//end function cbcurrencyconverter_get_symbols
}

if ( ! function_exists( 'cbcurrencyconverter_get_symbol' ) ) {
	/**
	 * Get symbold for a currency using currency code
	 *
	 * @param $currency_code
	 *
	 * @return mixed|string
	 * @since 3.0.9
	 *
	 */
	function cbcurrencyconverter_get_symbol( $currency_code = '' ) {
		return CBCurrencyConverterHelper::getCurrencySymbol( $currency_code );
	}//end function cbcurrencyconverter_get_symbol
}


if(!function_exists('cbcurrencyconverter_rate')){
	/**
	 * Direct currency rate
	 *
	 * @param $from
	 * @param $to
	 * @param $amount
	 * @param $decimal_point
	 *
	 * @return string
	 * @since v3.1.0
	 */
	function cbcurrencyconverter_rate($from = '', $to = '', $amount = 1, $decimal_point = 2){
		return CBCurrencyConverterHelper::cbcurrencyconverter_rate($from, $to, $amount, $decimal_point);
	}//end function cbcurrencyconverter_rate
}

if ( ! function_exists( 'cbcurrencyconverter_icon_path' ) ) {
	/**
	 * Resume icon path
	 *
	 * @return mixed|null
	 * @since 1.0.0
	 */
	function cbcurrencyconverter_icon_path() {
		$directory = trailingslashit( CBCURRENCYCONVERTER_ROOT_PATH ) . 'assets/icons/';

		return apply_filters( 'cbcurrencyconverter_icon_path', $directory );
	}//end method cbcurrencyconverter_icon_path
}


if ( ! function_exists( 'cbcurrencyconverter_load_svg' ) ) {
	/**
	 * Load an SVG file from a directory.
	 *
	 * @param string $svg_name The name of the SVG file (without the .svg extension).
	 * @param string $directory The directory where the SVG files are stored.
	 *
	 * @return string|false The SVG content if found, or false on failure.
	 * @since 1.0.0
	 */
	function cbcurrencyconverter_load_svg( $svg_name = '', $folder = '' ) {
		//note: code partially generated using chatgpt
		if ( $svg_name == '' ) {
			return '';
		}

		if ( ! function_exists( 'WP_Filesystem' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
		}

		$credentials = request_filesystem_credentials( site_url() . '/wp-admin/', '', false, false, null );
		if ( ! WP_Filesystem( $credentials ) ) {
			return ''; // Error handling here
		}

		global $wp_filesystem;

		$directory = cbcurrencyconverter_icon_path();

		// Sanitize the file name to prevent directory traversal attacks.
		$svg_name = sanitize_file_name( $svg_name );

		if($folder != ''){
			$folder = trailingslashit($folder);
		}

		// Construct the full file path.
		$file_path = $directory. $folder . $svg_name . '.svg';
		$file_path = apply_filters('cbcurrencyconverter_svg_file_path', $file_path, $svg_name);

		// Check if the file exists.
		//if ( file_exists( $file_path ) && is_readable( $file_path ) ) {
		if ( $wp_filesystem->exists( $file_path ) && is_readable( $file_path ) ) {
			// Get the SVG file content.
			return $wp_filesystem->get_contents( $file_path );
		} else {
			// Return false if the file does not exist or is not readable.
			return '';
		}
	}//end method cbcurrencyconverter_load_svg
}


if(!function_exists('cbcurrencyconverter_sanitize_wp_kses')){
	function  cbcurrencyconverter_sanitize_wp_kses() {
		return CBCurrencyConverterHelper::sanitize_wp_kses();
	}//end function cbcurrencyconverter_sanitize_wp_kses
}