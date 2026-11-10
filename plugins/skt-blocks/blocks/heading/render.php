<?php
/**
 * Heading block.
 *
 * @package SKT\Blocks
 *
 * @var bool $is_preview Whether the block renders in the editor.
 */

use function SKT\Blocks\heading_tag;

defined( 'ABSPATH' ) || exit;

$skt_text = trim( (string) get_field( 'text' ) );

if ( '' === $skt_text && ! $is_preview ) {
	return;
}

$skt_tag  = heading_tag( get_field( 'tag' ) );
$skt_size = in_array( get_field( 'size' ), array( 'h1', 'h2', 'h3', 'h4' ), true ) ? get_field( 'size' ) : 'h2';
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'skt-heading skt-heading--' . $skt_size ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped by core. ?>>
	<<?php echo tag_escape( $skt_tag ); ?> class="skt-heading__text"><?php echo esc_html( '' !== $skt_text ? $skt_text : __( 'Add a heading', 'skt-blocks' ) ); ?></<?php echo tag_escape( $skt_tag ); ?>>
</div>
