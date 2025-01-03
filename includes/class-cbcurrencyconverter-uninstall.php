<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Fired during plugin uninstallation
 *
 * @link       https://codeboxr.com
 * @since      1.0.0
 *
 * @package    CBCurrencyConverter
 * @subpackage CBCurrencyConverter/includes
 */

/**
 * Fired during plugin uninstallation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    CBCurrencyConverter
 * @subpackage CBCurrencyConverter/includes
 * @author     codeboxr <info@codeboxr.com>
 */
class CBCurrencyConverter_Uninstall {
	/**
	 * Uninstall plugin functionality
	 *
	 *
	 * @since    1.0.0
	 */
	public static function uninstall() {
		// For the regular site.
		if ( ! is_multisite() ) {
			self::uninstall_tasks();
		}
		else{
			//for multi site
			global $wpdb;

			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
			$original_blog_id = get_current_blog_id();

			foreach ( $blog_ids as $blog_id )   {
				switch_to_blog( $blog_id );

				self::uninstall_tasks();
			}

			switch_to_blog( $original_blog_id );
		}
	}//end method uninstall

	/**
	 * Do the necessary uninstall tasks
	 *
	 * @since 3.1.1
	 * @return void
	 */
	public static function uninstall_tasks() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}


		$settings             = new CBCurrencyconverterSetting();
		$delete_global_config = $settings->get_option( 'delete_options', 'cbcurrencyconverter_tools', '' );

		if ( $delete_global_config == 'on' ) {
			//delete plugin options
			$option_values = CBCurrencyConverterHelper::getAllOptionNames();

			do_action('cbcurrencyconverter_plugin_options_deleted_before');

			foreach ( $option_values as $option_value ) {
				//delete_option( $option_value['option_name'] );
				$option = $option_value['option_name'];

				do_action('cbcurrencyconverter_plugin_option_delete_before', $option);
				delete_option( $option );
				do_action('cbcurrencyconverter_plugin_option_delete_after', $option);
			}

			do_action( 'cbcurrencyconverter_plugin_options_deleted_after' );
			do_action( 'cbcurrencyconverter_plugin_options_deleted' );

			//delete plugin transient caches
			$transient_caches = CBCurrencyConverterHelper::getAllTransientCacheNames();

			do_action('cbcurrencyconverter_plugin_transient_caches_deleted_before');

			foreach ( $transient_caches as $transient_cache ) {
				do_action('cbcurrencyconverter_plugin_transient_cache_delete_before', $transient_cache);
				delete_transient( $transient_cache );
				do_action('cbcurrencyconverter_plugin_transient_cache_delete_after', $transient_cache);
			}

			do_action('cbcurrencyconverter_plugin_transient_caches_deleted_after');
			do_action('cbcurrencyconverter_plugin_transient_caches_deleted');
			//end delete plugin transient caches

			do_action( 'cbcurrencyconverter_plugin_uninstall' );
		}
	}//end uninstall

}//end class CBCurrencyConverter_Uninstall