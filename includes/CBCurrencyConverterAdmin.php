<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://codeboxr.com
 * @since      1.0.0
 *
 * @package    CBCurrencyConverter
 * @subpackage CBCurrencyConverter/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    CBCurrencyConverter
 * @subpackage CBCurrencyConverter/admin
 * @author     codeboxr <info@codeboxr.com>
 */
class CBCurrencyConverterAdmin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;


	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * for setting
	 * @since    1.0.0
	 * @access   private
	 * @var      string $settings The current version of this plugin.
	 * */
	private $settings;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param  string  $plugin_name  The name of this plugin.
	 * @param  string  $version  The version of this plugin.
	 *
	 * @since    1.0.0
	 *
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			$this->version = current_time( 'timestamp' ); //for development time only
		}

		$this->settings = new CBCurrencyconverterSetting();
	}//end of oonstructor

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles( $hook ) {
		$version = $this->version;
		$page    = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		$css_url_part     = CBCURRENCYCONVERTER_ROOT_URL . 'assets/css/';
		$js_url_part      = CBCURRENCYCONVERTER_ROOT_URL . 'assets/js/';
		$vendors_url_part = CBCURRENCYCONVERTER_ROOT_URL . 'assets/vendors/';

		$css_path_part     = CBCURRENCYCONVERTER_ROOT_PATH . 'assets/css/';
		$js_path_part      = CBCURRENCYCONVERTER_ROOT_PATH . 'assets/js/';
		$vendors_path_part = CBCURRENCYCONVERTER_ROOT_PATH . 'assets/vendors/';


		if ( $page == 'cbcurrencyconverter' ) {

			wp_register_style( 'awesome-notifications', $vendors_url_part . 'awesome-notifications/style.css', [], $version );
			wp_register_style( 'select2', $vendors_url_part . 'select2/css/select2.min.css', [], $version );
			wp_register_style( 'pickr', $vendors_url_part . 'pickr/themes/classic.min.css', [], $version );


			wp_enqueue_style( 'pickr' );

			wp_register_style( 'cbcurrencyconverter-public', $css_url_part . 'cbcurrencyconverter-public.css', [ 'select2' ], $version, 'all' );
			wp_register_style( 'cbcurrencyconverter-admin', $css_url_part . 'cbcurrencyconverter-admin.css', [], $version );

			wp_register_style( 'cbcurrencyconverter-setting', $css_url_part . 'cbcurrencyconverter-setting.css', [
				'pickr',
				'select2',
				'awesome-notifications',
				'cbcurrencyconverter-public',
				'cbcurrencyconverter-admin'
			], $version );


			wp_enqueue_style( 'select2' );
			wp_enqueue_style( 'awesome-notifications' );

			wp_enqueue_style( 'cbcurrencyconverter-public' );
			wp_enqueue_style( 'cbcurrencyconverter-admin' );

			wp_enqueue_style( 'cbcurrencyconverter-setting' );
		}

		//widgets pages
		if ( $hook == 'widgets.php' ) {
			wp_register_style( 'select2', $vendors_url_part . 'select2/css/select2.min.css', [], $version );

			wp_register_style( 'cbcurrencyconverter-widget', $css_url_part . 'cbcurrencyconverter-widget.css', [
				'select2'
			], $version );

			wp_enqueue_style( 'select2' );
			wp_enqueue_style( 'cbcurrencyconverter-widget' );
		}
	}//end enqueue_styles

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts( $hook ) {
		$version = $this->version;
		$page    = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended


		$css_url_part     = CBCURRENCYCONVERTER_ROOT_URL . 'assets/css/';
		$js_url_part      = CBCURRENCYCONVERTER_ROOT_URL . 'assets/js/';
		$vendors_url_part = CBCURRENCYCONVERTER_ROOT_URL . 'assets/vendors/';

		$css_path_part     = CBCURRENCYCONVERTER_ROOT_PATH . 'assets/css/';
		$js_path_part      = CBCURRENCYCONVERTER_ROOT_PATH . 'assets/js/';
		$vendors_path_part = CBCURRENCYCONVERTER_ROOT_PATH . 'assets/vendors/';

		$all_currencies = CBCurrencyConverterHelper::getCurrencyList();


		//main setting page
		if ( $page == 'cbcurrencyconverter' ) {
			wp_register_script( 'awesome-notifications', $vendors_url_part . 'awesome-notifications/script.js', [], $version, true );
			wp_register_script( 'select2', $vendors_url_part . 'select2/js/select2.full.min.js', [ 'jquery' ], $version, true );
			wp_register_script( 'pickr', $vendors_url_part . 'pickr/pickr.min.js', [], $version, true );

			wp_register_script( 'cbcurrencyconverter-public', $js_url_part . 'cbcurrencyconverter-public.js', [
				'jquery',
				'select2',
				'awesome-notifications'
			], $version, true );


			$ajax_nonce = wp_create_nonce( 'cbcurrencyconverter_nonce' );

			wp_localize_script( 'cbcurrencyconverter-public',
				'cbcurrencyconverter_public',
				[
					'all_currencies'       => $all_currencies,
					'ajaxurl'              => admin_url( 'admin-ajax.php' ),
					'nonce'                => $ajax_nonce,
					'empty_selection'      => esc_html__( 'Please choose from or to currency properly', 'cbcurrencyconverter' ),
					'same_selection'       => esc_html__( 'From and to currency both are same!', 'cbcurrencyconverter' ),
					'please_select'        => esc_html__( 'Please Select', 'cbcurrencyconverter' ),
					'please_wait'          => esc_html__( 'Please wait, processing.', 'cbcurrencyconverter' ),
					'select_currency'      => esc_html__( 'Select Currency', 'cbcurrencyconverter' ),
					'select_currency_from' => esc_html__( 'Select From Currency', 'cbcurrencyconverter' ),
					'select_currency_to'   => esc_html__( 'Select To Currency', 'cbcurrencyconverter' ),

				] );


			wp_register_script( 'cbcurrencyconverter-setting', $js_url_part . 'cbcurrencyconverter-setting.js',
				[
					'pickr',
					'select2',
					'jquery',
				], $version, true );

			// Localize the script with new data
			$translation_array = [
				'ajaxurl'           => admin_url( 'admin-ajax.php' ),
				'nonce'             => wp_create_nonce( "settingsnonce" ),
				'ajax_fail'         => esc_html__( 'Request failed, please reload the page.', 'cbcurrencyconverter' ),
				'is_user_logged_in' => is_user_logged_in() ? 1 : 0,
				'please_select'     => esc_html__( 'Please Select', 'cbcurrencyconverter' ),
				'upload_btn'        => esc_html__( 'Upload', 'cbcurrencyconverter' ),
				'upload_title'      => esc_html__( 'Select Media', 'cbcurrencyconverter' ),
				'copy_success'      => esc_html__( 'Shortcode copied to clipboard', 'cbcurrencyconverter' ),
				'copy_fail'         => esc_html__( 'Oops, unable to copy', 'cbcurrencyconverter' ),
				'all_currencies'    => $all_currencies,
				'teeny_setting'     => [
					'teeny'         => true,
					'media_buttons' => true,
					'editor_class'  => '',
					'textarea_rows' => 5,
					'quicktags'     => false,
					'menubar'       => false,
				],
				'pickr_i18n'        => [
					// Strings visible in the UI
					'ui:dialog'       => esc_html__( 'color picker dialog', 'cbcurrencyconverter' ),
					'btn:toggle'      => esc_html__( 'toggle color picker dialog', 'cbcurrencyconverter' ),
					'btn:swatch'      => esc_html__( 'color swatch', 'cbcurrencyconverter' ),
					'btn:last-color'  => esc_html__( 'use previous color', 'cbcurrencyconverter' ),
					'btn:save'        => esc_html__( 'Save', 'cbcurrencyconverter' ),
					'btn:cancel'      => esc_html__( 'Cancel', 'cbcurrencyconverter' ),
					'btn:clear'       => esc_html__( 'Clear', 'cbcurrencyconverter' ),

					// Strings used for aria-labels
					'aria:btn:save'   => esc_html__( 'save and close', 'cbcurrencyconverter' ),
					'aria:btn:cancel' => esc_html__( 'cancel and close', 'cbcurrencyconverter' ),
					'aria:btn:clear'  => esc_html__( 'clear and close', 'cbcurrencyconverter' ),
					'aria:input'      => esc_html__( 'color input field', 'cbcurrencyconverter' ),
					'aria:palette'    => esc_html__( 'color selection area', 'cbcurrencyconverter' ),
					'aria:hue'        => esc_html__( 'hue selection slider', 'cbcurrencyconverter' ),
					'aria:opacity'    => esc_html__( 'selection slider', 'cbcurrencyconverter' ),
				],
				'awn_options'       => [
					'tip'           => esc_html__( 'Tip', 'cbcurrencyconverter' ),
					'info'          => esc_html__( 'Info', 'cbcurrencyconverter' ),
					'success'       => esc_html__( 'Success', 'cbcurrencyconverter' ),
					'warning'       => esc_html__( 'Attention', 'cbcurrencyconverter' ),
					'alert'         => esc_html__( 'Error', 'cbcurrencyconverter' ),
					'async'         => esc_html__( 'Loading', 'cbcurrencyconverter' ),
					'confirm'       => esc_html__( 'Confirmation', 'cbcurrencyconverter' ),
					'confirmOk'     => esc_html__( 'OK', 'cbcurrencyconverter' ),
					'confirmCancel' => esc_html__( 'Cancel', 'cbcurrencyconverter' )
				],
				'lang'              => get_user_locale(),
				'search_text'       => esc_html__( 'Search', 'cbcurrencyconverter' )
			];

			wp_localize_script( 'cbcurrencyconverter-setting', 'cbcurrencyconverter_setting', apply_filters( 'cbcurrencyconverter_setting_vars', $translation_array ) );

			wp_enqueue_media();
			wp_enqueue_script( 'pickr' );
			wp_enqueue_script( 'awesome-notifications' );
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'select2' );


			wp_enqueue_script( 'cbcurrencyconverter-public' );   //for the calculator
			wp_enqueue_script( 'cbcurrencyconverter-setting' );  //for settings
		}

		if ( $hook == 'widgets.php' ) {
			wp_register_script( 'select2', $vendors_url_part . 'select2/js/select2.full.min.js', [ 'jquery' ], $version, true );

			wp_register_script( 'cbcurrencyconverter-widget', $js_url_part . 'cbcurrencyconverter-widget.js', [
				'jquery',
				'select2'
			], $version, true );
			$translation_array = [
				'please_select'  => esc_html__( 'Please Select', 'cbcurrencyconverter' ),
				'upload_btn'     => esc_html__( 'Upload', 'cbcurrencyconverter' ),
				'upload_title'   => esc_html__( 'Select Media', 'cbcurrencyconverter' ),
				'all_currencies' => $all_currencies,
			];

			wp_localize_script( 'cbcurrencyconverter-widget', 'cbcurrencyconverter_widget', $translation_array );

			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'select2' );
			wp_enqueue_script( 'cbcurrencyconverter-widget' );
		}
	}//end enqueue_scripts


	/**
	 * Add admin pages
	 */
	public function admin_menus() {
		add_submenu_page( 'options-general.php',
			esc_html__( 'Currency Converter', 'cbcurrencyconverter' ),
			esc_html__( 'Currency Converter', 'cbcurrencyconverter' ),
			'manage_options',
			'cbcurrencyconverter', [ $this, 'admin_pages' ]
		);
	}//end admin_menus


	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function admin_pages() {
		$doc = isset( $_REQUEST['doc'] ) ? absint( $_REQUEST['doc'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		if ( $doc ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo cbcurrencyconverter_get_template_html( 'admin/support.php', [
				'admin_ref' => $this,
				'settings'  => $this->settings
			] );
		} else {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo cbcurrencyconverter_get_template_html( 'admin/settings.php', [
				'admin_ref' => $this,
				'settings'  => $this->settings
			] );
		}
	}//end admin_pages


	/**
	 * Initalize Setting
	 */
	public function setting_init() {
		//set the settings
		$this->settings->set_sections( $this->get_settings_sections() );
		$this->settings->set_fields( $this->get_settings_fields() );

		//initialize settings
		$this->settings->admin_init();
	}//end setting_init

	/**
	 * Settings Sections
	 */
	public function get_settings_sections() {
		return CBCurrencyConverterHelper::settings_sections();
	}//end get_settings_sections

	/**
	 * Settings fields
	 */
	public function get_settings_fields() {
		return CBCurrencyConverterHelper::settings_fields();
	}//end get_settings_fields


	/**
	 * Add setting link in plugin listing
	 *
	 * @param $links
	 *
	 * @return array
	 */
	public function action_links( $links ) {
		$new_links['settings'] = '<a style="color: #fb4e24; font-weight: bold;" href="' . admin_url( 'options-general.php?page=cbcurrencyconverter' ) . '">' . esc_html__( 'Settings', 'cbcurrencyconverter' ) . '</a>';

		return array_merge( $new_links, $links );
	}//end action_links

	/**
	 * Filters the array of row meta for each/specific plugin in the Plugins list table.
	 * Appends additional links below each/specific plugin on the plugins page.
	 *
	 * @access  public
	 *
	 * @param  array  $links_array  An array of the plugin's metadata
	 * @param  string  $plugin_file_name  Path to the plugin file
	 * @param  array  $plugin_data  An array of plugin data
	 * @param  string  $status  Status of the plugin
	 *
	 * @return  array       $links_array
	 */
	public function plugin_row_meta( $links_array, $plugin_file_name, $plugin_data, $status ) {

		if ( strpos( $plugin_file_name, CBCURRENCYCONVERTER_BASE_NAME ) !== false ) {

			if ( ! function_exists( 'is_plugin_active' ) ) {
				include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			}


			if ( in_array( 'cbcurrencyconverteraddon/cbcurrencyconverteraddon.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || defined( 'CBCURRENCYCONVERTERADDON_NAME' ) ) {

			} else {
				$links_array['pro'] = '<a style="color: #fb4e24; font-weight: bold;" href="https://codeboxr.com/product/cbx-currency-converter-for-wordpress/" target="_blank">' . esc_html__( 'Try Pro Addon', 'cbcurrencyconverter' ) . '</a>';
			}

			$links_array['documentation'] = '<a style="color: #fb4e24; font-weight: bold;" href="https://codeboxr.com/docs/documentation-for-cbx-currency-converter-for-wordpress/" target="_blank">' . esc_html__( 'Documentation', 'cbcurrencyconverter' ) . '</a>';
		}

		return $links_array;
	}//end plugin_row_meta

	/**
	 * Plugin upgrade process on 'plugins_loaded'
	 *
	 * @return void
	 */
	public function plugins_loaded_upgrader_process() {
		$saved_version = get_option( 'cbcurrencyconverter_version' );

		if ( $saved_version === false || version_compare( $saved_version, CBCURRENCYCONVERTER_VERSION, '<' ) ) {

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

			set_transient( 'cbcurrencyconverter_upgraded_notice', 1 );

			// Update the saved version
			update_option( 'cbcurrencyconverter_version', CBCURRENCYCONVERTER_VERSION );
		}
	}//end method plugins_loaded_upgrader_process

	/**
	 * Show plugin update
	 *
	 * @param $plugin_file
	 * @param $plugin_data
	 *
	 * @return void
	 */
	public function custom_message_after_plugin_row_proaddon( $plugin_file, $plugin_data ) {
		if ( $plugin_file !== 'cbcurrencyconverteraddon/cbcurrencyconverteraddon.php' ) {
			return;
		}

		if ( defined( 'CBCURRENCYCONVERTERADDON_NAME' ) ) {
			return;
		}

		$pro_addon_version        = CBCurrencyConverterHelper::get_any_plugin_version( 'cbcurrencyconverteraddon/cbcurrencyconverteraddon.php' );
		$pro_addon_version_latest = '1.7.7';


		if ( $pro_addon_version != '' && version_compare( $pro_addon_version, '1.7.7', '<' ) ) {
			// Custom message to display

			//$plugin_setting_url = admin_url( 'admin.php?page=cbxwpbookmark_settings#cbxwpbookmark_licences' );
			$plugin_manual_update = 'https://codeboxr.com/manual-update-pro-addon/';

			/* translators:translators: %s: plugin setting url for licence */
			$custom_message = wp_kses( sprintf( __( '<strong>Note:</strong> CBX Currency Converter Pro Addon is custom plugin. This plugin can not be auto update from dashboard/plugin manager. For manual update please check <a target="_blank" href="%1$s">documentation</a>. <strong style="color: red;">It seems this plugin\'s current version is older than %2$s . To get the latest pro addon features, this plugin needs to upgrade to %2$s or later.</strong>', 'cbcurrencyconverter' ),
				esc_url( $plugin_manual_update ), $pro_addon_version_latest ), [ 'strong' => [ 'style' => [] ], 'a' => [ 'href' => [], 'target' => [] ] ] );

			// Output a row with custom content
			echo '<tr class="plugin-update-tr">
            <td colspan="3" class="plugin-update colspanchange">
                <div class="notice notice-warning inline">
                    ' . wp_kses_post( $custom_message ) . '
                </div>
            </td>
          </tr>';
		}
	}//end method custom_message_after_plugin_row_proaddon

	/**
	 * Show a notice to anyone who has just installed the plugin for the first time
	 * This notice shouldn't display to anyone who has just updated this plugin
	 */
	public function plugin_activate_upgrade_notices() {
		// Check the transient to see if cbxpollproaddon has been force deactivated
		if ( get_transient( 'cbcurrencyconverteraddon_forcedactivated_notice' ) ) {
			$notice_html = '<div style="border-left:4px solid #d63638;" class="notice notice-error is-dismissible">';
			$notice_html .= '<p>' . wp_kses(__( '<strong>CBX Currency Converter Pro Addon</strong> has been deactivated as it\'s not compatible with core plugin <strong>CBX Currency Converter</strong> current installed version. Please upgrade CBX Currency Converter Pro Addon to latest version ',
					'cbcurrencyconverter' ), ['strong' => []]) . '</p>';
			$notice_html .= '</div>';

			echo $notice_html; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

			// Delete the transient
			delete_transient( 'cbcurrencyconverteraddon_forcedactivated_notice' );
		}

		//delete transient for cache delete notice if any
		if ( get_transient( 'cbcurrencyconverter_transientreset_notice' ) ) {
			$notice_html = '<div style="border-left-color:#fb4e24;" class="notice notice-success is-dismissible">';
			$notice_html .= '<p style="color: #fb4e24;">' . esc_html__( 'Currency rate cache has been reset', 'cbcurrencyconverter' ) . '</p>';
			$notice_html .= '</div>';

			echo $notice_html; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

			//delete the transient
			delete_transient( 'cbcurrencyconverter_transientreset_notice' );
		}

		//delete transient for full reset notice if any
		if ( get_transient( 'cbcurrencyconverter_fullreset_notice' ) ) {
			$notice_html = '<div style="border-left-color:#fb4e24;" class="notice notice-success is-dismissible">';
			$notice_html .= '<p style="color: #fb4e24;">' . esc_html__( 'CBX Currency Converter plugin setting has been reset to defaults', 'cbcurrencyconverter' ) . '</p>';
			$notice_html .= '</div>';

			echo $notice_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

			//delete the transient
			delete_transient( 'cbcurrencyconverter_fullreset_notice' );
		}


		// Check the transient to see if we've just activated the plugin
		if ( get_transient( 'cbcurrencyconverter_activated_notice' ) ) {
			$notice_html = '<div style="border-left-color:#fb4e24;" class="notice notice-success is-dismissible">';
			/* translators: %s : plugin core version */
			$notice_html .= '<p>' . sprintf( wp_kses( __( 'Thanks for installing/deactivating <strong>CBX Currency Converter</strong> V%s - Codeboxr Team', 'cbcurrencyconverter' ), [ 'strong' => [] ] ), esc_attr( CBCURRENCYCONVERTER_VERSION ) ) . '</p>'; //phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage
			/* translators: 1. Plugin internal url  2. External plugin's author website link */
			$notice_html .= '<p>' . sprintf( __( 'Check <a style="color: #fb4e24; font-weight: bold;" href="%1$s">Plugin Setting</a> | <a style="color: #fb4e24; font-weight: bold;" href="%2$s" target="_blank">Documentation</a>', 'cbcurrencyconverter' ), esc_url( admin_url( 'options-general.php?page=cbcurrencyconverter' ) ), 'https://codeboxr.com/product/cbx-currency-converter-for-wordpress/' ) . '</p>';
			$notice_html .= '</div>';


			echo $notice_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

			//Delete the transient
			delete_transient( 'cbcurrencyconverter_activated_notice' );

			$this->pro_addon_compatibility_campaign();
		}

		// Check the transient to see if we've just activated the plugin
		if ( get_transient( 'cbcurrencyconverter_upgraded_notice' ) ) {
			$notice_html = '<div style="border-left-color:#fb4e24;" class="notice notice-success is-dismissible">';
			/* translators: %s: plugin core version */
			$notice_html .= '<p>' . sprintf( wp_kses( __( 'Thanks for upgrading <strong>CBX Currency Converter</strong> V%s , enjoy the new features and bug fixes - Codeboxr Team', 'cbcurrencyconverter' ), [ 'strong' => [] ] ),
					esc_attr( CBCURRENCYCONVERTER_VERSION ) ) . '</p>'; //phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage
			/* translators:1. Plugin internal url 2. Plugin author's website url for this plugin */
			$notice_html .= '<p>' . sprintf( wp_kses( __( 'Check <a style="color: #fb4e24; font-weight: bold;"  href="%1$s" >Plugin Setting</a> | <a style="color: #fb4e24; font-weight: bold;" href="%2$s" target="_blank">Documentation</a>', 'cbcurrencyconverter' ), [ 'a' => [ 'href' => [], 'style' => [], 'target' => [], 'class' => [] ] ] ), esc_url( admin_url( 'options-general.php?page=cbcurrencyconverter' ) ), 'https://codeboxr.com/product/cbx-currency-converter-for-wordpress/' ) . '</p>';
			$notice_html .= '</div>';

			echo $notice_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

			//Delete the transient
			delete_transient( 'cbcurrencyconverter_upgraded_notice' );

			$this->pro_addon_compatibility_campaign();
		}
	}//end plugin_activate_upgrade_notices

	/**
	 * Check plugin compatibility and pro addon install campaign
	 */
	public function pro_addon_compatibility_campaign() {

		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}

		//if the pro addon is active or installed
		if ( in_array( 'cbcurrencyconverteraddon/cbcurrencyconverteraddon.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || defined( 'CBCURRENCYCONVERTERADDON_NAME' ) ) {
			//plugin is activated
			$plugin_version = CBCURRENCYCONVERTERADDON_VERSION;
		} else {
			/* translators: %s: plugin pro addon external url */
			echo '<div style="border-left-color:#fb4e24;" class="notice notice-success is-dismissible"><p>' . sprintf( wp_kses( __( '<a target="_blank" href="%s">CBX Currency Converter Pro Addon</a> has extended features, settings, widgets and shortcodes. try it  - Codeboxr Team', 'cbcurrencyconverter' ), [ 'a' => [ 'href' => [], 'target' => [], 'style' => [] ] ] ), 'https://codeboxr.com/product/cbx-currency-converter-for-wordpress/' ) . '</p></div>';
		}
	}//end pro_addon_compatibility_campaign

	/**
	 * Pro Addon update message
	 */
	public function plugin_update_message_pro_addons() {
		/* translators: 1. Plugin setting url 2. Documentation link */
		echo ' ' . sprintf( wp_kses( __( 'Check how to <a style="color:#9c27b0 !important; font-weight: bold;" href="%1$s"><strong>Update manually</strong></a> , download latest version from <a style="color:#9c27b0 !important; font-weight: bold;" href="%2$s"><strong>My Account</strong></a> section of Codeboxr.com', 'cbcurrencyconverter' ), [ 'a' => [ 'href' => [], 'style' => [], 'target' => [], 'class' => [] ], 'strong' => [] ] ), 'https://codeboxr.com/manual-update-pro-addon/',
				'https://codeboxr.com/my-account/' );
	}//end plugin_update_message_pro_addons

	/**
	 * Load setting html
	 *
	 * @return void
	 * @since 3.0.9
	 *
	 */
	public function settings_reset_load() {
		//security check
		check_ajax_referer( 'settingsnonce', 'security' );

		$msg            = [];
		$msg['html']    = '';
		$msg['message'] = esc_html__( 'CBX Currency reset setting html loaded successfully', 'cbcurrencyconverter' );
		$msg['success'] = 1;

		if ( ! current_user_can( 'manage_options' ) ) {
			$msg['message'] = esc_html__( 'Sorry, you don\'t have enough permission', 'cbcurrencyconverter' );
			$msg['success'] = 0;
			wp_send_json( $msg );
		}

		$msg['html'] = CBCurrencyConverterHelper::setting_reset_html_table();

		wp_send_json( $msg );
	}//end method settings_reset_load

	/**
	 * Reset plugin data
	 *
	 * @since 3.0.9
	 */
	public function plugin_reset() {
		//security check
		check_ajax_referer( 'settingsnonce', 'security' );

		$url = admin_url( 'options-general.php?page=cbcurrencyconverter' );

		$msg            = [];
		$msg['message'] = esc_html__( 'CBX Currency plugin setting reset scheduled successfully', 'cbcurrencyconverter' );
		$msg['success'] = 1;
		$msg['url']     = $url;

		if ( ! current_user_can( 'manage_options' ) ) {
			$msg['message'] = esc_html__( 'Sorry, you don\'t have enough permission', 'cbcurrencyconverter' );
			$msg['success'] = 0;
			wp_send_json( $msg );
		}


		do_action( 'cbcurrencyconverter_plugin_reset_before' );


		$plugin_resets = $_POST;

		//delete options
		do_action( 'cbcurrencyconverter_plugin_options_deleted_before' );

		$reset_options = isset( $plugin_resets['reset_options'] ) ? $plugin_resets['reset_options'] : [];
		$option_values = ( is_array( $reset_options ) && sizeof( $reset_options ) > 0 ) ? array_values( $reset_options ) : array_values( CBCurrencyConverterHelper::getAllOptionNamesValues() );

		foreach ( $option_values as $key => $option ) {
			//delete_option( $option );
			do_action( 'cbcurrencyconverter_plugin_option_delete_before', $option );
			delete_option( $option );
			do_action( 'cbcurrencyconverter_plugin_option_delete_after', $option );
		}

		do_action( 'cbcurrencyconverter_plugin_options_deleted_after' );
		do_action( 'cbcurrencyconverter_plugin_options_deleted' );
		//end delete options

		do_action( 'cbcurrencyconverter_plugin_reset_after' );
		do_action( 'cbcurrencyconverter_plugin_reset' );

		wp_send_json( $msg );
	}//end method plugin_reset


	/**
	 * Delete transients created by this plugin and then redirect
	 */
	public function transient_reset() {
		//security check
		check_ajax_referer( 'settingsnonce', 'security' );

		$url = admin_url( 'options-general.php?page=cbcurrencyconverter' );

		$msg            = [];
		$msg['message'] = esc_html__( 'CBX Currency plugin api rate cache reset successfully', 'cbcurrencyconverter' );
		$msg['success'] = 1;
		$msg['url']     = $url;

		if ( ! current_user_can( 'manage_options' ) ) {
			$msg['message'] = esc_html__( 'Sorry, you don\'t have enough permission', 'cbcurrencyconverter' );
			$msg['success'] = 0;
			wp_send_json( $msg );
		}

		//delete plugin transient caches
		$transient_caches = CBCurrencyConverterHelper::getAllTransientCacheNames();

		do_action( 'cbcurrencyconverter_plugin_transient_caches_deleted_before' );

		foreach ( $transient_caches as $transient_cache ) {
			do_action( 'cbcurrencyconverter_plugin_transient_cache_delete_before', $transient_cache );
			delete_transient( $transient_cache );
			do_action( 'cbcurrencyconverter_plugin_transient_cache_delete_after', $transient_cache );
		}

		do_action( 'cbcurrencyconverter_plugin_transient_caches_deleted_after' );
		do_action( 'cbcurrencyconverter_plugin_transient_caches_deleted' );
		//end delete plugin transient caches

		set_transient( 'cbcurrencyconverter_transientreset_notice', 1 );

		do_action( 'cbcurrencyconverter_transient_reset' );

		wp_send_json( $msg );
	}//end transient_reset
}//end class CBCurrencyConverterAdmin