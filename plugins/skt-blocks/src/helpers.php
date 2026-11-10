<?php
/**
 * Helpers shared by block fields and render templates.
 *
 * The blocks never hard-code colors: they read the palette from the active
 * theme through `skt_get_color_palette()` and style themselves with the
 * `--skt-*` custom properties it prints. A neutral fallback keeps them usable
 * under any other theme.
 *
 * @package SKT\Blocks
 */

namespace SKT\Blocks;

defined( 'ABSPATH' ) || exit;

/**
 * Color palette of the active theme.
 *
 * @return array<int, array{name: string, slug: string, color: string}>
 */
function get_palette(): array {
	if ( function_exists( 'skt_get_color_palette' ) ) {
		return skt_get_color_palette();
	}

	return array(
		array(
			'name'  => __( 'Base', 'skt-blocks' ),
			'slug'  => 'base',
			'color' => '#ffffff',
		),
		array(
			'name'  => __( 'Surface', 'skt-blocks' ),
			'slug'  => 'surface',
			'color' => '#f4f4f2',
		),
		array(
			'name'  => __( 'Contrast', 'skt-blocks' ),
			'slug'  => 'contrast',
			'color' => '#16161a',
		),
	);
}

/**
 * Palette as ACF select choices.
 *
 * @return array<string, string>
 */
function get_color_choices(): array {
	return wp_list_pluck( get_palette(), 'name', 'slug' );
}

/**
 * CSS value for a palette color, with its hex value as fallback.
 *
 * @param string $slug Color slug.
 * @return string Empty string for unknown slugs.
 */
function get_color_value( string $slug ): string {
	foreach ( get_palette() as $color ) {
		if ( $color['slug'] === $slug ) {
			return sprintf( 'var(--skt-color-%s, %s)', sanitize_key( $slug ), sanitize_hex_color( $color['color'] ) );
		}
	}

	return '';
}

/**
 * Color schemes for section blocks. Styled in src/scss/common.scss.
 *
 * @return array<string, string>
 */
function get_scheme_choices(): array {
	return array(
		'light'   => __( 'Light', 'skt-blocks' ),
		'surface' => __( 'Surface', 'skt-blocks' ),
		'dark'    => __( 'Dark', 'skt-blocks' ),
		'primary' => __( 'Primary', 'skt-blocks' ),
	);
}

/**
 * ACF field to pick a section color scheme.
 *
 * @param string $fallback Default scheme.
 * @return array<string, mixed>
 */
function scheme_field( string $fallback = 'light' ): array {
	return array(
		'label'         => __( 'Color scheme', 'skt-blocks' ),
		'name'          => 'scheme',
		'type'          => 'button_group',
		'choices'       => get_scheme_choices(),
		'default_value' => $fallback,
	);
}

/**
 * Classes of a section block for a scheme value.
 *
 * @param mixed $scheme Raw field value.
 * @return string
 */
function section_classes( $scheme ): string {
	$scheme = isset( get_scheme_choices()[ $scheme ] ) ? $scheme : 'light';

	return 'alignfull skt-section skt-section--' . $scheme;
}

/**
 * Tags an editor may choose for a block heading.
 *
 * @return string[]
 */
function get_heading_tags(): array {
	return array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p' );
}

/**
 * ACF field to pick the HTML tag of a heading.
 *
 * Visual size and document outline are independent: the tag only changes
 * semantics, never the look.
 *
 * @param string $name     Field name.
 * @param string $fallback Default tag.
 * @return array<string, mixed>
 */
function heading_tag_field( string $name = 'title_tag', string $fallback = 'h2' ): array {
	return array(
		'label'         => __( 'HTML tag', 'skt-blocks' ),
		'name'          => $name,
		'type'          => 'select',
		'choices'       => array_combine( get_heading_tags(), get_heading_tags() ),
		'default_value' => $fallback,
		'wrapper'       => array( 'width' => '25' ),
	);
}

/**
 * Validate a heading tag field value.
 *
 * @param mixed  $tag      Raw field value.
 * @param string $fallback Fallback tag.
 * @return string
 */
function heading_tag( $tag, string $fallback = 'h2' ): string {
	return in_array( $tag, get_heading_tags(), true ) ? $tag : $fallback;
}

/**
 * Render a button from an ACF link array.
 *
 * @param mixed  $link    ACF link value (url, title, target).
 * @param string $classes Button classes.
 * @return string Empty string when the link has no URL.
 */
function button( $link, string $classes = 'button' ): string {
	if ( ! is_array( $link ) || empty( $link['url'] ) ) {
		return '';
	}

	$is_blank = '_blank' === ( $link['target'] ?? '' );

	return sprintf(
		'<a class="%1$s" href="%2$s"%3$s>%4$s</a>',
		esc_attr( $classes ),
		esc_url( $link['url'] ),
		$is_blank ? ' target="_blank" rel="noopener"' : '',
		esc_html( '' !== ( $link['title'] ?? '' ) ? $link['title'] : __( 'Learn more', 'skt-blocks' ) )
	);
}
