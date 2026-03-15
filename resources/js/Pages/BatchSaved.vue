<script setup>
import { computed, ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';
import { Search } from '@element-plus/icons-vue';

const page = usePage();
const search = ref('');

const rawBatches = computed(() => {
const source = page.props.batches?.data ?? page.props.batches ?? [];

if (Array.isArray(source)) {
return source;
}

if (source && typeof source === 'object') {
return Object.values(source);
}

return [];
});

const normalizedBatches = computed(() => {
return rawBatches.value.map((item) => ({
id: item.id,
batchNumber: item.batch_number ?? `BATCH-${item.id ?? 'N/A'}`,
commodityName: item.commodity_name ?? 'N/A',
grade: item.grade ?? 'N/A',
weight: Number(item.weight ?? 0),
quantity: Number(item.quantity ?? 0),
price: item.price ?? null,
warehouse: item.warehouse ?? 'N/A',
status: item.status ?? 'created',
createdAt: item.created_at ?? null,
}));
});

const filteredBatches = computed(() => {
const q = search.value.trim().toLowerCase();

return normalizedBatches.value.filter((batch) => {
if (!q) return true;

const haystack = [
batch.batchNumber,
batch.commodityName,
batch.grade,
batch.quantity,
batch.price,
batch.warehouse,
batch.status,
].join(' ').toLowerCase();

return haystack.includes(q);
});
});

const formatWeight = (value) => `${Number(value || 0).toLocaleString(undefined, { maximumFractionDigits: 2 })} kg`;
const formatQuantity = (value) => Number(value || 0).toLocaleString(undefined, { maximumFractionDigits: 2 });

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
return date.toLocaleDateString();
};

const viewBatch = (batch) => {
router.get(route('market.batchSaved.show', { id: batch.id }));
};
</script>

<template>
<CooperativeLayout>
<div class="container">
<div class="card">
<div class="card-inner border-bottom market-card-header">
<div class="market-title">
<h6 class="title mb-1">Saved Batches</h6>
<p class="sub-text mb-0">
{{ filteredBatches.length }} saved batch{{ filteredBatches.length === 1 ? '' : 'es' }}.
</p>
</div>

<div class="market-actions">
<el-input
v-model="search"
size="default"
clearable
:prefix-icon="Search"
class="market-search"
placeholder="Search saved batches..."
/>
</div>
</div>

<div class="table-wrap">
<table class="table table-sm table-middle mb-0 batch-table">
<thead>
<tr>
<th><span class="table-head-label"><em class="icon ni ni-hash"></em>Batch No.</span></th>
<th><span class="table-head-label"><em class="icon ni ni-growth"></em>Commodity</span></th>
<th><span class="table-head-label"><em class="icon ni ni-award"></em>Grade</span></th>
<th><span class="table-head-label"><em class="icon ni ni-package"></em>Weight</span></th>
<th><span class="table-head-label"><em class="icon ni ni-layers"></em>Quantity</span></th>
<th><span class="table-head-label"><em class="icon ni ni-coins"></em>Price</span></th>
<th><span class="table-head-label"><em class="icon ni ni-home-fill"></em>Warehouse</span></th>
<th><span class="table-head-label"><em class="icon ni ni-flag"></em>Status</span></th>
<th><span class="table-head-label"><em class="icon ni ni-calendar"></em>Date</span></th>
<th class="text-end"><span class="table-head-label"><em class="icon ni ni-eye"></em>Action</span></th>
</tr>
</thead>
<tbody>
<tr v-for="batch in filteredBatches" :key="batch.id" class="batch-row" @click="viewBatch(batch)">
<td>
<strong>{{ batch.batchNumber }}</strong>
<div class="sub-text">#{{ batch.id }}</div>
</td>
<td>{{ batch.commodityName }}</td>
<td>{{ batch.grade }}</td>
<td>{{ formatWeight(batch.weight) }}</td>
<td>{{ formatQuantity(batch.quantity) }}</td>
<td>{{ formatPrice(batch.price) }}</td>
<td>{{ batch.warehouse }}</td>
<td><span class="badge bg-info-subtle text-info">{{ batch.status }}</span></td>
<td>{{ formatDate(batch.createdAt) }}</td>
<td class="text-end">
<el-button type="default" plain size="small" @click.stop="viewBatch(batch)">
View
</el-button>
</td>
</tr>

<tr v-if="!filteredBatches.length">
<td colspan="10" class="text-center py-4 text-muted">
No saved batches found.
</td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
</CooperativeLayout>
</template>

<style scoped>
.table-wrap {
overflow-x: auto;
}

.market-card-header {
display: flex;
align-items: center;
justify-content: space-between;
gap: 16px;
flex-wrap: wrap;
}

.market-actions {
display: flex;
align-items: center;
gap: 12px;
width: min(100%, 420px);
}

.market-search {
flex: 1;
--el-input-height: 34px;
}

.market-search :deep(.el-input__wrapper) {
height: 34px !important;
min-height: 34px !important;
}

.market-search :deep(.el-input__inner) {
height: 34px !important;
line-height: 34px !important;
}

.batch-table th {
font-size: 12px;
text-transform: uppercase;
letter-spacing: 0.04em;
color: #64748b;
}

.table-head-label {
display: inline-flex;
align-items: center;
gap: 0.35rem;
}

.batch-row {
cursor: pointer;
}

.batch-row:hover {
background: #f8fafc;
}

@media (max-width: 767px) {
.market-actions {
width: 100%;
}
}
</style>
