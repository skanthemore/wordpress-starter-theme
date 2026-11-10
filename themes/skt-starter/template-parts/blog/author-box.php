<?php
/**
 * Author box at the end of a post. Only shown when the author has a bio.
 *
 * @package SKT_Starter
 */

defined( 'ABSPATH' ) || exit;

$skt_author_id = (int) get_the_author_meta( 'ID' );
$skt_bio       = (string) get_the_author_meta( 'description', $skt_author_id );

if ( '' === trim( $skt_bio ) ) {
	return;
}
?>
<aside class="author-box" aria-label="<?php esc_attr_e( 'About the author', 'skt-starter' ); ?>">
	<?php echo get_avatar( $skt_author_id, 96, '', '', array( 'class' => 'author-box__avatar' ) ); ?>

	<div class="author-box__body">
		<p class="author-box__name">
			<a href="<?php echo esc_url( get_author_posts_url( $skt_author_id ) ); ?>" rel="author"><?php the_author(); ?></a>
		</p>
		<div class="author-box__bio"><?php echo wp_kses_post( wpautop( $skt_bio ) ); ?></div>
	</div>
</aside>
