<script setup>
import { Link } from '@inertiajs/vue3';

const highlights = [
{ name: 'Arabica Coffee', price: '$3.42 / kg', change: '+2.1%' },
{ name: 'Cocoa', price: '$4.88 / kg', change: '+1.4%' },
{ name: 'Maize', price: '$0.31 / kg', change: '-0.6%' },
{ name: 'Sesame', price: '$1.72 / kg', change: '+0.9%' },
];

const marketMetrics = [
{ label: 'Open Lots', value: '2,184' },
{ label: 'Best Bid', value: '$3.39 / kg' },
{ label: 'Best Ask', value: '$3.46 / kg' },
];

const recentTrades = [
{ commodity: 'Arabica AA', volume: '42 MT', price: '$3.44 / kg', time: '2m ago' },
{ commodity: 'Cocoa Grade 1', volume: '30 MT', price: '$4.91 / kg', time: '7m ago' },
{ commodity: 'Sesame Premium', volume: '18 MT', price: '$1.74 / kg', time: '12m ago' },
];

const features = [
{ title: 'Verified Listings', text: 'Only approved cooperatives and suppliers can publish export-ready lots.', icon: 'ni-shield-check' },
{ title: 'Transparent Pricing', text: 'Track bids, asks, and contract terms with full price visibility.', icon: 'ni-coins' },
{ title: 'Efficient Settlement', text: 'Move from listed lot to confirmed settlement through a guided workflow.', icon: 'ni-link-h' },
{ title: 'Live Bid Board', text: 'Monitor bid and ask movement in real time across active commodities.', icon: 'ni-activity-round' },
{ title: 'Counteroffer Threads', text: 'Negotiate using structured counteroffers with complete audit history.', icon: 'ni-chat-fill' },
{ title: 'Contract Templates', text: 'Generate standard trade contracts with flexible delivery and payment terms.', icon: 'ni-file-docs' },
];

const platformStats = [
{ label: 'Active Buyers', value: '1,250+' },
{ label: 'Listed Batches', value: '8,400+' },
{ label: 'Cooperatives', value: '180+' },
{ label: 'Countries', value: '14' },
];

const workflow = [
{ step: '1', title: 'List', text: 'Cooperatives publish verified commodity lots with quantity, grade, and location.', icon: 'ni-plus-circle' },
{ step: '2', title: 'Negotiate', text: 'Buyers review offers, compare lots, and agree on transparent contract terms.', icon: 'ni-chat-fill' },
{ step: '3', title: 'Contract', text: 'Both parties lock terms with digitally recorded contracts and milestone checkpoints.', icon: 'ni-file-text' },
{ step: '4', title: 'Settle', text: 'Trades are confirmed and tracked through a clear settlement workflow.', icon: 'ni-check-circle' },
];

const categories = ['Arabica Coffee', 'Robusta Coffee', 'Cocoa', 'Sesame', 'Maize', 'Beans', 'Groundnuts', 'Sorghum'];

const tradingTools = [
{ title: 'Price Alerts', text: 'Receive alerts when target bid, ask, or spread levels are reached.', icon: 'ni-bell' },
{ title: 'Lot Comparison', text: 'Compare quality, origin, volume, and price in one view.', icon: 'ni-list-thumb' },
{ title: 'Delivery Milestones', text: 'Track shipment, delivery, and settlement checkpoints per contract.', icon: 'ni-truck' },
{ title: 'Dispute Flags', text: 'Escalate and resolve quality or delivery issues with full trade context.', icon: 'ni-alert-circle' },
];

const trustSignals = [
{ title: 'Identity Verified', text: 'Every cooperative and trading account is validated before joining the marketplace.' },
{ title: 'Batch Traceability', text: 'Each lot is linked to source details, quality attributes, and listing history.' },
{ title: 'Audit-Friendly Records', text: 'Transactions and status updates are stored for clear reporting and compliance.' },
];

const faqs = [
{ q: 'Who can list commodities?', a: 'Registered cooperatives and approved suppliers can create and manage listings.' },
{ q: 'Can buyers compare multiple lots?', a: 'Yes. Buyers can compare price, quantity, grade, and origin before negotiating.' },
{ q: 'Do you support trading alerts and milestones?', a: 'Yes. Users can monitor price alerts and contract milestones from one dashboard.' },
{ q: 'What commodities are supported?', a: 'The platform supports coffee and several key staple and export crops.' },
];

