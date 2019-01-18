<?php
/**
 * Default template
 *
 * @package WP_Lever
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! empty( $filters ) ) {
	include 'parts/filters.php';
}

if ( ! empty( $jobs_by_group ) ) {
	include 'parts/postings.php';
} else {
	include 'parts/no-postings.php';
}

include 'parts/style-overwrites.php';