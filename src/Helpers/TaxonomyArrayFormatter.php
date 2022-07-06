<?php 

namespace PostTypeHandler\Helpers;

use PostTypeHandler\Taxonomy;

/**
 * Class to format arrays of taxonomies
 */
final class TaxonomyArrayFormatter {


	/**
	 * Format an array of taxonomies or single one to an array of taxonomy slugs
	 *
	 * @param array|string|Taxonomy $taxonomies Taxonomies to format.
	 *
	 * @return array Formatted taxonomies.
	 */
	public function format( $taxonomies ) {
		// convert to array
		if ( ! is_array( $taxonomies ) ) {
			$taxonomies = [ $taxonomies ];
		}

		// delete entry that are not string or Taxonomy
		$taxonomies = array_filter( $taxonomies, function( $taxonomy ) {
			return is_string( $taxonomy ) || $taxonomy instanceof Taxonomy;
		} );

		// convert Taxonomy objects to slug
		$taxonomies = array_map( function( $taxonomy ) {
			if ( $taxonomy instanceof Taxonomy ) {
				return $taxonomy->get_slug();
			}
			return $taxonomy;
		}, $taxonomies );

		return array_unique( $taxonomies );
	}
}