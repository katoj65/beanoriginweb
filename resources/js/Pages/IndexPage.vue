<script setup>
import { Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const currentYear = new Date().getFullYear();
const billingCycle = ref('monthly');
const activeFaq = ref('0');

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

const partners = [
'Atlas Roasters',
'Greenline Commodities',
'Harvest Guild',
'Northstar Foods',
'Horizon Export',
'Summit Trading',
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
monthly: '$0',
annual: '$0',
note: 'For small cooperatives',
points: ['Up to 20 monthly lots', 'Basic market dashboard', 'Email support'],
featured: false,
},
{
name: 'Growth',
monthly: '$149',
annual: '$129',
note: 'For active trading teams',
points: ['Unlimited listings', 'Counteroffer workflows', 'Contract templates'],
featured: true,
},
{
name: 'Enterprise',
monthly: 'Custom',
annual: 'Custom',
note: 'For regional exchanges',
points: ['Dedicated onboarding', 'Custom settlement stages', 'Priority SLA support'],
featured: false,
},
];

const displayPlans = computed(() =>
plans.map((plan) => {
const isEnterprise = plan.monthly === 'Custom';

return {
...plan,
price: billingCycle.value === 'annual' ? plan.annual : plan.monthly,
period: isEnterprise ? '' : billingCycle.value === 'annual' ? '/month, billed annually' : '/month',
};
}),
);

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

const faqTopics = ['Accounts', 'Trading', 'Contracts', 'Settlement', 'Compliance'];

const supportChannels = [
{ title: 'Live Chat', detail: 'Avg response: < 5 min', icon: 'bi-chat-dots' },
{ title: 'Help Center', detail: 'Guides and onboarding docs', icon: 'bi-life-preserver' },
{ title: 'Email Support', detail: 'support@tradeharbor.com', icon: 'bi-envelope' },
];
</script>

<template>
<div class="modern-exchange">
<header class="site-nav">
<div class="container">
<nav class="navbar navbar-expand-lg py-3">
<Link class="navbar-brand brandmark" href="/">
<img src="../../images/logo.png" alt="Commodity Origin logo" class="brand-logo" />
<span class="brand-title">Commodity Origin</span>
</Link>
<div class="header-auth">
<Link href="/login" class="header-auth-link">Login</Link>
<Link href="/register" class="header-auth-link register-link">Register</Link>
</div>

</nav>
</div>
</header>

<section class="hero-section">
<div class="container">
<div class="row align-items-center g-4">
<div class="col-lg-6">
<div class="hero-copy reveal">
<p class="hero-kicker">Commodity Trading Platform</p>
<h1 class="hero-focus-title">Trade Agricultural Commodities With Speed and Trust</h1>
<p class="hero-lead">
A digital marketplace for cooperatives, suppliers, and buyers to list lots, negotiate
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

<section class="ticker-section">
<div class="container">
<div class="ticker-shell">
<div class="ticker-track">
<span v-for="(row, index) in [...marketRows, ...marketRows]" :key="`${row.commodity}-${index}`">
{{ row.commodity }} · {{ row.region }} · Bid {{ row.bid }} · Ask {{ row.ask }}
</span>
</div>
</div>
</div>
</section>

