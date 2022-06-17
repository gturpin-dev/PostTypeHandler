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
}