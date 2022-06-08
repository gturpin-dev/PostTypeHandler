<?php

namespace PostTypeHandler;

use PostTypeHandler\Helpers\LabelsHandler;

/**
 * CLass to handle the registration of taxonomies
 */
class Taxnonomy {

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
		$taxonomy_options_manager = new TaxnonomyOptionsManager();
		
		$options = $taxonomy_options_manager->make_options( $this );
		return $this->set_options( $options );
	}

	/**
	 * Making labels for taxonomy with default values
	 *
	 * @return array Labels
	 */
	public function make_labels() {
		$taxonomy_labels_manager = new TaxnonomyLabelsManager();

		$labels = $taxonomy_labels_manager->make_labels( $this );
		return $this->set_labels( $labels );
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
}