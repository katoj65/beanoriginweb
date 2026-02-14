<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import TableDefault from '@/Tables/TableDefault.vue';
import FarmersTable from '@/Tables/FarmersTable.vue';
import { ref } from 'vue';

const tabs = ref([
  { title: 'Farmers', subtitle: 'Farmers registered', stats: '100', icon: 'ni-users' },
  { title: 'Coffee Available', subtitle: 'Batches for sale', stats: '200', icon: 'ni-package' },
  { title: 'Coffee Sold', subtitle: 'Batches sold', stats: '300', icon: 'ni-tranx' },
  { title: 'Buyers', subtitle: 'Active buyers', stats: '400', icon: 'ni-user-circle' },
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

const highlights = ref([
  { label: 'Today Deliveries', value: '27', icon: 'ni-truck' },
  { label: 'Open Purchase Orders', value: '12', icon: 'ni-cart' },
  { label: 'Certified Farms', value: '84%', icon: 'ni-shield-check' },
]);

const today = new Date().toLocaleDateString('en-US', {
  weekday: 'short',
  month: 'short',
  day: 'numeric',
  year: 'numeric',
});
</script>

<template>
<app-layout>
<div class="">


<div class="row">
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

<div class="row mt-4">
<div class="col-12 col-md-8">
<div class="card card-bordered card-preview modern-panel">
<div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
<h6 class="mb-0">Coffee Price Offerings</h6>
<span class="sub-text">Live buyer bids</span>
</div>
<table-default/>
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

<div class="row mt-4">
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

<div class="row mt-4">
<div class="col-12 col-md-7">
<div class="card border h-100 modern-panel">
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

</div>
</app-layout>
</template>

<style scoped>
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
font-weight: 800;
color: #000000;
margin-bottom: 0.6rem;
line-height: 1.2;
text-transform: uppercase;
}

.nk-order-ovwg-data .info {
margin-bottom: 0.5rem;
}

.nk-order-ovwg-data .info strong {
font-size: 1.6rem;
font-weight: 900;
color: #000000;
line-height: 1.1;
}

.nk-order-ovwg-data .title {
font-size: 0.8rem;
color: #000000;
font-weight: 600;
display: flex;
align-items: center;
gap: 0.4rem;
line-height: 1.3;
}

.nk-order-ovwg-data .title em {
color: #10b981;
font-size: 0.9rem;
}

@media (max-width: 991px) {
.dashboard-head {
flex-direction: column;
align-items: flex-start;
}
}

@media (max-width: 768px) {
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
