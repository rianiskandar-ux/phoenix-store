---
name: Phoenix Whistleblowing Software
description: Trusted. Precise. Humane. Compliance reporting built to protect people.
colors:
  primary: "#e8431a"
  primary-tint: "#fff5f2"
  primary-glow: "#fde8e2"
  warm-sand: "#faf3ec"
  surface-cool: "#f8f9fb"
  surface-white: "#ffffff"
  surface-icon: "#f2ece3"
  text-strong: "#111111"
  text-body: "#555555"
  text-muted: "#888888"
  text-faint: "#aaaaaa"
  divider: "#f0f0f0"
  dot-accent: "#d4b89a"
typography:
  display:
    fontFamily: "Inter, system-ui, sans-serif"
    fontSize: "clamp(2rem, 4vw, 3rem)"
    fontWeight: 900
    lineHeight: 1.2
    letterSpacing: "normal"
  headline:
    fontFamily: "Inter, system-ui, sans-serif"
    fontSize: "clamp(1.5rem, 3vw, 2rem)"
    fontWeight: 900
    lineHeight: 1.2
  title:
    fontFamily: "Inter, system-ui, sans-serif"
    fontSize: "1.2rem"
    fontWeight: 800
    lineHeight: 1.3
  body:
    fontFamily: "Inter, system-ui, sans-serif"
    fontSize: "1rem"
    fontWeight: 400
    lineHeight: 1.85
  label:
    fontFamily: "Inter, system-ui, sans-serif"
    fontSize: "0.72rem"
    fontWeight: 700
    letterSpacing: "0.07em"
rounded:
  pill: "100px"
  lg: "24px"
  card: "16px"
  md: "8px"
  icon: "10px"
  sm: "6px"
spacing:
  section-y: "80px"
  container: "1200px"
  card-gap: "16px"
  card-pad: "28px"
  card-pad-lg: "64px"
components:
  button-primary:
    backgroundColor: "{colors.primary}"
    textColor: "#ffffff"
    rounded: "{rounded.md}"
    padding: "14px 32px"
  button-primary-hover:
    backgroundColor: "#c93614"
    textColor: "#ffffff"
    rounded: "{rounded.md}"
    padding: "14px 32px"
  button-ghost:
    backgroundColor: "transparent"
    textColor: "rgba(255,255,255,0.85)"
    rounded: "{rounded.md}"
    padding: "14px 32px"
  badge-pill:
    backgroundColor: "{colors.primary-tint}"
    textColor: "{colors.primary}"
    rounded: "{rounded.pill}"
    padding: "5px 14px"
  card-feature:
    backgroundColor: "{colors.surface-white}"
    rounded: "{rounded.card}"
    padding: "{spacing.card-pad}"
  card-pricing-popular:
    backgroundColor: "#fff4f1"
    textColor: "{colors.text-strong}"
    rounded: "20px"
    padding: "{spacing.card-pad}"
---

# Design System: Phoenix Whistleblowing Software

## 1. Overview

**Creative North Star: "The Calm Witness"**

Phoenix sits at an unusual intersection: it must simultaneously convince a risk-aware Compliance Officer that this vendor is credible and approved-by-legal, and assure a frightened employee that pressing "submit" is safe. Most compliance tools resolve this tension by going corporate-cold (IBM, SAP). Phoenix resolves it differently: Stripe-level precision in layout and typography, with enough warmth in color and spacing that neither audience feels like they wandered into the wrong product.

The system is light-themed — a HR manager reviewing the site at their desk at 9am, browser tab open between a legal brief and a vendor shortlist, evaluating on trust signals, not on visual drama. Dark surfaces appear only in high-conviction moments (the CTA band), never as ambient atmosphere.

Density is medium-low. Generous padding, clear hierarchy, no information overload. The interface earns trust by doing less, not more. Features are named plainly. Numbers (50+ languages, 256-bit encryption) are stated without superlatives.

**Key Characteristics:**
- Single warm accent (Phoenix orange, `#e8431a`) carrying all interactive intent
- Dual surface palette: warm sand for content-rich sections, cool grey for structural/chrome areas
- Heavy weight (800–900) headings contrasted against 400-weight body for sharp hierarchy
- Subtle shadows that lift on interaction, flat at rest
- Badge-pill labels as section punctuation throughout
- Spring easing (`cubic-bezier(0.22,1,0.36,1)`) for reveals; no bouncing, no elastic

## 2. Colors: The Ember and Linen Palette

One accent, two surface registers. The orange earns attention exactly where it's needed; everywhere else, warm neutrals do the work.

### Primary
- **Phoenix Orange** (`#e8431a`): The single interactive voice. Used on CTAs, active nav states, badge dots, pricing highlights, and key emphasis spans in headings. Appears as a glow shadow (`0 6px 24px rgba(232,67,26,0.4)`) behind primary buttons. On dark surfaces, it intensifies rather than lightens.

