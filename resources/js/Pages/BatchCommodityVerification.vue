<script setup>
import { computed, ref } from 'vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';
import AddCommodityToBatch from '@/Components/AddCommodityToBatch.vue';
import AddBatchTradeActivity from '@/Components/AddBatchTradeActivity.vue';
import AddBatchProcess from '@/Components/AddBatchProcess.vue';
import AddToken from '@/Components/AddToken.vue';
import { ElNotification } from 'element-plus';
import { Back, Plus, MoreFilled, Edit, Delete } from '@element-plus/icons-vue';

const page = usePage();
const batch = computed(() => page.props.batch?.data ?? page.props.batch ?? {});
const attachedCommodities = computed(() => page.props.attached_commodities ?? []);
const batchActivities = computed(() => page.props.batch_activities ?? []);
const batchProcessingMetadata = computed(() => page.props.batch_processing_metadata ?? []);
const batchTradeActivityData = computed(() => page.props.batch_trade_activity_data ?? []);
const batchBlocks = computed(() => page.props.batch_blocks ?? []);
const activityOptions = computed(() => page.props.batch_status_list ?? []);
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

// Prevent tokenize action once the batch is already listed.
const isTokenizeDisabled = computed(() => {
const status = String(batch.value?.status ?? '').toLowerCase();
return status === 'listed';
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

const goBack = () => {
router.get(route('cooperative.produce'));
};

const addActivityModalVisible = ref(false);
const deleteDialogVisible = ref(false);
const deletingBatch = ref(false);
const tokenizingBatch = ref(false);
const activityForm = useForm({
activity: '',
});

const closeActivityModal = () => {
addActivityModalVisible.value = false;
activityForm.clearErrors();
};

const closeDeleteDialog = () => {
if (deletingBatch.value) return;
deleteDialogVisible.value = false;
};

const confirmDeleteBatch = () => {
const batchId = batch.value?.id ?? page.props.batch?.id ?? null;
if (!batchId) return;

router.delete(route('batch.destroy', { id: batchId }), {
preserveScroll: true,
onStart: () => {
deletingBatch.value = true;
},
onFinish: () => {
deletingBatch.value = false;
},
});
};

const handleMoreCommand = (command) => {
if (command === 'activity') {
activityForm.clearErrors();
addActivityModalVisible.value = true;
return;
}

if (command === 'edit') {
const batchId = batch.value?.id ?? page.props.batch?.id ?? null;
if (!batchId) return;
router.get(route('commodity.batch.edit', { id: batchId }));
return;
}

if (command === 'delete') {
deleteDialogVisible.value = true;
}
};

const submitActivity = () => {
const batchId = batch.value?.id ?? page.props.batch?.id ?? null;
if (!batchId) return;

activityForm.post(route('commodity.batch.activities.store', { id: batchId }), {
preserveScroll: true,
onSuccess: (response) => {
const message = response?.props?.flash?.success || 'Batch activity added successfully.';
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
closeActivityModal();
activityForm.reset('activity');
},
});
};

// Trigger tokenize flow via token controller route and refresh batch verification page.
const tokenizeBatch = () => {
const batchId = batch.value?.id ?? page.props.batch?.id ?? null;
if (!batchId || tokenizingBatch.value) return;

router.post(route('token.batch.tokenize', { id: batchId }), {}, {
preserveScroll: true,
onStart: () => {
tokenizingBatch.value = true;
},
onSuccess: (response) => {
const message = response?.props?.flash?.success || 'Batch tokenized successfully.';
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
onFinish: () => {
tokenizingBatch.value = false;
},
});
};






//
const commodityBatchState=computed(()=>{
const data=page.props.attached_commodities.length;
return data>0? true : false;
});

const activeVerificationTab = ref('commodities');







</script>

<template>
<CooperativeLayout>


<div class="container">
<div class="card card-bordered verification-shell">
<div class="card-inner border-bottom verification-head">
<div>
<h6 class="title mb-1"><em class="icon ni ni-shield-check mr-1"></em>Batch Commodity Verification</h6>
<p class="sub-text mb-0">Review this batch and proceed to add commodities under it.</p>
</div>

<el-button-group>
<el-button :icon="Back" @click="goBack">Back</el-button>
<el-dropdown trigger="click" class="more-dropdown-trigger" :disabled="isTokenizeDisabled" @command="handleMoreCommand">
<el-button :icon="MoreFilled" class="more-dropdown-button" :disabled="isTokenizeDisabled" />
<template #dropdown>
<el-dropdown-menu>
<el-dropdown-item :icon="Edit" command="edit">Edit</el-dropdown-item>
<el-dropdown-item :icon="Delete" command="delete">Delete</el-dropdown-item>
</el-dropdown-menu>
</template>
</el-dropdown>
</el-button-group>
</div>

<div class="card-inner border-bottom">
<h6 class="title mb-3"><em class="icon ni ni-layers mr-1"></em>Batch Details</h6>
<div class="details-grid">
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-hash mr-1"></em>Batch ID</span>
<strong>{{ batch.id ?? 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-tag mr-1"></em>Batch Code</span>
<strong>{{ batch.batch_code ?? 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-growth mr-1"></em>Commodity Name</span>
<strong class="text-capitalize">{{ batch.commodity_name ?? 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-property mr-1"></em>Commodity Type</span>
<strong class="text-capitalize">{{ batch.commodity_type ?? 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-package mr-1"></em>Weight</span>
<strong>{{ batch.weight ?? 'N/A' }} kg</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-award mr-1"></em>Grade</span>
<strong class="text-capitalize">{{ batch.grade ?? 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-drop mr-1"></em>Moisture</span>
<strong>{{ batch.moisture ?? 'N/A' }}%</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-home-fill mr-1"></em>Warehouse</span>
<strong class="text-capitalize">{{ batch.warehouse ?? 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-coins mr-1"></em>Price</span>
<strong>{{ formatMoney(batch.price) }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-calendar mr-1"></em>Created At</span>
<strong>{{ formatDateTime(batch.created_at) }}</strong>
</div>
<div class="detail-item detail-item-full">
<span class="sub-text"><em class="icon ni ni-flag mr-1"></em>Status</span>
<strong><span :class="statusClass">{{ batch.status ?? 'created' }}</span></strong>
</div>
</div>
</div>

<div class="card-inner border-bottom">
<el-tabs v-model="activeVerificationTab" class="app-themed-tabs">
<el-tab-pane name="commodities">
<template #label>
<span class="verification-tab-label"><em class="icon ni ni-package mr-1"></em>Batch Harvests</span>
</template>
<add-commodity-to-batch :batch-id="batch.id" :commodities="attachedCommodities" />
</el-tab-pane>

<el-tab-pane name="batch_processing">
<template #label>
<span class="verification-tab-label"><em class="icon ni ni-setting-alt mr-1"></em>Batch Processing</span>
</template>
<add-batch-process/>
</el-tab-pane>

<el-tab-pane name="batch_activities">
<template #label>
<span class="verification-tab-label"><em class="icon ni ni-activity mr-1"></em>Batch Activities</span>
</template>
<add-batch-trade-activity/>
</el-tab-pane>

<el-tab-pane name="token">
<template #label>
<span class="verification-tab-label"><em class="icon ni ni-coins mr-1"></em>Token</span>
</template>
<add-token/>
</el-tab-pane>
</el-tabs>
</div>

<el-dialog
v-model="deleteDialogVisible"
width="480px"
class="delete-dialog"
:close-on-click-modal="!deletingBatch"
:close-on-press-escape="!deletingBatch"
:show-close="!deletingBatch"
@close="closeDeleteDialog"
>
<template #header>
<div class="delete-dialog-header">
<div class="delete-dialog-icon">
<em class="icon ni ni-alert-circle"></em>
</div>
<div>
<h5>Delete Batch</h5>
<p class="sub-text mb-0">This action cannot be undone.</p>
</div>
</div>
</template>

<div class="delete-dialog-body">
Are you sure you want to delete this batch?
</div>

<template #footer>
<div class="delete-dialog-actions">
<el-button @click="closeDeleteDialog" :disabled="deletingBatch">Cancel</el-button>
<el-button type="danger" :loading="deletingBatch" @click="confirmDeleteBatch">
Delete Batch
</el-button>
</div>
</template>
</el-dialog>












</div>
</div>
</CooperativeLayout>
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

.verification-tab-label {
display: inline-flex;
align-items: center;
font-weight: 600;
}

.details-grid {
display: grid;
grid-template-columns: repeat(3, minmax(0, 1fr));
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
