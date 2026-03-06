<script setup>
import HomeLayout from '@/Layouts/HomeLayout.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();

const marketBoard = computed(() => {
const source = page.props.marketBoard;
if (Array.isArray(source)) return source;
return source?.data ?? [];
});

const batchListed = computed(() => page.props.batchListed ?? { buyers: 0, batches: 0 });

const tradingRows = computed(() =>
marketBoard.value
.map((row) => {
const commodity = row.commodity ?? 'Commodity';
const type = row.type ?? '';
const grade = row.grade ?? '';
const quantity = Number(row.quantity ?? 0);
const min = Number(row.min_price ?? 0);
const max = Number(row.max_price ?? 0);
const mid = min > 0 && max > 0 ? (min + max) / 2 : max || min || 0;
const spread = Math.max(max - min, 0);
const spreadPct = mid > 0 ? (spread / mid) * 100 : 0;

return {
instrument: [commodity, type, grade].filter(Boolean).join(' · '),
commodity,
quantity,
min,
max,
mid,
spread,
spreadPct,
};
})
.sort((a, b) => b.quantity - a.quantity),
);

const opportunities = computed(() => tradingRows.value.slice(0, 8));
const leadingInstrument = computed(() => opportunities.value[0] ?? null);

const totalQuantity = computed(() =>
tradingRows.value.reduce((sum, row) => sum + row.quantity, 0),
);

const averageSpread = computed(() =>
tradingRows.value.length === 0
? 0
: tradingRows.value.reduce((sum, row) => sum + row.spread, 0) / tradingRows.value.length,
);

const totalNotional = computed(() =>
tradingRows.value.reduce((sum, row) => sum + (row.quantity * row.mid), 0),
);

const liveStatus = computed(() => (tradingRows.value.length > 0 ? 'Live Session' : 'Awaiting Listings'));

const lastUpdated = computed(() =>
new Intl.DateTimeFormat('en-UG', {
dateStyle: 'medium',
timeStyle: 'short',
}).format(new Date()),
);

const tradingSteps = [
{
icon: 'bi-person-check-fill',
title: 'Create your buyer account',
detail: 'Register with your business profile so sellers can trust your demand and terms.',
},
{
icon: 'bi-sliders2-vertical',
title: 'Set your sourcing preferences',
detail: 'Choose commodity, grade, lot size, delivery window, and budget range.',
},
{
icon: 'bi-graph-up-arrow',
title: 'Track live market offers',
detail: 'Compare active listings by price range, available quantity, and spread dynamics.',
},
{
icon: 'bi-file-earmark-check-fill',
title: 'Confirm terms and place order',
detail: 'Lock quantity and pricing, then execute with clear settlement and fulfillment milestones.',
},
];

const benefitCards = [
{
icon: 'bi-shield-check',
title: 'Verified counterparties',
copy: 'Trade with trusted cooperatives, exporters, and procurement-aligned suppliers.',
},
{
icon: 'bi-lightning-charge-fill',
title: 'Faster sourcing decisions',
copy: 'See live market signals in one view so your team can commit with confidence.',
},
{
icon: 'bi-journal-check',
title: 'Transparent execution flow',
copy: 'From quote to settlement, every step is clear, trackable, and easier to manage.',
},
];

const currencyFormatter = new Intl.NumberFormat('en-UG', {
minimumFractionDigits: 2,
maximumFractionDigits: 2,
});

const integerFormatter = new Intl.NumberFormat('en-UG', {
maximumFractionDigits: 0,
});

const formatCurrency = (value) => `UGX ${currencyFormatter.format(Number(value ?? 0))}`;
const formatInteger = (value) => integerFormatter.format(Number(value ?? 0));
</script>

<template>
<home-layout>
<div class="start-trading-page">
<section class="hero-grid">
<article class="hero-card">
<p class="eyebrow">Start Trading</p>
<h1>Launch your first commodity order in minutes</h1>
<p class="hero-copy">
Set your buying criteria, compare verified market offers, and execute with a transparent workflow.
</p>

<div class="hero-actions">
<Link href="/register" class="cta cta-primary">Create Trading Account</Link>
<Link href="/login" class="cta cta-ghost">Sign In</Link>
<Link href="/live-markets" class="cta cta-soft">View Live Markets</Link>
</div>

<div class="hero-metrics">
<article class="metric-chip">
<small>Active Buyers</small>
<strong>{{ formatInteger(batchListed.buyers) }}</strong>
</article>
<article class="metric-chip">
<small>Listed Batches</small>
<strong>{{ formatInteger(batchListed.batches) }}</strong>
</article>
<article class="metric-chip">
<small>Products Live</small>
<strong>{{ formatInteger(tradingRows.length) }}</strong>
</article>
<article class="metric-chip">
<small>Market Depth (Kgs)</small>
<strong>{{ formatInteger(totalQuantity) }}</strong>
</article>
</div>
</article>

