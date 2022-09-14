<?php 

namespace PostTypeHandler;

use PostTypeHandler\Columns\Columns;
use PostTypeHandler\Helpers\LabelsHandler;
use PostTypeHandler\PostType\PostTypeFilters;
use PostTypeHandler\Helpers\DashiconFormatter;
use PostTypeHandler\Columns\ColumnsSortSortable;
use PostTypeHandler\PostType\PostTypeRegisterer;
use PostTypeHandler\PostType\TaxonomyRegisterer;
use PostTypeHandler\Helpers\TaxonomyArrayFormatter;
use PostTypeHandler\PostType\PostTypeLabelsManager;
use PostTypeHandler\PostType\PostTypeOptionsManager;

/**
 * Class to handle the registration of post types
 * 
 * @package PostTypeHandler
 * @link https://github.com/gturpin-dev/PostTypeHandler
 * @author gturpin-dev
 * @link https://github.com/gturpin-dev/
 * @license GPL-3.0
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
	 * @var string The dashicon to use for the post type.
	 */
	private $icon = '';

	/**
	 * @var array Options for the post type.
	 */
	private $options;

	/**
	 * @var array Labels for the post type.
	 */
	private $labels;

	/**
	 * @var array Taxonomies filters for the post type admin edit screen.
	 */
	private $taxonomy_filters = [];

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
	}

	/**
	 * Register the post type stuff
	 *
	 * @return void
	 */
	public function register() {

		// Register the post type
		add_action( 'init', [ $this, 'register_post_type' ] );

		// Update the post type for existing ones | Can't fire conditionally because of the 'init' hook.
		add_action( 'register_post_type_args', [ $this, 'update_post_type' ], 15, 2 );

		// Register taxonomies to the post type
		add_action( 'init', [ $this, 'register_taxonomies' ] );

		// Modify filters on the admin edit screen
        add_action( 'restrict_manage_posts', [ $this, 'update_admin_filters' ], 15, 2 );

		if ( isset( $this->columns ) ) {
			// Modify the admin columns for the post type
			add_filter( 'manage_' . $this->get_slug() . '_posts_columns', [ $this->columns, 'register' ], 15 );

			// Populate the admin columns for the post type
			add_action( 'manage_' . $this->get_slug() . '_posts_custom_column', [ $this->columns, 'populate_columns' ], 15, 2 );

			// Run filter to make columns sortable.
            add_filter( 'manage_edit-' . $this->get_slug() . '_sortable_columns', [ $this->columns, 'sortable_columns' ], 15 );

			// Run action that sorts columns on request.
            add_action( 'pre_get_posts', [ $this, 'sort_sortable_columns' ], 15 );
		}
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
	 * Update the admin filters for the post type
	 *
	 * @param string $post_type the post type slug
	 * @param string $which the location of the filters (top, bottom)
	 *
	 * @return void
	 */
	public function update_admin_filters( string $post_type, string $which ) {
		$post_type_filters = new PostTypeFilters( $this );

		$post_type_filters->update_admin_filters( $post_type, $which );
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
	 * Update an existing post type with new options.
	 *
	 * @param array $args The current options.
	 * @param string $post_type_slug The current post type slug.
	 * 
	 * @return void
	 */
	public function update_post_type( array $args, string $post_type_slug ) {
		$post_type_registerer = new PostTypeRegisterer( $this );

		return $post_type_registerer->update_post_type( $args, $post_type_slug );
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

	public function get_taxonomy_filters(): array {
		return $this->taxonomy_filters;
	}

	/**
	 * Set the taxonomy filters for the post type
	 *
	 * @param array $taxonomy_filters Taxonomy filters.
	 *
	 * @return PostType The current instance of the class.
	 */
	public function set_taxonomy_filters( array $taxonomy_filters ) {
		$this->taxonomy_filters = array_unique( $taxonomy_filters );
		
		return $this;
	}

	public function get_icon(): string {
		return $this->icon;
	}

	/**
	 * Set the icon for the post type
	 *
	 * @param string $icon Icon slug from wp dashicons.
	 * 
	 * @see https://developer.wordpress.org/resource/dashicons/ WP Dashicons
	 *
	 * @return string The current icon slug.
	 */
	public function set_icon( string $icon ): string {
		$dashicon_formatter = new DashiconFormatter();
		
		return $this->icon = $dashicon_formatter->format( $icon );
	}
}