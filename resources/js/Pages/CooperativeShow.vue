<script setup>
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';
import { router, usePage } from '@inertiajs/vue3';
import { ref, onMounted, computed } from 'vue';
import { Plus } from '@element-plus/icons-vue';


const props=defineProps({
title : String,
response : Object
});


const page = usePage();
const produces = computed(() => page.props.response?.produces?.data ?? page.props.response?.produces ?? []);
const listedCount = computed(() => produces.value.filter((p) => p.status === 'listed').length);
const soldCount = computed(() => Number(page.props.response?.sold_count ?? 0));
const listedQuantityTotal = computed(() => Number(page.props.response?.listed_quantity_total ?? 0));
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
withTrend('Items Sold', soldCount.value || soldWeight.value, 'count', 'orange'),
withTrend('Refunds', grossSales.value * 0.03, 'currency', 'green'),
withTrend('Gross Sale', grossSales.value, 'currency', 'red'),
withTrend('Shipping', listedWeight.value * 250, 'currency', 'green'),
withTrend('Processing', processingCount.value || listedCount.value, 'count', 'blue'),
]);









const payment_status = ref([
  { item: 'Revenue Generated', value: 'Shs 50 M', icon: 'ni-coins' },
  { item: 'Pending Payment', value: '50/2000', icon: 'ni-clock' },
  { item: 'Farmers Paid', value: '1000', icon: 'ni-user-check' },
  { item: 'Coffee Bags Sold', value: '2,000,000', icon: 'ni-bag' },
  { item: 'Carbon Credit', value: '850 Tons CO2', icon: 'ni-globe' },
  { item: 'Trees Planted', value: '15,000', icon: 'ni-growth' },
  { item: 'Loans Issued', value: '320', icon: 'ni-wallet' },
]);



const trend = {
  Jan: 20,
  Feb: 30,
  Mar: 20,
  Apr: 50,
  May: 30,
  Jun: 40,
  Jul: 60,
  Aug: 50,
  Sep: 10,
  Oct: 50,
  Nov: 80,
  Dec: 100,
};



const coffee_types = [
  { name: 'Arabica coffee', value: 50 },
  { name: 'Robusta', value: 80 },
  { name: 'Liberica', value: 20 },
  { name: 'Tuzza', value: 10 },
];




onMounted(()=>{
console.log(page.props.response.farmers);
});


const cooperative=computed(()=>{
return props.response.cooperative;
});

const count_farmers=computed(()=>props.response.count_farmers);
const farmers=computed(()=>props.response.farmers.data);




const tabs = computed(() => [
  { title: 'Farmers', subtitle: 'Farmers registered', stats: count_farmers, icon: 'ni-users' },
  { title: 'Coffee Available (Kgs)', subtitle: 'Listed for sale', stats: listedQuantityTotal, icon: 'ni-package' },
  { title: 'Coffee Sold', subtitle: 'Batches sold', stats: soldCount, icon: 'ni-tranx' },
  { title: 'Buyers', subtitle: 'Active buyers', stats: '400', icon: 'ni-user-circle' },
]);

const goToBatchDetails = (row) => {
  const batchId = Number(row?.batch_id ?? row?.id);
  if (!Number.isFinite(batchId) || batchId <= 0) {
    return;
  }

  router.get(route('cooperative.batches.show', { id: batchId }));
};

const goCreateCommodity = () => {
router.get(route('cooperative.produce.create'));
};

const goCreateBatch = () => {
router.get(route('cooperative.batches.create'));
};





</script>

<template>
<cooperative-layout>
<div class="nk-block">




<div class="row g-gs">
<div class="col-12 col-md-3" v-for="(t,key) in tabs" :key="key">


<div class="nk-order-ovwg-data card border">
<div class="amount">{{ t.title }}</div>
<div class="info"><strong>{{ t.stats }}</strong></div>
<div class="title">
<em :class="`icon ni ${t.icon}`"></em>
{{ t.subtitle }}
</div>
</div>