const demandByMarket = [
{ country: 'UAE', volume: '3,840 MT', segment: 'Specialty Roasters', trend: '+5.4%' },
{ country: 'Saudi Arabia', volume: '3,360 MT', segment: 'Retail Chains', trend: '+4.1%' },
{ country: 'EU', volume: '6,480 MT', segment: 'Importers & Roasters', trend: '+6.8%' },
{ country: 'US', volume: '5,520 MT', segment: 'Commercial Buyers', trend: '+5.9%' },
];
</script>

<template>
<div class="coffee-exchange-page">
<header class="topbar">
<div class="topbar-inner">
<Link href="/" class="brand" aria-label="Commodity Origin home">
<img src="../../images/logo.png" alt="Commodity Origin logo" class="brand-logo" />
<div class="brand-copy">
<span class="brand-text">Commodity Origin</span>
<small>GLOBAL COFFEE EXCHANGE</small>
</div>
</Link>
<nav class="actions">
<Link href="/login" class="btn btn-ghost">Log In</Link>
<Link href="/register" class="btn btn-solid">Get Started</Link>
</nav>
</div>
</header>



<section class="hero-grid">
<article class="surface hero-copy">
<p class="eyebrow">Digital Commodity Exchange</p>
<h1 class="mt-5 mb-5">Trade Verified Coffee Lots With Speed and Confidence</h1>
<p class="lead">Commodity Origin connects buyers, cooperatives, and suppliers in one trusted marketplace for transparent pricing, dependable quality, and faster execution.</p>
<p class="hero-support">From first listing to final settlement, each trade step is visible, auditable, and structured for export-grade coffee operations.</p>

<div class="hero-meta">
<span><em class="icon ni ni-check-circle"></em>Verified participants</span>
<span><em class="icon ni ni-clock"></em>Faster deal cycles</span>
<span><em class="icon ni ni-shield-check"></em>Audit-ready records</span>
</div>

<div class="hero-proof">
<article v-for="stat in platformStats.slice(0, 3)" :key="`hero-${stat.label}`" class="hero-proof-item">
<strong>{{ stat.value }}</strong>
<small>{{ stat.label }}</small>
</article>
</div>

<div class="hero-cta">
<Link href="/register" class="btn btn-solid">Start Trading</Link>
<Link href="/login" class="btn btn-outline">View Live Market</Link>
</div>


</article>

<article class="surface market-panel">
<div class="panel-head">
<h3>Live Market Snapshot</h3>
<span class="live-pill"><em class="icon ni ni-activity"></em>Live</span>
</div>
<p class="panel-sub">Track benchmark prices, spreads, and confirmed lot executions in one real-time board.</p>

<div class="ticker">
<article v-for="item in highlights" :key="item.name" class="ticker-item">
<span class="name">{{ item.name }}</span>
<strong>{{ item.price }}</strong>
<small :class="item.change.startsWith('+') ? 'trend-up' : 'trend-down'">{{ item.change }}</small>
</article>
</div>

<div class="snapshot-metrics">
<article v-for="metric in marketMetrics" :key="metric.label" class="metric-pill">
<span>{{ metric.label }}</span>
<strong>{{ metric.value }}</strong>
</article>
</div>

<div class="trade-feed">
<p class="feed-title">Recent Trade Activity</p>
<article v-for="trade in recentTrades" :key="trade.commodity + trade.time" class="feed-row">
<div class="feed-main">
<h5>{{ trade.commodity }}</h5>
<p>{{ trade.volume }} traded</p>
</div>
<div class="feed-meta">
<strong>{{ trade.price }}</strong>
<small>{{ trade.time }}</small>
</div>
</article>
</div>
</article>
</section>

<section class="section-grid">
<article class="surface panel panel-overview">
<h3>Platform Overview</h3>
<p class="panel-intro">Commodity Origin combines verified listings, transparent pricing, and clear settlement workflows in a single space for modern commodity teams.</p>
<div class="feature-list">
<div v-for="feature in features" :key="feature.title" class="feature-row">
<div class="card-icon"><em class="icon ni" :class="feature.icon"></em></div>
<div>
<h4>{{ feature.title }}</h4>
<p>{{ feature.text }}</p>
</div>
</div>
</div>
<div class="stat-strip">
<div v-for="s in platformStats" :key="s.label" class="stat-pill">
<strong>{{ s.value }}</strong>
<span>{{ s.label }}</span>
</div>
</div>
</article>

