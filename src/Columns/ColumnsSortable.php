<?php 

namespace PostTypeHandler\Columns;

class ColumnsSortable {

	/**
	 * The columns object.
	 * 
	 * @var Columns 
	 */
	private $columns_handler;
	
	public function __construct( Columns $columns_handler ) {
		$this->columns_handler = $columns_handler;
	}

	/**
	 * Adding the sortable columns to the built in ones.
	 *
	 * @param array $columns The built in columns.
	 * 
	 * @see https://developer.wordpress.org/reference/hooks/manage_post_type_posts_columns/#more-information for built in columns.
	 *
	 * @return array The sortable columns.
	 */
	public function sortable( array $columns ) {
		$columns_to_sort = $this->columns_handler->get_columns_to_sort();

		if ( ! empty( $columns_to_sort ) ) {
			return array_merge( $columns, $columns_to_sort );
		}

		return $columns;
	}

	/**
	 * Check if an orderby param is a custom sort column.
	 *
	 * @param string $order_by The orderby value from query param.
	 *
	 * @return boolean True if the orderby param is a custom sort column.
	 */
	public function is_sortable( string $order_by ) {
		$columns_to_sort = $this->columns_handler->get_columns_to_sort();

		if ( array_key_exists( $order_by, $columns_to_sort ) ) {
			return true;
		}

		foreach ( $columns_to_sort as $column => $column_options ) {
			// If the order_by param is a custom sort column, return true.
			if ( is_string( $column_options ) && $column_options === $order_by ) {
				return true;
			}

			// If the order_by param is a the first param of options array, return true.
			if ( is_array( $column_options ) && isset( $options[0] ) && $options[0] === $order_by ) {
				return true;
			}
		}

		return false;
	}
}