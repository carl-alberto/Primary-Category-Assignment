<?php
/**
 * Contains the mian functionalities, customization will mainly happen here.
 *
 * @package Primary Categ \ Main Functionalities
 * @author Carl Alberto
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class used for the main plugin functions.
 */
class Primary_Categ_Main {
	/**
	 * TODO: chnage function name and fill in blanks.
	 * For Dashicons, see https://developer.wordpress.org/resource/dashicons
	 */
	public function register_cpt1() {
		$post_type = ''; // Normally lowercase with underscores.
		$plural = '';
		$single = '';
		$description = '';

		$options = array(
			'public' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'show_ui' => true,
			'show_in_menu' => true,
			'show_in_nav_menus' => true,
			'show_in_admin_bar' => true,
			'query_var' => true,
			'can_export' => true,
			'rewrite' => true,
			'capability_type' => 'post',
			'has_archive' => true,
			'hierarchical' => true,
			'show_in_rest'       	=> true,
	  		'rest_base'          	=> $post_type,
	  		'rest_controller_class' => 'WP_REST_Posts_Controller',
			'supports' => array( 'title', 'editor', 'excerpt', 'comments', 'thumbnail' ),
			'menu_position' => 5,
			'menu_icon' => 'dashicons-admin-post',
		);

		$this->register_post_type( $post_type, $plural, $single, $description, $options );
	}

	/**
	 * TODO: change function name and fill in blanks.
	 */
	public function register_taxonomy1() {
		$taxonomy = '';
		$plural = '';
		$single = '';
		$post_types = array( '' );

		$taxonomy_args = array(
			'hierarchical' => true,
			'public' => true,
			'show_ui' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud' => true,
			'meta_box_cb' => null,
			'show_admin_column' => true,
			'show_in_quick_edit' => true,
			'update_count_callback' => '',
			'show_in_rest'          => true,
			'rest_base'             => $taxonomy,
			'rest_controller_class' => 'WP_REST_Terms_Controller',
			'query_var' => $taxonomy,
			'rewrite' => true,
			'sort' => '',
		);

		$this->register_taxonomy( $taxonomy, $plural, $single, $post_types, $taxonomy_args );
	}

	/**
	 * Wrapper function to register a new post type.
	 *
	 * @param  string $post_type	Post type name.
	 * @param  string $plural		Post type item plural name.
	 * @param  string $single		Post type item single name.
	 * @param  string $description	Description of post type.
	 * @param  string $options		Options when registering a post type.
	 * @return object              Post type class object.
	 */
	public function register_post_type( $post_type = '', $plural = '', $single = '', $description = '', $options = array() ) {

		if ( ! $post_type || ! $plural || ! $single ) {
			return;
		}
		$post_type = new Primary_Categ_Post_Type( $post_type, $plural, $single, $description, $options );

		return $post_type;
	}

	/**
	 * Wrapper function to register a new taxonomy.
	 *
	 * @param  string $taxonomy   Taxonomy name.
	 * @param  string $plural     Taxonomy single name.
	 * @param  string $single     Taxonomy plural name.
	 * @param  array  $post_types Post types to which this taxonomy applies.
	 * @param  array  $taxonomy_args Args when cerating a taxonomy.
	 * @return object             Taxonomy class object.
	 */
	public function register_taxonomy( $taxonomy = '', $plural = '', $single = '', $post_types = array(), $taxonomy_args = array() ) {

		if ( ! $taxonomy || ! $plural || ! $single ) {
			return;
		}
		$taxonomy = new Primary_Categ_Taxonomy( $taxonomy, $plural, $single, $post_types, $taxonomy_args );

		return $taxonomy;
	}

}
