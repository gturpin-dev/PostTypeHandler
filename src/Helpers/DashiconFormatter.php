<?php 

namespace PostTypeHandler\Helpers;

final class DashiconFormatter {
	
	/**
	 * Format a dashicon to add dashicons if it's not already there
	 * 
	 * @param string $dashicon Dashicon to format.
	 * 
	 * @see https://developer.wordpress.org/resource/dashicons/ WP Dashicons
	 *
	 * @return string Formatted dashicon.
	 */
	public function format( string $dashicon ) {
		if ( empty( $dashicon ) ) return '';

		if ( strpos( $dashicon, 'dashicons' ) === false ) {
			$dashicon = 'dashicons-' . $dashicon;
		}

		return $dashicon;
	}
}