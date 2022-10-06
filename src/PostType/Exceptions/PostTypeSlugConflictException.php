<?php
/**
 * Define PostTypeSlugConflictException exception.
 */

namespace PostTypeHandler\PostType\Exceptions;

/**
 * Exception thrown when there is a conflict with an existing slug.
 */
class PostTypeSlugConflictException extends \Exception {

	/**
	 * Exception error code.
	 *
	 * @var integer Error code.
	 */
	const CODE = 9993;

	/**
	 * Construct exception object.
	 *
	 * @param string     $message Exception message.
	 * @param \Throwable $previous Previous exception.
	 * @return PostTypeSlugConflictException The exception object.
	 */
	public function __construct( $message = '', \Throwable $previous = null ) {
		return parent::__construct( $message, self::CODE, $previous );
	}
}
