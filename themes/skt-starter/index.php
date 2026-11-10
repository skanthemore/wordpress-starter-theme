<?php
/**
 * Fallback template.
 *
 * @package SKT_Starter
 */

defined( 'ABSPATH' ) || exit;

get_header();

get_template_part( 'template-parts/blog/listing', null, array( 'title' => get_bloginfo( 'name' ) ) );

get_footer();
