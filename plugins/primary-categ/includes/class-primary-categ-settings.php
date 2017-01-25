<?php
/**
 * Contains class for the settings.
 *
 * @package Primary Categ \ Settings
 * @author Carl Alberto
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class used for the plugin setting.
 */
class Primary_Categ_Settings {

	/**
	 * The single instance of Primary_Categ_Settings.
	 *
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * The main plugin object.
	 *
	 * @var 	object
	 * @access  public
	 * @since 	1.0.0
	 */
	public $parent = null;

	/**
	 * Prefix for plugin settings.
	 *
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $base = '';

	/**
	 * Available settings for plugin.
	 *
	 * @var     array
	 * @access  public
	 * @since   1.0.0
	 */
	public $settings = array();

	/**
	 * Saved Primary Categories.
	 *
	 * @var     array
	 * @access  public
	 * @since   1.0.0
	 */
	public $primary_categories_array;

	/**
	 * Available settings for plugin.
	 *
	 * @param object $parent contains the parent object.
	 */
	public function __construct( $parent ) {
		$this->parent = $parent;

		// Change this to make your plugin settings unique.
		$this->base = $parent->base;

		// Initialise settings.
		add_action( 'init', array( $this, 'init_settings' ), 11 );

		// Register plugin settings.
		add_action( 'admin_init' , array( $this, 'register_settings' ) );

		// Add settings page to menu.
		add_action( 'admin_menu' , array( $this, 'add_menu_item' ) );

		// Add settings link to plugins page.
		add_filter( 'plugin_action_links_' . plugin_basename( $this->parent->file ) , array( $this, 'add_settings_link' ) );

	}

	/**
	 * Initialise settings.
	 *
	 * @return void
	 */
	public function init_settings() {
		$this->settings = $this->settings_fields();
	}

	/**
	 * Add settings page to admin menu.
	 *
	 * @return void
	 */
	public function add_menu_item() {
		$page = add_options_page(
			__( 'Primary Category Settings', 'primary-categ' ),
			__( 'Primary Category Settings', 'primary-categ' ),
			'manage_options',
			$this->parent->_token . '_settings',
			array( $this, 'settings_page' )
		);
		add_action( 'admin_print_styles-' . $page, array( $this, 'settings_assets' ) );
	}

	/**
	 * Load settings JS & CSS.
	 *
	 * @return void
	 */
	public function settings_assets() {

		// We're including the farbtastic script & styles here because they're needed for the colour picker
		// If you're not including a colour picker field then you can leave these calls out as well as the farbtastic dependency for the wpt-admin-js script below.

	}

	/**
	 * Add settings link to plugin list table.
	 *
	 * @param  array $links Existing links.
	 * @return array 		Modified links
	 */
	public function add_settings_link( $links ) {
		$settings_link = '<a href="options-general.php?page=' . $this->parent->_token . '_settings">' . __( 'Settings', 'primary-categ' ) . '</a>';
		array_push( $links, $settings_link );
		return $links;
	}

	/**
	 * Add settings link to plugin list table.
	 *
	 * @return array $taxo_array Modified links.
	 */
	public function get_all_categories() {
		$args = array(
			'public'   => true,
			'_builtin' => false,
		);
		$output = 'objects';
		$operator = 'and';
		$taxonomies = get_taxonomies( $args, $output, $operator );
		$taxo_array = array();
		$taxo_array[''] = 'No Primary Category Assigned yet';

		if ( $taxonomies ) {
			foreach ( $taxonomies  as $taxonomy ) {
				$taxo_array[ $taxonomy->name ] = $taxonomy->label ;
			}
		}
		return $taxo_array;

	}

	/**
	 * This will load the primary category if there is currently saved.
	 *
	 * @param string $option_name Name of option.
	 * @return string Returns the selected value in the option if there is. Default to null if not found.
	 */
	public function cpt_has_primary_assigned( $option_name = '' ) {
		if ( get_option( $option_name ) ) {
			$option_isset = get_option( $option_name );

			$this->assign_primary_categories( 'asdf' );

			return $option_isset;
		}
		return null;
	}

	/**
	 * This will load the primary category if there is currently saved.
	 *
	 * @param string $taxonomy_name Name of option.
	 */
	public function assign_primary_categories( $taxonomy_name ) {
		$this->primary_categories_array = $taxonomy_name;
	}

	/**
	 * This will load the primary category if there is currently saved.
	 *
	 * @return array $fields_all Returns settings all field.
	 */
	public function get_all_cpt() {
		$args = array(
		   'public'   => true,
		   '_builtin' => false,
		);
		$output = 'object';
		$operator = 'and';
		$post_types = get_post_types( $args, $output );

		// Load the default post type primary category.
		$defaultpost = 'post';
		$optionidname = 'sb_' . $defaultpost;
		$fields_all[] = array(
			'id' 			=> $optionidname,
			'label'			=> $defaultpost . ' CPT',
			'description'	=> 'Select a Primary Category to be assigned to your ' . $defaultpost,
			'type'			=> 'select',
			'options'		=> $this->get_all_categories(),
			'default'		=> $this->cpt_has_primary_assigned( $optionidname ),
		);

		// Load the default Custom post type primary categories.
		foreach ( $post_types as $post_type ) {
			$optionidname = 'sb_' . $post_type->name;
			$fields_all[] = array(
				'id' 			=> $optionidname,
				'label'			=> $post_type->label . ' CPT',
				'description'	=> 'Select a Primary Category to be assigned to your ' . $post_type->label,
				'type'			=> 'select',
				'options'		=> $this->get_all_categories(),
				'default'		=> $this->cpt_has_primary_assigned( $optionidname ),
			);
		}
		return $fields_all;
	}

