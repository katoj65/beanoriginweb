<script setup>
import { computed } from 'vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { ElNotification } from 'element-plus';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const page = usePage();
const batch = computed(() => page.props.batch ?? {});
const batchCommodities = computed(() => page.props.batch_commodities ?? []);
const batchActivities = computed(() => page.props.batch_activities ?? []);
const verifyForm = useForm({});

// Normalize batch status for UI checks and consistent display.
const statusValue = computed(() => String(batch.value?.status ?? '').toLowerCase());

// Disable verify action once batch is already tokenized.
const verifyDisabled = computed(() => ['tokenized', 'tokenised'].includes(statusValue.value));

// Build status badge class from current batch status.
const statusClass = computed(() => {
  if (statusValue.value === 'listed') return 'badge bg-warning-subtle text-warning text-capitalize';
  if (['tokenized', 'tokenised'].includes(statusValue.value)) return 'badge bg-success-subtle text-success text-capitalize';
  return 'badge bg-light text-dark text-capitalize';
});

// Format date values for readable output.
const formatDateTime = (value) => {
  if (!value) return 'N/A';
  const date = new Date(value);
  if (Number.isNaN(date.getTime())) return value;
  return date.toLocaleString();
};

// Format money values for display.
const formatMoney = (value) => {
  if (value === null || value === undefined || value === '') return 'N/A';
  const amount = Number(value);
  if (Number.isNaN(amount)) return value;
  return amount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
};

// Return to admin unverified token list.
const goBack = () => {
  router.get(route('admin.tokens.unverified'));
};

// Submit admin verification and move batch to tokenized status.
const verifyBatch = () => {
  if (!batch.value?.id || verifyDisabled.value) return;

  verifyForm.post(route('admin.batch.verify', { id: batch.value.id }), {
    preserveScroll: true,
    onSuccess: (response) => {
      const message = response?.props?.flash?.success || 'Batch verified successfully.';
      ElNotification({
        title: 'Successful',
        message,
        type: 'success',
        duration: 3200,
        position: 'top-right',
        offset: 84,
        showClose: true,
        customClass: 'theme-notification-success',
      });
    },
  });
};
</script>

<template>
<AdminLayout>
<div class="container">
<div class="card verification-shell">
<div class="card-inner border-bottom verification-head">
<div>
<h6 class="title mb-1"><em class="icon ni ni-shield-check mr-1"></em>Admin Batch Verification</h6>
<p class="sub-text mb-0">Confirm this listed batch and move it to tokenized status.</p>
</div>
<el-button-group>
<el-button @click="goBack">Back</el-button>
<el-button :loading="verifyForm.processing" :disabled="verifyDisabled" @click="verifyBatch">
Verify Batch
</el-button>
</el-button-group>
</div>

