<?php
/**
 * SVG uploads and SVG logos.
 *
 * WordPress does not sanitize SVG files, and an SVG can carry scripts. Uploads
 * are therefore limited to users who can already publish unfiltered HTML. Use
 * a sanitizing plugin before opening SVG uploads to any other role.
 *
 * @package SKT_Starter
 */

defined( 'ABSPATH' ) || exit;

/**
 * Allow SVG uploads for trusted users.
 *
 * @param array<string, string> $mimes Allowed mime types.
 * @return array<string, string>
 */
function skt_allow_svg_uploads( array $mimes ): array {
	if ( current_user_can( 'unfiltered_html' ) ) {
		$mimes['svg'] = 'image/svg+xml';
	}

	return $mimes;
}
add_filter( 'upload_mimes', 'skt_allow_svg_uploads' );

/**
 * Keep the SVG mime type when fileinfo reports it differently.
 *
 * Only applies when the extension is allowed for the current user.
 *
 * @param array<string, mixed> $data      File data.
 * @param string               $file      Full path to the file.
 * @param string               $filename  File name.
 * @param string[]|null        $mimes     Allowed mime types.
 * @return array<string, mixed>
 */
function skt_fix_svg_filetype( array $data, string $file, string $filename, ?array $mimes ): array {
	$is_svg = 'svg' === strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) );

	if ( ! $is_svg || ! isset( ( $mimes ?? get_allowed_mime_types() )['svg'] ) ) {
		return $data;
	}

	return array(
		'ext'             => 'svg',
		'type'            => 'image/svg+xml',
		'proper_filename' => $data['proper_filename'] ?? false,
	);
}
add_filter( 'wp_check_filetype_and_ext', 'skt_fix_svg_filetype', 10, 4 );

/**
 * Replace the cropped logo control with a plain media control.
 *
 * SVGs cannot be cropped, so the default control rejects them.
 *
 * @param WP_Customize_Manager $wp_customize Customizer instance.
 * @return void
 */
function skt_customize_logo_control( WP_Customize_Manager $wp_customize ): void {
	if ( ! current_theme_supports( 'custom-logo' ) ) {
		return;
	}

	$wp_customize->remove_control( 'custom_logo' );
	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'custom_logo',
			array(
				'label'     => __( 'Logo', 'skt-starter' ),
				'section'   => 'title_tagline',
				'priority'  => 8,
				'mime_type' => 'image',
			)
		)
	);
}
add_action( 'customize_register', 'skt_customize_logo_control', 20 );
