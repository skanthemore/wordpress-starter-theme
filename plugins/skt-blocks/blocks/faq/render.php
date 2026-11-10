<?php
/**
 * FAQ block.
 *
 * Built on <details>/<summary>: keyboard and screen reader support come from
 * the browser and no JavaScript is needed.
 *
 * @package SKT\Blocks
 *
 * @var bool $is_preview Whether the block renders in the editor.
 */

use function SKT\Blocks\heading_tag;
use function SKT\Blocks\section_classes;

defined( 'ABSPATH' ) || exit;

$skt_title = trim( (string) get_field( 'title' ) );
$skt_items = array();

foreach ( (array) get_field( 'items' ) as $skt_item ) {
	$skt_question = trim( (string) ( $skt_item['question'] ?? '' ) );
	$skt_answer   = trim( (string) ( $skt_item['answer'] ?? '' ) );

	if ( '' !== $skt_question && '' !== $skt_answer ) {
		$skt_items[] = array( $skt_question, $skt_answer );
	}
}

if ( ! $skt_items ) {
	if ( ! $is_preview ) {
		return;
	}

	$skt_items[] = array( __( 'Add your first question', 'skt-blocks' ), '<p>' . esc_html__( 'And its answer.', 'skt-blocks' ) . '</p>' );
}

$skt_tag = heading_tag( get_field( 'title_tag' ) );
?>
<section <?php echo get_block_wrapper_attributes( array( 'class' => section_classes( get_field( 'scheme' ) ) . ' skt-faq' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped by core. ?>>
	<div class="skt-section__inner skt-faq__inner">
		<?php if ( '' !== $skt_title ) : ?>
			<<?php echo tag_escape( $skt_tag ); ?> class="skt-faq__title" data-reveal><?php echo esc_html( $skt_title ); ?></<?php echo tag_escape( $skt_tag ); ?>>
		<?php endif; ?>

		<div class="skt-faq__items" data-reveal-group>
			<?php foreach ( $skt_items as list( $skt_question, $skt_answer ) ) : ?>
				<details class="skt-faq__item" data-reveal>
					<summary class="skt-faq__question"><?php echo esc_html( $skt_question ); ?></summary>
					<div class="skt-faq__answer"><?php echo wp_kses_post( $skt_answer ); ?></div>
				</details>
			<?php endforeach; ?>
		</div>
	</div>

	<?php
	if ( get_field( 'schema' ) && ! $is_preview ) {
		$skt_schema = array(
			'@context'   => 'https://schema.org',
			'@type'      => 'FAQPage',
			'mainEntity' => array_map(
				static fn( array $item ): array => array(
					'@type'          => 'Question',
					'name'           => wp_strip_all_tags( $item[0] ),
					'acceptedAnswer' => array(
						'@type' => 'Answer',
						'text'  => wp_strip_all_tags( $item[1] ),
					),
				),
				$skt_items
			),
		);

		wp_print_inline_script_tag( (string) wp_json_encode( $skt_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ), array( 'type' => 'application/ld+json' ) );
	}
	?>
</section>
