<?php

namespace PostTypeHandler;

use PostTypeHandler\Helpers\LabelsHandler;
use PostTypeHandler\Taxonomy\PostTypeRegisterer;
use PostTypeHandler\Taxonomy\TaxonomyRegisterer;
use PostTypeHandler\Taxonomy\TaxonomyLabelsManager;
use PostTypeHandler\Taxonomy\TaxonomyOptionsManager;

/**
 * CLass to handle the registration of taxonomies
 */
class Taxonomy {

	/**
	 * @var string Name of the taxonomy.
	 */
	private $name;

	/**
	 * @var string Slug of the taxonomy. Generated from the name if not set.
	 */
	private $slug;

	/**
	 * @var array Options for the taxonomy.
	 */
	private $options;

	/**
	 * @var array Labels for the taxonomy.
	 */
	private $labels;

	/**
	 * @var array Post types for the taxonomy.
	 */
	private $post_types;

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
	 * Making options for taxonomy with default values
	 * 
	 * @return array Options
	 */
	public function make_options() {
		$taxonomy_options_manager = new TaxonomyOptionsManager();
		
		$options = $taxonomy_options_manager->make_options( $this );
		return $this->set_options( $options );
	}

	/**
	 * Making labels for taxonomy with default values
	 *
	 * @return array Labels
	 */
	public function make_labels() {
		$taxonomy_labels_manager = new TaxonomyLabelsManager();

		$labels = $taxonomy_labels_manager->make_labels( $this );
		return $this->set_labels( $labels );
	}

	/**
	 * Register the post type stuff
	 *
	 * @return void
	 */
	public function register() {

		add_action( 'init', [ $this, 'register_taxonomy' ], 15 );
		add_action( 'init', [ $this, 'register_post_types' ], 15 );
		
	}

	/**
	 * Register the taxonomy himself
	 *
	 * @return WP_Taxonomy|WP_Error The registered taxonomy object on success, WP_Error object on failure
	 */
	public function register_taxonomy() {
		$taxonomy_registerer = new TaxonomyRegisterer( $this );

		return $taxonomy_registerer->register_taxonomy();
	}

	/**
	 * Register the post types for the taxonomy.
	 *
	 * @return void
	 */
	public function register_post_types() {
		$post_type_registerer = new PostTypeRegisterer( $this );
		$post_type_registerer->register_post_types();
	}

	/**
	 * Getters & setters
	 */
	public function get_slug(): string {
		return $this->slug;
	}

	public function set_slug( string $slug ): string {
		$this->slug = $slug;

		return $this->slug;
	}

	public function get_name(): string {
		return $this->name;
	}

	public function set_name( string $name ): string {
		$this->name = $name;

		return $this->name;
	}

	public function get_plural_name(): string {
		return $this->plural_name;
	}

	public function set_plural_name( string $plural_name ): string {
		$this->plural_name = $plural_name;

		return $this->plural_name;
	}

	public function get_options(): array {
		return $this->options;
	}

	public function set_options( array $options ): array {
		$this->options = $options;

		return $this->options;
	}

	public function get_labels(): array {
		return $this->labels;
	}

	public function set_labels( array $labels ): array {
		$this->labels = $labels;

		return $this->labels;
	}

	public function get_post_types(): array {
		return $this->post_types;
	}

	/**
	 * Setter for the post types
	 *
	 * @param array|string $taxonomies Post types to set
	 *
	 * @return array
	 */
	public function set_post_types( $post_types ): array {
		// bail early if not an array or string
		if ( ! is_array( $post_types ) && ! is_string( $post_types ) ) return $this->post_types;
		
		if ( is_string( $post_types ) ) {
			$post_types = [ $post_types ];
		}
		
		return $this->post_types = $post_types;
	}
}