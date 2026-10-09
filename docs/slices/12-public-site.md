# Slice 12: Public site and CMS

**Tables:** pages, menus, blog_categories, blog_posts, faqs, testimonials, team_members, banners, forms, form_submissions

## Build
- Pages: home, about, services (12), destinations, universities, course finder (filters, compare 3, eligibility GPA+IELTS+budget, wishlist), admission requirements, scholarships, process, contact + booking, apply online, blog, visa stories, privacy, terms.
- Real content from spec §1 and §16 (tagline, offices, phones, 19K followers, visa-story format).
- **Read `docs/DESIGN-LANDING.md` first.** It defines the Journey Rail concept, section order, tokens, motion and the performance budget for the home page.
- Glass limited to hero CTAs, the two floating hero cards and the sticky finder bar, via the `liquid-glass` skill, public Vite entry only.
- SEO: title/meta/canonical/OG, JSON-LD (Organization, EducationalOrganization), sitemap.xml, robots.txt, cookie banner.
- Motion rules from spec §8, all disabled under prefers-reduced-motion.

## Done when
Lighthouse mobile ≥ 90 on home and course finder; JS < 150 KB on home; enquiry form creates a lead.
