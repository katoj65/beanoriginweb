<script setup>
import { computed, ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import { Tickets } from '@element-plus/icons-vue';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';

const page = usePage();
const searchQuery = ref('');
const activeGroup = ref('bids');

const batches = computed(() => {
const source = page.props.batches ?? [];
return Array.isArray(source) ? source : [];
});

const myBids = computed(() => {
const source = page.props.my_bids ?? [];
return Array.isArray(source) ? source : [];
});

const submittedBids = computed(() => {
const source = page.props.submitted_bids ?? [];
if (Array.isArray(source)) return source;
if (Array.isArray(source?.data)) return source.data;
return [];
});

const submittedBatchRows = computed(() => {
return submittedBids.value.map((item) => {
const batchId = Number(item?.batch_id ?? item?.id ?? 0);
const batch = batches.value.find((entry) => Number(entry?.id ?? 0) === batchId) ?? null;
const bidUsers = Array.isArray(batch?.bid_users) ? batch.bid_users : [];
const seenBidders = new Set();
const biddersCount = bidUsers.filter((user) => {
const key = user?.id ?? user?.email ?? '';
if (!key || seenBidders.has(key)) return false;
seenBidders.add(key);
return true;
}).length;

return {
id: item?.id ?? batchId,
batch_id: batchId || null,
batch_code: batch?.batch_code ?? null,
commodity_name: batch?.commodity_name ?? null,
commodity_type: batch?.commodity_type ?? null,
grade: batch?.grade ?? null,
weight: batch?.weight ?? null,
quantity: batch?.quantity ?? null,
price: batch?.price ?? null,
warehouse: batch?.warehouse ?? null,
number_of_bidders: Number(item?.number_of_bidders ?? biddersCount ?? 0),
};
});
});

const filteredBatches = computed(() => {
if (activeGroup.value === 'bids') return batches.value;

const query = searchQuery.value.trim().toLowerCase();
if (!query) return batches.value;

return batches.value.filter((item) => {
const haystack = [
item.batch_code,
item.commodity_name,
item.commodity_type,
item.grade,
item.warehouse,
item.status,
item.market_type,
]
.join(' ')
.toLowerCase();

return haystack.includes(query);
});
});

const formatMoney = (value, currency = 'UGX') => {
const amount = Number(value ?? 0);
if (Number.isNaN(amount)) return `${currency} 0`;
return `${currency} ${amount.toLocaleString()}`;
};

const formatQuantity = (value) => Number(value ?? 0).toLocaleString();

const formatWeight = (value) => `${Number(value ?? 0).toLocaleString()} kg`;

const formatDateTime = (value) => {
if (!value) return 'N/A';
const date = new Date(value);
if (Number.isNaN(date.getTime())) return String(value);
return date.toLocaleString();
};

const statusClass = (status) => {
const key = String(status ?? '').toLowerCase();
if (key === 'complete' || key === 'completed' || key === 'approved') return 'badge bg-success-subtle text-success';
if (key === 'failed' || key === 'rejected' || key === 'banned') return 'badge bg-danger-subtle text-danger';
return 'badge bg-warning-subtle text-warning';
};

const getBidUsers = (item) => {
const users = Array.isArray(item?.bid_users) ? item.bid_users : [];
const seen = new Set();

return users.filter((user) => {
const key = user?.id ?? user?.email ?? '';
if (!key || seen.has(key)) return false;
seen.add(key);
return true;
});
};

const formatBidderName = (user) => {
const fullName = [user?.fname, user?.lname].filter(Boolean).join(' ').trim();
return fullName || user?.email || `User #${user?.id ?? ''}`;
};

const visibleBidderNames = (item) => getBidUsers(item).slice(0, 2).map(formatBidderName);

const hiddenBidderCount = (item) => Math.max(getBidUsers(item).length - 2, 0);

const hasBidders = (item) => getBidUsers(item).length > 0;

const openBatchDetails = (id) => {
const batchId = Number(id ?? 0);
if (!batchId) return;
router.get(route('bid.show', { id: batchId }));
};

const activeBiddingTab = ref('bidding_batches');

</script>

<template>
<CooperativeLayout>
<div class="container bidding-page">
<section class="card table-card">
<div class="card-inner table-top border-0">
<div class="table-heading">
<div class="title-line">
<el-icon class="title-icon"><Tickets /></el-icon>
<h6 class="mb-0">Bidding Batches</h6>
</div>
<p class="subtitle mb-0">

Click any row to open batch details.
</p>
</div>
</div>

<div class="card-inner p-0">
<el-tabs v-model="activeBiddingTab" class="app-themed-tabs bidding-main-tabs">
<el-tab-pane name="bidding_batches">
<template #label>
<span class="table-head-label"><em class="icon ni ni-grid mr-1"></em>Bidding</span>
</template>
<div class="table-responsive pt-0 mt-0">
<table class="table table-sm table-middle mb-0 bidding-table mt-0">
<thead>
<tr>
<th><span class="table-head-label"><em class="icon ni ni-hash"></em>Batch</span></th>
<th><span class="table-head-label"><em class="icon ni ni-growth"></em>Commodity</span></th>
<th><span class="table-head-label"><em class="icon ni ni-award"></em>Grade</span></th>
<th><span class="table-head-label"><em class="icon ni ni-package"></em>Weight</span></th>
<th><span class="table-head-label"><em class="icon ni ni-layers"></em>Quantity</span></th>
<th><span class="table-head-label"><em class="icon ni ni-coins"></em>Price</span></th>
<th><span class="table-head-label"><em class="icon ni ni-building"></em>Warehouse</span></th>
<th><span class="table-head-label"><em class="icon ni ni-users"></em>Bidders</span></th>
</tr>
</thead>
<tbody>
<tr v-for="item in filteredBatches" :key="item.id" class="clickable-row" @click="openBatchDetails(item.id)">
<td>{{ item.batch_code ?? `#${item.id}` }}</td>
<td class="text-capitalize">{{ item.commodity_name ?? 'N/A' }} - {{ item.commodity_type ?? 'N/A' }}</td>
<td>{{ item.grade ?? 'N/A' }}</td>
<td>{{ formatWeight(item.weight) }}</td>
<td>{{ formatQuantity(item.quantity) }}</td>
<td>{{ formatMoney(item.price) }}</td>
<td>{{ item.warehouse ?? 'N/A' }}</td>
<td>
<div class="bidders-cell">
<template v-if="hasBidders(item)">
<span v-for="name in visibleBidderNames(item)" :key="name" class="bidder-chip text-capitalize">
    {{ name }}
</span>
<span v-if="hiddenBidderCount(item)" class="bidders-more">+{{ hiddenBidderCount(item) }} more</span>
</template>
<span v-else class="bidders-empty">No bids</span>
</div>
</td>
</tr>
<tr v-if="!filteredBatches.length">
<td colspan="8" class="empty-cell">No bidding batches found.</td>
</tr>
</tbody>
</table>
</div>
</el-tab-pane>

<el-tab-pane name="submitted_bids">
<template #label>
<span class="table-head-label"><em class="icon ni ni-check-circle mr-1"></em>Submitted Bids</span>
</template>
<div class="table-responsive">
<table class="table table-sm table-middle mb-0 bidding-table">
<thead>
<tr>
<th><span class="table-head-label"><em class="icon ni ni-hash"></em>Batch</span></th>
<th><span class="table-head-label"><em class="icon ni ni-growth"></em>Commodity / Type</span></th>
<th><span class="table-head-label"><em class="icon ni ni-package"></em>Weight</span></th>
<th><span class="table-head-label"><em class="icon ni ni-layers"></em>Quantity / Price</span></th>
<th><span class="table-head-label"><em class="icon ni ni-users"></em>bidders</span></th>
<th><span class="table-head-label"><em class="icon ni ni-building"></em>Warehouse</span></th>
</tr>
</thead>
<tbody>
<tr v-for="item in submittedBatchRows" :key="item.id" class="clickable-row" @click="openBatchDetails(item.batch_id)">
<td>{{ item.batch_code ?? `#${item.batch_id ?? 'N/A'}` }}</td>
<td class="text-capitalize">{{ item.commodity_name ?? 'N/A' }} / {{ item.commodity_type ?? 'N/A' }} / {{ item.grade ?? 'N/A' }}</td>
<td>{{ formatWeight(item.weight) }}</td>
<td>{{ formatQuantity(item.quantity) }} / {{ formatMoney(item.price) }}</td>
<td>{{ formatQuantity(item.number_of_bidders) }}</td>
<td>{{ item.warehouse ?? 'N/A' }}</td>
</tr>
<tr v-if="!submittedBatchRows.length">
<td colspan="6" class="empty-cell">No submitted bids found.</td>
</tr>
</tbody>
</table>
</div>
</el-tab-pane>

<el-tab-pane name="my_bids">
<template #label>
<span class="table-head-label"><em class="icon ni ni-tranx mr-1"></em>My Bids</span>
</template>
<div class="table-responsive">
<table class="table table-sm table-middle mb-0 bidding-table">
<thead>
<tr>
<th><span class="table-head-label"><em class="icon ni ni-hash"></em>Batch</span></th>
<th><span class="table-head-label"><em class="icon ni ni-growth"></em>Commodity</span></th>
<th><span class="table-head-label"><em class="icon ni ni-coins"></em>Ask / Bid</span></th>
<th><span class="table-head-label"><em class="icon ni ni-layers"></em>Bid Qty</span></th>
<th><span class="table-head-label"><em class="icon ni ni-flag"></em>Status</span></th>
<th><span class="table-head-label"><em class="icon ni ni-building"></em>Warehouse</span></th>
<th><span class="table-head-label"><em class="icon ni ni-calendar"></em>Placed On</span></th>
</tr>
</thead>
<tbody>
<tr v-for="item in myBids" :key="item.id" class="clickable-row" @click="openBatchDetails(item.batch_id)">
<td>{{ item.batch_code ?? `#${item.batch_id}` }}</td>
<td class="text-capitalize">{{ item.commodity_name ?? 'N/A' }} - {{ item.grade ?? 'N/A' }}</td>
<td>UGX {{ formatQuantity(item.ask_price) }} / UGX {{ formatQuantity(item.bid_price) }}</td>
<td>{{ formatQuantity(item.bid_quantity) }}</td>
<td><span :class="statusClass(item.status)" class="text-capitalize">{{ item.status ?? 'pending' }}</span></td>
<td>{{ item.warehouse ?? 'N/A' }}</td>
<td>{{ formatDateTime(item.created_at) }}</td>
</tr>
<tr v-if="!myBids.length">
<td colspan="7" class="empty-cell">No bids found.</td>
</tr>
</tbody>
</table>
</div>
</el-tab-pane>
</el-tabs>
</div>
</section>
</div>
</CooperativeLayout>
</template>

<style scoped>
.bidding-page {
display: grid;
gap: 1rem;
}

.table-card {
border-radius: 14px;
overflow: hidden;
}

.table-top {
display: flex;
align-items: center;
justify-content: space-between;
gap: 0.75rem;
border-bottom: 1px solid #e2e8f0;
}

.table-heading {
display: grid;
gap: 0.2rem;
}

.title-line {
display: flex;
align-items: center;
gap: 0.35rem;
}

.title-icon {
color: #0f766e;
}

.subtitle {
display: inline-flex;
align-items: center;
gap: 0.3rem;
font-size: 12px;
color: #64748b;
}

.subtitle-icon {
color: #0ea5e9;
}

.search-input {
width: 250px;
--el-input-height: 35px;
}

.search-input :deep(.el-input__wrapper) {
height: 35px !important;
min-height: 35px !important;
padding-top: 0 !important;
padding-bottom: 0 !important;
font-size: 12px;
}

.search-input :deep(.el-input__inner) {
height: 35px !important;
line-height: 35px !important;
}

.bidding-table thead th {
font-size: 0.72rem;
text-transform: uppercase;
letter-spacing: 0.04em;
color: #64748b;
background: #f8fafc;
}

.table-head-label {
display: inline-flex;
align-items: center;
gap: 0.35rem;
}

.bidding-table tbody td {
vertical-align: middle;
}

.bidders-cell {
display: flex;
align-items: center;
flex-wrap: wrap;
gap: 0.35rem;
}

.bidder-chip {
display: inline-flex;
align-items: center;
max-width: 155px;
padding: 0.14rem 0.5rem;
border-radius: 999px;
border: 1px solid #dbe3ef;
color: #334155;
background: #ffffff;
font-size: 0.71rem;
line-height: 1.25;
white-space: nowrap;
overflow: hidden;
text-overflow: ellipsis;
}

.bidders-more {
font-size: 0.7rem;
color: #64748b;
}

.bidders-empty {
font-size: 0.72rem;
color: #94a3b8;
}

.clickable-row {
cursor: pointer;
}

.empty-cell {
text-align: center;
color: #64748b;
padding: 1rem 0.75rem !important;
}

.table-actions {
margin-left: auto;
display: flex;
align-items: center;
gap: 0.5rem;
}

.bidding-main-tabs :deep(.el-tabs__header) {
margin: 0;
}

.bidding-main-tabs :deep(.el-tabs__nav-wrap) {
padding: 0 14px;
}

.bidding-main-tabs :deep(.el-tabs__nav-wrap::after) {
height: 0;
}

.bidding-main-tabs :deep(.el-tabs__item) {
height: 40px;
line-height: 40px;
display: inline-flex;
align-items: center;
}

.bidding-main-tabs :deep(.el-tabs__content) {
border-top: 0;
}

.bidding-table thead th {
border-top: 0;
}

.tab-empty-space {
min-height: 260px;
}

@media (max-width: 768px) {
.table-top {
flex-direction: column;
align-items: flex-start;
}

.table-actions {
width: 100%;
margin-left: 0;
flex-direction: column;
align-items: stretch;
}

.search-input {
width: 100%;
margin-left: 0;
}
}
</style>
