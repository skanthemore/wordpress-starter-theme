/**
 * Build the plugin assets.
 *
 * - blocks/<block>/*.scss  -> blocks/<block>/*.css (next to block.json)
 * - src/scss/common.scss   -> assets/css/common.css
 * - Swiper from npm        -> assets/vendor/swiper/
 *
 * Usage: node scripts/build.js [--watch]
 */

import { existsSync, mkdirSync, readFileSync, readdirSync, writeFileSync } from 'node:fs';
import { createRequire } from 'node:module';
import { dirname, join, relative, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';

import autoprefixer from 'autoprefixer';
import chokidar from 'chokidar';
import postcss from 'postcss';
import * as sass from 'sass';

const root = resolve(dirname(fileURLToPath(import.meta.url)), '..');
const watch = process.argv.includes('--watch');
const require = createRequire(import.meta.url);

function entries() {
	const blocks = join(root, 'blocks');
	const list = [[join(root, 'src/scss/common.scss'), join(root, 'assets/css/common.css')]];

	for (const block of readdirSync(blocks)) {
		for (const file of ['style.scss', 'editor.scss']) {
			const source = join(blocks, block, file);

			if (existsSync(source)) {
				list.push([source, source.replace(/\.scss$/, '.css')]);
			}
		}
	}

	return list;
}

async function compile(source, target) {
	const { css } = sass.compile(source, {
		loadPaths: [join(root, 'src/scss')],
		style: watch ? 'expanded' : 'compressed',
	});
	const result = await postcss([autoprefixer]).process(css, { from: source, to: target });

	mkdirSync(dirname(target), { recursive: true });
	writeFileSync(target, result.css);
	console.log(`✓ ${relative(root, target)}`);
}

async function buildStyles() {
	for (const [source, target] of entries()) {
		await compile(source, target);
	}
}

function copyVendor() {
	const swiper = dirname(require.resolve('swiper/package.json'));
	const target = join(root, 'assets/vendor/swiper');

	mkdirSync(target, { recursive: true });

	// Source maps are not shipped, so drop the comments that point to them.
	for (const file of ['swiper-bundle.min.js', 'swiper-bundle.min.css', 'LICENSE']) {
		const contents = readFileSync(join(swiper, file), 'utf8').replace(/\n?\/[/*]# sourceMappingURL=\S+( \*\/)?\s*$/, '\n');

		writeFileSync(join(target, file), contents);
	}

	console.log(`✓ ${relative(root, target)}/`);
}

try {
	copyVendor();
	await buildStyles();
} catch (error) {
	console.error(error.message);
	process.exitCode = 1;
}

if (watch) {
	chokidar.watch(['blocks', 'src/scss'], { cwd: root, ignoreInitial: true }).on('all', async (event, path) => {
		if (!path.endsWith('.scss')) {
			return;
		}

		try {
			await buildStyles();
		} catch (error) {
			console.error(error.message);
		}
	});

	console.log('Watching for changes…');
}
