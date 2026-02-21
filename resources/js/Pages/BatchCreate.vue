<script setup>
import { computed } from 'vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';
import InputError from '@/Components/InputError.vue';
import SubmitButton from '@/Components/SubmitButton.vue';

const page = usePage();

const commodities = computed(() => page.props.commodities ?? []);
const statusOptions = computed(() => page.props.status_options ?? []);

const form = useForm({
batch_number: '',
grade: '',
weight: '',
status: 'listed',
commodity_ids: [],
ask_price: '',
notes: '',
});

const selectedCommodityWeight = computed(() => {
if (!form.commodity_ids.length) return 0;
return commodities.value
.filter((item) => form.commodity_ids.includes(item.id))
.reduce((sum, item) => sum + Number(item.weight ?? 0), 0);
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
<h6 class="title mb-1"><em class="icon ni ni-plus-circle mr-1"></em>Add Blockchain Batch</h6>
<p class="sub-text mb-0">Create a trade-ready batch and link commodities to the blockchain ledger.</p>
</div>
<el-button plain class="back-btn" @click="goBack">Back</el-button>
</div>
</div>

<div class="card-inner batch-create-body theme-no-highlight">
<form class="row g-4 form-shell" @submit.prevent="submit">
<div class="col-12 col-md-6 field-block">
<label class="form-label">Batch Number</label>
<el-input v-model="form.batch_number" size="large" placeholder="e.g. BATCH-2026-001" />
<InputError :message="form.errors.batch_number" class="mt-2" />
</div>

<div class="col-12 col-md-3 field-block">
<label class="form-label">Grade</label>
<el-input v-model="form.grade" size="large" placeholder="A / AA / Premium" />
<InputError :message="form.errors.grade" class="mt-2" />
</div>

<div class="col-12 col-md-3 field-block">
<label class="form-label">Status</label>
<el-select v-model="form.status" class="w-100" size="large" placeholder="Select status" popper-class="batch-select-popper">
<el-option
v-for="item in statusOptions"
:key="item"
:label="item"
:value="item"
/>
</el-select>
<InputError :message="form.errors.status" class="mt-2" />
</div>

<div class="col-12 col-md-6 field-block">
<label class="form-label">Total Weight (kg)</label>
<el-input v-model="form.weight" size="large" type="number" min="0.01" step="0.01" placeholder="Enter total batch weight" />
<div class="sub-text mt-1">Selected commodities weight: {{ selectedCommodityWeight.toFixed(2) }} kg</div>
<InputError :message="form.errors.weight" class="mt-2" />
</div>

<div class="col-12 col-md-6 field-block">
<label class="form-label">Ask Price (optional)</label>
<el-input v-model="form.ask_price" size="large" type="number" min="0" step="0.01" placeholder="Enter asking price" />
<InputError :message="form.errors.ask_price" class="mt-2" />
</div>

<div class="col-12 field-block">
<label class="form-label">Linked Commodities</label>
<el-select
v-model="form.commodity_ids"
class="w-100"
size="large"
multiple
filterable
collapse-tags
collapse-tags-tooltip
popper-class="batch-select-popper"
placeholder="Select commodities to include in this batch"
>
<el-option
v-for="item in commodities"
:key="item.id"
:label="`${item.commodity_name} • ${item.commodity_type} • ${item.weight} kg`"
:value="item.id"
/>
</el-select>
<InputError :message="form.errors.commodity_ids" class="mt-2" />
</div>

<div class="col-12 field-block">
<label class="form-label">Notes (optional)</label>
<el-input
v-model="form.notes"
size="large"
type="textarea"
:rows="4"
placeholder="Add any trade notes, quality notes, or conditions"
/>
<InputError :message="form.errors.notes" class="mt-2" />
</div>

<div class="col-12 col-md-4 action-row">
<SubmitButton :title="'Create Batch'" :status="form.processing" />
</div>
<div class="col-12 col-md-3 action-row cancel-wrap">
<el-button plain class="w-100 cancel-btn" @click="goBack">Cancel</el-button>
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
