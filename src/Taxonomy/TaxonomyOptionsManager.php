<?php 

namespace PostTypeHandler\Taxonomy;

use PostTypeHandler\Taxonomy;

final class TaxonomyOptionsManager {

	/**
	 * Create options for the taxonomy with default values
	 * 
	 * @param Taxonomy $taxonomy The taxonomy object.
	 * 
	 * @return array Options
	 */
	public function make_options( Taxonomy $taxonomy ) {

		// default options
		$options = [
			'hierarchical'      => true,
			'show_admin_column' => true,
			'rewrite'           => [
				'slug' => $taxonomy->get_slug(),
			],
		];

		// replace defaults with the options passed
		$options = array_replace_recursive( $options, $taxonomy->get_options() );

		return apply_filters( 'gt_taxonomy_' . $taxonomy->get_slug() . '_options', $options );
	}
}