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

			add_action( 'admin_menu', [ $this, 'wp_lever_settings_page' ] );
			add_action( 'admin_init', [ $this, 'wp_lever_settings_init' ] );
		}

		/**
		 * Add css and js files
		 */
		public function enqueue_scripts() {
			wp_enqueue_style(
				"main",
				plugin_dir_url( __FILE__ ) . 'css/main.css',
				null,
				'1.0.5'
			);

			wp_enqueue_script(
				"file_input",
				plugin_dir_url( __FILE__ ) . 'js/file_input.js',
				[ 'jquery' ],
				'1.0.5',
				true
			);

			wp_enqueue_script(
				"filters",
				plugin_dir_url( __FILE__ ) . 'js/filters.js',
				[ 'jquery' ],
				'1.0.5',
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

			$silentConf = esc_attr( get_option( 'silent', '' ) );
			$silent     = $silentConf == 'on' ? true : false;
			$api_key    = esc_attr( get_option( 'api_key', '' ) );

			if ( isset( $_GET["wpl_s"] ) && isset( $_GET["j_id"] ) && $_GET["j_id"] !== "" ) {
				return $this->result_page( $_GET["wpl_s"], $_GET["j_id"] );
			}

			if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_GET["apply"] ) && isset( $_GET["j_id"] ) && $_GET["j_id"] !== "" ) {
				$result = Lever_Service::send_application( $atts['site'], $_GET['j_id'], $api_key, $silent );

				// TODO: if succeed and silent, and have enabled a custom mail, then send the custom mail.
				return $this->redirect_to_application_result( $result, $_GET["j_id"] );
			}

			if ( isset( $_GET["apply"] ) && isset( $_GET["j_id"] ) && $_GET["j_id"] !== "" ) {
				return $this->apply_job_page( $atts, $_GET["j_id"] );
			}

			if ( isset( $_GET["j_id"] ) && $_GET["j_id"] !== "" ) {
				return $this->job_description_page( $atts, $_GET["j_id"] );
			}

			return $this->job_listing_page( $atts );
		}

		function wp_lever_settings_init() {
			add_settings_section(
				'wp-lever-section',
				'WP Lever Settings',
				'',
				'wp-lever-settings-page'
			);

			register_setting(
				'wp-lever-settings-page',
				'api_key'
			);

			register_setting(
				'wp-lever-settings-page',
				'consent_text'
			);

			register_setting(
				'wp-lever-settings-page',
				'additional_cards_template'
			);

			register_setting(
				'wp-lever-settings-page',
				'silent'
			);

			register_setting(
				'wp-lever-settings-page',
				'fail_message'
			);

			register_setting(
				'wp-lever-settings-page',
				'success_message'
			);

			add_settings_field(
				'api-key',
				'Api Key',
				[ $this, 'wp_lever_apikey_setting_cb' ],
				'wp-lever-settings-page',
				'wp-lever-section'
			);

			add_settings_field(
				'consent-text',
				'Consent Text (accepts HTML)',
				[ $this, 'wp_lever_consent_text_setting_cb' ],
				'wp-lever-settings-page',
				'wp-lever-section'
			);

			add_settings_field(
				'additional-cards-template',
				'Additional Cards Template (json)',
				[ $this, 'wp_lever_additional_cards_template_setting_cb' ],
				'wp-lever-settings-page',
				'wp-lever-section'
			);

			add_settings_field(
				'silent',
				'Silent (If checked it wont send Lever mail)',
				[ $this, 'wp_lever_silent_setting_cb' ],
				'wp-lever-settings-page',
				'wp-lever-section'
			);

			add_settings_field(
				'success-message',
				'Success message, displayed after successful application (accepts HTML)',
				[ $this, 'wp_lever_success_message_setting_cb' ],
				'wp-lever-settings-page',
				'wp-lever-section'
			);

			add_settings_field(
				'fail-message',
				'Fail message, displayed if the application fails (accepts HTML)',
				[ $this, 'wp_lever_fail_message_setting_cb' ],
				'wp-lever-settings-page',
				'wp-lever-section'
			);
		}

		function wp_lever_success_message_setting_cb() {
			$success_message = esc_attr( get_option( 'success_message', '' ) );
			?>
            <div>
                <textarea
                        name="success_message" rows="10"
                        cols="100"
                ><?php echo $success_message; ?></textarea>
            </div>
			<?php
		}

		function wp_lever_fail_message_setting_cb() {
			$fail_message = esc_attr( get_option( 'fail_message', '' ) );
			?>
            <div>
                <textarea
                        name="fail_message" rows="10"
                        cols="100"
                ><?php echo $fail_message; ?></textarea>
            </div>
			<?php
		}

		function wp_lever_apikey_setting_cb() {
			$api_key = esc_attr( get_option( 'api_key', '' ) );
			?>
            <div>
                <input type="text" name="api_key" class="regular-text code" value="<?php echo $api_key; ?>">
            </div>
			<?php
		}

		function wp_lever_consent_text_setting_cb() {
			$consent_text = esc_attr( get_option( 'consent_text', 'Accept conditions' ) );
			?>
            <div>
                <input type="text" name="consent_text" class="regular-text code" value="<?php echo $consent_text; ?>">
            </div>
			<?php
		}

		function wp_lever_additional_cards_template_setting_cb() {
			$additional_cards_template = esc_attr( get_option( 'additional_cards_template', '' ) );
			?>
            <div>
                <textarea
                        name="additional_cards_template" rows="10"
                        cols="100"
                ><?php echo $additional_cards_template; ?></textarea>
            </div>
			<?php
		}

		function wp_lever_silent_setting_cb() {
			$additional_cards_template = esc_attr( get_option( 'silent', '' ) );
			?>
            <div>
                <input
                        type="checkbox"
                        name="silent"
					<?php echo $additional_cards_template ? "checked='checked'" : ""; ?>
                />
            </div>
			<?php
		}

		function wp_lever_settings_page() {
			add_submenu_page(
				'options-general.php',
				'WP Lever',
				'WP Lever Settings',
				'manage_options',
				'wp-lever-settings-page',
				[ $this, 'wp_lever_settings_page_html' ] // callback function when rendering the page
			);
		}

		function wp_lever_settings_page_html() {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}
			?>
            <div class="wrap">
				<?php settings_errors(); ?>
                <form method="POST" action="options.php">
					<?php settings_fields( 'wp-lever-settings-page' ); ?>
					<?php do_settings_sections( 'wp-lever-settings-page' ) ?>
					<?php submit_button(); ?>
                </form>
            </div>
			<?php
		}

		/**
		 * @param $result
		 * @param $job_id
		 *
		 * @return false|string
		 */
		private function redirect_to_application_result( $result, $job_id ) {
			if ( $result ) {
				$page = add_query_arg( [
					"j_id"  => $job_id,
					"wpl_s" => 1
				], get_post_permalink( get_queried_object_id() ) );

			} else {
				$page = add_query_arg( [
					"j_id"  => $job_id,
					"wpl_s" => 0
				], get_post_permalink( get_queried_object_id() ) );
			}

			if ( wp_redirect( $page ) ) {
				exit;
			}

			echo "failed to redirect";
		}

		private function result_page( $status, $job_id ) {
			ob_start();
			wp_enqueue_style( $this->slug );

			if ( $status == 1 ) {
				include 'templates/success.php';
			} else {
				include 'templates/fail.php';
			}

			return ob_get_clean();
		}

		/**
		 * @param $atts
		 * @param $job_id
		 *
		 * @return false|string
		 */
		private function apply_job_page( $atts, $job_id ) {
			$job     = Lever_Service::get_job( $atts['site'], $job_id );
			$referer = isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : '';

			ob_start();
			wp_enqueue_style( $this->slug );
			include 'templates/apply.php';

			return ob_get_clean();
		}

		/**
		 * @param $atts
		 * @param $job_id
		 *
		 * @return false|string
		 */
		private function job_description_page( $atts, $job_id ) {
			$job = Lever_Service::get_job( $atts['site'], $job_id );

			ob_start();
			wp_enqueue_style( $this->slug );
			include 'templates/job.php';

			return ob_get_clean();
		}

		/**
		 * @param $atts
		 *
		 * @return false|string
		 */
		private function job_listing_page( $atts ) {
			$site = $atts['site'];
			unset( $atts['template'], $atts['site'] );
			$filters        = null;
			$filters_status = $atts['filters'] === 'enabled';

			$static_filters = $this->get_static_filters( $atts );

			$full_job_postings = Lever_Service::get_jobs( $site, $static_filters );

			if ( $filters_status ) {
				$atts           = $this->populate_atts_with_filters_from_request( $static_filters, $atts );
				$active_filters = $this->get_active_filters_from_request();
				$filters        = Job_Posting_Service::get_available_filters( $full_job_postings );
				foreach ( $filters as $filter ) {
					if ( ! isset( $static_filters[ $filter ] ) ) {
						unset( $atts[ $filter ] );
					}
				}
				$filtered_jobs = Job_Posting_Service::apply_filters( $full_job_postings, $atts );
			} else {
				$filtered_jobs = $full_job_postings;
			}

			$jobs_by_group = [];
			if ( null !== $filtered_jobs ) {
				$jobs_by_group = Job_Posting_Service::group_job_postings_by_team( $filtered_jobs );
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

		/**
		 * @param $job_id
		 *
		 * @return mixed
		 */
		private function build_job_url( $job_id ) {
			return add_query_arg( "j_id", $job_id, get_post_permalink( get_queried_object_id() ) );
		}

		/**
		 * @param $job_id
		 *
		 * @return mixed
		 */
		private function build_apply_url( $job_id ) {
			return add_query_arg( [ "j_id" => $job_id, "apply" => 1 ], get_post_permalink( get_queried_object_id() ) );
		}
	}

	new WP_Lever();
}