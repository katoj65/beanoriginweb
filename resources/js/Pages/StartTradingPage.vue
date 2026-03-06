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

const instruments = computed(() => marketBoard.value.length);
const totalQuantity = computed(() =>
    marketBoard.value.reduce((sum, row) => sum + Number(row.quantity ?? 0), 0),
);

const formatNumber = (value) => Number(value ?? 0).toLocaleString();

const marketItems = computed(() =>
    marketBoard.value
        .map((row) => {
            const commodity = row.commodity ?? 'Unknown commodity';
            const type = row.type ?? 'n/a';
            const grade = row.grade ?? 'n/a';
            const quantity = Number(row.quantity ?? 0);
            const min = Number(row.min_price ?? 0);
            const max = Number(row.max_price ?? 0);
            const spread = Math.max(max - min, 0);
            const label = [commodity, type, grade]
                .filter((part) => part && part !== 'n/a')
                .join(' · ');

            return {
                commodity,
                quantity,
                min,
                max,
                spread,
                label: label || commodity,
            };
        })
        .sort((a, b) => b.quantity - a.quantity),
);

const requirements = computed(() => {
    if (marketItems.value.length === 0) {
        return ['No live instrument data available yet.'];
    }

    return marketItems.value.slice(0, 5).map((item) =>
        `${item.label}: ${item.quantity.toLocaleString()} Kgs, range Shs. ${item.min.toLocaleString()} - Shs. ${item.max.toLocaleString()}`,
    );
});

const journey = computed(() => {
    if (marketItems.value.length === 0) {
        return [
            {
                title: 'Awaiting Live Market Data',
                detail: 'Once instruments are listed, this section will show real trading opportunities.',
            },
        ];
    }

    return marketItems.value.slice(0, 4).map((item) => ({
        title: item.label,
        detail: `Available quantity: ${item.quantity.toLocaleString()} Kgs. Price band: Shs. ${item.min.toLocaleString()} - Shs. ${item.max.toLocaleString()}.`,
    }));
});

const sellerSteps = computed(() => {
    if (marketItems.value.length === 0) {
        return ['No live listings available to mirror seller strategy.'];
    }

    return marketItems.value.slice(0, 4).map((item) =>
        `List ${item.label} at Shs. ${item.min.toLocaleString()} - Shs. ${item.max.toLocaleString()} for ${item.quantity.toLocaleString()} Kgs.`,
    );
});

const buyerSteps = computed(() => {
    if (marketItems.value.length === 0) {
        return ['No live listings available to mirror buyer strategy.'];
    }

    return marketItems.value.slice(0, 4).map((item) =>
        `Evaluate ${item.label}: spread Shs. ${item.spread.toLocaleString()} across ${item.quantity.toLocaleString()} Kgs.`,
    );
});
</script>

<template>
<home-layout>
<div class="trade-start">
<section class="hero-block">
<div class="hero-copy">
<p class="hero-kicker">Start Trading</p>
<h1 class="hero-focus-title">A Cleaner Path to Your First Commodity Trade</h1>
<p class="hero-lead">
Set up your account, get verified, and move through a clear execution flow used by modern commodity teams.
</p>

<div class="cta-row">
<Link href="/register" class="btn btn-brand btn-lg cta-primary">Create Free Trading Account</Link>
<Link href="/live-markets" class="btn btn-soft btn-lg">View Live Markets</Link>
<Link href="/login" class="btn btn-soft btn-lg cta-login">Access Trading Desk</Link>
</div>

<div class="metrics-row">
<div class="metric-tile">
<small>Instruments</small>
<strong>{{ instruments }}</strong>
</div>
<div class="metric-tile">
<small>Active Buyers</small>
<strong>{{ batchListed.buyers }}</strong>
</div>
<div class="metric-tile">
<small>Market Depth (Kgs)</small>
<strong>{{ totalQuantity.toLocaleString() }}</strong>
</div>
</div>
</div>

<aside class="readiness-card">
<h5>Live Board Snapshot</h5>
<ul class="readiness-list">
<li v-for="(item, idx) in requirements" :key="`req-${idx}`">{{ item }}</li>
</ul>
<Link href="/register" class="btn btn-brand w-100 mt-3">Register to Continue</Link>
</aside>
</section>

<section class="market-feed">
<div class="section-intro mb-2">
<p class="mini-kicker">Live Data</p>
<h3>marketBoard Feed</h3>
</div>
<div class="feed-table-wrap">
<table class="table align-middle mb-0 feed-table">
<thead>
<tr>
<th>Commodity</th>
<th>Type</th>
<th>Grade</th>
<th>Quantity</th>
<th>Min Price</th>
<th>Max Price</th>
</tr>
</thead>
<tbody>
<tr v-for="(row, idx) in marketBoard" :key="`feed-${idx}`">
<td class="text-capitalize">{{ row.commodity ?? '-' }}</td>
<td class="text-capitalize">{{ row.type ?? '-' }}</td>
<td class="text-capitalize">{{ row.grade ?? '-' }}</td>
<td>{{ formatNumber(row.quantity) }} Kgs</td>
<td>Shs. {{ formatNumber(row.min_price) }}</td>
<td>Shs. {{ formatNumber(row.max_price) }}</td>
</tr>
<tr v-if="marketBoard.length === 0">
<td colspan="6" class="text-center text-muted py-4">No marketBoard data available.</td>
</tr>
</tbody>
</table>
</div>
</section>

