<script setup>
import { computed, ref } from 'vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';
import AddCommodityToBatch from '@/Components/AddCommodityToBatch.vue';
import { Back, Plus, MoreFilled, Edit, Delete } from '@element-plus/icons-vue';

const page = usePage();
const batch = computed(() => page.props.batch?.data ?? page.props.batch ?? {});
const attachedCommodities = computed(() => page.props.attached_commodities ?? []);
const batchActivities = computed(() => page.props.batch_activities ?? []);
const activityOptions = computed(() => page.props.batch_status_list ?? []);

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

const goBack = () => {
router.get(route('cooperative.produce'));
};

const addActivityModalVisible = ref(false);
const activityForm = useForm({
activity: '',
});

const closeActivityModal = () => {
addActivityModalVisible.value = false;
activityForm.clearErrors();
};

const handleMoreCommand = (command) => {
if (command === 'activity') {
activityForm.clearErrors();
addActivityModalVisible.value = true;
}
};

const submitActivity = () => {
const batchId = batch.value?.id ?? page.props.batch?.id ?? null;
if (!batchId) return;

activityForm.post(route('commodity.batch.activities.store', { id: batchId }), {
preserveScroll: true,
onSuccess: () => {
closeActivityModal();
activityForm.reset('activity');
},
});
};






//
const commodityBatchState=computed(()=>{
const data=page.props.attached_commodities.length;
return data>0? true : false;
});








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
<el-button  :icon="Plus" v-if="commodityBatchState ==true">Tokenize</el-button>
<el-dropdown trigger="click" class="more-dropdown-trigger" @command="handleMoreCommand">
<el-button :icon="MoreFilled" class="more-dropdown-button" />
<template #dropdown>
<el-dropdown-menu>
<el-dropdown-item :icon="Plus" command="activity">Activity</el-dropdown-item>
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
<div class="activity-log-head">
<h6 class="title mb-0"><em class="icon ni ni-activity mr-1"></em>Batch Activities</h6>
<span class="activity-log-count">{{ batchActivities.length }} activities</span>
</div>

<div v-if="batchActivities.length" class="activity-log-list">
<el-timeline class="activity-timeline">
<el-timeline-item
v-for="item in batchActivities"
:key="item.id"
type="primary"
:timestamp="formatDateTime(item.created_at)"
placement="top"
>
<div class="activity-timeline-content text-capitalize">{{ item.activity }}</div>
</el-timeline-item>
</el-timeline>
</div>

<div v-else class="empty-activity-log">
No activities recorded for this batch yet.
</div>
</div>


<add-commodity-to-batch :batch-id="batch.id" :commodities="attachedCommodities" />

<el-dialog
v-model="addActivityModalVisible"
class="activity-dialog"
width="560"
align-center
destroy-on-close
:close-on-click-modal="false"
>
<template #header>
<div class="activity-dialog-header">
<div class="activity-dialog-icon">
<i class="bi bi-list-check"></i>
</div>
<div>
<h5 class="mb-1">Add Batch Activity</h5>
<p class="sub-text mb-0">Record a new activity and keep this batch timeline up to date.</p>
</div>
</div>
</template>

<form @submit.prevent="submitActivity">
<div class="activity-dialog-body">
<div class="activity-context">
<div class="context-item">
<span class="sub-text">Batch Code</span>
<strong>{{ batch.batch_code ?? 'N/A' }}</strong>
</div>
<div class="context-item">
<span class="sub-text">Commodity</span>
<strong>{{ batch.commodity_name ?? 'N/A' }}</strong>
</div>
</div>

<div class="form-group mb-0 activity-form-block">
<label class="form-label mb-1">Activity</label>
<el-select
v-model="activityForm.activity"
class="w-100"
size="large"
clearable
placeholder="Select activity"
>
<el-option
v-for="status in activityOptions"
:key="status"
:label="status"
:value="status"
/>
</el-select>
<div class="sub-text mt-1">Activities are sourced from batch status list.</div>
<div v-if="activityForm.errors.activity" class="text-danger small mt-1">
{{ activityForm.errors.activity }}
</div>
</div>
</div>

<div class="activity-dialog-actions">
<el-button type="primary" native-type="submit" :loading="activityForm.processing">
Save Activity
</el-button>
</div>
</form>
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
}
</style>