<article class="surface panel panel-market">
<h3 class="mb-4">Supported Commodities</h3>
<div class="chips">
<span v-for="c in categories" :key="c" class="chip">{{ c }}</span>
</div>

<h4 class="subhead">Trading Tools</h4>
<div class="tools-grid">
<article v-for="tool in tradingTools" :key="tool.title" class="tool-item">
<div class="tool-icon"><em class="icon ni" :class="tool.icon"></em></div>
<div>
<h5>{{ tool.title }}</h5>
<p>{{ tool.text }}</p>
</div>
</article>
</div>

<h4 class="subhead">Why Teams Trust Commodity Origin</h4>
<div class="trust-list">
<article v-for="item in trustSignals" :key="item.title" class="trust-row">
<h5>{{ item.title }}</h5>
<p>{{ item.text }}</p>
</article>
</div>
</article>
</section>

<section class="section-grid">
<article class="surface panel panel-workflow">
<h3>How Trading Works</h3>
<div class="workflow-list">
<article v-for="item in workflow" :key="item.step" class="workflow-row">
<span class="step">{{ item.step }}</span>
<div class="workflow-copy">
<h4>{{ item.title }}</h4>
<p>{{ item.text }}</p>
</div>
<div class="workflow-icon"><em class="icon ni" :class="item.icon"></em></div>
</article>
</div>
</article>

<article class="surface panel panel-faq">
<h3>Frequently Asked Questions</h3>
<div class="faq-list">
<article v-for="item in faqs" :key="item.q" class="faq-row">
<h4>{{ item.q }}</h4>
<p>{{ item.a }}</p>
</article>
</div>
</article>
</section>

<section class="cta surface">
<p class="cta-kicker">Ready to Start</p>
<h2>Built for Modern Commodity Operations</h2>
<p>Manage listings, pricing, negotiation, contracts, and settlement in one secure workspace for cooperatives, buyers, and trading teams.</p>
<div class="cta-actions">
<Link href="/register" class="btn btn-solid">Create Account</Link>
<Link href="/login" class="btn btn-cta-outline">Sign In</Link>
</div>
</section>
</div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap');

.coffee-exchange-page {
min-height: 100vh;
padding: 94px 24px 58px;
font-family: 'Manrope', sans-serif;
color: #1d2733;
position: relative;
overflow-x: clip;
--title-font: 'Space Grotesk', sans-serif;
--coffee-900: #3f2718;
--coffee-800: #5a3824;
--coffee-700: #73492d;
--coffee-500: #a06a42;
--mint-700: #0f766e;
--muted: #5d6f82;
--surface: rgba(255, 255, 255, 0.88);
--surface-strong: rgba(255, 255, 255, 0.94);
--surface-border: rgba(95, 62, 39, 0.14);
--shadow-soft: none;
--shadow-lift: none;
--radius-lg: 20px;
background:
radial-gradient(1100px 520px at 10% -170px, rgba(160, 106, 66, 0.22), rgba(160, 106, 66, 0)),
radial-gradient(1000px 460px at 100% -130px, rgba(15, 118, 110, 0.13), rgba(15, 118, 110, 0)),
linear-gradient(180deg, rgba(248, 244, 238, 0.6), rgba(255, 255, 255, 0.6)),
url('../../images/bg2.jpg');
background-size: auto, auto, auto, cover;
background-position: left top, right top, center, center top;
background-repeat: no-repeat;
background-attachment: fixed;
}

.coffee-exchange-page::before {
content: '';
position: absolute;
inset: 0;
background:
radial-gradient(16px 10px at 12% 16%, rgba(115, 73, 45, 0.06), transparent),
radial-gradient(16px 10px at 26% 22%, rgba(115, 73, 45, 0.06), transparent),
radial-gradient(16px 10px at 84% 20%, rgba(115, 73, 45, 0.06), transparent);
pointer-events: none;
}

.coffee-exchange-page > * {
position: relative;
z-index: 1;
}

