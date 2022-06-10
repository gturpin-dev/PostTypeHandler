<?php 

namespace PostTypeHandler\Taxonomy;

use PostTypeHandler\Taxonomy;

final class TaxonomyRegisterer {

	private Taxonomy $taxonomy;

	public function __construct( Taxonomy $taxonomy ) {
		$this->taxonomy = $taxonomy;
	}

	/**
	 * Register the taxonomy only if it doesn't already exist.
	 * If the taxonomy is registered, it will update its options.
	 * 
	 * @see https://developer.wordpress.org/reference/functions/register_taxonomy/
	 *
	 * @return WP_Taxonomy|WP_Error The registered taxonomy object on success, WP_Error object on failure
	 */
	public function register_taxonomy() {
		if ( ! taxonomy_exists( $this->taxonomy->get_slug() ) ) {
			$labels            = $this->taxonomy->get_labels();
			$options           = $this->taxonomy->get_options();
			$options['labels'] = $labels;

			return register_taxonomy( $this->taxonomy->get_slug(), null, $options );
		}

		return false;
	}
}