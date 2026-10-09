# Landing Page Design Brief

Used by slice 12. The home page is the main sales asset, so it gets the one distinctive idea in the whole product.

## The idea: the Journey Rail

Every competitor consultancy site is a hero followed by a grid of cards. This one is built around a single continuous navy line that runs down the page and draws itself as the visitor scrolls. The five real steps of the client's process dock onto that line:

**Dream → Counselling → Select University → Apply → Get Admission**

The rail is one SVG path, 2 px, navy `#0B2C5C`, animated with `stroke-dasharray` tied to scroll progress from 0 to 100 %. Each step sits in an alternating left/right position with an orange node that fills when it enters the viewport. On mobile the rail moves to a 24 px left gutter and all steps stack to its right. The rail is decorative, so it carries `aria-hidden="true"` and each step remains a normal heading and paragraph in the document order.

## Section order

1. **Hero.** Two columns. Left: eyebrow "100% FREE Counselling", headline in Instrument Serif at up to 72 px, "Your Dream to Study Abroad — Just Click & Achieve It", sub in Inter "Find The Right Path with Global Consultancy!", then two calls to action as liquid-glass pill buttons, "Book Free Counselling" (primary) and "Find Your Course". Right: a photograph of students on a UK campus, with destination flags drifting at 0.5× scroll speed and two floating glass cards reading "Visa Approved" and "Offer Received". An orange bar beneath carries the phone numbers, matching the client's own Facebook banner.
2. **Trust strip.** Four figures that count up once on first view: 1000+ students placed, 50+ partner universities, 15+ countries, 19K followers.
3. **Journey Rail.** The five steps described above.
4. **Destinations.** Six cards for UK, USA, Canada, Australia, Europe and Malaysia. Each shows a flag, a photograph and a live course count from the database. Hover lifts the card 4 px and scales the image to 1.05.
5. **Course finder.** A sticky glass filter bar: subject, level, country, intake, tuition, IELTS. It runs a real query and links into the full finder page.
6. **Services.** Six of the twelve services, each a card with a thin orange top border appearing on hover.
7. **Partner universities.** A logo marquee looping every 30 seconds, grey until hovered, paused on hover and on focus. Missing logos fall back to a navy circle with the university's initials.
8. **Visa success stories.** Three cards in the client's own format, flag, student photograph and the Bengali congratulation line, for example "🇬🇧 UK STUDENT VISA SUCCESS | অভিনন্দন TANIYA AZAD! 🎉". Content comes from the testimonials table, never hard-coded.
9. **Closing call to action.** Navy background with a blurred orange blob that drifts with the pointer.
10. **Footer.** Four columns: company, destinations, services, contact. Both offices (London, Suite 3, LMC Business Wing, 38–44 Whitechapel Road, and Khulna, House 52, Alingon Road 5, Nirala), all three phone numbers, the email address and the social links.

## Tokens

| Token | Value |
|---|---|
| Navy | `#0B2C5C` |
| Orange | `#FF8C00` |
| Ink | `#0F172A` |
| Background | `#F8FAFC` |
| Muted | `#64748B` |
| Border | `#E2E8F0` |
| Radius | 12 px |
| Shadow | `0 8px 32px rgba(11,44,92,.08)` |

Inter 400–500 for body at 14–16 px with relaxed leading. Instrument Serif 600–700 for headings with tight tracking and line height 0.9–1.1.

## Glass usage

WebGL glass is limited to three places: the two hero buttons, the two floating hero cards and the sticky course finder bar. Everything else uses `bg-white/70 backdrop-blur-xl border border-white/40`. Follow the `liquid-glass` skill. Load the glass scripts only from the public Vite entry, never in the CRM or portal bundles.

## Motion

Fade-rise of 16 px over 0.7 s on `cubic-bezier(.16,1,.3,1)`, staggered 0.06–0.12 s between siblings. Counters ease out over 1.2 s. Flags parallax at 0.5×. Buttons scale to 0.98 when pressed and carry a 0.6 s shine sweep. All of it stops under `prefers-reduced-motion: reduce`, where the rail renders fully drawn and counters show their final value immediately.

## Budget and quality gates

- Lighthouse mobile 90 or above on the home page and the course finder.
- Cumulative layout shift below 0.1. The hero image is preloaded as AVIF with a WebP fallback and explicit width and height.
- Home page JavaScript under 150 KB compressed.
- Works with WebGL unavailable: the glass elements fall back to blurred CSS panels.
- AA contrast throughout. A 2 px orange focus ring with offset. Glass buttons stay real `<a>` or `<button>` elements. The finder is fully keyboard operable.
- Checked at 375, 768 and 1440 px.
