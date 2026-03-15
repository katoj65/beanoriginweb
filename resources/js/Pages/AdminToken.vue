<script setup>
import { computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const page = usePage();
const tokens = computed(() => page.props.tokens ?? []);

// Format money values for table display.
const formatMoney = (value) => {
  if (value === null || value === undefined || value === '') return 'N/A';
  const amount = Number(value);
  if (Number.isNaN(amount)) return value;
  return amount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
};

// Format date values for readable output.
const formatDateTime = (value) => {
  if (!value) return 'N/A';
  const date = new Date(value);
  if (Number.isNaN(date.getTime())) return value;
  return date.toLocaleString();
};

// Return a short hash to keep table rows compact.
const shortHash = (value) => {
  const text = String(value ?? '');
  if (text.length <= 14) return text || 'N/A';
  return `${text.slice(0, 8)}...${text.slice(-6)}`;
};

// Resolve owner text from payload values.
const ownerText = (item) => {
  const name = String(item?.owner_name ?? '').trim();
  if (name) return name;
  return item?.owner_email ?? 'N/A';
};

// Navigate back to admin dashboard.
const goBack = () => {
  router.get(route('admin.dashboard'));
};
</script>

<template>
<AdminLayout>
<div class="admin-token-page">
<div class="card admin-token-card">
<div class="card-inner border-bottom admin-token-head">
<div>
<h6 class="title mb-1"><em class="icon ni ni-coins mr-1"></em>Unverified Tokens</h6>
<p class="sub-text mb-0">Tokens pending verification by admin.</p>
</div>
<el-button plain @click="goBack">Back</el-button>
</div>

<div class="card-inner admin-token-body">
<div v-if="tokens.length" class="table-responsive">
<table class="table table-sm table-middle mb-0 admin-token-table">
<thead>
<tr>
<th>#</th>
<th>Batch</th>
<th>Commodity</th>
<th>Weight</th>
<th>Price</th>
<th>Owner</th>
<th>Created At</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>
<tbody>
<tr v-for="item in tokens" :key="item.id">
<td>{{ item.token_index ?? item.id }}</td>
<td>{{ item.batch_code ?? `#${item.batch_id ?? 'N/A'}` }}</td>
<td class="text-capitalize">{{ item.commodity_name ?? 'N/A' }}</td>
<td>{{ item.weight ?? 'N/A' }} kg</td>
<td>{{ formatMoney(item.price) }}</td>
<td>{{ ownerText(item) }}</td>
<td>{{ formatDateTime(item.created_at) }}</td>
<td><span class="badge bg-warning text-dark text-capitalize">{{ item.status ?? 'pending' }}</span></td>
<td>
<el-button size="small" @click="router.get(route('admin.batch.verification', { id: item.batch_id ?? item.id }))">
Verify
</el-button>
</td>
</tr>
</tbody>
</table>
</div>

<div v-else class="empty-state">
No unverified tokens found.
</div>
</div>
</div>
</div>
</AdminLayout>
</template>

<style scoped>
.admin-token-page {
  width: 100%;
}

.admin-token-card {
  border-radius: 12px;
  width: 100%;
}

.admin-token-head {
display: flex;
align-items: center;
justify-content: space-between;
gap: 12px;
flex-wrap: wrap;
}

.admin-token-body {
padding: 0 !important;
}

.admin-token-body .table-responsive {
margin: 0;
width: 100%;
}

.admin-token-table {
width: 100%;
margin: 0;
}

.admin-token-table th {
background: #f8fafc;
color: #526484;
font-weight: 600;
white-space: nowrap;
}

.admin-token-table td {
white-space: nowrap;
}

.empty-state {
border: 1px dashed #d9e2f0;
border-radius: 10px;
background: #f8fafc;
padding: 16px 24px;
color: #64748b;
}
</style>
