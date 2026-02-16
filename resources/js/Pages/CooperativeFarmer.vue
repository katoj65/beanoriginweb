<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';

const page = usePage();

const farmers = computed(() => page.props.farmers.data);
const stats = computed(() => page.props.stats ?? { total: 0, active: 0, pending: 0 });

const statusClass = (status) => {
if (status === 'active') return 'bg-success';
if (status === 'pending') return 'bg-warning text-dark';
if (status === 'suspended') return 'bg-danger';
return 'bg-secondary';
};

const statusLabel = (status) => {
if (!status) return 'Unknown';
return status.charAt(0).toUpperCase() + status.slice(1).replace('_', ' ');
};

const addressLabel = (farmer) => {
return [farmer.village, farmer.sub_county, farmer.district].filter(Boolean).join(', ') || 'N/A';
};
</script>

<template>
<CooperativeLayout>
<div class="container">

<div class="row g-gs">
<div class="col-12">
<div class="card card-bordered">
<div class="card-inner farmers-header-compact d-flex justify-content-between align-items-start">
<div>
<h6 class="title mb-1">Registered Farmers</h6>
<p class="sub-text mb-0">Farmers currently registered under your cooperative.</p>
</div>
<Link :href="route('cooperative.farmers.create')" class="btn btn-primary btn-sm">
<em class="icon ni ni-plus mr-1"></em>
Add Farmer
</Link>
</div>
<div class="card-inner">
<div class="stats-grid">
<div class="stat-tile">
<span class="sub-text">Total Farmers</span>
<h4 class="mb-0">{{ stats.total }}</h4>
</div>
<div class="stat-tile">
<span class="sub-text">Active</span>
<h4 class="mb-0 text-success">{{ stats.active }}</h4>
</div>
<div class="stat-tile">
<span class="sub-text">Pending</span>
<h4 class="mb-0 text-warning">{{ stats.pending }}</h4>
</div>
</div>
</div>

<div class="card-inner p-0">
<div v-if="farmers.length === 0" class="empty-state">
<em class="icon ni ni-users"></em>
<h6 class="mb-1">No Farmers Registered Yet</h6>
<p class="sub-text mb-0">Farmers linked to your cooperative will appear here.</p>
</div>

<div v-else class="table-responsive">
<table class="table mb-0">
<thead>
<tr>
<th>#</th>
<th>Name</th>
<th>Phone</th>
<th>Email</th>
<th>Crop</th>
<th>Location</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>
<tbody>
<tr v-for="(farmer, index) in farmers" :key="farmer.id">
<td>{{ index + 1 }}</td>
<td>{{ farmer.full_name || 'N/A' }}</td>
<td>{{ farmer.phone_number || 'N/A' }}</td>
<td>{{ farmer.email || 'N/A' }}</td>
<td>{{ farmer.primary_crop || 'N/A' }}</td>
<td>{{ addressLabel(farmer) }}</td>
<td>
<span class="badge rounded-pill" :class="statusClass(farmer.status)">
    {{ statusLabel(farmer.status) }}
</span>
</td>
<td>
<Link :href="route('cooperative.farmers.show', farmer.id)" class="btn btn-sm btn-light">
View
</Link>
</td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
</div>
</CooperativeLayout>
</template>

<style scoped>
.farmers-header-compact {
padding-bottom: 8px;
}

.stats-grid {
display: grid;
grid-template-columns: repeat(3, minmax(0, 1fr));
gap: 12px;
}

.stat-tile {
border-radius: 10px;
padding: 12px;
background: #f8fafc;
}

.empty-state {
padding: 2.5rem 1rem;
text-align: center;
}

.empty-state .icon {
font-size: 2rem;
color: #94a3b8;
margin-bottom: 0.75rem;
}

@media (max-width: 768px) {
.stats-grid {
grid-template-columns: 1fr;
}
}
</style>
