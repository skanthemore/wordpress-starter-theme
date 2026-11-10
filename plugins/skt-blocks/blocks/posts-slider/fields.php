<?php
/**
 * Posts slider block fields.
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
		'label'         => __( 'Number of posts', 'skt-blocks' ),
		'name'          => 'count',
		'type'          => 'number',
		'min'           => 3,
		'max'           => 12,
		'default_value' => 6,
		'wrapper'       => array( 'width' => '33' ),
	),
	array(
		'label'         => __( 'Category', 'skt-blocks' ),
		'name'          => 'category',
		'type'          => 'taxonomy',
		'taxonomy'      => 'category',
		'field_type'    => 'select',
		'allow_null'    => 1,
		'add_term'      => 0,
		'return_format' => 'id',
		'instructions'  => __( 'Leave empty to show every category.', 'skt-blocks' ),
		'wrapper'       => array( 'width' => '33' ),
	),
	array_merge( scheme_field( 'surface' ), array( 'wrapper' => array( 'width' => '33' ) ) ),
);
