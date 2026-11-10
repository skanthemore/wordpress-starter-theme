<?php
/**
 * Post listing shared by the blog index, archives and search results.
 *
 * @package SKT_Starter
 *
 * @var array{title?: string, description?: string, eyebrow?: string, search?: bool} $args
 */

defined( 'ABSPATH' ) || exit;

$skt_args = wp_parse_args(
	$args,
	array(
		'title'       => '',
		'description' => '',
		'eyebrow'     => '',
		'search'      => false,
	)
);

$skt_categories = is_home() || is_category() ? get_categories( array( 'hide_empty' => true ) ) : array();
$skt_current    = is_category() ? get_queried_object_id() : 0;
?>
<div class="archive">
	<header class="archive__header">
		<?php if ( '' !== $skt_args['eyebrow'] ) : ?>
			<p class="eyebrow"><?php echo esc_html( $skt_args['eyebrow'] ); ?></p>
		<?php endif; ?>

		<h1 class="archive__title"><?php echo esc_html( $skt_args['title'] ); ?></h1>

		<?php if ( '' !== $skt_args['description'] ) : ?>
			<div class="archive__description"><?php echo wp_kses_post( $skt_args['description'] ); ?></div>
		<?php endif; ?>

		<?php if ( $skt_args['search'] ) : ?>
			<?php get_search_form(); ?>
		<?php endif; ?>

		<?php if ( count( $skt_categories ) > 1 ) : ?>
			<nav class="archive__filter" aria-label="<?php esc_attr_e( 'Categories', 'skt-starter' ); ?>">
				<a class="archive__filter-link" href="<?php echo esc_url( skt_get_blog_url() ); ?>"<?php echo is_home() ? ' aria-current="page"' : ''; ?>>
					<?php esc_html_e( 'All', 'skt-starter' ); ?>
				</a>
				<?php foreach ( $skt_categories as $skt_category ) : ?>
					<a class="archive__filter-link" href="<?php echo esc_url( get_category_link( $skt_category ) ); ?>"<?php echo $skt_current === $skt_category->term_id ? ' aria-current="page"' : ''; ?>>
						<?php echo esc_html( $skt_category->name ); ?>
					</a>
				<?php endforeach; ?>
			</nav>
		<?php endif; ?>
	</header>

	<?php if ( have_posts() ) : ?>
		<div class="post-grid" data-reveal-group>
			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/blog/card' );
			endwhile;
			?>
		</div>

		<?php skt_the_pagination(); ?>
	<?php else : ?>
		<p class="archive__empty">
			<?php
			echo esc_html(
				$skt_args['search']
					? __( 'Nothing matched your search. Try different keywords.', 'skt-starter' )
					: __( 'There are no posts here yet.', 'skt-starter' )
			);
			?>
		</p>
	<?php endif; ?>
</div>
