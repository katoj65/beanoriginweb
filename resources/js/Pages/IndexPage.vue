<script setup>
import { Link } from '@inertiajs/vue3';

const highlights = [
{ name: 'Arabica Coffee', price: '$3.42 / kg', change: '+2.1%' },
{ name: 'Cocoa', price: '$4.88 / kg', change: '+1.4%' },
{ name: 'Maize', price: '$0.31 / kg', change: '-0.6%' },
{ name: 'Sesame', price: '$1.72 / kg', change: '+0.9%' },
];

const features = [
{ title: 'Verified Listings', text: 'Only approved cooperatives and suppliers can publish market-ready lots.', icon: 'ni-shield-check' },
{ title: 'Transparent Pricing', text: 'Monitor offers, bids, and contract terms with full visibility.', icon: 'ni-coins' },
{ title: 'Efficient Settlement', text: 'Move from listing to confirmed trade through a streamlined process.', icon: 'ni-link-h' },
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
{ step: '3', title: 'Settle', text: 'Trades are confirmed and tracked through a clear settlement workflow.', icon: 'ni-check-circle' },
];

const categories = ['Arabica Coffee', 'Robusta Coffee', 'Cocoa', 'Sesame', 'Maize', 'Beans', 'Groundnuts', 'Sorghum'];

const trustSignals = [
{ title: 'Identity Verified', text: 'Every cooperative and trading account is validated before joining the marketplace.' },
{ title: 'Batch Traceability', text: 'Each lot is linked to source details, quality attributes, and listing history.' },
{ title: 'Audit-Friendly Records', text: 'Transactions and status updates are stored for clear reporting and compliance.' },
];

const faqs = [
{ q: 'Who can list commodities?', a: 'Registered cooperatives and approved suppliers can create and manage listings.' },
{ q: 'Can buyers compare multiple lots?', a: 'Yes. Buyers can review price, quantity, grade, and location before negotiating.' },
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
<h3>Live Market Snapshot</h3>
<div class="ticker mt-4">
<article v-for="item in highlights" :key="item.name" class="ticker-item">
<span class="name">{{ item.name }}</span>
<strong>{{ item.price }}</strong>
<small :class="item.change.startsWith('+') ? 'up' : 'down'">{{ item.change }}</small>
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
linear-gradient(180deg, rgba(248, 250, 252, 0.74) 0%, rgba(238, 242, 246, 0.78) 100%),
radial-gradient(1200px 500px at 10% -10%, rgba(111, 78, 55, 0.10), rgba(111, 78, 55, 0)),
radial-gradient(900px 420px at 110% 10%, rgba(90, 62, 43, 0.08), rgba(90, 62, 43, 0)),
url('../../images/bg2.jpg');
background-size: cover;
background-position: center center;
background-repeat: no-repeat;
background-attachment: scroll;
font-family: 'Plus Jakarta Sans', sans-serif;
color: #1f2937;
--title-font: 'Sora', sans-serif;
--section-gap: 26px;
}

.index-page::before {
content: '';
position: absolute;
inset: 0;
background: linear-gradient(135deg, rgba(111, 78, 55, 0.05), rgba(51, 65, 85, 0.02));
pointer-events: none;
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
margin: 0 auto 34px;
padding: 12px 16px;
background: transparent;
border: none;
border-radius: 0;
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
border-radius: 10px;
background: #fff;
padding: 4px;
}

.brand-text {
line-height: 1;
font-size: 1.35rem;
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
gap: 18px;
align-items: stretch;
}

.hero-copy,
.hero-panel {
background: #fff;
border: none;
border-radius: 18px;
padding: 28px;
box-shadow: 0 14px 30px rgba(15, 23, 42, 0.10), 0 4px 10px rgba(15, 23, 42, 0.06);
animation: fadeUp 0.5s ease both;
transition: transform 0.25s ease, box-shadow 0.25s ease;
}

.hero-panel { animation-delay: 0.1s; }

.hero-copy:hover,
.hero-panel:hover {
transform: translateY(-2px);
box-shadow: 0 18px 34px rgba(15, 23, 42, 0.14), 0 8px 16px rgba(15, 23, 42, 0.08);
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
font-size: 2.05rem;
font-weight: 700;
line-height: 1.32;
letter-spacing: -0.02em;
margin: 0 0 16px;
}

