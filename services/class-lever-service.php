<?php
/**
 * @package WP_Lever
 */

namespace WP_Lever\Services;

use WP_Lever\Transformers\Api_Response_Transformer;

class Lever_Service {
	const FIELD_PREFIX = "lever_";
	// 30kb
	const MAX_FILE_SIZE = 3e+7;
	const MAX_POST_ATTEMPTS = 3;

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
	 * @param $site
	 * @param $job_id
	 *
	 * @return \WP_Lever\Data_Transfer_Objects\Job_Posting|null
	 */
	public static function get_job( $site, $job_id ) {
		$url      = 'https://api.lever.co/v0/postings/' . $site . '/' . $job_id;
		$response = wp_remote_get( $url, [
			'timeout' => 200,
			'headers' => [
				'Accept' => 'application/json',
			],
		] );
		$body     = wp_remote_retrieve_body( $response );

		return Api_Response_Transformer::transform_single( $body );
	}

	/**
	 * @param $site
	 * @param $job_id
	 * @param $api_key
	 * @param $silent , default false
	 * @param $tries , default 0
	 *
	 * @return bool
	 */
	public static function send_application( $site, $job_id, $api_key, $silent = false, $tries = 0 ) {
		if ( $tries > self::MAX_POST_ATTEMPTS ) {
			return false;
		}

		$url      = 'https://api.lever.co/v0/postings/' . $site . '/' . $job_id . '?key=' . $api_key;
		$boundary = wp_generate_password( 24, false, false );

		$headers = [
			'content-type' => 'multipart/form-data; boundary=' . $boundary,
		];

		try {
			$payload = self::buildPostPayload( $boundary, $silent );
		} catch ( \Exception $e ) {
			return false;
		}

		$response = wp_remote_post( $url, [
			'method'  => 'POST',
			'timeout' => 200,
			'headers' => $headers,
			'body'    => $payload,
		] );

		$statusCode = wp_remote_retrieve_response_code( $response );
		if ( $statusCode == 429 ) {
			sleep( 1 );
			self::send_application( $site, $job_id, $api_key, $silent, ++ $tries );
		}

		if ( $statusCode != 200 ) {
			return false;
		}

		return true;
	}


	/**
	 * @param $boundary
	 * @oaran $silent
	 *
	 * @return string
	 */
	private static function buildPostPayload( $boundary, $silent ) {
		$payload = '';

		foreach ( $_POST as $name => $value ) {
			//include only lever_ prefixed fields
			if ( strpos( $name, self::FIELD_PREFIX ) != 0 ) {
				continue;
			}

			//remove the prefix
			$field_name = str_replace( self::FIELD_PREFIX, '', $name );

			if ( is_array( $value ) ) {
				$payload .= self::getArrayPostField( $boundary, $field_name, $value );
				continue;
			}

			$payload .= self::getPostField( $boundary, $field_name, $value );
		}

		$silentValue = "false";
		if ( $silent ) {
			$silentValue = "true";
		}

		$payload .= self::getPostField( $boundary, "silent", $silentValue );
		$payload .= self::getFilesPayload( $boundary );

		$payload .= '--' . $boundary . '--';

		return $payload;
	}

	private static function getArrayPostField( $boundary, $field_name, $value ) {
		$payload = "";
		foreach ( $value as $arr_key => $arr_value ) {
			if ( is_int( $arr_key ) ) {
				$arr_fieldName = $field_name . "[]";
			} else {
				$arr_fieldName = $field_name . "[" . $arr_key . "]";
			}

			if ( is_array( $arr_value ) ) {
				$payload .= self::getArrayPostField( $boundary, $arr_fieldName, $arr_value );
				continue;
			}

			$payload .= self::getPostField( $boundary, $arr_fieldName, $arr_value );
		}

		return $payload;
	}

