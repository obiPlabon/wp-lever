<?php
/**
 * @package WP_Lever
 */

namespace WP_Lever\Services;

use WP_Lever\Transformers\Api_Response_Transformer;

class Lever_Service {
	/**
	 * Fetch jobs from Lever.co.
	 *
	 * @param string $site
	 * @param array $params
	 *
	 * @return array|mixed|object
	 */
	public static function get_jobs( $site, $params ) {
		$query_str = self::build_query_str( $params );
		$url       = 'https://api.lever.co/v0/postings/' . $site . '?' . $query_str;
		$response  = wp_remote_get( $url, [
			'timeout' => 200,
			'headers' => [
				'Accept' => 'application/json',
			],
		] );
		$body      = wp_remote_retrieve_body( $response );

		return Api_Response_Transformer::transform( $body );
	}

	/**
	 * Build query string from an associative array.
	 *
	 * @param array $params
	 *
	 * @return string
	 */
	private static function build_query_str( $params ) {
		$comma_fields = [
			'team',
			'commitment',
			'department',
		];

		$query_str = '';
		foreach ( $params as $key => $val ) {
			$val = trim( $val );

			if ( ! $val ) {
				continue;
			}

			if ( in_array( $key, $comma_fields ) ) {
				$query_str .= self::build_multi_query_str( $key, $val ) . '&';
				continue;
			}

			$query_str .= $key . '=' . urlencode( $val ) . '&';
		}

		return rtrim( $query_str, '&' );
	}

	/**
	 * Build query string from comma separated string.
	 *
	 * @param string $var
	 * @param string $value
	 *
	 * @return string
	 */
	private static function build_multi_query_str( $var, $value ) {
		$query_str = '';
		foreach ( explode( ',', $value ) as $val ) {
			$val = trim( $val );
			if ( $val ) {
				$query_str .= $var . '=' . urlencode( $val ) . '&';
			}
		}

		return rtrim( $query_str, '&' );
	}
}