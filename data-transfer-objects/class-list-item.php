<?php
/**
 * @package WP_Lever
 */

namespace WP_Lever\Data_Transfer_Objects;

/**
 *
 */
class List_Item {
	/**
	 * @var string
	 */
	private $title;

	/**
	 * @var string
	 */
	private $content;

	/**
	 * @return string
	 */
	public function get_title() {
		return $this->title;
	}

	/**
	 * @param string $title
	 */
	public function set_title( $title ) {
		$this->title = $title;
	}

	/**
	 * @return string
	 */
	public function get_content() {
		return $this->content;
	}

	/**
	 * @param string $content
	 */
	public function set_content( $content ) {
		$this->content = $content;
	}
}
