<?php
/**
 * Media & text block.
 *
 * @package SKT\Blocks
 *
 * @var bool $is_preview Whether the block renders in the editor.
 */

use function SKT\Blocks\button;
use function SKT\Blocks\heading_tag;
use function SKT\Blocks\section_classes;

defined( 'ABSPATH' ) || exit;

$skt_title    = trim( (string) get_field( 'title' ) );
$skt_image_id = (int) get_field( 'image' );

if ( '' === $skt_title && ! $skt_image_id && ! $is_preview ) {
	return;
}

$skt_eyebrow  = trim( (string) get_field( 'eyebrow' ) );
$skt_text     = (string) get_field( 'text' );
$skt_button   = button( get_field( 'button' ) );
$skt_tag      = heading_tag( get_field( 'title_tag' ) );
$skt_position = 'start' === get_field( 'image_position' ) ? 'start' : 'end';
$skt_classes  = section_classes( get_field( 'scheme' ) ) . ' skt-media-text skt-media-text--image-' . $skt_position;
?>
<section <?php echo get_block_wrapper_attributes( array( 'class' => $skt_classes ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped by core. ?>>
	<div class="skt-section__inner skt-media-text__inner" data-reveal-group>
		<div class="skt-media-text__copy" data-reveal>
			<?php if ( '' !== $skt_eyebrow ) : ?>
				<p class="skt-media-text__eyebrow"><?php echo esc_html( $skt_eyebrow ); ?></p>
			<?php endif; ?>

			<<?php echo tag_escape( $skt_tag ); ?> class="skt-media-text__title"><?php echo esc_html( '' !== $skt_title ? $skt_title : __( 'Add a title', 'skt-blocks' ) ); ?></<?php echo tag_escape( $skt_tag ); ?>>

			<?php if ( '' !== trim( $skt_text ) ) : ?>
				<div class="skt-media-text__text"><?php echo wp_kses_post( $skt_text ); ?></div>
			<?php endif; ?>

			<?php if ( '' !== $skt_button ) : ?>
				<p class="skt-media-text__actions"><?php echo $skt_button; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in button(). ?></p>
			<?php endif; ?>
		</div>

		<?php if ( $skt_image_id ) : ?>
			<figure class="skt-media-text__media" data-reveal="image">
				<?php echo wp_get_attachment_image( $skt_image_id, 'large', false, array( 'class' => 'skt-media-text__image' ) ); ?>
			</figure>
		<?php endif; ?>
	</div>
</section>
