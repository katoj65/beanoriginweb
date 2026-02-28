<script setup>
import { computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const page = usePage();
const batches = computed(() => page.props.batches ?? []);
const totalWeight = computed(() =>
  batches.value.reduce((sum, item) => sum + (Number(item?.weight) || 0), 0)
);
const totalValue = computed(() =>
  batches.value.reduce((sum, item) => sum + (Number(item?.price) || 0), 0)
);

// Format money values for table display.
const formatMoney = (value) => {
  if (value === null || value === undefined || value === '') return 'N/A';
  const amount = Number(value);
  if (Number.isNaN(amount)) return value;
  return amount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
};

// Format numeric values with 2 decimals.
const formatNumber = (value) => {
  const amount = Number(value);
  if (Number.isNaN(amount)) return '0.00';
  return amount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
};

// Format date values for readable output.
const formatDateTime = (value) => {
  if (!value) return 'N/A';
  const date = new Date(value);
  if (Number.isNaN(date.getTime())) return value;
  return date.toLocaleString();
};

// Normalize status labels for table consistency.
const formatStatus = (value) => {
  const status = String(value ?? '').trim().toLowerCase();
  if (!status) return 'tokenized';
  return status === 'tokenised' ? 'tokenized' : status;
};

// Resolve owner name for display with email fallback.
const ownerText = (item) => {
  const name = String(item?.owner_name ?? '').trim();
  return name || item?.owner_email || 'N/A';
};

// Navigate back to admin dashboard.
const goBack = () => {
  router.get(route('admin.dashboard'));
};

// Navigate to admin unverified tokens page.
const goUnverified = () => {
  router.get(route('admin.tokens.unverified'));
};

// Open tokenized batch details page from marketplace table.
const openBatchDetails = (row) => {
  const batchId = row?.id ?? null;
  if (!batchId) return;
  router.get(route('admin.marketplace.show', { id: batchId }));
};
</script>

<template>
<AdminLayout>
<div class="container">
<div class="card card-bordered marketplace-shell">
<div class="card-inner border-bottom marketplace-head">
<div>
<h6 class="title mb-1"><em class="icon ni ni-store mr-1"></em>Admin Marketplace</h6>
<p class="sub-text mb-0">All batches currently in tokenized status.</p>
</div>
<el-button-group>
<el-button plain @click="goBack">Back</el-button>
<el-button plain @click="goUnverified">Verification Requests</el-button>
</el-button-group>
</div>

<div class="card-inner marketplace-body">
<div v-if="batches.length" class="market-table-wrap">
<div class="market-table-meta">
<span class="meta-pill"><strong>{{ batches.length }}</strong> Batches</span>
<span class="meta-pill"><strong>{{ formatNumber(totalWeight) }}</strong> kg Total Weight</span>
<span class="meta-pill"><strong>{{ formatMoney(totalValue) }}</strong> Total Price</span>
</div>

<el-table :data="batches" size="small" class="market-table" empty-text="No tokenized batches found in marketplace." highlight-current-row @row-click="openBatchDetails">
<el-table-column label="Batch" min-width="130">
<template #default="{ row }">
<span class="fw-semibold">{{ row.batch_code ?? `#${row.id}` }}</span>
</template>
</el-table-column>

<el-table-column label="Commodity" min-width="170">
<template #default="{ row }">
<div class="cell-stack">
<span class="text-capitalize">{{ row.commodity_name ?? 'N/A' }}</span>
<span class="sub-line text-capitalize">{{ row.commodity_type ?? 'N/A' }} | {{ row.grade ?? 'N/A' }}</span>
</div>
</template>
</el-table-column>

<el-table-column label="Weight (kg)" width="120" align="right">
<template #default="{ row }">
{{ formatNumber(row.weight) }}
</template>
</el-table-column>

<el-table-column label="Price" width="130" align="right">
<template #default="{ row }">
{{ formatMoney(row.price) }}
</template>
</el-table-column>

<el-table-column label="Warehouse" min-width="140">
<template #default="{ row }">
<span class="text-capitalize">{{ row.warehouse ?? 'N/A' }}</span>
</template>
</el-table-column>

<el-table-column label="Owner" min-width="170">
<template #default="{ row }">
<div class="cell-stack">
<span>{{ ownerText(row) }}</span>
<span class="sub-line">{{ row.owner_email ?? 'N/A' }}</span>
</div>
</template>
</el-table-column>

<el-table-column label="Status" width="120" align="center">
<template #default="{ row }">
<el-tag type="success" effect="light" class="text-capitalize">{{ formatStatus(row.status) }}</el-tag>
</template>
</el-table-column>

<el-table-column label="Created At" min-width="170">
<template #default="{ row }">
<span class="sub-line-dark">{{ formatDateTime(row.created_at) }}</span>
</template>
</el-table-column>
</el-table>
</div>

<div v-else class="empty-state">
No tokenized batches found in marketplace.
</div>
</div>
</div>
</div>
</AdminLayout>
</template>

<style scoped>
.marketplace-shell {
  overflow: hidden;
}

.marketplace-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  flex-wrap: wrap;
}

.marketplace-body {
  padding: 0 !important;
}

.market-table-wrap {
  background: #ffffff;
}

.market-table-meta {
  display: flex;
  align-items: center;
  gap: 8px;
  flex-wrap: wrap;
  padding: 10px 12px;
  border-bottom: 1px solid #e2e8f0;
  background: #ffffff;
}

.meta-pill {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  border: 1px solid #dbe5f1;
  border-radius: 999px;
  padding: 4px 10px;
  font-size: 12px;
  color: #526484;
  background: #f8fafc;
}

.cell-stack {
  display: flex;
  flex-direction: column;
  gap: 2px;
  line-height: 1.2;
}

.sub-line {
  font-size: 12px;
  color: #64748b;
}

.sub-line-dark {
  color: #334155;
}

.market-table :deep(.el-table__header th.el-table__cell) {
  background: #f8fafc;
  color: #526484;
  font-weight: 600;
}

.market-table :deep(.el-table__cell) {
  padding-top: 10px;
  padding-bottom: 10px;
}

.market-table :deep(.el-table__body td.el-table__cell) {
  background: #ffffff;
}

.market-table :deep(.el-table__body .el-table__row) {
  cursor: pointer;
}

.market-table :deep(.el-table__inner-wrapper::before) {
  display: none;
}

.empty-state {
  border: 1px dashed #d9e2f0;
  border-radius: 10px;
  background: #f8fafc;
  padding: 16px 24px;
  color: #64748b;
}
</style>
