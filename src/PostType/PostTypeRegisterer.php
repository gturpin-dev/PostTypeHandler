<?php 

namespace PostTypeHandler\PostType;

use PostTypeHandler\PostType;
use PostTypeHandler\PostType\Exceptions\PostTypeNameEmptyException;
use PostTypeHandler\PostType\Exceptions\PostTypeNameLimitException;

final class PostTypeRegisterer {
	private const KEY_MAX_LENGTH = 20;

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
	 * @throws PostTypeNameLimitException
	 * @throws PostTypeNameEmptyException
	 */
	public function register_post_type() {
		if ( ! post_type_exists( $this->post_type->get_slug() ) && $this->is_valid_slug( $this->post_type->get_slug() ) ) {
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


	/**
	 * Check if the Post Type slug is valid (not empty, not exceed KEY_MAX_LENGTH characters,
	 * not conflict with existing slug).
	 *
	 * @param string $slug
	 *
	 * @return bool
	 * @throws PostTypeNameLimitException
	 * @throws PostTypeNameEmptyException
	 * @throws PostTypeSlugConflictException
	 */
	private function is_valid_slug( string $slug ): bool {
		if ( empty( $slug ) ) {
			throw new PostTypeNameEmptyException( 'The post type cannot be empty.' );
		}

		if ( strlen( $slug ) > self::KEY_MAX_LENGTH ) {
			throw new PostTypeNameLimitException( sprintf(
				'The post type name must no exceed $d characters.',
				self::KEY_MAX_LENGTH
			) );
		}

		/**
		 * Filters whether to check for slug conflicts with existing Page, Post,
		 * and Custom Post Type records. Checking requires a DB query, which
		 * may be avoided.
		 *
		 * @param boolean $check_slug_conflict Whether to check for slug conflicts.
		 */
		$check_slug_conflict = (bool) apply_filters( 'gt_post_type_' . $this->post_type->get_slug() . '_check_slug_conflict', true );
		if ( $check_slug_conflict ) {
			// Replicate slug check logic from the wp-includes/post.php wp_unique_post_slug() function.
			global $wpdb;
			$check_sql       = "SELECT post_name FROM $wpdb->posts WHERE post_name = %s LIMIT 1";
			$post_name_query = $wpdb->get_var( $wpdb->prepare( $check_sql, $this->post_type->get_slug() ) );
			if ( $post_name_query ) {
				throw new PostTypeSlugConflictException( 'Post type conflicts with existing post_name slug.' );
			}
		}

		return true;
	}
}