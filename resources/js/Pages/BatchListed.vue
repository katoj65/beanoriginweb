<script setup>
import { computed, ref } from 'vue';
import { router, usePage, useForm } from '@inertiajs/vue3';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';
import SubmitButton from '@/Components/SubmitButton.vue';
import { Search, Plus } from '@element-plus/icons-vue';

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
commodityNames,
commodityCount: item.commodity_count ?? commodityNames.length,
askPrice: item.ask_price ?? null,
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

const viewBatch = (batch) => {
router.get(route('cooperative.batches.show', { id: batch.id }));
};

const resetFilters = () => {
selectedStatus.value = 'all';
search.value = '';
};

const goToCreate = () => {
router.get(route('cooperative.batches.create'));
};

const centerDialogVisible = ref(false);
const form = useForm({
batch_number: '',
batch_action: '',
});

const handleActionConfirm = () => {
form.post(route('cooperative.batches.verification.action'), {
preserveScroll: true,
onSuccess: () => {
centerDialogVisible.value = false;
form.reset();
},
});
};

const fallbackActions = [
{ id: 'roasted', name: 'roasted' },
{ id: 'bought', name: 'bought' },
{ id: 'shipped', name: 'shipped' },
{ id: 'delivered', name: 'delivered' },
{ id: 'exported', name: 'exported' },
];
const actions = computed(() => {
const list = page.props.batch_action_list ?? [];
return list.length ? list : fallbackActions;
});

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
placeholder="Search by batch number, status, commodity..."
/>
<div class="market-app-buttons">
<el-button-group>
<el-button size="large" type="default" plain :icon="Plus" @click="goToCreate">
Add Batch
</el-button>
<el-button size="large" type="default" plain @click="centerDialogVisible = true">
Action
</el-button>
</el-button-group>
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
<th class="text-end">Action</th>
</tr>
</thead>
<tbody>
<tr v-for="batch in filteredBatches" :key="batch.id" class="batch-row" @click="viewBatch(batch)">
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
<td class="text-end">
<el-button type="default" plain size="small" @click.stop="viewBatch(batch)">
View
</el-button>
</td>
</tr>
<tr v-if="!filteredBatches.length">
<td colspan="8" class="text-center py-4 text-muted">
No blockchain batches found for the current filters.
</td>
</tr>
</tbody>
</table>
</div>
</div>
</div>








<el-dialog
v-model="centerDialogVisible"
class="theme-action-dialog"
width="560"
align-center
>
<template #header>
<div class="theme-dialog-header border-0">
<div>
<h5 class="theme-dialog-title">Batch Action</h5>
<p class="theme-dialog-subtext mb-0">Apply a quick action to the live market board.</p>
</div>
</div>
</template>

<div class="theme-dialog-body border-0">
<form @submit.prevent="handleActionConfirm">
<div class="form-group">
<label class="form-label" for="batch-number-input">Batch Number</label>
<div class="form-control-wrap theme-no-highlight">
<el-input
id="batch-number-input"
v-model="form.batch_number"
class="w-100 dialog-field-control"
size="large"
clearable
placeholder="Enter batch number"
/>
</div>
<div v-if="form.errors.batch_number" class="text-danger small mt-1">
{{ form.errors.batch_number }}
</div>
</div>




<div class="form-group">
<label class="form-label" for="batch-action-select">Action</label>
<div class="form-control-wrap">
<el-select
id="batch-action-select"
v-model="form.batch_action"
class="w-100 theme-no-highlight-select dialog-field-control"
placeholder="Select action"
clearable
filterable
popper-class="theme-no-highlight-select-popper"
>
<el-option
v-for="item in actions"
:key="item.id"
:label="item.name"
:value="item.name"
/>
</el-select>
</div>
<div v-if="form.errors.batch_action" class="text-danger small mt-1">
{{ form.errors.batch_action }}
</div>
</div>

<div class="theme-dialog-footer mt-3">
<SubmitButton :title="'Submit'" :status="form.processing" />
</div>
</form>

</div>
</el-dialog>











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

.batch-row {
cursor: pointer;
}

.batch-row:hover td {
background: #f8fafc;
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

.dialog-field-control :deep(.el-input__wrapper),
.dialog-field-control :deep(.el-select__wrapper) {
min-height: 44px !important;
height: 44px !important;
align-items: center;
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
