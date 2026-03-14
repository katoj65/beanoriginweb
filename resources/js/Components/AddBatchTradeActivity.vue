<script setup>
import { ref, computed } from 'vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { ElNotification } from 'element-plus';
import { Delete, Plus } from '@element-plus/icons-vue';
import InputError from '@/Components/InputError.vue';

const addProcessModalVisible = ref(false);

const openModal = () => {
form.clearErrors();
addProcessModalVisible.value = true;
};

const closeModal = () => {
addProcessModalVisible.value = false;
form.reset();
form.clearErrors();
};



const formatDateTime = (value) => {
if (!value) return 'N/A';
const date = new Date(value);
if (Number.isNaN(date.getTime())) return value;
return date.toLocaleString();
};


const page=usePage();
const resolvedBatchId = computed(() => page.props.batch?.id ?? page.props.batch?.data?.id ?? null);
const trade_activity=computed(()=>page.props.batch_trade_activity_metadata ?? page.props.batch_processing_metadata ?? []);
const batch_trade_data = computed(() => page.props.batch_trade_activity_data ?? []);



const form = useForm({
activity: '',
});

const submit = () => {
if (!resolvedBatchId.value) return;

form.post(route('batch.trade-activity.store', { id: resolvedBatchId.value }), {
preserveState: true,
preserveScroll: true,
onSuccess: (response) => {
ElNotification({
title: 'Success',
message: response?.props?.flash?.success || 'Batch trade activity saved successfully.',
type: 'success',
});
closeModal();
},
});
};

const destroyTradeActivity = (tradeActivityId) => {
if (!resolvedBatchId.value || !tradeActivityId) return;

router.delete(route('batch.trade-activity.destroy', { id: resolvedBatchId.value, tradeActivityId }), {
preserveScroll: true,
onSuccess: () => {
ElNotification({
title: 'Success',
message: 'Batch trade activity deleted successfully.',
type: 'success',
});
},
});
};




</script>

<template>
<div class="batch-process-action">
<div class="batch-process-head">
<div class="batch-process-copy">
<h6 class="title mb-0"><em class="icon ni ni-setting-alt mr-1"></em>Batch Trade Activity</h6>
<p class="sub-text mb-0">Track and manage trade activities for this batch.</p>






</div>
<el-button type="primary" :icon="Plus" @click="openModal">
Add Batch Activity
</el-button>
</div>

<div v-if="batch_trade_data.length" class="table-responsive process-table-wrap mt-3">
<table class="table table-sm table-middle mb-0 process-table">
<thead>
<tr>
<th style="width:80%;"><span class="head-label"><em class="icon ni ni-activity"></em>Activity</span></th>
<th><span class="head-label"><em class="icon ni ni-calendar"></em>Date Added</span></th>
<th class="text-right"><span class="head-label"><em class="icon ni ni-trash"></em></span></th>
</tr>
</thead>
<tbody>
<tr v-for="item in batch_trade_data" :key="item.id">
<td class="text-capitalize">
<span class="activity-cell">
<em class="icon ni ni-activity"></em>
{{ item.activity ?? 'N/A' }}
</span>
</td>
<td>{{ formatDateTime(item.created_at) }}</td>
<td class="text-right">
<el-button
class="process-delete-btn"
type="danger"
text
:icon="Delete"
@click="destroyTradeActivity(item.id)"
/>
</td>
</tr>
</tbody>
</table>
</div>
<div v-else class="empty-process mt-3">
No batch trade activity recorded yet.
</div>

<el-dialog
v-model="addProcessModalVisible"
width="520"
align-center
destroy-on-close
>
<template #header>
<div class="batch-process-dialog-head">
<h6 class="title mb-1">Add Batch Trade Activity</h6>
<p class="sub-text mb-0">Select a trade activity for this batch.</p>
</div>
</template>
<form @submit.prevent="submit">
<el-form class="mt-2" label-position="top">
<el-form-item label="Activity">
<el-select
v-model="form.activity"
placeholder="Select trade activity"
class="w-100"
clearable
filterable
>
<el-option
v-for="item in trade_activity"
:key="item"
:label="item"
:value="item"
/>
</el-select>
<InputError :message="form.errors.activity" class="mt-1" />
</el-form-item>

<div class="batch-process-dialog-actions mt-4">
<el-button @click="closeModal">Cancel</el-button>
<el-button type="primary" :loading="form.processing" @click="submit">Submit</el-button>
</div>
</el-form>
</form>
</el-dialog>





</div>
</template>

<style scoped>
.batch-process-head {
display: flex;
align-items: center;
justify-content: space-between;
gap: 12px;
flex-wrap: wrap;
}

.batch-process-copy {
display: flex;
flex-direction: column;
gap: 2px;
}

.head-label {
display: inline-flex;
align-items: center;
gap: 6px;
}

.head-label .icon {
color: #8094ae;
font-size: 13px;
}

.process-delete-btn {
padding: 4px;
}

.activity-cell {
display: inline-flex;
align-items: center;
gap: 6px;
}

.activity-cell .icon {
color: #8094ae;
font-size: 13px;
}

.process-table-wrap {
border: 1px solid #e5e9f2;
border-radius: 10px;
overflow: hidden;
background: #fff;
}

.process-table th {
background: #f8fafc;
color: #526484;
font-weight: 600;
white-space: nowrap;
}

.process-table td {
color: #364a63;
white-space: nowrap;
}

.empty-process {
border: 1px dashed #d9e2f0;
border-radius: 10px;
padding: 12px;
color: #64748b;
background: #f8fafc;
}

.batch-process-dialog-actions {
display: flex;
justify-content: flex-end;
gap: 10px;
}

@media (max-width: 767px) {
.batch-process-head :deep(.el-button) {
width: 100%;
}
}
</style>
