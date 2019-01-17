<?php
/**
 * @package WP_Lever
 */

namespace WP_Lever\Transformers;

use WP_Lever\Data_Transfer_Objects\Job_Posting;
use WP_Lever\Data_Transfer_Objects\List_Item;
use WP_Lever\Data_Transfer_Objects\Listing_Text;
use WP_Lever\Data_Transfer_Objects\Categories;
use WP_Lever\Validators\Job_Posting_Validator;

/**
 *
 */
class Api_Response_Transformer {
	/**
	 * @param string $jsonResponse
	 *
	 * @return Job_Posting[]|null
	 */
	public static function transform( $jsonResponse ) {
		$response = json_decode( $jsonResponse, true );

		if ( empty( $response ) ) {
			return null;
		}

		return self::transform_from_array( $response );
	}

	/**
	 * @param array $response
	 *
	 * @return Job_Posting[]
	 */
	private static function transform_from_array( $response ) {
		$jobPostings = [];

		foreach ( $response as $item ) {
			$transformedItem = self::transform_item( $item );

			if ( null === $transformedItem ) {
				continue;
			}

			$jobPostings[] = $transformedItem;
		}

		return $jobPostings;
	}

	/**
	 * @param array $item
	 *
	 * @return Job_Posting|null
	 */
	private static function transform_item( $item ) {
		$job_posting = new Job_Posting();

		$valid_item = Job_Posting_Validator::validate( $item );

		if ( ! $valid_item ) {
			return null;
		}

		$job_posting->set_id( $item['id'] );
		$job_posting->set_title( $item['text'] );

		$additional = new Listing_Text();
		$additional->set_formatted( isset( $item['additional'] ) ? $item['additional'] : '' );
		$additional->set_plain( isset( $item['additionalPlain'] ) ? $item['additionalPlain'] : '' );

		$job_posting->set_additional( $additional );

		$description = new Listing_Text();
		$description->set_formatted( isset( $item['description'] ) ? $item['description'] : '' );
		$description->set_plain( isset( $item['descriptionPlain'] ) ? $item['descriptionPlain'] : '' );

		$job_posting->set_description( $description );

		$categories = new Categories();
		$categories->set_commitment( isset( $item['categories']['commitment'] ) ? $item['categories']['commitment'] : null );
		$categories->set_location( isset( $item['categories']['location'] ) ? $item['categories']['location'] : null );
		$categories->set_team( isset( $item['categories']['team'] ) ? $item['categories']['team'] : null );
		$categories->set_department( isset( $item['categories']['department'] ) ? $item['categories']['department'] : null );

		$job_posting->set_categories( $categories );
		$job_posting->set_description_url( isset( $item['hostedUrl'] ) ? $item['hostedUrl'] : '' );
		$job_posting->set_apply_url( isset( $item['applyUrl'] ) ? $item['applyUrl'] : '' );

		self::transform_lists( isset( $item['lists'] ) ? $item['lists'] : [] );


		try {
			$created_at = new \DateTime( $item['createdAt'] );
		} catch ( \Exception $e ) {
			$created_at = null;
		}

		$job_posting->set_created_at( $created_at );

		return $job_posting;
	}

	/**
	 * @param $lists
	 *
	 * @return List_Item[]
	 */
	private static function transform_lists( $lists ) {
		$listItems = [];

		foreach ( $lists as $list ) {
			$listItem = new List_Item();
			$listItem->set_title( isset( $list['text'] ) ? $list['text'] : '' );
			$listItem->set_content( isset( $list['content'] ) ? $list['content'] : '' );

			$listItems[] = $listItem;
		}

		return $listItems;
	}
}
