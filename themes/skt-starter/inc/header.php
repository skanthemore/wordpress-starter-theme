<?php
/**
 * Header appearance: color scheme and position.
 *
 * - Color: `dark` (dark text, the default) or `light` (light text for pages
 *   that open with a dark image).
 * - Position: `static` (in the document flow) or `overlay` (on top of the
 *   first block).
 *
 * Each value resolves in this order:
 * 1. the `skt_header_color` / `skt_header_position` filters;
 * 2. the per-entry setting in the editor sidebar;
 * 3. the default in Appearance → Theme Options → Header.
 *
 * @package SKT_Starter
 */

defined( 'ABSPATH' ) || exit;

/**
 * Header color choices.
 *
 * @return array<string, string>
 */
function skt_get_header_color_choices(): array {
	return array(
		'dark'  => __( 'Dark', 'skt-starter' ),
		'light' => __( 'Light', 'skt-starter' ),
	);
}

/**
 * Header position choices.
 *
 * @return array<string, string>
 */
function skt_get_header_position_choices(): array {
	return array(
		'static'  => __( 'Static', 'skt-starter' ),
		'overlay' => __( 'Overlay', 'skt-starter' ),
	);
}

/**
 * Resolve one header setting for the current request.
 *
 * @param string                $setting  Setting name, `color` or `position`.
 * @param array<string, string> $choices  Allowed values.
 * @param string                $fallback Value used when nothing else is set.
 * @return string
 */
function skt_resolve_header_setting( string $setting, array $choices, string $fallback ): string {
	$value = skt_get_option_string( 'header_' . $setting );

	if ( is_singular() ) {
		$entry_value = (string) get_post_meta( get_queried_object_id(), 'header_' . $setting, true );

		if ( isset( $choices[ $entry_value ] ) ) {
			$value = $entry_value;
		}
	}

	/**
	 * Filters a header setting: `skt_header_color` or `skt_header_position`.
	 *
	 * @param string $value Resolved value.
	 */
	$value = (string) apply_filters( 'skt_header_' . $setting, $value );

	return isset( $choices[ $value ] ) ? $value : $fallback;
}

/**
 * Header color for the current request.
 *
 * @return string `dark` or `light`.
 */
function skt_get_header_color(): string {
	return skt_resolve_header_setting( 'color', skt_get_header_color_choices(), 'dark' );
}

/**
 * Header position for the current request.
 *
 * @return string `static` or `overlay`.
 */
function skt_get_header_position(): string {
	return skt_resolve_header_setting( 'position', skt_get_header_position_choices(), 'static' );
}

/**
 * Expose the resolved header settings as body classes.
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function skt_header_body_classes( array $classes ): array {
	$classes[] = 'has-header-' . skt_get_header_color();
	$classes[] = 'has-header-' . skt_get_header_position();

	return $classes;
}
add_filter( 'body_class', 'skt_header_body_classes' );

/**
 * Register the per-entry header settings in the editor sidebar.
 *
 * @return void
 */
function skt_register_header_entry_fields(): void {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	$inherit = array( '' => __( 'Default', 'skt-starter' ) );

	acf_add_local_field_group(
		array(
			'key'      => 'group_skt_header_entry',
			'title'    => __( 'Header', 'skt-starter' ),
			'position' => 'side',
			'fields'   => array(
				array(
					'key'           => 'field_skt_entry_header_color',
					'label'         => __( 'Color', 'skt-starter' ),
					'name'          => 'header_color',
					'type'          => 'select',
					'choices'       => $inherit + skt_get_header_color_choices(),
					'default_value' => '',
				),
				array(
					'key'           => 'field_skt_entry_header_position',
					'label'         => __( 'Position', 'skt-starter' ),
					'name'          => 'header_position',
					'type'          => 'select',
					'choices'       => $inherit + skt_get_header_position_choices(),
					'default_value' => '',
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '!=',
						'value'    => 'attachment',
					),
				),
			),
		)
	);
}
add_action( 'acf/init', 'skt_register_header_entry_fields' );
