<?php
/**
 * Page.
 *
 * Pages are composed with blocks. The title is printed unless the page opens
 * with a Hero block, see skt_show_page_title().
 *
 * @package SKT_Starter
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-entry' ); ?>>
		<?php if ( skt_show_page_title() ) : ?>
			<header class="page-entry__header">
				<?php the_title( '<h1 class="page-entry__title">', '</h1>' ); ?>
			</header>
		<?php endif; ?>

		<div class="entry-content">
			<?php
			the_content();

			wp_link_pages(
				array(
					'before' => '<nav class="page-links" aria-label="' . esc_attr__( 'Pages', 'skt-starter' ) . '">',
					'after'  => '</nav>',
				)
			);
			?>
		</div>
	</article>

	<?php if ( comments_open() || get_comments_number() ) : ?>
		<?php comments_template(); ?>
	<?php endif; ?>
	<?php
endwhile;

get_footer();
