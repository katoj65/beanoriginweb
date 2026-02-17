<script setup>
import { computed, watchEffect } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';
import InputError from '@/Components/InputError.vue';
import SubmitButton from '@/Components/SubmitButton.vue';

const page = usePage();
const farms = computed(() => page.props.farms ?? []);

const form = useForm({
farm_id: '',
crop_name: '',
crop_type: '',
quantity: '',
price: '',
location: '',
date_of_havest: '',
crop_grade: '',
process_method: '',
status: 'listed',
});

watchEffect(() => {
if (!form.farm_id && farms.value.length) {
form.farm_id = farms.value[0].id;
}
});

const submit = () => {
form.post(route('cooperative.produce.store'), {
preserveScroll: true,
onSuccess: () => {
form.reset();
form.status = 'listed';
form.farm_id = farms.value[0]?.id ?? '';
},
});
};

const crops=computed(()=>page.props.crops.data);





</script>

<template>
<CooperativeLayout>



<div class="container">
<div class="card card-bordered">
<div class="card-inner border-bottom">
<h6 class="title mb-1"><em class="icon ni ni-plus mr-1"></em>Add Produce</h6>
<p class="sub-text mb-0">Create a new coffee harvest batch for sale.</p>
</div>

<div class="card-inner">
<form class="row g-3" @submit.prevent="submit">
<div class="col-12">
<InputError :message="form.errors.farm_id" class="mt-2" />
</div>

<div class="col-12 col-md-4">
<label class="form-label">Crop Name</label>
 <el-select v-model="form.crop_name" placeholder="Select" class="form-control-like">
    <el-option
      v-for="item in crops"
      :key="item.name"
      :label="item.name"
      :value="item.name"
    />
  </el-select>
<InputError :message="form.errors.crop_name" class="mt-2" />
</div>

<div class="col-12 col-md-4">
<label class="form-label">Crop Type</label>
<input v-model="form.crop_type" type="text" class="form-control" />
<InputError :message="form.errors.crop_type" class="mt-2" />
</div>

<div class="col-12 col-md-4">
<label class="form-label">Quantity</label>
<input v-model="form.quantity" type="number" min="0" step="0.01" class="form-control" />
<InputError :message="form.errors.quantity" class="mt-2" />
</div>

<div class="col-12 col-md-4">
<label class="form-label">Price</label>
<input v-model="form.price" type="number" min="0" step="0.01" class="form-control" />
<InputError :message="form.errors.price" class="mt-2" />
</div>

<div class="col-12 col-md-4">
<label class="form-label">Location</label>
<input v-model="form.location" type="text" class="form-control" />
<InputError :message="form.errors.location" class="mt-2" />
</div>

<div class="col-12 col-md-4">
<label class="form-label">Date Of Havest</label>
<input v-model="form.date_of_havest" type="date" class="form-control" />
<InputError :message="form.errors.date_of_havest" class="mt-2" />
</div>

<div class="col-12 col-md-6">
<label class="form-label">Crop Grade</label>
<input v-model="form.crop_grade" type="text" class="form-control" />
<InputError :message="form.errors.crop_grade" class="mt-2" />
</div>

<div class="col-12 col-md-6">
<label class="form-label">Process Method</label>
<input v-model="form.process_method" type="text" class="form-control" />
<InputError :message="form.errors.process_method" class="mt-2" />
</div>


<div class="col-12 col-md-4 d-flex align-items-end">
<SubmitButton :title="'Save Produce'" :status="form.processing" />
</div>
</form>
</div>
</div>
</div>
</CooperativeLayout>
</template>
