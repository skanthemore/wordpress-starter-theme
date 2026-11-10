<?php
/**
 * Theme bootstrap.
 *
 * Every feature lives in its own file under inc/. This file only defines the
 * shared constants and loads those modules in dependency order.
 *
 * @package SKT_Starter
 */

defined( 'ABSPATH' ) || exit;

define( 'SKT_THEME_VERSION', (string) wp_get_theme( get_template() )->get( 'Version' ) );
define( 'SKT_THEME_DIR', get_template_directory() );
define( 'SKT_THEME_URI', get_template_directory_uri() );

require_once SKT_THEME_DIR . '/inc/setup.php';
require_once SKT_THEME_DIR . '/inc/options.php';
require_once SKT_THEME_DIR . '/inc/tokens.php';
require_once SKT_THEME_DIR . '/inc/assets.php';
require_once SKT_THEME_DIR . '/inc/performance.php';
require_once SKT_THEME_DIR . '/inc/svg.php';
require_once SKT_THEME_DIR . '/inc/icons.php';
require_once SKT_THEME_DIR . '/inc/navigation.php';
require_once SKT_THEME_DIR . '/inc/class-skt-menu-panel-walker.php';
require_once SKT_THEME_DIR . '/inc/header.php';
require_once SKT_THEME_DIR . '/inc/template-tags.php';
