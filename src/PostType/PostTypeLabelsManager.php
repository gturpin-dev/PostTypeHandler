<?php 

namespace PostTypeHandler\PostType;

use PostTypeHandler\PostType;

final class PostTypeLabelsManager {

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
			'view_items'         => __( 'View ' . $post_type->get_plural_name(), '' ),
			'search_items'       => __( 'Search ' . $post_type->get_plural_name(), '' ),
			'not_found'          => __( 'No ' . $post_type->get_plural_name() . ' found.', '' ),
			'not_found_in_trash' => __( 'No ' . $post_type->get_plural_name() . ' found in Trash.', '' ),
			'parent_item_colon'  => __( 'Parent ' . $post_type->get_name() . ':', '' ),
		];

		// replace defaults with the options passed
		$labels = array_replace_recursive( $labels, $post_type->get_labels() );

		return apply_filters( 'gt_post_type_' . $post_type->get_slug() . '_labels', $labels );
	}
}