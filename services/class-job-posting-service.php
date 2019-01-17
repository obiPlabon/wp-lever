<?php
/**
 * @package WP_Lever
 */

namespace WP_Lever\Services;

use WP_Lever\Data_Transfer_Objects\Job_Posting;

class Job_Posting_Service {
	/**
	 * @param Job_Posting[] $job_postings
	 *
	 * @return Job_Posting[][]
	 */
	public static function group_job_postings_by_team( $job_postings ) {
		$grouped = [];

		foreach ( $job_postings as $job_posting ) {
			$team               = $job_posting->get_categories()->get_team();
			$grouped[ $team ][] = $job_posting;
		}

		ksort( $grouped );

		return $grouped;
	}

	/**
	 * @param Job_Posting[] $job_postings
	 *
	 * @return array
	 */
	public static function get_available_filters( $job_postings ) {
		$filters = [];
		foreach ( $job_postings as $job_posting ) {
			$location   = $job_posting->get_categories()->get_location();
			$department = $job_posting->get_categories()->get_department();
			$teams      = $job_posting->get_categories()->get_team();
			$commitment = $job_posting->get_categories()->get_commitment();

			if ( ! empty( $location ) ) {
				$filters['location'][] = $location;
			}

			if ( ! empty( $department ) ) {
				$filters['department'][] = $department;
			}

			if ( ! empty( $teams ) ) {
				$filters['team'][] = $teams;
			}

			if ( ! empty( $commitment ) ) {
				$filters['commitment'][] = $commitment;
			}
		}

		$unique = [];
		foreach ( $filters as $key => $filter_values ) {
			$unique[ $key ] = array_unique( $filter_values );
			sort( $unique[ $key ] );
		}

		$filters_with_options = array_filter( $unique, function ( $filter_values ) {
			return ! ( count( $filter_values ) < 2 );
		} );

		return $filters_with_options;
	}
}