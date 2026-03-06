<script setup>
import AppLayout from '@/Layouts/AppLayoutV1.1.vue';
import TableDefault from '@/Tables/TableDefault.vue';
import FarmersTable from '@/Tables/FarmersTable.vue';
import { computed, ref } from 'vue';
import { usePage } from '@inertiajs/vue3';









const page = usePage();
const produces = computed(() => page.props.response?.produces?.data ?? page.props.response?.produces ?? []);
const listedCount = computed(() => produces.value.filter((p) => p.status === 'listed').length);
const soldCount = computed(() => produces.value.filter((p) => p.status === 'sold').length);
const toNumber = (value) => {
const parsed = Number(value ?? 0);
return Number.isFinite(parsed) ? parsed : 0;
};

const formatCount = (value) => new Intl.NumberFormat('en-UG').format(Math.round(value));
const formatMoney = (value) => `UGX ${new Intl.NumberFormat('en-UG').format(Math.round(value))}`;

const soldWeight = computed(() =>
produces.value
.filter((p) => p.status === 'sold')
.reduce((sum, p) => sum + toNumber(p.weight ?? p.quantity), 0),
);

const listedWeight = computed(() =>
produces.value
.filter((p) => ['listed', 'tokenized'].includes(p.status))
.reduce((sum, p) => sum + toNumber(p.weight ?? p.quantity), 0),
);

const grossSales = computed(() =>
produces.value
.filter((p) => p.status === 'sold')
.reduce((sum, p) => sum + (toNumber(p.price) * toNumber(p.weight ?? p.quantity)), 0),
);

const processingCount = computed(() =>
produces.value.filter((p) => !['sold', 'listed', 'tokenized'].includes(p.status)).length,
);

const withTrend = (label, value, type, tone, previousFactor = 0.86) => {
const previous = Math.max(value * previousFactor, 0);
const delta = previous > 0 ? ((value - previous) / previous) * 100 : 0;

return {
label,
valueLabel: type === 'currency' ? formatMoney(value) : formatCount(value),
previousLabel: type === 'currency' ? formatMoney(previous) : formatCount(previous),
deltaLabel: `${Math.abs(delta).toFixed(1)}%`,
direction: delta >= 0 ? 'up' : 'down',
tone,
};
};

const overviewTiles = computed(() => [
withTrend('Orders', produces.value.length, 'count', 'blue'),
withTrend('Items Sold', soldCount.value, 'count', 'orange'),
withTrend('Refunds', grossSales.value * 0.03, 'currency', 'green'),
withTrend('Gross Sale', grossSales.value, 'currency', 'red'),
withTrend('Shipping', listedWeight.value * 250, 'currency', 'green'),
withTrend('Processing', processingCount.value || listedCount.value, 'count', 'blue'),
]);

const tabs = computed(() => ([
{title:'Farmers',subtitle:'Farmers registered',stats:'100',icon:'ni-users'},
{title:'Coffee Available',subtitle:'Batches for sale',stats:String(listedCount.value),icon:'ni-package'},
{title:'Coffee Sold',subtitle:'Batches sold',stats:String(soldCount.value),icon:'ni-tranx'},
{title:'Buyers',subtitle:'Active buyers',stats:'400',icon:'ni-user-circle'}
]));



const payment_status=ref([
{item:'Revenue Generated',value:'Shs 50 M',icon:'ni-coins'},
{item:'Pending Payment',value:'50/2000',icon:'ni-clock'},
{item:'Farmers Paid',value:'1000',icon:'ni-user-check'},
{item:'Coffee Bags Sold',value:'2,000,000',icon:'ni-bag'},
{item:'Carbon Credit',value:'850 Tons CO2',icon:'ni-globe'},
{item:'Trees Planted',value:'15,000',icon:'ni-growth'},
{item:'Loans Issued',value:'320',icon:'ni-wallet'},
]);



const trend={
'Jan':20,
'Feb':30,
'Mar':20,
'Apr':50,
'May':30,
'Jun':40,
'Jul':60,
'Aug':50,
'Sep':10,
'Oct':50,
'Nov':80,
'Dec':100
};





const coffee_types=[
{name:'Arabica coffee',value:50},
{name:'Robusta',value:80},
{name:'Lberica',value:20},
{name:'Tuzza',value:10}

];




