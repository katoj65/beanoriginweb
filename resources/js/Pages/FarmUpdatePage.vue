<script setup>
import { computed } from 'vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';
import InputError from '@/Components/InputError.vue';
import SubmitButton from '@/Components/SubmitButton.vue';
import { Back } from '@element-plus/icons-vue';
import { ElNotification } from 'element-plus';

const page = usePage();
const farm = computed(() => page.props.farm?.data ?? page.props.farm ?? {});

const form = useForm({
farm_name: farm.value.farm_name ?? '',
location: farm.value.location ?? '',
area_acres: farm.value.area_acres ?? '',
});

const submit = () => {
const id = farm.value.id;
if (!id) return;

form.put(route('farmer.farms.update', { id }), {
onSuccess: () => {
ElNotification({
title: 'Success',
message: page.props.flash?.success || 'Farm details updated successfully.',
type: 'success',
});
},
});
};

const goBack = () => {
const id = farm.value.id;
if (!id) {
window.history.back();
return;
}

router.get(route('cooperative.farms.show', { id }));
};
</script>

<template>
<CooperativeLayout>
<div class="container">
<div class="row g-gs">
<div class="col-12">
<div class="card">
<div class="card-inner border-bottom d-flex justify-content-between align-items-center">
<div>
<h6 class="title mb-1">Update Farm</h6>
<p class="sub-text mb-0">Edit farm details and save changes.</p>
</div>
<el-button :icon="Back" @click="goBack">Back</el-button>
</div>

<div class="card-inner">

<div class="row">
<div class="col-12 col-md-2"></div>
<div class="col-12 col-md-8">
<form class="row g-3 border m-2 p-2 rounded" @submit.prevent="submit">
<div class="col-12 col-md-6">
<label class="form-label">Farm Name</label>
<el-input v-model="form.farm_name" placeholder="Main farm name" />
<InputError :message="form.errors.farm_name" class="mt-2" />
</div>

<div class="col-12 col-md-6">
<label class="form-label">Location</label>
<el-input v-model="form.location" placeholder="Village, sub county, district" />
<InputError :message="form.errors.location" class="mt-2" />
</div>

<div class="col-12">
<label class="form-label">Area (Acres)</label>
<el-input v-model="form.area_acres" type="number" min="0" step="0.01" placeholder="0.00" />
<InputError :message="form.errors.area_acres" class="mt-2" />
</div>

<div class="col-12 col-md-6 d-flex align-items-end">
<SubmitButton :title="'Update Farm'" :status="form.processing" />
</div>
</form>
</div>
<div class="col-12 col-md-2"></div>

</div>

</div>
</div>
</div>
</div>
</div>
</CooperativeLayout>
</template>