<aside class="pulse-card">
<div class="pulse-top">
<span class="status-pill">
<span class="status-dot"></span>
{{ liveStatus }}
</span>
<small>Updated {{ lastUpdated }}</small>
</div>

<h2>{{ leadingInstrument?.instrument ?? 'Waiting for active listings' }}</h2>
<p class="pulse-copy">Most active instrument based on listed quantity.</p>

<div class="pulse-stats">
<article>
<small>Average Spread</small>
<strong>{{ formatCurrency(averageSpread) }}</strong>
</article>
<article>
<small>Notional Value</small>
<strong>{{ formatInteger(totalNotional) }}</strong>
</article>
</div>

<div class="pulse-list">
<article v-for="item in opportunities.slice(0, 4)" :key="`pulse-${item.instrument}`">
<div>
<strong>{{ item.instrument }}</strong>
<small>{{ formatInteger(item.quantity) }} Kgs</small>
</div>
<span>{{ formatCurrency(item.mid) }}</span>
</article>
<p v-if="opportunities.length === 0" class="empty-inline">No live products yet.</p>
</div>
</aside>
</section>

<section class="steps-shell">
<div class="section-head">
<p class="eyebrow">Trading Journey</p>
<h3>How to trade on Commodity Origin</h3>
</div>
<div class="steps-grid">
<article v-for="(step, index) in tradingSteps" :key="step.title" class="step-card">
<div class="step-top">
<span class="step-index">{{ index + 1 }}</span>
<i :class="['bi', step.icon]" aria-hidden="true"></i>
</div>
<h4>{{ step.title }}</h4>
<p>{{ step.detail }}</p>
</article>
</div>
</section>

<section class="board-shell">
<div class="board-head">
<div>
<p class="eyebrow">Live Opportunities</p>
<h3>Commodity Price Snapshot</h3>
</div>
<Link href="/live-markets" class="cta cta-soft board-link">Open Full Exchange Board</Link>
</div>

<div class="table-wrap">
<table class="table table-hover align-middle mb-0 board-table">
<thead>
<tr>
<th>Instrument</th>
<th>Quantity (Kgs)</th>
<th>Min (UGX)</th>
<th>Max (UGX)</th>
<th>Mid (UGX)</th>
<th>Spread</th>
</tr>
</thead>
<tbody>
<tr v-for="item in opportunities" :key="`board-${item.instrument}`">
<td><strong>{{ item.instrument }}</strong></td>
<td>{{ formatInteger(item.quantity) }}</td>
<td>{{ formatCurrency(item.min) }}</td>
<td>{{ formatCurrency(item.max) }}</td>
<td>{{ formatCurrency(item.mid) }}</td>
<td>
<span class="spread-pill">{{ item.spreadPct.toFixed(2) }}%</span>
</td>
</tr>
<tr v-if="opportunities.length === 0">
<td colspan="6" class="text-center py-4 text-muted">No market opportunities available yet.</td>
</tr>
</tbody>
</table>
</div>
</section>

<section class="benefits-grid">
<article v-for="item in benefitCards" :key="item.title" class="benefit-card">
<div class="benefit-icon">
<i :class="['bi', item.icon]" aria-hidden="true"></i>
</div>
<h4>{{ item.title }}</h4>
<p>{{ item.copy }}</p>
</article>
</section>

<section class="register-strip">
<div>
<p class="eyebrow">Ready to trade?</p>
<h3>Open your account and start sourcing with confidence</h3>
<p class="strip-copy">Register to unlock buyer tools, live market visibility, and faster negotiations.</p>
</div>
<div class="strip-actions">
<Link href="/register" class="cta cta-primary">Register Now</Link>
<Link href="/login" class="cta cta-ghost">Already have an account?</Link>
</div>
</section>
</div>
</home-layout>
</template>

<style scoped>
.start-trading-page {
--st-border: #dbe8f2;
--st-text: #18344f;
--st-muted: #5f7b95;
--st-brand: #0e8a7d;
--st-brand-strong: #0b6f65;
display: grid;
gap: 1.2rem;
padding-bottom: 2rem;
}

.hero-grid {
display: grid;
grid-template-columns: 1.32fr 0.92fr;
gap: 1rem;
}

.hero-card,
.pulse-card,
.steps-shell,
.board-shell,
.benefit-card,
.register-strip {
border: 1px solid var(--st-border);
border-radius: 22px;
background: rgba(255, 255, 255, 0.96);
box-shadow: 0 14px 28px rgba(16, 42, 67, 0.06);
}

