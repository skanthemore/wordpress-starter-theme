<?php
/**
 * Posts slider block.
 *
 * Without JavaScript the track is a native horizontal scroller with scroll
 * snapping; view.js upgrades it to Swiper.
 *
 * @package SKT\Blocks
 *
 * @var bool $is_preview Whether the block renders in the editor.
 */

use function SKT\Blocks\heading_tag;
use function SKT\Blocks\section_classes;

defined( 'ABSPATH' ) || exit;

$skt_query = new WP_Query(
	array(
		'post_type'           => 'post',
		'posts_per_page'      => min( 12, max( 3, (int) get_field( 'count' ) ) ),
		'cat'                 => (int) get_field( 'category' ),
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	)
);

if ( ! $skt_query->have_posts() && ! $is_preview ) {
	return;
}

$skt_title = trim( (string) get_field( 'title' ) );
$skt_tag   = heading_tag( get_field( 'title_tag' ) );
?>
<section <?php echo get_block_wrapper_attributes( array( 'class' => section_classes( get_field( 'scheme' ) ) . ' skt-posts-slider' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped by core. ?>>
	<div class="skt-section__inner">
		<?php if ( '' !== $skt_title ) : ?>
			<<?php echo tag_escape( $skt_tag ); ?> class="skt-posts-slider__title" data-reveal><?php echo esc_html( $skt_title ); ?></<?php echo tag_escape( $skt_tag ); ?>>
		<?php endif; ?>

		<?php if ( $skt_query->have_posts() ) : ?>
			<div class="skt-posts-slider__track swiper" data-skt-posts-slider>
				<div class="swiper-wrapper">
					<?php
					while ( $skt_query->have_posts() ) :
						$skt_query->the_post();
						?>
						<article class="swiper-slide skt-posts-slider__slide">
							<div class="skt-posts-slider__media">
								<?php the_post_thumbnail( 'medium_large', array( 'class' => 'skt-posts-slider__image' ) ); ?>
							</div>
							<time class="skt-posts-slider__date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
							<h3 class="skt-posts-slider__post-title">
								<a class="skt-posts-slider__link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h3>
						</article>
						<?php
					endwhile;
					wp_reset_postdata();
					?>
				</div>
				<div class="swiper-pagination"></div>
			</div>
		<?php else : ?>
			<p><?php esc_html_e( 'Publish a few posts to fill this slider.', 'skt-blocks' ); ?></p>
		<?php endif; ?>
	</div>
</section>
