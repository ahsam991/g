<x-layouts.public title="Study Abroad Consultancy" description="100% FREE education counselling and application processing. Dream to study abroad? Find the right path with Global Consultancy.">
    {{-- HERO with animated text and floating cards --}}
    <section class="relative overflow-hidden bg-gradient-to-b from-canvas to-white pt-24 pb-14 sm:pt-32 sm:pb-20">
        {{-- Decorative blobs --}}
        <div class="pointer-events-none absolute -right-32 -top-24 h-96 w-96 rounded-full bg-orange/10 blur-3xl animate-pulse-slow"></div>
        <div class="pointer-events-none absolute -left-40 bottom-0 h-80 w-80 rounded-full bg-navy/5 blur-3xl"></div>

        <div class="mx-auto grid max-w-7xl items-center gap-10 px-4 sm:px-6 lg:grid-cols-2 lg:gap-12">
            <div x-data x-intersect.once="$el.classList.add('animate-rise')" class="opacity-0">
                <span class="inline-flex items-center gap-2 rounded-full bg-orange/10 px-4 py-1.5 text-sm font-medium text-orange">
                    <span class="relative flex h-2 w-2">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-orange opacity-75"></span>
                        <span class="relative inline-flex h-2 w-2 rounded-full bg-orange"></span>
                    </span>
                    100% FREE Counselling &amp; Application Processing
                </span>
                <h1 class="mt-4 font-display text-3xl leading-[1.1] text-navy sm:mt-5 sm:text-5xl lg:text-6xl xl:text-[72px]">
                    Dream to Study Abroad? <span class="italic text-orange">Just Click &amp; Achieve It</span>
                </h1>
                <p class="mt-4 max-w-lg text-base text-muted sm:mt-5 sm:text-lg">{{ settings('company.subtagline', 'Find The Right Path with Global Consultancy!') }}</p>
                <p class="mt-2 inline-flex items-center gap-2 rounded-lg bg-success/10 px-3 py-1.5 text-sm font-medium text-success">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>
                    Admission Open for January Intake - 2026
                </p>
                <div class="mt-6 flex flex-col gap-3 sm:mt-8 sm:flex-row sm:gap-4">
                    <a href="{{ route('contact') }}" class="group relative overflow-hidden rounded-full bg-navy px-7 py-3.5 text-center font-semibold text-white shadow-glass transition-all duration-300 hover:bg-navy-700 hover:shadow-lg active:scale-[.98]">
                        <span class="relative z-10">Book Free Counselling</span>
                        <span class="absolute inset-0 -translate-x-full bg-gradient-to-r from-transparent via-white/20 to-transparent transition-transform duration-700 group-hover:translate-x-full"></span>
                    </a>
                    <a href="{{ route('courses') }}" class="rounded-full border border-navy/20 bg-white px-7 py-3.5 text-center font-semibold text-navy transition-all duration-300 hover:border-orange hover:text-orange active:scale-[.98]">Check Eligibility</a>
                </div>
            </div>

            <div x-data x-intersect.once="$el.classList.add('animate-rise')" class="relative opacity-0 [animation-delay:150ms]">
                <div class="aspect-[4/5] max-h-[420px] overflow-hidden rounded-3xl bg-gradient-to-br from-navy to-navy-900 shadow-glass sm:max-h-none">
                    <div class="flex h-full flex-col items-center justify-center p-6 text-center text-white/90 sm:p-8">
                        <div class="relative">
                            <svg class="h-16 w-16 text-orange sm:h-20 sm:w-20 animate-float" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/></svg>
                        </div>
                        <p class="mt-4 font-display text-xl sm:mt-6 sm:text-2xl">Pathway to<br>International Education</p>
                        <p class="mt-3 text-sm text-white/60">Transforming Futures Since Day One</p>
                    </div>
                </div>
                {{-- Floating glass cards with bounce animation --}}
                <div class="absolute -left-2 top-12 hidden rounded-2xl border border-white/40 bg-white/70 px-4 py-3 shadow-glass backdrop-blur-xl sm:block sm:-left-4 sm:top-16 sm:px-5 sm:py-4 animate-float-left">
                    <div class="flex items-center gap-3">
                        <span class="grid h-9 w-9 place-items-center rounded-full bg-success/15 text-success sm:h-10 sm:w-10">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/></svg>
                        </span>
                        <div><p class="text-xs text-muted">Visa Approved</p><p class="text-sm font-semibold text-navy sm:text-base">UK Student Visa</p></div>
                    </div>
                </div>
                <div class="absolute -right-2 bottom-12 hidden rounded-2xl border border-white/40 bg-white/70 px-4 py-3 shadow-glass backdrop-blur-xl sm:block sm:-right-4 sm:bottom-16 sm:px-5 sm:py-4 animate-float-right">
                    <div class="flex items-center gap-3">
                        <span class="grid h-9 w-9 place-items-center rounded-full bg-orange/15 text-orange sm:h-10 sm:w-10">🎓</span>
                        <div><p class="text-xs text-muted">Offer Received</p><p class="text-sm font-semibold text-navy sm:text-base">Conditional Offer</p></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- TRUST STRIP — animated counters --}}
    <section class="border-y border-line bg-white py-8 sm:py-10" x-data="counterStrip()" x-intersect.once="start()">
        <div class="mx-auto grid max-w-5xl grid-cols-2 gap-6 px-4 sm:grid-cols-4 sm:px-6">
            <div class="text-center">
                <p class="font-display text-3xl text-navy sm:text-4xl" x-text="students + '+'">0+</p>
                <p class="mt-1 text-xs font-medium uppercase tracking-wide text-muted">Students Placed</p>
            </div>
            <div class="text-center">
                <p class="font-display text-3xl text-navy sm:text-4xl" x-text="universities + '+'">0+</p>
                <p class="mt-1 text-xs font-medium uppercase tracking-wide text-muted">Partner Universities</p>
            </div>
            <div class="text-center">
                <p class="font-display text-3xl text-navy sm:text-4xl" x-text="countries + '+'">0+</p>
                <p class="mt-1 text-xs font-medium uppercase tracking-wide text-muted">Countries</p>
            </div>
            <div class="text-center">
                <p class="font-display text-3xl text-orange sm:text-4xl" x-text="followers.toLocaleString()">0</p>
                <p class="mt-1 text-xs font-medium uppercase tracking-wide text-muted">Facebook Followers</p>
            </div>
        </div>
    </section>

    {{-- JOURNEY RAIL with scroll-triggered reveal --}}
    <section class="relative mx-auto max-w-5xl px-4 py-14 sm:px-6 sm:py-20">
        <div class="text-center" x-data x-intersect.once="$el.classList.add('animate-rise')">
            <span class="text-sm font-semibold uppercase tracking-wide text-orange">How it works</span>
            <h2 class="mt-2 font-display text-3xl text-navy sm:text-4xl">Your journey, five simple steps</h2>
            <p class="mt-2 text-sm text-muted sm:mt-3 sm:text-base">From first dream to first day on campus.</p>
        </div>

        <div class="relative mt-10 sm:mt-16">
            {{-- Mobile rail (left gutter) --}}
            <div aria-hidden="true" class="absolute left-4 top-0 h-full w-0.5 bg-gradient-to-b from-orange via-navy/30 to-navy/10 md:hidden"></div>
            {{-- Desktop rail (centre) --}}
            <div aria-hidden="true" class="absolute left-1/2 top-0 hidden h-full w-0.5 -translate-x-1/2 bg-gradient-to-b from-orange via-navy/30 to-navy/10 md:block"></div>

            <ol class="space-y-6 md:space-y-0">
                @foreach([
                    ['Dream', 'Tell us where you want to study and what you want to become. Every journey starts with a conversation.', 'M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z'],
                    ['Free Counselling', 'Expert guidance at no cost from British Council certified counsellors who put your needs first.', 'M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0 0 11.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155'],
                    ['Select University', 'We shortlist the right courses and universities matched to your grades, budget and career goals.', 'M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5'],
                    ['Apply', 'We handle the paperwork, documents, IELTS prep and deadlines. SOP guidance and interview preparation included.', 'M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z'],
                    ['Get Admission', 'Offer letter, visa approval, accommodation and pre-departure briefing. A confident departure guaranteed.', 'M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z'],
                ] as $i => $step)
                    <li x-data x-intersect.once="$el.classList.add('animate-rise')" class="relative opacity-0 pl-10 md:pl-0 md:grid md:grid-cols-2 md:gap-8 md:py-6 {{ $i % 2 ? 'md:[&>*:first-child]:col-start-2 md:[&>*:first-child]:text-left md:[&>*:last-child]:col-start-1 md:[&>*:last-child]:row-start-1' : '' }}" style="animation-delay: {{ $i * 100 }}ms">
                        {{-- Mobile node --}}
                        <div aria-hidden="true" class="absolute left-2 top-6 h-4 w-4 rounded-full border-[3px] border-white bg-orange shadow md:hidden"></div>
                        <div class="{{ $i % 2 ? '' : 'md:text-right' }}">
                            <div class="group rounded-2xl border border-line bg-white p-5 shadow-glass transition-all duration-300 ease-rise hover:-translate-y-1 hover:shadow-lg hover:border-orange/30 sm:p-6">
                                <div class="mb-3 inline-flex h-10 w-10 items-center justify-center rounded-xl bg-orange/10 text-orange transition-colors group-hover:bg-orange group-hover:text-white">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $step[2] }}"/></svg>
                                </div>
                                <span class="font-display text-sm text-orange">Step {{ $i + 1 }}</span>
                                <h3 class="mt-1 font-display text-xl text-navy sm:text-2xl">{{ $step[0] }}</h3>
                                <p class="mt-2 text-sm leading-relaxed text-muted">{{ $step[1] }}</p>
                            </div>
                        </div>
                        {{-- Desktop node --}}
                        <div aria-hidden="true" class="absolute left-1/2 top-8 hidden h-5 w-5 -translate-x-1/2 rounded-full border-4 border-white bg-orange shadow md:block"></div>
                        <div class="hidden md:block"></div>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- DESTINATIONS with hover animations --}}
    <section class="bg-canvas py-14 sm:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <div class="flex flex-wrap items-end justify-between gap-3" x-data x-intersect.once="$el.classList.add('animate-rise')">
                <div>
                    <span class="text-sm font-semibold uppercase tracking-wide text-orange">Unlock Global Opportunities</span>
                    <h2 class="mt-2 font-display text-3xl text-navy sm:text-4xl">Popular destinations</h2>
                </div>
                <a href="{{ route('destinations') }}" class="text-sm font-semibold text-orange transition-transform hover:translate-x-1 hover:underline">All destinations →</a>
            </div>

            <div class="mt-8 grid gap-4 sm:mt-10 sm:grid-cols-2 sm:gap-5 lg:grid-cols-3">
                @foreach([
                    ['United Kingdom', '🇬🇧', 'Russell Group universities, 2-year post-study work visa', 'bg-gradient-to-br from-blue-50 to-red-50'],
                    ['USA', '🇺🇸', 'Ivy League and state universities, OPT work options', 'bg-gradient-to-br from-blue-50 to-indigo-50'],
                    ['Canada', '🇨🇦', 'Affordable tuition, work while you study, path to residency', 'bg-gradient-to-br from-red-50 to-orange-50'],
                    ['Australia', '🇦🇺', 'Globally recognised degrees, generous post-study work rights', 'bg-gradient-to-br from-yellow-50 to-green-50'],
                    ['Malaysia', '🇲🇾', 'Affordable global campuses, gateway to Asia', 'bg-gradient-to-br from-blue-50 to-yellow-50'],
                    ['Europe', '🇪🇺', 'Low or no tuition, English-taught programmes', 'bg-gradient-to-br from-indigo-50 to-blue-50'],
                ] as $i => $dest)
                    <a href="{{ route('destinations') }}" x-data x-intersect.once="$el.classList.add('animate-rise')" class="group overflow-hidden rounded-2xl border border-line bg-white opacity-0 shadow-glass transition-all duration-300 ease-rise hover:-translate-y-1.5 hover:shadow-lg" style="animation-delay: {{ $i * 80 }}ms">
                        <div class="{{$dest[3]}} p-3 text-center text-5xl transition-transform duration-500 group-hover:scale-110">{!! $dest[1] !!}</div>
                        <div class="p-5 sm:p-6">
                            <h3 class="font-display text-xl text-navy transition-colors group-hover:text-orange">{{ $dest[0] }}</h3>
                            <p class="mt-1 text-sm text-muted">{{ $dest[2] }}</p>
                            <span class="mt-3 inline-flex items-center gap-1 text-xs font-semibold text-orange opacity-0 transition-all duration-300 group-hover:opacity-100 group-hover:translate-x-1">Learn more <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg></span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- SERVICES with icons and stagger animation --}}
    <section class="py-14 sm:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <div class="text-center" x-data x-intersect.once="$el.classList.add('animate-rise')">
                <span class="text-sm font-semibold uppercase tracking-wide text-orange">Our Services</span>
                <h2 class="mt-2 font-display text-3xl text-navy sm:text-4xl">How we help you</h2>
                <p class="mx-auto mt-3 max-w-xl text-sm text-muted sm:text-base">From choosing the right university to settling in abroad, we provide complete end-to-end support.</p>
            </div>
            <div class="mt-8 grid gap-4 sm:mt-12 sm:grid-cols-2 sm:gap-5 lg:grid-cols-3">
                @foreach([
                    ['Free Counselling', 'Expert guidance at no cost on choosing a university and program. Honest, no-pressure advice from certified counsellors.', 'M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0 0 11.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155'],
                    ['Admission Support', 'University application help, course shortlisting, document preparation and follow-up with institutions.', 'M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z'],
                    ['Visa Compliance', 'Document checks, SOP guidance, interview preparation. Pre-visa interview prep for high approval rates.', 'M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z'],
                    ['Student Accommodation', 'Verified housing arranged before you fly, close to your campus. Introductions to other students going your way.', 'M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205 3 1m1.5.5-1.5-.5M6.75 7.364V3h-3v18m3-13.636 10.5-3.819'],
                    ['IELTS &amp; English', 'Training and preparation to meet your language requirements. Spoken English and IELTS coaching available.', 'M10.5 21l5.25-11.25L21 21m-9-3h7.5M3 5.621a48.474 48.474 0 0 1 6-.371m0 0c1.12 0 2.233.038 3.334.114M9 5.25V3m3.334 2.364V3m0 2.364a48.174 48.174 0 0 1 0 0'],
                    ['End-to-End Support', 'From first chat to post-arrival settling in. BRP collection, bank accounts and pre-departure briefing included.', 'M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z'],
                ] as $i => $service)
                    <div x-data x-intersect.once="$el.classList.add('animate-rise')" class="group rounded-2xl border border-line bg-white p-5 opacity-0 shadow-glass transition-all duration-300 ease-rise hover:-translate-y-1.5 hover:shadow-lg hover:border-orange/30 sm:p-7" style="animation-delay: {{ $i * 80 }}ms">
                        <div class="mb-3 inline-flex h-11 w-11 items-center justify-center rounded-xl bg-navy/5 text-navy transition-colors group-hover:bg-orange group-hover:text-white">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{!! $service[2] !!}"/></svg>
                        </div>
                        <div class="h-1 w-10 rounded-full bg-line transition-all duration-300 group-hover:w-16 group-hover:bg-orange"></div>
                        <h3 class="mt-4 font-display text-xl text-navy">{!! $service[0] !!}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-muted">{{ $service[1] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ADMISSION GUIDELINES --}}
    <section class="bg-canvas py-14 sm:py-20">
        <div class="mx-auto max-w-5xl px-4 sm:px-6">
            <div class="text-center" x-data x-intersect.once="$el.classList.add('animate-rise')">
                <span class="text-sm font-semibold uppercase tracking-wide text-orange">Admission Guidelines</span>
                <h2 class="mt-2 font-display text-3xl text-navy sm:text-4xl">Choosing the best university</h2>
                <p class="mx-auto mt-3 max-w-xl text-sm text-muted sm:text-base">Whether you are applying for Foundation, Undergraduate, Pre-Masters or Masters, we guide you through every requirement.</p>
            </div>
            <div class="mt-8 grid gap-4 sm:mt-12 sm:grid-cols-2 sm:gap-5" x-data="{ open: null }">
                @foreach([
                    ['Foundation', 'High school diploma, minimum age requirement, IELTS/TOEFL score, statement of purpose and recommendation letters.'],
                    ['Undergraduate', 'High school diploma, minimum GPA, SAT/ACT (US), IELTS/TOEFL, personal statement, recommendation letters and portfolio if applicable.'],
                    ['Pre-Masters', 'Bachelor\'s degree, minimum GPA, IELTS/TOEFL, CV/resume, statement of purpose and sometimes a writing sample or interview.'],
                    ['Masters / PhD', 'Bachelor\'s degree, minimum GPA, GRE/GMAT (programme dependent), IELTS/TOEFL, recommendation letters, statement of purpose and research proposal for PhD.'],
                ] as $i => $level)
                    <div x-data x-intersect.once="$el.classList.add('animate-rise')" class="opacity-0 overflow-hidden rounded-2xl border border-line bg-white shadow-glass transition-all duration-300 hover:shadow-lg" style="animation-delay: {{ $i * 100 }}ms">
                        <button @click="open = open === {{ $i }} ? null : {{ $i }}" class="flex w-full items-center justify-between p-5 text-left sm:p-6">
                            <h3 class="font-display text-lg text-navy sm:text-xl">{{ $level[0] }}</h3>
                            <svg class="h-5 w-5 shrink-0 text-muted transition-transform duration-300" :class="open === {{ $i }} && 'rotate-180'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
                        </button>
                        <div x-show="open === {{ $i }}" x-collapse class="border-t border-line px-5 pb-5 sm:px-6 sm:pb-6">
                            <p class="mt-4 text-sm leading-relaxed text-muted">{{ $level[1] }}</p>
                            <a href="{{ route('contact') }}" class="mt-3 inline-flex items-center gap-1 text-sm font-semibold text-orange hover:underline">Apply now <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg></a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- VISA SUCCESS STORIES — real students from the website --}}
    <section class="py-14 sm:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <div class="text-center" x-data x-intersect.once="$el.classList.add('animate-rise')">
                <span class="text-sm font-semibold uppercase tracking-wide text-orange">A Glimpse of Our Happy Students</span>
                <h2 class="mt-2 font-display text-3xl text-navy sm:text-4xl">Visa success stories</h2>
                <p class="mt-2 text-sm text-muted sm:mt-3 sm:text-base">Real students, real approvals. অভিনন্দন!</p>
            </div>
            <div class="mt-8 grid gap-5 sm:mt-12 sm:grid-cols-2 lg:grid-cols-3">
                @foreach([
                    ['🇬🇧', 'Pushpita Das', 'UK Student Visa', 'The entire visa process was smooth. Global Consultancy guided me step by step with clear instructions.'],
                    ['🇬🇧', 'Jannatul Ferdous', 'UK Student Visa', 'From choosing the right university to getting my visa approved, they were with me at every step.'],
                    ['🇬🇧', 'Fahima Momtaz', 'UK Student Visa', 'I got my visa approved on the first attempt! Their interview preparation made all the difference.'],
                    ['🇨🇦', 'Abdur Rahman Fahad', 'Canada Study Permit', 'They helped me find the right programme and handled all my documentation. Highly recommend!'],
                    ['🇦🇺', 'Maisha Ibnat', 'Australia Student Visa', 'Offer, visa and accommodation sorted in one smooth journey. Could not have done it without them.'],
                    ['🇲🇾', 'Sanjida Sultana Mahin', 'Malaysia Student Visa', 'Affordable and professional service. They found me the perfect university in Malaysia.'],
                ] as $i => $story)
                    <figure x-data x-intersect.once="$el.classList.add('animate-rise')" class="rounded-2xl border border-line bg-white p-5 opacity-0 shadow-glass transition-all duration-300 ease-rise hover:-translate-y-1 hover:shadow-lg sm:p-6" style="animation-delay: {{ $i * 100 }}ms">
                        <div class="flex items-center gap-3">
                            <span class="text-3xl">{!! $story[0] !!}</span>
                            <div>
                                <figcaption class="font-semibold text-navy">{{ $story[1] }}</figcaption>
                                <p class="text-xs font-medium text-orange">{{ $story[2] }} ✓</p>
                            </div>
                        </div>
                        <blockquote class="mt-4 text-sm leading-relaxed text-muted">"{{ $story[3] }}"</blockquote>
                        <div class="mt-3 flex gap-0.5">
                            @for($s = 0; $s < 5; $s++)
                                <svg class="h-4 w-4 text-orange" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                    </figure>
                @endforeach
            </div>
        </div>
    </section>

    {{-- WHATSAPP FLOATING BUTTON --}}
    <a href="https://wa.me/447402993321" target="_blank" rel="noopener" aria-label="Chat on WhatsApp"
       class="fixed bottom-6 right-6 z-40 flex h-14 w-14 items-center justify-center rounded-full bg-[#25D366] text-white shadow-lg transition-transform duration-300 hover:scale-110 active:scale-95 sm:bottom-8 sm:right-8">
        <svg class="h-7 w-7" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
    </a>

    {{-- CTA with animated background --}}
    <section class="relative overflow-hidden bg-navy py-14 sm:py-20">
        <div class="pointer-events-none absolute -left-24 top-1/2 h-72 w-72 -translate-y-1/2 rounded-full bg-orange/20 blur-3xl animate-pulse-slow"></div>
        <div class="pointer-events-none absolute -right-24 top-1/4 h-56 w-56 rounded-full bg-navy-700/50 blur-3xl animate-pulse-slow [animation-delay:2s]"></div>
        <div class="relative mx-auto max-w-3xl px-4 text-center sm:px-6" x-data x-intersect.once="$el.classList.add('animate-rise')">
            <h2 class="font-display text-3xl text-white sm:text-4xl md:text-5xl">Ready to start your journey?</h2>
            <p class="mt-3 text-base text-white/70 sm:mt-4 sm:text-lg">Book a free counselling session today. No cost, no obligation.</p>
            <div class="mt-8 flex flex-col items-center gap-4 sm:flex-row sm:justify-center">
                <a href="{{ route('contact') }}" class="group relative overflow-hidden rounded-full bg-orange px-8 py-4 font-semibold text-navy shadow-glass transition-all duration-300 hover:bg-orange-600 hover:shadow-lg active:scale-[.98]">
                    <span class="relative z-10">Book Free Counselling</span>
                    <span class="absolute inset-0 -translate-x-full bg-gradient-to-r from-transparent via-white/25 to-transparent transition-transform duration-700 group-hover:translate-x-full"></span>
                </a>
                <a href="tel:{{ settings('company.phone_uk', '+447402993321') }}" class="inline-flex items-center gap-2 text-white/80 transition-colors hover:text-white">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/></svg>
                    {{ settings('company.phone_uk', '07402993321') }}
                </a>
            </div>
        </div>
    </section>
</x-layouts.public>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('counterStrip', () => ({
        students: 0,
        universities: 0,
        countries: 0,
        followers: 0,
        start() {
            this.animateCounter('students', {{ settings('stats.students_placed', 1000) }}, 1200);
            this.animateCounter('universities', {{ settings('stats.universities', 50) }}, 1000);
            this.animateCounter('countries', {{ settings('stats.countries', 15) }}, 800);
            this.animateCounter('followers', {{ settings('social.followers', 19000) }}, 1400);
        },
        animateCounter(prop, target, duration) {
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                this[prop] = target;
                return;
            }
            const start = performance.now();
            const step = (now) => {
                const elapsed = now - start;
                const progress = Math.min(elapsed / duration, 1);
                // easeOutExpo
                const eased = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
                this[prop] = Math.round(eased * target);
                if (progress < 1) requestAnimationFrame(step);
            };
            requestAnimationFrame(step);
        }
    }));
});
</script>
@endpush
