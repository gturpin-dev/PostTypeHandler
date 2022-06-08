<?php 

namespace PostTypeHandler\PostType;

use PostTypeHandler\PostType;

final class PostTypeOptionsManager {

	/**
	 * Create options for the post type with default values
	 * 
	 * @param PostType $post_type The post type object.
	 * 
	 * @return array Options
	 */
	public function make_options( PostType $post_type ) {

		// default options
		$options = [
			'public'  => true,
			'rewrite' => [
				'slug' => $post_type->get_slug(),
			],
			'menu_position'   => 6,
			'capability_type' => 'page',
			'hierarchical'    => true,
			'has_archive'     => true,
			'hierarchical'    => false,
			'menu_icon'       => 'dashicons-admin-generic',
			'supports'        => [
				'title',
				'editor',
				'thumbnail',
				'excerpt',
			],
		];

		// replace defaults with the options passed
		$options = array_replace_recursive( $options, $post_type->get_options() );

		return apply_filters( 'gt_post_type_' . $post_type->get_slug() . '_options', $options );
	}
}