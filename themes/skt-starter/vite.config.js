import { dirname, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';
import { defineConfig } from 'vite';

const root = dirname(fileURLToPath(import.meta.url));

/*
 * WordPress loads the bundle from fixed paths (see inc/assets.php), so output
 * names are not hashed: dist/main.js and dist/main.css. Cache busting comes
 * from the file modification time.
 */
export default defineConfig(({ mode }) => ({
	build: {
		outDir: 'dist',
		emptyOutDir: true,
		cssCodeSplit: false,
		sourcemap: mode === 'development',
		minify: mode !== 'development',
		target: 'es2020',
		rollupOptions: {
			input: resolve(root, 'src/js/main.js'),
			output: {
				entryFileNames: 'main.js',
				assetFileNames: (asset) => {
					const name = asset.names?.[0] ?? asset.name ?? '';

					return name.endsWith('.css') ? 'main.css' : 'assets/[name][extname]';
				},
			},
		},
	},
}));
