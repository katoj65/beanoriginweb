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
{ title: 'Verified Listings', text: 'Only approved cooperatives and suppliers can publish market-ready lots.', icon: 'ni-shield-check' },
{ title: 'Transparent Pricing', text: 'Monitor offers, bids, and contract terms with full visibility.', icon: 'ni-coins' },
{ title: 'Efficient Settlement', text: 'Move from listing to confirmed trade through a streamlined process.', icon: 'ni-link-h' },
{ title: 'Live Bid Board', text: 'Track bid and ask movements in real time across active commodity categories.', icon: 'ni-activity-round' },
{ title: 'Counteroffer Threads', text: 'Negotiate with structured counteroffers and maintain full audit history.', icon: 'ni-chat-fill' },
{ title: 'Contract Templates', text: 'Generate standardized trade contracts with configurable delivery and payment terms.', icon: 'ni-file-docs' },
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
{ title: 'Price Alerts', text: 'Get notified when target bid, ask, or spread levels are reached.', icon: 'ni-bell' },
{ title: 'Lot Comparison', text: 'Compare quality, origin, volume, and price side by side.', icon: 'ni-list-thumb' },
{ title: 'Delivery Milestones', text: 'Track shipment and settlement checkpoints per contract.', icon: 'ni-truck' },
{ title: 'Dispute Flags', text: 'Escalate and resolve quality or delivery issues with full context.', icon: 'ni-alert-circle' },
];

const trustSignals = [
{ title: 'Identity Verified', text: 'Every cooperative and trading account is validated before joining the marketplace.' },
{ title: 'Batch Traceability', text: 'Each lot is linked to source details, quality attributes, and listing history.' },
{ title: 'Audit-Friendly Records', text: 'Transactions and status updates are stored for clear reporting and compliance.' },
];

const faqs = [
{ q: 'Who can list commodities?', a: 'Registered cooperatives and approved suppliers can create and manage listings.' },
{ q: 'Can buyers compare multiple lots?', a: 'Yes. Buyers can review price, quantity, grade, and location before negotiating.' },
{ q: 'Do you support trading alerts and milestones?', a: 'Yes. Users can monitor pricing alerts and contract milestone status in one dashboard.' },
{ q: 'What commodities are supported?', a: 'The platform supports coffee and several key staple and export crops.' },
];
</script>

<template>
<div class="index-page">

<header class="topbar">
<Link href="/" class="brand" aria-label="Commodity Origin home">
<img src="../../images/logo.png" alt="Commodity Origin logo" class="brand-logo" />
<span class="brand-text">Commodity Origin</span>
</Link>
<nav class="actions">
<Link href="/login" class="btn btn-ghost">Log In</Link>
<Link href="/register" class="btn btn-solid">Get Started</Link>
</nav>
</header>








<section class="hero">
<div class="hero-copy">
<p class="eyebrow">Digital Commodity Exchange</p>
<h1>Trade Verified Commodities With Confidence</h1>
<p class="lead">Commodity Origin connects buyers, cooperatives, and suppliers in a trusted marketplace built for transparent pricing, dependable quality, and faster transactions.</p>
<div class="hero-meta">
<span><em class="icon ni ni-check-circle"></em>Verified participants</span>
<span><em class="icon ni ni-clock"></em>Faster deal cycles</span>
<span><em class="icon ni ni-shield-check"></em>Audit-ready records</span>
</div>
<div class="hero-cta">
<Link href="/register" class="btn btn-solid">Start Trading</Link>
<Link href="/login" class="btn btn-outline">View Live Market</Link>
</div>
</div>
<div class="hero-panel">
<div class="panel-head">
<h3>Live Market Snapshot</h3>
<span class="live-pill"><em class="icon ni ni-activity"></em>Live</span>
</div>
<div class="ticker mt-4">
<article v-for="item in highlights" :key="item.name" class="ticker-item">
<span class="name">{{ item.name }}</span>
<strong>{{ item.price }}</strong>
<small :class="item.change.startsWith('+') ? 'up' : 'down'">{{ item.change }}</small>
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
</div>
</section>







<section class="overview">
<article class="panel panel-overview">
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

<article class="panel panel-market">
<h3>Supported Commodities</h3>
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

