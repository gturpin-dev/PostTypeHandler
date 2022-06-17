<?php 

namespace PostTypeHandler;

use PostTypeHandler\Columns\Columns;
use PostTypeHandler\Helpers\LabelsHandler;
use PostTypeHandler\Columns\ColumnsSortSortable;
use PostTypeHandler\PostType\PostTypeRegisterer;
use PostTypeHandler\PostType\TaxonomyRegisterer;
use PostTypeHandler\Helpers\TaxonomyArrayFormatter;
use PostTypeHandler\PostType\PostTypeLabelsManager;
use PostTypeHandler\PostType\PostTypeOptionsManager;

/**
 * Class to handle the registration of post types
 */
class PostType {

	/**
	 * @var string Name of the post type.
	 */
	private $name;

	/**
	 * @var string Plural name of the post type.
	 */
	private $plural_name;

	/**
	 * @var string Slug of the post type. Generated from the name if not set.
	 */
	private $slug;

	/**
	 * @var array Options for the post type.
	 */
	private $options;

	/**
	 * @var array Labels for the post type.
	 */
	private $labels;

	/**
	 * @var array Taxonomies for the post type.
	 */
	private $taxonomies = [];

	/**
	 * @var Columns Columns for the post type.
	 */
	private Columns $columns;

	/**
	 * @param string $name Name of the post type.
	 * @param array $options Options for the post type.
	 * @param array $labels Labels for the post type.
	 * 
	 * @return void
	 */
	public function __construct( string $name, array $options = [], array $labels = [] ) {
		$this->name    = $name;
		$this->options = $options;
		$this->labels  = $labels;

		$this->load();
	}

	/**
	 * Starting stuff for the class
	 *
	 * @return void
	 */
	public function load(): void {
		$this->make_slug();
		$this->make_plural_name();
		$this->make_options();
		$this->make_labels();
	}

	/**
	 * Making slug based on the name
	 *
	 * @return string Slug
	 */
	public function make_slug( string $name = null ): string {
		if ( ! $name ) {
			$name = $this->name;
		}

		$labels_handler = new LabelsHandler();
		$slug           = $labels_handler->make_slug( $name );

		return $this->set_slug( $slug );
	}

	/**
	 * Making plural name based on the name
	 * 
	 * @return string Plural name
	 */
	public function make_plural_name(): string {
		$labels_handler = new LabelsHandler();
		$plural_name    = $labels_handler->make_plural_name( $this->name );

		return $this->set_plural_name( $plural_name );
	}

	/**
	 * Making options for the post type with default values
	 * 
	 * @return array Options
	 */
	public function make_options() {
		$post_type_options_manager = new PostTypeOptionsManager();
		
		$options = $post_type_options_manager->make_options( $this );
		return $this->set_options( $options );
	}

	/**
	 * Making labels for the post type with default values
	 *
	 * @return array Labels
	 */
	public function make_labels() {
		$post_type_labels_manager = new PostTypeLabelsManager();

		$labels = $post_type_labels_manager->make_labels( $this );
		return $this->set_labels( $labels );
	}

	/**
	 * Register the post type stuff
	 *
	 * @return void
	 */
	public function register() {

		add_action( 'init', [ $this, 'register_post_type' ], 15 );
		add_action( 'init', [ $this, 'register_taxonomies' ], 15 );

		if ( isset( $this->columns ) ) {
			// Modify the admin columns for the post type
			add_filter( 'manage_' . $this->get_slug() . '_posts_columns', [ $this->columns, 'register' ], 15 );

			// Populate the admin columns for the post type
			add_action( 'manage_' . $this->get_slug() . '_posts_custom_column', [ $this->columns, 'populate_columns' ], 15, 2 );

			// Run filter to make columns sortable.
            add_filter('manage_edit-' . $this->get_slug() . '_sortable_columns', [ $this->columns, 'sortable_columns' ], 15 );

			// Run action that sorts columns on request.
            add_action( 'pre_get_posts', [ $this->columns, 'sort_sortable_columns' ], 15 );
		}
	}

	/**
	 * Register the post type himself
	 *
	 * @return WP_Post_Type|WP_Error|bool The registered post type object on success, WP_Error object on failure or False if the post type already exists.
	 */
	public function register_post_type() {
		$post_type_registerer = new PostTypeRegisterer( $this );

		return $post_type_registerer->register_post_type();
	}

	/**
	 * Register the taxonomies for the post type.
	 *
	 * @return void
	 */
	public function register_taxonomies() {
		$taxonomy_registerer = new TaxonomyRegisterer( $this );
		$taxonomy_registerer->register_taxonomies();
	}

	/**
	 * Get the Column Manager for the post type.
	 *
	 * @return PostTypeHandler\Columns\Columns
	 */
	public function columns() {
		if ( ! isset( $this->columns ) ) {
			$this->columns = new Columns();
		}

		return $this->columns;
	}

	/**
	 * Update the query to sort by the custom column.
	 *
	 * @param \WP_Query $query The query to update.
	 *
	 * @return void
	 */
	public function sort_sortable_columns( \WP_Query $query ) {
		$sort_sortable_columns = new ColumnsSortSortable( $this );
		$sort_sortable_columns->sort_column( $query );
	}

	/**
	 * Getters & Setters
	 */
	public function get_name(): string {
		return $this->name;
	}

	public function set_name( string $name ): string {
		return $this->name = $name;
	}

	public function get_plural_name(): string {
		return $this->plural_name;
	}

	public function set_plural_name( string $plural_name ): string {
		return $this->plural_name = $plural_name;
	}

	public function get_slug(): string {
		return $this->slug;
	}

	public function set_slug( string $slug ): string {
		return $this->slug = $slug;
	}

	public function get_options(): array {
		return $this->options;
	}

	public function set_options( array $options ): array {
		return $this->options = $options;
	}

	public function get_labels(): array {
		return $this->labels;
	}

	public function set_labels( array $labels ): array {
		return $this->labels = $labels;
	}

	public function get_taxonomies(): array {
		return $this->taxonomies;
	}

	/**
	 * Setter for the taxonomies
	 *
	 * @param array|string|Taxonomy $taxonomies Taxonomies to set.
	 *
	 * @return array
	 */
	public function set_taxonomies( $taxonomies ): array {
		$taxonomy_formatter = new TaxonomyArrayFormatter();

		return $this->taxonomies = $taxonomy_formatter->format( $taxonomies );
	}
}