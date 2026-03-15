<script setup>
import { computed } from 'vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';
import InputError from '@/Components/InputError.vue';
import SubmitButton from '@/Components/SubmitButton.vue';

const page = usePage();
const grades = computed(() => page.props.grades ?? []);
const crops = computed(() => page.props.crops ?? []);
const batch = computed(() => page.props.batch?.data ?? page.props.batch ?? {});

// Format option labels for commodity and grade selects.
const capitalizeLabel = (value) => {
const text = String(value ?? '').trim();
if (!text) return '';

return text
.replace(/_/g, ' ')
.split(/\s+/)
.map((word) => {
if (!word) return '';
if (word === word.toUpperCase() && word.length > 1) return word;
const lower = word.toLowerCase();
return lower.charAt(0).toUpperCase() + lower.slice(1);
})
.join(' ');
};

const form = useForm({
batch_code: batch.value.batch_code ?? '',
commodity_name: batch.value.commodity_name ?? '',
commodity_type: batch.value.commodity_type ?? '',
weight: batch.value.weight ?? '',
price: batch.value.price ?? '',
grade: batch.value.grade ?? '',
moisture: batch.value.moisture ?? '',
warehouse: batch.value.warehouse ?? '',
});

// Submit the batch edit form to the commodity batch update route.
const submit = () => {
form.put(route('commodity.batch.update', { id: batch.value.id }), {
preserveScroll: true,
});
};

// Return to the batch verification page for this batch.
const goBack = () => {
router.get(route('commodity.batch.verify', { id: batch.value.id }));
};
</script>

<template>
<CooperativeLayout>
<div class="container">
<div class="card batch-edit-card">
<div class="card-inner border-bottom batch-edit-head">
<div class="batch-edit-head-content">
<div class="batch-edit-title-wrap">
<h6 class="title mb-1"><em class="icon ni ni-edit-alt mr-1"></em>Edit Batch</h6>
<p class="sub-text mb-0">Update batch details and keep verification data accurate.</p>
</div>
<el-button plain class="back-btn" @click="goBack">Back</el-button>
</div>
</div>

<div class="card-inner batch-edit-body theme-no-highlight">
<form class="row g-4 form-shell" @submit.prevent="submit">
<div v-if="form.errors.batch" class="col-12">
<div class="alert alert-warning mb-0">
{{ form.errors.batch }}
</div>
</div>

<div class="col-12 col-md-6 field-block">
<label class="form-label">Batch Code</label>
<el-input v-model="form.batch_code" size="large" placeholder="e.g. BATCH-2026-001" />
<InputError :message="form.errors.batch_code" class="mt-2" />
</div>

<div class="col-12 col-md-6 field-block">
<label class="form-label">Commodity Name</label>
<el-select
v-model="form.commodity_name"
class="w-100 theme-no-highlight-select text-capitalize"
size="large"
clearable
filterable
popper-class="theme-no-highlight-select-popper text-capitalize"
placeholder="Select commodity"
>
<el-option
v-for="item in crops"
:key="item.id"
class="text-capitalize"
:label="capitalizeLabel(item.name)"
:value="item.name"
/>
</el-select>
<InputError :message="form.errors.commodity_name" class="mt-2" />
</div>

<div class="col-12 col-md-6 field-block">
<label class="form-label">Commodity Type</label>
<el-input v-model="form.commodity_type" size="large" placeholder="e.g. Green Bean" />
<InputError :message="form.errors.commodity_type" class="mt-2" />
</div>

<div class="col-12 col-md-6 field-block">
<label class="form-label">Weight (kg)</label>
<el-input v-model="form.weight" size="large" type="number" min="0.01" step="0.01" placeholder="Enter batch weight" />
<InputError :message="form.errors.weight" class="mt-2" />
</div>

<div class="col-12 col-md-6 field-block">
<label class="form-label">Price</label>
<el-input v-model="form.price" size="large" type="number" min="0.01" step="0.01" placeholder="Enter batch price" />
<InputError :message="form.errors.price" class="mt-2" />
</div>

<div class="col-12 col-md-6 field-block">
<label class="form-label">Grade</label>
<el-select
v-model="form.grade"
class="w-100 theme-no-highlight-select text-capitalize"
size="large"
popper-class="theme-no-highlight-select-popper text-capitalize"
placeholder="Select grade"
>
<el-option
v-for="item in grades"
:key="item.id"
class="text-capitalize"
:label="capitalizeLabel(item.name)"
:value="item.name"
/>
</el-select>
<InputError :message="form.errors.grade" class="mt-2" />
</div>

<div class="col-12 col-md-6 field-block">
<label class="form-label">Moisture (%) (optional)</label>
<el-input v-model="form.moisture" size="large" type="number" min="0" step="0.01" placeholder="Enter moisture level" />
<InputError :message="form.errors.moisture" class="mt-2" />
</div>

<div class="col-12 col-md-6 field-block">
<label class="form-label">Warehouse</label>
<el-input v-model="form.warehouse" size="large" placeholder="Enter warehouse location or code" />
<InputError :message="form.errors.warehouse" class="mt-2" />
</div>

<div class="col-12 col-md-3 action-row">
<SubmitButton :title="'Update Batch'" :status="form.processing" />
</div>
</form>
</div>
</div>
</div>
</CooperativeLayout>
</template>

<style scoped>
.batch-edit-card {
border-radius: 12px;
}

.batch-edit-head {
padding: 20px 24px;
}

.batch-edit-head-content {
display: flex;
justify-content: space-between;
align-items: center;
gap: 12px;
}

.batch-edit-title-wrap {
min-width: 0;
}

.batch-edit-body {
padding: 24px;
}

.form-shell {
padding: 6px 2px 2px;
}

.field-block {
padding-top: 2px;
padding-bottom: 2px;
}

.form-label {
font-weight: 600;
color: #334155;
margin-bottom: 8px;
}

.action-row {
padding-top: 10px;
}

@media (max-width: 767px) {
.batch-edit-head {
padding: 16px;
}

.batch-edit-head-content {
flex-direction: column;
align-items: stretch;
}

.back-btn {
width: 100%;
}

.batch-edit-body {
padding: 16px;
}
}
</style>
