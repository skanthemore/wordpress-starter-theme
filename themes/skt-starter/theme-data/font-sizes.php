<?php
/**
 * Font size presets: `slug => [ min, max, name ]`.
 *
 * Values scale fluidly between 375px and 1440px viewports.
 *
 * Relies on skt_presets() from helpers.php, loaded by inc/tokens.php.
 *
 * @package SKT_Starter
 */

return skt_presets(
	array(
		'h1'     => array( '40px', '64px', 'Heading 1' ),
		'h2'     => array( '32px', '48px', 'Heading 2' ),
		'h3'     => array( '24px', '32px', 'Heading 3' ),
		'h4'     => array( '20px', '24px', 'Heading 4' ),
		'body-l' => array( '18px', '20px', 'Body large' ),
		'body-m' => array( '16px', '16px', 'Body' ),
		'body-s' => array( '14px', '14px', 'Body small' ),
	)
);
