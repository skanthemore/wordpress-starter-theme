<?php
/**
 * Post card for the current post in the loop.
 *
 * @package SKT_Starter
 *
 * @var array{heading?: string} $args
 */

defined( 'ABSPATH' ) || exit;

$skt_heading    = 'h3' === ( $args['heading'] ?? '' ) ? 'h3' : 'h2';
$skt_categories = get_the_category();
?>
<article <?php post_class( 'post-card' ); ?> data-reveal>
	<div class="post-card__media">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'large', array( 'class' => 'post-card__image' ) ); ?>
		<?php endif; ?>
	</div>

	<div class="post-card__body">
		<p class="post-card__meta">
			<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
			<?php if ( $skt_categories ) : ?>
				<span aria-hidden="true">·</span>
				<span><?php echo esc_html( $skt_categories[0]->name ); ?></span>
			<?php endif; ?>
		</p>

		<<?php echo tag_escape( $skt_heading ); ?> class="post-card__title">
			<a class="post-card__link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</<?php echo tag_escape( $skt_heading ); ?>>
	</div>
</article>
