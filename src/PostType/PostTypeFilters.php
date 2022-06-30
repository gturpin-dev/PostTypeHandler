<?php 

namespace PostTypeHandler\PostType;

use PostTypeHandler\PostType;

class PostTypeFilters {

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
	 * Update the admin filters for the post type
	 *
	 * @param string $post_type the post type slug
	 * @param string $which the location of the filters (top, bottom)
	 *
	 * @return void
	 */
	public function update_admin_filters( string $post_type, string $which ) {
		// bail early if not the current post type
		if ( $post_type !== $this->post_type->get_slug() ) return;

		// getting filters
		$filters = $this->post_type->get_taxonomy_filters();

		foreach ( $filters as $taxonomy_slug ) {
			
			// continue if the taxonomy is not registered
			if ( ! taxonomy_exists( $taxonomy_slug ) ) continue;
			// continue if the taxonomy is not registered to this post type
			if ( ! is_object_in_taxonomy( $this->post_type->get_slug(), $taxonomy_slug ) ) continue;

			$taxonomy = get_taxonomy( $taxonomy_slug );

			// Build the dropdown
			$selected = null;

			if ( isset( $_GET[ $taxonomy_slug ] ) ) {
				$selected = $_GET[ $taxonomy_slug ];
			}

			$dropdown_args = [
				'name'            => $taxonomy_slug,
				'value_field'     => 'slug',
				'taxonomy'        => $taxonomy->name,
				'show_option_all' => $taxonomy->labels->all_items,
				'hierarchical'    => $taxonomy->hierarchical,
				'selected'        => $selected,
				'orderby'         => 'name',
				'hide_if_empty'   => true,
				'show_count'      => true,
			];

			// Output screen reader label.
			echo '<label class="screen-reader-text" for="cat">' . $taxonomy->labels->filter_by_item . '</label>';

			// Output dropdown for taxonomy.
			wp_dropdown_categories( $dropdown_args );
		}
	}
}