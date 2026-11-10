<?php
/**
 * Spacing presets: `slug => [ min, max, name ]`.
 *
 * Relies on skt_presets() from helpers.php, loaded by inc/tokens.php.
 *
 * @package SKT_Starter
 */

return skt_presets(
	array(
		's'  => array( '24px', '32px', 'Small' ),
		'm'  => array( '40px', '64px', 'Medium' ),
		'l'  => array( '64px', '104px', 'Large' ),
		'xl' => array( '88px', '144px', 'Extra large' ),
	)
);
