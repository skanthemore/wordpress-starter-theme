<?php
/**
 * Not found.
 *
 * @package SKT_Starter
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<section class="not-found">
	<p class="eyebrow">404</p>
	<h1 class="not-found__title"><?php esc_html_e( 'Page not found', 'skt-starter' ); ?></h1>
	<p class="not-found__text"><?php esc_html_e( 'The page you are looking for does not exist or has moved.', 'skt-starter' ); ?></p>

	<?php get_search_form(); ?>

	<a class="button" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to the home page', 'skt-starter' ); ?></a>
</section>
<?php
get_footer();
