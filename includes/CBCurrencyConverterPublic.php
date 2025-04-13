<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://codeboxr.com
 * @since      1.0.0
 *
 * @package    CBCurrencyConverter
 * @subpackage CBCurrencyConverter/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    CBCurrencyConverter
 * @subpackage CBCurrencyConverter/public
 * @author     codeboxr <info@codeboxr.com>
 */
class CBCurrencyConverterPublic {

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
	 * @param  string  $plugin_name  The name of the plugin.
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
	}//end of contructor

	public function cbcurrencyconverter_init() {
		do_action( 'cbcurrencyconverter_public', $this );
	}//end cbcurrencyconverter_init

	public function init_shortcodes() {
		add_shortcode( 'cbcurrencyconverter', [ $this, 'cbcurrencyconverter_shortcode' ] );
		add_shortcode( 'cbcurrencyconverter_rate', [ $this, 'cbcurrencyconverter_rate_shortcode' ] );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		$css_url_part     = CBCURRENCYCONVERTER_ROOT_URL . 'assets/css/';
		$js_url_part      = CBCURRENCYCONVERTER_ROOT_URL . 'assets/js/';
		$vendors_url_part = CBCURRENCYCONVERTER_ROOT_URL . 'assets/vendors/';

		$css_path_part     = CBCURRENCYCONVERTER_ROOT_PATH . 'assets/css/';
		$js_path_part      = CBCURRENCYCONVERTER_ROOT_PATH . 'assets/js/';
		$vendors_path_part = CBCURRENCYCONVERTER_ROOT_PATH . 'assets/vendors/';

		$ver = $this->version;

		wp_register_style( 'select2', $vendors_url_part . 'select2/css/select2.min.css', [], $ver );
		wp_register_style( 'cbcurrencyconverter-public', $css_url_part . 'cbcurrencyconverter-public.css', [ 'select2' ], $ver, 'all' );

		wp_enqueue_style( 'select2' );
		wp_enqueue_style( 'cbcurrencyconverter-public' );
	}//end method enqueue_styles

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		$css_url_part     = CBCURRENCYCONVERTER_ROOT_URL . 'assets/css/';
		$js_url_part      = CBCURRENCYCONVERTER_ROOT_URL . 'assets/js/';
		$vendors_url_part = CBCURRENCYCONVERTER_ROOT_URL . 'assets/vendors/';

		$css_path_part     = CBCURRENCYCONVERTER_ROOT_PATH . 'assets/css/';
		$js_path_part      = CBCURRENCYCONVERTER_ROOT_PATH . 'assets/js/';
		$vendors_path_part = CBCURRENCYCONVERTER_ROOT_PATH . 'assets/vendors/';

		$ver    = $this->version;
		$ajax_nonce = wp_create_nonce( "cbcurrencyconverter_nonce" );

		wp_register_script( 'select2', $vendors_url_part . 'select2/js/select2.full.min.js', [ 'jquery' ], $ver, true );
		wp_register_script( 'cbcurrencyconverter-public', $js_url_part . 'cbcurrencyconverter-public.js', [ 'jquery', 'select2' ], $ver, true );

		wp_localize_script( 'cbcurrencyconverter-public',
			'cbcurrencyconverter_public',
			[
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

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'select2' );


		wp_enqueue_script( 'cbcurrencyconverter-public' );
	}//end method enqueue_scripts

	/**
	 * Direct currency rate shortcode
	 *
	 * @param $atts
	 *
	 * @return string
	 * @since v3.1.0
	 *
	 */
	public function cbcurrencyconverter_rate_shortcode( $atts ) {
		$atts = array_change_key_case( (array) $atts, CASE_LOWER );

		$default_values = [
			'from'          => '',
			'to'            => '',
			'amount'        => 1,
			'decimal_point' => 2,
		];

		$instance = shortcode_atts( $default_values, $atts, 'cbcurrencyconverter_rate' );


		$instance['from']          = esc_attr( $instance['from'] );
		$instance['to']            = esc_attr( $instance['to'] );
		$instance['amount']        = floatval( $instance['amount'] );
		$instance['decimal_point'] = absint( $instance['decimal_point'] );


		return CBCurrencyConverterHelper::cbcurrencyconverter_rate( $instance['from'], $instance['to'], $instance['amount'], $instance['decimal_point'] );
	}//end method cbcurrencyconverter_rate_shortcode

	/**
	 * Shortcode handler
	 *
	 * @param $atts
	 *
	 * @return string|void
	 */
	public function cbcurrencyconverter_shortcode( $atts ) {
		$atts = array_change_key_case( (array) $atts, CASE_LOWER );

		$default_values = CBCurrencyConverterHelper::global_default_values();


		$instance = shortcode_atts( $default_values, $atts, 'cbcurrencyconverter' );

		//remove white spaces
		$instance['calc_from_currencies'] = explode( ',', str_replace( ' ', '', esc_attr( $instance['calc_from_currencies'] ) ) );
		$instance['calc_to_currencies']   = explode( ',', str_replace( ' ', '', esc_attr( $instance['calc_to_currencies'] ) ) );
		$instance['list_to_currencies']   = explode( ',', str_replace( ' ', '', esc_attr( $instance['list_to_currencies'] ) ) );


		$instance['layout']        = esc_attr( $instance['layout'] );
		$instance['decimal_point'] = absint( $instance['decimal_point'] );

		$instance['calc_title'] = esc_attr( $instance['calc_title'] );
		$instance['list_title'] = esc_attr( $instance['list_title'] );

		$instance['calc_default_amount'] = floatval( $instance['calc_default_amount'] );
		$instance['list_default_amount'] = floatval( $instance['list_default_amount'] );

		$instance['calc_from_currency'] = esc_attr( $instance['calc_from_currency'] );
		$instance['calc_to_currency']   = esc_attr( $instance['calc_to_currency'] );


		extract( $instance );

		if ( sizeof( $calc_from_currencies ) == 0 ) {
			$calc_from_currencies = $instance['calc_from_currencies'] = $default_values['calc_from_currencies'];
		}
		if ( sizeof( $calc_to_currencies ) == 0 ) {
			$calc_to_currencies = $instance['calc_to_currencies'] = $default_values['calc_to_currencies'];
		}
		if ( sizeof( $list_to_currencies ) == 0 ) {
			$list_to_currencies = $instance['list_to_currencies'] = $default_values['list_to_currencies'];
		}

		if ( ! in_array( $calc_from_currency, $calc_from_currencies ) || $calc_from_currency == '' ) {
			$calc_from_currency = $instance['calc_from_currency'] = esc_attr( cbcurrencyconverter_first_value( $calc_from_currencies ) );
		}

		if ( ! in_array( $calc_to_currency, $calc_to_currencies ) || $calc_to_currency == '' ) {
			$calc_to_currency = $instance['calc_to_currency'] = esc_attr( cbcurrencyconverter_first_value( $calc_to_currencies ) );
		}

		if ( $list_from_currency == '' ) {
			$list_from_currency = $instance['list_from_currency'] = esc_attr( $default_values['list_from_currency'] );
		}

		if ( $layout == 'list' ) {
			return CBCurrencyConverterHelper::cbxcclistview( 'shortcode', $instance );
		} elseif ( $layout == 'cal' ) {
			return CBCurrencyConverterHelper::cbxcccalcview( 'shortcode', $instance );
		} elseif ( $layout == 'calwithlistbottom' ) {
			return CBCurrencyConverterHelper::cbxcccalcview( 'shortcode', $instance ) . CBCurrencyConverterHelper::cbxcclistview( 'shortcode', $instance );
		} elseif ( $layout == 'calwithlisttop' ) {
			return CBCurrencyConverterHelper::cbxcclistview( 'shortcode', $instance ) . CBCurrencyConverterHelper::cbxcccalcview( 'shortcode', $instance );
		}
	}//end codeboxrcurrencyconverter_shortcode


	/**
	 * Currency Rate api method switch based on setting
	 *
	 * @param     $conversion_value
	 * @param     $price
	 * @param     $convertfrom
	 * @param     $convertto
	 * @param  int  $decimal_point
	 *
	 * @return  rating value
	 */
	public function cbxconvertcurrency_method_switcher( $conversion_value = 0, $price = 0, $convertfrom = 'USD', $convertto = 'CAD', $decimal_point = 2 ) {
		$setting    = $this->settings;
		$api_source = $setting->get_option( 'api_source', 'cbcurrencyconverter_global', 'alphavantage' );


		$rates_api = CBCurrencyConverterHelper::currency_rates_api();

		if ( isset( $rates_api[ $api_source ] ) ) {
			$rate_api = $rates_api[ $api_source ];
			if ( is_callable( [ $rate_api['class'], $rate_api['method'] ] ) ) {
				return call_user_func_array( [ $rate_api['class'], $rate_api['method'] ], [ $conversion_value, $price, $convertfrom, $convertto, $decimal_point ] );
			} else {
				return $conversion_value;
			}
		}

		return $conversion_value;
	}//end cbxconvertcurrency_method_switcher

	/**
	 * Ajax request for current rate conversion
	 */
	public function cbcurrencyconverter_ajax_cur_convert() {
		//security check
		if ( isset( $_POST['cbcurrencyconverter_data']['nonce'] ) && ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cbcurrencyconverter_data']['nonce'] ) ), 'cbcurrencyconverter_nonce' ) ) {
			die( 'Security check' );
		}

		$setting              = $this->settings;
		$decimal_point_global = $setting->get_option( 'decimal_point', 'cbcurrencyconverter_global', 2 );


		$data          = isset( $_POST['cbcurrencyconverter_data'] ) ? wp_unslash( $_POST['cbcurrencyconverter_data'] ) : [];//phpcs:ignore  WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$decimal_point = isset( $data['decimal'] ) ? absint( $data['decimal'] ) : absint( $decimal_point_global );


		$convert_from  = sanitize_text_field( $data['from'] );
		$convert_to    = sanitize_text_field( $data['to'] );
		$convert_price = floatval( $data['amount'] );

		$response = '';

		if ( $data['error'] == '' ) {
			$conversion_value           = CBCurrencyConverterHelper::getCurrencyRate( $convert_price, $convert_from, $convert_to, $decimal_point );
			$conversion_value           = str_replace( ',', '', $conversion_value );
			$conversion_value_formatted = number_format_i18n( $conversion_value, $decimal_point );

			$response = apply_filters( 'cbxconvertcurrency_conversion_value_formatted', $conversion_value_formatted, $conversion_value, $convert_price, $convert_from, $convert_to, $decimal_point, 'api' );

		} else {
			$response = $data['error'];
		}

		echo( wp_json_encode( $response ) );
		die();
	}//end cbcurrencyconverter_ajax_cur_convert

	/**
	 * Registering Widgets
	 */
	public function register_widgets() {
		register_widget( 'CBCurrencyConverterWidget' );
	}//end register_widgets

	/**
	 * Load extra css before elementor style are loaded
	 *
	 * Load Elementor Custom Icon
	 */
	function elementor_icon_loader() {
		$ver = $this->version;

		$css_url_part     = CBCURRENCYCONVERTER_ROOT_URL . 'assets/css/';
		$js_url_part      = CBCURRENCYCONVERTER_ROOT_URL . 'assets/js/';
		$vendors_url_part = CBCURRENCYCONVERTER_ROOT_URL . 'assets/vendors/';

		$css_path_part     = CBCURRENCYCONVERTER_ROOT_PATH . 'assets/css/';
		$js_path_part      = CBCURRENCYCONVERTER_ROOT_PATH . 'assets/js/';
		$vendors_path_part = CBCURRENCYCONVERTER_ROOT_PATH . 'assets/vendors/';

		wp_register_style( 'cbcurrencyconverter-elementor', $css_url_part . 'cbcurrencyconverter-elementor.css', false, $ver );
		wp_enqueue_style( 'cbcurrencyconverter-elementor' );
	}//end elementor_icon_loader

	/**
	 * Load extra js after elementor scripts are loaded
	 */
	public function elementor_script_loader() {
		/*$all_currencies = CBCurrencyConverterHelper::getCurrencyList();

		wp_register_script( 'cbcurrencyconverter-elementor-admin', CBCURRENCYCONVERTER_ROOT_URL . 'assets/js/cbcurrencyconverter-elementor-admin.js', ['jquery'], CBCURRENCYCONVERTER_VERSION, true );
		// Localize the script with new data
		$translation_array = array(
			'please_select'   => esc_html__( 'Please Select', 'cbcurrencyconverter' ),
			'upload_btn'      => esc_html__( 'Upload', 'cbcurrencyconverter' ),
			'upload_title'    => esc_html__( 'Select Media', 'cbcurrencyconverter' ),
			'all_currencies'  => $all_currencies,
		);

		wp_localize_script( 'cbcurrencyconverter-elementor-admin', 'cbcurrencyconverter_elementor_admin', $translation_array );

		wp_enqueue_script( 'cbcurrencyconverter-elementor-admin' );*/

	}//end method elementor_script_loader


	/**
	 * Init elementor widget
	 *
	 * @throws Exception
	 */
	public function init_elementor_widgets() {
		//include file
		require_once CBCURRENCYCONVERTER_ROOT_PATH . 'includes/Widgets/Elementor/CBCurrencyConverterElemWidget.php';

		//register elementor widget
		\Elementor\Plugin::instance()->widgets_manager->register( new CBCurrencyConverterElemWidget() );
	}//end widgets_registered

	/**
	 * Add new category to elementor
	 *
	 * @param $elements_manager
	 */
	public function add_elementor_widget_categories( $elements_manager ) {
		$elements_manager->add_category(
			'codeboxr',
			[
				'title' => esc_html__( 'Codeboxr Widgets', 'cbcurrencyconverter' ),
				'icon'  => 'fa fa-plug',
			]
		);
	}//end add_elementor_widget_categories

	/**
	 * // Before VC Init
	 */
	public function vc_before_init_actions() {

		if ( ! class_exists( 'CBX_VCParam_DropDownMulti' ) ) {
			require_once CBCURRENCYCONVERTER_ROOT_PATH . 'includes/Widgets/VisualComposer/Params/CBCCVCParamDropDownMulti.php';
		}

		if ( ! class_exists( 'CBCurrencyConverter_WPBWidget' ) ) {
			require_once CBCURRENCYCONVERTER_ROOT_PATH . 'includes/Widgets/VisualComposer/CBCurrencyConverterWPBWidget.php';
		}

		new CBCurrencyConverterWPBWidget();
	}//end method vc_before_init_actions
}//end class CBCurrencyConverterPublic