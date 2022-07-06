<?php 

namespace PostTypeHandler\PostType;

use PostTypeHandler\PostType;

final class PostTypeRegisterer {
	
	/**
	 * The post type object.
	 *
	 * @var \PostTypeHandler\PostType
	 */
	private PostType $post_type;

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
			$labels            = $this->post_type->make_labels();
			$options           = $this->post_type->make_options();
			$options['labels'] = $labels;

			return register_post_type( $this->post_type->get_slug(), $options );
		}
		
		return false;
	}
}