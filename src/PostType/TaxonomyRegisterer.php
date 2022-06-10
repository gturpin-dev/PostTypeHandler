<?php 

namespace PostTypeHandler\PostType;

use PostTypeHandler\PostType;

final class TaxonomyRegisterer {
	
	private $post_type;

	public function __construct( PostType $post_type ) {
		$this->post_type = $post_type;
	}

	/**
	 * Register the taxonomies for the post type.
	 *
	 * @see https://developer.wordpress.org/reference/functions/register_taxonomy_for_object_type/
	 * 
	 * @return void
	 */
	public function register_taxonomies() {
		$taxonomies = $this->post_type->get_taxonomies();
		
		if ( ! empty( $taxonomies ) ) {
			foreach ( $taxonomies as $taxonomy ) {
				register_taxonomy_for_object_type( $taxonomy, $this->post_type->get_slug() );
			}
		}
	}
}