<section class="operations">
<article class="panel panel-workflow">
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

<article class="panel panel-faq">
<h3>Frequently Asked Questions</h3>
<div class="faq-list">
<article v-for="item in faqs" :key="item.q" class="faq-row">
<h4>{{ item.q }}</h4>
<p>{{ item.a }}</p>
</article>
</div>
</article>
</section>

<section class="cta">
<p class="cta-kicker">Ready to Start</p>
<h2 class="text-white">Built for Modern Commodity Operations</h2>
<p>Manage listing, pricing, negotiation, and settlement in one trusted workspace built for cooperatives, buyers, and trading teams.</p>
<div class="cta-actions">
<Link href="/register" class="btn btn-solid">Create Account</Link>
<Link href="/login" class="btn btn-cta-outline">Sign In</Link>
</div>
</section>
</div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Sora:wght@500;600;700;800&display=swap');

.index-page {
min-height: 100vh;
padding: 36px 24px 56px;
position: relative;
overflow: hidden;
background:
linear-gradient(180deg, #f8fbff 0%, #fefcf8 58%, #ffffff 100%);
background-size: cover;
background-position: center center;
background-repeat: no-repeat;
background-attachment: scroll;
font-family: 'Plus Jakarta Sans', sans-serif;
color: #1f2937;
--title-font: 'Sora', sans-serif;
--section-gap: 30px;
--surface: rgba(255, 255, 255, 0.84);
--muted-text: #64748b;
--accent-amber: #b8752d;
--accent-forest: #1f7a5a;
--accent-slate: #375a7f;
--card-shadow: 0 1px 4px rgba(15, 23, 42, 0.08);
--card-shadow-hover: 0 3px 8px rgba(15, 23, 42, 0.1);
}

.index-page::before {
content: '';
position: absolute;
top: 0;
left: 0;
width: 100%;
height: 65vh;
background:
linear-gradient(160deg, rgba(111, 78, 55, 0.09), rgba(51, 65, 85, 0.02)),
radial-gradient(820px 360px at 10% -10%, rgba(255, 255, 255, 0.22), rgba(255, 255, 255, 0)),
radial-gradient(920px 420px at 85% -20%, rgba(255, 255, 255, 0.5), rgba(255, 255, 255, 0));
pointer-events: none;
z-index: 0;
}

.index-page::after {
content: '';
position: absolute;
top: 0;
left: 0;
width: 100%;
height: 65vh;
background-image: url('../../images/bg2.jpg');
background-size: cover;
background-position: center top;
background-repeat: no-repeat;
opacity: 0.35;
pointer-events: none;
z-index: 0;
}

.index-page > * {
position: relative;
z-index: 1;
}

.topbar {
display: flex;
justify-content: space-between;
align-items: center;
max-width: 1100px;
margin: 0 auto 28px;
padding: 12px 16px;
background: transparent;
border: none;
border-radius: 14px;
backdrop-filter: none;
box-shadow: none;
}

.brand {
display: inline-flex;
align-items: center;
gap: 10px;
font-family: var(--title-font);
font-size: 1.08rem;
font-weight: 700;
color: var(--app-coffee-700);
text-decoration: none;
letter-spacing: -0.01em;
}

.brand-logo {
width: 42px;
height: 42px;
object-fit: contain;
border-radius: 50%;
background: #fff;
padding: 4px;
}

.brand-text {
line-height: 1;
font-size: 1.35rem;
letter-spacing: -0.015em;
}

.actions {
display: flex;
gap: 10px;
}

.hero {
max-width: 1100px;
margin: 0 auto;
display: grid;
grid-template-columns: 1.2fr 1fr;
gap: 24px;
align-items: stretch;
}

.hero-copy,
.hero-panel {
background: linear-gradient(180deg, rgba(255, 248, 240, 0.96), rgba(255, 255, 255, 0.88));
border: none;
border-radius: 18px;
padding: 30px;
box-shadow: var(--card-shadow);
animation: fadeUp 0.5s ease both;
transition: transform 0.25s ease, box-shadow 0.25s ease;
backdrop-filter: blur(2px);
}

.hero-panel {
background: linear-gradient(180deg, rgba(238, 248, 248, 0.95), rgba(255, 255, 255, 0.88));
}

.hero-copy {
position: relative;
isolation: isolate;
}

.hero-copy::after {
content: '';
position: absolute;
right: -40px;
bottom: -56px;
width: 190px;
height: 190px;
border-radius: 999px;
background: radial-gradient(circle, rgba(111, 78, 55, 0.2), rgba(111, 78, 55, 0));
z-index: -1;
}

.hero-panel { animation-delay: 0.1s; }

.hero-copy:hover,
.hero-panel:hover {
transform: translateY(-4px);
box-shadow: var(--card-shadow-hover);
}

.eyebrow {
font-size: 0.78rem;
letter-spacing: 0.08em;
text-transform: uppercase;
font-weight: 700;
color: var(--app-coffee-700);
margin: 0 0 12px;
}

h1 {
font-family: var(--title-font);
font-size: clamp(2rem, 3.9vw, 2.6rem);
font-weight: 700;
line-height: 1.2;
letter-spacing: -0.02em;
margin: 0 0 16px;
max-width: 16ch;
}

.lead {
font-size: 1.02rem;
line-height: 1.72;
color: #475569;
margin: 0 0 22px;
max-width: 58ch;
}

.hero-meta {
display: flex;
flex-wrap: wrap;
gap: 10px;
margin-bottom: 20px;
}

.hero-meta span {
display: inline-flex;
align-items: center;
gap: 6px;
padding: 7px 11px;
border-radius: 999px;
font-size: 0.78rem;
font-weight: 600;
background: rgba(245, 232, 220, 0.82);
border: none;
color: #334155;
}

.hero-cta {
display: flex;
gap: 12px;
flex-wrap: wrap;
}

.panel-head {
display: flex;
align-items: center;
justify-content: space-between;
gap: 10px;
margin-bottom: 4px;
}

.panel-head h3 {
margin: 0;
}

.live-pill {
display: inline-flex;
align-items: center;
gap: 6px;
padding: 6px 11px;
border-radius: 999px;
font-size: 0.74rem;
font-weight: 700;
color: #0f6a4d;
background: rgba(16, 185, 129, 0.22);
border: 1px solid rgba(16, 185, 129, 0.34);
}

.ticker {
display: grid;
grid-template-columns: 1fr 1fr;
gap: 10px;
}

.ticker-item {
background: linear-gradient(180deg, rgba(248, 250, 252, 0.94), rgba(241, 245, 249, 0.9));
border: none;
border-radius: 12px;
padding: 14px 14px 14px 16px;
display: flex;
flex-direction: column;
gap: 6px;
transition: transform 0.2s ease, box-shadow 0.2s ease;
position: relative;
overflow: hidden;
box-shadow: var(--card-shadow);
}

.ticker-item::before {
content: '';
position: absolute;
top: 0;
left: 0;
width: 4px;
height: 100%;
background: linear-gradient(180deg, var(--app-coffee-700), var(--app-coffee-800));
opacity: 0.7;
}

.ticker-item:hover {
transform: translateY(-2px);
box-shadow: var(--card-shadow-hover);
}

.snapshot-metrics {
display: grid;
grid-template-columns: repeat(3, minmax(0, 1fr));
gap: 8px;
margin-top: 10px;
}

.metric-pill {
background: linear-gradient(180deg, rgba(250, 243, 234, 0.95), rgba(241, 249, 248, 0.9));
border-radius: 10px;
padding: 9px 10px;
display: flex;
flex-direction: column;
gap: 3px;
box-shadow: var(--card-shadow);
}

.metric-pill span {
font-size: 0.7rem;
font-weight: 600;
color: var(--muted-text);
}

.metric-pill strong {
font-size: 0.84rem;
color: #1e293b;
}

.trade-feed {
margin-top: 12px;
padding-top: 10px;
border-top: 1px solid rgba(148, 163, 184, 0.22);
background: rgba(255, 255, 255, 0.45);
border-radius: 10px;
padding-left: 10px;
padding-right: 10px;
padding-bottom: 6px;
}

.feed-title {
margin: 0 0 8px;
font-size: 0.76rem;
font-weight: 700;
letter-spacing: 0.06em;
text-transform: uppercase;
color: #475569;
}

.feed-row {
display: grid;
grid-template-columns: 1fr auto;
gap: 8px;
padding: 8px 0;
border-bottom: 1px solid rgba(148, 163, 184, 0.16);
}

.feed-row:last-child {
border-bottom: none;
padding-bottom: 0;
}

.feed-main h5 {
margin: 0;
font-size: 0.82rem;
font-weight: 600;
color: #1f2937;
}

.feed-main p {
margin: 2px 0 0;
font-size: 0.75rem;
color: var(--muted-text);
}

.feed-meta {
display: flex;
flex-direction: column;
align-items: flex-end;
gap: 2px;
}

.feed-meta strong {
font-size: 0.8rem;
color: #334155;
}

.feed-meta small {
font-size: 0.72rem;
color: var(--muted-text);
}

.name {
font-size: 0.8rem;
color: var(--muted-text);
letter-spacing: 0.02em;
}

.up { color: #047857; font-weight: 700; }
.down { color: #b91c1c; font-weight: 700; }

.overview,
.operations {
max-width: 1100px;
margin: var(--section-gap) auto 0;
display: grid;
grid-template-columns: 1.45fr 1fr;
gap: 18px;
}

.panel {
background: linear-gradient(180deg, var(--surface), rgba(255, 255, 255, 0.74));
border: none;
border-radius: 16px;
padding: 26px;
box-shadow: var(--card-shadow);
backdrop-filter: blur(3px);
transition: transform 0.24s ease, box-shadow 0.24s ease;
}

.panel-overview {
background: linear-gradient(180deg, rgba(255, 246, 236, 0.94), rgba(255, 255, 255, 0.82));
}

.panel-market {
background: linear-gradient(180deg, rgba(238, 248, 246, 0.94), rgba(255, 255, 255, 0.84));
}

.panel-workflow {
background: linear-gradient(180deg, rgba(239, 246, 255, 0.94), rgba(255, 255, 255, 0.84));
}

.panel-faq {
background: linear-gradient(180deg, rgba(247, 244, 255, 0.92), rgba(255, 255, 255, 0.84));
}

.panel:hover {
transform: translateY(-3px);
box-shadow: var(--card-shadow-hover);
}

.panel h3 {
margin: 0 0 14px;
font-family: var(--title-font);
font-size: 1.22rem;
font-weight: 700;
letter-spacing: -0.01em;
}

.panel-intro {
margin: 0 0 20px;
color: #475569;
line-height: 1.65;
font-size: 0.96rem;
max-width: 62ch;
}

.feature-list {
display: grid;
gap: 12px;
}

.feature-row {
display: grid;
grid-template-columns: auto 1fr;
gap: 10px;
align-items: flex-start;
padding: 10px 0;
border-bottom: 1px solid rgba(148, 163, 184, 0.18);
transition: background-color 0.2s ease, border-color 0.2s ease;
border-radius: 10px;
}

.feature-row:hover {
background: rgba(248, 250, 252, 0.72);
border-color: rgba(148, 163, 184, 0.28);
}

.feature-row:last-child {
border-bottom: none;
}

.card-icon {
width: 34px;
height: 34px;
display: inline-flex;
align-items: center;
justify-content: center;
border-radius: 10px;
background: rgba(184, 117, 45, 0.16);
color: #93581f;
}

.feature-row h4 {
margin: 0 0 6px;
font-family: var(--title-font);
font-size: 0.98rem;
font-weight: 600;
}

.feature-row p {
margin: 0;
color: var(--muted-text);
font-size: 0.92rem;
line-height: 1.6;
}

.stat-strip {
display: grid;
grid-template-columns: repeat(4, minmax(0, 1fr));
gap: 10px;
margin-top: 14px;
}

.stat-pill {
background: linear-gradient(180deg, rgba(247, 251, 255, 0.95), rgba(255, 249, 242, 0.9));
border: none;
border-radius: 12px;
padding: 10px;
text-align: center;
transition: transform 0.2s ease, box-shadow 0.2s ease;
box-shadow: var(--card-shadow);
}

.stat-pill:hover {
transform: translateY(-2px);
box-shadow: var(--card-shadow-hover);
}

.stat-pill strong {
display: block;
font-size: 1.05rem;
color: var(--app-coffee-700);
}

.stat-pill span {
font-size: 0.78rem;
color: var(--muted-text);
}

.subhead {
margin: 22px 0 12px;
font-family: var(--title-font);
font-size: 0.98rem;
font-weight: 600;
color: #1e293b;
}

.chips {
display: flex;
flex-wrap: wrap;
gap: 8px;
}

.chip {
display: inline-flex;
align-items: center;
padding: 7px 11px;
border-radius: 999px;
background: #f8fafc;
border: 1px solid rgba(148, 163, 184, 0.45);
font-size: 0.82rem;
color: #334155;
transition: transform 0.18s ease, background-color 0.18s ease, border-color 0.18s ease;
}

.chip:nth-child(4n + 1) { background: #fef3e7; color: #8c4d14; border-color: rgba(184, 117, 45, 0.45); }
.chip:nth-child(4n + 2) { background: #e8f7f2; color: #0f5d45; border-color: rgba(31, 122, 90, 0.42); }
.chip:nth-child(4n + 3) { background: #e8f0ff; color: #294f8a; border-color: rgba(41, 79, 138, 0.4); }
.chip:nth-child(4n + 4) { background: #f2ecff; color: #5a3c94; border-color: rgba(90, 60, 148, 0.4); }

.chip:hover {
transform: translateY(-1px);
filter: brightness(0.98);
}

.tools-grid {
display: grid;
gap: 10px;
margin-bottom: 10px;
}

.tool-item {
display: grid;
grid-template-columns: auto 1fr;
gap: 10px;
align-items: flex-start;
padding: 10px 0;
border-bottom: 1px solid rgba(148, 163, 184, 0.18);
border-radius: 10px;
transition: background-color 0.2s ease;
}

.tool-item:hover {
background: rgba(248, 250, 252, 0.72);
}

.tool-item:last-child {
border-bottom: none;
}

.tool-icon {
width: 34px;
height: 34px;
display: inline-flex;
align-items: center;
justify-content: center;
border-radius: 10px;
background: rgba(31, 122, 90, 0.14);
color: #165f45;
}

.tool-item h5 {
margin: 0 0 6px;
font-family: var(--title-font);
font-size: 0.92rem;
font-weight: 600;
}

.tool-item p {
margin: 0;
font-size: 0.88rem;
line-height: 1.55;
color: var(--muted-text);
}

.trust-list {
display: grid;
gap: 10px;
}

.trust-row {
padding: 10px 0;
border-bottom: 1px solid rgba(148, 163, 184, 0.18);
border-radius: 10px;
transition: background-color 0.2s ease;
}

.trust-row:hover {
background: rgba(248, 250, 252, 0.72);
}

.trust-row:last-child {
border-bottom: none;
}

.trust-row h5 {
margin: 0 0 6px;
font-family: var(--title-font);
font-size: 0.92rem;
font-weight: 600;
}

.trust-row p {
margin: 0;
font-size: 0.9rem;
line-height: 1.6;
color: var(--muted-text);
}

.workflow-list,
.faq-list {
display: grid;
gap: 10px;
}

.workflow-row {
display: grid;
grid-template-columns: auto 1fr auto;
gap: 10px;
align-items: flex-start;
padding: 10px 0;
border-bottom: 1px solid rgba(148, 163, 184, 0.18);
border-radius: 10px;
transition: background-color 0.2s ease;
}

.workflow-row:hover {
background: rgba(248, 250, 252, 0.72);
}

.workflow-row:last-child {
border-bottom: none;
}

.step {
display: inline-flex;
width: 28px;
height: 28px;
align-items: center;
justify-content: center;
border-radius: 999px;
background: rgba(55, 90, 127, 0.14);
color: #2a4f79;
font-weight: 800;
font-size: 0.8rem;
margin-top: 2px;
}

.workflow-copy h4 {
margin: 0 0 6px;
font-family: var(--title-font);
font-size: 0.98rem;
font-weight: 600;
}

.workflow-copy p {
margin: 0;
font-size: 0.9rem;
line-height: 1.6;
color: var(--muted-text);
}

.workflow-icon {
color: rgba(111, 78, 55, 0.6);
font-size: 1rem;
margin-top: 4px;
}

.faq-row {
padding: 10px 0;
border-bottom: 1px solid rgba(148, 163, 184, 0.18);
border-radius: 10px;
transition: background-color 0.2s ease;
}

.faq-row:hover {
background: rgba(248, 250, 252, 0.72);
}

.faq-row:last-child {
border-bottom: none;
}

.faq-row h4 {
margin: 0 0 8px;
font-family: var(--title-font);
font-size: 0.95rem;
font-weight: 600;
}

.faq-row p {
margin: 0;
font-size: 0.9rem;
line-height: 1.6;
color: var(--muted-text);
}

.cta {
max-width: 1100px;
margin: calc(var(--section-gap) + 4px) auto 0;
padding: 30px 24px;
border-radius: 16px;
background:
linear-gradient(135deg, var(--app-coffee-700), var(--app-coffee-800)),
radial-gradient(800px 280px at 5% -20%, rgba(255, 255, 255, 0.14), rgba(255, 255, 255, 0)),
radial-gradient(700px 280px at 95% 120%, rgba(255, 255, 255, 0.14), rgba(255, 255, 255, 0));
color: #fff;
text-align: center;
box-shadow: none;
position: relative;
overflow: hidden;
}

.cta::before {
content: '';
position: absolute;
inset: 0;
background:
radial-gradient(560px 220px at 20% 0%, rgba(255, 255, 255, 0.18), rgba(255, 255, 255, 0)),
radial-gradient(460px 180px at 100% 100%, rgba(255, 255, 255, 0.14), rgba(255, 255, 255, 0));
pointer-events: none;
}

.cta > * {
position: relative;
z-index: 1;
}

.cta-kicker {
margin: 0 0 10px;
font-size: 0.75rem;
font-weight: 700;
letter-spacing: 0.08em;
text-transform: uppercase;
opacity: 0.9;
}

.cta h2 {
margin: 0 0 14px;
font-family: var(--title-font);
font-weight: 700;
letter-spacing: -0.015em;
font-size: 1.5rem;
}

.cta p {
margin: 0 auto 22px;
opacity: 0.95;
max-width: 700px;
line-height: 1.65;
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
text-decoration: none;
font-weight: 700;
font-size: 0.92rem;
line-height: 1;
transition: transform 0.18s ease, box-shadow 0.2s ease, background-color 0.2s ease, border-color 0.2s ease;
white-space: nowrap;
}

.btn-solid {
background: linear-gradient(135deg, var(--app-coffee-700), var(--app-coffee-800));
color: #fff;
border: none;
box-shadow: none;
}

.btn-ghost {
background: #fff;
border: 1px solid var(--app-border);
color: #334155;
}

.btn-outline {
background: transparent;
border: 1px solid rgba(111, 78, 55, 0.35);
color: var(--app-coffee-800);
}

.cta .btn-solid {
background: #fff;
color: var(--app-coffee-700);
border-color: #fff;
box-shadow: none;
}

.btn-cta-outline {
background: transparent;
color: #fff;
border: 1px solid rgba(255, 255, 255, 0.6);
}

.btn:hover {
transform: translateY(-1px);
box-shadow: none;
filter: brightness(0.97);
}

.btn:focus-visible {
outline: 2px solid rgba(111, 78, 55, 0.35);
outline-offset: 2px;
}

@keyframes fadeUp {
from { opacity: 0; transform: translateY(12px); }
to { opacity: 1; transform: translateY(0); }
}

@media (max-width: 900px) {
.hero { grid-template-columns: 1fr; }
.overview { grid-template-columns: 1fr; }
.operations { grid-template-columns: 1fr; }
.ticker { grid-template-columns: 1fr; }
.snapshot-metrics { grid-template-columns: 1fr; }
.stat-strip { grid-template-columns: repeat(2, minmax(0, 1fr)); }
.hero-copy,
.hero-panel { padding: 22px; }
.panel { padding: 20px; }
.index-page { padding: 24px 16px 40px; }
.topbar {
padding: 10px 12px;
border-radius: 12px;
}
.brand-logo { width: 36px; height: 36px; }
.brand { font-size: 1.05rem; }
h1 { line-height: 1.24; }
}
</style>
