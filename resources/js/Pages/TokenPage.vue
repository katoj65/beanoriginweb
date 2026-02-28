<script setup>
import { computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';

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
router.get(route('cooperative.produce'));
};
</script>

<template>
<CooperativeLayout>
<div class="container">
<div class="card card-bordered token-page-card">
<div class="card-inner border-bottom token-head">
<div>
<h6 class="title mb-1"><em class="icon ni ni-coins mr-1"></em>Tokenized Batches</h6>
<p class="sub-text mb-0">Batches with status marked as tokenized.</p>
</div>
<el-button plain @click="goBack">Back</el-button>
</div>

<div class="card-inner">
<div v-if="batches.length" class="table-responsive">
<table class="table table-sm table-middle mb-0 token-table">
<thead>
<tr>
<th>Batch</th>
<th>Commodity</th>
<th>Type</th>
<th>Grade</th>
<th>Weight</th>
<th>Price</th>
<th>Warehouse</th>
<th>Status</th>
<th>Created At</th>
</tr>
</thead>
<tbody>
<tr v-for="item in batches" :key="item.id">
<td>{{ item.batch_code ?? `#${item.id}` }}</td>
<td class="text-capitalize">{{ item.commodity_name ?? 'N/A' }}</td>
<td class="text-capitalize">{{ item.commodity_type ?? 'N/A' }}</td>
<td class="text-capitalize">{{ item.grade ?? 'N/A' }}</td>
<td>{{ item.weight ?? 'N/A' }} kg</td>
<td>{{ formatMoney(item.price) }}</td>
<td class="text-capitalize">{{ item.warehouse ?? 'N/A' }}</td>
<td><span class="badge bg-success-subtle text-success text-capitalize">{{ formatStatus(item.status) }}</span></td>
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

.token-table th {
background: #f8fafc;
color: #526484;
font-weight: 600;
white-space: nowrap;
}

.token-table td {
white-space: nowrap;
}

.empty-state {
border: 1px dashed #d9e2f0;
border-radius: 10px;
background: #f8fafc;
padding: 14px;
color: #64748b;
}
</style>
