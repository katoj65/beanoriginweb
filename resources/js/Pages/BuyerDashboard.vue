<script setup>
import { computed, ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import BuyerLayout from '@/Layouts/BuyerLayout.vue';

const page = usePage();

const fallbackListings = [
  {
    batch_id: 101,
    commodity_name: 'Arabica Coffee',
    batch_code: 'AR-2401',
    seller_name: 'Kigezi Cooperative',
    weight: 1250,
    ask_price: 14200,
    location: 'Kabale',
    created_at: '2026-02-18',
  },
  {
    batch_id: 102,
    commodity_name: 'Robusta Coffee',
    batch_code: 'RB-2409',
    seller_name: 'Bugisu Growers',
    weight: 1780,
    ask_price: 11800,
    location: 'Mbale',
    created_at: '2026-02-20',
  },
  {
    batch_id: 103,
    commodity_name: 'Premium Arabica',
    batch_code: 'PA-2411',
    seller_name: 'Rwenzori Farms',
    weight: 940,
    ask_price: 16700,
    location: 'Kasese',
    created_at: '2026-02-21',
  },
  {
    batch_id: 104,
    commodity_name: 'Robusta Screen 18',
    batch_code: 'RS-2414',
    seller_name: 'Luweero Producers',
    weight: 2200,
    ask_price: 10950,
    location: 'Luweero',
    created_at: '2026-02-22',
  },
];

const fallbackSuppliers = [
  { name: 'Kigezi Cooperative', orders: 8, delivered: 6, rating: '4.8/5', region: 'Western' },
  { name: 'Bugisu Growers', orders: 6, delivered: 5, rating: '4.6/5', region: 'Eastern' },
  { name: 'Rwenzori Farms', orders: 5, delivered: 5, rating: '4.9/5', region: 'Western' },
  { name: 'Luweero Producers', orders: 4, delivered: 3, rating: '4.4/5', region: 'Central' },
];

const marketListings = computed(() => {
  const source = page.props.response?.market_listings;
  return Array.isArray(source) ? source : fallbackListings;
});

const suppliers = computed(() => {
  const source = page.props.response?.supplier_activity;
  return Array.isArray(source) ? source : fallbackSuppliers;
});

const totalSpend = computed(() => Number(page.props.response?.total_spend ?? 15420000));
const openOrders = computed(() => Number(page.props.response?.open_orders_count ?? 12));
const watchlistCount = computed(() => Number(page.props.response?.watchlist_count ?? marketListings.value.length));
const activeSuppliers = computed(() => {
  const supplied = Number(page.props.response?.active_suppliers_count);
  if (Number.isFinite(supplied)) return supplied;
  return new Set(marketListings.value.map((row) => row.seller_name).filter(Boolean)).size;
});

const tabs = computed(() => [
  { title: 'Open Orders', subtitle: 'Orders in progress', stats: openOrders.value, icon: 'ni-cart' },
  { title: 'Total Spend', subtitle: 'Current month spend', stats: `UGX ${totalSpend.value.toLocaleString()}`, icon: 'ni-coins' },
  { title: 'Watchlist', subtitle: 'Tracked market lots', stats: watchlistCount.value, icon: 'ni-eye' },
  { title: 'Suppliers', subtitle: 'Active sellers', stats: activeSuppliers.value, icon: 'ni-users' },
]);

const procurementStatus = ref([
  { item: 'Orders Placed', value: '28', icon: 'ni-cart-fill' },
  { item: 'Awaiting Delivery', value: '12', icon: 'ni-clock' },
  { item: 'Delivered This Month', value: '16', icon: 'ni-check-circle-fill' },
  { item: 'Average Price / Kg', value: 'UGX 12,950', icon: 'ni-price-tag-fill' },
  { item: 'Preferred Suppliers', value: '7', icon: 'ni-user-list-fill' },
]);

const trend = {
  Jan: 40,
  Feb: 55,
  Mar: 48,
  Apr: 64,
  May: 72,
  Jun: 70,
  Jul: 82,
  Aug: 76,
  Sep: 88,
  Oct: 92,
  Nov: 95,
  Dec: 102,
};

const commodityDemand = ref([
  { name: 'Arabica', value: 82 },
  { name: 'Robusta', value: 68 },
  { name: 'Specialty Lots', value: 54 },
  { name: 'Organic Coffee', value: 37 },
]);

const formatWeight = (value) => `${Number(value ?? 0).toLocaleString()} kg`;
const formatPrice = (value) => `UGX ${Number(value ?? 0).toLocaleString()}`;
const formatDate = (value) => {
  if (!value) return 'N/A';
  const date = new Date(value);
  if (Number.isNaN(date.getTime())) return String(value);
  return date.toLocaleDateString();
};
</script>

<template>
<buyer-layout>
<div class="container py-1">
<div class="row">
<div class="col-12 col-md-3" v-for="(t, key) in tabs" :key="key">
<div class="nk-order-ovwg-data card border modern-panel stat-card">
<div class="amount">{{ t.title }}</div>
<div class="info"><strong>{{ t.stats }}</strong></div>
<div class="title"><em :class="`icon ni ${t.icon}`"></em>{{ t.subtitle }}</div>
</div>
</div>
</div>

<div class="row mt-4">
<div class="col-12 col-md-8">
<div class="card card-bordered card-preview modern-panel">
<div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
<h6 class="mb-0">Marketplace Listings</h6>
<span class="sub-text">Fresh lots available for bidding</span>
</div>
<div class="card-body p-0">
<el-table :data="marketListings" height="340" style="width: 100%">
<el-table-column prop="commodity_name" width="160" label="Commodity" />
<el-table-column prop="batch_code" width="120" label="Batch" />
<el-table-column prop="seller_name" width="170" label="Supplier" />
<el-table-column prop="weight" width="110" label="Weight">
<template #default="scope">{{ formatWeight(scope.row.weight) }}</template>
</el-table-column>
<el-table-column prop="ask_price" width="140" label="Ask Price">
<template #default="scope">{{ formatPrice(scope.row.ask_price) }}</template>
</el-table-column>
<el-table-column prop="location" width="120" label="Location" />
<el-table-column prop="created_at" min-width="130" label="Listed">
<template #default="scope">{{ formatDate(scope.row.created_at) }}</template>
</el-table-column>
</el-table>
</div>
</div>
</div>

<div class="col-12 col-md-4">
<div class="card card-bordered pricing modern-panel h-100">
<div class="pricing-head">
<div class="pricing-title">
<h4 class="card-title title">Procurement Snapshot</h4>
<p class="sub-text">Current order and supplier performance summary.</p>
</div>
</div>

<div class="pricing-body mb-0 pb-0">
<ul class="pricing-features">
<li v-for="(item, key) in procurementStatus" :key="key">
<em :class="`icon ni ${item.icon}`" style="margin-right: 8px; color: #000000;"></em>
<span class="w-50">{{ item.item }}</span>
<span class="ms-auto">{{ item.value }}</span>
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
<h6 class="title">Buyer Spend Trend</h6>
<p>Monthly procurement volume overview for 2026.</p>
</div>
</div>
<line-chart :data="trend" />
</div>
</div>
</div>

<div class="col-12 col-md-4">
<div class="card card-bordered h-100 modern-panel">
<div class="card-inner pb-0">
<div class="card-title-group pt-1">
<div class="card-title">
<h6 class="title">Order Fulfillment</h6>
</div>
</div>
</div>
<div class="card-inner pt-0">
<div class="invest-ov gy-1">
<div class="subtitle">Delivery Performance</div>
<div class="invest-ov-details">
<div class="invest-ov-info">
<div><span class="amount text-success">78%</span></div>
<div class="title">On Time</div>
</div>
<div class="invest-ov-info">
<div><span class="amount text-warning">15%</span></div>
<div class="title">Delayed</div>
</div>
<div class="invest-ov-info">
<div><span class="amount text-danger">7%</span></div>
<div class="title">Canceled</div>
</div>
</div>
</div>
<div class="invest-ov">
<div class="subtitle">Target Completion</div>
<div class="progress progress-lg mt-3">
<div class="progress-bar progress-bar-striped progress-bar-animated bg-success" style="width: 78%;">78%</div>
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
<h6>Supplier Activity</h6>
</div>
</div>
<div class="card-body p-0">
<el-table :data="suppliers" height="250" style="width: 100%">
<el-table-column prop="name" label="Supplier" min-width="170" />
<el-table-column prop="orders" label="Orders" width="90" />
<el-table-column prop="delivered" label="Delivered" width="100" />
<el-table-column prop="rating" label="Rating" width="90" />
<el-table-column prop="region" label="Region" width="100" />
</el-table>
</div>
</div>
</div>

<div class="col-12 col-md-5">
<div class="card border h-100 modern-panel">
<div class="card-header bg-white p-0 mb-0">
<div class="card-title border-bottom p-2 mb-0">
<h6>Demand by Commodity</h6>
</div>
</div>
<div class="card-body p-0">
<div class="pl-4 pr-4 py-2 border-bottom" v-for="(item, key) in commodityDemand" :key="key">
<div class="row">
<div class="col-12 col-md-5">{{ item.name }}</div>
<div class="col-12 col-md-7">
<el-progress :percentage="item.value" color="#1d4ed8" :stroke-width="8" />
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</buyer-layout>
</template>

<style scoped>
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
  font-size: 1.5rem;
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
  color: #2563eb;
  font-size: 0.9rem;
}

.stat-card {
  min-height: 122px;
}

@media (max-width: 768px) {
  .nk-order-ovwg-data.card.border {
    padding: 0.75rem;
  }

  .nk-order-ovwg-data .amount {
    font-size: 0.85rem;
  }

  .nk-order-ovwg-data .info strong {
    font-size: 1.3rem;
  }

  .nk-order-ovwg-data .title {
    font-size: 0.75rem;
  }
}
</style>
