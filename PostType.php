<?php 

namespace PostTypeHandler;

use PostTypeHandler\PostType\PostTypeRegisterer;
use PostTypeHandler\PostType\TaxonomyRegisterer;
use PostTypeHandler\PostType\PostTypeLabelsManager;
use PostTypeHandler\PostType\PostTypeOptionsManager;

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

		$post_type_labels_manager = new PostTypeLabelsManager();

		$slug = $post_type_labels_manager->make_slug( $name );
		return $this->set_slug( $slug );
	}

	/**
	 * Making plural name based on the name
	 * 
	 * @return string Plural name
	 */
	public function make_plural_name(): string {
		$post_type_labels_manager = new PostTypeLabelsManager();

		$plural_name = $post_type_labels_manager->make_plural_name( $this->name );
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
	 * @param array|string $taxonomies Taxonomies to set.
	 *
	 * @return array
	 */
	public function set_taxonomies( $taxonomies ): array {
		// bail early if not an array or string
		if ( ! is_array( $taxonomies ) && ! is_string( $taxonomies ) ) return $this->taxonomies;
		
		
		if ( is_string( $taxonomies ) ) {
			$taxonomies = [ $taxonomies ];
		}
		
		return $this->taxonomies = $taxonomies;
	}
}