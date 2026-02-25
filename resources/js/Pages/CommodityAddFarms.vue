<script setup>
import { computed, watch } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';
import InputError from '@/Components/InputError.vue';

const page = usePage();

const commodity = computed(() => page.props.commodity?.data ?? page.props.commodity ?? null);
const farms = computed(() => page.props.farms?.data ?? page.props.farms ?? []);
const selectedFarmIds = computed(() => page.props.origin_farm_ids ?? []);

const form = useForm({
farm_ids: [],
});

watch(
() => selectedFarmIds.value,
(ids) => {
form.farm_ids = Array.isArray(ids) ? [...ids] : [];
},
{ immediate: true },
);

const selectedFarmNames = computed(() => {
const lookup = new Map(farms.value.map((farm) => [Number(farm.id), farm.farm_name]));
return form.farm_ids
.map((id) => lookup.get(Number(id)))
.filter((name) => Boolean(name));
});

const submit = () => {
if (!commodity.value?.id) return;
form.post(route('commodity.origin-farms.store', { id: commodity.value.id }), {
preserveScroll: true,
});
};
</script>

<template>
<CooperativeLayout>
<div class="container">
<div class="card card-bordered add-origin-shell">
<div class="card-inner border-bottom shell-head">
<div>
<h6 class="title mb-1"><em class="icon ni ni-map-pin mr-1"></em>Link Commodity Origins</h6>
<p class="sub-text mb-0">Assign farms where this commodity originated.</p>
</div>
<span class="badge bg-light text-dark shell-id">ID #{{ commodity?.id ?? 'N/A' }}</span>
</div>

<div class="card-inner" v-if="commodity">
<div class="row g-3">
<div class="col-12 col-lg-4">
<div class="origin-side-card">
<h6 class="title-sm mb-3">Commodity Snapshot</h6>
<div class="snapshot-item">
<span class="sub-text">Commodity Name</span>
<strong>{{ commodity.commodity_name }}</strong>
</div>
<div class="snapshot-item">
<span class="sub-text">Commodity Type</span>
<strong>{{ commodity.commodity_type }}</strong>
</div>
<div class="snapshot-item">
<span class="sub-text">Grade</span>
<strong>{{ commodity.grade }}</strong>
</div>
<div class="snapshot-item">
<span class="sub-text">Weight</span>
<strong>{{ commodity.weight }} kg</strong>
</div>
<div class="snapshot-item">
<span class="sub-text">Price</span>
<strong>{{ commodity.price ?? 'N/A' }}</strong>
</div>
<div class="snapshot-item">
<span class="sub-text">Harvest Date</span>
<strong>{{ commodity.harvest_date }}</strong>
</div>
</div>
</div>

<div class="col-12 col-lg-8">
<div class="origin-form-card">
<div class="form-head">
<h6 class="title-sm mb-1">Origin Farms</h6>
<p class="sub-text mb-0">{{ form.farm_ids.length }} selected</p>
</div>

<label class="form-label mb-2 mt-3">Choose Farms</label>
<el-select
v-model="form.farm_ids"
multiple
collapse-tags
collapse-tags-tooltip
placeholder="Select farms where this commodity originated"
class="form-control-like w-100"
>
<el-option
v-for="farm in farms"
:key="farm.id"
:label="farm.farm_name"
:value="farm.id"
/>
</el-select>
<InputError :message="form.errors.farm_ids" class="mt-2" />

<div v-if="selectedFarmNames.length" class="selected-preview">
<span v-for="name in selectedFarmNames" :key="name" class="selected-pill">{{ name }}</span>
</div>

<div class="form-actions">
<el-button type="primary" :loading="form.processing" @click="submit">
Save Origin Farms
</el-button>
</div>
</div>
</div>
</div>
</div>

<div class="card-inner" v-else>
<p class="mb-0">Commodity details are not available.</p>
</div>
</div>
</div>
</CooperativeLayout>
</template>

<style scoped>
.add-origin-shell {
overflow: hidden;
}

.shell-head {
display: flex;
align-items: center;
justify-content: space-between;
gap: 12px;
flex-wrap: wrap;
}

.shell-id {
font-weight: 600;
}

.origin-side-card,
.origin-form-card {
border: 1px solid #e5e9f2;
border-radius: 12px;
background: #f8fafc;
padding: 14px;
}

.snapshot-item {
padding: 9px 10px;
border-radius: 10px;
background: #ffffff;
border: 1px solid #e9edf5;
display: flex;
flex-direction: column;
gap: 2px;
}

.snapshot-item + .snapshot-item {
margin-top: 8px;
}

.selected-preview {
display: flex;
flex-wrap: wrap;
gap: 8px;
margin-top: 12px;
}

.selected-pill {
display: inline-flex;
align-items: center;
padding: 5px 10px;
border-radius: 999px;
background: #eef2f8;
border: 1px solid #dce4f1;
font-size: 12px;
font-weight: 500;
color: #3f5473;
}

.form-actions {
display: flex;
justify-content: flex-end;
margin-top: 12px;
}

@media (max-width: 991px) {
.form-actions {
justify-content: stretch;
}

.form-actions .el-button {
width: 100%;
}
}
</style>
