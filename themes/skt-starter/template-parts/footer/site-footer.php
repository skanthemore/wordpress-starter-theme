<?php
/**
 * Footer content.
 *
 * Texts and social links come from Appearance → Theme Options → Footer; the
 * link lists are the "Footer menu" and "Legal menu" locations.
 *
 * @package SKT_Starter
 */

defined( 'ABSPATH' ) || exit;

$skt_text   = skt_get_option_string( 'footer_text', get_bloginfo( 'description' ) );
$skt_social = skt_get_social_links();
?>
<div class="site-footer__inner">
	<div class="site-footer__brand">
		<div class="site-logo">
			<?php echo skt_get_logo_markup(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in skt_get_logo_markup() and get_custom_logo(). ?>
		</div>

		<?php if ( '' !== $skt_text ) : ?>
			<p class="site-footer__text"><?php echo esc_html( $skt_text ); ?></p>
		<?php endif; ?>

		<?php if ( $skt_social ) : ?>
			<ul class="site-footer__social">
				<?php foreach ( $skt_social as $skt_network => $skt_link ) : ?>
					<li>
						<a class="site-footer__social-link" href="<?php echo esc_url( $skt_link['url'] ); ?>" target="_blank" rel="noopener me">
							<?php echo skt_get_icon( $skt_network ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static SVG. ?>
							<span class="screen-reader-text"><?php echo esc_html( $skt_link['label'] ); ?></span>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>

	<?php if ( has_nav_menu( 'footer' ) ) : ?>
		<nav class="site-footer__nav" aria-label="<?php esc_attr_e( 'Footer', 'skt-starter' ); ?>">
			<?php skt_render_simple_menu( 'footer', 'site-footer__menu' ); ?>
		</nav>
	<?php endif; ?>
</div>

<div class="site-footer__bottom">
	<p class="site-footer__copyright"><?php echo esc_html( skt_get_copyright() ); ?></p>

	<?php if ( has_nav_menu( 'legal' ) ) : ?>
		<nav class="site-footer__legal" aria-label="<?php esc_attr_e( 'Legal', 'skt-starter' ); ?>">
			<?php skt_render_simple_menu( 'legal', 'site-footer__legal-menu' ); ?>
		</nav>
	<?php endif; ?>
</div>

<a class="back-to-top" href="#main" data-back-to-top>
	<?php echo skt_get_icon( 'arrow-up' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static SVG. ?>
	<span class="screen-reader-text"><?php esc_html_e( 'Back to top', 'skt-starter' ); ?></span>
</a>