.topbar {
position: fixed;
top: 0;
left: 0;
right: 0;
z-index: 60;
padding: 10px 24px;
background: #ffffff;
backdrop-filter: blur(10px);
box-shadow: none;
border-bottom: none;
}

.topbar-inner {
max-width: 1160px;
margin: 0 auto;
display: flex;
justify-content: space-between;
align-items: center;
gap: 16px;
}

.brand {
display: inline-flex;
align-items: center;
gap: 10px;
text-decoration: none;
transition: transform 0.2s ease, filter 0.2s ease;
}

.brand:hover {
transform: translateY(-1px);
filter: saturate(1.05);
}

.brand-logo {
width: 40px;
height: 40px;
object-fit: contain;
border-radius: 50%;
background: #fff;
padding: 4px;
}

.brand-copy {
display: flex;
flex-direction: column;
line-height: 1.05;
}

.brand-text {
font-family: var(--title-font);
font-size: 1.2rem;
font-weight: 700;
letter-spacing: -0.015em;
color: var(--coffee-800);
}

.brand-copy small {
font-size: 0.67rem;
font-weight: 900;
letter-spacing: 0.08em;
text-transform: uppercase;
color: #000000;
margin-top: 10px;
}

.actions {
display: flex;
gap: 10px;
}

.hero-grid,
.section-grid,
.cta {
max-width: 1160px;
margin-left: auto;
margin-right: auto;
}

.hero-grid {
display: grid;
grid-template-columns: 1.25fr 1fr;
gap: 24px;
align-items: stretch;
margin-top: 8px;
}

.section-grid {
margin-top: 28px;
display: grid;
grid-template-columns: 1.3fr 1fr;
gap: 22px;
align-items: stretch;
}

.section-grid + .section-grid {
margin-top: 22px;
}

.surface {
border-radius: var(--radius-lg);
background: linear-gradient(180deg, var(--surface-strong), var(--surface));
border: none;
box-shadow: var(--shadow-soft);
backdrop-filter: blur(4px);
transition: transform 0.22s ease, box-shadow 0.24s ease;
}

.hero-grid > .surface,
.section-grid > .surface {
height: 100%;
}

.surface:hover {
transform: translateY(-3px);
box-shadow: var(--shadow-lift);
}

.hero-copy,
.panel,
.market-panel,
.cta {
padding: 30px;
}

.eyebrow {
margin: 0 0 10px;
font-size: 0.76rem;
font-weight: 800;
letter-spacing: 0.08em;
text-transform: uppercase;
color: var(--coffee-700);
}

