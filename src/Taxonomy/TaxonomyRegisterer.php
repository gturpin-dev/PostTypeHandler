<?php 

namespace PostTypeHandler\Taxonomy;

use PostTypeHandler\Taxonomy;
use PostTypeHandler\Taxonomy\Exceptions\TaxonomyNameEmptyException;
use PostTypeHandler\Taxonomy\Exceptions\TaxonomyNameLimitException;

final class TaxonomyRegisterer {
	private const KEY_MAX_LENGTH = 32;

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
	 * @throws TaxonomyNameLimitException
	 * @throws TaxonomyNameEmptyException
	 */
	public function register_taxonomy() {
		if ( ! taxonomy_exists( $this->taxonomy->get_slug() ) && $this->is_valid_slug( $this->taxonomy->get_slug() ) ) {
			$labels            = $this->taxonomy->make_labels();
			$options           = $this->taxonomy->make_options();
			$options['labels'] = $labels;

			return register_taxonomy( $this->taxonomy->get_slug(), null, $options );
		}

		return false;
	}

	/**
	 * Check if the taxonomy slug is valid (not empty and not exceed 32 characters).
	 *
	 * @param string $slug
	 *
	 * @return bool
	 * @throws TaxonomyNameLimitException
	 * @throws TaxonomyNameEmptyException
	 */
	private function is_valid_slug( string $slug ): bool {
		if ( empty( $slug ) ) {
			throw new TaxonomyNameEmptyException( 'The taxonomy slug cannot be empty.' );
		}

		if ( strlen( $slug ) > self::KEY_MAX_LENGTH ) {
			throw new TaxonomyNameLimitException( sprintf(
				'The taxonomy slug must not exceed %d characters.',
				self::KEY_MAX_LENGTH
			) );
		}

		return true;
	}
}