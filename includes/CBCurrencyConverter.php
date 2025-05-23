<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://codeboxr.com
 * @since      1.0.0
 *
 * @package    CBCurrencyConverter
 * @subpackage CBCurrencyConverter/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    CBCurrencyConverter
 * @subpackage CBCurrencyConverter/includes
 * @author     codeboxr <info@codeboxr.com>
 */
final class CBCurrencyConverter {
	/**
	 * The single instance of the class.
	 *
	 * @var self
	 * @since  3.0.7
	 */
	private static $instance = null;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	public $plugin_basename;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->plugin_name = CBCURRENCYCONVERTER_NAME;
		$this->version     = CBCURRENCYCONVERTER_VERSION;

		$this->plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' );

		$this->load_dependencies();

		$this->define_common_hooks();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Singleton Instance.
	 *
	 * Ensures only one instance of cbxcurrencyconverted is loaded or can be loaded.
	 *
	 * @return self Main instance.
	 * @see run_cbxjob()
	 * @since  1.0.0
	 * @static
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}//end method instance

	/**
	 * Cloning is forbidden.
	 *
	 * @since 3.1.7
	 */
	public function __clone() {
		cbcurrencyconverter_doing_it_wrong( __FUNCTION__, esc_html__( 'Cloning is forbidden.', 'cbcurrencyconverter' ), '3.1.7' );
	}//end method clone

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 3.1.7
	 */
	public function __wakeup() {
		cbcurrencyconverter_doing_it_wrong( __FUNCTION__, esc_html__( 'Unserializing instances of this class is forbidden.', 'cbcurrencyconverter' ), '3.1.7' );
	}//end method wakeup

	/**
	 * Get currency list
	 *
	 * @return mixed|void
	 */
	public static function getCurrencyList() {
		cbcurrencyconverter_deprecated_function( 'getCurrencyList function', '2.2', 'CBCurrencyConverterHelper::getCurrencyList' );

		return CBCurrencyConverterHelper::getCurrencyList();
	}//end method getCurrencyList

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - CBCurrencyConverter_Loader. Orchestrates the hooks of the plugin.
	 * - CBCurrencyConverter_i18n. Defines internationalization functionality.
	 * - CBCurrencyConverterAdmin. Defines all hooks for the admin area.
	 * - CBCurrencyConverterPublic. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/Functions/cbcurrencyconverter-tpl-loader.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/CBCurrencyconverterSetting.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/Helpers/CBCurrencyConverterHelper.php';


		//exchange api class
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/rates_api/class-cbcurrencyconverter-exchangeratehost.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/rates_api/class-cbcurrencyconverter-alphavantage.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/rates_api/class-cbcurrencyconverter-openexchangeratesfree.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/rates_api/class-cbcurrencyconverter-currencylayerfree.php';


		//public and admin classes
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/CBCurrencyConverterAdmin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/CBCurrencyConverterPublic.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/Functions/cbcurrencyconverter-functions.php';

		//widget classes
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/Widgets/Blocks/CBCurrencyConverterGutenbergWidget.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/Widgets/Classic/CBCurrencyConverterWidget.php';
	}//end method load_dependencies


	/**
	 * Load plugin language file
	 *
	 * @return void
	 */
	private function define_common_hooks() {
		add_action( 'init', [ $this, 'load_plugin_textdomain' ] );
	}//end method define_common_hooks

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		global $wp_version;

		$plugin_admin     = new CBCurrencyConverterAdmin( $this->get_plugin_name(), $this->get_version() );
		$gutenberg_widget = new CBCurrencyConverterGutenbergWidget( $this->get_plugin_name(), $this->get_version() );



		add_filter( 'plugin_action_links_' . CBCURRENCYCONVERTER_BASE_NAME, [ $plugin_admin, 'action_links' ], 10, 4 );
		add_filter( 'plugin_row_meta', [ $plugin_admin, 'plugin_row_meta' ], 10, 4 );
		add_action( 'plugins_loaded', [ $plugin_admin, 'plugins_loaded_upgrader_process' ] );
		add_action( 'admin_notices', [ $plugin_admin, 'plugin_activate_upgrade_notices' ] );
		add_action( 'after_plugin_row_cbcurrencyconverteraddon/cbcurrencyconverteraddon.php', [ $plugin_admin, 'custom_message_after_plugin_row_proaddon' ], 10, 2 );
		add_action( 'admin_enqueue_scripts', [ $plugin_admin, 'enqueue_styles' ] );
		add_action( 'admin_enqueue_scripts', [ $plugin_admin, 'enqueue_scripts' ] );

		add_action( 'admin_menu', [ $plugin_admin, 'admin_menus' ] );
		add_action( 'admin_init', [ $plugin_admin, 'setting_init' ] );

		//gutenberg
		add_action( 'init', [ $gutenberg_widget, 'gutenberg_blocks' ] );

		if ( version_compare( $wp_version, '5.8' ) >= 0 ) {
			add_filter( 'block_categories_all', [ $gutenberg_widget, 'gutenberg_block_categories' ], 10, 2 );
		} else {
			add_filter( 'block_categories', [ $gutenberg_widget, 'gutenberg_block_categories' ], 10, 2 );
		}

		add_action( 'enqueue_block_editor_assets', [ $gutenberg_widget, 'enqueue_block_editor_assets' ] );
		//end gutenberg

		//ajax plugin reset
		add_action( 'wp_ajax_cbcurrencyconverter_settings_reset_load', [ $plugin_admin, 'settings_reset_load' ] );
		add_action( 'wp_ajax_cbcurrencyconverter_settings_reset', [ $plugin_admin, 'plugin_reset' ] );
		add_action( 'wp_ajax_cbcurrencyconverter_transient_reset', [ $plugin_admin, 'transient_reset' ] ); //will be done using ajax
	}//end define_admin_hooks

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		global $plugin_public;

		$plugin_public = new CBCurrencyConverterPublic( $this->get_plugin_name(), $this->get_version() );

		add_action( 'wp_enqueue_scripts', [ $plugin_public, 'enqueue_styles' ] );
		add_action( 'wp_enqueue_scripts', [ $plugin_public, 'enqueue_scripts' ] );

		//init widget
		add_action( 'widgets_init', [ $plugin_public, 'register_widgets' ] );

		//shortcode
		add_action( 'init', [ $plugin_public, 'init_shortcodes' ] );

		//add_filter( 'cbxcc_convertion_method', $plugin_public, 'cbxconvertcurrency_method_alphavantage', 10, 5 );
		add_filter( 'cbxcc_convertion_method', [ $plugin_public, 'cbxconvertcurrency_method_switcher' ], 10, 5 );

		add_action( 'wp_ajax_currrency_convert', [ $plugin_public, 'cbcurrencyconverter_ajax_cur_convert' ] );
		add_action( 'wp_ajax_nopriv_currrency_convert', [ $plugin_public, 'cbcurrencyconverter_ajax_cur_convert' ] );

		add_action( 'init', [ $plugin_public, 'cbcurrencyconverter_init' ] );

		//elementor
		//add_action( 'elementor/init', $plugin_public, 'init_elementor_widgets' );
		add_action( 'elementor/widgets/widgets_registered', [ $plugin_public, 'init_elementor_widgets' ] );
		add_action( 'elementor/elements/categories_registered', [ $plugin_public, 'add_elementor_widget_categories' ] );

		add_action( 'elementor/editor/before_enqueue_styles', [ $plugin_public, 'elementor_icon_loader' ], 99999 );
		//add_action( 'elementor/editor/after_enqueue_scripts', [ $plugin_public, 'elementor_script_loader' ], 99999 );

		//vc editor
		add_action( 'vc_before_init', [ $plugin_public, 'vc_before_init_actions' ], 12 );//priority changed from default(10) to 12
	}//end define_public_hooks

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'cbcurrencyconverter', false, CBCURRENCYCONVERTER_ROOT_PATH . 'languages/' );
	}//end method load_plugin_textdomain

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return    string    The name of the plugin.
	 * @since     1.0.0
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}//end method get_plugin_name

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 * @since     1.0.0
	 */
	public function get_version() {
		return $this->version;
	}//end method get_version
}//end class CBCurrencyConverter