<?php
/**
 * Front-end assets.
 *
 * Vite compiles src/ into two files, dist/main.css and dist/main.js. Both are
 * versioned by modification time so every build busts caches.
 *
 * @package SKT_Starter
 */

defined( 'ABSPATH' ) || exit;

/**
 * Enqueue the compiled bundle.
 *
 * @return void
 */
function skt_enqueue_assets(): void {
	$css_path = SKT_THEME_DIR . '/dist/main.css';
	$js_path  = SKT_THEME_DIR . '/dist/main.js';

	if ( file_exists( $css_path ) ) {
		if ( skt_is_css_inlined() ) {
			wp_register_style( 'skt-theme', false, array(), SKT_THEME_VERSION );
			wp_add_inline_style( 'skt-theme', (string) file_get_contents( $css_path ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Local file.
		} else {
			wp_register_style( 'skt-theme', SKT_THEME_URI . '/dist/main.css', array(), (string) filemtime( $css_path ) );
		}

		wp_enqueue_style( 'skt-theme' );
	}

	if ( file_exists( $js_path ) ) {
		wp_enqueue_script_module( 'skt-theme', SKT_THEME_URI . '/dist/main.js', array(), (string) filemtime( $js_path ) );
	}
}
add_action( 'wp_enqueue_scripts', 'skt_enqueue_assets' );

/**
 * Preload the body font so text renders without a late swap.
 *
 * @return void
 */
function skt_preload_fonts(): void {
	$font = 'assets/fonts/inter/Inter-Regular.woff2';

	if ( file_exists( SKT_THEME_DIR . '/' . $font ) ) {
		printf(
			'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
			esc_url( SKT_THEME_URI . '/' . $font )
		);
	}
}
add_action( 'wp_head', 'skt_preload_fonts', 1 );
