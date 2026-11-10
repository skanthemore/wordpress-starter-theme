<?php
/**
 * Heading block fields.
 *
 * @package SKT\Blocks
 */

namespace SKT\Blocks;

defined( 'ABSPATH' ) || exit;

return array(
	array(
		'label'    => __( 'Text', 'skt-blocks' ),
		'name'     => 'text',
		'type'     => 'text',
		'required' => 1,
	),
	heading_tag_field( 'tag', 'h2' ),
	array(
		'label'         => __( 'Visual size', 'skt-blocks' ),
		'name'          => 'size',
		'type'          => 'select',
		'choices'       => array(
			'h1' => __( 'Heading 1', 'skt-blocks' ),
			'h2' => __( 'Heading 2', 'skt-blocks' ),
			'h3' => __( 'Heading 3', 'skt-blocks' ),
			'h4' => __( 'Heading 4', 'skt-blocks' ),
		),
		'default_value' => 'h2',
		'wrapper'       => array( 'width' => '25' ),
	),
);
