<script setup>
import { computed, ref } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';
import { Plus, Search } from '@element-plus/icons-vue'

const page = usePage();
const produces = computed(() => page.props.produces?.data ?? page.props.produces ?? []);

const totalBatches = computed(() => produces.value.length);
const listedCount = computed(() => Number(page.props.listed_count ?? produces.value.filter((b) => b.status === 'listed').length));
const soldCount = computed(() => Number(page.props.sold_count ?? produces.value.filter((b) => b.status === 'sold').length));
const totalVolume = computed(() => page.props.total_quantity);
const searchQuery = ref('');

const handleSearchInput = (value) => {
searchQuery.value = String(value ?? '');
};

const filteredProduces = computed(() => {
const query = String(searchQuery.value ?? '').toLowerCase();
if (!query) return produces.value;

return produces.value.filter((produce) => {
const searchable = [
produce.id,
produce.commodity_name,
produce.commodity_type,
produce.weight,
produce.harvest_date,
produce.grade,
produce.status,
];

return searchable.some((value) => String(value ?? '').toLowerCase().includes(query));
});
});

const statusClass = (status) => {
if (status === 'listed') return 'badge bg-success-subtle text-success';
if (status === 'negotiating') return 'badge bg-warning-subtle text-warning';
if (status === 'sold') return 'badge bg-info-subtle text-info';
return 'badge bg-light text-dark';
};

const goToBatch = (id) => {
router.get(route('cooperative.batch.show', { id }));
};

const goToCreate = () => {
router.get(route('cooperative.produce.create'));
};
</script>

<template>
<CooperativeLayout>
<div class="nk-block">

<div class="card card-bordered">
<div class="card-inner border-bottom">
<div class="row g-2 align-items-center">
<div class="col-12 col-md">
<h6 class="title mb-1"><em class="icon ni ni-bag mr-1"></em>Commodities</h6>
<p class="sub-text mb-0">Current coffee lots available to buyers.</p>


</div>
<div class="col-12 col-md-auto">
<div class="produce-toolbar">
<el-input
v-model="searchQuery"
:prefix-icon="Search"
clearable
placeholder="Search commodity, type, grade, status..."
class="produce-search-input"
@input="handleSearchInput"
@clear="handleSearchInput('')"
></el-input>
<el-button :icon="Plus" @click="goToCreate" class="produce-add-btn" />
</div>
</div>
</div>
<div class="row g-3 mt-2">
<div class="col-12 col-md-6 col-lg-3">
<div class="stat-tile">
<em class="icon ni ni-layers stat-icon"></em>
<span class="sub-text">Total Commodity</span>
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
<h5 class="mb-0">{{ totalVolume }} kgs</h5>
</div>
</div>
</div>
</div>

<div class="table-wrap">
<table class="table table-sm table-middle mb-0 batch-table">
<thead>
<tr>
<th>ID</th>
<th>Crop Name</th>
<th><em class="icon ni ni-tree-view mr-1"></em>Crop Type</th>
<th><em class="icon ni ni-package mr-1"></em>Quantity</th>
<th><em class="icon ni ni-calendar mr-1"></em>Date Of Harvest</th>
<th style="width:70px;"><em class="icon ni ni-award mr-1"></em>Crop Grade</th>
<th><em class="icon ni ni-flag mr-1"></em>Status</th>
</tr>
</thead>
<tbody>
<tr
v-for="produce in filteredProduces"
:key="produce.id"
class="clickable-row"
@click="goToBatch(produce.id)"
>
<td>{{ produce.id }}</td>
<td>{{ produce.commodity_name }}</td>
<td>{{ produce.commodity_type }}</td>
<td>{{ produce.weight }}</td>
<td>{{ produce.harvest_date }}</td>
<td>{{ produce.grade }}</td>
<td>
<span :class="statusClass(produce.status)">{{ produce.status }}</span>
</td>
</tr>
<tr v-if="!filteredProduces.length">
<td colspan="10" class="text-center py-3">{{ searchQuery ? 'No matching produce found.' : 'No produce batches found.' }}</td>
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
background: #f8fafc;
border: 1px solid #e9edf5;
border-radius: 10px;
padding: 8px 10px;
}

.stat-icon {
display: inline-flex;
margin-bottom: 4px;
font-size: 0.9rem;
}

.stat-tile :deep(.sub-text) {
display: block;
line-height: 1.15;
}

.stat-tile h5 {
font-size: 1.02rem;
line-height: 1.1;
}

.produce-toolbar {
display: flex;
align-items: center;
gap: 8px;
}

.produce-search-input {
width: 360px;
max-width: 100%;
}

.produce-add-btn {
flex: 0 0 auto;
}

:deep(.produce-search-input .el-input__wrapper) {
border-radius: 10px;
box-shadow: 0 0 0 1px #d7deea inset;
}

@media (max-width: 767px) {
.produce-toolbar {
flex-direction: column;
align-items: flex-start;
}

.produce-search-input {
width: 100%;
}
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
