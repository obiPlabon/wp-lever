<?php
/**
 * @package WP_Lever
 */

namespace WP_Lever\Data_Transfer_Objects;

/**
 *
 */
class Listing_Text {
	/**
	 * @var string
	 */
	private $plain;

	/**
	 * @var string
	 */
	private $formatted;

	/**
	 * @return string
	 */
	public function get_plain() {
		return $this->plain;
	}

	/**
	 * @param string $plain
	 */
	public function set_plain( $plain ) {
		$this->plain = $plain;
	}

	/**
	 * @return string
	 */
	public function get_formatted() {
		return $this->formatted;
	}

	/**
	 * @param string $formatted
	 */
	public function set_formatted( $formatted ) {
		$this->formatted = $formatted;
	}
}
