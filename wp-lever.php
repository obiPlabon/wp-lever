<?php
/**
 * @package WP_Lever
 */
/*
Plugin Name: WP Lever
Plugin URI: https://github.com/obiPlabon/wp-lever
Description: Shortcode for Lever.co api. Super easily show job listing from lever.co
Version: 0.0.1
Author: obiPlabon
Author URI: https://obiPlabon.im/
License: GPLv2 or later
Text Domain: wp-lever
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2018 obiPlabon.
*/

namespace WP_Lever;

use WP_Lever\Services\Job_Posting_Service;
use WP_Lever\Services\Lever_Service;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( trailingslashit( __DIR__ ) . '/autoloader.php' );


if ( ! class_exists( 'WP_Lever' ) ) {
	/**
	 * Class WP_Lever
	 */
	class WP_Lever {

		/**
		 * Slug used in various places.
		 *
		 * @var string
		 */
		protected $slug = 'lever';

		protected $filters = [
			'team',
			'department',
			'location',
			'commitment'
		];

		/**
		 * WP_Lever constructor.
		 */
		public function __construct() {
			add_action( 'init', [ $this, 'init' ] );
		}

		/**
		 * Initialize.
		 */
		public function init() {
			add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
			add_shortcode( $this->slug, [ $this, 'add_shortcode' ] );
		}

		public function enqueue_scripts() {
			wp_enqueue_style(
				$this->slug,
				plugin_dir_url( __FILE__ ) . 'css/main.css',
				null,
				'1.0.1'
			);

			wp_enqueue_script(
				$this->slug,
				plugin_dir_url( __FILE__ ) . 'js/filters.js',
				[ 'jquery' ],
				'1.0.1',
				true
			);
		}

		/**
		 * Render shortcode output.
		 *
		 * @param array $atts
		 * @param null $content
		 *
		 * @return string
		 */
		public function add_shortcode( $atts, $content = null ) {
			$defaults = [
				'skip'               => '',
				'limit'              => '',
				'location'           => '',
				'commitment'         => '',
				'team'               => '',
				'department'         => '',
				'level'              => '',
				'group'              => '',
				'template'           => 'default',
				'site'               => 'leverdemo',
				'filters'            => 'enabled',
				'primary-color'      => null,
				'primary-text-color' => null,
			];
			$atts     = shortcode_atts( $defaults, $atts, $this->slug );
			$site     = $atts['site'];

			unset( $atts['template'], $atts['site'] );

			$filters        = null;
			$filters_status = $atts['filters'] === 'enabled';

			$static_filters = $this->get_static_filters( $atts );

			if ( $filters_status ) {
				$active_filters = $this->get_active_filters_from_request();
			}

			$atts = $this->populate_atts_with_filters_from_request( $static_filters, $atts );

			$filtered_jobs = Lever_Service::get_jobs( $site, $atts );
			$jobs_by_group = [];
			if ( null !== $filtered_jobs ) {
				$jobs_by_group = Job_Posting_Service::group_job_postings_by_team( $filtered_jobs );
			}

			foreach ( $this->filters as $filter ) {
				if ( ! isset( $static_filters[ $filter ] ) ) {
					unset( $atts[ $filter ] );
				}
			}

			if ( $filters_status ) {
				$full_job_postings = Lever_Service::get_jobs( $site, $atts );
				$filters           = Job_Posting_Service::get_available_filters( $full_job_postings );
			}

			ob_start();
			wp_enqueue_style( $this->slug );
			include 'templates/default.php';

			return ob_get_clean();
		}

		/**
		 * @param array $static_filters
		 * @param array $atts
		 *
		 * @return array
		 */
		private function populate_atts_with_filters_from_request( array $static_filters, array $atts ) {
			foreach ( $this->filters as $filter ) {
				if ( ! isset( $static_filters[ $filter ] ) && isset( $_GET[ $filter ] ) ) {
					$atts[ $filter ] = $_GET[ $filter ];
				}
			}

			return $atts;
		}

		/**
		 * @param $atts
		 *
		 * @return array
		 */
		private function get_static_filters( array $atts ) {
			$static_filters = [];
			foreach ( $this->filters as $filter ) {
				if ( $atts[ $filter ] != "" ) {
					$static_filters[ $filter ] = $atts[ $filter ];
				}
			}

			return $static_filters;
		}

		/**
		 * @return array
		 */
		private function get_active_filters_from_request() {
			$activeFilters = [];

			foreach ( $this->filters as $filter ) {
				if ( isset( $_GET[ $filter ] ) ) {
					$activeFilters[ $filter ] = $_GET[ $filter ];
				}
			}

			return $activeFilters;
		}

		/**
		 * @param string $current_filter
		 * @param string $value
		 *
		 * @return string
		 */
		private function build_filter_url( $current_filter, $value ) {
			$urlParts = [];

			foreach ( $_GET as $active_filter => $active_filter_value ) {
				if ( $active_filter === $current_filter ) {
					continue;
				}
				$urlParts[] = $this->build_filter_part( $active_filter, $active_filter_value );
			}

			if ( $value !== '' ) {
				$urlParts[] = $this->build_filter_part( $current_filter, $value );
			}


			return sprintf( '?%s', implode( '&', $urlParts ) );
		}

		/**
		 * @param string $filter
		 * @param string $value
		 *
		 * @return string
		 */
		private function build_filter_part( $filter, $value ) {
			return sprintf( '%s=%s', urlencode( $filter ), urlencode( $value ) );
		}
	}

	new WP_Lever();
}