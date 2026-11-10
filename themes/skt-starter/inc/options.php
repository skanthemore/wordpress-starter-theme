<?php
/**
 * Theme Options page.
 *
 * Site-wide settings are edited in an ACF options page when ACF PRO is
 * active. Values are read straight from wp_options (ACF stores them as
 * `options_{name}`), so the theme keeps working, with defaults, when ACF is
 * not installed and can read them before ACF has initialised.
 *
 * @package SKT_Starter
 */

defined( 'ABSPATH' ) || exit;

/**
 * Read a raw Theme Options value.
 *
 * @param string $name     Field name.
 * @param mixed  $fallback Value returned when the option is empty.
 * @return mixed
 */
function skt_get_option( string $name, $fallback = '' ) {
	$value = get_option( 'options_' . $name, null );

	if ( null === $value || '' === $value || array() === $value ) {
		return $fallback;
	}

	return $value;
}

/**
 * Read a Theme Options value as a trimmed string.
 *
 * @param string $name     Field name.
 * @param string $fallback Value returned when the option is empty.
 * @return string
 */
function skt_get_option_string( string $name, string $fallback = '' ): string {
	$value = skt_get_option( $name, $fallback );

	return is_scalar( $value ) ? trim( (string) $value ) : $fallback;
}

/**
 * Social networks offered in the footer, keyed by icon name.
 *
 * @return array<string, string>
 */
function skt_get_social_networks(): array {
	/**
	 * Filters the social networks available in Theme Options.
	 *
	 * Every key must match an icon registered in skt_get_icons().
	 *
	 * @param array<string, string> $networks Network labels keyed by icon name.
	 */
	return (array) apply_filters(
		'skt_social_networks',
		array(
			'instagram' => 'Instagram',
			'facebook'  => 'Facebook',
			'tiktok'    => 'TikTok',
		)
	);
}

/**
 * Social links that have a URL, ready for output.
 *
 * @return array<string, array{label: string, url: string}>
 */
function skt_get_social_links(): array {
	$links = array();

	foreach ( skt_get_social_networks() as $network => $label ) {
		$url = esc_url_raw( skt_get_option_string( 'social_' . $network ) );

		if ( '' !== $url ) {
			$links[ $network ] = array(
				'label' => $label,
				'url'   => $url,
			);
		}
	}

	return $links;
}

/**
 * Copyright line with `{year}` and `{site}` placeholders resolved.
 *
 * @return string
 */
function skt_get_copyright(): string {
	$template = skt_get_option_string( 'footer_copyright', '© {year} {site}' );

	return strtr(
		$template,
		array(
			'{year}' => wp_date( 'Y' ),
			'{site}' => get_bloginfo( 'name' ),
		)
	);
}

/**
 * Register the Theme Options page and its fields.
 *
 * @return void
 */
function skt_register_theme_options(): void {
	if ( ! function_exists( 'acf_add_options_page' ) || ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_options_page(
		array(
			'page_title'  => __( 'Theme Options', 'skt-starter' ),
			'menu_title'  => __( 'Theme Options', 'skt-starter' ),
			'menu_slug'   => 'skt-theme-options',
			'parent_slug' => 'themes.php',
			'capability'  => 'edit_theme_options',
			'redirect'    => false,
		)
	);

	$fields = array_merge(
		skt_get_header_option_fields(),
		skt_get_footer_option_fields(),
		skt_get_color_option_fields()
	);

	acf_add_local_field_group(
		array(
			'key'      => 'group_skt_theme_options',
			'title'    => __( 'Theme Options', 'skt-starter' ),
			'fields'   => $fields,
			'location' => array(
				array(
					array(
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => 'skt-theme-options',
					),
				),
			),
		)
	);
}
add_action( 'acf/init', 'skt_register_theme_options' );

/**
 * Fields of the Header tab.
 *
 * @return array<int, array<string, mixed>>
 */
function skt_get_header_option_fields(): array {
	return array(
		array(
			'key'   => 'field_skt_tab_header',
			'label' => __( 'Header', 'skt-starter' ),
			'type'  => 'tab',
		),
		array(
			'key'           => 'field_skt_header_color',
			'label'         => __( 'Default header color', 'skt-starter' ),
			'name'          => 'header_color',
			'type'          => 'button_group',
			'choices'       => skt_get_header_color_choices(),
			'default_value' => 'dark',
			'instructions'  => __( 'Use light on pages that open with a dark image. Each entry can override it.', 'skt-starter' ),
			'wrapper'       => array( 'width' => '50' ),
		),
		array(
			'key'           => 'field_skt_header_position',
			'label'         => __( 'Default header position', 'skt-starter' ),
			'name'          => 'header_position',
			'type'          => 'button_group',
			'choices'       => skt_get_header_position_choices(),
			'default_value' => 'static',
			'instructions'  => __( 'Overlay places the header on top of the first block.', 'skt-starter' ),
			'wrapper'       => array( 'width' => '50' ),
		),
		array(
			'key'           => 'field_skt_header_cta',
			'label'         => __( 'Header button', 'skt-starter' ),
			'name'          => 'header_cta',
			'type'          => 'link',
			'return_format' => 'array',
			'instructions'  => __( 'Optional call to action shown next to the menu.', 'skt-starter' ),
		),
	);
}

/**
 * Fields of the Footer tab.
 *
 * @return array<int, array<string, mixed>>
 */
function skt_get_footer_option_fields(): array {
	$fields = array(
		array(
			'key'   => 'field_skt_tab_footer',
			'label' => __( 'Footer', 'skt-starter' ),
			'type'  => 'tab',
		),
		array(
			'key'          => 'field_skt_footer_text',
			'label'        => __( 'Footer text', 'skt-starter' ),
			'name'         => 'footer_text',
			'type'         => 'textarea',
			'rows'         => 3,
			'new_lines'    => '',
			'instructions' => __( 'Short description shown under the logo. Defaults to the site tagline.', 'skt-starter' ),
		),
		array(
			'key'          => 'field_skt_footer_copyright',
			'label'        => __( 'Copyright', 'skt-starter' ),
			'name'         => 'footer_copyright',
			'type'         => 'text',
			'placeholder'  => '© {year} {site}',
			'instructions' => __( 'Use {year} for the current year and {site} for the site title.', 'skt-starter' ),
		),
	);

	foreach ( skt_get_social_networks() as $network => $label ) {
		$fields[] = array(
			'key'     => 'field_skt_social_' . $network,
			'label'   => $label,
			'name'    => 'social_' . $network,
			'type'    => 'url',
			'wrapper' => array( 'width' => '25' ),
		);
	}

	return $fields;
}

/**
 * Fields of the Colors tab, one per palette color.
 *
 * Defaults are read from theme-data/ directly: the resolved palette already
 * contains the saved overrides.
 *
 * @return array<int, array<string, mixed>>
 */
function skt_get_color_option_fields(): array {
	$fields = array(
		array(
			'key'   => 'field_skt_tab_colors',
			'label' => __( 'Colors', 'skt-starter' ),
			'type'  => 'tab',
		),
	);

	foreach ( require SKT_THEME_DIR . '/theme-data/colors.php' as $color ) {
		$name     = skt_color_option_name( $color['slug'] );
		$fields[] = array(
			'key'           => 'field_skt_' . $name,
			'label'         => $color['name'],
			'name'          => $name,
			'type'          => 'color_picker',
			'default_value' => $color['color'],
			'return_format' => 'string',
			'wrapper'       => array( 'width' => '25' ),
		);
	}

	return $fields;
}