</div>
</div>




<div class="row g-gs">
<div class="col-12 col-md-8">
<div class="card card-bordered card-preview modern-panel">
<div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
<h6 class="mb-0">Your Coffee Price Offerings</h6>
<span class="sub-text">Live bids</span>
</div>
<div class="card-body p-0">
<el-table :data="produces" height="350" style="width: 100%" @row-click="goToBatchDetails" class="clickable-batches-table">
<el-table-column prop="commodity_name" width="170">
<template #header><em class="icon ni ni-growth mr-1"></em>Commodity</template>
</el-table-column>
<el-table-column prop="batch_code" width="150">
<template #header><em class="icon ni ni-tag mr-1"></em>Batch Code</template>
</el-table-column>
<el-table-column prop="weight" width="110">
<template #header><em class="icon ni ni-package mr-1"></em>Weight</template>
</el-table-column>
<el-table-column prop="price" width="110">
<template #header><em class="icon ni ni-coins mr-1"></em>Price</template>
</el-table-column>
<el-table-column prop="created_at" min-width="170">
<template #header><em class="icon ni ni-calendar mr-1"></em>Created</template>
</el-table-column>
</el-table>
</div>
</div>
</div>

<div class="col-12 col-md-4">
<div class="card card-bordered pricing modern-panel">
<div class="pricing-head">
<div class="pricing-title">
<h4 class="card-title title">Payments</h4>
<p class="sub-text">Overview of finance and sustainability metrics.</p>
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
<span class="w-50">{{ p.item }}</span>
-
<span class="ms-auto">{{ p.value }}</span>
</li>
</ul>
</div>
</div>
</div>
</div>

<div class="row g-gs">
<div class="col-12 col-md-8">
<div class="card card-bordered h-100 modern-panel">
<div class="card-inner">
<div class="card-title-group align-start mb-3">
<div class="card-title">
<h6 class="title">Coffee Market Overview</h6>
<p>Market overview since Jan 2026</p>
</div>
</div>
<div>
<line-chart :data="trend"></line-chart>
</div>
</div>
</div>
</div>

<div class="col-12 col-md-4">
<div class="card card-bordered h-100 modern-panel">
<div class="card-inner pb-0">
<div class="card-title-group pt-1">
<div class="card-title">
<h6 class="title">EMI Overview</h6>
</div>
<div class="card-tools">
<a href="#" class="link">See Details</a>
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
<div class="subtitle">EMI Progress</div>
<div class="progress progress-lg mt-3">
<div class="progress-bar progress-bar-striped progress-bar-animated bg-success" style="width: 65%;">65%</div>
</div>
</div>
</div>
</div>
</div>
</div>

<div class="row g-gs">

<div class="col-12 col-md-7">
<div class="card border h-100 modern-panel">
<div class="card-header bg-white p-0">
<div class="card-title border-bottom p-2">
<h6>Farmers</h6>
</div>
</div>
<div class="card-body p-0">
<el-table :data="farmers" height="250" style="width: 100%">
<el-table-column prop="name" label="Names" width="180" />
<el-table-column prop="gender" label="Gender" width="80" />
<el-table-column prop="produce" label="Produce" />
<el-table-column prop="location" label="Location" />
<el-table-column prop="status" label="Status" />
</el-table>
</div>
</div>
</div>

<div class="col-12 col-md-5">
<div class="card border h-100 modern-panel">
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
<div class="card border stats-matrix-card">
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






<el-dropdown trigger="click" placement="top-end" class="cooperative-fab-dropdown">
<el-button class="cooperative-fab" type="success" circle :icon="Plus" />
<template #dropdown>
<el-dropdown-menu class="cooperative-fab-menu">
<el-dropdown-item @click="goCreateCommodity"><em class="icon ni ni-growth mr-1"></em>Create Commodity</el-dropdown-item>
<el-dropdown-item @click="goCreateBatch"><em class="icon ni ni-package mr-1"></em>Create Batch</el-dropdown-item>
</el-dropdown-menu>
</template>
</el-dropdown>