.hero-card {
padding: 1.8rem;
background:
radial-gradient(430px 210px at 0% 0%, rgba(226, 245, 238, 0.9), transparent 64%),
radial-gradient(320px 180px at 100% 100%, rgba(233, 243, 255, 0.84), transparent 62%),
#ffffff;
}

.eyebrow {
margin: 0;
text-transform: uppercase;
letter-spacing: 0.08em;
font-size: 0.73rem;
font-weight: 800;
color: #2c6a9b;
}

h1 {
margin: 0.5rem 0 0;
font-size: clamp(1.75rem, 2.8vw, 2.35rem);
line-height: 1.18;
color: var(--st-text);
}

.hero-copy {
margin: 0.8rem 0 0;
max-width: 62ch;
font-size: 0.96rem;
line-height: 1.6;
color: var(--st-muted);
}

.hero-actions {
display: flex;
flex-wrap: wrap;
gap: 0.7rem;
margin-top: 1.2rem;
}

.cta {
display: inline-flex;
align-items: center;
justify-content: center;
min-height: 41px;
padding: 0 1rem;
border-radius: 12px;
border: 1px solid transparent;
font-size: 0.86rem;
font-weight: 800;
text-decoration: none;
white-space: nowrap;
}

.cta-primary {
background: linear-gradient(135deg, var(--st-brand), var(--st-brand-strong));
color: #ffffff;
border-color: #0b6f65;
}

.cta-primary:hover {
color: #ffffff;
filter: brightness(0.98);
}

.cta-ghost {
background: #ffffff;
border-color: #c7d9e8;
color: #1f486d;
}

.cta-ghost:hover {
background: #f4f9fd;
color: #1a3f60;
}

.cta-soft {
background: #e9f5f2;
border-color: #c3e3dd;
color: #0f6e64;
}

.cta-soft:hover {
background: #dff1ed;
color: #0c6158;
}

.hero-metrics {
display: grid;
grid-template-columns: repeat(4, minmax(0, 1fr));
gap: 0.65rem;
margin-top: 1.1rem;
}

.metric-chip {
border: 1px solid #d9e7f1;
border-radius: 12px;
padding: 0.7rem 0.75rem;
background: #ffffff;
display: grid;
gap: 0.3rem;
}

.metric-chip small {
font-size: 0.7rem;
color: #5d7891;
text-transform: uppercase;
letter-spacing: 0.04em;
}

.metric-chip strong {
font-size: 1rem;
line-height: 1.2;
color: #24425f;
}

.pulse-card {
padding: 1.35rem;
display: grid;
align-content: start;
gap: 0.85rem;
}

.pulse-top {
display: flex;
align-items: center;
justify-content: space-between;
gap: 0.7rem;
}

.status-pill {
display: inline-flex;
align-items: center;
gap: 0.4rem;
height: 29px;
padding: 0 0.7rem;
border-radius: 999px;
background: #e8f7f2;
border: 1px solid #bde6d7;
font-size: 0.73rem;
font-weight: 800;
color: #0d6c62;
}

.status-dot {
width: 7px;
height: 7px;
border-radius: 50%;
background: #0d6c62;
box-shadow: 0 0 0 5px rgba(13, 108, 98, 0.11);
}

.pulse-top small {
font-size: 0.72rem;
color: #69839d;
}

.pulse-card h2 {
margin: 0;
font-size: 1.14rem;
line-height: 1.3;
color: #213f5c;
}

.pulse-copy {
margin: -0.25rem 0 0;
font-size: 0.84rem;
line-height: 1.5;
color: #62809a;
}

.pulse-stats {
display: grid;
grid-template-columns: 1fr 1fr;
gap: 0.6rem;
}

.pulse-stats article {
border: 1px solid #d9e8f2;
border-radius: 11px;
background: #fbfdff;
padding: 0.65rem 0.7rem;
display: grid;
gap: 0.24rem;
}

.pulse-stats small {
font-size: 0.68rem;
letter-spacing: 0.04em;
text-transform: uppercase;
color: #5f7b95;
}

.pulse-stats strong {
font-size: 0.9rem;
line-height: 1.25;
color: #234462;
}

.pulse-list {
display: grid;
gap: 0.5rem;
}

.pulse-list article {
border: 1px solid #dde9f2;
border-radius: 10px;
padding: 0.55rem 0.65rem;
background: #ffffff;
display: grid;
grid-template-columns: 1fr auto;
align-items: center;
gap: 0.6rem;
}

.pulse-list strong {
display: block;
font-size: 0.78rem;
line-height: 1.3;
color: #294967;
}

.pulse-list small {
font-size: 0.71rem;
color: #6f88a1;
}

.pulse-list span {
font-size: 0.76rem;
font-weight: 800;
color: #1d4b75;
}

