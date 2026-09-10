# ⭕ CirclePress — Multi-Niche WordPress Theme

Lightweight, SEO-first magazine theme with **9 built-in niche designs**.
Each niche has its own **style, homepage template, single-post features and Schema.org markup**.

📖 **الدليل الكامل بالعربية:** [docs/AR-GUIDE.md](docs/AR-GUIDE.md)
👀 **Live styles preview:** open `demo/index.html` (or run a static server — see below)

## Niches

| Niche | Hero style | Schema |
|---|---|---|
| 🍲 Food & Recipes | magazine | Recipe + FAQ |
| 🧶 Crochet | split | HowTo + CreativeWork |
| 🐾 Pets | bold + search | Article + FAQ + Product |
| 💅 Nails | magazine | HowTo + ImageObject |
| 🛋️ Furniture | split | Product + Review |
| 🏡 Home Decor | magazine | Article + HowTo |
| 🛠️ DIY & Crafts | bold + search | HowTo + FAQ |
| 💄 Beauty | split | Product + Review + HowTo |
| 🌱 Gardening | magazine | HowTo + Article |

## Install

1. Upload `circlepress.zip` via **Appearance → Themes → Add New → Upload**.
2. Activate, then go to **Customizer → 🌐 Site Niche** and pick your niche.
3. Create **Home** (template `CirclePress Home — …`) + **Blog** pages, set them in **Settings → Reading**.

## Highlights

- **12 page templates** — one homepage per niche + full width + landing.
- **6 ad slots** (header, below-title, in-content auto, sidebar, footer, sticky) — AdSense / Ezoic / Mediavine ready.
- **Affiliate toolkit** — product boxes, pros/cons, comparison tables, ratings, auto disclosure, nofollow/sponsored.
- **Built-in SEO** — meta, canonical, OG/Twitter, breadcrumbs, TOC, reading progress; auto-defers to RankMath/Yoast.
- **Small-hosting friendly** — no jQuery, ~4KB vanilla JS, system fonts by default, lazy ads/iframes.
- **GDPR consent** — cookie banner (bar/card), strict ad+video blocking, Consent Mode v2, `[cookie_settings]`.
- **Shortcodes** — `[recipe_card]` `[howto]` `[product_box]` `[pros_cons]` `[star_rating]` `[faq]` `[cta_button]` `[compare]` `[disclosure]` `[circlepress_ad]` `[cookie_settings]`

## Preview the styles (no WordPress needed)

```bash
cd /home/user/Circlepress
python3 -m http.server 8000 --bind 0.0.0.0
# open http://localhost:8000/demo/
```

## Build the installable ZIP

```bash
cd circlepress && zip -r ../circlepress.zip . -x "*.DS_Store*"
```

## Structure

```
circlepress/
├── style.css, rtl.css, screenshot.png, readme.txt
├── functions.php, header.php, footer.php, sidebar.php, comments.php
├── index.php, front-page.php, home.php, single.php, page.php, archive.php, search.php, 404.php
├── inc/            # setup, niches, customizer, seo, schema, ads, meta-boxes, shortcodes, template-tags, performance
├── template-parts/ # components, home sections, content
├── page-templates/ # 9 niche homes + generic home + fullwidth + landing
├── assets/         # css/editor.css, js/main.js (vanilla)
└── languages/      # circlepress.pot
```