<section class="journey-block">
<div class="section-intro">
<p class="mini-kicker">Trading Journey</p>
<h3>Current Market Opportunities</h3>
</div>
<div class="journey-list">
<article v-for="(step, idx) in journey" :key="`journey-${idx}`" class="journey-item">
<div class="step-index">{{ idx + 1 }}</div>
<div>
<h6>{{ step.title }}</h6>
<p>{{ step.detail }}</p>
</div>
</article>
</div>
</section>

<section class="flow-grid">
<article class="flow-card">
<div class="flow-head">
<h5>Seller Focus</h5>
<span>Supply Side</span>
</div>
<ol>
<li v-for="(step, idx) in sellerSteps" :key="`seller-${idx}`">{{ step }}</li>
</ol>
</article>
<article class="flow-card">
<div class="flow-head">
<h5>Buyer Focus</h5>
<span>Demand Side</span>
</div>
<ol>
<li v-for="(step, idx) in buyerSteps" :key="`buyer-${idx}`">{{ step }}</li>
</ol>
</article>
</section>

<section class="register-banner">
<div>
<p class="mini-kicker mb-1">Ready To Enter The Market?</p>
<h4 class="mb-0">Create your account and execute your first trade.</h4>
</div>
<Link href="/register" class="btn btn-brand btn-lg cta-primary">Create Account Now</Link>
</section>
</div>
</home-layout>
</template>

<style scoped>
.trade-start {
display: grid;
gap: 1.5rem;
}

.hero-block {
display: grid;
grid-template-columns: 1.35fr 0.85fr;
gap: 1rem;
}

.hero-copy,
.readiness-card,
.journey-block,
.flow-card,
.register-banner {
border: 1px solid #dbe7ee;
border-radius: 18px;
background: rgba(255, 255, 255, 0.96);
box-shadow: 0 10px 24px rgba(16, 42, 67, 0.06);
}

.hero-copy {
padding: 1.5rem;
}

.cta-row {
display: flex;
flex-wrap: wrap;
gap: 0.6rem;
margin-top: 1rem;
}

.cta-primary {
min-width: 250px;
}

.cta-login {
border-color: #bfd6e4;
color: #1f3a56;
}

.metrics-row {
display: grid;
grid-template-columns: repeat(3, minmax(0, 1fr));
gap: 0.6rem;
margin-top: 1rem;
}

.metric-tile {
padding: 0.8rem 0.9rem;
border: 1px solid #e3edf3;
border-radius: 12px;
background: #fff;
}

.metric-tile small {
display: block;
color: #627d98;
}

.readiness-card {
padding: 1.25rem;
}

.readiness-list {
list-style: none;
padding: 0;
margin: 0.8rem 0 0 0;
display: grid;
gap: 0.7rem;
}

.readiness-list li {
display: grid;
grid-template-columns: 12px 1fr;
gap: 0.7rem;
align-items: start;
font-size: 0.92rem;
color: #334e68;
}

.readiness-list li::before {
content: '';
width: 9px;
height: 9px;
border-radius: 999px;
margin-top: 0.35rem;
background: linear-gradient(135deg, #0e8a7d, #0b6f65);
box-shadow: 0 0 0 2px rgba(14, 138, 125, 0.16);
}

.journey-block {
padding: 1.4rem;
}

.market-feed {
padding: 1.2rem;
border: 1px solid #dbe7ee;
border-radius: 18px;
background: rgba(255, 255, 255, 0.96);
box-shadow: 0 10px 24px rgba(16, 42, 67, 0.06);
}

.feed-table-wrap {
overflow-x: auto;
border: 1px solid #e3edf3;
border-radius: 12px;
background: #fff;
}

.feed-table thead th {
font-size: 0.74rem;
text-transform: uppercase;
letter-spacing: 0.05em;
color: #486581;
border-bottom-color: #dbe7ee;
}

.section-intro {
margin-bottom: 1rem;
}

.mini-kicker {
text-transform: uppercase;
letter-spacing: 0.08em;
font-weight: 800;
font-size: 0.74rem;
color: #0b6f65;
}

.journey-list {
display: grid;
gap: 0.8rem;
}

.journey-item {
display: grid;
grid-template-columns: 34px 1fr;
gap: 0.8rem;
align-items: start;
padding: 0.8rem;
border: 1px solid #e6eff4;
border-radius: 12px;
background: #fcfeff;
}

.journey-item h6 {
margin: 0 0 0.25rem 0;
}

.journey-item p {
margin: 0;
color: #627d98;
}

.step-index {
width: 34px;
height: 34px;
border-radius: 999px;
display: inline-flex;
align-items: center;
justify-content: center;
font-weight: 800;
background: #e8f4f2;
color: #0b6f65;
}

.flow-grid {
display: grid;
grid-template-columns: 1fr 1fr;
gap: 1rem;
}

.flow-card {
padding: 1.25rem;
}

.flow-head {
display: flex;
justify-content: space-between;
align-items: center;
margin-bottom: 0.8rem;
}

.flow-head span {
font-size: 0.75rem;
font-weight: 700;
color: #486581;
background: #eff6fb;
border-radius: 999px;
padding: 0.25rem 0.55rem;
}

.flow-card ol {
margin: 0;
padding-left: 1rem;
display: grid;
gap: 0.6rem;
color: #486581;
}

.register-banner {
padding: 1.2rem 1.3rem;
display: flex;
justify-content: space-between;
align-items: center;
gap: 1rem;
}

@media (max-width: 991.98px) {
.hero-block,
.flow-grid,
.register-banner {
grid-template-columns: 1fr;
}

.register-banner {
display: grid;
}
}

@media (max-width: 767.98px) {
.metrics-row {
grid-template-columns: 1fr;
}
}
</style>
