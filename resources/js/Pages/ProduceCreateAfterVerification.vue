<script setup>
import { computed, watchEffect, ref } from 'vue';
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
date_of_harvest: '',
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

const crops = computed(() => page.props.crops?.data ?? []);
const crop_type = computed(() => page.props.crop_type?.data ?? []);
const process_methods = computed(() => page.props.process_method?.data ?? []);
const crop_grade = computed(() => page.props.crop_grade?.data ?? []);
const farmer=computed(()=>page.props.farmer.data);




</script>

<template>
<CooperativeLayout>



<div class="container">
<div class="card card-bordered">
<div class="card-inner border-bottom">
<h6 class="title mb-1"><em class="icon ni ni-plus mr-1"></em>Add Batch</h6>
<p class="sub-text mb-0">Create a new coffee harvest batch for sale.</p>
</div>

<div class="card-inner">

<div class="verified-farmer-section">
<h6 class="title mb-2">Verified Farmer</h6>
<ul class="nk-support border mb-3 support-ticket-list">
<li class="nk-support-item">
<div class="user-avatar support-avatar">
<span>DM</span>
</div>
<div class="nk-support-content">
<div class="title support-title">
<h5 class="support-name mb-0">{{ farmer.name || 'Farmer' }}</h5>
<span class="badge badge-dot badge-dot-xs bg-success ms-1">Verified</span>
</div>
<div class="support-meta">
<span class="meta-pill"><em class="icon ni ni-users mr-1"></em>Gender: {{ farmer.gender }}</span>
<span class="meta-pill"><em class="icon ni ni-growth mr-1"></em>Primary Crop: {{ farmer.produce }}</span>
<span class="meta-pill"><em class="icon ni ni-map-pin mr-1"></em>Location: {{ farmer.location }}</span>
</div>
<!-- <span class="time support-time"><em class="icon ni ni-clock mr-1"></em>Updated 2 hours ago</span> -->
</div>
</li>

</ul>
</div>











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
<el-select v-model="form.crop_type" placeholder="Select" class="form-control-like">
    <el-option
      v-for="item in crop_type"
      :key="item.name"
      :label="item.name"
      :value="item.name"
    />
  </el-select>
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
<input v-model="form.date_of_harvest" type="date" class="form-control" />
<InputError :message="form.errors.date_of_havest" class="mt-2" />
</div>

<div class="col-12 col-md-6">
<label class="form-label">Crop Grade</label>
<el-select v-model="form.crop_grade" placeholder="Select" class="form-control-like">
    <el-option
      v-for="item in crop_grade"
      :key="item.name"
      :label="item.name"
      :value="item.name"
    />
  </el-select>
<InputError :message="form.errors.crop_grade" class="mt-2" />
</div>

<div class="col-12 col-md-6">
<label class="form-label">Process Method</label>
<el-select v-model="form.process_method" placeholder="Select" class="form-control-like">
    <el-option
      v-for="item in process_methods"
      :key="item.name"
      :label="item.name"
      :value="item.name"
    />
  </el-select>
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

<style scoped>
.support-ticket-list {
  border-radius: 12px;
  border: 1px solid #e5edf6 !important;
  background: #ffffff;
  overflow: hidden;
}

.support-avatar {
  background: linear-gradient(135deg, #e6f0ff, #d8e8ff);
  color: #1e40af;
  font-weight: 700;
}

.support-title {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 8px;
  margin-bottom: 10px;
}

.support-name {
  color: #1f2b46;
  font-weight: 700;
}

.support-meta {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  margin-bottom: 8px;
}

.meta-pill {
  display: inline-flex;
  align-items: center;
  padding: 6px 10px;
  border-radius: 999px;
  background: #eef4fb;
  color: #44566c;
  font-size: 12px;
  font-weight: 500;
}

.support-time {
  display: inline-flex;
  align-items: center;
  color: #8094ae;
  font-size: 12px;
}

.verified-farmer-section {
  border-bottom: 1px solid #e5edf6;
  padding-bottom: 14px;
  margin-bottom: 16px;
}
</style>
