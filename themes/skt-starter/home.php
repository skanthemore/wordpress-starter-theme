<?php
/**
 * Blog index.
 *
 * The heading and intro come from the page set as "Posts page" in
 * Settings → Reading: its title and excerpt.
 *
 * @package SKT_Starter
 */

defined( 'ABSPATH' ) || exit;

get_header();

$skt_page_id = (int) get_option( 'page_for_posts' );

get_template_part(
	'template-parts/blog/listing',
	null,
	array(
		'title'       => $skt_page_id ? get_the_title( $skt_page_id ) : __( 'Blog', 'skt-starter' ),
		'description' => $skt_page_id && has_excerpt( $skt_page_id ) ? wpautop( get_the_excerpt( $skt_page_id ) ) : '',
	)
);

get_footer();
