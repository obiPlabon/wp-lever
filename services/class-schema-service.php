<?php
/**
 * @package WP_Lever
 */

namespace WP_Lever\Services;


/**
 *
 */
class Schema_Service {
	/**
	 * @param \WP_Lever\Data_Transfer_Objects\Job_Posting|null $job_posting
	 *
	 * @return string
	 */
	public static function getJsonLDString( $job_posting ) {
		if ( $job_posting == null ) {
			return "";
		}

		$json_ld = array(
			"@content"           => "http://schema.org/",
			"@type"              => "JobPosting",
			"title"              => $job_posting->get_title(),
			"description"        => $job_posting->get_description()->get_formatted(),
			"identifier"         => array(
				"@type" => "PropertyValue",
				"name"  => $job_posting->get_title(),
				"value" => $job_posting->get_id()
			),
			"jobLocation"        => array(
				"@type"   => "Place",
				"address" => $job_posting->get_categories()->get_location()
			),
			"hiringOrganization" => array(
				"@type"      => "Organization",
				"name"       => $job_posting->get_categories()->get_department(),
				"department" => array(
					"@type" => "Organization",
					"name"  => $job_posting->get_categories()->get_team()
				)
			)
		);

		if ($job_posting->get_created_at() != null){
			$json_ld["datePosted"] = $job_posting->get_created_at()->format("Y-m-d\TH:i:sO");
		}

		return json_encode( $json_ld, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES );
	}
}