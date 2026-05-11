# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

```bash
npm run dev      # Start dev server at localhost:3000
npm run build    # Production build (test before pushing)
npm run lint     # ESLint check
git push origin main  # Triggers Netlify auto-deploy
```

## Architecture

**Next.js 16 App Router** — all pages under `app/`, all components under `components/`.

```
app/
  layout.js                  — root layout: Inter font, Navbar, paddingTop 60px
  page.js                    — homepage: stacks all home/* sections
  pricing/                   — pricing page (client, fetches WooCommerce via API route)
  get-started/               — signup form + plan selection
  consultant-solutions/      — consultant landing page
  api/pricing/route.js       — server route: fetches live prices from WooCommerce

components/
  home/                      — homepage sections (HeroSection, AboutSection, etc.)
  layout/Navbar.js
  ui/                        — shared UI primitives

lib/woocommerce.js           — WooCommerce REST API helpers (getProduct, getVariation)
hooks/useReveal.js           — IntersectionObserver scroll-reveal hook
```

**Data flow**: Pricing page fetches `/api/pricing` which calls WooCommerce via `lib/woocommerce.js`. Prices are cached server-side (`revalidate: 3600`). Static fallback prices are hardcoded in `get-started/page.js` with a comment marking where to update them.

## HeroSection internals

The most complex component. Key parts:
- `WorldMapInteractive` — Europe puzzle map via `react-simple-maps`. 30 EU countries in `COUNTRY_DATA[]` (name, iso2, color). Default fill = diagonal SVG hatch pattern; hover fill = vivid gradient (both defined as `<defs>` in the SVG).
- Click-to-zoom uses `d3-geo` `geoCentroid`/`geoBounds`. Countries with area > 1500 (Russia) skip zoom.
- Pan clamped to `lon[-28,50]` `lat[30,73]` in `onMoveEnd`.
- SVG z-order managed by sorting the geographies array so hovered country renders last.
- 2-layer wave SVG (right edge decoration): wrapped in `overflow:hidden` container (`width:18%`, right-anchored). Layer 1 sits behind, layer 2 offset `top:20% left:20%` inside the container. Both play-once entrance animations.

## Styling conventions

- Inline styles throughout — no CSS modules.
- Tailwind 4 used for utility classes where convenient.
- All interactive/animated components have `"use client"` at the top.
- Mouse parallax pattern: `useState({x,y})` + `onMouseMove` on wrapper div, then `translate(${mouse.x * N}px, ${mouse.y * N}px)` on ornament layers.
- Scroll reveal: `useReveal()` hook returns `{ ref, visible }`. Apply `revealStyle(visible)` for fade-in.

## Brand tokens
- Primary: `#e8431a`
- Light tint bg: `#fff5f2`
- Subtle border: `rgba(232,67,26,0.2)`
- Spring animation easing: `cubic-bezier(0.22,1,0.36,1)`

## Deployment

Netlify auto-deploys from `main`. Build: `npm install && next build`, Node 20.

**⚠️ Do not remove `.npmrc`** — it contains `legacy-peer-deps=true` which is required because `react-simple-maps@3.0.0` declares peer support only for React 16–18 but this project uses React 19. Removing it breaks Netlify's `npm install`.

## Environment variables

Required in `.env.local` (not committed):
- `NEXT_PUBLIC_WC_URL` — WooCommerce store URL
- `WC_CONSUMER_KEY` / `WC_CONSUMER_SECRET` — WooCommerce REST API credentials
