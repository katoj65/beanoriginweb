

<script setup>
import { router, useForm } from '@inertiajs/vue3';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';
import InputError from '@/Components/InputError.vue';
import SubmitButton from '@/Components/SubmitButton.vue';
import { Back } from '@element-plus/icons-vue';

const form = useForm({
first_name: '',
last_name: '',
phone_number: '',
email: '',
gender: '',
date_of_birth: '',
national_id: '',
district: '',
sub_county: '',
village: '',
primary_crop: '',
});

const submit = () => {
form.post(route('cooperative.farmers.store'));
};

const goBack = () => {
router.get(route('cooperative.farmers'));
};
</script>

<template>
<CooperativeLayout>
<div class="container">
<div class="row g-gs">
<div class="col-12">
<div class="card card-bordered">
<div class="card-inner border-bottom d-flex justify-content-between align-items-center">
<div>
<h6 class="title mb-1">Add Farmer</h6>
<p class="sub-text mb-0">Register a farmer under your cooperative.</p>
</div>
<el-button :icon="Back" @click="goBack">Back</el-button>
</div>

<div class="card-inner">
<form class="row g-3" @submit.prevent="submit">
<div class="col-12 col-md-6">
<label class="form-label">First Name</label>
<el-input v-model="form.first_name" placeholder="Enter first name" />
<InputError :message="form.errors.first_name" class="mt-2" />
</div>

<div class="col-12 col-md-6">
<label class="form-label">Last Name</label>
<el-input v-model="form.last_name" placeholder="Enter last name" />
<InputError :message="form.errors.last_name" class="mt-2" />
</div>

<div class="col-12 col-md-6">
<label class="form-label">Phone Number</label>
<el-input v-model="form.phone_number" placeholder="+256..." />
<InputError :message="form.errors.phone_number" class="mt-2" />
</div>

<div class="col-12 col-md-6">
<label class="form-label">Email</label>
<el-input v-model="form.email" type="email" placeholder="farmer@email.com" />
<InputError :message="form.errors.email" class="mt-2" />
</div>

<div class="col-12 col-md-6">
<label class="form-label">Gender</label>
<el-select v-model="form.gender" class="w-100" placeholder="Select gender">
<el-option label="Male" value="male" />
<el-option label="Female" value="female" />
<el-option label="Other" value="other" />
</el-select>
<InputError :message="form.errors.gender" class="mt-2" />
</div>

<div class="col-12 col-md-6">
<label class="form-label">Date of Birth</label>
<el-date-picker
v-model="form.date_of_birth"
type="date"
value-format="YYYY-MM-DD"
format="YYYY-MM-DD"
class="w-100"
placeholder="Select date of birth"
/>
<InputError :message="form.errors.date_of_birth" class="mt-2" />
</div>

<div class="col-12 col-md-6">
<label class="form-label">National ID</label>
<el-input v-model="form.national_id" placeholder="National ID" />
<InputError :message="form.errors.national_id" class="mt-2" />
</div>

<div class="col-12 col-md-6">
<label class="form-label">Primary Crop</label>
<el-input v-model="form.primary_crop" placeholder="Coffee, Maize..." />
<InputError :message="form.errors.primary_crop" class="mt-2" />
</div>

<div class="col-12 col-md-6">
<label class="form-label">District</label>
<el-input v-model="form.district" placeholder="District" />
<InputError :message="form.errors.district" class="mt-2" />
</div>

<div class="col-12 col-md-6">
<label class="form-label">Sub County</label>
<el-input v-model="form.sub_county" placeholder="Sub county" />
<InputError :message="form.errors.sub_county" class="mt-2" />
</div>

<div class="col-12 col-md-12">
<label class="form-label">Village</label>
<el-input v-model="form.village" placeholder="Village" />
<InputError :message="form.errors.village" class="mt-2" />
</div>

<div class="col-12 col-md-6 d-flex align-items-end">
<SubmitButton :title="'Save Farmer'" :status="form.processing" />
</div>
</form>
</div>
</div>
</div>
</div>
</div>
</CooperativeLayout>
</template>
