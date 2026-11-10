<?php
/**
 * Framed image block.
 *
 * @package SKT\Blocks
 *
 * @var bool $is_preview Whether the block renders in the editor.
 */

use function SKT\Blocks\get_color_value;

defined( 'ABSPATH' ) || exit;

$skt_image_id = (int) get_field( 'image' );

if ( ! $skt_image_id && ! $is_preview ) {
	return;
}

$skt_background = get_color_value( (string) get_field( 'background' ) );
$skt_caption    = $skt_image_id ? wp_get_attachment_caption( $skt_image_id ) : '';
$skt_attributes = array(
	'class' => 'skt-image' . ( get_field( 'full_width' ) ? ' alignfull skt-image--full' : ' alignwide' ),
);

if ( '' !== $skt_background ) {
	$skt_attributes['style'] = '--skt-image-bg:' . $skt_background;
}
?>
<figure <?php echo get_block_wrapper_attributes( $skt_attributes ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped by core. ?>>
	<?php if ( $skt_image_id ) : ?>
		<?php
		echo wp_get_attachment_image(
			$skt_image_id,
			'large',
			false,
			array(
				'class'       => 'skt-image__img',
				'data-reveal' => 'image',
			)
		);
		?>
	<?php else : ?>
		<div class="skt-image__placeholder"><?php esc_html_e( 'Choose an image', 'skt-blocks' ); ?></div>
	<?php endif; ?>

	<?php if ( $skt_caption ) : ?>
		<figcaption class="skt-image__caption"><?php echo esc_html( $skt_caption ); ?></figcaption>
	<?php endif; ?>
</figure>
