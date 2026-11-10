# WordPress Starter Theme

A lean starting point for custom WordPress builds: a classic theme driven by PHP design tokens, and a companion plugin of ACF blocks that render with those same tokens.

| Package | What it is |
| --- | --- |
| [`themes/skt-starter`](themes/skt-starter) | Classic theme: templates, design tokens, header and footer, Vite asset pipeline. |
| [`plugins/skt-blocks`](plugins/skt-blocks) | ACF PRO blocks: hero, media & text, rich text, FAQ, posts slider, heading and framed image. |

The two are independent. The theme runs without the plugin or ACF; the blocks run under any theme and fall back to a neutral palette.

## Principles

- **One source of truth for design.** Colors, type scale, spacing and widths live in `themes/skt-starter/theme-data/`. The theme turns them into `theme.json` presets and `--skt-*` custom properties; every stylesheet, in the theme and in the blocks, consumes those properties.
- **The editor stays native.** Only presets reach the block editor. The front-end bundle never styles the editor canvas; each block ships its own styles.
- **Progressive enhancement.** The FAQ, the back-to-top link and the posts slider work without JavaScript, and scroll reveals never hide content unless their script has run.
- **Performance by default.** No jQuery on the front end, one stylesheet and one ES module from the theme, block assets only on pages that use the block, self-hosted fonts with a preload, and an eager, high-priority hero image.
- **Accessible markup.** Skip link, visible focus, an `inert` off-canvas menu, disclosure buttons with `aria-expanded`, native `<details>` for the FAQ, one link per card.

## Requirements

- WordPress 6.6+ and PHP 8.1+
- Node.js 18.18+ and npm, to build the assets
- ACF PRO 6.0+, only for the blocks and the Theme Options page
- Docker, optionally, for the local environment

## Getting started

Build both packages:

```bash
cd themes/skt-starter && npm install && npm run build
cd ../../plugins/skt-blocks && npm install && npm run build
```

Run a local WordPress with both packages mounted:

```bash
docker compose up -d   # http://localhost:8080
```

ACF PRO is commercial software and is not included. Mount your own copy through a git-ignored `compose.override.yaml`; the comment at the top of [`compose.yaml`](compose.yaml) shows how.

To use the packages in an existing site, copy or symlink `themes/skt-starter` and `plugins/skt-blocks` into `wp-content/`, or install the ZIPs described below.

## Development

| Command | Where | Does |
| --- | --- | --- |
| `npm run dev` | each package | Rebuilds on every change. |
| `npm run build` | each package | Production build. |
| `npm run tokens` | each package | Mirrors `theme-data/breakpoints.php` into Sass. Run it after changing breakpoints. |
| `npm run zip` | each package | Builds and writes an installable ZIP to `build/`. |
| `composer install && composer lint` | repository root | WordPress Coding Standards and PHP compatibility checks. |

## Design tokens

```text
theme-data/colors.php      → --skt-color-{slug}      + theme.json palette
theme-data/font-sizes.php  → --skt-font-size-{slug}  + theme.json font sizes
theme-data/spacings.php    → --skt-spacing-{slug}    + theme.json spacing sizes
theme-data/fonts.php       → --skt-font-{slug}
theme-data/widths.php      → --skt-width-{slug}      + theme.json content and wide sizes
theme-data/layout.php      → --skt-{group}-{slug}
theme-data/breakpoints.php → Sass $breakpoints (media queries cannot read custom properties)
```

Font sizes and spacing are fluid: each preset declares a size at 375px and at 1440px and is compiled into a `clamp()`. Palette colors can be overridden per site from **Appearance → Theme Options → Colors**.

Companion code reads the tokens through a small public API instead of duplicating values: `skt_get_design_tokens()` and `skt_get_color_palette()`, both filterable through `skt_design_tokens`.

## Theme features

- **Header** with logo or site title, an optional call-to-action button, a WPML or Polylang language switcher and an off-canvas menu with nested submenus.
- **Header color and position** per site and per entry: dark or light text, static or overlaying the first block. Resolved in `inc/header.php`, filterable with `skt_header_color` and `skt_header_position`.
- **Footer** with text, social links and two menu locations.
- **Blog templates**: index with category filter, archives, search, and single posts with author box, post navigation and related posts.
- **Pages** print their title unless they open with a Hero block (`skt_show_page_title` filter).
- **SVG logos and uploads**, restricted to users who can already post unfiltered HTML.
- **Performance options** in the Customizer: inline the stylesheet, drop jQuery Migrate.

## Repository layout

```text
.
├── bin/package.sh            ZIP packaging shared by both packages
├── compose.yaml              local WordPress
├── phpcs.xml.dist            coding standards
├── themes/skt-starter/
│   ├── inc/                  one file per feature, loaded by functions.php
│   ├── template-parts/       header, footer and blog partials
│   ├── theme-data/           design tokens
│   ├── src/js, src/scss      sources compiled by Vite into dist/
│   └── *.php                 templates
└── plugins/skt-blocks/
    ├── blocks/<slug>/        block.json, fields.php, render.php, style.scss
    ├── src/                  plugin wiring, helpers and shared Sass
    └── docs/                 how to request a new block
```

## License

GPL-2.0-or-later, see [LICENSE](LICENSE). Inter is distributed under the SIL Open Font License, see `themes/skt-starter/assets/fonts/inter/OFL.txt`.
