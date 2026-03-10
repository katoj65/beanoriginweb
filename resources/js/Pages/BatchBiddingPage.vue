<script setup>
import { computed } from 'vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { ElNotification } from 'element-plus';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';

const page = usePage();
const batch = computed(() => page.props.batch ?? {});
const ownerProfile = computed(() => page.props.owner_profile ?? {});
const batchCommodities = computed(() => page.props.batch_commodities ?? []);
const commodityFarmMap = computed(() => page.props.commodity_farm_map ?? []);
const hasBidOnBatch = computed(() => Boolean(page.props.has_bid_on_batch ?? false));
const myBidOffer = computed(() => page.props.my_bid_offer ?? null);
// Read bidder offers from backend and keep descending price order for table rank.
const bidOffers = computed(() => {
const source = page.props.bid_offers ?? [];
const offers = Array.isArray(source) ? source : [];
return [...offers].sort((a, b) => Number(b.bid_price ?? 0) - Number(a.bid_price ?? 0));
});
const topBidPrice = computed(() => {
if (!bidOffers.value.length) return null;
return Number(bidOffers.value[0]?.bid_price ?? 0);
});
const bidForm = useForm({
bid_quantity: Number(page.props.my_bid_offer?.bid_quantity ?? 1),
bid_price: page.props.my_bid_offer?.bid_price ?? null,
bid_notes: page.props.my_bid_offer?.bid_notes ?? '',
});
const commodityRows = computed(() => {
const farmsByCommodity = new Map(
commodityFarmMap.value.map((item) => [item.id, item.farms ?? []]),
);

return batchCommodities.value.map((commodity) => {
const farms = farmsByCommodity.get(commodity.id) ?? [];
return {
...commodity,
farm_names: farms.length ? farms.map((farm) => farm.farm_name).filter(Boolean).join(', ') : 'N/A',
farm_locations: farms.length ? farms.map((farm) => farm.location).filter(Boolean).join(', ') : 'N/A',
};
});
});

const formatPrice = (value) => {
if (value === null || value === undefined || value === '') return 'N/A';
const amount = Number(value);
if (Number.isNaN(amount)) return String(value);
return amount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
};

const formatDateTime = (value) => {
if (!value) return 'N/A';
const date = new Date(value);
if (Number.isNaN(date.getTime())) return String(value);
return date.toLocaleString();
};

const formatDate = (value) => {
if (!value) return 'N/A';
const date = new Date(value);
if (Number.isNaN(date.getTime())) return String(value);
return date.toLocaleDateString();
};

const statusIcon = (status) => {
const key = String(status ?? '').toLowerCase();
if (key === 'complete' || key === 'completed' || key === 'approved') return 'ni-check-circle-fill';
if (key === 'failed' || key === 'rejected' || key === 'banned') return 'ni-cross-circle-fill';
return 'ni-clock-fill';
};

const statusPillClass = (status) => {
const key = String(status ?? '').toLowerCase();
if (key === 'complete' || key === 'completed' || key === 'approved') return 'status-pill is-complete';
if (key === 'failed' || key === 'rejected' || key === 'banned') return 'status-pill is-failed';
return 'status-pill is-pending';
};

const submitBid = () => {
const batchId = Number(batch.value?.id ?? 0);
if (!batchId) return;

bidForm.post(route('market.batchBidding.store', { id: batchId }), {
preserveScroll: true,
onSuccess: () => {
ElNotification({
title: 'Success',
message: hasBidOnBatch.value ? 'Counter offer submitted successfully.' : 'Bidding request submitted successfully.',
type: 'success',
customClass: 'small-success-notification',
});
bidForm.reset('bid_price', 'bid_notes');
bidForm.bid_quantity = 1;
},
});
};

const handleBidAction = (command) => {
if (command !== 'withdraw') return;

const batchId = Number(batch.value?.id ?? 0);
if (!batchId || !hasBidOnBatch.value) return;

router.delete(route('market.batchBidding.withdraw', { id: batchId }), {
preserveScroll: true,
onSuccess: () => {
ElNotification({
title: 'Success',
message: 'Your bid was withdrawn successfully.',
type: 'success',
customClass: 'small-success-notification',
});
},
onError: (errors) => {
ElNotification({
title: 'Error',
message: errors?.bid_offer || 'Unable to withdraw bid at the moment.',
type: 'error',
});
},
});
};
</script>

