<script setup>
import { computed } from 'vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { ElNotification } from 'element-plus';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';
import InputError from '@/Components/InputError.vue';
import SubmitButton from '@/Components/SubmitButton.vue';
import { Back } from '@element-plus/icons-vue';

const page = usePage();
const commodity = computed(() => page.props.commodity?.data ?? page.props.commodity ?? {});
const crops = computed(() => page.props.crops?.data ?? page.props.crops ?? []);
const cropType = computed(() => page.props.crop_type?.data ?? page.props.crop_type ?? []);
const cropGrade = computed(() => page.props.crop_grade?.data ?? page.props.crop_grade ?? []);

const initialValues = {
commodity_name: commodity.value.commodity_name ?? '',
commodity_type: commodity.value.commodity_type ?? '',
grade: commodity.value.grade ?? '',
weight: commodity.value.weight ?? '',
price: commodity.value.price ?? '',
ripe_percentage: commodity.value.ripe_percentage ?? null,
density_percentage: commodity.value.density_percentage ?? null,
harvest_date: commodity.value.harvest_date ?? '',
};

const form = useForm({
commodity_name: initialValues.commodity_name,
commodity_type: initialValues.commodity_type,
grade: initialValues.grade,
weight: initialValues.weight,
price: initialValues.price,
ripe_percentage: initialValues.ripe_percentage,
density_percentage: initialValues.density_percentage,
harvest_date: initialValues.harvest_date,
});

const capitalizeLabel = (value) => {
const text = String(value ?? '').trim();
if (!text) return '';
return text.charAt(0).toUpperCase() + text.slice(1);
};

const normalizeText = (value) => String(value ?? '').trim();
const normalizeNumber = (value) => {
if (value === '' || value === null || value === undefined) return null;
const num = Number(value);
return Number.isNaN(num) ? null : num;
};
const normalizeDate = (value) => String(value ?? '').trim().slice(0, 10);

const sanitizePercentageInteger = (value) => {
if (value === '' || value === null || value === undefined) return null;
const numericValue = Number(value);
if (Number.isNaN(numericValue)) return null;
return Math.min(100, Math.max(0, Math.trunc(numericValue)));
};

const hasChanges = () => (
normalizeText(form.commodity_name) !== normalizeText(initialValues.commodity_name)
|| normalizeText(form.commodity_type) !== normalizeText(initialValues.commodity_type)
|| normalizeText(form.grade) !== normalizeText(initialValues.grade)
|| normalizeNumber(form.weight) !== normalizeNumber(initialValues.weight)
|| normalizeNumber(form.price) !== normalizeNumber(initialValues.price)
|| normalizeNumber(form.ripe_percentage) !== normalizeNumber(initialValues.ripe_percentage)
|| normalizeNumber(form.density_percentage) !== normalizeNumber(initialValues.density_percentage)
|| normalizeDate(form.harvest_date) !== normalizeDate(initialValues.harvest_date)
);

const submit = () => {
if (!commodity.value?.id) return;
if (!hasChanges()) {
ElNotification({
title: 'Error',
message: 'No changes detected. Update at least one field before submitting.',
type: 'error',
});
return;
}
form.put(route('commodity.update-data', { id: commodity.value.id }), {
preserveScroll: true,
onSuccess: () => {
ElNotification({
title: 'Success',
message: 'Commodity updated successfully.',
type: 'success',
});
},
});
};

const goBack = () => {
if (!commodity.value?.id) return;
router.get(route('commodity.show', { id: commodity.value.id }));
};
</script>

<template>
<CooperativeLayout>
<div class="container">
<div class="card card-bordered commodity-update-card">
<div class="card-inner border-bottom commodity-update-head">
<div class="commodity-update-head-content">
<div class="commodity-update-title-wrap">
<h6 class="title mb-1"><em class="icon ni ni-edit-alt mr-1"></em>Update Commodity</h6>
<p class="sub-text mb-0">Edit commodity details and save updates.</p>
</div>
<el-button-group>
<el-button :icon="Back" @click="goBack">Back</el-button>
</el-button-group>
</div>
</div>

