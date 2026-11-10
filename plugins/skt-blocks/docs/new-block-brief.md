# New block brief

Fill this in before building a block. It is written for people and for coding assistants alike: a complete brief is enough to scaffold the first version without guessing.

## 1. Name

- Human name:
- Slug (`skt/<slug>`):

## 2. Purpose

What content problem does the block solve, and where will it be used?

## 3. Reference

A wireframe, screenshot or sketch. Without one, describe the structure from top to bottom.

## 4. Fields

One line per field: label, name, ACF type, required or optional, notes.

```text
Title   | title     | text      | required |
Text    | text      | wysiwyg   | optional | SKT toolbar
Button  | button    | link      | optional |
Scheme  | scheme    | button_group | optional | scheme_field()
```

## 5. Behaviour

- Static or interactive? If interactive, what exactly does `view.js` do?
- Does it work, in a reduced form, without JavaScript?

## 6. Responsive behaviour

What changes between mobile, tablet and desktop: stacking, order, visibility, sizes.

## 7. Variants and states (optional)

Color schemes, media position, compact spacing, highlighted state.

## 8. Content rules (optional)

- What is the minimum content for the block to render?
- Can the image or the button be omitted?

## 9. Accessibility (optional)

Heading level, ARIA, keyboard interaction, motion.

---

## Definition of done

- [ ] `block.json`, `fields.php`, `render.php` and `style.scss` follow the conventions in the plugin README.
- [ ] Styles only use `--skt-*` tokens.
- [ ] Renders nothing on the front end without its essential content, and a placeholder in the editor.
- [ ] Usable with the keyboard, readable by screen readers, respects `prefers-reduced-motion`.
- [ ] `composer lint` passes.
