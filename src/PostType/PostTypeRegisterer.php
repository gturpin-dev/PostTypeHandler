<?php 

namespace PostTypeHandler\PostType;

use PostTypeHandler\PostType;
use PostTypeHandler\PostType\Exceptions\PostTypeNameEmptyException;
use PostTypeHandler\PostType\Exceptions\PostTypeNameLimitException;
use PostTypeHandler\PostType\Exceptions\PostTypeSlugConflictException;

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
	 * Check if the Post Type slug is valid e.g. 
	 * Not empty.
	 * Not exceed KEY_MAX_LENGTH characters.
	 * Not conflict with existing slug.
	 *
	 * @param string $slug the post type slug.
	 *
	 * @return bool True if the slug is valid, false otherwise.
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
				'The post type name must no exceed %d characters.',
				self::KEY_MAX_LENGTH
			) );
		}
		
		$wp_debug            = defined( 'WP_DEBUG' ) ? WP_DEBUG : false;
		$check_slug_conflict = (bool) apply_filters( 'gt_post_type_' . $this->post_type->get_slug() . '_check_slug_conflict', $wp_debug );

		if ( $check_slug_conflict ) {
			$this->check_slug_conflict();
		}

		return true;
	}

	/**
	 * Check for slug conflicts with posts slugs
	 *
	 * @return bool True if the slug is valid, false otherwise.
	 * @throws PostTypeSlugConflictException
	 */
	private function check_slug_conflict(): bool {
		$existing_posts = get_posts( [
			'post_type'      => 'any',
			'post_status'    => 'any',
			'posts_per_page' => 1,
			'name'           => $this->post_type->get_slug(),
		] );

		if ( ! empty( $existing_posts ) ) {
			throw new PostTypeSlugConflictException( 'Post type "' . $this->post_type->get_slug() . '" slug conflicts with existing post slug.' );
		}

		return true;
	}
}