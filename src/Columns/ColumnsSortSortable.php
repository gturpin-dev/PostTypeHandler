<?php 

namespace PostTypeHandler\Columns;

use PostTypeHandler\PostType;

class ColumnsSortSortable {

	/**
	 * The columns object.
	 *
	 * @var PostType
	 */
	private PostType $post_type_handler;
	
	public function __construct( PostType $post_type_handler ) {
		$this->post_type_handler = $post_type_handler;
	}

	/**
	 * Update the query to sort by the custom column.
	 *
	 * @param \WP_Query $query The query to update.
	 *
	 * @return void
	 */
	public function sort_column( \WP_Query $query ) {

		// bail early if we weren't in the post type admin
		if ( ! is_admin() ) return;
		if ( $query->get( 'post_type' ) !== $this->post_type_handler->get_slug() ) return;
		
		// Bail if we are in the customizer
		global $current_screen;
		if ( isset( $current_screen->base ) && $current_screen->base === 'customize' ) return;

		$order_by       = $query->get( 'orderby' );
		$columns_object = $this->post_type_handler->columns();
		
		// bail early if the column isn't sortable
		if ( ! $columns_object->is_sortable( $order_by ) ) return;

		// Get the custom column options
		$meta = $columns_object->retrieve_sortable_meta( $order_by );

		// Determine the type of ordering to use
		if ( is_string( $meta ) || ! isset( $meta[1] ) ) {
			$meta_key   = $meta;
			$meta_value = 'meta_value';
		} else {
			$meta_key   = $meta[0];
			$meta_value = 'meta_value_num';
		}
		
		// Set the custom order
		$query->set( 'meta_key', $meta_key );
		$query->set( 'orderby', $meta_value );
	}
}