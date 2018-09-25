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

        /**
         * WP_Lever constructor.
         */
        public function __construct() {
            add_action( 'init', array( $this, 'init' ) );
        }

        /**
         * Initialize.
         */
        public function init() {
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
            add_shortcode( $this->slug, array( $this, 'add_shortcode' ) );
        }

        public function enqueue_scripts() {
            wp_register_style(
                $this->slug,
                plugin_dir_url( __FILE__ ) . 'css/main.css',
                null,
                '1.0.0'
                );
        }

        /**
         * Render shortcode output.
         *
         * @param array $atts
         * @param null $content
         * @return string
         */
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
                'template'   => 'default',
                'site'       => 'leverdemo',
            );
            $atts = shortcode_atts( $defaults, $atts, $this->slug );
            $template = $atts['template'];
            $site = $atts['site'];

            unset( $atts['template'], $atts['site'] );

            $lever_jobs = $this->get_jobs( $site, $atts );

            ob_start();
            if ( ! empty( $lever_jobs  ) ) {
                wp_enqueue_style( $this->slug );
                include 'templates/default.php';
            }
            return ob_get_clean();
        }

        /**
         * Build query string from an associative array.
         *
         * @param array $params
         * @return string
         */
        protected function build_query_str( $params ) {
            $comma_fields = array(
                'team',
                'commitment',
                'department',
            );

            $query_str = '';
            foreach ( $params as $key => $val ) {
                $val = trim( $val );

                if ( ! $val ) {
                    continue;
                }

                if ( in_array( $key, $comma_fields ) ) {
                    $query_str .= $this->build_multi_query_str( $key, $val ) . '&';
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
         * @return string
         */
        protected function build_multi_query_str( $var, $value ) {
            $query_str = '';
            foreach ( explode( ',', $value) as $val ) {
                $val = trim( $val );
                if ( $val ) {
                    $query_str .= $var . '=' . urlencode( $val ) . '&';
                }
            }
            return rtrim( $query_str, '&' );
        }

        /**
         * Fetch jobs from Lever.co.
         *
         * @param string $site
         * @param array $params
         * @return array|mixed|object
         */
        public function get_jobs( $site, $params ) {
            $query_str = $this->build_query_str( $params );
            $url = 'https://api.lever.co/v0/postings/' . $site . '?' . $query_str;
            $response = wp_remote_get( $url, array(
                'timeout' => 200,
                'headers' => array(
                    'Accept' => 'application/json'
                )
            ) );
            $body = wp_remote_retrieve_body( $response );
            return json_decode( $body );
        }
    }

    new WP_Lever();
}