</script>

<template>
<app-layout>






<div class="nk-block">
<div class="row g-gs">


<div class="col-12 col-md-3" v-for="(t,key) in tabs" :key="key">



<div class="nk-order-ovwg-data card border">
<div class="ovwg-head">
<div class="amount">{{ t.title }}</div>
<span class="ovwg-icon" aria-hidden="true">
<em :class="`icon ni ${t.icon}`"></em>
</span>
</div>
<div class="info"><strong>{{ t.stats }}</strong></div>
<div class="title">{{ t.subtitle }}</div>
</div>





</div>


</div>








<div class="row g-gs">
<div class="col-12 col-md-8">

<div class="card card-bordered card-preview">
<div class="card-header bg-white border-bottom">
<h6>Coffee Price Offerings</h6>
</div>
<table-default/>
</div>







</div>
<div class="col-12 col-md-4">


<div class="card card-bordered pricing">
<div class="pricing-head">
<div class="pricing-title">
<h4 class="card-title title">Payments</h4>
<p class="sub-text">Enjoy entry level of invest &amp; earn.</p>
</div>
<div class="card-text">
<div class="row">
<div class="col-6">
<span class="h4 fw-500">1.67%</span>
<span class="sub-text">Daily Interest</span>
</div>
<div class="col-6">
<span class="h4 fw-500">30</span>
<span class="sub-text">Term Days</span>
</div>
</div>
</div>
</div>


<div class="pricing-body mb-0 pb-0">
<ul class="pricing-features">
<li v-for="(p,key) in payment_status" :key="key">
<em :class="`icon ni ${p.icon}`" style="margin-right: 8px; color: #000000;"></em>
<span class="w-50">
{{ p.item }}
</span> - <span class="ms-auto">
{{ p.value }}
</span></li>

</ul>
</div>


</div>







</div>
</div>






<div class="row g-gs">
<div class="col-12 col-md-8">

<div class="card card-bordered h-100">
<div class="card-inner">
<div class="card-title-group align-start mb-3">
<div class="card-title">
<h6 class="title">Coffee Market Overview</h6>
<p>Market overview in since Jan 2026</p>
</div>
</div><!-- .card-title-group -->
<div>

<line-chart :data="trend"></line-chart>


</div>
</div><!-- .card-inner -->
</div>
</div>
<div class="col-12 col-md-4">


<div class="card card-bordered h-100">
<div class="card-inner pb-0">
<div class="card-title-group pt-1">
<div class="card-title">
<h6 class="title">EMI Overview</h6>
</div>
<div class="card-tools">
<a href="html/loan/loan-details.html" class="link">See Details</a>
</div>
</div>
</div>
<div class="card-inner pt-0">
<div class="invest-ov gy-1">
<div class="subtitle">Activated Loan EMI</div>
<div class="invest-ov-details">
<div class="invest-ov-stats">
<div><span class="amount d-flex align-items-end text-primary">52<span class="sub-text ps-1"> Weeks</span></span></div>
<div class="title">Total EMI</div>
</div>
<div class="invest-ov-info">
<div class="amount">3560.395 <span class="currency currency-usd">USD</span></div>
<div class="title">Amount</div>
</div>
</div>
</div>
<div class="invest-ov gy-1">
<div class="subtitle">EMI Status</div>
<div class="invest-ov-details">
<div class="invest-ov-info">
<div><span class="amount text-success">39</span></div>
<div class="title">Paid</div>
</div>
<div class="invest-ov-info">
<div><span class="amount text-warning">13</span></div>
<div class="title">Due</div>
</div>
<div class="invest-ov-info">
<div><span class="date">13-05-2021</span></div>
<div class="title">Next EMI Date</div>
</div>
</div>
</div>
<div class="invest-ov">
<div class="subtitle">EMI Status</div>
<div class="progress progress-lg mt-3">
<div class="progress-bar progress-bar-striped progress-bar-animated bg-success" data-progress="65" style="width: 65%;">65%</div>
</div>
</div>
</div>
</div>





</div>
</div>






<div class="row g-gs">
<div class="col-12 col-md-7">
<div class="card border h-100">
<div class="card-header bg-white p-0">
<div class="card-title border-bottom p-2">
<h6>Farmers</h6>
</div>
</div>

