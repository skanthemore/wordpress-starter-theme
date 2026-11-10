<?php
/**
 * FAQ block fields.
 *
 * @package SKT\Blocks
 */

namespace SKT\Blocks;

defined( 'ABSPATH' ) || exit;

return array(
	array(
		'label'   => __( 'Title', 'skt-blocks' ),
		'name'    => 'title',
		'type'    => 'text',
		'wrapper' => array( 'width' => '75' ),
	),
	heading_tag_field( 'title_tag', 'h2' ),
	array(
		'label'        => __( 'Questions', 'skt-blocks' ),
		'name'         => 'items',
		'type'         => 'repeater',
		'layout'       => 'block',
		'min'          => 1,
		'button_label' => __( 'Add question', 'skt-blocks' ),
		'sub_fields'   => array(
			array(
				'label'    => __( 'Question', 'skt-blocks' ),
				'name'     => 'question',
				'type'     => 'text',
				'required' => 1,
			),
			array(
				'label'        => __( 'Answer', 'skt-blocks' ),
				'name'         => 'answer',
				'type'         => 'wysiwyg',
				'tabs'         => 'visual',
				'toolbar'      => 'basic',
				'media_upload' => 0,
				'required'     => 1,
			),
		),
	),
	array(
		'label'         => __( 'Structured data', 'skt-blocks' ),
		'name'          => 'schema',
		'type'          => 'true_false',
		'ui'            => 1,
		'default_value' => 1,
		'instructions'  => __( 'Outputs FAQPage JSON-LD built from the visible questions. Use it once per page.', 'skt-blocks' ),
		'wrapper'       => array( 'width' => '50' ),
	),
	array_merge( scheme_field( 'light' ), array( 'wrapper' => array( 'width' => '50' ) ) ),
);
