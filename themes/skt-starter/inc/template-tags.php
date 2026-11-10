<?php
/**
 * Small helpers used by the templates.
 *
 * @package SKT_Starter
 */

defined( 'ABSPATH' ) || exit;

/**
 * URL of the posts page, or the home page when there is none.
 *
 * @return string
 */
function skt_get_blog_url(): string {
	$page_id = (int) get_option( 'page_for_posts' );

	return $page_id ? (string) get_permalink( $page_id ) : home_url( '/' );
}

/**
 * Attributes for an ACF link array: href, target and a safe rel.
 *
 * @param array<string, string> $link ACF link (url, title, target).
 * @return string Escaped attributes, prefixed with a space.
 */
function skt_get_link_attributes( array $link ): string {
	$attributes = ' href="' . esc_url( $link['url'] ?? '' ) . '"';

	if ( '_blank' === ( $link['target'] ?? '' ) ) {
		$attributes .= ' target="_blank" rel="noopener"';
	}

	return $attributes;
}

/**
 * Whether a page renders its own title.
 *
 * Pages that open with a Hero block already have a main heading.
 *
 * @return bool
 */
function skt_show_page_title(): bool {
	/**
	 * Filters whether the page template prints the page title.
	 *
	 * @param bool $show Whether to print the title.
	 */
	return (bool) apply_filters( 'skt_show_page_title', ! has_block( 'skt/hero' ) );
}

/**
 * Print the archive pagination.
 *
 * @return void
 */
function skt_the_pagination(): void {
	the_posts_pagination(
		array(
			'mid_size'           => 1,
			'prev_text'          => __( 'Previous', 'skt-starter' ),
			'next_text'          => __( 'Next', 'skt-starter' ),
			'screen_reader_text' => __( 'Posts navigation', 'skt-starter' ),
			'class'              => 'pagination',
		)
	);
}

/**
 * Posts that share a category with the given post.
 *
 * @param int $post_id Post ID.
 * @param int $count   Number of posts.
 * @return WP_Query
 */
function skt_get_related_posts( int $post_id, int $count = 3 ): WP_Query {
	$args = array(
		'post_type'           => 'post',
		'posts_per_page'      => $count,
		'post__not_in'        => array( $post_id ),
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	);

	$categories = wp_get_post_categories( $post_id );

	if ( $categories ) {
		$args['category__in'] = $categories;
	}

	return new WP_Query( $args );
}