<section class="partners-section">
<div class="container">
<p class="partners-label">Trusted by cooperatives, exporters, and procurement teams</p>
<div class="partners-grid">
<span v-for="partner in partners" :key="partner" class="partner-pill">{{ partner }}</span>
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
<div class="billing-toggle" role="group" aria-label="Billing cycle toggle">
<button
type="button"
class="toggle-btn"
:class="{ active: billingCycle === 'monthly' }"
@click="billingCycle = 'monthly'"
>
Monthly
</button>
<button
type="button"
class="toggle-btn"
:class="{ active: billingCycle === 'annual' }"
@click="billingCycle = 'annual'"
>
Annual <span>Save 13%</span>
</button>
</div>
</div>
<div class="row g-3">
<div v-for="plan in displayPlans" :key="plan.name" class="col-md-6 col-xl-4 col-lg-12">
<article :class="['plan-card reveal', { featured: plan.featured }]">
<span v-if="plan.featured" class="featured-tag">Most Popular</span>
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
<p>Answers to the most common onboarding, trading, and settlement questions.</p>
</div>
<div class="row g-4 faq-layout">
<div class="col-lg-8">
<div class="faq-topic-list reveal">
<span v-for="topic in faqTopics" :key="topic" class="faq-topic-pill">{{ topic }}</span>
</div>
<el-collapse v-model="activeFaq" accordion class="custom-accordion reveal">
<el-collapse-item
v-for="(item, index) in faqs"
:key="item.q"
:name="String(index)"
class="faq-collapse-item"
>
<template #title>
<span class="faq-item-title">
<span class="faq-index">Q{{ index + 1 }}</span>
<span>{{ item.q }}</span>
</span>
</template>
<div class="faq-answer">
{{ item.a }}
</div>
</el-collapse-item>
</el-collapse>
</div>
<div class="col-lg-4">
<aside class="faq-help-card reveal delay-1">
<p class="help-kicker">Need more help?</p>
<h4>Talk to our support team</h4>
<p class="help-copy">Get tailored guidance for setup, compliance, and trade operations.</p>
<div class="help-list">
<article v-for="channel in supportChannels" :key="channel.title" class="help-item">
<div class="help-icon"><i :class="['bi', channel.icon]"></i></div>
<div>
<strong>{{ channel.title }}</strong>
<small>{{ channel.detail }}</small>
</div>
</article>
</div>
<Link href="/register" class="btn btn-brand w-100 mt-2">Contact Support</Link>
</aside>
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
<p class="mb-0">© {{ currentYear }} Commodity Origin. All rights reserved.</p>
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

.brand-logo {
width: 34px;
height: 34px;
object-fit: contain;
border-radius: 10px;
background: #fff;
border: 1px solid #d7e7ef;
padding: 3px;
}

.brand-title {
line-height: 1;
}

.header-auth {
margin-left: auto;
display: inline-flex;
gap: 12px;
align-items: center;
}

.header-auth-link {
display: inline-flex;
align-items: center;
justify-content: center;
padding: 8px 14px;
border-radius: 10px;
border: 1px solid #b9d5ce;
background: #ffffff;
color: #334e68;
font-size: 0.9rem;
font-weight: 700;
text-decoration: none;
line-height: 1;
transition: all 0.2s ease;
}

.header-auth-link:hover {
color: #0b6f65;
border-color: #0b6f65;
background: #f4fbf9;
}

.header-auth-link.register-link {
background: linear-gradient(135deg, var(--mx-brand), var(--mx-brand-dark));
border-color: var(--mx-brand-dark);
color: #ffffff;
}

