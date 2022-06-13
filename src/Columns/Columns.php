<?php 

namespace PostTypeHandler\Columns;

/**
 * Class to handle admin columns for the post type.
 */
class Columns {

	/**
	 * An array of columns to add.
	 * 
	 * @var array
	 */
	private $columns_to_add = [];

	/**
	 * Add a new column to the post type.
	 *
	 * @param string|array $columns The column slug or an array of columns.
	 * @param string $label The label for the column.
	 *
	 * @return void
	 */
	public function add( $columns, $label = null ) {
		// convert to array if only one column was passed
		if ( ! is_array( $columns ) ) {
			$columns = [ $columns => $label ];
		}

		// Create Label from slug if no label was passed
		foreach ( $columns as $column => $label ) {
			if ( is_null( $label ) ) {
				$label = str_replace( [ '_', '-' ], ' ', ucfirst( $column ) );
			}

			$column = sanitize_key( $column );

			$this->columns_to_add[ $column ] = $label;
		}

		return $this;
	}
}