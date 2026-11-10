<?php
/**
 * Hero block fields.
 *
 * @package SKT\Blocks
 */

namespace SKT\Blocks;

defined( 'ABSPATH' ) || exit;

return array(
	array(
		'label' => __( 'Content', 'skt-blocks' ),
		'type'  => 'tab',
	),
	array(
		'label'    => __( 'Heading', 'skt-blocks' ),
		'name'     => 'heading',
		'type'     => 'text',
		'required' => 1,
		'wrapper'  => array( 'width' => '75' ),
	),
	heading_tag_field( 'heading_tag', 'h1' ),
	array(
		'label'     => __( 'Text', 'skt-blocks' ),
		'name'      => 'text',
		'type'      => 'textarea',
		'rows'      => 3,
		'new_lines' => 'br',
	),
	array(
		'label'         => __( 'Button', 'skt-blocks' ),
		'name'          => 'button',
		'type'          => 'link',
		'return_format' => 'array',
	),
	array(
		'label' => __( 'Media', 'skt-blocks' ),
		'type'  => 'tab',
	),
	array(
		'label'         => __( 'Image', 'skt-blocks' ),
		'name'          => 'image',
		'type'          => 'image',
		'return_format' => 'id',
		'preview_size'  => 'medium',
		'instructions'  => __( 'Landscape, at least 1920px wide.', 'skt-blocks' ),
		'wrapper'       => array( 'width' => '50' ),
	),
	array(
		'label'         => __( 'Mobile image', 'skt-blocks' ),
		'name'          => 'image_mobile',
		'type'          => 'image',
		'return_format' => 'id',
		'preview_size'  => 'medium',
		'instructions'  => __( 'Optional portrait crop for small screens.', 'skt-blocks' ),
		'wrapper'       => array( 'width' => '50' ),
	),
	array(
		'label'         => __( 'Background video', 'skt-blocks' ),
		'name'          => 'video',
		'type'          => 'file',
		'return_format' => 'url',
		'mime_types'    => 'mp4,webm',
		'instructions'  => __( 'Optional muted loop played over the image. The image stays as poster and fallback.', 'skt-blocks' ),
	),
	array(
		'label'         => __( 'Darken media', 'skt-blocks' ),
		'name'          => 'overlay',
		'type'          => 'true_false',
		'ui'            => 1,
		'default_value' => 1,
		'instructions'  => __( 'Adds a gradient so light text stays readable.', 'skt-blocks' ),
	),
);
