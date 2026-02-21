<script setup>
import { computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';

const page = usePage();
const batch = computed(() => page.props.batch?.data ?? page.props.batch ?? {});

const statusClass = computed(() => {
const status = (batch.value?.status ?? '').toLowerCase();
if (status === 'listed') return 'badge bg-success-subtle text-success';
if (status === 'sold') return 'badge bg-info-subtle text-info';
if (status === 'processing' || status === 'processed') return 'badge bg-warning-subtle text-warning';
return 'badge bg-light text-dark';
});

const chainClass = computed(() => (
batch.value?.is_on_chain
? 'badge bg-success-subtle text-success'
: 'badge bg-secondary-subtle text-secondary'
));

const formatDateTime = (value) => {
if (!value) return 'N/A';
const date = new Date(value);
if (Number.isNaN(date.getTime())) return value;
return date.toLocaleString();
};

const goBack = () => {
router.get(route('cooperative.batches.listed'));
};

const goCreate = () => {
router.get(route('cooperative.batches.create'));
};
</script>

<template>
<CooperativeLayout>
<div class="container">
<div class="card card-bordered details-card">
<div class="card-inner border-bottom details-head">
<div>
<h6 class="title mb-1"><em class="icon ni ni-layers mr-1"></em>Batch Details</h6>
<p class="sub-text mb-0">View full details of the selected commodity batch.</p>
</div>
<el-button-group>
<el-button plain @click="goBack">Back</el-button>
<el-button type="default" @click="goCreate">Add Batch</el-button>
</el-button-group>
</div>

<div class="card-inner">
<div class="details-grid">
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-hash mr-1"></em>Batch Code</span>
<strong>{{ batch.batch_code ?? 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-growth mr-1"></em>Commodity</span>
<strong>{{ batch.commodity_name ?? 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-box-view mr-1"></em>Commodity Type</span>
<strong>{{ batch.commodity_type ?? 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-package mr-1"></em>Weight</span>
<strong>{{ batch.weight ?? 'N/A' }} kg</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-award mr-1"></em>Grade</span>
<strong>{{ batch.grade ?? 'N/A' }}</strong>
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
<strong><span :class="statusClass">{{ batch.status ?? 'N/A' }}</span></strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-link-alt mr-1"></em>On Chain</span>
<strong><span :class="chainClass">{{ batch.is_on_chain ? 'Yes' : 'No' }}</span></strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-calendar mr-1"></em>Created At</span>
<strong>{{ formatDateTime(batch.created_at) }}</strong>
</div>



<div class="detail-section detail-item-full">
<h6 class="detail-section-title"><em class="icon ni ni-user mr-1"></em>Buyer Details</h6>
</div>

<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-user mr-1"></em>Buyer Name</span>
<strong>{{ batch.owner?.name || 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-call mr-1"></em>Buyer Phone</span>
<strong>{{ batch.owner?.phone || 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-map-pin mr-1"></em>Buyer Address</span>
<strong>{{ batch.owner?.address || 'N/A' }}</strong>
</div>
<div class="detail-item detail-item-full">
<span class="sub-text"><em class="icon ni ni-mail mr-1"></em>Buyer Email</span>
<strong>{{ batch.owner?.email || 'N/A' }}</strong>
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
grid-template-columns: repeat(2, minmax(0, 1fr));
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

.detail-section {
border-top: 1px solid #dbe3ee;
padding-top: 10px;
margin-top: 2px;
}

.detail-section-title {
margin: 0;
}

@media (max-width: 768px) {
.details-head {
flex-direction: column;
align-items: stretch;
}

.details-grid {
grid-template-columns: 1fr;
}

}
</style>
