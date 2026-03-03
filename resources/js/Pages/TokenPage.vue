<script setup>
import { computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';
import { Back, Clock, ShoppingCart } from '@element-plus/icons-vue';

const page = usePage();
const batches = computed(() => page.props.batches ?? []);

// Format amount values consistently for list display.
const formatMoney = (value) => {
if (value === null || value === undefined || value === '') return 'N/A';
const amount = Number(value);
if (Number.isNaN(amount)) return value;
return amount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
};

// Format date and time values for readable table output.
const formatDateTime = (value) => {
if (!value) return 'N/A';
const date = new Date(value);
if (Number.isNaN(date.getTime())) return value;
return date.toLocaleString();
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
router.get(route('market.show', { id: batchId }));
};
</script>

<template>
<CooperativeLayout>
<div class="container">
<div class="card card-bordered token-page-card">
<div class="card-inner border-bottom token-head">
<div>
<h6 class="title mb-1"><em class="icon ni ni-coins mr-1"></em>
 Batch  Market </h6>
<p class="sub-text mb-0">Batches available on the market.</p>
</div>
<el-button-group>
<el-button plain :icon="Back" @click="goBack">
Back
</el-button>
<el-button plain :icon="Clock" @click="goToReservedMarket">
Reserved
</el-button>
<el-button plain :icon="ShoppingCart" @click="goToBoughtMarket">
Bought
</el-button>
<!-- <el-button plain @click="goToMyTokens">
My Tokens
</el-button> -->
</el-button-group>
</div>

<div class="card-inner token-table-body">
<div v-if="batches.length" class="table-responsive">
<table class="table table-sm table-middle mb-0 token-table">
<thead>
<tr>
<th><span class="table-head-label"><em class="icon ni ni-hash"></em>Batch</span></th>
<th><span class="table-head-label"><em class="icon ni ni-growth"></em>Commodity</span></th>
<th><span class="table-head-label"><em class="icon ni ni-box-view"></em>Type</span></th>
<th><span class="table-head-label"><em class="icon ni ni-award"></em>Grade</span></th>
<th><span class="table-head-label"><em class="icon ni ni-package"></em>Weight</span></th>
<th><span class="table-head-label"><em class="icon ni ni-coins"></em>Price</span></th>
<th><span class="table-head-label"><em class="icon ni ni-home-fill"></em>Warehouse</span></th>
<th><span class="table-head-label"><em class="icon ni ni-calendar"></em>Created At</span></th>
</tr>
</thead>
<tbody>
<tr v-for="item in batches" :key="item.id" class="token-row-clickable" @click="openBatchDetails(item.id)">
<td>{{ item.batch_code ?? `#${item.id}` }}</td>
<td class="text-capitalize">{{ item.commodity_name ?? 'N/A' }}</td>
<td class="text-capitalize">{{ item.commodity_type ?? 'N/A' }}</td>
<td class="text-capitalize">{{ item.grade ?? 'N/A' }}</td>
<td>{{ item.weight ?? 'N/A' }} kg</td>
<td>{{ formatMoney(item.price) }}</td>
<td class="text-capitalize">{{ item.warehouse ?? 'N/A' }}</td>
<td>{{ formatDateTime(item.created_at) }}</td>
</tr>
</tbody>
</table>
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
align-items: center;
justify-content: space-between;
gap: 12px;
flex-wrap: wrap;
}

.token-table-body {
padding: 0 !important;
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
</style>
