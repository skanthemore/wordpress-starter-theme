<?php
/**
 * Helpers shared by the token definition files.
 *
 * Kept free of WordPress calls so the build scripts can load theme-data/
 * from the PHP CLI.
 *
 * @package SKT_Starter
 */

if ( ! function_exists( 'skt_fluid_size' ) ) {
	/**
	 * Build a fluid clamp() value that scales linearly between two viewports.
	 *
	 * @param string $min_size     Size at the smallest viewport, in px.
	 * @param string $max_size     Size at the largest viewport, in px.
	 * @param string $min_viewport Smallest viewport width, in px.
	 * @param string $max_viewport Largest viewport width, in px.
	 * @param int    $root_size    Root font size, in px.
	 * @return string
	 */
	function skt_fluid_size( string $min_size, string $max_size, string $min_viewport = '375px', string $max_viewport = '1440px', int $root_size = 16 ): string {
		$min_rem = (float) $min_size / $root_size;
		$max_rem = (float) $max_size / $root_size;

		if ( $min_rem === $max_rem ) {
			return rtrim( rtrim( sprintf( '%.4F', $min_rem ), '0' ), '.' ) . 'rem';
		}

		$min_vw = (int) $min_viewport;
		$max_vw = (int) $max_viewport;
		$slope  = ( $max_rem - $min_rem ) / ( $max_vw - $min_vw );

		return sprintf(
			'clamp(%1$.4Frem, %2$.4Frem + %3$.4Fvw, %4$.4Frem)',
			$min_rem,
			$min_rem - $slope * $min_vw,
			$slope * $root_size * 100,
			$max_rem
		);
	}
}

if ( ! function_exists( 'skt_presets' ) ) {
	/**
	 * Turn a `slug => [ min, max, name ]` map into theme.json presets.
	 *
	 * @param array<string, array{0: string, 1: string, 2: string}> $sizes Size map.
	 * @return array<int, array{name: string, slug: string, size: string}>
	 */
	function skt_presets( array $sizes ): array {
		$presets = array();

		foreach ( $sizes as $slug => $size ) {
			$presets[] = array(
				'name' => $size[2],
				'slug' => (string) $slug,
				'size' => skt_fluid_size( $size[0], $size[1] ),
			);
		}

		return $presets;
	}
}
