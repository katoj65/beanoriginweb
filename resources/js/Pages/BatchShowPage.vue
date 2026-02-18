<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';

const page = usePage();
const batch = computed(() => page.props.produce?.data ?? page.props.produce ?? {});
const farmer = computed(() => page.props.farmer?.data ?? page.props.farmer ?? {});








const statusClass = computed(() => {
const status = batch.value?.status;
if (status === 'listed') return 'badge bg-success-subtle text-success';
if (status === 'expired') return 'badge bg-danger-subtle text-danger';
if (status === 'sold') return 'badge bg-info-subtle text-info';
return 'badge bg-light text-dark';
});

const batchTitle = computed(() => `Batch #${batch.value?.id ?? 'N/A'}`);

const formatDate = (value) => {
if (!value) return 'N/A';
const date = new Date(value);
if (Number.isNaN(date.getTime())) return value;
return date.toLocaleDateString();
};

const formatMoney = (value) => {
if (value === null || value === undefined || value === '') return 'N/A';
const num = Number(value);
if (Number.isNaN(num)) return value;
return num.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
};
</script>

<template>
<CooperativeLayout>
<div class="container">
<div class="card card-bordered">
<div class="card-inner border-bottom d-flex justify-content-between align-items-center">
<div>
<h6 class="title mb-1"><em class="icon ni ni-bag mr-1"></em>{{ batchTitle }}</h6>
<p class="sub-text mb-0">Commodity batch traceability details from farm origin to listing.</p>
</div>
<Link :href="route('cooperative.produce')" class="btn btn-outline-light btn-sm">
<em class="icon ni ni-arrow-left mr-1"></em>Back to Batches
</Link>
</div>

<div class="card-inner">
<div class="details-grid">
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-hash mr-1"></em>Batch ID</span>
<strong>{{ batch.id }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-flag mr-1"></em>Status</span>
<strong><span :class="statusClass">{{ batch.status }}</span></strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-growth mr-1"></em>Crop Name</span>
<strong>{{ batch.crop_name }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-growth mr-1"></em>Crop Type</span>
<strong>{{ batch.crop_type }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-package mr-1"></em>Quantity</span>
<strong>{{ batch.quantity }} kg</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-coins mr-1"></em>Price</span>
<strong>{{ formatMoney(batch.price) }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-map-pin mr-1"></em>Location</span>
<strong>{{ batch.location }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-calendar mr-1"></em>Date of Harvest</span>
<strong>{{ formatDate(batch.date_of_harvest) }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-award mr-1"></em>Crop Grade</span>
<strong>{{ batch.crop_grade }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-setting mr-1"></em>Process Method</span>
<strong>{{ batch.process_method }}</strong>
</div>

</div>
</div>






<div class="card-inner border-top">
<h6 class="title mb-3"><em class="icon ni ni-user mr-1"></em>Farmer Details</h6>
<div class="details-grid">
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-user mr-1 label-icon"></em>Name</span>
<strong>{{ [farmer.first_name, farmer.last_name].filter(Boolean).join(' ') || 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-users mr-1 label-icon"></em>Gender</span>
<strong>{{ farmer.gender ?? 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-growth mr-1 label-icon"></em>Primary Crop</span>
<strong>{{ farmer.crop ?? 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-map-pin mr-1 label-icon"></em>Location</span>
<strong>{{ farmer.location ?? 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-check-circle mr-1 label-icon"></em>Status</span>
<strong>{{ farmer.status ?? 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-call mr-1 label-icon"></em>Telephone</span>
<strong>{{ farmer.telephone ?? 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-mail mr-1 label-icon"></em>Email</span>
<strong>{{ farmer.email ?? 'N/A' }}</strong>
</div>
</div>
</div>
</div>
</div>
</CooperativeLayout>
</template>

<style scoped>
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
grid-column: span 2;
}

.label-icon {
color: currentColor;
font-size: 14px;
opacity: 1;
}

@media (max-width: 768px) {
.details-grid {
grid-template-columns: 1fr;
}

.detail-item-full {
grid-column: span 1;
}
}
</style>