</div>
</cooperative-layout>
</template>

<style scoped>
.cooperative-fab-dropdown {
position: fixed;
right: 24px;
bottom: 28px;
z-index: 1100;
}

:deep(.cooperative-fab.el-button) {
width: 56px;
height: 56px;
background-color: #111111 !important;
border-color: #111111 !important;
color: #ffffff !important;
box-shadow: 0 14px 28px rgba(17, 17, 17, 0.4);
}

:deep(.cooperative-fab.el-button:hover),
:deep(.cooperative-fab.el-button:focus) {
background-color: #000000 !important;
border-color: #000000 !important;
color: #ffffff !important;
}

:deep(.cooperative-fab .el-icon) {
font-size: 24px;
font-weight: 700;
}

:deep(.cooperative-fab-menu .el-dropdown-menu__item) {
display: inline-flex;
align-items: center;
gap: 0.35rem;
font-size: 0.86rem;
}

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

.dashboard-head {
display: flex;
justify-content: space-between;
align-items: center;
gap: 1rem;
}

.dashboard-head-actions {
display: flex;
align-items: center;
gap: 0.5rem;
}

.highlight-card {
border-radius: 10px;
}

.highlight-card .icon {
font-size: 1.1rem;
color: #334155;
}

.modern-panel {
border-radius: 12px;
box-shadow: 0 6px 22px rgba(15, 23, 42, 0.05);
overflow: hidden;
}

.modern-panel .card-header,
.modern-panel .card-inner,
.modern-panel .card-body {
border-radius: inherit;
}

:deep(.modern-panel .el-table),
:deep(.modern-panel .el-table__inner-wrapper),
:deep(.modern-panel .el-table__header-wrapper),
:deep(.modern-panel .el-table__body-wrapper) {
border-radius: 12px;
}

:deep(.clickable-batches-table .el-table__row) {
cursor: pointer;
}

:deep(.modern-panel .el-table__header-wrapper) {
border-top-left-radius: 12px;
border-top-right-radius: 12px;
}

:deep(.modern-panel .el-table__body-wrapper) {
border-bottom-left-radius: 12px;
border-bottom-right-radius: 12px;
}

.nk-order-ovwg-data.card.border {
border-radius: 12px;
padding: 0.875rem;
border: none;
transition: all 0.3s ease;
overflow: hidden;
backdrop-filter: blur(10px);
}

.nk-order-ovwg-data.card.border:hover {
transform: translateY(-4px) scale(1.01);
background: linear-gradient(135deg, #ffffff 0%, #fefefe 50%, #f8fafc 100%);
}

.nk-order-ovwg-data .amount {
font-size: 0.95rem;
font-weight: 400;
color: #526484;
margin-bottom: 0.6rem;
line-height: 1.2;
text-transform: uppercase;
}

.nk-order-ovwg-data .info {
margin-bottom: 0.5rem;
}

.nk-order-ovwg-data .info strong {
font-size: 1.6rem;
font-weight: 400;
color: #526484;
line-height: 1.1;
}

.nk-order-ovwg-data .title {
font-size: 0.8rem;
color: #526484;
font-weight: 400 !important;
display: flex;
align-items: center;
gap: 0.4rem;
line-height: 1.3;
}

.nk-order-ovwg-data .title em {
color: #526484;
font-size: 0.9rem;
}

@media (max-width: 991px) {
.dashboard-head {
flex-direction: column;
align-items: flex-start;
}
}

@media (max-width: 768px) {
.cooperative-fab-dropdown {
right: 12px;
bottom: 16px;
}

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
padding: 0.75rem;
}

.nk-order-ovwg-data .amount {
font-size: 0.85rem;
}

.nk-order-ovwg-data .info strong {
font-size: 1.4rem;
}

.nk-order-ovwg-data .title {
font-size: 0.75rem;
}
}
</style>
