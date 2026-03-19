<script setup>
import { computed, ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import CommodityOriginFarms from '@/Commodity/CommodityOriginFarms.vue';
import CommodityQualityData from '@/Commodity/CommodityQualityData.vue';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';
import { Back } from '@element-plus/icons-vue';

const page = usePage();

const commodity = computed(() => page.props.commodity?.data ?? page.props.commodity ?? {});
const can = computed(() => page.props.can ?? {});
const originFarms = computed(() => page.props.origin_farms ?? []);
const qualityMetadata = computed(() => page.props.quality_metadata ?? []);
const commodityQualityData = computed(() => page.props.commodity_quality_data ?? []);
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

</script>

<template>
<CooperativeLayout>
<div class="container">
<div class="card commodity-show-shell">
<div class="card-inner header-row">
<div>
<h6 class="title mb-1 font-large"><em class="icon ni ni-bag mr-1"></em>Commodity #{{ commodity.id }}</h6>
<p class="sub-text mb-0">Detailed commodity information and linked origin farms.</p>
</div>

<div class="header-actions">

<el-button-group>
<el-button :icon="Back" @click="goBack">Back</el-button>
<el-button v-if="can.is_owner" @click="goToCommodityEdit"><em class="icon ni ni-edit-alt mr-1"></em>Edit Commodity</el-button>
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






<div class="card-inner commodity-tabs-section">




<el-tabs v-model="activeCommodityTab" class="app-themed-tabs">
<el-tab-pane name="commodity_quality_data">
<template #label>
<span class="commodity-tab-label"><em class="icon ni ni-check-circle mr-1"></em>Commodity Quality Data</span>
</template>

<CommodityQualityData
:commodity-id="commodity.id"
:can-is-owner="can.is_owner"
:quality-metadata="qualityMetadata"
:commodity-quality-data="commodityQualityData"
/>
</el-tab-pane>

<el-tab-pane name="farm_origin">
<template #label>
<span class="commodity-tab-label"><em class="icon ni ni-map-pin mr-1"></em>Farm Origin</span>
</template>

<CommodityOriginFarms
:commodity-id="commodity.id"
:origin-farms="originFarms"
/>
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

.commodity-tabs-section {
padding-top: 16px;
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

.detail-item {
background: #f8fafc;
border: 1px solid #f2f5f9;
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

.detail-item-full {
grid-column: span 1;
}

.detail-item-span-2 {
grid-column: span 1;
}
}
</style>