<div class="card-inner border-bottom">
<div class="details-grid">
<div class="detail-item">
<span class="sub-text">Batch ID</span>
<strong>{{ batch.id ?? 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text">Batch Code</span>
<strong>{{ batch.batch_code ?? 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text">Commodity Name</span>
<strong class="text-capitalize">{{ batch.commodity_name ?? 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text">Commodity Type</span>
<strong class="text-capitalize">{{ batch.commodity_type ?? 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text">Grade</span>
<strong class="text-capitalize">{{ batch.grade ?? 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text">Weight</span>
<strong>{{ batch.weight ?? 'N/A' }} kg</strong>
</div>
<div class="detail-item">
<span class="sub-text">Price</span>
<strong>{{ formatMoney(batch.price) }}</strong>
</div>
<div class="detail-item">
<span class="sub-text">Moisture</span>
<strong>{{ batch.moisture ?? 'N/A' }}%</strong>
</div>
<div class="detail-item">
<span class="sub-text">Warehouse</span>
<strong class="text-capitalize">{{ batch.warehouse ?? 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text">Created At</span>
<strong>{{ formatDateTime(batch.created_at) }}</strong>
</div>
<div class="detail-item">
<span class="sub-text">Owner</span>
<strong>{{ batch.owner_name ?? 'N/A' }}</strong>
<span class="sub-text">{{ batch.owner_email ?? '' }}</span>
</div>
<div class="detail-item">
<span class="sub-text">Status</span>
<strong><span :class="statusClass">{{ batch.status ?? 'N/A' }}</span></strong>
</div>
</div>
</div>

<div class="card-inner border-bottom">
<div class="activity-head">
<h6 class="title mb-0"><em class="icon ni ni-activity mr-1"></em>Batch Activities</h6>
<span class="activity-count">{{ batchActivities.length }} activities</span>
</div>

<div v-if="batchActivities.length" class="activity-list">
<el-timeline class="activity-timeline">
<el-timeline-item
v-for="item in batchActivities"
:key="item.id"
type="primary"
:timestamp="formatDateTime(item.created_at)"
placement="top"
>
<div class="activity-content text-capitalize">{{ item.activity }}</div>
</el-timeline-item>
</el-timeline>
</div>

<div v-else class="empty-activities">
No activities recorded for this batch yet.
</div>
</div>

<div class="card-inner border-bottom">
<div class="activity-head">
<h6 class="title mb-0"><em class="icon ni ni-package mr-1"></em>Commodities Attached To Batch</h6>
<span class="activity-count">{{ batchCommodities.length }} commodities</span>
</div>

<div v-if="batchCommodities.length" class="farm-table-wrap">
<el-table :data="batchCommodities" size="small" class="farm-table">
<el-table-column label="Commodity" min-width="190">
<template #default="{ row }">
<span class="text-capitalize">{{ row.commodity_name ?? `Commodity #${row.id}` }}</span>
</template>
</el-table-column>
<el-table-column label="Type" min-width="150">
<template #default="{ row }">
<span class="text-capitalize">{{ row.commodity_type ?? 'N/A' }}</span>
</template>
</el-table-column>
<el-table-column label="Grade" min-width="130">
<template #default="{ row }">
<span class="text-capitalize">{{ row.grade ?? 'N/A' }}</span>
</template>
</el-table-column>
<el-table-column label="Weight (kg)" width="130" align="right">
<template #default="{ row }">
{{ row.weight ?? 'N/A' }}
</template>
</el-table-column>
<el-table-column label="Status" min-width="120">
<template #default="{ row }">
<span class="text-capitalize">{{ row.status ?? 'N/A' }}</span>
</template>
</el-table-column>
</el-table>
</div>

<div v-else class="empty-activities">
No commodities are attached to this batch.
</div>
</div>

<div class="card-inner" v-if="$page.props.errors?.batch">
<div class="alert alert-warning mb-0">{{ $page.props.errors.batch }}</div>
</div>
</div>
</div>
</AdminLayout>
</template>

<style scoped>
.verification-shell {
  overflow: hidden;
}

.verification-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  flex-wrap: wrap;
}

.details-grid {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 12px;
}

.detail-item {
  background: #f8fafc;
  border-radius: 10px;
  padding: 12px;
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.farm-table-wrap {
  margin-top: 12px;
  border: 1px solid #dbe5f1;
  border-radius: 12px;
  overflow: hidden;
  background: #ffffff;
}

.farm-table :deep(.el-table__header th.el-table__cell) {
  background: #f8fafc;
  color: #526484;
  font-weight: 600;
}

.farm-table :deep(.el-table__body td.el-table__cell) {
  background: #ffffff;
}

.farm-table :deep(.el-table__inner-wrapper::before) {
  display: none;
}

.activity-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 10px;
  flex-wrap: wrap;
}

.activity-count {
  display: inline-flex;
  align-items: center;
  padding: 3px 10px;
  border-radius: 999px;
  border: 1px solid #dbe7ff;
  background: #f3f7ff;
  color: #1e40af;
  font-size: 11px;
  font-weight: 600;
  letter-spacing: 0.02em;
  text-transform: uppercase;
}

.activity-list {
  margin-top: 12px;
}

.activity-list :deep(.el-timeline) {
  padding-left: 0;
  margin: 0;
}

.activity-list :deep(.el-timeline-item) {
  padding-bottom: 12px;
}

.activity-list :deep(.el-timeline-item:last-child) {
  padding-bottom: 0;
}

.activity-list :deep(.el-timeline-item__tail) {
  left: 4px;
  border-left: 2px solid #dbe5f1;
}

.activity-list :deep(.el-timeline-item__node--normal) {
  left: 0;
  width: 10px;
  height: 10px;
  border: 0;
  background: var(--app-coffee-700);
  box-shadow: 0 0 0 3px rgba(111, 78, 55, 0.16);
}

.activity-list :deep(.el-timeline-item__wrapper) {
  top: -2px;
  padding-left: 18px;
}

.activity-list :deep(.el-timeline-item__timestamp) {
  font-size: 11.5px;
  line-height: 1.25;
  color: #64748b;
  margin-bottom: 2px;
}

.activity-content {
  font-size: 13px;
  font-weight: 600;
  color: #0f172a;
  line-height: 1.35;
}

.empty-activities {
  border: 1px dashed #d9e2f0;
  border-radius: 10px;
  background: #f8fafc;
  padding: 12px;
  color: #64748b;
  margin-top: 12px;
}

@media (max-width: 767px) {
  .details-grid {
    grid-template-columns: 1fr;
  }
}
</style>
