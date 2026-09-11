=== CirclePress ===
Contributors: circlepress
Requires at least: 6.0
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 2.1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Tags: blog, magazine, food, recipes, crafts, diy, beauty, home-decor, gardening, pets, seo, adsense, affiliate, lightweight, responsive, translation-ready

Lightweight SEO-first multi-niche magazine theme with 9 built-in niche designs,
each with its own style, homepage, single-post features and Schema.org markup.

== Description ==

CirclePress is built for niche bloggers who want speed + rankings + revenue:

* 9 niche presets: Food & Recipes, Crochet, Pets, Nails, Furniture, Home Decor,
  DIY & Crafts, Beauty, Gardening — switch from Customizer → Site Niche.
* Pro food-blog design: SVG icons, slide-in mobile drawer, fullscreen search,
  Pinterest Save buttons, WPRM-style recipe cards, editorial section headers.
* 14 page templates: niche homepages + full width + landing + gift guide + A-Z index.
* 4 homepage layouts (Magazine/Grid/List/Showcase) with per-page override.
* 6 Gutenberg block patterns for building custom homepages.
* Fully mobile-ready: touch submenus, scrollable tables, responsive videos.
* Conversion kit: auto next-post, listicles + ItemList schema, shop-the-look,
  coupon boxes, gift guides with filters.
* Engagement kit: sticky action bar, visitor ratings, bookmarks, text-to-speech,
  dark mode, font-size controls, Pinterest Pin-it.
* E-E-A-T: reviewer box + sources + reviewedBy schema; IndexNow instant indexing.
* Per-niche Schema.org JSON-LD: Recipe, HowTo, Product, Review, FAQPage,
  Article, BreadcrumbList, WebSite, Organization — no plugin needed.
* Ad-ready: 6 slots (header, below-title, in-content auto, sidebar, footer,
  sticky) compatible with AdSense, Ezoic and Mediavine.
* Affiliate-ready: product boxes, pros/cons, comparison tables, star ratings,
  auto disclosure, nofollow/sponsored links.
* GDPR consent banner with strict ad/embed blocking + Google Consent Mode v2.
* SEO built-in: meta descriptions, canonical, robots, Open Graph, Twitter cards,
  breadcrumbs, TOC, reading progress. Auto-disables when RankMath/Yoast is active.
* Small-hosting friendly: no jQuery on frontend, tiny vanilla JS, system fonts
  by default, lazy ads/iframes, emoji/oEmbed/heartbeat controls.

== Installation ==

1. Upload `circlepress.zip` via Appearance → Themes → Add New → Upload.
2. Activate the theme.
3. Go to Appearance → Customize → Site Niche and pick your niche.
4. Set a static homepage (Home + Blog pages) or use a "CirclePress Home" template.

== Frequently Asked Questions ==

= Do I need Elementor or Gutenberg blocks? =
No. The theme works with plain Gutenberg (6 block patterns included). Elementor also works (Full Width + Landing templates included).

= Will schema duplicate with RankMath/Yoast? =
No — CirclePress auto-detects major SEO plugins and steps back.

= How do I add AdSense? =
Customizer → Ads → paste your <ins> codes per slot. Same slots accept Ezoic placeholders and Mediavine.

= How do I get the Recipe/HowTo/Product cards? =
Edit any post → "CirclePress: Niche Details" box → fill the group you need. The card + schema appear automatically.

= How do I change the homepage layout? =
Customizer → Homepage → Layout shape (global), or per page via Page Options → Homepage layout.

= How do listicles/coupons/gift guides work? =
Use the shortcodes: [top_list][rank_item], [coupon], [gift_grid][gift_item]. Full examples in docs/AR-GUIDE.md.

= Is it GDPR compliant? =
Yes — enable the cookie banner (Customizer → Privacy & Consent). Strict mode blocks ads and video embeds until consent, and Google Consent Mode v2 signals are sent automatically. Remember to publish a Privacy Policy page.

== Changelog ==

= 2.1.0 =
* NEW LOOK: modern borderless cards (rounded image, kicker, serif title, stars) — no boxes, no stretched gaps.
* Wider columns with sidebar active (2-col instead of 3) so cards are never narrow/tall.
* Excerpts hidden on grid cards (kept on list rows); Save button always visible on touch.

= 2.0.2 =
* FIX: dark mode, font size (A+/A-) and consent buttons (Accept/Reject/Customize) now work — footer markup prints before scripts + JS waits for DOM ready.
* FIX: auto-load next post sentinel printed too late (same root cause).
* IMPROVED: font size buttons now scale the whole page instead of rem-only text.

= 2.0.1 =
* HOTFIX: mobile overflow — no more cut-off content on phones (shrinkable grids, wrapping recipe meta, contained ads/embeds, safe long words).

= 2.0.0 =
* PRO REDESIGN: SVG icon system replaces all UI emoji.
* NEW: slide-in mobile drawer menu with search + social + accordions.
* NEW: fullscreen search overlay.
* NEW: Pinterest Save buttons on cards, WPRM-style recipe card, editorial headers.
* NEW: mobile-first pass — scrollable action bar, stacked recipe/rank layouts.
* FIX: demo page responsive issues on small phones.

= 1.3.0 =
* NEW: auto-load next post (infinite reading, ads included).
* NEW: listicles [top_list][rank_item] with award badges + ItemList schema.
* NEW: shop-the-look hotspots, coupon boxes (copy + countdown), gift guides.
* NEW: sticky action bar (Jump/Video/Listen/Save/Print/Pin) + video box.
* NEW: visitor star ratings merged into stars + schema.
* NEW: bookmarks + [saved_posts] page, text-to-speech, dark mode, font size.
* NEW: Pinterest Pin-it on images, E-E-A-T reviewer/sources, IndexNow.
* NEW: A-Z index + gift guide page templates.
* FIX: anchor offset under sticky header, strict-mode cache backup, LCP fetchpriority.

= 1.2.0 =
* NEW: 4 homepage layouts (Magazine/Grid/List/Showcase) — global + per-page.
* NEW: 6 Gutenberg block patterns (CirclePress category).
* NEW: full mobile layer — touch submenus, scrollable tables, responsive videos, safe-area, 360px support.

= 1.1.0 =
* NEW: GDPR cookie consent banner (bar/card styles, Accept/Reject/Customize).
* NEW: Strict mode blocks ad slots + YouTube/Vimeo embeds until marketing consent.
* NEW: Click-to-load video facades (faster + privacy-friendly).
* NEW: Google Consent Mode v2 integration for AdSense/Analytics.
* NEW: [cookie_settings] shortcode + floating settings button.

= 1.0.0 =
* Initial release: 9 niches, 12 templates, schema suite, ad system, affiliate tools.
