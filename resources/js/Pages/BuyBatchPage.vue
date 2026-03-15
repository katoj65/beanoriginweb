<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';
import BuyButton from '@/Components/BuyButton.vue';
import ReserveButton from '@/Components/ReserveButton.vue';

const page = usePage();
const batch = computed(() => page.props.batch ?? {});
const isReservedByUser = computed(() => Boolean(page.props.is_reserved_by_user ?? false));
const ownerProfile = computed(() => page.props.owner_profile ?? {});

const toTitleCase = (value) => {
const text = String(value ?? '').trim().toLowerCase();
if (!text) return 'N/A';
return text.replace(/\b([a-z])/g, (match, letter) => letter.toUpperCase());
};

const ownerAddressDisplay = computed(() => {
const value = ownerProfile.value?.address;
return toTitleCase(value);
});
const batchCommodities = computed(() => page.props.batch_commodities ?? []);
const commodityFarmMap = computed(() => page.props.commodity_farm_map ?? []);
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
</script>

<template>
<CooperativeLayout>
<div class="container py-0">
<div class="card buy-batch-card">
<div class="card-inner border-bottom page-head">
<div>
<h6 class="title mb-1"><em class="icon ni ni-bag mr-1"></em>Buy Batch</h6>
<p class="sub-text mb-0">Review batch details and reserve this lot.</p>
</div>
<el-button-group>
<BuyButton :item="batch" />
</el-button-group>
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
<span class="sub-text"><em class="icon ni ni-coins mr-1"></em>Price</span>
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
<span class="sub-text"><em class="icon ni ni-flag mr-1"></em>Status</span>
<strong class="text-capitalize">{{ batch.status ?? 'N/A' }}</strong>
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
<strong class="text-capitalize">{{ ownerAddressDisplay }}</strong>
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

.detail-item-full {
grid-column: 1 / -1;
}

.detail-item-double {
grid-column: span 2;
}

.commodities-section {
border-top: 1px solid #e5e9f2;
padding-top: 12px;
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
.details-grid {
grid-template-columns: 1fr;
}

.detail-item-double {
grid-column: span 1;
}
}
</style>
