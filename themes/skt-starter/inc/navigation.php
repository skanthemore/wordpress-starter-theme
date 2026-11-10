<?php
/**
 * Navigation: logo, menu panel walker, language links and simple menus.
 *
 * @package SKT_Starter
 */

defined( 'ABSPATH' ) || exit;

/**
 * Logo markup, or the site title when no logo is set.
 *
 * @return string
 */
function skt_get_logo_markup(): string {
	if ( has_custom_logo() ) {
		return get_custom_logo();
	}

	return sprintf(
		'<a class="site-logo__text" href="%1$s" rel="home">%2$s</a>',
		esc_url( home_url( '/' ) ),
		esc_html( get_bloginfo( 'name' ) )
	);
}

/**
 * Render a one-level menu location, or nothing when it has no menu.
 *
 * @param string $location   Menu location.
 * @param string $menu_class Class of the list.
 * @return void
 */
function skt_render_simple_menu( string $location, string $menu_class ): void {
	if ( ! has_nav_menu( $location ) ) {
		return;
	}

	wp_nav_menu(
		array(
			'theme_location' => $location,
			'menu_class'     => $menu_class,
			'container'      => false,
			'depth'          => 1,
			'fallback_cb'    => false,
		)
	);
}

/**
 * Language links from WPML or Polylang.
 *
 * @return array<int, array{code: string, name: string, url: string, active: bool}>
 */
function skt_get_language_links(): array {
	$links = array();

	$wpml = apply_filters( 'wpml_active_languages', null, array( 'skip_missing' => 0 ) );

	if ( is_array( $wpml ) ) {
		foreach ( $wpml as $language ) {
			$links[] = array(
				'code'   => (string) $language['code'],
				'name'   => (string) $language['native_name'],
				'url'    => (string) $language['url'],
				'active' => ! empty( $language['active'] ),
			);
		}
	} elseif ( function_exists( 'pll_the_languages' ) ) {
		foreach ( (array) pll_the_languages( array( 'raw' => 1 ) ) as $language ) {
			$links[] = array(
				'code'   => (string) $language['slug'],
				'name'   => (string) $language['name'],
				'url'    => (string) $language['url'],
				'active' => ! empty( $language['current_lang'] ),
			);
		}
	}

	/**
	 * Filters the language switcher links.
	 *
	 * @param array<int, array{code: string, name: string, url: string, active: bool}> $links Language links.
	 */
	return (array) apply_filters( 'skt_language_links', $links );
}
