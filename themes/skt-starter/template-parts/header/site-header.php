<?php
/**
 * Header bar and off-canvas menu panel.
 *
 * @package SKT_Starter
 */

defined( 'ABSPATH' ) || exit;

$skt_cta       = skt_get_option( 'header_cta', array() );
$skt_has_cta   = is_array( $skt_cta ) && ! empty( $skt_cta['url'] ) && ! empty( $skt_cta['title'] );
$skt_has_menu  = has_nav_menu( 'primary' );
$skt_languages = skt_get_language_links();
$skt_current   = current( wp_list_filter( $skt_languages, array( 'active' => true ) ) );
?>
<div class="site-header__inner">
	<div class="site-logo">
		<?php echo skt_get_logo_markup(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in skt_get_logo_markup() and get_custom_logo(). ?>
	</div>

	<div class="site-header__actions">
		<?php if ( $skt_current ) : ?>
			<div class="language-switcher" data-language-switcher>
				<button class="language-switcher__toggle" type="button" aria-expanded="false" aria-controls="language-switcher-list">
					<?php echo skt_get_flag( $skt_current['code'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static SVG. ?>
					<span class="screen-reader-text"><?php esc_html_e( 'Language:', 'skt-starter' ); ?></span>
					<span><?php echo esc_html( strtoupper( $skt_current['code'] ) ); ?></span>
					<?php echo skt_get_icon( 'chevron-down', 'language-switcher__chevron' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static SVG. ?>
				</button>

				<ul id="language-switcher-list" class="language-switcher__list" hidden>
					<?php foreach ( $skt_languages as $skt_language ) : ?>
						<li>
							<a
								class="language-switcher__link"
								href="<?php echo esc_url( $skt_language['url'] ); ?>"
								hreflang="<?php echo esc_attr( $skt_language['code'] ); ?>"
								lang="<?php echo esc_attr( $skt_language['code'] ); ?>"
								<?php echo $skt_language['active'] ? 'aria-current="true"' : ''; ?>
							>
								<?php echo skt_get_flag( $skt_language['code'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static SVG. ?>
								<span><?php echo esc_html( $skt_language['name'] ); ?></span>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>

		<?php if ( $skt_has_cta ) : ?>
			<a class="button button--small site-header__cta"<?php echo skt_get_link_attributes( $skt_cta ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in skt_get_link_attributes(). ?>>
				<?php echo esc_html( $skt_cta['title'] ); ?>
			</a>
		<?php endif; ?>

		<?php if ( $skt_has_menu ) : ?>
			<button
				class="site-header__toggle"
				type="button"
				aria-expanded="false"
				aria-controls="menu-panel"
				data-menu-toggle
				data-label-open="<?php esc_attr_e( 'Open menu', 'skt-starter' ); ?>"
				data-label-close="<?php esc_attr_e( 'Close menu', 'skt-starter' ); ?>"
			>
				<span class="screen-reader-text" data-menu-toggle-label><?php esc_html_e( 'Open menu', 'skt-starter' ); ?></span>
				<span class="site-header__toggle-bars" aria-hidden="true"></span>
			</button>
		<?php endif; ?>
	</div>
</div>

<?php if ( $skt_has_menu ) : ?>
	<nav id="menu-panel" class="menu-panel" aria-label="<?php esc_attr_e( 'Primary', 'skt-starter' ); ?>" data-menu-panel inert>
		<div class="menu-panel__inner">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'menu_class'     => 'menu-panel__menu',
					'container'      => false,
					'depth'          => 2,
					'fallback_cb'    => false,
					'walker'         => new SKT_Menu_Panel_Walker(),
				)
			);
			?>
		</div>
	</nav>
<?php endif; ?>
