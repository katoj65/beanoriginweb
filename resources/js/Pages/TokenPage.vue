<script setup>
import { computed, ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';
import { Back, Clock, Filter, ShoppingCart } from '@element-plus/icons-vue';
import BuyButton from '@/Components/BuyButton.vue';

const page = usePage();
const batches = computed(() => page.props.batches ?? []);
const isFilterOpen = ref(false);
const searchQuery = ref('');
const selectedCommodity = ref('');
const selectedGrade = ref('');

const commodityOptions = computed(() =>
[...new Set(
batches.value
.map((item) => String(item?.commodity_name ?? '').trim())
.filter(Boolean)
)]
.sort((a, b) => a.localeCompare(b))
);

const gradeOptions = computed(() =>
[...new Set(
batches.value
.map((item) => String(item?.grade ?? '').trim())
.filter(Boolean)
)]
.sort((a, b) => a.localeCompare(b))
);

const filteredBatches = computed(() => {
const query = searchQuery.value.trim().toLowerCase();

return batches.value.filter((item) => {
const commodity = String(item?.commodity_name ?? '').trim();
const type = String(item?.commodity_type ?? '').trim();
const grade = String(item?.grade ?? '').trim();
const batchCode = String(item?.batch_code ?? `#${item?.id ?? ''}`).trim();

if (selectedCommodity.value && commodity !== selectedCommodity.value) return false;
if (selectedGrade.value && grade !== selectedGrade.value) return false;

if (!query) return true;

return [
batchCode,
commodity,
type,
grade,
String(item?.id ?? ''),
].some((value) => String(value).toLowerCase().includes(query));
});
});

// Format amount values consistently for list display.
const formatMoney = (value) => {
if (value === null || value === undefined || value === '') return 'N/A';
const amount = Number(value);
if (Number.isNaN(amount)) return value;
return amount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
};

// Normalize status labels for display consistency.
const formatStatus = (value) => {
const status = String(value ?? '').trim().toLowerCase();
if (!status) return 'tokenized';
if (status === 'tokenised') return 'tokenized';
return status;
};

// Navigate back to cooperative produce page.
const goBack = () => {
router.get(route('market.index'));
};

// Open authenticated user's tokenized batches.
const goToMyTokens = () => {
router.get(route('token.user-token'));
};

// Open reserved market batches.
const goToReservedMarket = () => {
router.get(route('market.reserved'));
};

// Open bought market batches.
const goToBoughtMarket = () => {
router.get(route('market.bought'));
};

// Open selected batch details page from tokenized batches table.
const openBatchDetails = (batchId) => {
if (!batchId) return;
router.get(route('batch.data', { id: batchId }));
};

const clearFilters = () => {
searchQuery.value = '';
selectedCommodity.value = '';
selectedGrade.value = '';
};

const toggleFilterPanel = () => {
isFilterOpen.value = !isFilterOpen.value;
};
</script>

<template>
<CooperativeLayout>
<div class="container">
<div class="card token-page-card">
<div class="card-inner border-bottom token-head">
<div class="token-head-title">
<h6 class="title mb-1">Marketplace </h6>
<p class="sub-text mb-0">Batches available on the market.</p>
</div>
<el-button plain size="small" class="head-filter-btn" :icon="Filter" @click="toggleFilterPanel">
{{ isFilterOpen ? 'Hide Filter' : 'Show Filter' }}
</el-button>
</div>


<div class="card-inner token-table-body">
<transition name="filter-slide">
<div v-show="isFilterOpen" class="market-filter-panel">
<div class="market-filter-fields">
<el-input
v-model="searchQuery"
size="small"
class="market-filter-search"
clearable
placeholder="Search batch, commodity, type, or grade"
/>
<el-select v-model="selectedCommodity" size="small" class="market-filter-select" clearable placeholder="All Commodities">
<el-option v-for="option in commodityOptions" :key="option" :label="option" :value="option" />
</el-select>
<el-select v-model="selectedGrade" size="small" class="market-filter-select" clearable placeholder="All Grades">
<el-option v-for="option in gradeOptions" :key="option" :label="option" :value="option" />
</el-select>
<el-button plain size="small" class="market-filter-reset" @click="clearFilters">Reset</el-button>
</div>
</div>
</transition>

<div v-if="filteredBatches.length" class="table-responsive">
<table class="table table-sm table-middle mb-0 token-table">
<thead>
<tr>
<th ><span class="table-head-label"><em class="icon ni ni-hash"></em>Batch</span></th>
<th><span class="table-head-label"><em class="icon ni ni-growth"></em>Commodity - Type</span></th>
<th><span class="table-head-label"><em class="icon ni ni-award"></em>Grade</span></th>
<th><span class="table-head-label"><em class="icon ni ni-package"></em>Weight</span></th>
<th><span class="table-head-label"><em class="icon ni ni-layers"></em>Quantity</span></th>
<th><span class="table-head-label"><em class="icon ni ni-coins"></em>Price</span></th>
<th><span class="table-head-label"><em class="icon ni ni-cart"></em>Action</span></th>
</tr>
</thead>
<tbody>
<tr v-for="item in filteredBatches" :key="item.id" class="token-row-clickable">
<td @click="openBatchDetails(item.id)" style="width:50px;">{{ item.batch_code ?? `#${item.id}` }}</td>
<td class="text-capitalize" @click="openBatchDetails(item.id)">{{ item.commodity_name ?? 'N/A' }} - {{ item.commodity_type ?? 'N/A' }}</td>
<td class="text-capitalize" @click="openBatchDetails(item.id)">{{ item.grade ?? 'N/A' }}</td>
<td @click="openBatchDetails(item.id)">{{ item.weight ?? 'N/A' }} kg</td>
<td @click="openBatchDetails(item.id)">{{ item.quantity ?? 'N/A' }}</td>
<td @click="openBatchDetails(item.id)">{{ formatMoney(item.price) }}</td>
<td>
<BuyButton :item="item" />
</td>
</tr>
</tbody>
</table>
</div>

<div v-else-if="batches.length" class="empty-state">
No batches match the selected filters.
</div>

<div v-else class="empty-state">
No tokenized batches available.
</div>
</div>
</div>
</div>
</CooperativeLayout>
</template>

<style scoped>
.token-page-card {
border-radius: 12px;
}

.token-head {
display: flex;
align-items: flex-start;
justify-content: flex-start;
gap: 12px;
flex-wrap: nowrap;
position: relative;
padding-right: 120px;
}

.token-head-title {
flex: 1 1 auto;
min-width: 0;
}

.head-filter-btn {
position: absolute;
top: 12px;
right: 16px;
}

.token-table-body {
padding: 0 !important;
}

.market-filter-panel {
padding: 10px 16px 12px;
border-bottom: 1px solid #e2e8f0;
background: #f8fafc;
}

.market-filter-fields {
display: grid;
grid-template-columns: minmax(220px, 2fr) repeat(2, minmax(140px, 1fr)) auto;
gap: 8px;
}

.market-filter-panel {
--market-filter-control-height: 36px;
}

.market-filter-search,
.market-filter-select {
width: 100%;
}

.market-filter-search {
--el-input-height: var(--market-filter-control-height);
}

.market-filter-reset {
--el-button-size: var(--market-filter-control-height);
--el-component-size-small: var(--market-filter-control-height);
min-height: var(--market-filter-control-height) !important;
height: var(--market-filter-control-height) !important;
line-height: var(--market-filter-control-height) !important;
font-size: 13px !important;
padding-left: 12px !important;
padding-right: 12px !important;
padding-top: 0 !important;
padding-bottom: 0 !important;
display: inline-flex;
align-items: center;
}

.market-filter-reset :deep(.el-button__text) {
line-height: var(--market-filter-control-height) !important;
font-size: 13px !important;
}

.market-filter-search :deep(.el-input__wrapper),
.market-filter-select :deep(.el-select__wrapper) {
height: var(--market-filter-control-height) !important;
min-height: var(--market-filter-control-height) !important;
padding-top: 0 !important;
padding-bottom: 0 !important;
}

.market-filter-search :deep(.el-input__inner),
.market-filter-select :deep(.el-select__placeholder),
.market-filter-select :deep(.el-select__selected-item) {
line-height: var(--market-filter-control-height) !important;
font-size: 13px !important;
}

.filter-slide-enter-active,
.filter-slide-leave-active {
transition: max-height 0.24s ease, opacity 0.18s ease, transform 0.18s ease;
overflow: hidden;
}

.filter-slide-enter-from,
.filter-slide-leave-to {
max-height: 0;
opacity: 0;
transform: translateY(-6px);
}

.filter-slide-enter-to,
.filter-slide-leave-from {
max-height: 220px;
opacity: 1;
transform: translateY(0);
}

.token-table-body .table-responsive {
margin: 0;
width: 100%;
}

.token-table {
width: 100%;
margin: 0;
}

.token-table th {
background: #f8fafc;
color: #526484;
font-weight: 600;
white-space: nowrap;
}

.token-table td {
white-space: nowrap;
}

.table-head-label {
display: inline-flex;
align-items: center;
gap: 6px;
}

.token-row-clickable {
cursor: pointer;
}

.token-row-clickable:hover {
background: #f8fafc;
}

.empty-state {
border: 1px dashed #d9e2f0;
border-radius: 10px;
background: #f8fafc;
padding: 16px 24px;
color: #64748b;
}

@media (max-width: 992px) {
.market-filter-fields {
grid-template-columns: 1fr 1fr;
}
}

@media (max-width: 640px) {
.token-head {
padding-right: 96px;
}

.market-filter-fields {
grid-template-columns: 1fr;
}

.market-filter-search,
.market-filter-select,
.market-filter-reset {
width: 100%;
}
}
</style>
