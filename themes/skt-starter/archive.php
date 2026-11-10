<?php
/**
 * Category, tag, author and date archives.
 *
 * @package SKT_Starter
 */

defined( 'ABSPATH' ) || exit;

get_header();

get_template_part(
	'template-parts/blog/listing',
	null,
	array(
		'title'       => wp_strip_all_tags( get_the_archive_title() ),
		'description' => get_the_archive_description(),
	)
);

get_footer();
