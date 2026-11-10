<?php
/**
 * Theme supports, menus and editor integration.
 *
 * @package SKT_Starter
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register theme supports and menu locations.
 *
 * Design presets (colors, font sizes, spacing, layout widths) are not declared
 * here: they come from theme-data/ through inc/tokens.php.
 *
 * @return void
 */
function skt_setup(): void {
	load_theme_textdomain( 'skt-starter', SKT_THEME_DIR . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'disable-custom-colors' );
	add_theme_support( 'disable-custom-font-sizes' );

	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' )
	);

	add_theme_support(
		'custom-logo',
		array(
			'height'      => 80,
			'width'       => 240,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);

	/*
	 * The front-end bundle is intentionally not loaded in the editor: blocks
	 * ship their own editor styles. This small stylesheet only keeps the
	 * canvas typography neutral and readable.
	 */
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/editor/editor.css' );

	register_nav_menus(
		array(
			'primary' => __( 'Primary menu', 'skt-starter' ),
			'footer'  => __( 'Footer menu', 'skt-starter' ),
			'legal'   => __( 'Legal menu', 'skt-starter' ),
		)
	);
}
add_action( 'after_setup_theme', 'skt_setup' );
