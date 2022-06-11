<?php 

namespace PostTypeHandler\Helpers;

/**
 * Class to format arrays of taxonomies
 */
class TaxonomyArrayFormatter {


	/**
	 * Format an array of taxonomies or single one to an array of taxonomy slugs
	 *
	 * @param array|string|Taxonomy $taxonomies Taxonomies to format.
	 *
	 * @return array Formatted taxonomies.
	 */
	public function format( $taxonomies ) {
		// bail early if not an array or string or Taxonomy object
		if ( ! is_array( $taxonomies ) && ! is_string( $taxonomies ) && ! is_a( $taxonomies, 'PostTypeHandler\Taxonomy' ) ) return $taxonomies;

		// convert to array
		if ( ! is_array( $taxonomies ) ) {
			$taxonomies = [ $taxonomies ];
		}

		// convert Taxonomy objects to slug
		$taxonomies = array_map( function( $taxonomy ) {
			if ( is_a( $taxonomy, 'PostTypeHandler\Taxonomy' ) ) {
				return $taxonomy->get_slug();
			}
			return $taxonomy;
		}, $taxonomies );

		return array_unique( $taxonomies );
	}
}