<template>
<CooperativeLayout>

<div class="container py-0">
<div class="card card-bordered buy-batch-card">
<div class="card-inner border-bottom page-head">
<div>
<h6 class="title mb-1"><em class="icon ni ni-gavel mr-1"></em>Batch Bidding</h6>
<p class="sub-text mb-0">Review bidding lot details and submit your offer.</p>
</div>
</div>

<div class="card-inner">
<div class="details-grid">
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-tag mr-1"></em>Batch Code</span>
<strong>{{ batch.batch_code ?? 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-growth mr-1"></em>Commodity</span>
<strong>{{ batch.commodity_name ?? 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-box-view mr-1"></em>Type</span>
<strong>{{ batch.commodity_type ?? 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-award mr-1"></em>Grade</span>
<strong>{{ batch.grade ?? 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-package mr-1"></em>Weight</span>
<strong>{{ batch.weight ?? 'N/A' }} kg</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-layers mr-1"></em>Quantity</span>
<strong>{{ batch.quantity ?? 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-coins mr-1"></em>Ask Price</span>
<strong>UGX {{ formatPrice(batch.price) }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-drop mr-1"></em>Moisture</span>
<strong>{{ batch.moisture ?? 'N/A' }}%</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-home-fill mr-1"></em>Warehouse</span>
<strong>{{ batch.warehouse ?? 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-calendar mr-1"></em>Created At</span>
<strong>{{ formatDateTime(batch.created_at) }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-user mr-1"></em>Owner</span>
<strong>{{ batch.owner?.name || 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-call mr-1"></em>Owner Phone</span>
<strong>{{ ownerProfile.tel ?? 'N/A' }}</strong>
</div>
<div class="detail-item detail-item-double">
<span class="sub-text"><em class="icon ni ni-map-pin mr-1"></em>Owner Address</span>
<strong>{{ ownerProfile.address ?? 'N/A' }}</strong>
</div>
</div>

<div class="commodities-section mt-3">
<div class="section-head mb-2">
<h6 class="title mb-0">Batch Harvest Details</h6>
<span class="sub-text">{{ commodityRows.length }} item(s)</span>
</div>
<div v-if="commodityRows.length" class="commodity-table-wrap">
<el-table :data="commodityRows" size="small" border stripe class="commodity-table">
<el-table-column prop="commodity_name" min-width="180">
<template #header>
<span class="table-head-label"><em class="icon ni ni-tag"></em>Commodity</span>
</template>
</el-table-column>
<el-table-column prop="commodity_type" min-width="120">
<template #header>
<span class="table-head-label"><em class="icon ni ni-layers"></em>Type</span>
</template>
</el-table-column>
<el-table-column prop="grade" min-width="100">
<template #header>
<span class="table-head-label"><em class="icon ni ni-award"></em>Grade</span>
</template>
</el-table-column>
<el-table-column prop="weight" min-width="100">
<template #header>
<span class="table-head-label"><em class="icon ni ni-package"></em>Weight</span>
</template>
</el-table-column>
<el-table-column prop="harvest_date" min-width="140">
<template #header>
<span class="table-head-label"><em class="icon ni ni-calendar"></em>Harvest Date</span>
</template>
<template #default="{ row }">
{{ formatDate(row.harvest_date) }}
</template>
</el-table-column>
<el-table-column prop="farm_names" min-width="220" show-overflow-tooltip>
<template #header>
<span class="table-head-label"><em class="icon ni ni-home-fill"></em>Farm</span>
</template>
</el-table-column>
<el-table-column prop="farm_locations" min-width="220" show-overflow-tooltip>
<template #header>
<span class="table-head-label"><em class="icon ni ni-map-pin"></em>Farm Location</span>
</template>
</el-table-column>
</el-table>
</div>
<div v-else class="sub-text">No commodities linked to this batch.</div>
</div>








<div class="bidding-form-section mt-3">
<div class="section-head mb-2">
<h6 class="title mb-0">{{ hasBidOnBatch ? 'Counter Offer' : 'Place Your Bid' }}</h6>
<span class="sub-text">
<em v-if="hasBidOnBatch" class="icon ni ni-check-circle mr-1 text-success"></em>
{{ hasBidOnBatch ? 'You already have a bid on this batch. Submit a counter offer below.' : 'Submit your bid request for this batch.' }}
</span>
</div>

<div class="offer-summary-grid mb-3">
<div class="offer-summary-card bids-table-card">
<div class="offer-card-head">
<p class="offer-summary-title mb-0"><em class="icon ni ni-users mr-1"></em>Bidder Offers</p>
<div class="offer-head-meta">
<span class="sub-text"><em class="icon ni ni-sort-down mr-1"></em>Price Desc</span>
<span class="sub-text"><em class="icon ni ni-layers mr-1"></em>{{ bidOffers.length }} Offers</span>
<span v-if="topBidPrice !== null" class="sub-text"><em class="icon ni ni-coins mr-1"></em>Top: UGX {{ formatPrice(topBidPrice) }}</span>
<div v-if="hasBidOnBatch" class="offer-head-actions">
<el-dropdown trigger="click" @command="handleBidAction">
<el-button plain size="small" class="offer-action-btn">
<em class="icon ni ni-more-v mr-1"></em>Bid Actions
<em class="icon ni ni-chevron-down ml-1"></em>
</el-button>
<template #dropdown>
<el-dropdown-menu>
<el-dropdown-item command="withdraw">
<em class="icon ni ni-cross-circle mr-1"></em>Withdraw from bidding
</el-dropdown-item>
</el-dropdown-menu>
</template>
</el-dropdown>
</div>
</div>
</div>
<!-- Plain Element table for all bidder offers (no vertical lines, full width). -->
<el-table
:data="bidOffers"
size="small"
fit
table-layout="auto"
empty-text="No bidder offers yet."
class="offer-table plain-offer-table"
>
<el-table-column width="86" label="Rank" align="center">
<template #header>
<span class="table-head-label"><em class="icon ni ni-hash"></em>Rank</span>
</template>
<template #default="{ $index }"><span class="rank-text">#{{ $index + 1 }}<small v-if="$index === 0">Top</small></span></template>
</el-table-column>
<el-table-column prop="buyer_name" min-width="180" label="Bidder">
<template #header>
<span class="table-head-label"><em class="icon ni ni-user"></em>Bidder</span>
</template>
<template #default="{ row }">
<span class="buyer-name"><em class="icon ni ni-user-circle mr-1"></em>{{ row.buyer_name || 'Buyer' }}</span>
<span v-if="row.is_my_offer" class="you-tag">You</span>
</template>
</el-table-column>
<el-table-column prop="bid_quantity" width="120" label="Qty" align="right">
<template #header>
<span class="table-head-label"><em class="icon ni ni-layers"></em>Qty</span>
</template>
<template #default="{ row }">{{ Number(row.bid_quantity ?? 0).toLocaleString() }}</template>
</el-table-column>
<el-table-column prop="bid_price" width="170" label="Offer Price" align="right">
<template #header>
<span class="table-head-label"><em class="icon ni ni-coins"></em>Offer Price</span>
</template>
<template #default="{ row }"><span class="offer-price-text">UGX {{ formatPrice(row.bid_price) }}</span></template>
</el-table-column>
<el-table-column prop="status" width="130" label="Status" align="center">
<template #header>
<span class="table-head-label"><em class="icon ni ni-flag"></em>Status</span>
</template>
<template #default="{ row }">
<span :class="statusPillClass(row.status)" class="text-capitalize"><em class="icon ni mr-1" :class="statusIcon(row.status)"></em>{{ row.status ?? 'pending' }}</span>
</template>
</el-table-column>
<el-table-column prop="created_at" min-width="180" label="Placed On">
<template #header>
<span class="table-head-label"><em class="icon ni ni-calendar"></em>Placed On</span>
</template>
<template #default="{ row }"><span class="placed-on-text">{{ formatDateTime(row.created_at) }}</span></template>
</el-table-column>
</el-table>
</div>
</div>

<div>
<form class="bidding-form bidding-form-modern" @submit.prevent="submitBid">
<div class="bid-layout-twocol">
<div class="bid-form-col">
<div class="bid-grid-form">
<div class="bid-control">
<label class="bid-control-label">{{ hasBidOnBatch ? 'Counter Quantity' : 'Bid Quantity' }}</label>
<el-input-number v-model="bidForm.bid_quantity" :min="1" :step="1" class="w-100" />
<small v-if="bidForm.errors.bid_quantity" class="text-danger d-block mt-1">{{ bidForm.errors.bid_quantity }}</small>
</div>
<div class="bid-control">
<label class="bid-control-label">{{ hasBidOnBatch ? 'Counter Price (UGX)' : 'Bid Price (UGX)' }}</label>
<el-input-number v-model="bidForm.bid_price" :min="0" :step="1" class="w-100" />
<small v-if="bidForm.errors.bid_price" class="text-danger d-block mt-1">{{ bidForm.errors.bid_price }}</small>
</div>
<div class="bid-control bid-control-full mt-1">
<label class="bid-control-label">Notes</label>
<el-input
v-model="bidForm.bid_notes"
type="textarea"
:autosize="{ minRows: 3, maxRows: 6 }"
placeholder="Add terms for your bid or counter offer..."
/>
<small v-if="bidForm.errors.bid_notes" class="text-danger d-block mt-1">{{ bidForm.errors.bid_notes }}</small>
</div>
</div>
<div class="bid-submit-row-modern">
<el-button type="primary" native-type="submit" :loading="bidForm.processing" class="w-100 w-sm-auto">
{{ hasBidOnBatch ? 'Submit Counter Offer' : 'Submit Bid' }}
</el-button>
</div>
</div>

<aside class="bid-side-col">
<div class="ask-box">
<div class="ask-box-head">
<span class="ask-box-label"><em class="icon ni ni-coin-alt"></em>Current Ask Price</span>
<em class="icon ni ni-trend-up ask-box-trend"></em>
</div>
<strong class="ask-box-price"><em class="icon ni ni-coins"></em>UGX {{ formatPrice(batch.price) }}</strong>
</div>
<div class="instruction-box">
<p class="instruction-title mb-2"><em class="icon ni ni-info-fill"></em>Bidding Instructions</p>
<ul class="instruction-list mb-0">
<li><em class="icon ni ni-arrow-right-circle"></em><span>Enter the quantity you want to bid for.</span></li>
<li><em class="icon ni ni-arrow-right-circle"></em><span>Provide your price per unit in UGX.</span></li>
<li><em class="icon ni ni-arrow-right-circle"></em><span>Review existing offers before sending your counter offer.</span></li>
<li><em class="icon ni ni-arrow-right-circle"></em><span>Submit only after confirming all values.</span></li>
</ul>
</div>
</aside>
</div>
</form>
</div>

</div>








</div>
</div>
</div>
</CooperativeLayout>
</template>

<style scoped>
.buy-batch-card {
border-radius: 12px;
border: 1px solid #d6e0ec;
box-shadow: 0 6px 18px rgba(15, 23, 42, 0.06);
overflow: hidden;
}

.page-head {
display: flex;
justify-content: space-between;
align-items: center;
gap: 12px;
flex-wrap: wrap;
}

.details-grid {
display: grid;
grid-template-columns: repeat(3, minmax(0, 1fr));
gap: 12px;
}

.detail-item {
background: #f8fafc;
border-radius: 10px;
padding: 10px 12px;
display: flex;
flex-direction: column;
gap: 4px;
}

.detail-item-double {
grid-column: span 3;
}

.commodities-section {
border-top: 1px solid #e5e9f2;
padding-top: 12px;
}

.bidding-form-section {
border-top: 1px solid #e5e9f2;
padding-top: 12px;
}

.bidding-form {
background: transparent;
border: 0;
border-radius: 10px;
padding: 0;
}

.bidding-form-modern {
background: #fcfdff;
border: 1px solid #dbe5f2;
border-radius: 14px;
padding: 16px;
box-shadow: 0 8px 22px rgba(15, 23, 42, 0.04);
}

.bid-layout-twocol {
display: grid;
grid-template-columns: repeat(2, minmax(0, 1fr));
gap: 18px;
}

.bid-form-col,
.bid-side-col {
display: flex;
flex-direction: column;
gap: 14px;
}

.bid-side-col {
border-left: 1px solid #e5ecf6;
padding-left: 18px;
}

.bid-grid-form {
display: grid;
grid-template-columns: repeat(2, minmax(0, 1fr));
gap: 12px;
}

.bid-control {
background: transparent;
border: 0;
padding: 0;
}

.bid-control-label {
display: block;
margin-bottom: 4px;
font-size: 11px;
font-weight: 600;
letter-spacing: 0.06em;
text-transform: uppercase;
color: #7187a6;
}

.bid-control-full {
grid-column: 1 / -1;
}

:deep(.bid-control .el-input-number) {
width: 100%;
border-radius: 0;
overflow: hidden;
}

:deep(.bid-control .el-input-number .el-input__wrapper) {
border: 0;
box-shadow: none;
background: #f3f6fb;
border-radius: 0;
padding-inline: 10px;
}

:deep(.bid-control .el-input-number__decrease),
:deep(.bid-control .el-input-number__increase) {
background: #eef3fa;
border-color: transparent;
}

:deep(.bid-control .el-textarea__inner) {
border: 1px solid #d6e2f1;
border-radius: 0;
box-shadow: none;
background: #ffffff;
padding: 10px 12px;
}

.bid-submit-row-modern {
display: flex;
justify-content: flex-start;
padding-top: 2px;
}

.bidding-form-section .text-danger {
font-size: 13px;
font-weight: 500;
}

.bid-submit-row-modern .el-button {
min-width: 140px;
}

.ask-box,
.instruction-box {
border: 1px solid #dfe8f4;
border-radius: 12px;
padding: 12px;
background: #fff;
}

.ask-box {
background: linear-gradient(180deg, #f8fbff 0%, #ffffff 100%);
}

.ask-box-head {
display: flex;
align-items: center;
justify-content: space-between;
gap: 10px;
margin-bottom: 4px;
}

.ask-box-label {
display: inline-flex;
align-items: center;
gap: 6px;
font-size: 11px;
font-weight: 600;
letter-spacing: 0.06em;
text-transform: uppercase;
color: #8094ae;
margin-bottom: 0;
}

.ask-box-price {
display: inline-flex;
align-items: center;
gap: 8px;
font-size: 18px;
line-height: 1.2;
color: #1f2b3a;
font-weight: 700;
}

.ask-box-trend {
color: #7187a6;
font-size: 15px;
}

.instruction-title {
display: inline-flex;
align-items: center;
gap: 6px;
font-size: 12px;
font-weight: 700;
letter-spacing: 0.04em;
text-transform: uppercase;
color: #526484;
}

.instruction-list {
padding-left: 16px;
color: #6b7f99;
line-height: 1.45;
}

.instruction-list li {
display: flex;
align-items: flex-start;
gap: 6px;
}

.instruction-list li .icon {
margin-top: 2px;
font-size: 12px;
color: #7187a6;
}

.instruction-list li + li {
margin-top: 6px;
}

.section-head {
display: flex;
align-items: center;
justify-content: space-between;
gap: 12px;
}

.offer-summary-grid {
display: grid;
grid-template-columns: 1fr;
gap: 12px;
}

.offer-summary-card {
border: 1px solid #e5e7eb;
border-radius: 12px;
background: #fff;
padding: 0;
box-shadow: none;
}

.offer-summary-title {
display: inline-flex;
align-items: center;
gap: 6px;
font-size: 12px;
font-weight: 700;
letter-spacing: 0.04em;
text-transform: uppercase;
color: #526484;
}

.offer-kpi-grid {
display: grid;
grid-template-columns: repeat(2, minmax(0, 1fr));
gap: 10px;
}

.offer-kpi {
display: flex;
flex-direction: column;
gap: 5px;
padding: 10px;
border: 1px solid #e6edf6;
border-radius: 10px;
background: #f9fbfe;
}

.offer-table {
width: 100%;
}

.bids-table-card {
padding: 0;
overflow: hidden;
}

.offer-card-head {
display: flex;
align-items: center;
justify-content: space-between;
gap: 10px;
padding: 12px 14px;
border-bottom: 1px solid #e5e7eb;
}

.offer-head-meta {
display: inline-flex;
align-items: center;
gap: 12px;
flex-wrap: wrap;
}

.offer-head-actions {
display: inline-flex;
align-items: center;
}

.offer-action-btn {
min-height: 30px;
}

.offer-note {
padding: 8px 10px;
border-radius: 8px;
background: #f8fafd;
color: #5b7089;
font-size: 12px;
}

.offer-empty {
padding: 12px;
border-radius: 10px;
border: 1px dashed #d8e2ef;
background: #fbfdff;
color: #6b7f99;
font-size: 12px;
}

.buyer-name {
display: inline-flex;
align-items: center;
font-weight: 600;
color: inherit;
}

.you-tag {
display: inline-flex;
align-items: center;
margin-left: 8px;
padding: 1px 7px;
border: 1px solid #d1d5db;
border-radius: 999px;
font-size: 10px;
font-weight: 600;
line-height: 1.4;
}

.status-pill {
display: inline-flex;
align-items: center;
justify-content: center;
padding: 2px 8px;
border: 1px solid #d1d5db;
border-radius: 999px;
font-size: 11px;
font-weight: 600;
line-height: 1.4;
background: #fff;
}

.status-pill .icon {
font-size: 11px;
}

.status-pill.is-complete {
border-color: #cbd5e1;
}

.status-pill.is-failed {
border-color: #d1d5db;
}

.status-pill.is-pending {
border-color: #d1d5db;
line-height: 1.4;
}

.rank-text {
display: inline-flex;
align-items: baseline;
gap: 4px;
font-weight: 600;
}

.rank-text small {
font-size: 10px;
color: #6b7280;
text-transform: uppercase;
}

.offer-price-text {
font-variant-numeric: tabular-nums;
font-weight: 600;
}

.placed-on-text {
font-size: 13px;
white-space: nowrap;
}

:deep(.plain-offer-table .el-table__header th.el-table__cell) {
background: #fff;
color: #374151;
font-weight: 600;
font-size: 13px;
border-right: 0;
border-bottom: 1px solid #e5e7eb;
}

:deep(.plain-offer-table .el-table__row td.el-table__cell) {
font-size: 14px;
padding-top: 10px;
padding-bottom: 10px;
border-right: 0;
border-bottom: 1px solid #f0f2f5;
}

:deep(.plain-offer-table .el-table__row:hover td.el-table__cell) {
background: #fff !important;
}

:deep(.plain-offer-table .el-table__inner-wrapper::before) {
height: 0;
}

:deep(.plain-offer-table .el-table__empty-block) {
min-height: 120px;
}

.commodity-table-wrap {
border: 1px solid #cfd9e6;
border-radius: 12px;
overflow: hidden;
background: #fff;
box-shadow: 0 2px 10px rgba(15, 23, 42, 0.04);
}

.commodity-table {
width: 100%;
}

:deep(.commodity-table .el-table__header th.el-table__cell) {
background: #f8fafc;
color: #526484;
font-weight: 600;
}

:deep(.commodity-table .el-table__body td.el-table__cell) {
vertical-align: top;
}

.table-head-label {
display: inline-flex;
align-items: center;
gap: 6px;
}

:deep(.small-success-notification .el-notification__title) {
font-size: 13px;
}

@media (max-width: 768px) {
.details-grid,
.bid-layout-twocol,
.bid-grid-form,
.offer-summary-grid,
.offer-kpi-grid {
grid-template-columns: 1fr;
}

.detail-item-double,
.bid-control-full {
grid-column: span 1;
}

.bid-submit-row-modern .el-button {
width: 100%;
}

.bid-side-col {
border-left: 0;
padding-left: 0;
}
}
</style>
