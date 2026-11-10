<?php
/**
 * Search results.
 *
 * @package SKT_Starter
 */

defined( 'ABSPATH' ) || exit;

get_header();

get_template_part(
	'template-parts/blog/listing',
	null,
	array(
		'eyebrow' => __( 'Search', 'skt-starter' ),
		/* translators: %s: search query. */
		'title'   => sprintf( __( 'Results for “%s”', 'skt-starter' ), get_search_query( false ) ),
		'search'  => true,
	)
);

get_footer();
