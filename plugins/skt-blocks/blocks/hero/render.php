<?php
/**
 * Hero block.
 *
 * The image is the LCP element: it loads eagerly with high priority. A
 * background video is attached by view.js only after the page has loaded,
 * and never for visitors who prefer reduced motion or save data.
 *
 * @package SKT\Blocks
 *
 * @var bool $is_preview Whether the block renders in the editor.
 */

use function SKT\Blocks\button;
use function SKT\Blocks\heading_tag;

defined( 'ABSPATH' ) || exit;

$skt_heading  = trim( (string) get_field( 'heading' ) );
$skt_text     = (string) get_field( 'text' );
$skt_image_id = (int) get_field( 'image' );
$skt_mobile   = (int) get_field( 'image_mobile' );
$skt_video    = (string) get_field( 'video' );

if ( '' === $skt_heading && ! $skt_image_id && ! $is_preview ) {
	return;
}

$skt_has_media = $skt_image_id || '' !== $skt_video;
$skt_classes   = array( 'alignfull', 'skt-hero', $skt_has_media ? 'skt-hero--media' : 'skt-hero--plain' );

if ( $skt_has_media && get_field( 'overlay' ) ) {
	$skt_classes[] = 'skt-hero--overlay';
}

$skt_tag    = heading_tag( get_field( 'heading_tag' ), 'h1' );
$skt_button = button( get_field( 'button' ), 'button' );
?>
<section <?php echo get_block_wrapper_attributes( array( 'class' => implode( ' ', $skt_classes ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped by core. ?>>
	<?php if ( $skt_has_media ) : ?>
		<div class="skt-hero__media">
			<?php if ( $skt_image_id ) : ?>
				<picture>
					<?php if ( $skt_mobile ) : ?>
						<source media="(max-width: 767px)" srcset="<?php echo esc_attr( (string) wp_get_attachment_image_srcset( $skt_mobile, 'full' ) ); ?>" sizes="100vw">
					<?php endif; ?>
					<?php
					echo wp_get_attachment_image(
						$skt_image_id,
						'full',
						false,
						array(
							'class'         => 'skt-hero__image',
							'sizes'         => '100vw',
							'loading'       => 'eager',
							'fetchpriority' => 'high',
						)
					);
					?>
				</picture>
			<?php endif; ?>

			<?php if ( '' !== $skt_video && ! $is_preview ) : ?>
				<video class="skt-hero__video" data-src="<?php echo esc_url( $skt_video ); ?>" muted loop playsinline preload="none" aria-hidden="true"></video>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<div class="skt-hero__content">
		<<?php echo tag_escape( $skt_tag ); ?> class="skt-hero__heading"><?php echo esc_html( '' !== $skt_heading ? $skt_heading : __( 'Add a heading', 'skt-blocks' ) ); ?></<?php echo tag_escape( $skt_tag ); ?>>

		<?php if ( '' !== trim( $skt_text ) ) : ?>
			<p class="skt-hero__text"><?php echo wp_kses( $skt_text, array( 'br' => array() ) ); ?></p>
		<?php endif; ?>

		<?php if ( '' !== $skt_button ) : ?>
			<p class="skt-hero__actions"><?php echo $skt_button; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in button(). ?></p>
		<?php endif; ?>
	</div>
</section>
