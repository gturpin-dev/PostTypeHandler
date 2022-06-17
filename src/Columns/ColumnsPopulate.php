<?php 

namespace PostTypeHandler\Columns;

class ColumnsPopulate {

	/**
	 * @var Columns The columns object.
	 */
	private Columns $columns_handler;

	public function __construct( Columns $columns_handler ) {
		$this->columns_handler = $columns_handler;
	}

	/**
	 * Populate a column with data.
	 *
	 * @param string $column The column slug.
	 * @param int $post_id The post ID.
	 *
	 * @return void
	 */
	public function populate( string $column, int $post_id ) {
		$columns_to_populate = $this->columns_handler->get_columns_to_populate();
		
		if ( isset( $columns_to_populate[ $column ] ) ) {
			call_user_func( $columns_to_populate[ $column ], $column, $post_id );
		}
	}
}