<?php
/**
 * @package WP_Lever
 */

namespace WP_Lever\Validators;

/**
 *
 */
class Job_Posting_Validator {
	/**
	 * @param array $item
	 *
	 * @return bool
	 */
	public static function validate( $item ) {
		if ( ! isset( $item['id'] ) ) {
			return false;
		}

		if ( ! isset( $item['hostedUrl'] ) ) {
			return false;
		}

		if ( ! isset( $item['applyUrl'] ) ) {
			return false;
		}

		return true;
	}
}
