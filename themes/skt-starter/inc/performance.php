<?php
/**
 * Performance settings (Customizer → Performance) and core clean-up.
 *
 * @package SKT_Starter
 */

defined( 'ABSPATH' ) || exit;

/**
 * Whether the compiled stylesheet is printed inline instead of linked.
 *
 * Inlining removes a render-blocking request, which pays off while the bundle
 * stays small.
 *
 * @return bool
 */
function skt_is_css_inlined(): bool {
	return (bool) get_theme_mod( 'skt_inline_css', false );
}

/**
 * Register the Performance section in the Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Customizer instance.
 * @return void
 */
function skt_customize_performance( WP_Customize_Manager $wp_customize ): void {
	$wp_customize->add_section(
		'skt_performance',
		array(
			'title'    => __( 'Performance', 'skt-starter' ),
			'priority' => 200,
		)
	);

	$settings = array(
		'skt_inline_css'             => array(
			'default'     => false,
			'label'       => __( 'Inline the theme stylesheet', 'skt-starter' ),
			'description' => __( 'Prints the compiled CSS in the page head instead of linking it.', 'skt-starter' ),
		),
		'skt_disable_jquery_migrate' => array(
			'default'     => true,
			'label'       => __( 'Disable jQuery Migrate', 'skt-starter' ),
			'description' => __( 'Only keep it if a plugin still relies on deprecated jQuery APIs.', 'skt-starter' ),
		),
	);

	foreach ( $settings as $id => $setting ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => $setting['default'],
				'sanitize_callback' => 'wp_validate_boolean',
			)
		);

		$wp_customize->add_control(
			$id,
			array(
				'type'        => 'checkbox',
				'section'     => 'skt_performance',
				'label'       => $setting['label'],
				'description' => $setting['description'],
			)
		);
	}
}
add_action( 'customize_register', 'skt_customize_performance' );

/**
 * Drop jQuery Migrate from the front-end jQuery dependency chain.
 *
 * @param WP_Scripts $scripts Script registry.
 * @return void
 */
function skt_remove_jquery_migrate( WP_Scripts $scripts ): void {
	if ( is_admin() || ! get_theme_mod( 'skt_disable_jquery_migrate', true ) || empty( $scripts->registered['jquery'] ) ) {
		return;
	}

	$jquery       = $scripts->registered['jquery'];
	$jquery->deps = array_values( array_diff( $jquery->deps, array( 'jquery-migrate' ) ) );
}
add_action( 'wp_default_scripts', 'skt_remove_jquery_migrate' );

/**
 * Remove the emoji detection script and styles.
 *
 * Every current browser renders emoji natively.
 *
 * @return void
 */
function skt_disable_emojis(): void {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'emoji_svg_url', '__return_false' );
}
add_action( 'init', 'skt_disable_emojis' );
