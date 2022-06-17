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
		if ( $query->get( 'post_type' !== $this->post_type_handler->get_slug() ) ) return;

		$order_by = $query->get( 'orderby' );

		// bail early if the column isn't sortable
		if ( ! $this->post_type_handler->columns()->is_sortable( $order_by ) ) return;

		// Determine the type of ordering to apply.
		// Set the custom order
	}
}