### Neutral
- **Warm Sand** (`#faf3ec`): Section background for content-heavy areas (About, features). Tinted toward amber rather than grey; distinguishes these sections from chrome areas without visual noise.
- **Cool Whisper** (`#f8f9fb`): Structural surface. Used for page chrome, pricing page background, comparison table base. Cooler than warm sand, signals "interface" rather than "content."
- **Pure Canvas** (`#ffffff`): Card backgrounds. White against warm sand reads as lifted; white against cool whisper reads as flush.
- **Phoenix Tint** (`#fff5f2`): The lightest accent application. Used in badge pill backgrounds, section tag chips. Keeps the orange identity present at near-zero saturation.
- **Peach Glow** (`#fde8e2`): Hero gradient anchor. Used in warm section hero backgrounds alongside soft purples and ambers; never used flat.
- **Icon Bone** (`#f2ece3`): Icon container background. Warm beige that complements the sand backgrounds while distinguishing the icon tray from the card.
- **Near-Black** (`#111111`): Heading text. Not pure black; retains slight warmth. Used for all display and headline copy.
- **Body Stone** (`#555555`): Primary body text. Readable, not harsh.
- **Muted Slate** (`#888888`): Supporting copy, card taglines, metadata.
- **Faint** (`#aaaaaa`): Tertiary text, price notes, plan metadata.
- **Divider** (`#f0f0f0`): Borders and separators. At this lightness it reads as structural without competing.
- **Dot Amber** (`#d4b89a`): Radial dot grid pattern used as ornamental background texture in warm sections.

**The One Voice Rule.** Phoenix Orange (`#e8431a`) is the only color that signals action, emphasis, or brand identity. It is never used decoratively — no orange borders on non-interactive elements, no orange gradients behind text, no ambient orange fills that aren't tied to state or intent. Its scarcity is the point. When it appears, users trust it.

**The No-Dark-Surface Rule.** Dark surfaces (near-black through charcoal) appear only in the CTA band gradient. No dark section backgrounds, no dark cards, no dark nav. Phoenix is a daytime product used by professionals in lit offices. The dark moment is deliberate contrast, not ambient palette.

## 3. Typography

**Display/Body Font:** Inter (Google Fonts, latin subset)
**Fallback stack:** system-ui, sans-serif

**Character:** A single-family system at two extreme weight poles. Inter at 900 in display reads authoritative and modern; Inter at 400 in body reads clear and approachable. The gap between them is wide enough that hierarchy is never ambiguous. No serif accent, no display font switch — Phoenix earns personality from weight and spacing, not typeface variety.

### Hierarchy
- **Display** (900, `clamp(2rem, 4vw, 3rem)`, line-height 1.2): Hero headlines. Page titles. Single per section.
- **Headline** (900, `clamp(1.5rem, 3vw, 2rem)`, line-height 1.2): Section headers, comparison table headings, pricing page title.
- **Title** (800, `1.2rem`, line-height 1.3): Card titles in modal, feature card headings, plan names in pricing cards at `2.2rem`/900 for price numerals.
- **Body** (400, `1rem`, line-height 1.85): Section prose. Cap at 65–75ch for comfortable reading. Color `#555555`.
- **Label** (700, `0.72rem`, letter-spacing `0.07em`, uppercase): Badge pills, section category tags, table column headers, pricing plan tier names. The uppercase treatment is reserved for this role only.

**The Weight-Only Hierarchy Rule.** Size and weight carry the typographic hierarchy. Never italic, never underline (outside hyperlinks), never color-shift body copy to indicate importance — use weight (800) for emphasis within running text instead.

## 4. Elevation

Phoenix uses a lift-on-interaction model. Surfaces are flat at rest; depth appears as a response to state (hover, focus, elevation tier). There is no ambient elevation baseline above zero.

### Shadow Vocabulary
- **Ambient Rest** (`0 1px 6px rgba(0,0,0,0.07)`): Feature cards at rest. Nearly imperceptible — signals card boundary without lifting off the surface.
- **Hover Lift** (`0 8px 28px rgba(0,0,0,0.1)`): Feature cards and interactive containers on hover. The card rises 3px (`translateY(-3px)`) in concert with this shadow change.
- **Standard Card** (`0 2px 16px rgba(0,0,0,0.06)`): Pricing cards at rest. Slightly more depth than ambient rest to establish the card tray.
- **Brand Glow** (`0 6px 24px rgba(232,67,26,0.4)`): Primary CTA buttons only. The only colored shadow in the system. Keeps the button visually separated from dark backgrounds in the CTA band.
- **Premium Glow** (`0 20px 60px rgba(232,67,26,0.15)`): Popular pricing card. Wider, softer orange glow that halos the promoted plan.
- **Modal Deep** (`0 24px 80px rgba(0,0,0,0.15)`): Modal dialog. The deepest shadow in the system; used once, for the highest-elevation surface.
- **Nav Ambient** (`0 1px 3px rgba(0,0,0,0.06)`): Fixed navbar. Anchors the nav to the top without making it feel heavy.

**The Flat-By-Default Rule.** Shadows appear on state change, not at rest (with the exception of the navbar, which requires persistent separation). Hover and modal are the two states that summon shadow. Everything else is flat.

## 5. Components

