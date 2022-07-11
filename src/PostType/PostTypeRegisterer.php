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

	/**
	 * Update an existing post type with new options.
	 *
	 * @param array $args The current options.
	 * @param string $post_type_slug The current post type slug.
	 * 
	 * @see https://developer.wordpress.org/reference/hooks/register_post_type_args/
	 *
	 * @return array The updated options.
	 */
	public function update_post_type( array $args, string $post_type_slug ) {
		// Bail early if it's not the right post type.
		if ( $this->post_type->get_slug() !== $post_type_slug ) return $args;

		$labels            = $this->post_type->get_labels();
		$options           = $this->post_type->get_options();
		$options['labels'] = $labels;

		// Update the post type with the new labels and options.
		$args = array_replace_recursive( $args, $options );

		return $args;
	}
}