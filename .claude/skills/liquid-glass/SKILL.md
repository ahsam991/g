---
name: liquid-glass
description: Use when building Apple Liquid Glass-style WebGL glass buttons or containers on the Global Consultancy public website (home hero CTAs, floating "Visa Approved" / "Offer Received" cards, sticky course finder bar). Covers the vendored liquid-glass-js library in resources/vendor/liquid-glass, its API, load order, fallbacks and where it must not be used.
---

# Liquid Glass (liquid-glass-js)

Vendored at `resources/vendor/liquid-glass/` (source dashersw/liquid-glass-js, commit 78cb6cc, MIT).

## Where to use it
- Public site only: home hero CTAs, floating glass cards, course finder bar, CTA section.
- Never in the CRM or candidate portal. It costs WebGL plus html2canvas, and the spec caps JS at about 150 KB.
- At most a few instances per page.

## Load order
1. html2canvas 1.4.1 (npm `html2canvas@1.4.1`, or `https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js`).
2. `container.js`, then `button.js`. They define global `Container` and `Button` classes.
3. `glass.css`.

Once Laravel exists (build-plan slice 12), add these to a separate Vite entry used only by the public layout. Do not add them to the CRM or portal bundles.

## API
```js
const bar = new Container({ borderRadius: 24, type: 'pill', tintOpacity: 0.3 }) // type: rounded | circle | pill
const cta = new Button({
  text: 'Book Free Counselling',
  size: 20,
  type: 'pill',
  tintOpacity: 0.3,
  warp: false,
  onClick: () => (window.location.href = '/contact#book'),
})
bar.addChild(cta)          // nested glass samples the parent
document.querySelector('#hero-cta').appendChild(bar.element)
bar.updateSizeFromDOM()    // call after layout changes
```
`removeChild(child)` is also available.

## Required guards
- Feature-detect WebGL. If it is missing, or `prefers-reduced-motion: reduce` matches, render the plain Tailwind version: `bg-white/70 backdrop-blur-xl rounded-xl border border-white/40`.
- Glass buttons must still be real links or buttons for keyboard users. Keep the orange focus ring and AA contrast from the design system.
- Brand tints: navy `#0B2C5C`, orange `#FF8C00`. Text on glass stays `#0F172A` or white with enough contrast.
- Test at 375, 768 and 1440 px and keep Lighthouse mobile at 90 or above.
