<?php
/**
 * Rich text block fields.
 *
 * @package SKT\Blocks
 */

namespace SKT\Blocks;

defined( 'ABSPATH' ) || exit;

return array(
	array(
		'label'        => __( 'Text', 'skt-blocks' ),
		'name'         => 'content',
		'type'         => 'wysiwyg',
		'tabs'         => 'visual',
		'toolbar'      => 'SKT',
		'media_upload' => 0,
		'required'     => 1,
	),
	array(
		'label'         => __( 'Button', 'skt-blocks' ),
		'name'          => 'button',
		'type'          => 'link',
		'return_format' => 'array',
	),
	scheme_field( 'light' ),
);
