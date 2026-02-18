<script setup>
import { computed } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';

const page = usePage();
const produces = computed(() => page.props.produces?.data ?? page.props.produces ?? []);

const totalBatches = computed(() => produces.value.length);
const listedCount = computed(() => Number(page.props.listed_count ?? produces.value.filter((b) => b.status === 'listed').length));
const soldCount = computed(() => Number(page.props.sold_count ?? produces.value.filter((b) => b.status === 'sold').length));
const totalVolume = computed(() => Number(page.props.total_quantity ?? produces.value.reduce((sum, b) => sum + Number(b.quantity || 0), 0)));

const statusClass = (status) => {
if (status === 'listed') return 'badge bg-success-subtle text-success';
if (status === 'negotiating') return 'badge bg-warning-subtle text-warning';
if (status === 'sold') return 'badge bg-info-subtle text-info';
return 'badge bg-light text-dark';
};

const goToBatch = (id) => {
router.get(route('cooperative.batch.show', { id }));
};
</script>

<template>
<CooperativeLayout>
<div class="container">

<div class="row g-3 mb-3">
<div class="col-12 col-md-6 col-lg-3">
<div class="stat-tile">
<em class="icon ni ni-layers stat-icon"></em>
<span class="sub-text">Total Batches</span>
<h5 class="mb-0">{{ totalBatches }}</h5>
</div>
</div>
<div class="col-12 col-md-6 col-lg-3">
<div class="stat-tile">
<em class="icon ni ni-bag stat-icon"></em>
<span class="sub-text">Listed For Sale</span>
<h5 class="mb-0">{{ listedCount }}</h5>
</div>
</div>
<div class="col-12 col-md-6 col-lg-3">
<div class="stat-tile">
<em class="icon ni ni-check-circle stat-icon"></em>
<span class="sub-text">Sold</span>
<h5 class="mb-0">{{ soldCount }}</h5>
</div>
</div>
<div class="col-12 col-md-6 col-lg-3">
<div class="stat-tile">
<em class="icon ni ni-growth stat-icon"></em>
<span class="sub-text">Total Volume</span>
<h5 class="mb-0">{{ totalVolume }} kg</h5>
</div>
</div>
</div>

<div class="card card-bordered">
<div class="card-inner border-bottom d-flex justify-content-between align-items-center">
<div>
<h6 class="title mb-1"><em class="icon ni ni-bag mr-1"></em>Batch Listings</h6>
<p class="sub-text mb-0">Current coffee lots available to buyers.</p>
</div>
<Link :href="route('cooperative.produce.create')" class="btn btn-primary btn-sm">
<em class="icon ni ni-plus mr-1"></em>New Batch
</Link>
</div>

<div class="table-wrap">
<table class="table table-sm table-middle mb-0 batch-table">
<thead>
<tr>
<th>ID</th>
<th>Crop Name</th>
<th><em class="icon ni ni-tree-view mr-1"></em>Crop Type</th>
<th><em class="icon ni ni-package mr-1"></em>Quantity</th>
<th><em class="icon ni ni-coins mr-1"></em>Price</th>
<th><em class="icon ni ni-map-pin mr-1"></em>Location</th>
<th><em class="icon ni ni-calendar mr-1"></em>Date Of Harvest</th>
<th><em class="icon ni ni-award mr-1"></em>Crop Grade</th>
<th>Process Method</th>
<th><em class="icon ni ni-flag mr-1"></em>Status</th>
</tr>
</thead>
<tbody>
<tr
v-for="produce in produces"
:key="produce.id"
class="clickable-row"
@click="goToBatch(produce.id)"
>
<td>{{ produce.id }}</td>
<td>{{ produce.crop_name }}</td>
<td>{{ produce.crop_type }}</td>
<td>{{ produce.quantity }}</td>
<td>{{ produce.price }}</td>
<td>{{ produce.location }}</td>
<td>{{ produce.date_of_harvest }}</td>
<td>{{ produce.crop_grade }}</td>
<td>{{ produce.process_method }}</td>
<td>
<span :class="statusClass(produce.status)">{{ produce.status }}</span>
</td>
</tr>
<tr v-if="!produces.length">
<td colspan="10" class="text-center py-3">No produce batches found.</td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
</CooperativeLayout>
</template>

<style scoped>
.page-head {
display: flex;
flex-direction: column;
gap: 2px;
}

.stat-tile {
background: #ffffff;
border: 1px solid var(--app-border);
border-radius: 10px;
padding: 12px;
}

.stat-icon {
display: inline-flex;
margin-bottom: 8px;
font-size: 1rem;
}

.table-wrap {
overflow-x: auto;
}

.batch-table th {
background: #f8fafc;
color: #526484;
font-weight: 600;
white-space: nowrap;
}

.batch-table td {
color: #364a63;
vertical-align: middle;
white-space: nowrap;
}

.clickable-row {
cursor: pointer;
}

.clickable-row:hover {
background: #f8fafc;
}
</style>
