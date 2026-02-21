<script setup>
import { computed, ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';
import { Search, Plus, Refresh } from '@element-plus/icons-vue';

const page = usePage();

const search = ref('');
const selectedStatus = ref('all');

const statusOptions = [
{ label: 'All Status', value: 'all' },
{ label: 'Listed', value: 'listed' },
{ label: 'Processing', value: 'processing' },
{ label: 'Processed', value: 'processed' },
{ label: 'Sold', value: 'sold' },
];

const rawBatches = computed(() => page.props.batches?.data ?? page.props.batches ?? []);

const parseCommodityNames = (item) => {
if (Array.isArray(item?.commodities)) {
return item.commodities
.map((commodity) => commodity?.commodity_name)
.filter(Boolean);
}

if (Array.isArray(item?.commodity_names)) {
return item.commodity_names.filter(Boolean);
}

if (typeof item?.commodity_names === 'string' && item.commodity_names.trim()) {
return item.commodity_names.split(',').map((value) => value.trim()).filter(Boolean);
}

return [];
};

const normalizedBatches = computed(() => {
return rawBatches.value.map((item) => {
const commodityNames = parseCommodityNames(item);
return {
id: item.id,
batchNumber: item.batch_number ?? `BATCH-${item.id ?? 'N/A'}`,
status: item.status ?? 'listed',
grade: item.grade ?? 'N/A',
weight: Number(item.weight ?? 0),
listedAt: item.listed_at ?? item.created_at ?? null,
latestBlockHash: item.latest_block_hash ?? item.current_hash ?? null,
commodityNames,
commodityCount: item.commodity_count ?? commodityNames.length,
askPrice: item.ask_price ?? null,
viewCommodityId: item.commodity_id ?? item.commodities?.[0]?.id ?? null,
};
});
});

const filteredBatches = computed(() => {
const q = search.value.trim().toLowerCase();
return normalizedBatches.value.filter((batch) => {
const matchesStatus = selectedStatus.value === 'all' || batch.status === selectedStatus.value;
const haystack = [
batch.batchNumber,
batch.status,
batch.grade,
batch.commodityNames.join(' '),
batch.latestBlockHash ?? '',
].join(' ').toLowerCase();
const matchesSearch = !q || haystack.includes(q);
return matchesStatus && matchesSearch;
});
});

const statusClass = (status) => {
if (status === 'listed') return 'badge bg-success-subtle text-success';
if (status === 'processing') return 'badge bg-warning-subtle text-warning';
if (status === 'processed') return 'badge bg-info-subtle text-info';
if (status === 'sold') return 'badge bg-secondary-subtle text-secondary';
return 'badge bg-light text-dark';
};

const formatWeight = (value) => `${Number(value || 0).toLocaleString(undefined, { maximumFractionDigits: 2 })} kg`;

const formatDate = (value) => {
if (!value) return 'N/A';
const date = new Date(value);
if (Number.isNaN(date.getTime())) return value;
return date.toLocaleDateString();
};

const formatPrice = (value) => {
if (value === null || value === undefined || value === '') return 'Negotiable';
const amount = Number(value);
if (Number.isNaN(amount)) return value;
return amount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
};

const truncateHash = (value) => {
if (!value) return 'N/A';
if (value.length <= 16) return value;
return `${value.slice(0, 10)}...${value.slice(-6)}`;
};

const viewBatch = (batch) => {
if (batch.viewCommodityId) {
router.get(route('cooperative.batch.show', { id: batch.viewCommodityId }));
return;
}

router.get(route('cooperative.produce'));
};

const resetFilters = () => {
selectedStatus.value = 'all';
search.value = '';
};

const goToCreate = () => {
router.get(route('cooperative.batches.create'));
};
</script>

<template>
<CooperativeLayout>
<div class="container">
<div class="card card-bordered">
<div class="card-inner border-bottom market-card-header">
<div class="market-title">
<h6 class="title mb-1">Blockchain Trading Board</h6>
<p class="sub-text mb-0">
{{ filteredBatches.length }} result{{ filteredBatches.length === 1 ? '' : 's' }} available for trading.
</p>
</div>
<div class="market-actions">
<el-input
v-model="search"
size="large"
clearable
:prefix-icon="Search"
class="market-search"
placeholder="Search by batch number, hash, commodity..."
/>
<div class="market-app-buttons">

<el-button
size="large"
plain
type="default"
@click="selectedStatus = 'all'"
>
All
</el-button>
<el-button
size="large"
type="default"
plain
@click="selectedStatus = 'listed'"
>
Listed
</el-button>
<el-button size="large" type="default" plain :icon="Plus" @click="goToCreate">
Add Batch
</el-button>
</div>
</div>
</div>

<div class="table-wrap">
<table class="table table-sm table-middle mb-0 batch-table">
<thead>
<tr>
<th>Batch No.</th>
<th>Commodity</th>
<th>Grade</th>
<th>Weight</th>
<th>Ask Price</th>
<th>Status</th>
<th>Listed Date</th>
<th>Block Hash</th>
<th class="text-end">Action</th>
</tr>
</thead>
<tbody>
<tr v-for="batch in filteredBatches" :key="batch.id">
<td>
<strong>{{ batch.batchNumber }}</strong>
<div class="sub-text">#{{ batch.id }}</div>
</td>
<td>
<div class="fw-medium">{{ batch.commodityNames[0] ?? 'N/A' }}</div>
<div class="sub-text">{{ batch.commodityCount }} linked commodity(s)</div>
</td>
<td>{{ batch.grade }}</td>
<td>{{ formatWeight(batch.weight) }}</td>
<td>{{ formatPrice(batch.askPrice) }}</td>
<td>
<span :class="statusClass(batch.status)">{{ batch.status }}</span>
</td>
<td>{{ formatDate(batch.listedAt) }}</td>
<td>
<span class="hash-chip" :title="batch.latestBlockHash ?? ''">{{ truncateHash(batch.latestBlockHash) }}</span>
</td>
<td class="text-end">
<el-button type="primary" plain size="small" @click="viewBatch(batch)">
View
</el-button>
</td>
</tr>
<tr v-if="!filteredBatches.length">
<td colspan="9" class="text-center py-4 text-muted">
No blockchain batches found for the current filters.
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

.batch-table th {
background: #f8fafc;
color: #526484;
font-weight: 600;
white-space: nowrap;
}

.batch-table td {
vertical-align: middle;
color: #364a63;
white-space: nowrap;
}

.hash-chip {
display: inline-flex;
align-items: center;
border: 1px dashed #cbd5e1;
background: #f8fafc;
border-radius: 999px;
padding: 2px 8px;
font-size: 12px;
font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, 'Liberation Mono', 'Courier New', monospace;
color: #475569;
}

.market-card-header {
display: flex;
justify-content: space-between;
align-items: center;
gap: 14px;
}

.market-actions {
display: flex;
align-items: center;
gap: 10px;
}

.market-search {
width: 360px;
max-width: 100%;
--el-input-border-color: #dcdfe6;
--el-input-hover-border-color: #dcdfe6;
--el-input-focus-border-color: #dcdfe6;
--el-input-focus-border: #dcdfe6;
--el-input-bg-color: #ffffff;
--el-input-text-color: #606266;
--el-fill-color-blank: #ffffff;
}

.market-search :deep(.el-input__wrapper),
.market-search :deep(.el-input__wrapper:hover),
.market-search :deep(.el-input__wrapper.is-focus),
.market-search :deep(.el-input__wrapper:focus-within) {
box-shadow: 0 0 0 1px #dcdfe6 inset !important;
border-color: #dcdfe6 !important;
transition: none !important;
background-color: #ffffff !important;
}

.market-search :deep(.el-input__inner),
.market-search :deep(.el-input__inner:focus) {
outline: none;
box-shadow: none !important;
background-color: transparent !important;
-webkit-tap-highlight-color: transparent;
caret-color: inherit;
}

.market-search :deep(.el-input__inner:focus-visible) {
outline: none !important;
}

.market-search :deep(.el-input__prefix-inner) {
color: #909399 !important;
}

.market-search :deep(.el-input__wrapper.is-focus .el-input__prefix-inner) {
color: #909399 !important;
}

.market-app-buttons {
display: flex;
align-items: center;
gap: 8px;
}

@media (max-width: 767px) {
.market-card-header {
flex-direction: column;
align-items: stretch;
}

.market-actions {
flex-direction: column;
align-items: stretch;
}

.market-search {
width: 100%;
}

.market-app-buttons {
justify-content: flex-end;
}
}
</style>
