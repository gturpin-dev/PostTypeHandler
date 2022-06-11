<?php 

namespace PostTypeHandler\Taxonomy;

use PostTypeHandler\Taxonomy;

class PostTypeRegisterer {

	private Taxonomy $taxonomy;

	public function __construct( Taxonomy $taxonomy ) {
		$this->taxonomy = $taxonomy;
	}
	
	/**
	 * Register the taxonomies for the post type.
	 *
	 * @see https://developer.wordpress.org/reference/functions/register_taxonomy_for_object_type/
	 * 
	 * @return void
	 */
	public function register_post_types() {
		$post_types = $this->taxonomy->get_post_types();

		if ( ! empty( $post_types ) ) {
			foreach ( $post_types as $post_type ) {
				register_taxonomy_for_object_type( $this->taxonomy->get_slug(), $post_type );
			}
		}
	}
}