<div class="card-inner commodity-update-body">
<form class="row g-3" @submit.prevent="submit">
<div class="col-12 col-md-6">
<label class="form-label">Commodity Name</label>
<el-select
v-model="form.commodity_name"
class="w-100 text-capitalize"
filterable
clearable
placeholder="Select commodity name"
>
<el-option
v-for="item in crops"
:key="item.id"
:label="capitalizeLabel(item.name)"
:value="item.name"
class="text-capitalize"
/>
</el-select>
<InputError :message="form.errors.commodity_name" class="mt-1" />
</div>

<div class="col-12 col-md-6">
<label class="form-label">Commodity Type</label>
<el-select
v-model="form.commodity_type"
class="w-100 text-capitalize"
filterable
clearable
placeholder="Select commodity type"
>
<el-option
v-for="item in cropType"
:key="item.id"
:label="capitalizeLabel(item.name)"
:value="item.name"
class="text-capitalize"
/>
</el-select>
<InputError :message="form.errors.commodity_type" class="mt-1" />
</div>

<div class="col-12 col-md-6">
<label class="form-label">Grade</label>
<el-select
v-model="form.grade"
class="w-100 text-capitalize"
filterable
clearable
placeholder="Select grade"
>
<el-option
v-for="item in cropGrade"
:key="item.id"
:label="capitalizeLabel(item.name)"
:value="item.name"
class="text-capitalize"
/>
</el-select>
<InputError :message="form.errors.grade" class="mt-1" />
</div>

<div class="col-12 col-md-6">
<label class="form-label">Weight (kg)</label>
<el-input v-model="form.weight" type="number" min="0" step="0.01" />
<InputError :message="form.errors.weight" class="mt-1" />
</div>

<div class="col-12 col-md-6">
<label class="form-label">Price</label>
<el-input v-model="form.price" type="number" min="0" step="0.01" />
<InputError :message="form.errors.price" class="mt-1" />
</div>

<div class="col-12 col-md-6">
<label class="form-label">Ripe Percentage</label>
<input
:value="form.ripe_percentage ?? ''"
type="number"
min="0"
max="100"
step="1"
class="form-control"
placeholder="Enter ripe percentage"
@input="form.ripe_percentage = sanitizePercentageInteger($event.target.value)"
/>
<InputError :message="form.errors.ripe_percentage" class="mt-1" />
</div>

<div class="col-12 col-md-6">
<label class="form-label">Density Percentage</label>
<input
:value="form.density_percentage ?? ''"
type="number"
min="0"
max="100"
step="1"
class="form-control"
placeholder="Enter density percentage"
@input="form.density_percentage = sanitizePercentageInteger($event.target.value)"
/>
<InputError :message="form.errors.density_percentage" class="mt-1" />
</div>

<div class="col-12 col-md-6">
<label class="form-label">Date of Harvest</label>
<el-input v-model="form.harvest_date" type="date" />
<InputError :message="form.errors.harvest_date" class="mt-1" />
</div>

<div class="col-12 col-md-3 pt-1">
<SubmitButton :title="'Update Commodity'" :status="form.processing" />
</div>
</form>
</div>
</div>
</div>
</CooperativeLayout>
</template>

<style scoped>
.commodity-update-head {
padding: 20px 24px;
}

.commodity-update-head-content {
display: flex;
align-items: center;
justify-content: space-between;
gap: 12px;
}

.commodity-update-body {
padding: 24px;
}

.form-label {
font-weight: 600;
color: #334155;
margin-bottom: 8px;
}

@media (max-width: 767px) {
.commodity-update-head {
padding: 16px;
}

.commodity-update-head-content {
flex-direction: column;
align-items: stretch;
}

.commodity-update-body {
padding: 16px;
}
}
</style>
