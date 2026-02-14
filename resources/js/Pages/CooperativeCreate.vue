<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import SubmitButton from '@/Components/SubmitButton.vue';
import InputError from '@/Components/InputError.vue';





const form=useForm({
legal_name:'',
name:'',
reg_num:'',
reg_date:'',
district:'',
physical_address:'',
postal_address:'',
email:'',
telephone:'',
website:''


});


const isLoading=ref(false);
const submit=()=>{
isLoading.value=true;
form.post(route('cooperative-store'),{
onFinish:()=>{
isLoading.value=false;
}
});



}





</script>

<template>
<AppLayout>
<div class="container">
<div class="card card-bordered">
<div class="card-inner">
<h6 class="title mb-0">Create Cooperative Account</h6>
<p class="sub-text mt-1 mb-4">Provide the official details of your cooperative.</p>
<form class="row g-3" @submit.prevent="submit">
<div class="col-12">
<label class="form-label" for="legal_name">Legal Cooperative Name</label>
<input id="legal_name" type="text" class="form-control" placeholder="Enter legal cooperative name" v-model="form.legal_name" />
<input-error :message="form.errors.legal_name" />
</div>

<div class="col-12">
<label class="form-label" for="trading_name">Trading Name (if different)</label>
<input id="trading_name" type="text" class="form-control" placeholder="Enter trading name" v-model="form.name" />
<input-error :message="form.errors.name" />
</div>

<div class="col-12 col-md-6">
<label class="form-label" for="registration_number">Registration Number</label>
<input id="registration_number" type="text" class="form-control" placeholder="Enter registration number" v-model="form.reg_num" />
<input-error :message="form.errors.reg_num" />
</div>

<div class="col-12 col-md-6">
<label class="form-label" for="registration_date">Registration Date</label>
<input id="registration_date" type="date" class="form-control" v-model="form.reg_date" />
<input-error :message="form.errors.reg_date" />
</div>

<div class="col-12">
<label class="form-label" for="region_district">Region / District</label>
<input id="region_district" type="text" class="form-control" placeholder="Enter region or district" v-model="form.district" />
<input-error :message="form.errors.district" />
</div>

<div class="col-12">
<label class="form-label" for="physical_address">Physical Address</label>
<input id="physical_address" type="text" class="form-control" placeholder="Enter physical address" v-model="form.physical_address" />
<input-error :message="form.errors.physical_address" />
</div>

<div class="col-12">
<label class="form-label" for="postal_address">Postal Address</label>
<input id="postal_address" type="text" class="form-control" placeholder="Enter postal address" v-model="form.postal_address" />
<input-error :message="form.errors.postal_address" />
</div>

<div class="col-12 col-md-6">
<label class="form-label" for="official_email">Official Email</label>
<input id="official_email" type="email" class="form-control" placeholder="Enter official email" v-model="form.email" />
<input-error :message="form.errors.email" />
</div>

<div class="col-12 col-md-6">
<label class="form-label" for="official_phone">Official Phone Number</label>
<input id="official_phone" type="tel" class="form-control" placeholder="Enter official phone number" v-model="form.telephone" />
<input-error :message="form.errors.telephone" />
</div>

<div class="col-12">
<label class="form-label" for="website">Website (optional)</label>
<input id="website" type="url" class="form-control" placeholder="https://example.com" v-model="form.website" />
<input-error :message="form.errors.website" />
</div>

<div class="col-2 pt-2">
<submit-button title="Save" :status="isLoading"/>
</div>
</form>

</div>
</div>
</div>
</AppLayout>
</template>

<style scoped>
.title {
  font-size: 1.15rem;
}

.sub-text {
  font-size: 0.95rem;
}

.form-label {
  font-size: 0.95rem;
}

.form-control {
  font-size: 0.98rem;
}

:deep(.text-red-600) {
  margin-top: 8px;
}
</style>
