<?php 

namespace PostTypeHandler\Helpers;

/**
 * Helper to handle labels, text manipulation
 */
class LabelsHandler {
	
	/**
	 * Making slug based on the name
	 * 
	 * @param string $name the name to slugify
	 *
	 * @return string Slug
	 */
	public function make_slug( string $name ) {
		return sanitize_key( $name );
	}

	/**
	 * Create plural name based on the name
	 * added 's' to the end of the name if it's not already there
	 * if the name ends with 'y', 'ies' is added to the end of the name
	 *
	 * @param string $name
	 *
	 * @return string Plural name
	 */
	public function make_plural_name( string $name ) {
		$last_letter = substr( $name, -1 );

		if ( 'y' === $last_letter ) {
			$name = substr( $name, 0, strlen( $name ) - 1 ) . 'ies';
		} elseif ( 's' !== $last_letter ) {
			$name .= 's';
		}

		return $name;
	}
}