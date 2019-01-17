<?php
/**
 * @package WP_Lever
 */

namespace WP_Lever\Data_Transfer_Objects;

/**
 *
 */
class Categories {
	/**
	 * @var null|string
	 */
	private $commitment;

	/**
	 * @var null|string
	 */
	private $location;

	/**
	 * @var null|string
	 */
	private $team;

	/**
	 * @var null|string
	 */
	private $department;

	/**
	 * @return string|null
	 */
	public function get_commitment() {
		return $this->commitment;
	}

	/**
	 * @param string|null $commitment
	 */
	public function set_commitment( $commitment ) {
		$this->commitment = $commitment;
	}

	/**
	 * @return string|null
	 */
	public function get_location() {
		return $this->location;
	}

	/**
	 * @param string|null $location
	 */
	public function set_location( $location ) {
		$this->location = $location;
	}

	/**
	 * @return string|null
	 */
	public function get_team() {
		return $this->team;
	}

	/**
	 * @param string|null $team
	 */
	public function set_team( $team ) {
		$this->team = $team;
	}

	/**
	 * @return string|null
	 */
	public function get_department() {
		return $this->department;
	}

	/**
	 * @param string|null $department
	 */
	public function set_department( $department ) {
		$this->department = $department;
	}
}
