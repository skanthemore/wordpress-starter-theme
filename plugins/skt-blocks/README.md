# SKT Blocks

ACF PRO blocks that style themselves with the design tokens of the active theme.

## Blocks

| Block | Purpose | JavaScript |
| --- | --- | --- |
| `skt/hero` | Opening section with heading, text, button, responsive image and optional background video. | Starts the video after load |
| `skt/media-text` | Image next to an eyebrow, title, text and button. | — |
| `skt/rich-text` | Full-width text section with a button. | — |
| `skt/faq` | Accordion on native `<details>`, with optional FAQPage JSON-LD. | — |
| `skt/posts-slider` | Latest posts, optionally from one category. | Swiper, over a native scroll-snap fallback |
| `skt/heading` | Heading with independent HTML tag and visual size. | — |
| `skt/image` | Single image framed on a palette color. | — |

## Anatomy of a block

Every folder in `blocks/` that contains a `block.json` is registered automatically, and so is its field group.

```text
blocks/<slug>/
  block.json     metadata, assets and the ACF render template
  fields.php     returns the ACF field definitions as a plain array
  render.php     the markup
  style.scss     compiled to style.css by `npm run build`
  view.js        optional front-end script
```

`fields.php` returns fields without keys; the plugin derives stable keys from the block slug and the field name (`field_skt_<slug>_<name>`) and builds the field group from `block.json`.

## Conventions

- **Naming.** Block name `skt/<slug>`, BEM classes rooted at `.skt-<slug>`, text domain `skt-blocks`.
- **Tokens, never values.** Styles use the `--skt-*` custom properties printed by the theme. Palette-driven fields read their choices from `get_color_choices()`.
- **Sections.** Full-width blocks call `section_classes()` and list `skt-blocks-common` in their `block.json` styles. That shared stylesheet provides the padding and the light, surface, dark and primary color schemes.
- **Headings.** `heading_tag_field()` lets editors pick the semantic tag; the visual size never changes with it.
- **Buttons.** `button()` renders an ACF link with the theme's `.button` class and a safe `rel` for new tabs.
- **Empty states.** On the front end a block without its essential content renders nothing; in the editor it renders a placeholder.
- **Escaping.** Escape late: `esc_html()`, `esc_attr()`, `esc_url()`, `wp_kses_post()`, `tag_escape()`.
- **Shared assets.** Libraries are registered once in `src/plugin.php` and referenced by handle from `block.json`, so they only load on pages that use them.

To request a new block, start from [docs/new-block-brief.md](docs/new-block-brief.md).

## Build

```bash
npm install
npm run dev     # watch
npm run build   # production
npm run tokens  # resync breakpoints from the theme
```