	/**
	 * @param $boundary
	 * @param $payload
	 *
	 * @return string
	 * @throws \Exception
	 */
	private static function getFilesPayload( $boundary ) {
		$payload = "";
		foreach ( $_FILES as $name => $file ) {
			//include only lever_ prefixed fields
			if ( strpos( $name, self::FIELD_PREFIX ) != 0 ) {
				continue;
			}

			if ( is_array( $file["name"] ) ) {
				foreach ( $file["name"] as $fileId => $fieldNumArr ) {
					foreach ( $fieldNumArr as $fieldNum => $fileName ) {
						// remove prefix
						$field_name = str_replace( self::FIELD_PREFIX, '', $name );
						$field_name .= "[" . $fileId . "][" . $fieldNum . "]";
						$payload    .= self::getPayloadForFile(
							$boundary,
							$field_name,
							$file["size"][ $fileId ][ $fieldNum ],
							$file["error"][ $fileId ][ $fieldNum ],
							$file["name"][ $fileId ][ $fieldNum ],
							$file["tmp_name"][ $fileId ][ $fieldNum ],
							$file["type"][ $fileId ][ $fieldNum ]
						);
					}
				}

				continue;
			}

			$field_name = str_replace( self::FIELD_PREFIX, '', $name );
			$payload    .= self::getPayloadForFile( $boundary, $field_name, $file["size"], $file["error"], $file["name"], $file["tmp_name"], $file["type"] );
		}

		return $payload;
	}

	/**
	 * @param $boundary
	 * @param $payload
	 * @param $field_name
	 * @param $file_size
	 * @param $file_error
	 * @param $file_name
	 * @param $file_tmp_name
	 * @param $file_type
	 *
	 * @return string
	 * @throws \Exception
	 */
	private function getPayloadForFile( $boundary, $field_name, $file_size, $file_error, $file_name, $file_tmp_name, $file_type ) {
		if ( $file_size > self::MAX_FILE_SIZE ) {
			throw new \Exception( $field_name . ' size larger than the limit' );
		}

		if ( $file_error !== UPLOAD_ERR_OK ) {
			switch ( $file_error ) {
				case UPLOAD_ERR_INI_SIZE:
				case UPLOAD_ERR_FORM_SIZE:
					throw new \Exception( "failed to upload " . $field_name . " file too large" );
					break;
				case UPLOAD_ERR_PARTIAL:
					throw new \Exception( "failed to upload " . $field_name . " was uploaded partially" );
					break;
				case UPLOAD_ERR_NO_FILE:
					break;
				default:
					throw new \Exception( "failed to upload " . $field_name . " internal server error" );
			}
		}

		if ( $file_size == 0 ) {
			return "";
		}

		return self::getPayloadForFileField( $boundary, $field_name, $file_name, $file_tmp_name, $file_type );
	}

	/**
	 * @param $boundary
	 * @param $field_name
	 * @param $file_location
	 * @param $type
	 *
	 * @return string
	 */
	private static function getPayloadForFileField( $boundary, $field_name, $file_name, $file_location, $type ) {
		$payload = '--' . $boundary;
		$payload .= "\r\n";
		$payload .= 'Content-Disposition: form-data; name="' . $field_name .
		            '"; filename="' . $file_name . '"' . "\r\n";
		$payload .= 'Content-Type: ' . $type . "\r\n";
		$payload .= "\r\n";
		$payload .= file_get_contents( $file_location );
		$payload .= "\r\n";

		return $payload;
	}

	/**
	 * @param $boundary
	 * @param $field_name
	 * @param $field_value
	 *
	 * @return string
	 */
	private static function getPostField( $boundary, $field_name, $field_value ) {
		$payload = '--' . $boundary;
		$payload .= "\r\n";
		$payload .= 'Content-Disposition: form-data; name="' . $field_name .
		            '"' . "\r\n\r\n";
		$payload .= $field_value;
		$payload .= "\r\n";

		return $payload;
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