<?php
/**
 * Mirror the breakpoint tokens into Sass.
 *
 * Media queries cannot read CSS custom properties, so the stylesheet needs the
 * breakpoint values at compile time. This script writes them from
 * theme-data/breakpoints.php, which stays the single source of truth.
 *
 * Usage: php scripts/sync-tokens.php [output-file]
 *
 * @package SKT_Starter
 */

declare(strict_types=1);

$theme_root  = dirname( __DIR__ );
$output      = $argv[1] ?? $theme_root . '/src/scss/generated/_breakpoints.scss';
$breakpoints = require $theme_root . '/theme-data/breakpoints.php';

if ( ! is_array( $breakpoints ) || array() === $breakpoints ) {
	fwrite( STDERR, "theme-data/breakpoints.php must return a non-empty array.\n" );
	exit( 1 );
}

$lines = array(
	'// Generated from theme-data/breakpoints.php by scripts/sync-tokens.php.',
	'// Do not edit by hand: run `npm run tokens` instead.',
	'',
	'$breakpoints: (',
);

foreach ( $breakpoints as $name => $value ) {
	$lines[] = sprintf( '  "%s": %s,', preg_replace( '/[^a-z0-9-]/', '', strtolower( (string) $name ) ), $value );
}

$lines[] = ');';
$lines[] = '';

if ( ! is_dir( dirname( $output ) ) ) {
	mkdir( dirname( $output ), 0755, true );
}

file_put_contents( $output, implode( "\n", $lines ) );
fwrite( STDOUT, 'Wrote ' . $output . "\n" );
