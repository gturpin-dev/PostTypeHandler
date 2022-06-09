<?php 

namespace PostTypeHandler\Taxonomy;

use PostTypeHandler\Taxonomy;

final class TaxonomyLabelsManager {

	/**
	 * Making labels for the post type with default values
	 *
	 * @param \PostTypeHandler\Taxonomy $taxonomy
	 *
	 * @return array Labels
	 */
	public function make_labels( Taxonomy $taxonomy ) {

		// default labels
		$labels = [
			'name'                       => __( $taxonomy->get_plural_name(), '' ),
			'singular_name'              => __( $taxonomy->get_name(), '' ),
			'menu_name'                  => __( $taxonomy->get_plural_name(), '' ),
			'all_items'                  => __( 'All ' . $taxonomy->get_plural_name(), '' ),
			'edit_item'                  => __( 'Edit ' . $taxonomy->get_name(), '' ),
			'view_item'                  => __( 'View ' . $taxonomy->get_name(), '' ),
			'update_item'                => __( 'Update ' . $taxonomy->get_name(), '' ),
			'add_new_item'               => __( 'Add New ' . $taxonomy->get_name(), '' ),
			'new_item_name'              => __( 'New ' . $taxonomy->get_name() . 'Name', '' ),
			'parent_item'                => __( 'Parent ' . $taxonomy->get_plural_name(), '' ),
			'parent_item_colon'          => __( 'Parent ' . $taxonomy->get_plural_name(), '' ),
			'search_items'               => __( 'Search ' . $taxonomy->get_plural_name(), '' ),
			'popular_items'              => __( 'Popular ' . $taxonomy->get_plural_name(), '' ),
			'separate_items_with_commas' => __( 'Seperate ' . $taxonomy->get_plural_name() . 'with commas', '' ),
			'add_or_remove_items'        => __( 'Add or remove ' . $taxonomy->get_plural_name(), '' ),
			'choose_from_most_used'      => __( 'Choose from most used ' . $taxonomy->get_plural_name(), '' ),
			'not_found'                  => __( 'No ' . $taxonomy->get_plural_name() . 'found', '' ),
		];

		// replace defaults with the options passed
		$labels = array_replace_recursive( $labels, $taxonomy->get_labels() );

		return apply_filters( 'gt_taxonomy_' . $taxonomy->get_slug() . '_labels', $labels );
	}
}