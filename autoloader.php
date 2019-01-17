<?php
/**
 * @package WP_Lever
 */

spl_autoload_register( 'wp_lever_namespace_autoload' );

/**
 * @param string $class_name
 */
function wp_lever_namespace_autoload( $class_name ) {
	if ( false === strpos( $class_name, 'WP_Lever' ) ) {
		return;
	}

	$file_parts = explode( '\\', $class_name );

	if ( 1 === count( $file_parts ) ) {
		return;
	}

	$namespace = '';
	$file_name = '';

	for ( $i = count( $file_parts ) - 1; $i > 0; $i -- ) {
		$current = str_ireplace( '_', '-', strtolower( $file_parts[ $i ] ) );

		if ( count( $file_parts ) - 1 === $i ) {
			if ( stripos( $file_parts[ count( $file_parts ) - 1 ], 'interface' ) ) {
				$interface_name = explode( '_', $file_parts[ count( $file_parts ) - 1 ] );
				$interface_name = $interface_name[0];

				$file_name = "interface-$interface_name.php";
			} else {
				$file_name = "class-$current.php";
			}
		} else {
			$namespace = '/' . $current . $namespace;
		}
	}

	$file_path = trailingslashit( __DIR__ . $namespace );
	$file_path .= $file_name;

	if ( file_exists( $file_path ) ) {
		include_once( $file_path );
	} else {
		wp_die(
			esc_html( "The file attempting to be loaded at $file_path does not exist." )
		);
	}
}