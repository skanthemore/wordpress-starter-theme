<?php
/**
 * Plugin wiring: requirements, assets, block discovery and field groups.
 *
 * A block is a folder in blocks/ that contains a block.json. Nothing has to
 * be registered by hand: adding the folder is enough.
 *
 * @package SKT\Blocks
 */

namespace SKT\Blocks;

defined( 'ABSPATH' ) || exit;

/**
 * Hook the plugin once every plugin is loaded, so ACF can be detected.
 *
 * @return void
 */
function bootstrap(): void {
	if ( ! has_acf_pro() ) {
		add_action( 'admin_notices', __NAMESPACE__ . '\\render_requirements_notice' );
		return;
	}

	add_action( 'init', __NAMESPACE__ . '\\register_shared_assets', 5 );
	add_action( 'init', __NAMESPACE__ . '\\register_blocks' );
	add_action( 'acf/init', __NAMESPACE__ . '\\register_field_groups' );
	add_filter( 'block_categories_all', __NAMESPACE__ . '\\add_block_category' );
	add_filter( 'acf/fields/wysiwyg/toolbars', __NAMESPACE__ . '\\add_wysiwyg_toolbar' );
}

/**
 * Whether ACF PRO 6.0+ (block.json support) is active.
 *
 * @return bool
 */
function has_acf_pro(): bool {
	return defined( 'ACF_PRO' ) && ACF_PRO && defined( 'ACF_VERSION' ) && version_compare( ACF_VERSION, '6.0', '>=' );
}

/**
 * Explain why no block is available.
 *
 * @return void
 */
function render_requirements_notice(): void {
	if ( ! current_user_can( 'activate_plugins' ) ) {
		return;
	}

	printf(
		'<div class="notice notice-warning"><p>%s</p></div>',
		esc_html__( 'SKT Blocks needs ACF PRO 6.0 or later. The blocks stay disabled until it is active.', 'skt-blocks' )
	);
}

/**
 * Register assets that several blocks share.
 *
 * Blocks list these handles in their block.json, so WordPress only prints
 * them on pages that contain one of those blocks.
 *
 * @return void
 */
function register_shared_assets(): void {
	$assets = array(
		'skt-blocks-common' => array( 'assets/css/common.css', array() ),
		'swiper'            => array( 'assets/vendor/swiper/swiper-bundle.min.css', array() ),
	);

	foreach ( $assets as $handle => list( $file, $deps ) ) {
		if ( file_exists( SKT_BLOCKS_PATH . $file ) ) {
			wp_register_style( $handle, SKT_BLOCKS_URL . $file, $deps, (string) filemtime( SKT_BLOCKS_PATH . $file ) );
		}
	}

	$swiper = 'assets/vendor/swiper/swiper-bundle.min.js';

	if ( file_exists( SKT_BLOCKS_PATH . $swiper ) ) {
		wp_register_script( 'swiper', SKT_BLOCKS_URL . $swiper, array(), (string) filemtime( SKT_BLOCKS_PATH . $swiper ), array( 'strategy' => 'defer' ) );
	}
}

/**
 * Folders in blocks/ that contain a block.json, keyed by slug.
 *
 * @return array<string, string> Absolute block paths keyed by slug.
 */
function get_block_paths(): array {
	static $paths = null;

	if ( null === $paths ) {
		$paths = array();

		$files = glob( SKT_BLOCKS_PATH . 'blocks/*/block.json' );

		foreach ( is_array( $files ) ? $files : array() as $metadata ) {
			$paths[ basename( dirname( $metadata ) ) ] = dirname( $metadata );
		}
	}

	return $paths;
}

/**
 * Register every block from its block.json.
 *
 * @return void
 */
function register_blocks(): void {
	foreach ( get_block_paths() as $path ) {
		register_block_type( $path );
	}
}

/**
 * Register one ACF field group per block from its fields.php.
 *
 * Each fields.php returns a plain array of field definitions. Keys may be left
 * out: they are derived from the block slug and the field name, which keeps
 * them unique and stable.
 *
 * @return void
 */
function register_field_groups(): void {
	foreach ( get_block_paths() as $slug => $path ) {
		$fields_file = $path . '/fields.php';

		if ( ! file_exists( $fields_file ) ) {
			continue;
		}

		$metadata = wp_json_file_decode( $path . '/block.json', array( 'associative' => true ) );

		acf_add_local_field_group(
			array(
				'key'      => 'group_skt_' . str_replace( '-', '_', $slug ),
				'title'    => $metadata['title'] ?? $slug,
				'fields'   => with_field_keys( require $fields_file, 'field_skt_' . str_replace( '-', '_', $slug ) ),
				'location' => array(
					array(
						array(
							'param'    => 'block',
							'operator' => '==',
							'value'    => $metadata['name'],
						),
					),
				),
			)
		);
	}
}

/**
 * Give every field, including repeater sub fields, a key when it has none.
 *
 * @param array<int, array<string, mixed>> $fields Field definitions.
 * @param string                           $prefix Key prefix.
 * @return array<int, array<string, mixed>>
 */
function with_field_keys( array $fields, string $prefix ): array {
	foreach ( $fields as $index => $field ) {
		$name = $field['name'] ?? ( $field['type'] . '_' . $index );
		$key  = $prefix . '_' . $name;

		$fields[ $index ]['key'] = $field['key'] ?? $key;

		if ( ! empty( $field['sub_fields'] ) ) {
			$fields[ $index ]['sub_fields'] = with_field_keys( $field['sub_fields'], $key );
		}
	}

	return $fields;
}

/**
 * Add the SKT category at the top of the inserter.
 *
 * @param array<int, array<string, string>> $categories Block categories.
 * @return array<int, array<string, string>>
 */
function add_block_category( array $categories ): array {
	array_unshift(
		$categories,
		array(
			'slug'  => 'skt',
			'title' => __( 'SKT Blocks', 'skt-blocks' ),
		)
	);

	return $categories;
}

/**
 * Add a compact WYSIWYG toolbar for long-form text fields.
 *
 * @param array<string, array<int, string[]>> $toolbars ACF toolbars.
 * @return array<string, array<int, string[]>>
 */
function add_wysiwyg_toolbar( array $toolbars ): array {
	$toolbars['SKT'] = array(
		1 => array( 'formatselect', 'bold', 'italic', 'bullist', 'numlist', 'blockquote', 'link', 'unlink', 'removeformat', 'undo', 'redo' ),
	);

	return $toolbars;
}
