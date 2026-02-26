<script setup>
import { computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';
import AddCommodityToBatch from '@/Components/AddCommodityToBatch.vue';
import { Back, Plus, MoreFilled, Edit, Delete } from '@element-plus/icons-vue';

const page = usePage();
const batch = computed(() => page.props.batch?.data ?? page.props.batch ?? {});
const attachedCommodities = computed(() => page.props.attached_commodities ?? []);

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
<el-dropdown trigger="click" class="more-dropdown-trigger">
<el-button :icon="MoreFilled" class="more-dropdown-button" />
<template #dropdown>
<el-dropdown-menu>
<el-dropdown-item :icon="Plus">Activity</el-dropdown-item>
<el-dropdown-item :icon="Edit">Edit</el-dropdown-item>
<el-dropdown-item :icon="Delete">Delete</el-dropdown-item>
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


<add-commodity-to-batch :batch-id="batch.id" :commodities="attachedCommodities" />


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
</style>
