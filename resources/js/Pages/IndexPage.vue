<script setup>
import { Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { usePage } from '@inertiajs/vue3';

const currentYear = new Date().getFullYear();
const billingCycle = ref('monthly');
const activeFaq = ref('0');

const metrics = [
{ label: 'Active Buyers', value: '1,850+' },
{ label: 'Listed Lots', value: '9,200+' },
{ label: 'Monthly Volume', value: '48,000 MT' },
{ label: 'Avg Settlement', value: 'T+2 Days' },
];

const partners = [
{
name: 'Atlas Roasters',
segment: 'Global Coffee Buyer',
status: 'Buyer',
tone: 'buyer',
icon: 'bi-cup-hot-fill',
goodFor: 'Premium coffee contracts',
typicalLot: '12-30 MT',
delivery: '2-4 days',
region: 'East Africa',
},
{
name: 'Greenline Commodities',
segment: 'Regional Export Partner',
status: 'Exporter',
tone: 'exporter',
icon: 'bi-globe2',
goodFor: 'Export-ready volumes',
typicalLot: '20-60 MT',
delivery: '3-5 days',
region: 'COMESA',
},
{
name: 'Harvest Guild',
segment: 'Farmer Cooperative Network',
status: 'Cooperative',
tone: 'cooperative',
icon: 'bi-diagram-3-fill',
goodFor: 'Origin-traceable supply',
typicalLot: '8-25 MT',
delivery: '2-3 days',
region: 'Great Lakes',
},
{
name: 'Northstar Foods',
segment: 'Food Industry Buyer',
status: 'Procurement',
tone: 'procurement',
icon: 'bi-building-check',
goodFor: 'Large monthly demand',
typicalLot: '25-80 MT',
delivery: '1-3 days',
region: 'EAC',
},
{
name: 'Horizon Export',
segment: 'Cross-Border Exporter',
status: 'Exporter',
tone: 'exporter',
icon: 'bi-airplane-fill',
goodFor: 'Container-scale orders',
typicalLot: '30-100 MT',
delivery: '3-6 days',
region: 'Intercontinental',
},
{
name: 'Summit Trading',
segment: 'Brokered Matchmaking',
status: 'Broker',
tone: 'broker',
icon: 'bi-bar-chart-line-fill',
goodFor: 'Fast buyer-seller matching',
typicalLot: '10-40 MT',
delivery: '1-2 days',
region: 'Regional Hub',
},
];

const trustSignals = [
{ label: 'Identity Checked', icon: 'bi-shield-check' },
{ label: 'Quality Info Up Front', icon: 'bi-file-earmark-lock2-fill' },
{ label: 'Payment and Delivery Tracked', icon: 'bi-cash-coin' },
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



const page=usePage();
const marketBoard=computed(() => {
const source = page.props.marketBoard;
if (Array.isArray(source)) return source;
return source?.data ?? [];
});
const batchListed=computed(()=>page.props.batchListed ?? { buyers: 0, batches: 0 });

const formatInteger = (value) =>
Number(value ?? 0).toLocaleString('en-UG', { maximumFractionDigits: 0 });
const formatCurrency = (value) =>
`Shs. ${Number(value ?? 0).toLocaleString('en-UG', { minimumFractionDigits: 0, maximumFractionDigits: 2 })}`;

const marketRows = computed(() =>
marketBoard.value
.map((row) => {
const commodity = row.commodity ?? 'Unknown';
const type = row.type ?? null;
const grade = row.grade ?? null;
const min = Number(row.min_price ?? 0);
const max = Number(row.max_price ?? 0);
const quantity = Number(row.quantity ?? 0);
const midpoint = min > 0 && max > 0 ? (min + max) / 2 : max || min || 1;
const spreadValue = Math.max(max - min, 0);
const spreadPct = midpoint > 0 ? (spreadValue / midpoint) * 100 : 0;
const notional = quantity * midpoint;

return {
commodity,
instrument: [commodity, type, grade].filter(Boolean).join(' · '),
quantity,
min,
max,
spreadValue,
spreadPct,
notional,
};
})
.sort((a, b) => b.quantity - a.quantity),
);

const tickerRows = computed(() => [...marketRows.value, ...marketRows.value]);
const heroRows = computed(() => marketRows.value.slice(0, 6));
const marketSearch = ref('');
const selectedCommodity = ref('all');

const commodityOptions = computed(() =>
[...new Set(marketRows.value.map((row) => row.commodity).filter(Boolean))],
);

const filteredMarketRows = computed(() => {
const query = marketSearch.value.trim().toLowerCase();

return marketRows.value.filter((row) => {
const matchesCommodity = selectedCommodity.value === 'all' || row.commodity === selectedCommodity.value;
const matchesQuery = !query || row.instrument.toLowerCase().includes(query);
return matchesCommodity && matchesQuery;
});
});

const totalMarketDepth = computed(() =>
marketRows.value.reduce((sum, row) => sum + row.quantity, 0),
);

const averageSpreadPct = computed(() =>
marketRows.value.length === 0
? 0
: marketRows.value.reduce((sum, row) => sum + row.spreadPct, 0) / marketRows.value.length,
);

const totalNotional = computed(() =>
marketRows.value.reduce((sum, row) => sum + row.notional, 0),
);

const marketLeader = computed(() => marketRows.value[0]?.instrument ?? 'No active instruments');
const trustStats = computed(() => {
const roles = new Set(partners.map((partner) => partner.status)).size;

return [
{ label: 'Verified Trade Partners', value: `${partners.length}+`, note: 'Buyers, exporters, and cooperatives' },
{ label: 'Partner Types', value: roles, note: 'Different buyer and supplier profiles' },
{ label: 'Products Live Now', value: marketRows.value.length, note: 'Fresh listings on the board today' },
{ label: 'Active Buyer Demand', value: formatInteger(batchListed.value.buyers ?? 0), note: 'Current buying activity' },
];
});

const marketSession = computed(() => (marketRows.value.length > 0 ? 'Live Session' : 'Session Pending'));
const partnerCoverage = computed(() => `${new Set(partners.map((partner) => partner.region)).size} trade zones`);

const buyerFlow = [
{ step: '1', title: 'Compare Offers', note: 'Review quality, lot size, and delivery timeline.' },
{ step: '2', title: 'Confirm Terms', note: 'Lock price and quantity with a clear contract.' },
{ step: '3', title: 'Settle and Receive', note: 'Track payment and delivery to completion.' },
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
<Link href="/start-trading" class="btn btn-brand btn-lg">Start Trading</Link>
<Link href="/live-markets" class="btn btn-soft btn-lg">View Live Markets</Link>
</div>
</div>
</div>
<div class="col-lg-6">
<div class="card hero-panel reveal delay-1">
<div class="card-body p-4">
<div class="d-flex justify-content-between align-items-center mb-3">
<h5 class="mb-0">Commodity Price Index</h5>
<span class="badge badge-live">Live</span>
</div>
<div class="table-responsive">
<table class="table align-middle mb-0 market-table">
<thead>
<tr>
<th>Commodity</th>
<th>Weight (Kgs)</th>
<th>Min Price (UGX)</th>
<th>Max Price (UGX)</th>
</tr>
</thead>
<tbody>
<tr v-for="(row, index) in heroRows" :key="`${row.instrument}-${index}`">
<td>
<strong class="d-block text-capitalize">{{ row.commodity }}</strong>
<small class="text-muted text-capitalize">{{ row.instrument }}</small>
</td>
<td>{{ formatInteger(row.quantity) }}</td>
<td>{{ formatCurrency(row.min) }}</td>
<td>{{ formatCurrency(row.max) }}</td>
</tr>
<tr v-if="heroRows.length === 0">
<td colspan="4" class="text-center text-muted py-3">No market board data available.</td>
</tr>
</tbody>
</table>
</div>
<div class="row g-2 mt-2">


<div class="col-6 col-md-3">
<div class="metric-box">
<div class="metric-label">
<i class="bi bi-people-fill metric-icon" aria-hidden="true"></i>
<small>Active Buyers</small>
</div>
<strong>
{{ batchListed.buyers }}
</strong>
</div>
</div>




<div class="col-6 col-md-3">
<div class="metric-box">
<div class="metric-label">
<i class="bi bi-box-seam-fill metric-icon" aria-hidden="true"></i>
<small>Batches Listed</small>
</div>
<strong>
{{ batchListed.batches }}
</strong>
</div>
</div>

<div class="col-6 col-md-3">
<div class="metric-box">
<div class="metric-label">
<i class="bi bi-layers-fill metric-icon" aria-hidden="true"></i>
<small>Depth (Kgs)</small>
</div>
<strong>{{ formatInteger(totalMarketDepth) }}</strong>
</div>
</div>

<div class="col-6 col-md-3">
<div class="metric-box">
<div class="metric-label">
<i class="bi bi-activity metric-icon" aria-hidden="true"></i>
<small>Avg Spread</small>
</div>
<strong>{{ averageSpreadPct.toFixed(2) }}%</strong>
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

<span v-for="(row, index) in tickerRows" :key="`${row.instrument}-${index}`">


{{ row.instrument }} · {{ formatInteger(row.quantity) }} Kgs · Low {{ formatCurrency(row.min) }} · High {{ formatCurrency(row.max) }}



</span>
<span v-if="marketRows.length === 0">No market board data available.</span>

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

<div class="market-insight-grid reveal">
<article class="insight-card">
<div class="insight-head">
<i class="bi bi-graph-up-arrow insight-icon" aria-hidden="true"></i>
<small>Lead Instrument</small>
</div>
<strong class="text-capitalize">{{ marketLeader }}</strong>
</article>
<article class="insight-card">
<div class="insight-head">
<i class="bi bi-currency-exchange insight-icon" aria-hidden="true"></i>
<small>Total Notional</small>
</div>
<strong>{{ formatCurrency(totalNotional) }}</strong>
</article>
<article class="insight-card">
<div class="insight-head">
<i class="bi bi-table insight-icon" aria-hidden="true"></i>
<small>Visible Rows</small>
</div>
<strong>{{ filteredMarketRows.length }} / {{ marketRows.length }}</strong>
</article>
</div>

<div class="card glass-card reveal delay-1">
<div class="card-body p-0">
<div class="table-responsive">
<table class="table table-hover align-middle mb-0">
<thead>
<tr>
<th>Commodity</th>
<th>Weight (Kgs)</th>
<th>Low (UGX)</th>
<th>High (UGX)</th>
<th>Spread (UGX)</th>
<th>Spread (%)</th>
</tr>
</thead>
<tbody>
<tr v-for="(row, index) in filteredMarketRows" :key="`${row.instrument}-${index}`">
<td><strong class="text-capitalize">{{ row.instrument }}</strong></td>
<td>{{ formatInteger(row.quantity) }}</td>
<td>{{ formatCurrency(row.min) }}</td>
<td>{{ formatCurrency(row.max) }}</td>
<td>{{ formatCurrency(row.spreadValue) }}</td>
<td><span class="spread-pill">{{ row.spreadPct.toFixed(2) }}%</span></td>
</tr>
<tr v-if="filteredMarketRows.length === 0">
<td colspan="6" class="text-center text-muted py-4">
{{ marketRows.length === 0 ? 'No market board data available.' : 'No rows match the current filter.' }}
</td>
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
<div class="footer-shell">
<div class="container">
<div class="footer-contact-strip">
<article class="footer-contact-card">
<span class="footer-icon"><i class="bi bi-geo-alt-fill" aria-hidden="true"></i></span>
<div>
<h6>Address</h6>
<p>Kampala, Uganda</p>
</div>
</article>
<article class="footer-contact-card">
<span class="footer-icon"><i class="bi bi-envelope-fill" aria-hidden="true"></i></span>
<div>
<h6>Mail Us</h6>
<p>support@commodityorigin.com</p>
</div>
</article>
<article class="footer-contact-card">
<span class="footer-icon"><i class="bi bi-telephone-fill" aria-hidden="true"></i></span>
<div>
<h6>Telephone</h6>
<p>+256 700 000 000</p>
</div>
</article>
<article class="footer-contact-card">
<span class="footer-icon"><i class="bi bi-headset" aria-hidden="true"></i></span>
<div>
<h6>Support Desk</h6>
<p>Mon - Sat, 8:00AM - 7:00PM</p>
</div>
</article>
</div>

<div class="footer-main-grid">
<section class="footer-column">
<h5>Newsletter</h5>
<p class="footer-copy">
Get market moves, buyer demand signals, and commodity briefings delivered weekly.
</p>
<div class="footer-signup">
<input type="email" placeholder="Enter your email" aria-label="Email address" />
<button type="button">Sign Up</button>
</div>
</section>

<section class="footer-column">
<h5>Customer Service</h5>
<nav class="footer-link-list" aria-label="Customer service links">
<Link href="/start-trading">How to Start Trading</Link>
<Link href="/live-markets">Live Market Board</Link>
<Link href="/register">Open Buyer Account</Link>
<Link href="/login">Sign In</Link>
<a href="#">Trade Support</a>
<a href="#">Settlement Help</a>
</nav>
</section>

<section class="footer-column">
<h5>Information</h5>
<nav class="footer-link-list" aria-label="Information links">
<a href="#">About Commodity Origin</a>
<a href="#">Delivery Information</a>
<a href="#">Privacy Policy</a>
<a href="#">Terms & Conditions</a>
<a href="#">Quality Standards</a>
<a href="#">FAQ</a>
</nav>
</section>

<section class="footer-column">
<h5>Explore</h5>
<nav class="footer-link-list" aria-label="Explore links">
<Link href="/live-markets">View Exchange Board</Link>
<Link href="/start-trading">Buyer Onboarding</Link>
<a href="#">Export Partnerships</a>
<a href="#">Cooperative Supply</a>
<a href="#">Track Your Order</a>
<a href="#">Contact Team</a>
</nav>
</section>
</div>

<div class="footer-bottom">
<small>© {{ currentYear }} Commodity Origin. All rights reserved.</small>
<a href="#" class="footer-up" aria-label="Back to top">
<i class="bi bi-arrow-up"></i>
</a>
</div>
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
padding: 1.35rem 0 2.35rem;
}

.partners-shell {
border: 1px solid #d6e4ef;
border-radius: 22px;
background:
radial-gradient(460px 200px at 0% 0%, rgba(217, 241, 232, 0.72), transparent 64%),
radial-gradient(420px 180px at 100% 100%, rgba(230, 240, 255, 0.72), transparent 64%),
#ffffff;
padding: 24px;
}

.partners-head {
display: flex;
justify-content: space-between;
align-items: flex-start;
gap: 18px;
margin-bottom: 14px;
}

.partners-label {
font-size: 0.71rem;
color: #4f728f;
letter-spacing: 0.09em;
text-transform: uppercase;
margin-bottom: 0.45rem;
font-weight: 800;
}

.partners-title {
margin: 0;
font-size: 1.28rem;
color: #1f3b55;
line-height: 1.25;
}

.partners-subtitle {
margin: 0.65rem 0 0;
max-width: 62ch;
font-size: 0.9rem;
color: #5f7892;
line-height: 1.58;
}

.partners-badges {
display: flex;
align-items: center;
flex-wrap: wrap;
gap: 9px;
margin-top: 11px;
}

.session-pill {
display: inline-flex;
align-items: center;
gap: 6px;
height: 31px;
padding: 0 11px;
border-radius: 999px;
font-size: 0.72rem;
font-weight: 800;
background: #e8f7f1;
color: #0b6b5f;
border: 1px solid #bce6d8;
}

.session-dot {
width: 7px;
height: 7px;
border-radius: 50%;
background: #0b6b5f;
box-shadow: 0 0 0 6px rgba(11, 107, 95, 0.12);
}

.coverage-pill {
display: inline-flex;
align-items: center;
gap: 6px;
height: 31px;
padding: 0 11px;
border-radius: 999px;
font-size: 0.72rem;
font-weight: 800;
background: #ecf5ff;
border: 1px solid #c7dcf2;
color: #1f507b;
}

.coverage-pill i {
color: #2e77ad;
}

.partners-head-actions {
display: flex;
align-items: center;
gap: 9px;
}

.partners-layout {
display: grid;
grid-template-columns: 1.45fr 1fr;
gap: 14px;
align-items: stretch;
}

.partners-left {
display: grid;
gap: 11px;
}

.partners-inline-stats {
display: grid;
grid-template-columns: repeat(2, minmax(0, 1fr));
gap: 9px;
}

.inline-stat {
border: 1px solid #dbe8f1;
border-radius: 12px;
background: #fbfdff;
padding: 11px 12px;
display: grid;
gap: 4px;
}

.inline-stat small {
font-size: 0.67rem;
letter-spacing: 0.04em;
text-transform: uppercase;
color: #607a94;
}

.inline-stat strong {
font-size: 1.02rem;
line-height: 1.2;
color: #223c57;
}

.inline-stat span {
font-size: 0.72rem;
line-height: 1.35;
color: #7289a0;
}

.partners-signals {
display: flex;
flex-wrap: wrap;
gap: 8px;
}

.signal-pill {
display: inline-flex;
align-items: center;
gap: 8px;
min-height: 32px;
padding: 0 11px;
border: 1px solid #d8e6f1;
border-radius: 999px;
background: #f9fcff;
font-size: 0.75rem;
font-weight: 700;
color: #335878;
}

.signal-pill i {
color: #0b7d6f;
}

.partner-mini-grid {
display: grid;
grid-template-columns: repeat(3, minmax(0, 1fr));
gap: 9px;
}

.partner-mini {
border: 1px solid #dbe7f1;
border-radius: 12px;
background: #ffffff;
padding: 11px 11px 10px;
display: grid;
gap: 4px;
}

.partner-mini-head {
display: flex;
align-items: center;
gap: 8px;
}

.partner-mini-head i {
font-size: 0.92rem;
color: #20689f;
}

.partner-mini strong {
font-size: 0.79rem;
line-height: 1.25;
color: #274664;
}

.partner-mini p {
margin: 0;
font-size: 0.73rem;
line-height: 1.35;
color: #637d96;
}

.partner-mini small {
font-size: 0.7rem;
color: #7890a6;
}

.partners-right {
border: 1px solid #dbe8f2;
border-radius: 14px;
background: linear-gradient(180deg, #ffffff 0%, #f6fbff 100%);
padding: 13px;
display: grid;
gap: 10px;
align-content: start;
}

.partners-right-title {
margin: 0;
font-size: 0.98rem;
line-height: 1.25;
color: #254867;
}

.partners-right-subtitle {
margin: 0;
font-size: 0.77rem;
line-height: 1.45;
color: #67839d;
}

.guidance-steps {
display: grid;
gap: 8px;
}

.guidance-step {
display: grid;
grid-template-columns: auto 1fr;
align-items: start;
gap: 9px;
padding: 10px 10px;
border: 1px solid #dce8f3;
border-radius: 10px;
background: #fcfeff;
}

.flow-step {
width: 22px;
height: 22px;
display: inline-grid;
place-items: center;
border-radius: 50%;
background: #e6f2ff;
color: #1f65a2;
font-size: 0.66rem;
font-weight: 800;
}

.guidance-step strong {
display: block;
font-size: 0.79rem;
line-height: 1.2;
color: #244562;
}

.guidance-step small {
display: block;
margin-top: 2px;
font-size: 0.72rem;
line-height: 1.36;
color: #64809b;
}

.guidance-actions {
display: grid;
grid-template-columns: 1fr 1fr;
gap: 8px;
}

.guidance-actions .btn {
display: inline-flex;
justify-content: center;
align-items: center;
min-height: 36px;
font-size: 0.79rem;
font-weight: 800;
white-space: nowrap;
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

.metric-label {
display: inline-flex;
align-items: center;
gap: 6px;
}

.metric-icon {
font-size: 0.78rem;
color: #0b6f65;
}

.metric-box small {
color: #627d98;
}

.market-insight-grid {
display: grid;
grid-template-columns: repeat(3, minmax(0, 1fr));
gap: 10px;
margin-bottom: 12px;
}

.insight-card {
border: 1px solid var(--mx-border);
border-radius: 12px;
background: rgba(255, 255, 255, 0.88);
padding: 10px 12px;
display: grid;
gap: 2px;
}

.insight-head {
display: inline-flex;
align-items: center;
gap: 6px;
}

.insight-icon {
font-size: 0.82rem;
color: #0b6f65;
}

.insight-card small {
font-size: 0.74rem;
color: #627d98;
text-transform: uppercase;
letter-spacing: 0.04em;
}

.insight-card strong {
font-size: 0.95rem;
color: #243b53;
}

.market-toolbar {
display: grid;
grid-template-columns: minmax(0, 1fr) 220px;
gap: 10px;
margin-bottom: 12px;
}

.toolbar-search-wrap,
.toolbar-select-wrap {
position: relative;
}

.toolbar-icon {
position: absolute;
left: 11px;
top: 50%;
transform: translateY(-50%);
font-size: 0.78rem;
color: #5c7188;
pointer-events: none;
}

.toolbar-search,
.toolbar-select {
height: 40px;
width: 100%;
border: 1px solid #cfe1eb;
border-radius: 10px;
background: #fff;
padding: 0 12px 0 32px;
color: #334e68;
font-size: 0.88rem;
}

.toolbar-search:focus,
.toolbar-select:focus {
outline: none;
border-color: #0e8a7d;
box-shadow: 0 0 0 3px rgba(14, 138, 125, 0.12);
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
margin-top: 0.4rem;
color: #e8f0f7;
}

.footer-shell {
border-top: 1px solid rgba(255, 255, 255, 0.09);
background:
radial-gradient(500px 220px at 0% 0%, rgba(14, 138, 125, 0.2), transparent 70%),
radial-gradient(460px 220px at 100% 100%, rgba(242, 167, 75, 0.14), transparent 70%),
linear-gradient(180deg, #1a334b 0%, #142e47 50%, #102a43 100%);
}

.footer-contact-strip {
display: grid;
grid-template-columns: repeat(4, minmax(0, 1fr));
gap: 0.9rem;
align-items: center;
padding: 1.1rem 0;
border-bottom: 1px solid rgba(214, 229, 241, 0.22);
}

.footer-contact-card {
display: flex;
align-items: center;
gap: 0.72rem;
border: 1px solid rgba(214, 229, 241, 0.18);
border-radius: 14px;
padding: 0.85rem 0.9rem;
background: rgba(255, 255, 255, 0.04);
min-height: 94px;
}

.footer-icon {
width: 46px;
height: 46px;
border-radius: 50%;
display: inline-grid;
place-items: center;
background: linear-gradient(135deg, var(--mx-brand), var(--mx-brand-dark));
color: #ffffff;
font-size: 1.07rem;
flex-shrink: 0;
}

.footer-contact-card h6 {
margin: 0;
font-size: 0.82rem;
font-weight: 800;
color: #f4f8fc;
}

.footer-contact-card p {
margin: 0;
font-size: 0.76rem;
line-height: 1.5;
color: #b8cad9;
}

.footer-main-grid {
display: grid;
grid-template-columns: 1.2fr 1fr 1fr 1fr;
gap: 1.15rem;
padding: 1.15rem 0 1.05rem;
}

.footer-column h5 {
margin: 0 0 0.55rem;
font-size: 0.84rem;
line-height: 1.2;
color: var(--mx-accent);
font-weight: 800;
}

.footer-copy {
margin: 0;
font-size: 0.78rem;
line-height: 1.72;
color: #b9c9d8;
max-width: 34ch;
}

.footer-signup {
margin-top: 0.75rem;
display: grid;
grid-template-columns: 1fr auto;
align-items: center;
gap: 0.45rem;
padding: 0.3rem;
background: rgba(255, 255, 255, 0.08);
border: 1px solid rgba(214, 229, 241, 0.2);
border-radius: 999px;
}

.footer-signup input {
border: 0;
outline: 0;
background: transparent;
min-width: 0;
padding: 0 0.7rem;
height: 36px;
font-size: 0.82rem;
color: #f4f9ff;
}

.footer-signup input::placeholder {
color: #9eb2c5;
}

.footer-signup button {
height: 36px;
padding: 0 0.95rem;
border-radius: 999px;
border: 0;
background: var(--mx-accent);
color: #16354f;
font-size: 0.8rem;
font-weight: 700;
}

.footer-link-list {
display: grid;
gap: 0.42rem;
}

.footer-link-list a {
display: inline-flex;
align-items: center;
gap: 0.5rem;
font-size: 0.79rem;
line-height: 1.45;
color: #c6d5e3;
text-decoration: none;
}

.footer-link-list a::before {
content: '›';
font-size: 1.02rem;
line-height: 1;
color: #8fa8bd;
}

.footer-link-list a:hover {
color: #ffffff;
}

.footer-bottom {
display: flex;
align-items: center;
justify-content: space-between;
gap: 0.8rem;
padding: 0.95rem 0 1.05rem;
border-top: 1px solid rgba(214, 229, 241, 0.2);
}

.footer-bottom small {
margin: 0;
font-size: 0.75rem;
line-height: 1.4;
color: #b8cad9;
}

.footer-up {
width: 42px;
height: 42px;
border-radius: 50%;
display: inline-grid;
place-items: center;
background: var(--mx-accent);
color: #17354f;
text-decoration: none;
font-size: 1rem;
font-weight: 800;
}

.footer-up:hover {
color: #17354f;
filter: brightness(0.97);
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

.partners-inline-stats {
grid-template-columns: repeat(2, minmax(0, 1fr));
}

.partners-layout {
grid-template-columns: 1fr;
}

.partner-mini-grid {
grid-template-columns: repeat(2, minmax(0, 1fr));
}

.partners-head {
flex-direction: column;
gap: 12px;
}

.partners-head-actions {
width: 100%;
}

.partners-head-actions .btn {
flex: 1;
}

.cta-box {
padding: 1.4rem;
}

.faq-help-card {
margin-top: 4px;
position: static;
}

.market-insight-grid {
grid-template-columns: repeat(2, minmax(0, 1fr));
}

.footer-contact-strip {
grid-template-columns: repeat(2, minmax(0, 1fr));
}

.footer-main-grid {
grid-template-columns: repeat(2, minmax(0, 1fr));
}
}

@media (max-width: 767.98px) {
.partners-inline-stats {
grid-template-columns: 1fr;
}

.partner-mini-grid,
.guidance-actions {
grid-template-columns: 1fr;
}

.session-pill,
.coverage-pill {
width: 100%;
justify-content: center;
}

.partners-badges,
.partners-head-actions {
flex-direction: column;
align-items: stretch;
}

.footer-contact-strip,
.footer-main-grid {
grid-template-columns: 1fr;
}

.footer-contact-card {
min-height: 0;
}

.footer-bottom {
flex-direction: column;
align-items: flex-start;
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

.partners-inline-stats {
grid-template-columns: 1fr;
}

.session-pill,
.coverage-pill {
width: 100%;
justify-content: center;
}

.partners-badges,
.partners-head-actions {
flex-direction: column;
align-items: stretch;
}

.partners-shell {
padding: 18px 14px;
}

.footer-signup {
grid-template-columns: 1fr;
border-radius: 14px;
}

.footer-signup input,
.footer-signup button {
width: 100%;
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

.market-insight-grid {
grid-template-columns: 1fr;
}

.market-toolbar {
grid-template-columns: 1fr;
}
}
</style>