.empty-inline {
margin: 0;
font-size: 0.82rem;
color: #6f88a1;
}

.steps-shell,
.board-shell {
padding: 1.35rem;
}

.section-head {
margin-bottom: 0.85rem;
}

.section-head h3,
.board-head h3,
.register-strip h3 {
margin: 0.45rem 0 0;
font-size: 1.28rem;
line-height: 1.28;
color: #1f3f5b;
}

.steps-grid {
display: grid;
grid-template-columns: repeat(4, minmax(0, 1fr));
gap: 0.75rem;
}

.step-card {
border: 1px solid #dbe8f2;
border-radius: 13px;
background: #fbfdff;
padding: 0.95rem;
display: grid;
gap: 0.52rem;
}

.step-top {
display: flex;
align-items: center;
justify-content: space-between;
}

.step-index {
width: 28px;
height: 28px;
border-radius: 50%;
display: inline-grid;
place-items: center;
background: #e8f3ff;
color: #1c5e95;
font-size: 0.72rem;
font-weight: 800;
}

.step-top i {
font-size: 1rem;
color: #0f7a6f;
}

.step-card h4 {
margin: 0;
font-size: 0.9rem;
line-height: 1.3;
color: #244664;
}

.step-card p {
margin: 0;
font-size: 0.78rem;
line-height: 1.5;
color: #607d97;
}

.board-head {
display: flex;
align-items: center;
justify-content: space-between;
gap: 0.8rem;
margin-bottom: 0.8rem;
}

.board-link {
min-width: 218px;
}

.table-wrap {
overflow-x: auto;
border: 1px solid #dbe8f2;
border-radius: 14px;
background: #ffffff;
}

.board-table thead th {
font-size: 0.74rem;
text-transform: uppercase;
letter-spacing: 0.05em;
color: #4b6a85;
border-bottom-color: #d9e7f1;
white-space: nowrap;
}

.board-table td {
border-bottom-color: #ecf3f8;
color: #294866;
font-size: 0.86rem;
vertical-align: middle;
}

.spread-pill {
display: inline-flex;
align-items: center;
justify-content: center;
height: 29px;
padding: 0 0.58rem;
border-radius: 999px;
background: #eaf4ff;
border: 1px solid #cbe1f4;
color: #1d5f95;
font-size: 0.75rem;
font-weight: 800;
}

.benefits-grid {
display: grid;
grid-template-columns: repeat(3, minmax(0, 1fr));
gap: 0.8rem;
}

.benefit-card {
padding: 1rem;
display: grid;
gap: 0.5rem;
}

.benefit-icon {
width: 37px;
height: 37px;
border-radius: 11px;
display: inline-grid;
place-items: center;
background: #e7f5f2;
color: #0d746a;
font-size: 1rem;
}

.benefit-card h4 {
margin: 0;
font-size: 0.95rem;
line-height: 1.3;
color: #234461;
}

.benefit-card p {
margin: 0;
font-size: 0.83rem;
line-height: 1.5;
color: #607d97;
}

.register-strip {
padding: 1.35rem;
display: flex;
align-items: center;
justify-content: space-between;
gap: 1.1rem;
background:
radial-gradient(420px 220px at 0% 0%, rgba(222, 243, 234, 0.84), transparent 68%),
radial-gradient(360px 190px at 100% 100%, rgba(235, 244, 255, 0.9), transparent 64%),
#ffffff;
}

.strip-copy {
margin: 0.62rem 0 0;
font-size: 0.9rem;
line-height: 1.6;
color: #5e7a94;
max-width: 62ch;
}

.strip-actions {
display: flex;
flex-wrap: wrap;
justify-content: flex-end;
gap: 0.6rem;
}

@media (max-width: 1199.98px) {
.hero-metrics {
grid-template-columns: repeat(2, minmax(0, 1fr));
}

.steps-grid {
grid-template-columns: repeat(2, minmax(0, 1fr));
}
}

@media (max-width: 991.98px) {
.hero-grid {
grid-template-columns: 1fr;
}

.register-strip {
display: grid;
}

.strip-actions {
justify-content: flex-start;
}
}

@media (max-width: 767.98px) {
.hero-card,
.pulse-card,
.steps-shell,
.board-shell,
.benefit-card,
.register-strip {
border-radius: 16px;
}

.hero-card,
.pulse-card,
.steps-shell,
.board-shell,
.register-strip {
padding: 1rem;
}

.hero-actions,
.strip-actions {
display: grid;
grid-template-columns: 1fr;
}

.hero-metrics,
.pulse-stats,
.steps-grid,
.benefits-grid {
grid-template-columns: 1fr;
}

.board-head {
display: grid;
}

.board-link {
min-width: 0;
}
}
</style>
