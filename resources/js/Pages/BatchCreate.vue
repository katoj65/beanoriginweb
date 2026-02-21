<script setup>
import { computed } from 'vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';
import InputError from '@/Components/InputError.vue';
import SubmitButton from '@/Components/SubmitButton.vue';

const page = usePage();
const grades = computed(() => page.props.grades ?? []);
const crops = computed(() => page.props.crops ?? []);

const form = useForm({
batch_code: '',
commodity_name: '',
commodity_type: '',
weight: '',
grade: '',
moisture: '',
warehouse: '',
});

const submit = () => {
form.post(route('cooperative.batches.store'), {
preserveScroll: true,
});
};

const goBack = () => {
router.get(route('cooperative.batches.listed'));
};

</script>

<template>
<CooperativeLayout>

<div class="container">
<div class="card card-bordered batch-create-card">
<div class="card-inner border-bottom batch-create-head">
<div class="batch-create-head-content">
<div class="batch-create-title-wrap">
<h6 class="title mb-1"><em class="icon ni ni-plus-circle mr-1"></em>Add Batch</h6>
<p class="sub-text mb-0">Create a new batch using commodity details and warehouse information.</p>
</div>
<el-button plain class="back-btn" @click="goBack">Back</el-button>
</div>
</div>




<div class="card-inner batch-create-body theme-no-highlight">
<form class="row g-4 form-shell" @submit.prevent="submit">
<div class="col-12 col-md-6 field-block">
<label class="form-label">Batch Code</label>
<el-input v-model="form.batch_code" size="large" placeholder="e.g. BATCH-2026-001" />
<InputError :message="form.errors.batch_code" class="mt-2" />
</div>

<div class="col-12 col-md-6 field-block">
<label class="form-label" for="01">Commodity Name</label>
<el-select
v-model="form.commodity_name"
class="w-100 theme-no-highlight-select"
size="large"
clearable
filterable
popper-class="theme-no-highlight-select-popper"
placeholder="Select commodity"
>
<el-option
v-for="item in crops"
:key="item.id"
:label="item.name"
:value="item.name"
/>
</el-select>
<InputError :message="form.errors.commodity_name" class="mt-2" />
</div>

<div class="col-12 col-md-6 field-block">
<label class="form-label" for="02">Commodity Type</label>
<el-input v-model="form.commodity_type" size="large" placeholder="e.g. Green Bean" id="02" />
<InputError :message="form.errors.commodity_type" class="mt-2" />
</div>

<div class="col-12 col-md-6 field-block">
<label class="form-label" for="03">Weight (kg)</label>
<el-input v-model="form.weight" size="large" type="number" min="0.01" step="0.01" placeholder="Enter batch weight" if="03" />
<InputError :message="form.errors.weight" class="mt-2" />
</div>

<div class="col-12 col-md-6 field-block">
<label class="form-label">Grade</label>
<el-select
v-model="form.grade"
class="w-100 theme-no-highlight-select"
size="large"
filterable
popper-class="theme-no-highlight-select-popper"
placeholder="Select grade"
>
<el-option
v-for="item in grades"
:key="item.id"
:label="item.name"
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

<div class="col-12 field-block">
<label class="form-label">Warehouse</label>
<el-input v-model="form.warehouse" size="large" placeholder="Enter warehouse location or code" />
<InputError :message="form.errors.warehouse" class="mt-2" />
</div>

<div class="col-12 col-md-3 action-row">
<SubmitButton :title="'Create Batch'" :status="form.processing" />
</div>
</form>
</div>
</div>
</div>
</CooperativeLayout>
</template>

<style scoped>
.batch-create-card {
border-radius: 12px;
}

.batch-create-head {
padding: 20px 24px;
}

.batch-create-head-content {
display: flex;
justify-content: space-between;
align-items: center;
gap: 12px;
}

.batch-create-title-wrap {
min-width: 0;
}

.batch-create-body {
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

.cancel-wrap {
display: flex;
align-items: stretch;
}

.cancel-btn {
min-height: 48px;
}

@media (max-width: 767px) {
.batch-create-head {
padding: 16px;
}

.batch-create-head-content {
flex-direction: column;
align-items: stretch;
}

.back-btn {
width: 100%;
}

.batch-create-body {
padding: 16px;
}
}
</style>
