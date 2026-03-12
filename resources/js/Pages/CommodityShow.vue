<script setup>
import { computed, ref } from 'vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { ElNotification } from 'element-plus';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';
import { Back } from '@element-plus/icons-vue';

const page = usePage();

const commodity = computed(() => page.props.commodity?.data ?? page.props.commodity ?? {});
const originFarms = computed(() => page.props.origin_farms ?? []);
const qualityMetadata = computed(() => page.props.quality_metadata ?? []);
const commodityQualityData = computed(() => page.props.commodity_quality_data ?? []);
const sortedCommodityQualityData = computed(() => {
const rows = Array.isArray(commodityQualityData.value) ? [...commodityQualityData.value] : [];
return rows.sort((a, b) => {
const aTime = new Date(a?.created_at ?? '').getTime() || 0;
const bTime = new Date(b?.created_at ?? '').getTime() || 0;
return bTime - aTime;
});
});

const qualityForm = useForm({
activity: '',
value: '',
});
const showQualityForm = ref(false);
const activeCommodityTab = ref('commodity_quality_data');

const formatMoney = (value) => {
const num = Number(value);
if (Number.isNaN(num)) return value ?? 'N/A';
return num.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
};

const statusClass = computed(() => {
const status = String(commodity.value?.status ?? '').toLowerCase();
if (status === 'listed') return 'badge bg-success-subtle text-success';
if (status === 'sold') return 'badge bg-info-subtle text-info';
if (status === 'negotiating') return 'badge bg-warning-subtle text-warning';
return 'badge bg-light text-dark';
});

const goBack = () => {
router.get(route('cooperative.produce'));
};

const goToCommodityEdit = () => {
if (!commodity.value?.id) return;
router.get(route('commodity.edit', { id: commodity.value.id }));
};

const goToOriginFarms = () => {
if (!commodity.value?.id) return;
router.get(route('commodity.origin-farms.create', { id: commodity.value.id }));
};

const goToOriginFarmDetails = (farmId) => {
if (!commodity.value?.id || !farmId) return;
router.get(route('commodity.origin-farms.show', {
commodity: commodity.value.id,
farm: farmId,
}));
};

const submitCommodityQualityData = () => {
if (!commodity.value?.id) return;
qualityForm.post(route('commodity.quality-data.store', { id: commodity.value.id }), {
preserveScroll: true,
onSuccess: () => {
ElNotification({
title: 'Success',
message: 'Commodity quality data saved successfully.',
type: 'success',
});
resetQualityForm();
showQualityForm.value = false;
},
});
};

const resetQualityForm = () => {
qualityForm.reset('activity', 'value');
qualityForm.clearErrors();
};

const openQualityForm = () => {
showQualityForm.value = true;
};

const hideQualityForm = () => {
showQualityForm.value = false;
};

const destroyCommodityQualityData = (row) => {
const qualityId = Number(row?.id ?? 0);
if (!commodity.value?.id || !qualityId) return;

router.delete(route('commodity.quality-data.destroy', {
id: commodity.value.id,
qualityId,
}), {
preserveScroll: true,
onSuccess: () => {
ElNotification({
title: 'Success',
message: 'Commodity quality data deleted successfully.',
type: 'success',
});
},
});
};

</script>

<template>
<CooperativeLayout>
<div class="container">
<div class="card card-bordered commodity-show-shell">
<div class="card-inner border-bottom header-row">
<div>
<h6 class="title mb-1"><em class="icon ni ni-bag mr-1"></em>Commodity #{{ commodity.id }}</h6>
<p class="sub-text mb-0">Detailed commodity information and linked origin farms.</p>
</div>

<div class="header-actions">

<el-button-group>
<el-button :icon="Back" @click="goBack">Back</el-button>
<el-button @click="goToCommodityEdit"><em class="icon ni ni-edit-alt mr-1"></em>Edit Commodity</el-button>
<el-button  @click="goToOriginFarms" v-if="originFarms.length==0"> Manage Origin Farms</el-button>
</el-button-group>
</div>
</div>