<div class="card-body p-0">
<farmers-table/>
</div>

</div>
</div>
<div class="col-12 col-md-5">

<div class="card border h-100">
<div class="card-header bg-white p-0 mb-0">
<div class="card-title border-bottom p-2 mb-0">
<h6>Statistics</h6>
</div>
</div>

<div class="card-body p-0">
<div class="pl-4 pr-4 py-2 border-bottom" v-for="(m,key) in coffee_types" :key="key">
<div class="row">
<div class="col-12 col-md-4">{{ m.name }}</div>
<div class="col-12 col-md-8">
<el-progress :percentage="m.value" color="green" :stroke-width="8" />
</div>
</div>
</div>



</div>

</div>

</div>
</div>

<div class="row g-gs" id="statistics">
<div class="col-12">
<div class="stats-matrix-card card border">
<div class="stats-matrix-grid">
<article v-for="(tile, index) in overviewTiles" :key="`${tile.label}-${index}`" class="stats-matrix-cell">
<h6>{{ tile.label }}</h6>
<div class="stats-value">{{ tile.valueLabel }}</div>
<div class="stats-foot">
<span class="stats-base">{{ tile.previousLabel }}</span>
<span :class="['stats-delta', `tone-${tile.tone}`]">
<span class="delta-arrow">{{ tile.direction === 'up' ? '▲' : '▼' }}</span>
{{ tile.deltaLabel }}
</span>
</div>
</article>
</div>
</div>
</div>

</div>






</div>
</app-layout>
</template>

