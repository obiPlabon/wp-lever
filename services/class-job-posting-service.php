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

	/**
	 * @param Job_Posting[] $job_postings
	 * @param array $atts
	 *
	 * @return mixed
	 */
	public static function apply_filters( $job_postings, $atts ) {
		$filtered_job_postings = [];

		foreach ( $job_postings as $job_posting ) {
			$location   = mb_strtolower($job_posting->get_categories()->get_location());
			$department = mb_strtolower($job_posting->get_categories()->get_department());
			$team      = mb_strtolower($job_posting->get_categories()->get_team());
			$commitment = mb_strtolower($job_posting->get_categories()->get_commitment());

			if ( isset( $atts["location"] ) && $atts["location"] !== "" && mb_strtolower($atts["location"]) != $location ) {
				continue;
			}

			if ( isset( $atts["department"] ) && $atts["department"] !== "" && mb_strtolower($atts["department"]) != $department ) {
				continue;
			}

			if ( isset( $atts["team"] ) && $atts["team"] !== "" && mb_strtolower($atts["team"]) != $team ) {
				continue;
			}

			if ( isset( $atts["commitment"] ) && $atts["commitment"] !== "" && mb_strtolower($atts["commitment"]) != $commitment ) {
				continue;
			}

			$filtered_job_postings[] = $job_posting;
		}

		return $filtered_job_postings;
	}
}