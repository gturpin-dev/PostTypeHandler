<?php 

namespace PostTypeHandler\PostType;

use PostTypeHandler\PostType;

final class PostTypeRegisterer {
	
	private $post_type;

	public function __construct( PostType $post_type ) {
		$this->post_type = $post_type;
	}

	/**
	 * Register the post type only if it doesn't already exist.
	 * 
	 * @see https://developer.wordpress.org/reference/functions/register_post_type/
	 *
	 * @return WP_Post_Type|WP_Error|bool The registered post type object on success, WP_Error object on failure or False if the post type already exists.
	 */
	public function register_post_type() {
		if ( ! post_type_exists( $this->post_type->get_slug() ) ) {
			$labels            = $this->post_type->get_labels();
			$options           = $this->post_type->get_options();
			$options['labels'] = $labels;
			
			return register_post_type( $this->post_type->get_slug(), $options );
		}
		
		return false;
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