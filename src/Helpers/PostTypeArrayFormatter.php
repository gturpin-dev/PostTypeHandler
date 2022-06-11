<?php 

namespace PostTypeHandler\Helpers;

use PostTypeHandler\PostType;

/**
 * Class to format arrays of post types
 */
class PostTypeArrayFormatter {


	/**
	 * Format an array of post types or single one to an array of post types slugs
	 *
	 * @param array|string|PostType $post_types post types to format.
	 *
	 * @return array Formatted post_types.
	 */
	public function format( $post_types ) {
		// convert to array
		if ( ! is_array( $post_types ) ) {
			$post_types = [ $post_types ];
		}

		// delete entry that are not string or Post Type
		$post_types = array_filter( $post_types, function( $post_type ) {
			return is_string( $post_type ) || $post_type instanceof PostType;
		} );

		// convert Post Type objects to slug
		$post_types = array_map( function( $post_type ) {
			if ( $post_type instanceof PostType ) {
				return $post_type->get_slug();
			}
			return $post_type;
		}, $post_types );

		return array_unique( $post_types );
	}
}