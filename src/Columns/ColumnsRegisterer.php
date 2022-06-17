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
	 * Getters & Setters
	 */
	public function get_columns() {
		return $this->columns;
	}

	public function set_columns( array $columns ) {
		return $this->columns = $columns;
	}
}