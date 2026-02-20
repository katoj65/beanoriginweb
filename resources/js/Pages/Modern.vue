<script setup>
import { Link } from '@inertiajs/vue3';

const currentYear = new Date().getFullYear();

const marketRows = [
    { commodity: 'Arabica AA', region: 'Kilimanjaro', volume: '42 MT', bid: '$3.39/kg', ask: '$3.46/kg', spread: '2.1%' },
    { commodity: 'Cocoa Grade 1', region: 'Ashanti', volume: '30 MT', bid: '$4.80/kg', ask: '$4.92/kg', spread: '2.4%' },
    { commodity: 'Sesame Premium', region: 'Dodoma', volume: '18 MT', bid: '$1.70/kg', ask: '$1.76/kg', spread: '3.4%' },
    { commodity: 'Robusta FAQ', region: 'Bukoba', volume: '27 MT', bid: '$2.68/kg', ask: '$2.74/kg', spread: '2.2%' },
];

const metrics = [
    { label: 'Active Buyers', value: '1,850+' },
    { label: 'Listed Lots', value: '9,200+' },
    { label: 'Monthly Volume', value: '48,000 MT' },
    { label: 'Avg Settlement', value: 'T+2 Days' },
];

const features = [
    {
        title: 'Verified Counterparties',
        text: 'Every cooperative, supplier, and buyer is screened before they can trade.',
        icon: 'bi-shield-check',
    },
    {
        title: 'Live Price Discovery',
        text: 'Monitor bid and ask movement in real time across commodity categories.',
        icon: 'bi-graph-up-arrow',
    },
    {
        title: 'Digital Contracts',
        text: 'Generate and sign trade contracts with clear terms and milestone tracking.',
        icon: 'bi-file-earmark-text',
    },
    {
        title: 'Settlement Workflows',
        text: 'Track payment, delivery, and completion steps from one dashboard.',
        icon: 'bi-check2-circle',
    },
    {
        title: 'Quality Metadata',
        text: 'Store grade, moisture, origin, and process details on each lot.',
        icon: 'bi-award',
    },
    {
        title: 'Audit Trail',
        text: 'All status updates are recorded for transparent reporting and compliance.',
        icon: 'bi-journal-check',
    },
];

const steps = [
    {
        title: 'List Commodity Lots',
        text: 'Publish quantity, grade, location, and reserve terms with document attachments.',
    },
    {
        title: 'Receive and Negotiate',
        text: 'Buyers compare lots and negotiate through structured offer threads.',
    },
    {
        title: 'Confirm Contracts',
        text: 'Lock final pricing, payment conditions, and delivery milestones.',
    },
    {
        title: 'Settle and Close',
        text: 'Track fulfillment and close trades with immutable transaction history.',
    },
];

const plans = [
    {
        name: 'Starter',
        price: '$0',
        period: '/month',
        note: 'For small cooperatives',
        points: ['Up to 20 monthly lots', 'Basic market dashboard', 'Email support'],
        featured: false,
    },
    {
        name: 'Growth',
        price: '$149',
        period: '/month',
        note: 'For active trading teams',
        points: ['Unlimited listings', 'Counteroffer workflows', 'Contract templates'],
        featured: true,
    },
    {
        name: 'Enterprise',
        price: 'Custom',
        period: '',
        note: 'For regional exchanges',
        points: ['Dedicated onboarding', 'Custom settlement stages', 'Priority SLA support'],
        featured: false,
    },
];

const testimonials = [
    {
        name: 'Amina J.',
        role: 'Export Lead, Umoja Coop',
        quote: 'We moved from spreadsheet chaos to clean lot-level execution in under two weeks.',
    },
    {
        name: 'Daniel R.',
        role: 'Procurement Manager, Gulf Roasters',
        quote: 'The live board and clear contract milestones drastically reduced back-and-forth.',
    },
    {
        name: 'Susan K.',
        role: 'Operations Director, TraceBean',
        quote: 'Traceability and settlement visibility gave our compliance team exactly what it needed.',
    },
];