	/**
	 * Build settings fields.
	 *
	 * @return array $settings Fields to be displayed on settings page.
	 */
	private function settings_fields() {

		$this->get_all_categories();

		$settings['standard'] = array(
			'title'					=> __( 'Posts & Custom Post Types List', 'primary-categ' ),
			'description'			=> __( 'This will dynamically display your Post and Custom Post Types and its assigned Primary Category. Only one Primary Category can be assigned for each post type.', 'primary-categ' ),
			'fields'				=> $this->get_all_cpt(),
		);

		$settings = apply_filters( $this->parent->_token . '_settings_fields', $settings );

		return $settings;
	}

	/**
	 * Register plugin settings.
	 *
	 * @return void
	 */
	public function register_settings() {
		if ( is_array( $this->settings ) ) {

			// Check posted/selected tab.
			// TODO: Do a more proper input validation.
			$current_section = '';
			if ( isset( $_POST['tab'] ) && $_POST['tab'] ) { // @codingStandardsIgnoreLine
				$current_section = $_POST['tab'];
			} else {
				if ( isset( $_GET['tab'] ) && $_GET['tab'] ) { // @codingStandardsIgnoreLine
					$current_section = $_GET['tab'];
				}
			}

			foreach ( $this->settings as $section => $data ) {

				if ( $current_section && $current_section !== $section ) {
					continue;
				}
				// Add section to page.
				add_settings_section( $section, $data['title'], array( $this, 'settings_section' ), $this->parent->_token . '_settings' );

				foreach ( $data['fields'] as $field ) {

					// Validation callback for field.
					$validation = '';
					if ( isset( $field['callback'] ) ) {
						$validation = $field['callback'];
					}

					// Register field.
					$option_name = $this->base . $field['id'];
					register_setting( $this->parent->_token . '_settings', $option_name, $validation );

					// Add field to page.
					add_settings_field( $field['id'], $field['label'], array( $this->parent->admin, 'display_field' ), $this->parent->_token . '_settings', $section, array( 'field' => $field, 'prefix' => $this->base ) );
				}

				if ( ! $current_section ) {
					break;
				}
			}
		}
	}

	/**
	 * Displays the description in the plugins  settings.
	 *
	 * @access public
	 * @param array $section Contains the ID and the description.
	 */
	public function settings_section( $section ) {
		$html = '<p> ' . $this->settings[ $section['id'] ]['description'] . '</p>' . "\n";
		echo $html; // @codingStandardsIgnoreLine
	}

	/**
	 * Load settings page content.
	 *
	 * @return void
	 */
	public function settings_page() {

		// Build page HTML.
		$html = '<div class="wrap" id="' . $this->parent->_token . '_settings">' . "\n";
		$html .= '<h2>' . __( 'Primary Category Settings' , 'primary-categ' ) . '</h2>' . "\n";

		$tab = '';
		if ( isset( $_GET['tab'] ) && $_GET['tab'] ) { // @codingStandardsIgnoreLine
			$tab .= $_GET['tab'];
		}

		// Show page tabs.
		if ( is_array( $this->settings ) && 1 < count( $this->settings ) ) {
			$html .= '<h2 class="nav-tab-wrapper">' . "\n";
			$c = 0;
			foreach ( $this->settings as $section => $data ) {
				// Set tab class.
				$class = 'nav-tab';
				// @codingStandardsIgnoreLine
				if ( ! isset( $_GET['tab'] ) ) {
					if ( 0 === $c ) {
						$class .= ' nav-tab-active';
					}
				} else {
					// @codingStandardsIgnoreLine
					if ( isset( $_GET['tab'] ) && $section == $_GET['tab'] ) {
						$class .= ' nav-tab-active';
					}
				}

				// Set tab link.
				$tab_link = add_query_arg( array( 'tab' => $section ) );
				// @codingStandardsIgnoreLine
				if ( isset( $_GET['settings-updated'] ) ) {
					$tab_link = remove_query_arg( 'settings-updated', $tab_link );
				}

				// Output tab.
				$html .= '<a href="' . $tab_link . '" class="' . esc_attr( $class ) . '">' . esc_html( $data['title'] ) . '</a>' . "\n";

				++$c;
			}

			$html .= '</h2>' . "\n";
		}

		$html .= '<form method="post" action="options.php" enctype="multipart/form-data">' . "\n";
				// Get settings fields.
				ob_start();
				settings_fields( $this->parent->_token . '_settings' );
				do_settings_sections( $this->parent->_token . '_settings' );
				$html .= ob_get_clean();
				$html .= '<p class="submit">' . "\n";
					$html .= '<input type="hidden" name="tab" value="' . esc_attr( $tab ) . '" />' . "\n";
					$html .= '<input name="Submit" type="submit" class="button-primary" value="' . esc_attr( __( 'Save Settings' , 'primary-categ' ) ) . '" />' . "\n";
				$html .= '</p>' . "\n";
			$html .= '</form>' . "\n";
		$html .= '</div>' . "\n";
		// @codingStandardsIgnoreLine
		echo $html;
	}

	/**
	 * Main Primary_Categ_Settings Instance.
	 *
	 * Ensures only one instance of Primary_Categ_Settings is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see Primary_Categ()
	 * @param object $parent Contains the parent instance.
	 * @return Main Primary_Categ_Settings instance
	 */
	public static function instance( $parent ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $parent );
		}
		return self::$_instance;
	} // End instance()

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, esc_html_e( 'Cheatin&#8217; huh?', 'primary-categ' ), esc_html( $this->parent->_version ) );
	} // End __clone()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, esc_html_e( 'Cheatin&#8217; huh?', 'primary-categ' ), esc_html( $this->parent->_version ) );
	} // End __wakeup()

}
