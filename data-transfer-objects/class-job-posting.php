<?php
/**
 * @package WP_Lever
 */

namespace WP_Lever\Data_Transfer_Objects;

/**
 *
 */
class Job_Posting {
	/**
	 * @var string
	 */
	private $id;

	/**
	 * @var string
	 */
	private $title;

	/**
	 * @var Listing_Text
	 */
	private $description;

	/**
	 * @var Listing_Text
	 */
	private $additional;

	/**
	 * @var Categories
	 */
	private $categories;

	/**
	 * @var List_Item[]
	 */
	private $lists;

	/**
	 * @var string
	 */
	private $description_url;

	/**
	 * @var string
	 */
	private $apply_url;

	/**
	 * @var \DateTime|null
	 */
	private $created_at;

	/**
	 * @return string
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * @param string $id
	 */
	public function set_id( $id ) {
		$this->id = $id;
	}

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
	 * @return Listing_Text
	 */
	public function get_description() {
		return $this->description;
	}

	/**
	 * @param Listing_Text $description
	 */
	public function set_description( $description ) {
		$this->description = $description;
	}

	/**
	 * @return Listing_Text
	 */
	public function get_additional() {
		return $this->additional;
	}

	/**
	 * @param Listing_Text $additional
	 */
	public function set_additional( Listing_Text $additional ) {
		$this->additional = $additional;
	}

	/**
	 * @return Categories
	 */
	public function get_categories() {
		return $this->categories;
	}

	/**
	 * @param Categories $categories
	 */
	public function set_categories( Categories $categories ) {
		$this->categories = $categories;
	}

	/**
	 * @return List_Item[]
	 */
	public function get_lists() {
		return $this->lists;
	}

	/**
	 * @param List_Item[] $lists
	 */
	public function set_lists( array $lists ) {
		$this->lists = $lists;
	}

	/**
	 * @return string
	 */
	public function get_description_url() {
		return $this->description_url;
	}

	/**
	 * @param string $description_url
	 */
	public function set_description_url( $description_url ) {
		$this->description_url = $description_url;
	}

	/**
	 * @return string
	 */
	public function get_apply_url() {
		return $this->apply_url;
	}

	/**
	 * @param string $apply_url
	 */
	public function set_apply_url( $apply_url ) {
		$this->apply_url = $apply_url;
	}

	/**
	 * @return \DateTime|null
	 */
	public function get_created_at() {
		return $this->created_at;
	}

	/**
	 * @param \DateTime|null $created_at
	 */
	public function set_created_at( $created_at ) {
		$this->created_at = $created_at;
	}
}
