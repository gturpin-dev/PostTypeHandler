<?php 

namespace PostTypeHandler\PostType;

use PostTypeHandler\PostType;

final class PostTypeLabelsManager {

	/**
	 * Making slug based on the name
	 * 
	 * @param string $name the name to slugify
	 *
	 * @return string Slug
	 */
	public function make_slug( string $name ) {
		return sanitize_key( $name );
	}

	/**
	 * Create plural name based on the name
	 * added 's' to the end of the name if it's not already there
	 * if the name ends with 'y', 'ies' is added to the end of the name
	 *
	 * @param string $name
	 *
	 * @return string Plural name
	 */
	public function make_plural_name( string $name ) {
		$last_letter = substr( $name, -1 );

		if ( 'y' === $last_letter ) {
			$name = substr( $name, 0, strlen( $name ) - 1 ) . 'ies';
		} elseif ( 's' !== $last_letter ) {
			$name .= 's';
		}

		return $name;
	}

	/**
	 * Making labels for the post type with default values
	 *
	 * @param \PostTypeHandler\PostType $post_type
	 *
	 * @return array Labels
	 */
	public function make_labels( PostType $post_type ) {

		// default labels
		$labels = [
			'name'               => __( $post_type->get_name(), '' ),
			'singular_name'      => __( $post_type->get_name(), '' ),
			'menu_name'          => __( $post_type->get_plural_name(), '' ),
			'all_items'          => __( $post_type->get_plural_name(), '' ),
			'name_admin_bar'     => __( $post_type->get_name(), '' ),
			'add_new'            => __( 'Add New', '' ),
			'add_new_item'       => __( 'Add New ' . $post_type->get_name(), '' ),
			'edit_item'          => __( 'Edit ' . $post_type->get_name(), '' ),
			'new_item'           => __( 'New ' . $post_type->get_name(), '' ),
			'view_item'          => __( 'View ' . $post_type->get_name(), '' ),
			'search_items'       => __( 'Search ' . $post_type->get_plural_name(), '' ),
			'not_found'          => __( 'No ' . $post_type->get_plural_name() . ' found.', '' ),
			'not_found_in_trash' => __( 'No ' . $post_type->get_plural_name() . ' found in Trash.', '' ),
			'parent_item_colon'  => __( 'Parent ' . $post_type->get_name() . ':', '' ),
		];

		// replace defaults with the options passed
		$labels = array_replace_recursive( $labels, $post_type->get_labels() );

		return $labels;
	}
}