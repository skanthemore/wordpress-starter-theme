<?php
/**
 * Media & text block fields.
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
		'label' => __( 'Eyebrow', 'skt-blocks' ),
		'name'  => 'eyebrow',
		'type'  => 'text',
	),
	array(
		'label'    => __( 'Title', 'skt-blocks' ),
		'name'     => 'title',
		'type'     => 'text',
		'required' => 1,
		'wrapper'  => array( 'width' => '75' ),
	),
	heading_tag_field( 'title_tag', 'h2' ),
	array(
		'label'        => __( 'Text', 'skt-blocks' ),
		'name'         => 'text',
		'type'         => 'wysiwyg',
		'tabs'         => 'visual',
		'toolbar'      => 'SKT',
		'media_upload' => 0,
	),
	array(
		'label'         => __( 'Button', 'skt-blocks' ),
		'name'          => 'button',
		'type'          => 'link',
		'return_format' => 'array',
	),
	array(
		'label'         => __( 'Image', 'skt-blocks' ),
		'name'          => 'image',
		'type'          => 'image',
		'return_format' => 'id',
		'preview_size'  => 'medium',
	),
	array(
		'label' => __( 'Layout', 'skt-blocks' ),
		'type'  => 'tab',
	),
	array(
		'label'         => __( 'Image position', 'skt-blocks' ),
		'name'          => 'image_position',
		'type'          => 'button_group',
		'choices'       => array(
			'end'   => __( 'After the text', 'skt-blocks' ),
			'start' => __( 'Before the text', 'skt-blocks' ),
		),
		'default_value' => 'end',
	),
	scheme_field( 'light' ),
);
