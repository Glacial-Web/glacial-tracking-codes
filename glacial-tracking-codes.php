<?php
/**
 * Plugin Name:     Glacial Tracking Codes
 * Description:     Adds tracking codes to site or individual pages
 * Author:          Glacial Multimedia
 * Author URI:      https://glacial.com
 * Version:         0.1.0
 *
 * @package         Glacial_Tracking_Codes
 */

// If this file is called directly, DIE!!.
if ( ! defined( 'WPINC' ) ) {
	die;
}

function glacial_tracking_acf_notice() { ?>
    <div class="notice notice-error">
        <p>Please install Advanced Custom Fields Pro, it is required for Glacial Tracking Codes plugin to work.</p>
    </div>
<?php }

if ( ! function_exists( 'the_field' ) ) {
	add_action( 'admin_notices', 'glacial_tracking_acf_notice' );

} else {
	// Run all of our hooks
	add_filter( 'acf/settings/load_json', 'glacial_tracking_codes_json_load_point' );
	add_filter( 'acf/settings/save_json', 'glacial_tracking_codes_json_save_point' );
	add_action( 'wp_head', 'glacial_tracking_codes_head' );
	add_action( 'wp_body_open', 'glacial_tracking_codes_after_body' );

	if ( function_exists( 'acf_add_options_page' ) ) {
		acf_add_options_page( array(
		  'page_title' => 'Glacial Tracking Codes',
		  'menu_title' => 'Glacial Tracking Codes',
		  'menu_slug'  => 'glacial-tracking-codes',
		  'capability' => 'edit_posts',
		  'icon_url'   => 'dashicons-chart-line',
		  'redirect'   => false
		) );
	}

	function glacial_tracking_codes_json_save_point( $path ) {
		$path = plugin_dir_path( __FILE__ ) . '/acf-admin';

		return $path;
	}

	// Load ACF JSON
	function glacial_tracking_codes_json_load_point( $path ) {
		unset( $path[0] );

		$path[] = plugin_dir_path( __FILE__ ) . '/acf-admin';

		return $path;
	}

	function glacial_tracking_codes_head() {
		$global_tracking_codes_head = get_field( 'global_tracking_codes_head', 'options' );

		if ( $global_tracking_codes_head ) {
			echo $global_tracking_codes_head;
		}

		if ( have_rows( 'tracking_codes_by_page', 'options' ) ) {

			$fields = get_field( 'tracking_codes_by_page', 'options' );

			foreach ( $fields as $field ) {
				if ( in_array( get_the_ID(), $field['pages'] ) ) {
					echo $field['code'];
				}
			}
		}
	}

	function glacial_tracking_codes_after_body() {
		$global_tracking_codes_body = get_field( 'global_tracking_codes_body', 'options' );

		if ( $global_tracking_codes_body ) {
			echo $global_tracking_codes_body;
		}
	}

}


