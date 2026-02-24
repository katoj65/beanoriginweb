<script setup>
import { computed, ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import { Search } from '@element-plus/icons-vue';
import BuyerLayout from '@/Layouts/BuyerLayout.vue';

const page = usePage();

const listings = computed(() => {
const source = page.props.response?.listings?.data ?? page.props.response?.listings ?? [];
return Array.isArray(source) ? source : [];
});

const listedCount = computed(() => Number(page.props.response?.listed_count ?? listings.value.length));
const totalWeight = computed(() => Number(page.props.response?.total_weight ?? 0));
const averagePrice = computed(() => Number(page.props.response?.average_price ?? 0));
const search = ref('');

const filteredListings = computed(() => {
const query = search.value.trim().toLowerCase();
if (!query) return listings.value;

return listings.value.filter((item) => {
const haystack = [
item.commodity_name,
item.batch_code,
item.grade,
item.status,
item.created_at,
].join(' ').toLowerCase();

return haystack.includes(query);
});
});

const goToListing = (row) => {
const listingId = Number(row?.batch_id ?? row?.id);
if (!Number.isFinite(listingId) || listingId <= 0) return;
router.get(route('buyer.market.show', { id: listingId }));
};


const formatWeight = (value) => `${Number(value ?? 0).toLocaleString()} kg`;
const formatPrice = (value) => `UGX ${Number(value ?? 0).toLocaleString()}`;
const formatDate = (value) => {
if (!value) return 'N/A';
const date = new Date(value);
if (Number.isNaN(date.getTime())) return String(value);
return date.toLocaleDateString();
};

const formatStatus = (status) => {
const key = String(status ?? '').toLowerCase();
if (key === 'active') return 'Active';
if (key === 'used') return 'Used';
return 'N/A';
};
</script>

<template>
<buyer-layout>
<div class="container py-0">


<div class="row mt-0">
<div class="col-12">
<div class="card card-bordered card-preview modern-panel">
<div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
<div>
<h6 class="mb-0">Market Information</h6>
<span class="sub-text">Blocks currently listed for buying</span>
</div>
<div class="market-controls">
<el-input
v-model="search"
placeholder="Search commodity, batch, grade..."
clearable
class="market-search"
:prefix-icon="Search"
/>
<el-button type="default" plain class="market-filter-btn">
<em class="icon ni ni-filter"></em>
Filter
</el-button>
</div>
</div>
<div class="card-body p-0">
<el-table :data="filteredListings" height="420" style="width: 100%" @row-click="goToListing" class="clickable-market-table">
<el-table-column prop="commodity_name" min-width="170" label="Commodity" />
<el-table-column prop="batch_code" width="150" label="Batch Code" />
<el-table-column prop="grade" width="120" label="Grade" />
<el-table-column prop="weight" width="120" label="Weight">
<template #default="scope">{{ formatWeight(scope.row.weight) }}</template>
</el-table-column>
<el-table-column prop="price" width="140" label="Ask Price">
<template #default="scope">{{ formatPrice(scope.row.price) }}</template>
</el-table-column>
<el-table-column prop="status" width="110" label="Block Status">
<template #default="scope">
<span class="badge bg-success-subtle text-success">{{ formatStatus(scope.row.status) }}</span>
</template>
</el-table-column>
<el-table-column prop="created_at" width="130" label="Listed On">
<template #default="scope">{{ formatDate(scope.row.created_at) }}</template>
</el-table-column>
<el-table-column width="110" label="Action">
<template #default="scope">
<el-button type="default" plain size="small" @click.stop="goToListing(scope.row)">View</el-button>
</template>
</el-table-column>
</el-table>
</div>
</div>
</div>
</div>
</div>
</buyer-layout>
</template>

<style scoped>
.modern-panel {
border-radius: 12px;
box-shadow: 0 6px 22px rgba(15, 23, 42, 0.05);
overflow: hidden;
}

.modern-panel .card-header,
.modern-panel .card-inner,
.modern-panel .card-body {
border-radius: inherit;
}

.market-controls {
display: flex;
align-items: center;
gap: 0.5rem;
}

.market-search {
width: 280px;
}

.market-filter-btn :deep(.icon) {
margin-right: 0.35rem;
}

:deep(.clickable-market-table .el-table__row) {
cursor: pointer;
}

:deep(.modern-panel .el-table),
:deep(.modern-panel .el-table__inner-wrapper),
:deep(.modern-panel .el-table__header-wrapper),
:deep(.modern-panel .el-table__body-wrapper) {
border-radius: 12px;
}

:deep(.modern-panel .el-table__header-wrapper) {
border-top-left-radius: 12px;
border-top-right-radius: 12px;
}

:deep(.modern-panel .el-table__body-wrapper) {
border-bottom-left-radius: 12px;
border-bottom-right-radius: 12px;
}

.nk-order-ovwg-data.card.border {
border-radius: 12px;
padding: 0.875rem;
border: none;
transition: all 0.3s ease;
overflow: hidden;
backdrop-filter: blur(10px);
}

.nk-order-ovwg-data.card.border:hover {
transform: translateY(-4px) scale(1.01);
background: linear-gradient(135deg, #ffffff 0%, #fefefe 50%, #f8fafc 100%);
}

.nk-order-ovwg-data .amount {
font-size: 0.95rem;
font-weight: 800;
color: #000000;
margin-bottom: 0.6rem;
line-height: 1.2;
text-transform: uppercase;
}

.nk-order-ovwg-data .info {
margin-bottom: 0.5rem;
}

.nk-order-ovwg-data .info strong {
font-size: 1.5rem;
font-weight: 900;
color: #000000;
line-height: 1.1;
}

.nk-order-ovwg-data .title {
font-size: 0.8rem;
color: #000000;
font-weight: 600;
display: flex;
align-items: center;
gap: 0.4rem;
line-height: 1.3;
}

.nk-order-ovwg-data .title em {
color: #10b981;
font-size: 0.9rem;
}

.stat-card {
min-height: 122px;
}

@media (max-width: 768px) {
.market-controls {
width: 100%;
margin-top: 0.5rem;
}

.market-search {
width: 100%;
}

.card-header {
flex-direction: column;
align-items: flex-start !important;
}

.nk-order-ovwg-data.card.border {
padding: 0.75rem;
}

.nk-order-ovwg-data .amount {
font-size: 0.85rem;
}

.nk-order-ovwg-data .info strong {
font-size: 1.3rem;
}

.nk-order-ovwg-data .title {
font-size: 0.75rem;
}
}
</style>