.lead {
font-size: 1rem;
line-height: 1.68;
color: #475569;
margin: 0 0 20px;
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
padding: 6px 10px;
border-radius: 999px;
font-size: 0.78rem;
font-weight: 600;
background: #ffffff;
border: 1px solid var(--app-border);
color: #334155;
}

.hero-cta {
display: flex;
gap: 12px;
flex-wrap: wrap;
}

.ticker {
display: grid;
grid-template-columns: 1fr 1fr;
gap: 10px;
}

.ticker-item {
background: #f8fafc;
border: none;
border-radius: 12px;
padding: 14px;
display: flex;
flex-direction: column;
gap: 6px;
transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.ticker-item:hover {
transform: translateY(-2px);
box-shadow: 0 10px 18px rgba(15, 23, 42, 0.08);
}

.name {
font-size: 0.8rem;
color: #64748b;
}

.up { color: #047857; font-weight: 700; }
.down { color: #b91c1c; font-weight: 700; }

.overview,
.operations {
max-width: 1100px;
margin: var(--section-gap) auto 0;
display: grid;
grid-template-columns: 1.45fr 1fr;
gap: 16px;
}

.panel {
background: #fff;
border-radius: 16px;
padding: 24px;
box-shadow: 0 12px 22px rgba(15, 23, 42, 0.08);
}

.panel h3 {
margin: 0 0 14px;
font-family: var(--title-font);
font-size: 1.18rem;
font-weight: 700;
letter-spacing: -0.01em;
}

.panel-intro {
margin: 0 0 20px;
color: #475569;
line-height: 1.65;
font-size: 0.95rem;
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
background: rgba(111, 78, 55, 0.12);
color: var(--app-coffee-700);
}

.feature-row h4 {
margin: 0 0 6px;
font-family: var(--title-font);
font-size: 0.98rem;
font-weight: 600;
}

.feature-row p {
margin: 0;
color: #64748b;
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
background: #f8fafc;
border-radius: 12px;
padding: 10px;
text-align: center;
}

.stat-pill strong {
display: block;
font-size: 1rem;
color: var(--app-coffee-700);
}

.stat-pill span {
font-size: 0.78rem;
color: #64748b;
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
font-size: 0.82rem;
color: #334155;
}

.trust-list {
display: grid;
gap: 10px;
}

.trust-row {
padding: 10px 0;
border-bottom: 1px solid rgba(148, 163, 184, 0.18);
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
color: #64748b;
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
background: rgba(111, 78, 55, 0.12);
color: var(--app-coffee-700);
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
color: #64748b;
}

.workflow-icon {
color: rgba(111, 78, 55, 0.6);
font-size: 1rem;
margin-top: 4px;
}

.faq-row {
padding: 10px 0;
border-bottom: 1px solid rgba(148, 163, 184, 0.18);
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
color: #64748b;
}

.cta {
max-width: 1100px;
margin: calc(var(--section-gap) + 2px) auto 0;
padding: 30px 24px;
border-radius: 16px;
background: linear-gradient(135deg, var(--app-coffee-700), var(--app-coffee-800));
color: #fff;
text-align: center;
box-shadow: 0 20px 38px rgba(90, 62, 43, 0.34), 0 8px 16px rgba(90, 62, 43, 0.22);
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
padding: 10px 15px;
border-radius: 10px;
text-decoration: none;
font-weight: 700;
font-size: 0.92rem;
line-height: 1;
}

.btn-solid {
background: var(--app-coffee-700);
color: #fff;
border: 1px solid var(--app-coffee-700);
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
}

.btn-cta-outline {
background: transparent;
color: #fff;
border: 1px solid rgba(255, 255, 255, 0.6);
}

.btn:hover {
transform: translateY(-1px);
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
.stat-strip { grid-template-columns: repeat(2, minmax(0, 1fr)); }
.hero-copy,
.hero-panel { padding: 22px; }
.panel { padding: 18px; }
.index-page { padding: 24px 16px 40px; }
.brand-logo { width: 36px; height: 36px; }
.brand { font-size: 1.05rem; }
h1 { font-size: 1.8rem; }
}
</style>