h1 {
margin: 10px 0 14px;
font-family: var(--title-font);
font-size: calc(clamp(1.7rem, 1.8vw + 1rem, 2.2rem) + 10px);
line-height: 1.2;
font-weight: 800;
letter-spacing: -0.03em;
max-width: 15ch;
background: linear-gradient(135deg, #1f2937, var(--coffee-700));
-webkit-background-clip: text;
background-clip: text;
color: transparent;
}

.lead {
margin: 0;
max-width: 58ch;
font-size: 1.02rem;
line-height: 1.76;
color: #43586d;
}

.hero-support {
margin: 10px 0 0;
max-width: 62ch;
font-size: 0.91rem;
line-height: 1.72;
color: #5c7085;
}

.hero-meta {
margin-top: 18px;
display: flex;
flex-wrap: wrap;
gap: 9px;
}

.hero-meta span {
display: inline-flex;
align-items: center;
gap: 6px;
padding: 7px 11px;
font-size: 0.74rem;
font-weight: 700;
border-radius: 999px;
background: linear-gradient(180deg, rgba(255, 255, 255, 0.92), rgba(248, 238, 225, 0.9));
color: #2f4359;
box-shadow: none;
border: 1px solid rgba(95, 62, 39, 0.2);
}

.hero-proof {
margin-top: 16px;
display: grid;
grid-template-columns: repeat(3, minmax(0, 1fr));
gap: 8px;
}

.hero-proof-item {
padding: 10px 11px;
border-radius: 12px;
background: linear-gradient(180deg, rgba(255, 255, 255, 0.92), rgba(247, 238, 227, 0.86));
display: flex;
flex-direction: column;
gap: 2px;
border: 1px solid rgba(95, 62, 39, 0.2);
}

.hero-proof-item strong {
font-size: 0.95rem;
font-weight: 800;
color: #2d4056;
}

.hero-proof-item small {
font-size: 0.72rem;
font-weight: 700;
color: #5f7488;
}

.hero-cta {
display: flex;
flex-wrap: wrap;
gap: 10px;
margin-top: 18px;
}

.demand-board {
margin-top: 20px;
padding: 14px;
border-radius: 14px;
background: linear-gradient(180deg, rgba(255, 247, 238, 0.94), rgba(255, 255, 255, 0.84));
border: 1px solid rgba(115, 73, 45, 0.11);
}

.demand-head {
display: flex;
justify-content: space-between;
gap: 10px;
align-items: baseline;
margin-bottom: 8px;
}

.demand-head h3 {
margin: 0;
font-family: var(--title-font);
font-size: 0.83rem;
letter-spacing: 0.06em;
text-transform: uppercase;
color: #53677b;
}

.demand-head strong {
font-size: 0.82rem;
color: var(--coffee-700);
}

.demand-list {
list-style: none;
margin: 0;
padding: 0;
display: grid;
gap: 8px;
}

.demand-list li {
display: flex;
justify-content: space-between;
gap: 10px;
padding: 10px;
border-radius: 12px;
background: rgba(255, 255, 255, 0.72);
border: 1px solid rgba(95, 62, 39, 0.2);
transition: transform 0.2s ease, background-color 0.2s ease;
}

.demand-list li:hover {
transform: translateY(-1px);
background: rgba(255, 255, 255, 0.92);
}

.country {
display: block;
font-size: 0.8rem;
font-weight: 800;
color: #32485d;
}

.demand-list small {
font-size: 0.7rem;
color: var(--muted);
}

.demand-right {
display: flex;
flex-direction: column;
align-items: flex-end;
gap: 2px;
}

.demand-right strong {
font-size: 0.77rem;
color: #223245;
}

.panel-head {
display: flex;
justify-content: space-between;
align-items: center;
gap: 12px;
margin-bottom: 12px;
}

.panel-head h3,
.panel h3 {
margin: 0;
font-family: var(--title-font);
font-size: 1.12rem;
letter-spacing: -0.01em;
}

.panel-sub {
margin: -2px 0 14px;
font-size: 0.84rem;
line-height: 1.6;
color: #5a6f84;
}

.live-pill {
display: inline-flex;
align-items: center;
gap: 6px;
padding: 6px 10px;
border-radius: 999px;
font-size: 0.72rem;
font-weight: 800;
background: rgba(15, 118, 110, 0.15);
color: var(--mint-700);
}

.ticker {
display: grid;
grid-template-columns: 1fr 1fr;
gap: 10px;
}

.ticker-item {
padding: 12px;
border-radius: 12px;
background: linear-gradient(180deg, #ffffff, #f8f2ea);
border: 1px solid rgba(95, 62, 39, 0.2);
border-left: 3px solid rgba(106, 65, 38, 0.38);
display: flex;
flex-direction: column;
gap: 5px;
transition: transform 0.2s ease;
}

.ticker-item:hover {
transform: translateY(-2px);
}

.name {
font-size: 0.76rem;
color: var(--muted);
}

.snapshot-metrics {
margin-top: 10px;
display: grid;
grid-template-columns: repeat(3, minmax(0, 1fr));
gap: 8px;
}

.metric-pill {
padding: 10px;
border-radius: 11px;
background: linear-gradient(180deg, rgba(255, 255, 255, 0.95), rgba(243, 248, 247, 0.9));
border: 1px solid rgba(95, 62, 39, 0.2);
display: flex;
flex-direction: column;
gap: 2px;
transition: transform 0.2s ease;
}

.metric-pill:hover {
transform: translateY(-1px);
}

.metric-pill span {
font-size: 0.68rem;
font-weight: 700;
color: var(--muted);
}

.metric-pill strong {
font-size: 0.83rem;
color: #1f2f40;
}

.trade-feed {
margin-top: 12px;
padding: 10px;
border-radius: 12px;
background: rgba(255, 255, 255, 0.72);
border: 1px solid rgba(95, 62, 39, 0.2);
}

.feed-title {
margin: 0 0 8px;
font-size: 0.75rem;
font-weight: 800;
letter-spacing: 0.06em;
text-transform: uppercase;
color: #53677b;
}

.feed-row {
display: grid;
grid-template-columns: 1fr auto;
gap: 8px;
padding: 8px 0;
}

.feed-row + .feed-row {
border-top: 1px dashed rgba(95, 62, 39, 0.18);
}

.feed-main h5 {
margin: 0;
font-size: 0.82rem;
font-weight: 700;
}

.feed-main p {
margin: 2px 0 0;
font-size: 0.74rem;
color: var(--muted);
}

.feed-meta {
display: flex;
flex-direction: column;
align-items: flex-end;
}

.feed-meta strong {
font-size: 0.78rem;
}

.feed-meta small {
font-size: 0.7rem;
color: var(--muted);
}

.panel-intro {
margin: 10px 0 18px;
font-size: 0.95rem;
line-height: 1.7;
color: #4a5e72;
max-width: 62ch;
}

.feature-list,
.tools-grid,
.trust-list,
.workflow-list,
.faq-list {
display: grid;
gap: 10px;
}

.feature-row,
.tool-item,
.trust-row,
.workflow-row,
.faq-row {
padding: 10px;
border-radius: 12px;
background: rgba(255, 255, 255, 0.74);
display: grid;
gap: 10px;
transition: transform 0.2s ease, background-color 0.2s ease;
}

.feature-row,
.tool-item {
grid-template-columns: auto 1fr;
align-items: flex-start;
}

.workflow-row {
grid-template-columns: auto 1fr auto;
align-items: flex-start;
}

.feature-row:hover,
.tool-item:hover,
.trust-row:hover,
.workflow-row:hover,
.faq-row:hover {
transform: translateY(-1px);
background: rgba(255, 255, 255, 0.9);
}

.card-icon,
.tool-icon {
width: 34px;
height: 34px;
display: inline-flex;
align-items: center;
justify-content: center;
border-radius: 10px;
color: var(--coffee-700);
background: linear-gradient(180deg, rgba(160, 106, 66, 0.2), rgba(160, 106, 66, 0.1));
}

.feature-row h4,
.tool-item h5,
.trust-row h5,
.workflow-copy h4,
.faq-row h4 {
margin: 0 0 5px;
font-size: 0.9rem;
font-weight: 700;
}

.feature-row p,
.tool-item p,
.trust-row p,
.workflow-copy p,
.faq-row p {
margin: 0;
font-size: 0.83rem;
line-height: 1.62;
color: var(--muted);
}

.stat-strip {
margin-top: 14px;
display: grid;
grid-template-columns: repeat(4, minmax(0, 1fr));
gap: 10px;
}

.stat-pill {
padding: 11px 10px;
text-align: center;
border-radius: 12px;
background: linear-gradient(180deg, rgba(255, 255, 255, 0.96), rgba(255, 242, 228, 0.9));
border: 1px solid rgba(95, 62, 39, 0.2);
transition: transform 0.2s ease;
}

.stat-pill:hover {
transform: translateY(-1px);
}

.stat-pill strong {
display: block;
font-size: 1rem;
font-weight: 800;
color: var(--coffee-700);
}

.stat-pill span {
font-size: 0.74rem;
color: var(--muted);
}

.subhead {
margin: 20px 0 10px;
font-family: var(--title-font);
font-size: 0.94rem;
letter-spacing: -0.01em;
}

.chips {
display: flex;
flex-wrap: wrap;
gap: 8px;
}

.chip {
padding: 7px 11px;
font-size: 0.77rem;
font-weight: 700;
border-radius: 999px;
background: linear-gradient(180deg, rgba(255, 255, 255, 0.96), rgba(247, 239, 228, 0.9));
color: #5f412b;
box-shadow: none;
transition: transform 0.18s ease, filter 0.18s ease;
}

.panel-market .chip,
.panel-market .tool-item {
border: 1px solid rgba(95, 62, 39, 0.2);
}

.chip:hover {
transform: translateY(-1px);
filter: brightness(0.99);
}

.step {
width: 29px;
height: 29px;
display: inline-flex;
align-items: center;
justify-content: center;
border-radius: 999px;
font-size: 0.76rem;
font-weight: 800;
background: rgba(15, 118, 110, 0.15);
color: var(--mint-700);
}

.workflow-icon {
font-size: 0.95rem;
color: rgba(106, 65, 38, 0.7);
}

.cta {
margin-top: 24px;
text-align: center;
background: linear-gradient(135deg, var(--coffee-700), var(--coffee-900));
color: #fff;
position: relative;
overflow: hidden;
}

.cta::before {
content: '';
position: absolute;
inset: 0;
background:
radial-gradient(520px 220px at 15% 0%, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0)),
radial-gradient(420px 200px at 100% 100%, rgba(255, 255, 255, 0.14), rgba(255, 255, 255, 0));
pointer-events: none;
}

.cta > * {
position: relative;
z-index: 1;
}

.cta-kicker {
margin: 0 0 10px;
font-size: 0.74rem;
font-weight: 800;
letter-spacing: 0.08em;
text-transform: uppercase;
opacity: 0.9;
}

.cta h2 {
margin: 0 0 10px;
font-family: var(--title-font);
font-size: 1.52rem;
line-height: 1.22;
}

.cta p {
margin: 0 auto 20px;
max-width: 62ch;
line-height: 1.7;
}

.cta-actions {
display: inline-flex;
gap: 10px;
flex-wrap: wrap;
justify-content: center;
}

.btn {
display: inline-flex;
align-items: center;
justify-content: center;
padding: 10px 16px;
border-radius: 10px;
font-size: 0.9rem;
font-weight: 700;
text-decoration: none;
line-height: 1;
transition: transform 0.18s ease, box-shadow 0.2s ease, filter 0.2s ease;
}

.btn-solid {
background: linear-gradient(135deg, var(--coffee-700), var(--coffee-900));
color: #fff;
box-shadow: 0 10px 20px rgba(79, 48, 29, 0.24);
}

.btn-ghost {
background: rgba(255, 255, 255, 0.88);
color: #334155;
box-shadow: 0 8px 14px rgba(29, 39, 51, 0.07);
}

.btn-outline {
background: linear-gradient(180deg, rgba(255, 247, 238, 0.94), rgba(245, 231, 214, 0.88));
color: var(--coffee-800);
box-shadow: 0 7px 14px rgba(79, 48, 29, 0.1);
}

.btn-cta-outline {
background: rgba(255, 255, 255, 0.14);
color: #fff;
}

.btn:hover {
transform: translateY(-1px);
filter: brightness(0.98);
}

.btn:focus-visible {
outline: 2px solid rgba(95, 62, 39, 0.35);
outline-offset: 2px;
}

.trend-up {
color: #047857;
font-weight: 800;
}

.trend-down {
color: #b42318;
font-weight: 800;
}

@keyframes fadeUp {
from { opacity: 0; transform: translateY(12px); }
to { opacity: 1; transform: translateY(0); }
}

.hero-grid,
.section-grid,
.cta {
animation: fadeUp 0.45s ease both;
}

.section-grid { animation-delay: 0.08s; }
.cta { animation-delay: 0.14s; }

@media (prefers-reduced-motion: reduce) {
.hero-grid,
.section-grid,
.cta,
.surface,
.btn {
animation: none;
transition: none;
}
}

@media (max-width: 1120px) {
.hero-grid,
.section-grid {
grid-template-columns: 1fr;
}

.ticker,
.snapshot-metrics {
grid-template-columns: 1fr;
}

.stat-strip {
grid-template-columns: repeat(2, minmax(0, 1fr));
}
}

@media (max-width: 760px) {
.coffee-exchange-page {
padding: 78px 16px 42px;
}

.topbar {
padding: 10px 16px;
}

.topbar-inner {
gap: 10px;
}

.actions {
gap: 8px;
}

.brand-logo {
width: 34px;
height: 34px;
}

.brand-text {
font-size: 1.1rem;
}

.hero-copy,
.panel,
.market-panel,
.cta {
padding: 22px;
}

.btn {
padding: 9px 13px;
font-size: 0.84rem;
}

h1 {
font-size: 1.78rem;
line-height: 1.2;
}

.hero-proof {
grid-template-columns: 1fr;
}

.demand-head {
flex-direction: column;
align-items: flex-start;
gap: 2px;
}
}
</style>
