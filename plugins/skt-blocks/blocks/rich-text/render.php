<?php
/**
 * Rich text block.
 *
 * @package SKT\Blocks
 *
 * @var bool $is_preview Whether the block renders in the editor.
 */

use function SKT\Blocks\button;
use function SKT\Blocks\section_classes;

defined( 'ABSPATH' ) || exit;

$skt_content = (string) get_field( 'content' );

if ( '' === trim( $skt_content ) ) {
	if ( ! $is_preview ) {
		return;
	}

	$skt_content = '<p>' . esc_html__( 'Add the text of this section.', 'skt-blocks' ) . '</p>';
}

$skt_button = button( get_field( 'button' ) );
?>
<section <?php echo get_block_wrapper_attributes( array( 'class' => section_classes( get_field( 'scheme' ) ) . ' skt-rich-text' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped by core. ?>>
	<div class="skt-section__inner skt-rich-text__inner" data-reveal>
		<div class="skt-rich-text__content"><?php echo wp_kses_post( $skt_content ); ?></div>

		<?php if ( '' !== $skt_button ) : ?>
			<p class="skt-rich-text__actions"><?php echo $skt_button; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in button(). ?></p>
		<?php endif; ?>
	</div>
</section>