const faqs = [
    {
        q: 'Who can list commodities?',
        a: 'Approved cooperatives and verified suppliers can create and manage lots.',
    },
    {
        q: 'Can buyers compare multiple listings?',
        a: 'Yes. Buyers can compare grade, quantity, price, and origin side by side.',
    },
    {
        q: 'Does the platform support contracts and milestones?',
        a: 'Yes. Contract terms and milestone checkpoints are tracked in each trade lifecycle.',
    },
    {
        q: 'Which commodities are supported?',
        a: 'Coffee, cocoa, sesame, and multiple staple/export crops are supported by default.',
    },
];
</script>

<template>
    <div class="modern-exchange">
        <header class="site-nav">
            <div class="container">
                <nav class="navbar navbar-expand-lg py-3">
                    <Link class="navbar-brand brandmark" href="/">
                        <span class="brand-dot"></span>
                        <span>TradeHarbor</span>
                    </Link>
                    <button
                        class="navbar-toggler"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#modernNav"
                        aria-controls="modernNav"
                        aria-expanded="false"
                        aria-label="Toggle navigation"
                    >
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div id="modernNav" class="collapse navbar-collapse">
                        <ul class="navbar-nav ms-auto me-3">
                            <li class="nav-item"><a class="nav-link" href="#markets">Markets</a></li>
                            <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
                            <li class="nav-item"><a class="nav-link" href="#pricing">Pricing</a></li>
                            <li class="nav-item"><a class="nav-link" href="#faq">FAQ</a></li>
                        </ul>
                        <div class="d-flex gap-2">
                            <Link href="/login" class="btn btn-outline-brand">Log In</Link>
                            <Link href="/register" class="btn btn-brand">Get Started</Link>
                        </div>
                    </div>
                </nav>
            </div>
        </header>

        <section class="hero-section">
            <div class="container">
                <div class="row align-items-center g-4">
                    <div class="col-lg-6">
                        <div class="hero-copy reveal">
                            <p class="hero-kicker">Modern Commodity Exchange</p>
                            <h1 class="hero-focus-title">Trade Agricultural Commodities With Speed and Trust</h1>
                            <p class="hero-lead">
                                A modern digital marketplace for cooperatives, suppliers, and buyers to list lots, negotiate
                                prices, and settle trades with full transparency.
                            </p>
                            <div class="d-flex flex-wrap gap-2 mt-4">
                                <Link href="/register" class="btn btn-brand btn-lg">Start Trading</Link>
                                <a href="#markets" class="btn btn-soft btn-lg">View Live Markets</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card hero-panel reveal delay-1">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">Live Market Board</h5>
                                    <span class="badge badge-live">Live</span>
                                </div>
                                <div class="table-responsive">
                                    <table class="table align-middle mb-0 market-table">
                                        <thead>
                                            <tr>
                                                <th>Commodity</th>
                                                <th>Bid</th>
                                                <th>Ask</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="row in marketRows.slice(0, 3)" :key="row.commodity">
                                                <td>
                                                    <strong class="d-block">{{ row.commodity }}</strong>
                                                    <small class="text-muted">{{ row.region }}</small>
                                                </td>
                                                <td>{{ row.bid }}</td>
                                                <td>{{ row.ask }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row g-2 mt-2">
                                    <div v-for="metric in metrics.slice(0, 2)" :key="metric.label" class="col-6">
                                        <div class="metric-box">
                                            <small>{{ metric.label }}</small>
                                            <strong>{{ metric.value }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="metrics-section">
            <div class="container">
                <div class="row g-3">
                    <div v-for="metric in metrics" :key="metric.label" class="col-6 col-lg-3">
                        <div class="metric-card reveal">
                            <p>{{ metric.label }}</p>
                            <h3>{{ metric.value }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="markets" class="section-block">
            <div class="container">
                <div class="section-head reveal">
                    <p class="eyebrow">Markets</p>
                    <h2>Active Lots and Price Discovery</h2>
                    <p>Track live bids, asks, and spread movement across high-demand commodities.</p>
                </div>
                <div class="card glass-card reveal delay-1">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Commodity</th>
                                        <th>Region</th>
                                        <th>Volume</th>
                                        <th>Best Bid</th>
                                        <th>Best Ask</th>
                                        <th>Spread</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="row in marketRows" :key="row.commodity + row.region">
                                        <td><strong>{{ row.commodity }}</strong></td>
                                        <td>{{ row.region }}</td>
                                        <td>{{ row.volume }}</td>
                                        <td>{{ row.bid }}</td>
                                        <td>{{ row.ask }}</td>
                                        <td><span class="spread-pill">{{ row.spread }}</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="features" class="section-block alt-block">
            <div class="container">
                <div class="section-head reveal">
                    <p class="eyebrow">Platform Features</p>
                    <h2>Everything Needed to Run Modern Trade Operations</h2>
                    <p>From listing and negotiation to contract execution and settlement visibility.</p>
                </div>
                <div class="row g-3">
                    <div v-for="item in features" :key="item.title" class="col-md-6 col-xl-4">
                        <article class="feature-card reveal">
                            <div class="icon-wrap">
                                <i :class="['bi', item.icon]"></i>
                            </div>
                            <h5>{{ item.title }}</h5>
                            <p>{{ item.text }}</p>
                        </article>
                    </div>
                </div>
            </div>
        </section>

        <section class="section-block">
            <div class="container">
                <div class="row g-4 align-items-start">
                    <div class="col-lg-6">
                        <div class="section-head reveal">
                            <p class="eyebrow">How It Works</p>
                            <h2>From Listing to Settlement in Four Steps</h2>
                        </div>
                        <div class="timeline">
                            <article v-for="(step, idx) in steps" :key="step.title" class="timeline-step reveal">
                                <span class="step-badge">{{ idx + 1 }}</span>
                                <div>
                                    <h5>{{ step.title }}</h5>
                                    <p>{{ step.text }}</p>
                                </div>
                            </article>
                        </div>
                    </div>
                    <div id="pricing" class="col-lg-6">
                        <div class="section-head reveal">
                            <p class="eyebrow">Pricing</p>
                            <h2>Plans for Every Trading Team</h2>
                        </div>
                        <div class="row g-3">
                            <div v-for="plan in plans" :key="plan.name" class="col-md-6 col-xl-4 col-lg-12">
                                <article :class="['plan-card reveal', { featured: plan.featured }]">
                                    <h5>{{ plan.name }}</h5>
                                    <div class="price-line">
                                        <span class="price">{{ plan.price }}</span>
                                        <span class="period">{{ plan.period }}</span>
                                    </div>
                                    <p class="plan-note">{{ plan.note }}</p>
                                    <ul class="plan-list">
                                        <li v-for="point in plan.points" :key="point">{{ point }}</li>
                                    </ul>
                                    <button class="btn btn-sm w-100" :class="plan.featured ? 'btn-brand' : 'btn-soft'">
                                        Choose Plan
                                    </button>
                                </article>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section-block alt-block">
            <div class="container">
                <div class="section-head reveal">
                    <p class="eyebrow">Testimonials</p>
                    <h2>Trusted by Cooperative and Procurement Teams</h2>
                </div>
                <div class="row g-3">
                    <div v-for="quote in testimonials" :key="quote.name" class="col-lg-4">
                        <article class="testimonial-card reveal">
                            <p>"{{ quote.quote }}"</p>
                            <strong>{{ quote.name }}</strong>
                            <small>{{ quote.role }}</small>
                        </article>
                    </div>
                </div>
            </div>
        </section>

        <section id="faq" class="section-block">
            <div class="container">
                <div class="section-head reveal">
                    <p class="eyebrow">FAQ</p>
                    <h2>Common Questions</h2>
                </div>
                <div class="accordion custom-accordion reveal" id="faqAccordion">
                    <div v-for="(item, index) in faqs" :key="item.q" class="accordion-item">
                        <h2 :id="`heading-${index}`" class="accordion-header">
                            <button
                                class="accordion-button"
                                :class="{ collapsed: index !== 0 }"
                                type="button"
                                data-bs-toggle="collapse"
                                :data-bs-target="`#collapse-${index}`"
                                :aria-expanded="index === 0 ? 'true' : 'false'"
                                :aria-controls="`collapse-${index}`"
                            >
                                {{ item.q }}
                            </button>
                        </h2>
                        <div
                            :id="`collapse-${index}`"
                            class="accordion-collapse collapse"
                            :class="{ show: index === 0 }"
                            :aria-labelledby="`heading-${index}`"
                            data-bs-parent="#faqAccordion"
                        >
                            <div class="accordion-body">
                                {{ item.a }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="final-cta">
            <div class="container">
                <div class="cta-box reveal">
                    <div>
                        <p class="eyebrow mb-2">Ready to Trade Better?</p>
                        <h2>Launch Your Commodity Marketplace Workflow Today</h2>
                        <p>Onboard teams, list verified lots, and execute contracts with confidence.</p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <Link href="/register" class="btn btn-brand btn-lg">Create Account</Link>
                        <Link href="/login" class="btn btn-outline-brand btn-lg">Sign In</Link>
                    </div>
                </div>
            </div>
        </section>

        <footer class="site-footer">
            <div class="container py-4 d-flex flex-column flex-lg-row justify-content-between gap-2">
                <p class="mb-0">© {{ currentYear }} TradeHarbor Exchange. All rights reserved.</p>
                <div class="d-flex gap-3">
                    <a href="#">Privacy</a>
                    <a href="#">Terms</a>
                    <a href="#">Support</a>
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap');
@import url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css');

.modern-exchange {
    --mx-bg: #f4f9fb;
    --mx-bg-2: #eef4f7;
    --mx-text: #102a43;
    --mx-muted: #5f7386;
    --mx-brand: #0e8a7d;
    --mx-brand-dark: #0b6f65;
    --mx-accent: #f2a74b;
    --mx-border: #dbe7ee;
    --mx-card: #ffffff;
    background:
        radial-gradient(900px 400px at 0% -10%, rgba(14, 138, 125, 0.16), transparent 70%),
        radial-gradient(800px 380px at 100% 0%, rgba(242, 167, 75, 0.15), transparent 70%),
        linear-gradient(180deg, var(--mx-bg), var(--mx-bg-2) 60%, #ffffff);
    color: var(--mx-text);
    font-family: 'Manrope', sans-serif;
}

.modern-exchange h1,
.modern-exchange h2,
.modern-exchange h3,
.modern-exchange h4,
.modern-exchange h5 {
    font-family: 'Space Grotesk', sans-serif;
    letter-spacing: -0.02em;
}

.site-nav {
    position: sticky;
    top: 0;
    z-index: 40;
    backdrop-filter: blur(8px);
    background: rgba(244, 249, 251, 0.88);
    border-bottom: 1px solid rgba(219, 231, 238, 0.9);
}

.navbar-toggler {
    border-color: #b6c8d5;
}

.navbar-toggler:focus {
    box-shadow: none;
}

.navbar-toggler-icon {
    width: 1.1rem;
    height: 1.1rem;
    background-image: none;
    display: inline-block;
    position: relative;
}

.navbar-toggler-icon::before,
.navbar-toggler-icon::after {
    content: '';
    position: absolute;
    left: 0;
    width: 100%;
    height: 2px;
    background: #1f3a56;
    border-radius: 2px;
}

.navbar-toggler-icon::before {
    top: 3px;
    box-shadow: 0 5px 0 #1f3a56;
}

.navbar-toggler-icon::after {
    top: 13px;
}

.brandmark {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    font-weight: 700;
    color: var(--mx-text);
}

.brand-dot {
    width: 28px;
    height: 28px;
    border-radius: 8px;
    background: linear-gradient(135deg, var(--mx-brand), var(--mx-accent));
    box-shadow: 0 8px 20px rgba(14, 138, 125, 0.28);
}

.nav-link {
    color: var(--mx-muted);
    font-weight: 600;
}

.nav-link:hover {
    color: var(--mx-brand-dark);
}

.btn-brand {
    background: linear-gradient(135deg, var(--mx-brand), var(--mx-brand-dark));
    border: none;
    color: #fff;
    font-weight: 700;
}

.btn-brand:hover {
    color: #fff;
    transform: translateY(-1px);
}

.btn-outline-brand {
    border: 1px solid var(--mx-brand);
    color: var(--mx-brand-dark);
    font-weight: 700;
    background: #fff;
}

.btn-soft {
    background: #e8f4f2;
    border: 1px solid #c6e7e2;
    color: var(--mx-brand-dark);
    font-weight: 700;
}

.hero-section {
    padding: 4.5rem 0 2rem;
}

.hero-copy h1 {
    font-size: clamp(1.85rem, 3.2vw, 2.85rem);
    margin-bottom: 1rem;
}

.hero-focus-title {
    font-family: 'Manrope', sans-serif !important;
    font-weight: 800;
    letter-spacing: -0.01em;
}

.hero-kicker,
.eyebrow {
    color: var(--mx-brand-dark);
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    font-size: 0.74rem;
    margin-bottom: 0.75rem;
}

.hero-lead,
.section-head p {
    color: var(--mx-muted);
    max-width: 62ch;
}

.hero-panel {
    border: 1px solid var(--mx-border);
    border-radius: 18px;
    background: linear-gradient(180deg, #ffffff, #f9fcfd);
    box-shadow: 0 16px 30px rgba(16, 42, 67, 0.08);
}

.badge-live {
    background: #daf7f2;
    color: #0b6f65;
    border: 1px solid #b7eadf;
    font-weight: 700;
}

.market-table th {
    color: #486581;
    font-weight: 700;
    font-size: 0.84rem;
}

.market-table td {
    font-size: 0.92rem;
}

.metric-box {
    background: #f2f8fb;
    border: 1px solid var(--mx-border);
    border-radius: 12px;
    padding: 10px;
    display: grid;
    gap: 2px;
}

.metric-box small {
    color: #627d98;
}

.metrics-section {
    padding: 1.4rem 0 2rem;
}

.metric-card {
    border: 1px solid var(--mx-border);
    border-radius: 14px;
    background: rgba(255, 255, 255, 0.8);
    padding: 16px;
    height: 100%;
}

.metric-card p {
    margin: 0;
    color: #627d98;
    font-weight: 600;
    font-size: 0.86rem;
}

.metric-card h3 {
    margin: 6px 0 0;
    font-size: 1.45rem;
}

.section-block {
    padding: 3.4rem 0;
}

.alt-block {
    background: linear-gradient(180deg, rgba(255, 255, 255, 0.7), rgba(244, 249, 251, 0.9));
    border-top: 1px solid #e7eff4;
    border-bottom: 1px solid #e7eff4;
}

.section-head {
    margin-bottom: 1.4rem;
}

.section-head h2 {
    font-size: clamp(1.4rem, 2.4vw, 2.05rem);
    margin-bottom: 0.7rem;
}

.glass-card {
    border: 1px solid var(--mx-border);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 14px 28px rgba(15, 23, 42, 0.08);
    background: rgba(255, 255, 255, 0.86);
}

.table thead th {
    background: #f5f9fc;
    border-bottom-width: 1px;
    color: #486581;
    font-size: 0.82rem;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

.table td {
    vertical-align: middle;
}

.spread-pill {
    background: #e6f6f4;
    color: #0b6f65;
    border: 1px solid #b7eadf;
    border-radius: 999px;
    padding: 5px 10px;
    font-size: 0.8rem;
    font-weight: 700;
}

.feature-card {
    height: 100%;
    border: 1px solid var(--mx-border);
    border-radius: 14px;
    background: var(--mx-card);
    padding: 18px;
    box-shadow: 0 10px 24px rgba(16, 42, 67, 0.06);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.feature-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 16px 32px rgba(16, 42, 67, 0.1);
}

.icon-wrap {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    background: linear-gradient(135deg, #e8f5f3, #fff2df);
    color: var(--mx-brand-dark);
    display: grid;
    place-items: center;
    margin-bottom: 10px;
    font-size: 1.1rem;
}

.feature-card h5 {
    margin-bottom: 8px;
}

.feature-card p {
    margin: 0;
    color: var(--mx-muted);
}

.timeline {
    display: grid;
    gap: 12px;
}

.timeline-step {
    display: grid;
    grid-template-columns: auto 1fr;
    gap: 12px;
    align-items: flex-start;
    border: 1px solid var(--mx-border);
    border-radius: 14px;
    background: #fff;
    padding: 14px;
}

.step-badge {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: var(--mx-brand);
    color: #fff;
    display: grid;
    place-items: center;
    font-size: 0.8rem;
    font-weight: 700;
}

.timeline-step p {
    margin: 4px 0 0;
    color: var(--mx-muted);
}

.plan-card {
    height: 100%;
    border: 1px solid var(--mx-border);
    border-radius: 14px;
    background: #fff;
    padding: 16px;
}

.plan-card.featured {
    border-color: #9ddad2;
    background: linear-gradient(180deg, #ffffff, #f0fbf8);
    box-shadow: 0 12px 24px rgba(14, 138, 125, 0.12);
}

.price-line {
    display: flex;
    align-items: baseline;
    gap: 5px;
    margin: 8px 0;
}

.price {
    font-size: 1.6rem;
    font-weight: 800;
}

.period {
    color: #627d98;
}

.plan-note {
    color: #627d98;
    margin-bottom: 10px;
    font-size: 0.9rem;
}

.plan-list {
    margin: 0 0 12px;
    padding-left: 18px;
    color: #334e68;
    display: grid;
    gap: 6px;
    font-size: 0.9rem;
}

.testimonial-card {
    height: 100%;
    border: 1px solid var(--mx-border);
    border-radius: 14px;
    background: #fff;
    padding: 16px;
}

.testimonial-card p {
    color: #334e68;
}

.testimonial-card strong {
    display: block;
}

.testimonial-card small {
    color: #627d98;
}

.custom-accordion .accordion-item {
    border: 1px solid var(--mx-border);
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 10px;
}

.custom-accordion .accordion-button {
    font-weight: 700;
    color: #243b53;
}

.custom-accordion .accordion-button:not(.collapsed) {
    background: #ecfaf7;
    color: #0b6f65;
}

.custom-accordion .accordion-body {
    color: #486581;
}

.final-cta {
    padding: 3.5rem 0;
}

.cta-box {
    border-radius: 18px;
    background:
        radial-gradient(500px 220px at 0% 0%, rgba(255, 255, 255, 0.22), transparent 70%),
        linear-gradient(135deg, #0f766e, #0b6f65 52%, #115f8f);
    color: #fff;
    padding: 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
}

.cta-box p {
    margin: 0;
    color: rgba(255, 255, 255, 0.88);
}

.site-footer {
    border-top: 1px solid #dbe7ee;
    background: #fff;
    color: #627d98;
}

.site-footer a {
    color: #486581;
    text-decoration: none;
}

.reveal {
    opacity: 0;
    transform: translateY(12px);
    animation: revealUp 0.6s ease forwards;
}

.delay-1 {
    animation-delay: 0.14s;
}

@keyframes revealUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (max-width: 991.98px) {
    .hero-section {
        padding-top: 2.6rem;
    }

    .site-nav .navbar-collapse {
        padding-top: 10px;
    }

    .cta-box {
        padding: 1.4rem;
    }
}

@media (max-width: 575.98px) {
    .section-block {
        padding: 2.6rem 0;
    }

    .hero-copy h1 {
        font-size: 1.62rem;
    }

    .plan-list {
        padding-left: 15px;
    }
}
</style>
