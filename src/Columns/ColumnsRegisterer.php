<?php 

namespace PostTypeHandler\Columns;

use PostTypeHandler\Columns\Columns;

final class ColumnsRegisterer {

	/**
	 * @var array An array of columns.
	 */
	private $columns = [];

	/**
	 * @var Columns The columns object.
	 */
	private Columns $columns_handler;

	public function __construct( Columns $columns_handler, array $built_in_columns ) {
		$this->columns_handler = $columns_handler;
		$this->columns		   = $built_in_columns;
	}

	/**
	 * Register the columns with the post type.
	 * 
	 * @see https://developer.wordpress.org/reference/hooks/manage_post_type_posts_columns/#more-information for built in columns.
	 *
	 * @return array The updated columns without built in ones.
	 */
	public function register() {
		// If the user set the columns, we just return them.
		if ( ! empty( $this->columns_handler->get_columns() ) ) {
			return $this->columns_handler->get_columns();
		}

		$this->add_columns();
		$this->hide_columns();
		$this->order_columns();

		return $this->columns;
	}

	/**
	 * Add the columns from the columns object.
	 *
	 * @return void
	 */
	private function add_columns() {
		$columns_to_add = $this->columns_handler->get_columns_to_add();
		
		if ( ! empty( $columns_to_add ) ) {
			$this->columns = array_merge( $this->columns, $columns_to_add );
		}
	}

	/**
	 * Hide the columns from the columns object.
	 *
	 * @return void
	 */
	private function hide_columns() {
		$columns_to_hide = $this->columns_handler->get_columns_to_hide();
		
		if ( ! empty( $columns_to_hide ) ) {
			foreach ( $columns_to_hide as $column ) {
				unset( $this->columns[ $column ] );
			}
		}
	}

	/**
	 * Order the columns from the columns object.
	 *
	 * TODO: Manage the same order number
	 * TODO: Manage the negative values ?
	 * 
	 * @return void
	 */
	private function order_columns() {
		$positions = $this->columns_handler->get_positions();
		$columns   = $this->get_columns();

		// bail early if there is nothing to order
		if ( empty( $positions ) ) return;

		// make a copy to work with
		$copy_columns = $columns;

		// remove the doublets from the positions array TODO: check if we can replace that with array_diff
		foreach ( $positions as $column => $position ) {
			unset( $copy_columns[ $column ] );
		}
		// Reindex properly
		$copy_columns = array_flip( $copy_columns );
		$copy_columns = array_values( $copy_columns );

		// match positions format to columns array
		$positions = array_flip( $positions );

		// merge the two arrays with the right positions
		foreach ( $copy_columns as $key => $value ) {
			// check for the first available key in $positions
			$available_index = 0;
			while( isset( $positions[ $available_index ] ) ) $available_index++;
			
			// set the value to the available key
			$positions[ $available_index ] = $value;
		}

		// reorder the columns array based on the position
		$positions = array_flip( $positions );
		asort( $positions );

		// remake the array content
		$new_columns = array_merge( $positions, $columns );
		
		// update the columns array
		$this->set_columns( $new_columns );
	}

	/**
	 * Getters & Setters
	 */
	public function get_columns() {
		return $this->columns;
	}

	public function set_columns( array $columns ) {
		return $this->columns = $columns;
	}
}