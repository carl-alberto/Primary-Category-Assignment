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
	 * Generates a shortcode the custom searchbar.
	 */
	public function quick_search_mod() {
		?>
			<div class="search-form custom-search">
				<input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'primary-categ' ); ?>" value="" />
				<button type="submit" class="sc-search-submit search-submit"><?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'primary-categ' ); ?></button>
			</div>
		<?php
	}

	/**
	 * This generates the dropdown of all the designated primary categories.
	 *
	 * @param array $primary_group This is the taxonomies that will be tagged a primary group.
	 */
	public function display_primary_categories( $primary_group = array() ) {
		$args = array(
		   'public'   => true,
		);

		$output = 'object';
		$operator = 'and';
		$post_types = get_post_types( $args, $output );

		echo '<select id="alltaxonomies" class="dropdown-alltaxonomies">';
		echo sprintf( '<option value="">SELECT FILTER BELOW</option>' );
		foreach ( $post_types as $post_type ) {
			$taxonomy_names = get_object_taxonomies( $post_type->name, 'objects' );
			foreach ( $taxonomy_names as $taxonomy_name ) {
				// We need to display taxonomies tagged as primary categories.
				if ( in_array( $taxonomy_name->name, $primary_group, true ) ) {
					echo sprintf( '<option disabled value="%1s">%2s(POST TYPE)</option>', esc_html( $post_type->name ), esc_html( $post_type->label ) );
					$termitems = get_terms( array(
						'taxonomy' => $taxonomy_name->name,
						'hide_empty' => false,
					) );
					foreach ( $termitems as $term ) {
						echo sprintf( '<option value="%1s--%2s--%3s">-%4s</option>', esc_html( $post_type->name ), esc_html( $taxonomy_name->name ), esc_html( $term->slug ), esc_html( $term->name ) );
					}
				}
			}
		}
		echo '</select>';
	}

	/**
	 * This registers the first CPT to be used as a Primary Category.
	 */
	public function register_cpt_events() {
		$post_type = 'event'; // Normally lowercase with underscores.
		$plural = 'Events';
		$single = 'Event';
		$description = 'This will list all sample events';

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
			'menu_icon' => 'dashicons-welcome-view-site',
		);

		$this->register_post_type( $post_type, $plural, $single, $description, $options );
	}

	/**
	 * Primary category taxonomy to be assigned to events CPT.
	 */
	public function register_tax_events() {
		$taxonomy = 'primary-category';
		$plural = 'Primary Categories';
		$single = 'Primary Category';
		$post_types = array( 'event' );

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