<style scoped>
.stats-matrix-card {
background: var(--card-bg, #ffffff);
--stats-border-color: var(--bs-border-color, #dbdfea);
border: 1px solid var(--stats-border-color);
border-radius: 12px;
padding: 0;
box-shadow: 0 6px 22px rgba(15, 23, 42, 0.05);
overflow: hidden;
}

.stats-matrix-grid {
display: grid;
grid-template-columns: repeat(3, minmax(0, 1fr));
}

.stats-matrix-cell {
padding: 1.65rem 1.5rem;
border-right: 1px solid var(--stats-border-color);
border-bottom: 1px solid var(--stats-border-color);
}

.stats-matrix-cell:nth-child(3n) {
border-right: none;
}

.stats-matrix-cell:nth-child(n + 4) {
border-bottom: none;
}

.stats-matrix-cell h6 {
margin: 0;
font-size: 0.88rem;
font-weight: 600;
color: var(--bs-secondary-color, #8094ae);
}

.stats-value {
margin-top: 0.85rem;
font-size: 20px;
font-weight: 700;
line-height: 1.05;
letter-spacing: -0.02em;
color: var(--bs-body-color, #364a63);
}

.stats-foot {
margin-top: 0.72rem;
display: inline-flex;
align-items: center;
gap: 1rem;
}

.stats-base {
font-size: 0.8rem;
font-weight: 500;
color: var(--bs-secondary-color, #8094ae);
}

.stats-delta {
display: inline-flex;
align-items: center;
gap: 0.28rem;
font-size: 0.8rem;
font-weight: 600;
}

.delta-arrow {
font-size: 0.7rem;
line-height: 1;
}

.tone-blue {
color: var(--bs-info, #09c2de);
}

.tone-orange {
color: var(--bs-warning, #f4bd0e);
}

.tone-green {
color: var(--bs-success, #1ee0ac);
}

.tone-red {
color: var(--bs-danger, #e85347);
}

.modern-stat-card {
background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
border: 1px solid rgba(111, 78, 55, 0.1);
border-radius: 16px;
padding: 1.5rem;
transition: all 0.3s ease;
position: relative;
overflow: hidden;
}

.modern-stat-card:hover {
transform: translateY(-4px);
border-color: rgba(111, 78, 55, 0.2);
}

.stat-title {
font-size: 1.1rem;
font-weight: 700;
color: #6F4E37;
margin-bottom: 0.75rem;
letter-spacing: -0.01em;
}

.stat-number {
font-size: 2.5rem;
font-weight: 800;
background: linear-gradient(135deg, #6F4E37 0%, #8B4513 100%);
-webkit-background-clip: text;
-webkit-text-fill-color: transparent;
background-clip: text;
margin-bottom: 0.5rem;
line-height: 1;
}

.stat-subtitle {
font-size: 0.875rem;
color: #64748b;
font-weight: 500;
display: flex;
align-items: center;
gap: 0.5rem;
}

.stat-icon {
color: #10b981;
font-size: 1rem;
}

/* Enhanced modern styling for existing KPI cards */
.nk-order-ovwg-data.card.border {
border-radius: 12px;
padding: 1rem 1rem 0.95rem;
border: 1px solid var(--bs-border-color, #dbdfea) !important;
transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
background: linear-gradient(180deg, #ffffff 0%, #f9fbfd 100%);
position: relative;
overflow: hidden;
min-height: 126px;
display: flex;
flex-direction: column;
justify-content: space-between;
box-shadow: 0 6px 22px rgba(15, 23, 42, 0.05);
}

.nk-order-ovwg-data.card.border::before {
content: '';
position: absolute;
top: 0;
left: 0;
bottom: 0;
width: 2px;
background: var(--bs-primary, #6576ff);
opacity: 0.45;
}

.nk-order-ovwg-data.card.border:hover {
transform: translateY(-2px);
border-color: #c9d7e7 !important;
box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
}

.ovwg-head {
display: flex;
align-items: flex-start;
justify-content: space-between;
gap: 0.6rem;
margin-bottom: 0.55rem;
position: relative;
z-index: 2;
}

.nk-order-ovwg-data .amount {
font-size: 0.72rem;
font-weight: 700;
color: var(--bs-secondary-color, #8094ae);
letter-spacing: 0.06em;
position: relative;
line-height: 1.2;
text-transform: uppercase;
margin: 0;
max-width: 75%;
}

.nk-order-ovwg-data .info {
margin-bottom: 0.42rem;
position: relative;
z-index: 2;
}

.nk-order-ovwg-data .info strong {
font-size: 1.45rem;
font-weight: 700;
color: var(--bs-body-color, #364a63);
line-height: 1.1;
letter-spacing: -0.03em;
display: block;
}

.nk-order-ovwg-data .title {
font-size: 0.78rem;
color: var(--bs-secondary-color, #8094ae);
font-weight: 600;
position: relative;
z-index: 2;
letter-spacing: 0;
line-height: 1.3;
}

.ovwg-icon {
width: 28px;
height: 28px;
display: inline-flex;
align-items: center;
justify-content: center;
border-radius: 8px;
background: #eef2ff;
color: var(--bs-primary, #6576ff);
border: 1px solid #dde6ff;
font-size: 0.86rem;
flex-shrink: 0;
}

.ovwg-icon .icon {
line-height: 1;
}

.row.g-gs > [class*='col-']:nth-child(4n + 2) .nk-order-ovwg-data .ovwg-icon {
background: #ecfeff;
border-color: #cffafe;
color: #0e7490;
}

.row.g-gs > [class*='col-']:nth-child(4n + 3) .nk-order-ovwg-data .ovwg-icon {
background: #ecfdf3;
border-color: #ccf1de;
color: #15803d;
}

.row.g-gs > [class*='col-']:nth-child(4n + 4) .nk-order-ovwg-data .ovwg-icon {
background: #fff7ed;
border-color: #ffedd5;
color: #b45309;
}

@media (max-width: 768px) {
.stats-matrix-grid {
grid-template-columns: 1fr;
}

.stats-matrix-cell {
border-right: none;
padding: 1.1rem 1rem;
}

.stats-matrix-cell:nth-child(n + 4) {
border-bottom: 1px solid var(--stats-border-color);
}

.stats-matrix-cell:last-child {
border-bottom: none;
}

.stats-value {
font-size: 20px;
}

.nk-order-ovwg-data.card.border {
padding: 0.85rem;
min-height: 112px;
}

.ovwg-head {
margin-bottom: 0.45rem;
}

.nk-order-ovwg-data .amount {
font-size: 0.68rem;
}

.nk-order-ovwg-data .info strong {
font-size: 1.28rem;
}

.nk-order-ovwg-data .title {
font-size: 0.74rem;
}

.ovwg-icon {
width: 26px;
height: 26px;
font-size: 0.8rem;
}
}
</style>
