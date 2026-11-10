<?php
/**
 * Plugin Name:       SKT Blocks
 * Plugin URI:        https://github.com/skanthemore/wordpress-starter-theme
 * Description:       ACF blocks that render with the design tokens of the active theme. Companion of the SKT Starter theme.
 * Version:           1.0.0
 * Requires at least: 6.6
 * Requires PHP:      8.1
 * Author:            Cristian Cascante
 * Author URI:        https://github.com/skanthemore
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       skt-blocks
 *
 * @package SKT\Blocks
 */

namespace SKT\Blocks;

defined( 'ABSPATH' ) || exit;

define( 'SKT_BLOCKS_VERSION', '1.0.0' );
define( 'SKT_BLOCKS_PATH', plugin_dir_path( __FILE__ ) );
define( 'SKT_BLOCKS_URL', plugin_dir_url( __FILE__ ) );

require_once SKT_BLOCKS_PATH . 'src/helpers.php';
require_once SKT_BLOCKS_PATH . 'src/plugin.php';

add_action( 'plugins_loaded', __NAMESPACE__ . '\\bootstrap' );