<div class="card-inner">
<div class="details-grid">
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-tag mr-1"></em>Commodity Name</span>
<strong>{{ commodity.commodity_name ?? 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-layers mr-1"></em>Commodity Type</span>
<strong>{{ commodity.commodity_type ?? 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-award mr-1"></em>Grade</span>
<strong>{{ commodity.grade ?? 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-package mr-1"></em>Weight</span>
<strong>{{ commodity.weight ?? 'N/A' }} kg</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-coins mr-1"></em>Price</span>
<strong>UGX {{ formatMoney(commodity.price) }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-growth mr-1"></em>Ripe Percentage</span>
<strong>{{ commodity.ripe_percentage === null || commodity.ripe_percentage === undefined ? 'N/A' : `${commodity.ripe_percentage}%` }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-drop mr-1"></em>Density Percentage</span>
<strong>{{ (commodity.density_percentage ?? commodity.desity_percentage) === null || (commodity.density_percentage ?? commodity.desity_percentage) === undefined ? 'N/A' : `${commodity.density_percentage ?? commodity.desity_percentage}%` }}</strong>
</div>
<div class="detail-item detail-item-span-2">
<span class="sub-text"><em class="icon ni ni-calendar mr-1"></em>Date of Harvest</span>
<strong>{{ commodity.harvest_date ?? 'N/A' }}</strong>
</div>
<div class="detail-item detail-item-full">
<span class="sub-text"><em class="icon ni ni-flag mr-1"></em>Status</span>
<strong><span :class="statusClass">{{ commodity.status ?? 'created' }}</span></strong>
</div>
</div>
</div>






<div class="card-inner border-top">




<el-tabs v-model="activeCommodityTab" class="app-themed-tabs">
<el-tab-pane name="commodity_quality_data">
<template #label>
<span class="commodity-tab-label"><em class="icon ni ni-check-circle mr-1"></em>Commodity Quality Data</span>
</template>

<div class="d-flex align-items-center justify-content-between mb-2">
<h6 class="title mb-0"><em class="icon ni ni-check-circle mr-1"></em>Quality information about commodity/ harvest</h6>
<div class="quality-head-actions">
<span class="sub-text">{{ commodityQualityData.length }} record(s)</span>
<el-button v-if="!showQualityForm" type="primary" @click="openQualityForm" class="quality-add-btn">
<em class="icon ni ni-plus mr-1"></em>Add Quality Metric
</el-button>
</div>
</div>

<div v-if="showQualityForm" class="quality-form-panel mb-3">
<div class="quality-form-head">
<div class="quality-form-title-wrap">
<p class="quality-form-title mb-1"><em class="icon ni ni-edit mr-1"></em>Add Quality Metric</p>
<span class="sub-text">Record one quality activity and value at a time for this commodity.</span>
</div>
<button type="button" class="quality-close-btn" @click="hideQualityForm" aria-label="Close quality form">
<em class="icon ni ni-cross"></em>
</button>
</div>

<form class="quality-form-row row g-2 align-items-start" @submit.prevent="submitCommodityQualityData">
<div class="col-12 col-md-5 quality-col">
<label class="form-label"><em class="icon ni ni-list mr-1"></em>Quality Metric</label>
<el-select
v-model="qualityForm.activity"
class="w-100"
filterable
clearable
allow-create
default-first-option
placeholder="Select or type quality metric"
>
<el-option
v-for="item in qualityMetadata"
:key="item"
:label="item"
:value="item"
/>
</el-select>
<small class="quality-field-hint">Available quality metrics: {{ qualityMetadata.length }}</small>
<div class="quality-field-feedback">
<small v-if="qualityForm.errors.activity" class="text-danger d-block">{{ qualityForm.errors.activity }}</small>
</div>
</div>

<div class="col-12 col-md-5 quality-col">
<label class="form-label"><em class="icon ni ni-pen2 mr-1"></em>Value</label>
<el-input v-model="qualityForm.value" clearable placeholder="Enter quality value" />
<small class="quality-field-hint">Example: 11.5%, Grade A, Cup score 84</small>
<div class="quality-field-feedback">
<small v-if="qualityForm.errors.value" class="text-danger d-block">{{ qualityForm.errors.value }}</small>
</div>
</div>

<div class="col-12 col-md-2 quality-submit-col">
<label class="form-label quality-action-label"></label>
<div class="quality-submit-wrap">
<el-button type="primary" native-type="submit" :loading="qualityForm.processing" class="quality-save-btn">
Save
</el-button>
</div>
<small class="quality-field-hint quality-action-hint">Spacer</small>
</div>
</form>
</div>

<div v-if="sortedCommodityQualityData.length" class="quality-data-table-wrap mb-3">
<el-table :data="sortedCommodityQualityData" class="quality-data-table" :fit="true">
<el-table-column prop="activity" min-width="220">
<template #header>
<span class="quality-th"><em class="icon ni ni-list mr-1"></em>Quality Metric</span>
</template>
<template #default="{ row }">
<span class="text-capitalize quality-activity-cell">
<em class="icon ni ni-check-circle-fill mr-1 quality-table-icon"></em>{{ row.activity }}
</span>
</template>
</el-table-column>

<el-table-column prop="value" min-width="180">
<template #header>
<span class="quality-th"><em class="icon ni ni-pen2 mr-1"></em>Value</span>
</template>
<template #default="{ row }">
<span class="quality-value-cell">{{ row.value }}</span>
</template>
</el-table-column>

<el-table-column width="72" align="center">
<template #default="{ row }">
<el-button
text
class="quality-row-delete-btn"
@click="destroyCommodityQualityData(row)"
aria-label="Delete quality item"
>
<em class="icon ni ni-trash"></em>
</el-button>
</template>
</el-table-column>
</el-table>
</div>

<div v-else class="empty-origin mb-3">
No quality data added yet.
</div>
</el-tab-pane>

<el-tab-pane name="farm_origin">
<template #label>
<span class="commodity-tab-label"><em class="icon ni ni-map-pin mr-1"></em>Farm Origin</span>
</template>

<div class="d-flex align-items-center justify-content-between mb-2">
<h6 class="title mb-0"><em class="icon ni ni-map-pin mr-1"></em>Farm where commodity originates</h6>
<span class="sub-text">{{ originFarms.length }} linked</span>
</div>

<div v-if="originFarms.length" class="table-responsive origin-table-wrap">
<table class="table table-sm table-middle mb-0 origin-table border">
<thead>
<tr>
<th style="width: 100px;">Farm Name</th>
<th>Location</th>
<th>Area (Acres)</th>
</tr>
</thead>
<tbody>
<tr v-for="farm in originFarms" :key="farm.id">
<td>
<button type="button" class="farm-link-button text-capitalize" @click="goToOriginFarmDetails(farm.id)">
{{ farm.farm_name }}
</button>
</td>
<td>{{ farm.location ?? 'N/A' }}</td>
<td>{{ farm.area_acres ?? 'N/A' }}</td>
</tr>
</tbody>
</table>
</div>
<div v-else class="empty-origin">
No origin farms linked yet.
</div>
</el-tab-pane>
</el-tabs>
</div>













</div>
</div>
</CooperativeLayout>
</template>

<style scoped>
.commodity-show-shell {
overflow: hidden;
}

.header-row {
display: flex;
align-items: center;
justify-content: space-between;
gap: 12px;
flex-wrap: wrap;
}

.header-actions {
display: flex;
align-items: center;
gap: 8px;
}

.quality-head-actions {
display: inline-flex;
align-items: center;
gap: 10px;
}

.quality-add-btn {
color: #ffffff !important;
}

.commodity-tab-label {
display: inline-flex;
align-items: center;
font-weight: 600;
}

.details-grid {
display: grid;
grid-template-columns: repeat(3, minmax(0, 1fr));
gap: 12px;
}

.quality-form-row {
margin: 0;
}

.quality-col {
display: flex;
flex-direction: column;
}

.quality-form-panel {
border: 1px solid #e5e9f2;
border-radius: 14px;
padding: 14px;
background: linear-gradient(180deg, #fcfdff 0%, #f9fbff 100%);
max-width: 100%;
}

.quality-form-head {
display: flex;
align-items: flex-start;
justify-content: space-between;
gap: 10px;
margin-bottom: 10px;
}

.quality-form-title-wrap {
display: flex;
flex-direction: column;
gap: 2px;
min-width: 0;
}

.quality-form-title {
display: inline-flex;
align-items: center;
font-size: 14px;
font-weight: 700;
color: #1f2a37;
}

.quality-close-btn {
width: 28px;
height: 28px;
border: 0;
border-radius: 999px;
background: #eef2f7;
color: #526484;
display: inline-flex;
align-items: center;
justify-content: center;
flex: 0 0 28px;
}

.quality-close-btn:hover {
background: #e2e8f0;
}

.quality-field-hint {
display: block;
color: #64748b;
font-size: 11px;
margin-top: 4px;
}

.quality-field-feedback {
min-height: 18px;
margin-top: 4px;
}

.quality-submit-col {
display: flex;
flex-direction: column;
align-items: flex-start;
justify-content: flex-start;
gap: 0;
}

.quality-action-label {
opacity: 0;
margin-bottom: 8px;
height: 22px;
}

.quality-submit-wrap {
width: 100%;
height: 40px;
display: flex;
align-items: center;
}

.quality-save-btn {
width: 100%;
height: 40px;
min-height: 40px;
padding-inline: 16px;
display: inline-flex;
justify-content: center;
font-weight: 600;
}

.quality-action-hint {
opacity: 0;
height: 16px;
margin-top: 4px;
}

.quality-th {
display: inline-flex;
align-items: center;
font-size: 13px;
text-transform: uppercase;
letter-spacing: 0.05em;
}

.quality-data-table-wrap {
border: 1px solid #e5e9f2;
border-radius: 10px;
overflow: hidden;
background: #fff;
}

.quality-data-table {
width: 100%;
font-size: 13px;
}

.quality-data-table :deep(.el-table__inner-wrapper::before) {
display: none;
}

.quality-data-table :deep(.el-table__header th) {
background: #f8fafc;
color: #526484;
font-weight: 700;
border-bottom: 1px solid #e5e9f2;
}

.quality-data-table :deep(.el-table__cell) {
padding-top: 4px;
padding-bottom: 4px;
}

.quality-data-table :deep(.el-table__row td) {
border-bottom-color: #edf1f7;
}

.quality-data-table :deep(.el-table__row:hover > td.el-table__cell) {
background: #f8fafc;
}

.quality-table-icon {
color: #94a3b8;
font-size: 15px;
}

.quality-activity-cell {
font-weight: 600;
white-space: nowrap;
overflow: hidden;
text-overflow: ellipsis;
}

.quality-value-cell {
color: #0f172a;
white-space: nowrap;
overflow: hidden;
text-overflow: ellipsis;
}

.quality-row-delete-btn {
color: #64748b;
padding: 4px;
min-height: auto;
}

.quality-row-delete-btn:hover {
color: #334155;
}

.quality-row-delete-btn .icon {
font-size: 14px;
}

.quality-field-feedback .text-danger {
font-size: 13px;
font-weight: 500;
line-height: 1.3;
}


.detail-item {
background: #f8fafc;
border-radius: 10px;
padding: 12px;
display: flex;
flex-direction: column;
gap: 3px;
}

.detail-item-full {
grid-column: span 3;
}

.detail-item-span-2 {
grid-column: span 2;
}

.origin-table th {
background: #f8fafc;
color: #526484;
font-weight: 600;
white-space: nowrap;
border-bottom: 1px solid #e5e9f2;
padding-top: 4px;
padding-bottom: 4px;
}

.origin-table td {
color: #364a63;
vertical-align: middle;
white-space: nowrap;
border-top: 1px solid #edf1f7;
padding-top: 4px;
padding-bottom: 4px;
}

.origin-table-wrap {
border: 1px solid #e5e9f2;
border-radius: 10px;
overflow: hidden;
background: #fff;
}

.origin-table.border {
border: 0 !important;
}

.origin-table tbody tr:hover {
background: #f8fafc;
}

.empty-origin {
border: 1px dashed #d9e2f0;
border-radius: 10px;
padding: 12px;
color: #64748b;
background: #f8fafc;
}

.farm-link-button {
border: 0;
background: transparent;
padding: 0;
color: #0f766e;
font-weight: 600;
}

.farm-link-button:hover {
text-decoration: underline;
}

@media (max-width: 767px) {
.header-actions {
width: 100%;
}

.header-actions :deep(.el-button-group) {
display: flex;
width: 100%;
}

.header-actions :deep(.el-button) {
flex: 1;
}

.details-grid {
grid-template-columns: 1fr;
}

.quality-head-actions {
width: 100%;
justify-content: space-between;
}

.quality-form-head {
align-items: center;
}

.quality-submit-col {
justify-content: flex-start;
flex-wrap: wrap;
}

.detail-item-full {
grid-column: span 1;
}

.detail-item-span-2 {
grid-column: span 1;
}
}
</style>
