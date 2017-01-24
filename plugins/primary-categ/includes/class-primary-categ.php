<?php
/**
 * Contains class for the plugin.
 *
 * @package Primary Categ \ Main
 * @author Carl Alberto
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class used for the main plugin functions.
 */
class Primary_Categ {

	/**
	 * Constructor function.
	 *
	 * @access  public
	 * @since   1.0.0
	 * @param string $file Name of this file.
	 * @param string $version Version of this plugin.
	 * @param array  $pluginoptions Contains various options for the plugin.
	 * @return  void
	 */
	public function __construct( $file = '', $version = '1.0.0', $pluginoptions = array() ) {
		$this->_version = $version;
		$this->_token = 'primary_categ';
		$this->base = $pluginoptions['settings_prefix'];

		// Load plugin environment variables.
		$this->file = $file;
		$this->dir = dirname( $this->file );
		$this->assets_dir = trailingslashit( $this->dir ) . 'assets';
		$this->assets_url = esc_url( trailingslashit( plugins_url( '/assets/', $this->file ) ) );

		$this->script_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		register_deactivation_hook( $this->file, array( $this, 'plugin_deactivated' ) );

		// Load frontend JS & CSS.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ), 10 );

		// Adding a primamry Category for a sample post type called 'event'.
		$custom = new Primary_Categ_Main;
		$custom->register_cpt_events();
		$custom->register_tax_events();

		// Adds the SC from tha main file, to be used as a custom search.
		add_shortcode( 'sc_quick_search_mod', array( $custom, 'quick_search_mod' ), 0 );

		// Ajax request start.
		add_action( 'wp_ajax_pc_ajax_request', array( $this, 'pc_ajax_request' ), 0 );
		add_action( 'wp_ajax_nopriv_pc_ajax_request', array( $this, 'pc_ajax_request' ), 0 );

		add_action( 'wp_enqueue_scripts', array( $this, 'load_ajax_js' ), 0 );

		// Handle localisation.
		$this->load_plugin_textdomain();
		add_action( 'init', array( $this, 'load_localisation' ), 0 );
	}

	/**
	 * This loads the ajax action request.
	 *
	 * @access  public
	 */
	public function pc_ajax_request() {
		// Define your primary categories here.
		$primary_group = array(
			'primary-category',
		);
		$custom = new Primary_Categ_Main;
		$custom->display_primary_categories( $primary_group );
		die();
	}

	/**
	 * This prepares the ajax entrypoint.
	 *
	 * @access  public
	 */
	public function load_ajax_js() {
		wp_enqueue_script( 'ajax-script', plugin_dir_url( __FILE__ ) . '../assets/js/frontend.js', array( 'jquery' ) );
		wp_localize_script( 'ajax-script', 'pcAjaxObject',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'site_search_url' => site_url(),
			)
		);
	}

	/**
	 * The single instance of Primary_Categ.
	 *
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * Settings class object
	 *
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $settings = null;

	/**
	 * The version number.
	 *
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $_version;

	/**
	 * The token.
	 *
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $_token;

	/**
	 * The main plugin file.
	 *
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $file;

	/**
	 * The main plugin directory.
	 *
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $dir;

	/**
	 * The plugin assets directory.
	 *
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $assets_dir;

	/**
	 * The plugin assets URL.
	 *
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $assets_url;

	/**
	 * Suffix for Javascripts.
	 *
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $script_suffix;

	/**
	 * Array for plugin settings.
	 *
	 * @var     array
	 * @access  public
	 * @since   1.0.0
	 */
	public $pluginoptions;

	/**
	 * Load plugin localisation.
	 *
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function load_localisation() {
		load_plugin_textdomain( 'primary-categ', false, dirname( plugin_basename( $this->file ) ) . '/languages/' );
	} // End load_localisation ()

	/**
	 * Load plugin textdomain.
	 *
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function load_plugin_textdomain() {
	    $domain = 'primary-categ';

	    $locale = apply_filters( 'plugin_locale', get_locale(), $domain );

	    load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
	    load_plugin_textdomain( $domain, false, dirname( plugin_basename( $this->file ) ) . '/languages/' );
	} // End load_plugin_textdomain ()

	/**
	 * Main Primary_Categ Instance.
	 *
	 * Ensures only one instance of Primary_Categ is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see Primary_Categ()
	 * @param string $file Name of this file.
	 * @param string $version Version of this plugin.
	 * @param array  $pluginoptions Contains various options for the plugin.
	 * @return Main Primary_Categ instance
	 */
	public static function instance( $file = '', $version = '1.0.0', $pluginoptions = array() ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $file, $version, $pluginoptions );
		}
		return self::$_instance;
	} // End instance ()

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, esc_html_e( 'Cheatin&#8217; huh?', 'primary-categ' ), esc_html( $this->parent->_version ) );
	} // End __clone ()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, esc_html_e( 'Cheatin&#8217; huh?', 'primary-categ' ), esc_html( $this->parent->_version ) );
	} // End __wakeup ()

	/**
	 *  Runs when plugin is deactivated.
	 *
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function plugin_deactivated() {
	}

	/**
	 * Log the plugin version number.
	 *
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	private function _log_version_number() {
		update_option( $this->_token . '_version', $this->_version );
	} // End _log_version_number ()

}
