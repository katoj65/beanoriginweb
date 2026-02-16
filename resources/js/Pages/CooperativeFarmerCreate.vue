

<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';
import InputError from '@/Components/InputError.vue';
import SubmitButton from '@/Components/SubmitButton.vue';

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
status: 'pending',
});

const submit = () => {
form.post(route('cooperative.farmers.store'));
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
<Link :href="route('cooperative.farmers')" class="btn btn-light btn-sm">
Back to Farmers
</Link>
</div>

<div class="card-inner">
<form class="row g-3" @submit.prevent="submit">
<div class="col-12 col-md-6">
<label class="form-label">First Name</label>
<input v-model="form.first_name" type="text" class="form-control" placeholder="Enter first name" />
<InputError :message="form.errors.first_name" class="mt-2" />
</div>

<div class="col-12 col-md-6">
<label class="form-label">Last Name</label>
<input v-model="form.last_name" type="text" class="form-control" placeholder="Enter last name" />
<InputError :message="form.errors.last_name" class="mt-2" />
</div>

<div class="col-12 col-md-6">
<label class="form-label">Phone Number</label>
<input v-model="form.phone_number" type="text" class="form-control" placeholder="+256..." />
<InputError :message="form.errors.phone_number" class="mt-2" />
</div>

<div class="col-12 col-md-6">
<label class="form-label">Email</label>
<input v-model="form.email" type="email" class="form-control" placeholder="farmer@email.com" />
<InputError :message="form.errors.email" class="mt-2" />
</div>

<div class="col-12 col-md-4">
<label class="form-label">Gender</label>
<select v-model="form.gender" class="form-control">
<option value="">Select gender</option>
<option value="male">Male</option>
<option value="female">Female</option>
<option value="other">Other</option>
</select>
<InputError :message="form.errors.gender" class="mt-2" />
</div>

<div class="col-12 col-md-4">
<label class="form-label">Date of Birth</label>
<input v-model="form.date_of_birth" type="date" class="form-control" />
<InputError :message="form.errors.date_of_birth" class="mt-2" />
</div>

<div class="col-12 col-md-4">
<label class="form-label">Status</label>
<select v-model="form.status" class="form-control">
<option value="pending">Pending</option>
<option value="active">Active</option>
<option value="suspended">Suspended</option>
<option value="exited">Exited</option>
</select>
<InputError :message="form.errors.status" class="mt-2" />
</div>

<div class="col-12 col-md-6">
<label class="form-label">National ID</label>
<input v-model="form.national_id" type="text" class="form-control" placeholder="National ID" />
<InputError :message="form.errors.national_id" class="mt-2" />
</div>

<div class="col-12 col-md-6">
<label class="form-label">Primary Crop</label>
<input v-model="form.primary_crop" type="text" class="form-control" placeholder="Coffee, Maize..." />
<InputError :message="form.errors.primary_crop" class="mt-2" />
</div>

<div class="col-12 col-md-4">
<label class="form-label">District</label>
<input v-model="form.district" type="text" class="form-control" placeholder="District" />
<InputError :message="form.errors.district" class="mt-2" />
</div>

<div class="col-12 col-md-4">
<label class="form-label">Sub County</label>
<input v-model="form.sub_county" type="text" class="form-control" placeholder="Sub county" />
<InputError :message="form.errors.sub_county" class="mt-2" />
</div>

<div class="col-12 col-md-4">
<label class="form-label">Village</label>
<input v-model="form.village" type="text" class="form-control" placeholder="Village" />
<InputError :message="form.errors.village" class="mt-2" />
</div>

<div class="col-2">
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