.header-auth-link.register-link:hover {
color: #ffffff;
filter: brightness(0.98);
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

.ticker-section {
padding: 0 0 1rem;
}

.ticker-shell {
border: 1px solid var(--mx-border);
border-radius: 999px;
background: rgba(255, 255, 255, 0.8);
overflow: hidden;
}

.ticker-track {
white-space: nowrap;
display: inline-flex;
gap: 22px;
padding: 10px 18px;
min-width: 100%;
animation: tickerSlide 26s linear infinite;
}

.ticker-track span {
color: #486581;
font-size: 0.84rem;
font-weight: 600;
}

.partners-section {
padding: 0.3rem 0 1rem;
}

.partners-label {
font-size: 0.82rem;
color: #627d98;
letter-spacing: 0.06em;
text-transform: uppercase;
margin-bottom: 0.7rem;
}

.partners-grid {
display: grid;
grid-template-columns: repeat(6, minmax(0, 1fr));
gap: 10px;
}

.partner-pill {
border: 1px solid var(--mx-border);
border-radius: 12px;
background: rgba(255, 255, 255, 0.88);
color: #334e68;
font-size: 0.84rem;
font-weight: 700;
padding: 10px 12px;
text-align: center;
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
--hero-inner-radius: 14px;
}

.hero-panel .card-body {
border-radius: 16px;
}

.hero-panel .table-responsive {
border: 1px solid #d7e7ef;
border-radius: var(--hero-inner-radius);
overflow: hidden;
background: #fff;
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

.hero-panel .metric-box {
border-radius: var(--hero-inner-radius);
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

.billing-toggle {
display: inline-flex;
align-items: center;
border: 1px solid var(--mx-border);
background: #fff;
border-radius: 999px;
overflow: hidden;
}

.toggle-btn {
border: 0;
background: transparent;
color: #486581;
font-weight: 700;
font-size: 0.82rem;
padding: 8px 14px;
}

.toggle-btn span {
color: #0b6f65;
margin-left: 4px;
}

.toggle-btn.active {
background: #e7f7f3;
color: #0b6f65;
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
position: relative;
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

.featured-tag {
position: absolute;
top: 10px;
right: 10px;
border-radius: 999px;
background: #0f766e;
color: #fff;
font-size: 0.68rem;
font-weight: 700;
padding: 4px 9px;
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

.faq-layout {
align-items: flex-start;
}

.custom-accordion {
border: 0;
}

.custom-accordion :deep(.el-collapse) {
border-top: 0;
border-bottom: 0;
background: transparent;
}

.custom-accordion :deep(.el-collapse-item) {
border: 1px solid var(--mx-border);
border-radius: 12px;
overflow: hidden;
margin-bottom: 10px;
background: #fff;
box-shadow: 0 8px 20px rgba(15, 23, 42, 0.04);
}

.custom-accordion :deep(.el-collapse-item__header) {
border-bottom: 0;
height: auto;
min-height: 56px;
padding: 12px 16px;
font-weight: 700;
color: #243b53;
background: #fff;
line-height: 1.3;
}

.custom-accordion :deep(.el-collapse-item__header.is-active) {
color: #0b6f65;
background: #ecfaf7;
}

.custom-accordion :deep(.el-collapse-item__arrow) {
color: #486581;
font-weight: 700;
}

.custom-accordion :deep(.el-collapse-item__wrap) {
border-bottom: 0;
}

.custom-accordion :deep(.el-collapse-item__content) {
padding: 0 16px 14px;
color: #486581;
line-height: 1.65;
}

.faq-item-title {
display: inline-flex;
align-items: center;
gap: 10px;
font-size: 0.96rem;
white-space: normal;
}

.faq-answer {
font-size: 0.95rem;
}

.faq-topic-list {
display: flex;
flex-wrap: wrap;
gap: 8px;
margin-bottom: 14px;
}

.faq-topic-pill {
border: 1px solid var(--mx-border);
background: #fff;
color: #486581;
border-radius: 999px;
padding: 6px 11px;
font-size: 0.76rem;
font-weight: 700;
}

.faq-index {
display: inline-flex;
align-items: center;
justify-content: center;
min-width: 42px;
border-radius: 999px;
padding: 3px 10px;
background: #e7f7f3;
color: #0b6f65;
font-size: 0.72rem;
font-weight: 800;
}

.faq-help-card {
border: 1px solid var(--mx-border);
border-radius: 16px;
background: linear-gradient(180deg, #ffffff, #f6fbfc);
padding: 18px;
position: sticky;
top: 90px;
}

.help-kicker {
margin: 0 0 8px;
color: #0b6f65;
letter-spacing: 0.07em;
text-transform: uppercase;
font-size: 0.72rem;
font-weight: 800;
}

.faq-help-card h4 {
margin-bottom: 8px;
}

.help-copy {
margin: 0 0 12px;
color: #627d98;
font-size: 0.92rem;
}

.help-list {
display: grid;
gap: 8px;
margin-bottom: 10px;
}

.help-item {
border: 1px solid var(--mx-border);
border-radius: 12px;
background: #fff;
padding: 10px;
display: grid;
grid-template-columns: auto 1fr;
gap: 10px;
align-items: center;
}

.help-icon {
width: 34px;
height: 34px;
border-radius: 10px;
background: #e7f7f3;
color: #0b6f65;
display: grid;
place-items: center;
}

.help-item strong {
display: block;
font-size: 0.88rem;
color: #243b53;
}

.help-item small {
color: #627d98;
font-size: 0.78rem;
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

@keyframes tickerSlide {
0% {
transform: translateX(0);
}
100% {
transform: translateX(-50%);
}
}

@media (max-width: 991.98px) {
.hero-section {
padding-top: 2.6rem;
}

.site-nav .navbar-collapse {
padding-top: 10px;
}

.header-auth {
margin-top: 10px;
}

.partners-grid {
grid-template-columns: repeat(3, minmax(0, 1fr));
}

.cta-box {
padding: 1.4rem;
}

.faq-help-card {
margin-top: 4px;
position: static;
}
}

@media (max-width: 575.98px) {
.section-block {
padding: 2.6rem 0;
}

.hero-copy h1 {
font-size: 1.62rem;
}

.ticker-track {
gap: 16px;
padding: 8px 14px;
}

.partners-grid {
grid-template-columns: repeat(2, minmax(0, 1fr));
}

.billing-toggle {
width: 100%;
}

.toggle-btn {
flex: 1;
}

.plan-list {
padding-left: 15px;
}

.faq-index {
min-width: 36px;
}
}
</style>
