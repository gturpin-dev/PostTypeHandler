<?php 

namespace PostTypeHandler\Columns;

use PostTypeHandler\Columns\ColumnsRegisterer;

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
	 * An array of columns to hide.
	 *
	 * @var array
	 */
	private $columns_to_hide = [];

	/**
	 * An array of columns to populate.
	 *
	 * @var array
	 */
	private $columns_to_populate = [];

	/**
	 * An array of columns to sort.
	 *
	 * @var array
	 */
	private $columns_to_sort = [];

	/**
	 * The final set of columns.
	 *
	 * @var array
	 */
	private $columns = [];


	/**
	 * Add a new column to the post type.
	 *
	 * @param string|array $columns The column slug or an array of columns.
	 * 								The array key is the column slug and the value is the label.
	 * @param string $label The label for the column.
	 *
	 * @return Columns The current instance of the class.
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

			$column = sanitize_title( $column );

			$this->columns_to_add[ $column ] = ucfirst( $label );
		}

		return $this;
	}

	/**
	 * Hide a column from the post type.
	 *
	 * @param string|array $columns
	 *
	 * @return Columns The current instance of the class.
	 */
	public function hide( $columns ) {
		// convert to array if only one column was passed
		if ( ! is_array( $columns ) ) {
			$columns = [ $columns ];
		}

		foreach ( $columns as $column ) {
			$this->columns_to_hide[] = $column;
		}

		return $this;
	}

	/**
	 * Set all columns.
	 * Be careful with this method. It will replace all columns and there is no handle on params.
	 *
	 * @see https://developer.wordpress.org/reference/hooks/manage_post_type_posts_columns/ for built in columns.
	 * 
	 * @param array $columns 
	 *
	 * @return Columns The current instance of the class.
	 */
	public function set( $columns ) {
		$this->columns = $columns;

		return $this;
	}

	/**
	 * Store a column to populate.
	 * 
	 * @param string $column The column slug.
	 * @param callable $callback The callback to use to populate the column.
	 *                           The first param is the column value and the second is the post ID.
	 *
	 * @return Columns The current instance of the class.
	 */
	public function populate( string $column, callable $callback ) {
		$this->columns_to_populate[ $column ] = $callback;

		return $this;
	}

	/**
	 * Store the columns to sort by.
	 *
	 * @param array $columns_to_sort An array of columns to sort by.
	 *
	 * @return Columns The current instance of the class.
	 */
	public function sortable( array $columns_to_sort ) {
		foreach ( $columns_to_sort as $column => $options ) {
            $this->columns_to_sort[ $column ] = $options;
        }
		
		return $this;
	}

	/**
	 * Return the columns to sort.
	 *
	 * @param array $columns The built in columns.
	 * 
	 * @see https://developer.wordpress.org/reference/hooks/manage_post_type_posts_columns/#more-information for built in columns.
	 *
	 * @return array The sortable columns.
	 */
	public function sortable_columns( array $columns ) {
		$columns_sortable = new ColumnsSortable( $this );

		return $columns_sortable->sortable( $columns );
	}

	/**
	 * Check if an orderby param is a custom sort column.
	 *
	 * @param string $order_by The orderby value from query param.
	 *
	 * @return boolean True if the orderby param is a custom sort column.
	 */
	public function is_sortable( string $order_by ) {
		$columns_sortable = new ColumnsSortable( $this );

		return $columns_sortable->is_sortable( $order_by );
	}

	/**
	 * Retrieve the meta from the orderby value.
	 *
	 * @param string $order_by The orderby value from query param.
	 *
	 * @return array|bool The meta to sort by. False if not sortable.
	 */
	public function retrieve_sortable_meta( string $order_by ) {
		$columns_sortable = new ColumnsSortable( $this );

		return $columns_sortable->retrieve_sortable_meta( $order_by );
	}

	/**
	 * Populate a column with data.
	 *
	 * @param string $column The column slug.
	 * @param int $post_id The post ID.
	 * 
	 * @see PostTypeHandler\Columns\ColumnsPopulate::populate()
	 * @see https://developer.wordpress.org/reference/hooks/manage_post-post_type_posts_custom_column/ #parameters
	 *
	 * @return Columns The current instance of the class.
	 */
	public function populate_columns( string $column, int $post_id ) {
		$columns_populate = new ColumnsPopulate( $this );
		$columns_populate->populate( $column, $post_id );

		return $this;
	}

	/**
	 * Register the columns for the post type.
	 * 
	 * @param array $columns The built in columns.
	 * 
	 * @see PostTypeHandler\Columns\ColumnsRegisterer::register()
	 * @see https://developer.wordpress.org/reference/hooks/manage_post_type_posts_columns/ for built in columns.
	 *
	 * @return array The updated columns without built in ones.
	 */
	public function register( $columns ) {
		$columns_registerer = new ColumnsRegisterer( $this, $columns );
		
		return $columns_registerer->register();
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

	public function get_columns_to_add() {
		return $this->columns_to_add;
	}

	public function get_columns_to_hide() {
		return $this->columns_to_hide;
	}

	public function get_columns_to_populate() {
		return $this->columns_to_populate;
	}

	public function get_columns_to_sort() {
		return $this->columns_to_sort;
	}
}