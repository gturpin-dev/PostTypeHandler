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
			'capability_type' => 'post',
			'has_archive'     => true,
			'hierarchical'    => false,
			'menu_icon'       => 'dashicons-admin-generic'
		];

		// replace defaults with the options passed
		$options = array_replace_recursive( $options, $post_type->get_options() );

		return $options;
	}
}