### Buttons
- **Shape:** Gently rounded edges (8px radius); not pill, not sharp.
- **Primary:** Phoenix Orange (`#e8431a`) fill, white text, `14px 32px` padding, weight 700, font-size 0.875rem. Brand glow shadow. Hover darkens to `#c93614`.
- **Ghost (on dark):** Transparent fill, `1.5px solid rgba(255,255,255,0.18)` border, white text at 85% opacity. Used in CTA band alongside primary. Hover: border and text brightens.
- **Ghost (on light):** Transparent fill, `1.5px solid #e8431a` border, Phoenix Orange text. Used in pricing cards for non-popular plans.
- **Focus:** All buttons require visible `:focus-visible` ring. Use `outline: 2px solid #e8431a; outline-offset: 2px`.

### Badge Pills
- **Section tags:** Phoenix Tint (`#fff5f2`) background, `1px solid rgba(232,67,26,0.2)` border, 100px radius, `5px 14px` padding. Contains a 6px orange dot + uppercase label text. Used at top of every section to signal its category.
- **On dark surfaces:** `rgba(232,67,26,0.15)` background, `1px solid rgba(232,67,26,0.3)` border. Keeps orange identity readable on the CTA gradient.

### Cards / Containers
- **Feature Cards:** White background, 16px radius, `28px 24px` padding, ambient rest shadow. Lift on hover (`translateY(-3px)` + hover shadow). Internal layout: title + tagline top, icon tray bottom-left. Arrow indicator top-right reveals orange on hover.
- **Pricing Cards:** White background, 20px radius, 22–28px padding, standard card shadow. Popular variant: soft pink gradient (`#fff4f1` to `#fde8f0`), `2px solid #e8431a` border, premium glow shadow, `-12px` vertical offset to visually elevate it above peers.
- **CTA Band:** Near-black to Phoenix Orange gradient (`143deg`), 24px radius, `64px` padding. The only full-bleed dark container in the system.
- **Modal:** White, 18px radius, `40px` padding, modal deep shadow, `blur(4px)` backdrop.

### Navigation
- **Style:** White background, `1px solid #f0f0f0` bottom border, nav ambient shadow. Fixed at top, 60px height.
- **Links:** Inter 13.5px, weight 400 at rest / 600 active. Color `#444` rest, `#e8431a` active and hover. Transition 0.15s.
- **Mobile:** Hamburger (3-line, animated to X on open). Mobile menu drops below nav with the same white background and row dividers.

### Section Dividers (Signature Component)
Two types used as transitions between sections:
- **SVG Wave:** A smooth `path` wave rendered via inline SVG (`viewBox="0 0 1440 60"`), filling the incoming section's background color. Used at the bottom of the hero to transition into content.
- **Image Wave:** `wave.png` at 100% width, 80px height, used at the bottom of the About section (warm sand) to transition into the workflow orange band.

## 6. Do's and Don'ts

### Do:
- **Do** use Phoenix Orange (`#e8431a`) only on interactive elements, emphasis spans, and brand markers. Its scarcity signals action; diluting it with decorative uses destroys that signal.
- **Do** use weight (800–900) for emphasis within body copy. Never use color alone to denote importance.
- **Do** cap body prose at 65–75ch. Long lines erode the calm, readable character the system depends on.
- **Do** pair the warm sand surface (`#faf3ec`) with content-heavy sections and cool whisper (`#f8f9fb`) with structural/chrome sections. The thermal contrast creates rhythm without color.
- **Do** lift cards on hover with `translateY(-3px)` and a matching shadow upgrade. The motion is the affordance; the shadow alone is not enough.
- **Do** open every section with a badge-pill label in Inter label style. This is the structural punctuation of the system.
- **Do** apply `cubic-bezier(0.22,1,0.36,1)` (spring easing) on entrance animations and `ease-out` variants for state transitions.

### Don't:
- **Don't** make the interface corporate-boring. IBM grey, SAP blue-grey, dense information hierarchies without breathing room — these are the aesthetic the system explicitly rejects.
- **Don't** use dark mode, dark section backgrounds, or dark ambient surfaces. The CTA band is the single dark moment in the system; it works because it's isolated. Spreading dark surfaces makes Phoenix feel like a cybersecurity tool.
- **Don't** use purple gradients, neon accents, or glassmorphism cards. Generic SaaS-2023 aesthetics (purple-to-pink gradient, frosted glass overlays, Notion/Webflow template vibes) are explicitly out of scope and make Phoenix indistinguishable.
- **Don't** reference regulation as the opening hook in any headline. The EU Directive is context, not the value proposition. Lead with relief ("your platform, live in 2 hours") not obligation ("required for 50+ employee companies").
- **Don't** use `border-left` greater than 1px as an accent stripe on cards or callouts. The system uses full borders, background tints, or icon/number leads instead.
- **Don't** apply `background-clip: text` with a gradient. All text uses a single solid color; weight and size carry emphasis.
- **Don't** use bounce or elastic easing. Only ease-out curves and the project's spring easing (`cubic-bezier(0.22,1,0.36,1)`) are permitted.
- **Don't** use heavy legalese layout or dense government-portal typography. Phoenix handles compliance without feeling like a compliance document.
