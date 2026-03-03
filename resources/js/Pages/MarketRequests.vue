<script setup>
import { computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';
import { Back } from '@element-plus/icons-vue';

const page = usePage();
const requests = computed(() => page.props.requests ?? []);

const goToMarket = () => {
router.get(route('market.index'));
};

const openBatch = (id) => {
if (!id) return;
router.get(route('market.show', { id }));
};

const formatDateTime = (value) => {
if (!value) return 'N/A';
const date = new Date(value);
if (Number.isNaN(date.getTime())) return value;
return date.toLocaleString();
};
</script>

<template>
<CooperativeLayout>
<div class="container">
<div class="card card-bordered requests-card">
<div class="card-inner border-bottom requests-head">
<div>
<h6 class="title mb-1"><em class="icon ni ni-mail"></em> Request</h6>
<p class="sub-text mb-0">Requests submitted against your market batches.</p>
</div>
<el-button plain :icon="Back" @click="goToMarket">Back To Market</el-button>
</div>

<div class="card-inner requests-table-body">
<div v-if="requests.length" class="table-responsive">
<table class="table table-sm table-middle mb-0 requests-table">
<thead>
<tr>
<th>Batch</th>
<th>Commodity</th>
<th>Type</th>
<th>Activity</th>
<th>Buyer</th>
<th>Email</th>
<th>Created At</th>
</tr>
</thead>
<tbody>
<tr v-for="item in requests" :key="item.id" class="request-row-clickable" @click="openBatch(item.batch_id)">
<td>{{ item.batch_code ?? `#${item.batch_id}` }}</td>
<td class="text-capitalize">{{ item.commodity_name ?? 'N/A' }}</td>
<td class="text-capitalize">{{ item.commodity_type ?? 'N/A' }}</td>
<td class="text-capitalize">{{ item.activity ?? 'N/A' }}</td>
<td>{{ item.buyer_name || 'N/A' }}</td>
<td>{{ item.buyer_email || 'N/A' }}</td>
<td>{{ formatDateTime(item.created_at) }}</td>
</tr>
</tbody>
</table>
</div>

<div v-else class="empty-state">
No request available.
</div>
</div>
</div>
</div>
</CooperativeLayout>
</template>

<style scoped>
.requests-card {
border-radius: 12px;
}

.requests-head {
display: flex;
align-items: center;
justify-content: space-between;
gap: 12px;
flex-wrap: wrap;
}

.requests-table-body {
padding: 0 !important;
}

.requests-table {
width: 100%;
margin: 0;
}

.requests-table th {
background: #f8fafc;
color: #526484;
font-weight: 600;
white-space: nowrap;
}

.requests-table td {
white-space: nowrap;
}

.request-row-clickable {
cursor: pointer;
}

.request-row-clickable:hover {
background: #f8fafc;
}

.empty-state {
border: 1px dashed #d9e2f0;
border-radius: 10px;
background: #f8fafc;
padding: 16px 24px;
color: #64748b;
margin: 16px;
}
</style>
