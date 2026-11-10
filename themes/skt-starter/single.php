<?php
/**
 * Single post.
 *
 * @package SKT_Starter
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	$skt_categories = get_the_category();
	$skt_related    = skt_get_related_posts( get_the_ID() );
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
		<header class="entry__header">
			<?php if ( $skt_categories ) : ?>
				<p class="eyebrow">
					<a href="<?php echo esc_url( get_category_link( $skt_categories[0] ) ); ?>"><?php echo esc_html( $skt_categories[0]->name ); ?></a>
				</p>
			<?php endif; ?>

			<?php the_title( '<h1 class="entry__title">', '</h1>' ); ?>

			<?php if ( has_excerpt() ) : ?>
				<div class="entry__lead"><?php the_excerpt(); ?></div>
			<?php endif; ?>

			<p class="entry__meta">
				<?php echo get_avatar( get_the_author_meta( 'ID' ), 40, '', '', array( 'class' => 'entry__avatar' ) ); ?>
				<span>
					<a href="<?php echo esc_url( get_author_posts_url( (int) get_the_author_meta( 'ID' ) ) ); ?>" rel="author"><?php the_author(); ?></a>
					<span aria-hidden="true">·</span>
					<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
				</span>
			</p>
		</header>

		<?php if ( has_post_thumbnail() ) : ?>
			<figure class="entry__media">
				<?php
				the_post_thumbnail(
					'full',
					array(
						'class'         => 'entry__image',
						'loading'       => 'eager',
						'fetchpriority' => 'high',
					)
				);
				?>
			</figure>
		<?php endif; ?>

		<div class="entry__content entry-content">
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

		<footer class="entry__footer">
			<?php the_tags( '<ul class="entry__tags"><li>', '</li><li>', '</li></ul>' ); ?>

			<?php get_template_part( 'template-parts/blog/author-box' ); ?>

			<?php
			the_post_navigation(
				array(
					'prev_text' => '<span class="post-navigation__label">' . esc_html__( 'Previous', 'skt-starter' ) . '</span> <span class="post-navigation__title">%title</span>',
					'next_text' => '<span class="post-navigation__label">' . esc_html__( 'Next', 'skt-starter' ) . '</span> <span class="post-navigation__title">%title</span>',
					'class'     => 'post-navigation',
				)
			);
			?>
		</footer>
	</article>

	<?php if ( comments_open() || get_comments_number() ) : ?>
		<?php comments_template(); ?>
	<?php endif; ?>

	<?php if ( $skt_related->have_posts() ) : ?>
		<section class="related" aria-labelledby="related-title">
			<h2 id="related-title" class="related__title"><?php esc_html_e( 'Keep reading', 'skt-starter' ); ?></h2>

			<div class="post-grid" data-reveal-group>
				<?php
				while ( $skt_related->have_posts() ) :
					$skt_related->the_post();
					get_template_part( 'template-parts/blog/card', null, array( 'heading' => 'h3' ) );
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		</section>
	<?php endif; ?>
	<?php
endwhile;

get_footer();
