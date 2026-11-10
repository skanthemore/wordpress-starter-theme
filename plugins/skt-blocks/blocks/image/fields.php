<?php
/**
 * Framed image block fields.
 *
 * The background choices are read from the active theme palette.
 *
 * @package SKT\Blocks
 */

namespace SKT\Blocks;

defined( 'ABSPATH' ) || exit;

return array(
	array(
		'label'         => __( 'Image', 'skt-blocks' ),
		'name'          => 'image',
		'type'          => 'image',
		'return_format' => 'id',
		'preview_size'  => 'medium',
		'required'      => 1,
	),
	array(
		'label'         => __( 'Background', 'skt-blocks' ),
		'name'          => 'background',
		'type'          => 'select',
		'choices'       => get_color_choices(),
		'default_value' => 'surface',
		'wrapper'       => array( 'width' => '50' ),
	),
	array(
		'label'   => __( 'Full width', 'skt-blocks' ),
		'name'    => 'full_width',
		'type'    => 'true_false',
		'ui'      => 1,
		'wrapper' => array( 'width' => '50' ),
	),
);
