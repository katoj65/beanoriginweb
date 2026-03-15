<script setup>
import { computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';
import { Back } from '@element-plus/icons-vue';

const page = usePage();

const batch = computed(() => page.props.batch ?? {});
const owner = computed(() => page.props.owner ?? {});

const statusClass = computed(() => {
const status = String(batch.value?.status ?? '').toLowerCase();
if (status === 'created') return 'badge bg-warning-subtle text-warning text-capitalize';
if (status === 'listed') return 'badge bg-success-subtle text-success text-capitalize';
if (status === 'sold') return 'badge bg-info-subtle text-info text-capitalize';
return 'badge bg-light text-dark text-capitalize';
});

const marketTypeClass = computed(() => {
const marketType = String(batch.value?.market_type ?? '').toLowerCase();
if (marketType === 'save') return 'badge bg-secondary-subtle text-secondary text-capitalize';
if (marketType === 'marketplace') return 'badge bg-primary-subtle text-primary text-capitalize';
if (marketType === 'bidding') return 'badge bg-info-subtle text-info text-capitalize';
return 'badge bg-light text-dark text-capitalize';
});

const formatNumber = (value) => Number(value ?? 0).toLocaleString(undefined, { maximumFractionDigits: 2 });

const formatPrice = (value) => {
if (value === null || value === undefined || value === '') return 'N/A';
const amount = Number(value);
if (Number.isNaN(amount)) return value;
return amount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
};

const formatDate = (value) => {
if (!value) return 'N/A';
const date = new Date(value);
if (Number.isNaN(date.getTime())) return value;
return date.toLocaleString();
};

const goBack = () => {
router.get(route('market.batchSaved'));
};
</script>

<template>
<CooperativeLayout>
<div class="container">
<div class="card details-card">
<div class="card-inner border-bottom details-head">
<div>
<h6 class="title mb-1"><em class="icon ni ni-folder-check mr-1"></em>Saved Batch Details</h6>
<p class="sub-text mb-0">View full details of your saved commodity batch.</p>
</div>
<el-button plain :icon="Back" @click="goBack">Back</el-button>
</div>

<div class="card-inner">
<div class="details-grid">
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-hash mr-1"></em>Batch Code</span>
<strong>{{ batch.batch_code ?? 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-growth mr-1"></em>Commodity</span>
<strong class="text-capitalize">{{ batch.commodity_name ?? 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-box-view mr-1"></em>Commodity Type</span>
<strong class="text-capitalize">{{ batch.commodity_type ?? 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-award mr-1"></em>Grade</span>
<strong>{{ batch.grade ?? 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-package mr-1"></em>Weight</span>
<strong>{{ formatNumber(batch.weight) }} kg</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-layers mr-1"></em>Quantity</span>
<strong>{{ formatNumber(batch.quantity) }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-coins mr-1"></em>Price</span>
<strong>UGX {{ formatPrice(batch.price) }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-home-fill mr-1"></em>Warehouse</span>
<strong>{{ batch.warehouse ?? 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-flag mr-1"></em>Status</span>
<strong><span :class="statusClass">{{ batch.status ?? 'N/A' }}</span></strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-send mr-1"></em>Publish To</span>
<strong><span :class="marketTypeClass">{{ batch.market_type ?? 'N/A' }}</span></strong>
</div>
<div class="detail-item detail-span-2">
<span class="sub-text"><em class="icon ni ni-calendar mr-1"></em>Created At</span>
<strong>{{ formatDate(batch.created_at) }}</strong>
</div>

<div class="detail-section detail-item-full">
<h6 class="detail-section-title"><em class="icon ni ni-user-circle mr-1"></em>Owner Information</h6>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-user mr-1"></em>Name</span>
<strong class="text-capitalize">{{ owner.name ?? 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-mail mr-1"></em>Email</span>
<strong>{{ owner.email ?? 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-call mr-1"></em>Phone</span>
<strong>{{ owner.tel ?? 'N/A' }}</strong>
</div>
<div class="detail-item detail-span-3">
<span class="sub-text"><em class="icon ni ni-map-pin mr-1"></em>Address</span>
<strong class="text-capitalize">{{ owner.address ?? 'N/A' }}</strong>
</div>
</div>
</div>
</div>
</div>
</CooperativeLayout>
</template>

<style scoped>
.details-card {
border-radius: 12px;
}

.details-head {
display: flex;
justify-content: space-between;
align-items: center;
gap: 12px;
}

.details-grid {
display: grid;
grid-template-columns: repeat(3, minmax(0, 1fr));
gap: 12px;
}

.detail-item {
background: #f8fafc;
border-radius: 10px;
padding: 12px;
display: flex;
flex-direction: column;
gap: 4px;
}

.detail-item-full {
grid-column: 1 / -1;
}

.detail-span-2 {
grid-column: span 2;
}

.detail-span-3 {
grid-column: span 3;
}

.detail-section {
border-top: 1px solid #dbe3ee;
padding-top: 10px;
margin-top: 2px;
}

.detail-section-title {
margin: 0;
}

@media (max-width: 991px) {
.details-head {
flex-direction: column;
align-items: flex-start;
}

.details-grid {
grid-template-columns: repeat(2, minmax(0, 1fr));
}

.detail-span-2,
.detail-span-3 {
grid-column: span 2;
}
}

@media (max-width: 767px) {
.details-grid {
grid-template-columns: 1fr;
}

.detail-span-2,
.detail-span-3 {
grid-column: span 1;
}
}
</style>
