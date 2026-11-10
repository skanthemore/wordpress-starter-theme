<?php
/**
 * Inline SVG icons and language flags.
 *
 * Icons are inlined rather than loaded from a sprite or a font: there are only
 * a few, they inherit `currentColor` and cost no extra request.
 *
 * @package SKT_Starter
 */

defined( 'ABSPATH' ) || exit;

/**
 * Icon registry: name => [ viewBox, inner markup ].
 *
 * @return array<string, array{0: string, 1: string}>
 */
function skt_get_icons(): array {
	$icons = array(
		'chevron-down' => array( '0 0 16 16', '<path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708"/>' ),
		'arrow-up'     => array( '0 0 24 24', '<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>' ),
		'instagram'    => array( '0 0 24 24', '<path d="M7.8 2h8.4A5.8 5.8 0 0 1 22 7.8v8.4a5.8 5.8 0 0 1-5.8 5.8H7.8A5.8 5.8 0 0 1 2 16.2V7.8A5.8 5.8 0 0 1 7.8 2Zm0 2A3.8 3.8 0 0 0 4 7.8v8.4A3.8 3.8 0 0 0 7.8 20h8.4a3.8 3.8 0 0 0 3.8-3.8V7.8A3.8 3.8 0 0 0 16.2 4H7.8Zm4.2 3.2A4.8 4.8 0 1 1 12 16.8a4.8 4.8 0 0 1 0-9.6Zm0 2A2.8 2.8 0 1 0 12 14.8a2.8 2.8 0 0 0 0-5.6Zm5.05-2.45a1.15 1.15 0 1 1 0 2.3 1.15 1.15 0 0 1 0-2.3Z"/>' ),
		'facebook'     => array( '0 0 24 24', '<path d="M14 8.2V6.6c0-.78.18-1.2 1.36-1.2H17V2.2A22.5 22.5 0 0 0 14.3 2C11.6 2 9.8 3.65 9.8 6.7v1.5H7v3.6h2.8V22H14V11.8h2.8l.42-3.6H14Z"/>' ),
		'tiktok'       => array( '0 0 24 24', '<path d="M16.2 2c.33 2.76 1.86 4.4 4.8 4.58v3.1a8.1 8.1 0 0 1-4.72-1.48v6.62c0 4.95-5.4 8.2-9.9 5.68-4.35-2.45-3.68-8.9 1.14-10.4a8 8 0 0 1 2.92-.28v3.26a3.7 3.7 0 0 0-1.78.06c-2.32.72-2.16 4.03.02 4.72 1.42.45 3.44-.4 3.44-2.7V2h4.08Z"/>' ),
	);

	/**
	 * Filters the icon registry.
	 *
	 * @param array<string, array{0: string, 1: string}> $icons Icons keyed by name.
	 */
	return (array) apply_filters( 'skt_icons', $icons );
}

/**
 * Render an icon.
 *
 * The markup comes from the registry above, never from user input.
 *
 * @param string $name  Icon name.
 * @param string $extra_class Extra CSS classes.
 * @return string Empty string for unknown icons.
 */
function skt_get_icon( string $name, string $extra_class = '' ): string {
	$icons = skt_get_icons();

	if ( ! isset( $icons[ $name ] ) ) {
		return '';
	}

	list( $view_box, $body ) = $icons[ $name ];

	return sprintf(
		'<svg class="%1$s" viewBox="%2$s" width="16" height="16" fill="currentColor" aria-hidden="true" focusable="false">%3$s</svg>',
		esc_attr( trim( 'icon icon-' . sanitize_html_class( $name ) . ' ' . $extra_class ) ),
		esc_attr( $view_box ),
		$body
	);
}

/**
 * Render a small 3:2 flag for a language code.
 *
 * Inline SVG instead of emoji flags, which Windows does not render.
 *
 * @param string $language Language code (es, en, de...).
 * @param string $extra_class Extra CSS classes.
 * @return string Empty string when no flag is defined.
 */
function skt_get_flag( string $language, string $extra_class = '' ): string {
	$flags = array(
		'es' => '<path fill="#AA151B" d="M0 0h9v6H0z"/><path fill="#F1BF00" d="M0 1.5h9v3H0z"/>',
		'en' => '<path fill="#012169" d="M0 0h9v6H0z"/><path stroke="#FFF" stroke-width="1.2" d="m0 0 9 6m0-6L0 6"/><path stroke="#C8102E" stroke-width=".45" d="m0 0 9 6m0-6L0 6"/><path stroke="#FFF" stroke-width="1.8" d="M4.5 0v6M0 3h9"/><path stroke="#C8102E" stroke-width="1.05" d="M4.5 0v6M0 3h9"/>',
		'de' => '<path fill="#000" d="M0 0h9v2H0z"/><path fill="#D00" d="M0 2h9v2H0z"/><path fill="#FFCE00" d="M0 4h9v2H0z"/>',
		'fr' => '<path fill="#002395" d="M0 0h3v6H0z"/><path fill="#FFF" d="M3 0h3v6H3z"/><path fill="#ED2939" d="M6 0h3v6H6z"/>',
		'it' => '<path fill="#009246" d="M0 0h3v6H0z"/><path fill="#FFF" d="M3 0h3v6H3z"/><path fill="#CE2B37" d="M6 0h3v6H6z"/>',
		'nl' => '<path fill="#AE1C28" d="M0 0h9v2H0z"/><path fill="#FFF" d="M0 2h9v2H0z"/><path fill="#21468B" d="M0 4h9v2H0z"/>',
		'ja' => '<path fill="#FFF" d="M0 0h9v6H0z"/><circle cx="4.5" cy="3" r="1.8" fill="#BC002D"/>',
	);

	$language = strtolower( substr( $language, 0, 2 ) );

	if ( ! isset( $flags[ $language ] ) ) {
		return '';
	}

	return sprintf(
		'<svg class="%1$s" viewBox="0 0 9 6" width="18" height="12" aria-hidden="true" focusable="false">%2$s</svg>',
		esc_attr( trim( 'flag flag-' . $language . ' ' . $extra_class ) ),
		$flags[ $language ]
	);
}
