<script setup>
import { computed, ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';
import HarvestData from '@/Batch/HarvestData.vue';
import LabMetricsData from '@/Batch/LabMetricsData.vue';
import QRCodeData from '@/Batch/QRCodeData.vue';


const page = usePage();
const batch = computed(() => page.props.batch?.data ?? page.props.batch ?? {});
const attachedCommodities = computed(() => page.props.attached_commodities ?? []);
const batchActivities = computed(() => page.props.batch_activities ?? []);
const batchProcessingData = computed(() => page.props.batch_processing_data ?? []);
const batchLabMetricsMetadata = computed(() => page.props.batch_lab_metrics_metadata ?? []);
const batchLabMetrics = computed(() => page.props.batch_lab_metrics ?? []);
const batchQrCodeData = computed(() => page.props.batch_qr_code_data ?? []);
const can = computed(() => page.props.can ?? {});
const timelineActivities = computed(() => {
const activities = Array.isArray(batchActivities.value) ? [...batchActivities.value] : [];
const createdAt = batch.value?.created_at ?? null;

if (createdAt) {
activities.push({
id: 'batch-created',
activity: 'batch created',
created_at: createdAt,
});
}

return activities;
});

const statusClass = computed(() => {
const status = String(batch.value?.status ?? '').toLowerCase();
if (status === 'listed') return 'badge bg-success-subtle text-success';
if (status === 'sold') return 'badge bg-info-subtle text-info';
if (status === 'processing' || status === 'processed') return 'badge bg-warning-subtle text-warning';
return 'badge bg-light text-dark';
});

const formatMoney = (value) => {
if (value === null || value === undefined || value === '') return 'N/A';
const amount = Number(value);
if (Number.isNaN(amount)) return value;
return amount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
};

const formatDateTime = (value) => {
if (!value) return 'N/A';
const date = new Date(value);
if (Number.isNaN(date.getTime())) return value;
return date.toLocaleString();
};

const activeVerificationTab = ref('commodities');



</script>

<template>
<CooperativeLayout>

<div class="batch-page-shell">
<div class="card verification-shell">
<div class="card-inner verification-head">
<div>
<h6 class="title mb-1 font-large"><em class="icon ni ni-shield-check mr-1"></em>Batch</h6>
<p class="sub-text mb-0">Batch details and specifications</p>
</div>

</div>

<div class="card-inner">
<div class="batch-details-head mb-3">
<div>
<h6 class="title mb-1"><em class="icon ni ni-layers mr-1"></em>Batch Details</h6>
<p class="sub-text mb-0">Core batch profile, quality indicators, and listing information for this lot.</p>
</div>
<span class="batch-details-pill">Snapshot</span>
</div>
<div class="details-grid">
<div class="detail-item">
<span class="detail-item-label"><em class="icon ni ni-hash mr-1"></em>Batch ID</span>
<strong>{{ batch.id ?? 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="detail-item-label"><em class="icon ni ni-tag mr-1"></em>Batch Code</span>
<strong>{{ batch.batch_code ?? 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="detail-item-label"><em class="icon ni ni-growth mr-1"></em>Commodity Name</span>
<strong class="text-capitalize">{{ batch.commodity_name ?? 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="detail-item-label"><em class="icon ni ni-property mr-1"></em>Commodity Type</span>
<strong class="text-capitalize">{{ batch.commodity_type ?? 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="detail-item-label"><em class="icon ni ni-package mr-1"></em>Weight</span>
<strong>{{ batch.weight ?? 'N/A' }} kg</strong>
</div>
<div class="detail-item">
<span class="detail-item-label"><em class="icon ni ni-award mr-1"></em>Grade</span>
<strong class="text-capitalize">{{ batch.grade ?? 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="detail-item-label"><em class="icon ni ni-drop mr-1"></em>Moisture</span>
<strong>{{ batch.moisture ?? 'N/A' }}%</strong>
</div>
<div class="detail-item">
<span class="detail-item-label"><em class="icon ni ni-home-fill mr-1"></em>Warehouse</span>
<strong class="text-capitalize">{{ batch.warehouse ?? 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="detail-item-label"><em class="icon ni ni-coins mr-1"></em>Price</span>
<strong>{{ formatMoney(batch.price) }}</strong>
</div>
<div class="detail-item">
<span class="detail-item-label"><em class="icon ni ni-calendar mr-1"></em>Created At</span>
<strong>{{ formatDateTime(batch.created_at) }}</strong>
</div>
<div class="detail-item detail-item-full">
<span class="detail-item-label"><em class="icon ni ni-flag mr-1"></em>Status</span>
<strong><span :class="statusClass">{{ batch.status ?? 'created' }}</span></strong>
</div>
</div>
</div>





<div class="card-inner">
<el-tabs v-model="activeVerificationTab" class="app-themed-tabs">
<el-tab-pane name="commodities">
<template #label>
<span class="verification-tab-label"><em class="icon ni ni-package mr-1"></em>Harvests Data</span>
</template>
<harvest-data :batch-id="batch.id" :is-owner="can.is_owner" :commodities="attachedCommodities" />
</el-tab-pane>

<el-tab-pane name="lab_metrics">
<template #label>
<span class="verification-tab-label"><em class="icon ni ni-bar-chart-fill mr-1"></em>Lab Metrics</span>
</template>
<lab-metrics-data :metrics="batchLabMetrics" :metadata="batchLabMetricsMetadata" />
</el-tab-pane>

<el-tab-pane name="token">
<template #label>
<span class="verification-tab-label"><em class="icon ni ni-coins mr-1"></em>Token</span>
</template>
<!-- <batch-token-data /> -->
</el-tab-pane>

<el-tab-pane name="qr_code_data">
<template #label>
<span class="verification-tab-label"><em class="icon ni ni-qr mr-1"></em>QR Code Data</span>
</template>
<qr-code-data :qr-code-data="batchQrCodeData" />
</el-tab-pane>
</el-tabs>
</div>


</div>
</div>
</CooperativeLayout>
</template>

<style scoped>
.batch-page-shell {
width: 100%;
max-width: none;
padding: 0;
margin: 0;
}

.verification-shell {
overflow: hidden;
margin: 0;
border-radius: 0;
border: 0;
box-shadow: none;
}

.verification-head {
display: flex;
align-items: center;
justify-content: space-between;
gap: 12px;
flex-wrap: wrap;
}

.verification-tab-label {
display: inline-flex;
align-items: center;
font-weight: 600;
}

.verification-head-actions {
display: inline-flex;
align-items: center;
justify-content: flex-end;
gap: 10px;
margin-left: auto;
}

.batch-details-head {
display: flex;
align-items: flex-start;
justify-content: space-between;
gap: 16px;
padding: 2px 2px 4px;
}

.batch-details-pill {
display: inline-flex;
align-items: center;
padding: 8px 12px;
border-radius: 999px;
background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
border: 1px solid #e7edf5;
color: #475569;
font-size: 12px;
font-weight: 600;
white-space: nowrap;
box-shadow: 0 1px 0 rgba(255, 255, 255, 0.95) inset;
}

.batch-owner-indicator {
display: inline-flex;
align-items: center;
justify-content: center;
width: 34px;
height: 34px;
padding: 6px 10px;
border-radius: 999px;
background: #ecfdf3;
border: 1px solid #bbf7d0;
color: #166534;
font-size: 16px;
}

.details-grid {
display: grid;
grid-template-columns: repeat(3, minmax(0, 1fr));
gap: 12px;
}

.detail-item {
position: relative;
background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
border: 1px solid #e7edf5;
border-radius: 16px;
padding: 16px;
display: flex;
flex-direction: column;
gap: 8px;
box-shadow:
0 1px 0 rgba(255, 255, 255, 0.98) inset,
0 8px 18px rgba(15, 23, 42, 0.03);
overflow: hidden;
transition: transform 0.18s ease, border-color 0.2s ease, box-shadow 0.2s ease;
}

.detail-item::before {
content: "";
position: absolute;
inset: 0 0 auto 0;
height: 48px;
background: linear-gradient(90deg, rgba(59, 130, 246, 0.045) 0%, rgba(16, 185, 129, 0.025) 100%);
pointer-events: none;
}

.detail-item:hover {
transform: translateY(-1px);
border-color: #d8e2ec;
box-shadow:
0 1px 0 rgba(255, 255, 255, 1) inset,
0 12px 24px rgba(15, 23, 42, 0.045);
}

.detail-item-label {
display: inline-flex;
align-items: center;
font-size: 11px;
font-weight: 700;
letter-spacing: 0.06em;
text-transform: uppercase;
color: #7c8aa5;
position: relative;
z-index: 1;
}

.detail-item strong {
position: relative;
z-index: 1;
font-size: 17px;
line-height: 1.35;
color: #162033;
letter-spacing: -0.015em;
font-weight: 700;
text-wrap: balance;
}

.detail-item-full {
grid-column: span 2;
}

.prompt-box {
border: 1px dashed #d9e2f0;
border-radius: 10px;
background: #f8fafc;
padding: 14px;
}

.activity-log-list {
margin-top: 12px;
}

.activity-log-head {
display: flex;
align-items: center;
justify-content: space-between;
gap: 10px;
flex-wrap: wrap;
}

.activity-log-count {
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

.activity-log-list :deep(.el-timeline) {
padding-left: 0;
margin: 0;
}

.activity-log-list :deep(.el-timeline-item) {
padding-bottom: 12px;
}

.activity-log-list :deep(.el-timeline-item:last-child) {
padding-bottom: 0;
}

.activity-log-list :deep(.el-timeline-item__tail) {
left: 4px;
border-left: 2px solid #dbe5f1;
}

.activity-log-list :deep(.el-timeline-item__node--normal) {
left: 0;
width: 10px;
height: 10px;
border: 0;
background: var(--app-coffee-700);
box-shadow: 0 0 0 3px rgba(111, 78, 55, 0.16);
}

.activity-log-list :deep(.el-timeline-item__wrapper) {
top: -2px;
padding-left: 18px;
}

.activity-log-list :deep(.el-timeline-item__timestamp) {
font-size: 11.5px;
line-height: 1.25;
color: #64748b;
margin-bottom: 2px;
}

.activity-timeline-content {
font-size: 13px;
font-weight: 600;
color: #0f172a;
line-height: 1.35;
}

.empty-activity-log {
border: 1px dashed #d9e2f0;
border-radius: 10px;
background: #f8fafc;
padding: 12px;
color: #64748b;
}

@media (max-width: 767px) {
.batch-details-head {
flex-direction: column;
}

.details-grid {
grid-template-columns: 1fr;
}

.detail-item-full {
grid-column: span 1;
}
}

.verification-head :deep(.more-dropdown-button) {
margin-left: -1px;
border-top-left-radius: 0;
border-bottom-left-radius: 0;
box-shadow: inset 1px 0 0 #dcdfe6;
}

.activity-dialog-header h5 {
font-size: 1.1rem;
font-weight: 700;
margin: 0;
color: #0f172a;
}

.activity-dialog-header {
display: flex;
align-items: flex-start;
gap: 12px;
}

.activity-dialog-icon {
width: 38px;
height: 38px;
border-radius: 12px;
display: inline-flex;
align-items: center;
justify-content: center;
background: linear-gradient(180deg, #fff7ed 0%, #ffedd5 100%);
color: #c2410c;
font-size: 18px;
flex-shrink: 0;
}

.activity-dialog-body {
display: flex;
flex-direction: column;
gap: 18px;
}

.activity-context {
display: grid;
grid-template-columns: repeat(2, minmax(0, 1fr));
gap: 12px;
}

.context-item {
background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
border: 1px solid #dbe5f1;
border-radius: 12px;
padding: 14px;
display: flex;
flex-direction: column;
gap: 4px;
}

.activity-form-block {
background: #ffffff;
border: 1px solid #e2e8f0;
border-radius: 12px;
padding: 14px;
}

.activity-dialog-actions {
display: flex;
justify-content: flex-end;
gap: 10px;
margin-top: 8px;
padding-top: 16px;
border-top: 1px solid #e2e8f0;
}

.activity-dialog :deep(.el-dialog__header) {
padding: 24px 26px 14px;
border-bottom: 1px solid #e2e8f0;
background: linear-gradient(180deg, #f8fbff 0%, #ffffff 100%);
}

.activity-dialog :deep(.el-dialog__body) {
padding: 20px 26px 24px;
}

.delete-dialog-header {
display: flex;
align-items: flex-start;
gap: 12px;
}

.delete-dialog-header h5 {
margin: 0;
font-size: 1.05rem;
font-weight: 700;
color: #7f1d1d;
}

.delete-dialog-icon {
width: 36px;
height: 36px;
border-radius: 11px;
display: inline-flex;
align-items: center;
justify-content: center;
background: #fef2f2;
color: #dc2626;
font-size: 18px;
flex-shrink: 0;
}

.delete-dialog-body {
padding: 2px 2px 6px;
color: #475569;
line-height: 1.55;
}

.delete-dialog-actions {
display: flex;
justify-content: flex-end;
gap: 10px;
}

.delete-dialog :deep(.el-dialog__header) {
padding: 20px 22px 10px;
border-bottom: 1px solid #fee2e2;
background: linear-gradient(180deg, #fff7f7 0%, #ffffff 100%);
}

.delete-dialog :deep(.el-dialog__body) {
padding: 16px 22px 10px;
}

.delete-dialog :deep(.el-dialog__footer) {
padding: 12px 22px 18px;
border-top: 1px solid #fee2e2;
}

@media (max-width: 767px) {
.activity-context {
grid-template-columns: 1fr;
}

.activity-log-list :deep(.el-timeline-item) {
padding-bottom: 10px;
}

.activity-log-list :deep(.el-timeline-item__wrapper) {
padding-left: 14px;
}

.activity-log-list :deep(.el-timeline-item__timestamp) {
white-space: normal;
}

.activity-dialog-actions {
flex-direction: column-reverse;
padding-top: 14px;
}

.activity-dialog-actions :deep(.el-button) {
width: 100%;
}

.activity-dialog :deep(.el-dialog__header) {
padding: 18px 18px 12px;
}

.activity-dialog :deep(.el-dialog__body) {
padding: 14px 18px 18px;
}

.delete-dialog :deep(.el-dialog__header) {
padding: 16px 16px 10px;
}

.delete-dialog :deep(.el-dialog__body) {
padding: 12px 16px 8px;
}

.delete-dialog :deep(.el-dialog__footer) {
padding: 10px 16px 14px;
}

.delete-dialog-actions :deep(.el-button) {
min-width: 96px;
}
}
</style>
