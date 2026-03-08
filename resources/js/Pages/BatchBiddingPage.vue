<script setup>
import { computed } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';

const page = usePage();
const batch = computed(() => page.props.batch ?? {});
const isReservedByUser = computed(() => Boolean(page.props.is_reserved_by_user ?? false));
const ownerProfile = computed(() => page.props.owner_profile ?? {});
const batchCommodities = computed(() => page.props.batch_commodities ?? []);
const commodityFarmMap = computed(() => page.props.commodity_farm_map ?? []);
const bidForm = useForm({
bid_quantity: 1,
bid_price: null,
bid_notes: '',
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

const statusTagType = (value) => {
const status = String(value ?? '').trim().toLowerCase();
if (status === 'listed' || status === 'tokenized' || status === 'tokenised') return 'success';
if (status === 'verified') return 'info';
if (status === 'sold' || status === 'closed') return 'danger';
return 'warning';
};

const submitBid = () => {
const batchId = Number(batch.value?.id ?? 0);
if (!batchId) return;

bidForm.post(route('market.batchBidding.store', { id: batchId }), {
preserveScroll: true,
onSuccess: () => {
bidForm.reset('bid_price', 'bid_notes');
bidForm.bid_quantity = 1;
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
<div class="details-table-wrap">
<table class="table table-sm table-middle mb-0 details-table">
<tbody>
<tr>
<th><em class="icon ni ni-tag mr-1"></em>Batch Code</th>
<td>{{ batch.batch_code ?? 'N/A' }}</td>
<th><em class="icon ni ni-growth mr-1"></em>Commodity</th>
<td>{{ batch.commodity_name ?? 'N/A' }}</td>
</tr>
<tr>
<th><em class="icon ni ni-box-view mr-1"></em>Type</th>
<td>{{ batch.commodity_type ?? 'N/A' }}</td>
<th><em class="icon ni ni-award mr-1"></em>Grade</th>
<td>{{ batch.grade ?? 'N/A' }}</td>
</tr>
<tr>
<th><em class="icon ni ni-package mr-1"></em>Weight</th>
<td>{{ batch.weight ?? 'N/A' }} kg</td>
<th><em class="icon ni ni-layers mr-1"></em>Quantity</th>
<td>{{ batch.quantity ?? 'N/A' }}</td>
</tr>
<tr>
<th><em class="icon ni ni-coins mr-1"></em>Price</th>
<td>UGX {{ formatPrice(batch.price) }}</td>
<th><em class="icon ni ni-drop mr-1"></em>Moisture</th>
<td>{{ batch.moisture ?? 'N/A' }}%</td>
</tr>
<tr>
<th><em class="icon ni ni-home-fill mr-1"></em>Warehouse</th>
<td>{{ batch.warehouse ?? 'N/A' }}</td>
<th><em class="icon ni ni-flag mr-1"></em>Status</th>
<td><el-tag size="small" :type="statusTagType(batch.status)" class="text-capitalize">{{ batch.status ?? 'N/A' }}</el-tag></td>
</tr>
<tr>
<th><em class="icon ni ni-calendar mr-1"></em>Created At</th>
<td>{{ formatDateTime(batch.created_at) }}</td>
<th><em class="icon ni ni-user mr-1"></em>Owner</th>
<td>{{ batch.owner?.name || 'N/A' }}</td>
</tr>
<tr>
<th><em class="icon ni ni-call mr-1"></em>Owner Phone</th>
<td>{{ ownerProfile.tel ?? 'N/A' }}</td>
<th><em class="icon ni ni-map-pin mr-1"></em>Owner Address</th>
<td>{{ ownerProfile.address ?? 'N/A' }}</td>
</tr>
<tr>
<th><em class="icon ni ni-check-circle mr-1"></em>Requested By You</th>
<td colspan="3">
<el-tag size="small" :type="isReservedByUser ? 'success' : 'info'">{{ isReservedByUser ? 'Yes' : 'No' }}</el-tag>
</td>
</tr>
</tbody>
</table>
</div>

<div class="bidding-form-section mt-3">
<div class="section-head mb-2">
<h6 class="title mb-0"><em class="icon ni ni-coin-alt mr-1"></em>Place Your Bid</h6>
<span class="sub-text">Submit your bid request for this batch.</span>
</div>
<form class="bidding-form" @submit.prevent="submitBid">
<table class="table table-sm table-middle mb-0 bidding-form-table">
<tbody>
<tr>
<th>Ask Price</th>
<td>UGX {{ formatPrice(batch.price) }}</td>
</tr>
<tr>
<th>Bid Quantity</th>
<td>
<el-input-number v-model="bidForm.bid_quantity" :min="1" :step="1" class="w-100" />
<small v-if="bidForm.errors.bid_quantity" class="text-danger d-block mt-1">{{ bidForm.errors.bid_quantity }}</small>
</td>
</tr>
<tr>
<th>Bid Price (UGX)</th>
<td>
<el-input-number v-model="bidForm.bid_price" :min="0" :step="1" class="w-100" />
<small v-if="bidForm.errors.bid_price" class="text-danger d-block mt-1">{{ bidForm.errors.bid_price }}</small>
</td>
</tr>
<tr>
<th>Notes</th>
<td>
<el-input v-model="bidForm.bid_notes" type="textarea" :rows="3" placeholder="Any note for your bid..." />
<small v-if="bidForm.errors.bid_notes" class="text-danger d-block mt-1">{{ bidForm.errors.bid_notes }}</small>
</td>
</tr>
<tr>
<th>Action</th>
<td>
<el-button type="primary" native-type="submit" :loading="bidForm.processing">
Submit Bid
</el-button>
</td>
</tr>
</tbody>
</table>
</form>
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

.details-table-wrap {
border: 1px solid #d6e0ec;
border-radius: 10px;
overflow: hidden;
}

.details-table th {
width: 18%;
background: #f8fafc;
font-weight: 600;
color: #364a63;
white-space: nowrap;
}

.details-table td {
width: 32%;
color: #526484;
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
background: #f8fafc;
border: 1px solid #d6e0ec;
border-radius: 10px;
padding: 12px;
}

.bidding-form-table th {
width: 25%;
background: #f8fafc;
font-weight: 600;
color: #364a63;
}

.bidding-form-table td {
width: 75%;
background: #fff;
}

.section-head {
display: flex;
align-items: center;
justify-content: space-between;
gap: 12px;
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

@media (max-width: 768px) {
.details-table th,
.details-table td,
.bidding-form-table th,
.bidding-form-table td {
display: block;
width: 100%;
}
}
</style>
