<?php
/**
 * @package WP Lever
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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_Lever' ) ) {
	class WP_Lever {

		protected $slug = 'lever';

		public function __construct() {
			add_action( 'init', array( $this, 'register_shortcode' ) );
		}

		public function register_shortcode() {
			add_shortcode( $this->slug, array( $this, 'add_shortcode' ) );
		}

		public function add_shortcode( $atts, $content = null ) {
			$defaults = array(
				'skip'       => '',
				'limit'      => '',
				'location'   => '',
				'commitment' => '',
				'team'       => '',
				'department' => '',
				'level'      => '',
				'group'      => '',
				'template'   => 'general'
			);
			$atts = shortcode_atts( $defaults, $atts, $this->slug );
			$template = $atts['template'];

			unset( $atts['template'] );

		}

		protected function generate_multi_param_query( $query, $param ) {
			$query_str = http_build_query( $this->array_from_comma( $param ) );
			return preg_replace( '/[0-9]/', $query, $query_str );
		}

		protected function array_from_comma( $str ) {
			return array_filter( explode( ',', $str ) );
		}

		public function get_jobs() {
			// https://api.lever.co/v0/postings/leverdemo?mode=json
		}
	}
}