<?php
/**
 * Menu panel walker.
 *
 * @package SKT_Starter
 */

defined( 'ABSPATH' ) || exit;

/**
 * Walker for the off-canvas menu panel.
 *
 * Top-level items with children get a disclosure button that controls their
 * submenu, so nested navigation works with keyboard and screen readers.
 */
class SKT_Menu_Panel_Walker extends Walker_Nav_Menu {

	/**
	 * ID of the submenu opened by the last top-level item.
	 *
	 * @var string
	 */
	private string $submenu_id = '';

	/**
	 * Start a submenu list.
	 *
	 * @param string        $output Markup.
	 * @param int           $depth  Depth.
	 * @param stdClass|null $args   Menu arguments.
	 * @return void
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ): void {
		if ( 0 === $depth ) {
			$output .= sprintf( '<ul id="%s" class="menu-panel__submenu" hidden>', esc_attr( $this->submenu_id ) );
			return;
		}

		$output .= '<ul class="menu-panel__submenu menu-panel__submenu--nested">';
	}

	/**
	 * Start a menu item.
	 *
	 * @param string        $output            Markup.
	 * @param WP_Post       $data_object       Menu item.
	 * @param int           $depth             Depth.
	 * @param stdClass|null $args              Menu arguments.
	 * @param int           $current_object_id Current item ID.
	 * @return void
	 */
	public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ): void {
		$item         = $data_object;
		$classes      = empty( $item->classes ) ? array() : (array) $item->classes;
		$has_children = in_array( 'menu-item-has-children', $classes, true );
		$title        = apply_filters( 'the_title', $item->title, $item->ID );

		$classes[] = 'menu-panel__item';
		$classes[] = 0 === $depth ? 'menu-panel__item--top' : 'menu-panel__item--sub';

		$output .= sprintf( '<li class="%s">', esc_attr( implode( ' ', array_filter( array_map( 'sanitize_html_class', $classes ) ) ) ) );

		$link = sprintf(
			'<a class="%1$s" href="%2$s"%3$s%4$s%5$s>%6$s</a>',
			0 === $depth ? 'menu-panel__link' : 'menu-panel__sublink',
			esc_url( $item->url ),
			$item->target ? ' target="' . esc_attr( $item->target ) . '"' : '',
			$this->get_rel( $item ),
			in_array( 'current-menu-item', $classes, true ) ? ' aria-current="page"' : '',
			esc_html( $title )
		);

		if ( 0 !== $depth || ! $has_children ) {
			$output .= $link;
			return;
		}

		$this->submenu_id = 'menu-panel-submenu-' . (int) $item->ID;

		$output .= sprintf(
			'<div class="menu-panel__row">%1$s<button class="menu-panel__toggle" type="button" aria-expanded="false" aria-controls="%2$s"><span class="screen-reader-text">%3$s</span>%4$s</button></div>',
			$link,
			esc_attr( $this->submenu_id ),
			/* translators: %s: menu item title. */
			esc_html( sprintf( __( 'Show submenu for %s', 'skt-starter' ), $title ) ),
			skt_get_icon( 'chevron-down', 'menu-panel__toggle-icon' )
		);
	}

	/**
	 * Build the rel attribute, adding noopener to links that open a new tab.
	 *
	 * @param WP_Post $item Menu item.
	 * @return string
	 */
	private function get_rel( WP_Post $item ): string {
		$rel = (string) $item->xfn;

		if ( '_blank' === $item->target && ! str_contains( $rel, 'noopener' ) ) {
			$rel = trim( $rel . ' noopener' );
		}

		return '' === $rel ? '' : ' rel="' . esc_attr( $rel ) . '"';
	}
}
