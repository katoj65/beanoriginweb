<script setup>
import { computed, watchEffect } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { ElNotification } from 'element-plus';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';
import InputError from '@/Components/InputError.vue';
import SubmitButton from '@/Components/SubmitButton.vue';

const page = usePage();

const form = useForm({
verification_id:'',
crop_name: '',
crop_type: '',
phone_number: '',
quantity: '',
price: '',
ripe_percentage: null,
density_percentage: null,
date_of_harvest: '',
crop_grade: '',

});

const sanitizePercentageInteger = (value) => {
if (value === '' || value === null || value === undefined) return null;
const numericValue = Number(value);
if (Number.isNaN(numericValue)) return null;
return Math.min(100, Math.max(0, Math.trunc(numericValue)));
};

const submit = () => {
form.post(route('commodity.store'), {
preserveScroll: true,
onSuccess: () => {
ElNotification({
title: 'Success',
message: 'Commodity harvest submitted successfully.',
type: 'success',
});

},
});
};

const crops = computed(() => page.props.crops?.data ?? []);
const crop_type = computed(() => page.props.crop_type?.data ?? []);
const crop_grade = computed(() => page.props.crop_grade?.data ?? []);



watchEffect(()=>{
const segments = page.url.split('/');
const id=segments[4];
form.verification_id=id;

});


</script>

<template>
<CooperativeLayout>



<div class="container">
<div class="card">
<div class="card-inner border-bottom">
<h6 class="title mb-1"><em class="icon ni ni-plus mr-1"></em>Add Commodity/ Harvest</h6>
<p class="sub-text mb-0">Create a new coffee harvest.</p>
</div>

<div class="card-inner">

<!-- <div class="verified-farmer-section">
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
<span class="meta-pill"><em class="icon ni ni-home mr-1"></em>Farms/ Gardens: {{ farmsCount }}</span>


</div>

</div>
</li>

</ul>
</div> -->











<form class="row g-3" @submit.prevent="submit">
<div class="col-12 col-md-6">
<label class="form-label" for="01">Crop Name</label>
<el-select v-model="form.crop_name" placeholder="Select" class="form-control-like" id="01">
<el-option
v-for="item in crops"
:key="item.name"
:label="item.name"
:value="item.name"
/>
</el-select>
<InputError :message="form.errors.crop_name" class="mt-2" />
</div>

<div class="col-12 col-md-6">
<label class="form-label" for="02">Crop Type</label>
<el-select v-model="form.crop_type" placeholder="Select" class="form-control-like" id="02">
<el-option
v-for="item in crop_type"
:key="item.name"
:label="item.name"
:value="item.name"
/>
</el-select>
<InputError :message="form.errors.crop_type" class="mt-2" />
</div>

<div class="col-12 col-md-6">
<label class="form-label" for="03">Weight (Kgs)</label>
<input v-model="form.quantity" type="number" min="0" step="0.01" class="form-control" placeholder="Enter batch quantity in Kgs" id="03"/>
<InputError :message="form.errors.quantity" class="mt-2" />
</div>

<div class="col-12 col-md-6">
<label class="form-label" for="04">Price</label>
<input v-model="form.price" type="number" min="0" step="0.01" class="form-control" placeholder="Enter price" id="04"/>
<InputError :message="form.errors.price" class="mt-2" />
</div>

<div class="col-12 col-md-6">
<label class="form-label" for="04a">Ripe Percentage</label>
<input
:value="form.ripe_percentage ?? ''"
type="number"
min="0"
max="100"
step="1"
class="form-control"
placeholder="Enter ripe percentage"
id="04a"
@input="form.ripe_percentage = sanitizePercentageInteger($event.target.value)"
/>
<InputError :message="form.errors.ripe_percentage" class="mt-2" />
</div>

<div class="col-12 col-md-6">
<label class="form-label" for="04b">Density Percentage</label>
<input
:value="form.density_percentage ?? ''"
type="number"
min="0"
max="100"
step="1"
class="form-control"
placeholder="Enter density percentage"
id="04b"
@input="form.density_percentage = sanitizePercentageInteger($event.target.value)"
/>
<InputError :message="form.errors.density_percentage" class="mt-2" />
</div>


<div class="col-12 col-md-6">
<label class="form-label" for="06">Date of Harvest</label>
<input v-model="form.date_of_harvest" type="date" class="form-control" id="06"/>
<InputError :message="form.errors.date_of_harvest" class="mt-2" />
</div>

<div class="col-12 col-md-6">
<label class="form-label" for="07">Crop Grade</label>
<el-select v-model="form.crop_grade" placeholder="Select" class="form-control-like" id="07">
<el-option label="None" value="N/A"/>
<el-option
v-for="item in crop_grade"
:key="item.name"
:label="item.name"
:value="item.name"
/>
</el-select>
<InputError :message="form.errors.crop_grade" class="mt-2" />
</div>

<div class="col-12 col-md-12">
<label class="form-label" for="03a">Farmer Telephone Number</label>
<input
v-model="form.phone_number"
type="tel"
class="form-control"
placeholder="Enter farmer telephone number"
id="03a"/>
<InputError :message="form.errors.phone_number" class="mt-2" />
</div>






<div class="col-12 col-md-12 d-flex align-items-end mt-4">
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
