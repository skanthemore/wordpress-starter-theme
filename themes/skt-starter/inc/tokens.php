<?php
/**
 * Design token pipeline.
 *
 * The theme-data/ folder is the single source of truth. From it the theme:
 *
 * 1. injects color, font size, spacing and layout presets into theme.json;
 * 2. prints every token as a `--skt-*` custom property on the front end and
 *    in the editor canvas;
 * 3. exposes a small public API (`skt_get_design_tokens()` and friends) that
 *    companion plugins can rely on instead of duplicating values.
 *
 * @package SKT_Starter
 */

defined( 'ABSPATH' ) || exit;

/**
 * Get every design token.
 *
 * Color values can be overridden from Theme Options → Colors.
 *
 * @return array{colors: array<int, array<string, string>>, font_sizes: array<int, array<string, string>>, spacings: array<int, array<string, string>>, custom: array<string, array<string, string>>}
 */
function skt_get_design_tokens(): array {
	static $tokens = null;

	if ( null !== $tokens ) {
		return $tokens;
	}

	$data = SKT_THEME_DIR . '/theme-data';

	require_once $data . '/helpers.php';

	$colors = require $data . '/colors.php';

	foreach ( $colors as &$color ) {
		$override = sanitize_hex_color( skt_get_option( skt_color_option_name( $color['slug'] ) ) );

		if ( $override ) {
			$color['color'] = $override;
		}
	}
	unset( $color );

	$widths = require $data . '/widths.php';

	$tokens = array(
		'colors'     => $colors,
		'font_sizes' => require $data . '/font-sizes.php',
		'spacings'   => require $data . '/spacings.php',
		'custom'     => array_merge(
			array(
				'font'  => require $data . '/fonts.php',
				'width' => $widths,
			),
			require $data . '/layout.php'
		),
	);

	/**
	 * Filters the resolved design tokens.
	 *
	 * @param array $tokens Design tokens.
	 */
	$tokens = (array) apply_filters( 'skt_design_tokens', $tokens );

	return $tokens;
}

/**
 * Get the color palette.
 *
 * @return array<int, array<string, string>>
 */
function skt_get_color_palette(): array {
	return skt_get_design_tokens()['colors'];
}

/**
 * Option name that stores the override of a palette color.
 *
 * @param string $slug Color slug.
 * @return string
 */
function skt_color_option_name( string $slug ): string {
	return 'color_' . str_replace( '-', '_', sanitize_key( $slug ) );
}

/**
 * Inject the token presets into the theme.json data layer.
 *
 * Only presets and layout sizes are injected. Global styles are deliberately
 * left out so the block editor keeps its native look; front-end typography
 * and spacing live in the compiled stylesheet.
 *
 * @param WP_Theme_JSON_Data $theme_json Theme JSON data.
 * @return WP_Theme_JSON_Data
 */
function skt_filter_theme_json( WP_Theme_JSON_Data $theme_json ): WP_Theme_JSON_Data {
	$tokens = skt_get_design_tokens();
	$widths = $tokens['custom']['width'];

	return $theme_json->update_with(
		array(
			'version'  => 3,
			'settings' => array(
				'color'      => array( 'palette' => $tokens['colors'] ),
				'typography' => array( 'fontSizes' => $tokens['font_sizes'] ),
				'spacing'    => array( 'spacingSizes' => $tokens['spacings'] ),
				'layout'     => array(
					'contentSize' => $widths['content'],
					'wideSize'    => $widths['wide'],
				),
			),
		)
	);
}
add_filter( 'wp_theme_json_data_theme', 'skt_filter_theme_json' );

/**
 * Strip characters that could break out of a CSS declaration.
 *
 * Token values are authored by developers, but colors can come from the
 * options page, so every value is treated as untrusted.
 *
 * @param string $value Raw CSS value.
 * @return string
 */
function skt_sanitize_css_value( string $value ): string {
	return trim( str_replace( array( '<', '>', '{', '}', ';', '\\' ), '', $value ) );
}

/**
 * Build the custom property declarations for every token.
 *
 * @param string $selector Selector that receives the declarations.
 * @return string
 */
function skt_get_token_css( string $selector = ':root' ): string {
	$tokens       = skt_get_design_tokens();
	$declarations = array();

	foreach ( $tokens['custom'] as $group => $values ) {
		foreach ( $values as $key => $value ) {
			$declarations[ "{$group}-{$key}" ] = $value;
		}
	}

	$presets = array(
		'color'     => array( $tokens['colors'], 'color' ),
		'font-size' => array( $tokens['font_sizes'], 'size' ),
		'spacing'   => array( $tokens['spacings'], 'size' ),
	);

	foreach ( $presets as $prefix => list( $items, $value_key ) ) {
		foreach ( $items as $item ) {
			$declarations[ "{$prefix}-{$item['slug']}" ] = $item[ $value_key ];
		}
	}

	$css = '';

	foreach ( $declarations as $name => $value ) {
		$css .= sprintf( '--skt-%s:%s;', sanitize_key( $name ), skt_sanitize_css_value( (string) $value ) );
	}

	return $selector . '{' . $css . '}';
}

/**
 * Print the token custom properties on the front end.
 *
 * Printed early in <head> so both the theme bundle and block styles can
 * consume them.
 *
 * @return void
 */
function skt_print_token_css(): void {
	printf( "<style id=\"skt-tokens\">%s</style>\n", skt_get_token_css() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Values are sanitized in skt_get_token_css().
}
add_action( 'wp_head', 'skt_print_token_css', 5 );

/**
 * Expose the token custom properties inside the editor canvas.
 *
 * @return void
 */
function skt_enqueue_editor_token_css(): void {
	if ( ! is_admin() ) {
		return;
	}

	wp_register_style( 'skt-tokens', false, array(), SKT_THEME_VERSION );
	wp_enqueue_style( 'skt-tokens' );
	wp_add_inline_style( 'skt-tokens', skt_get_token_css( '.editor-styles-wrapper' ) );
}
add_action( 'enqueue_block_assets', 'skt_enqueue_editor_token_css' );
