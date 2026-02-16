<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';
import SubmitButton from '@/Components/SubmitButton.vue';
import InputError from '@/Components/InputError.vue';
import { ElMessage } from 'element-plus';
import SuccessMessage from '@/Components/SuccessMessage.vue';

const page = usePage();
const cooperative = page.props?.cooperative ?? null;

const form = useForm({
category: '',
priority: 'normal',
subject: '',
description: '',
preferred_channel: 'email',
contact_name: '',
contact_email: cooperative?.email ?? '',
contact_phone: cooperative?.tel ?? '',
});

const categories = [
{ label: 'Technical Issue', value: 'technical' },
{ label: 'Payments & Finance', value: 'payments' },
{ label: 'Farmer Management', value: 'farmers' },
{ label: 'Compliance & Verification', value: 'compliance' },
{ label: 'Partnership & Market Access', value: 'partnership' },
{ label: 'Other', value: 'other' },
];

const priorities = [
{ label: 'Low', value: 'low' },
{ label: 'Normal', value: 'normal' },
{ label: 'High', value: 'high' },
{ label: 'Critical', value: 'critical' },
];

const submitHelpRequest = () => {
form.post(route('cooperative.help.store'), {
onSuccess: () => {
ElMessage({
message: page.props.flash?.success,
type: 'success',
placement: 'bottom-left',
customClass: 'el-success-message',
});
form.reset();
},
});


};



</script>

<template>
<CooperativeLayout>
<div class="container">
<div class="row g-gs">



<div class="col-12 col-xl-8">
<div class="card card-bordered">
<div class="card-inner border-bottom">
<h6 class="title mb-1">Submit Help Request</h6>
<p class="sub-text mb-0">Describe your issue so the right admin team can respond quickly.</p>
</div>




<div class="card-inner">
<form class="row g-3" @submit.prevent="submitHelpRequest">
<div class="col-12 col-md-6">
<label class="form-label">Category</label>
<el-select v-model="form.category" placeholder="Select category" style="width: 100%">
<el-option v-for="item in categories" :key="item.value" :label="item.label" :value="item.value" />
</el-select>
              <input-error :message="form.errors.category" class="mt-2" />
</div>

<div class="col-12 col-md-6">
<label class="form-label">Priority</label>
<el-select v-model="form.priority" placeholder="Select priority" style="width: 100%">
<el-option v-for="item in priorities" :key="item.value" :label="item.label" :value="item.value" />
</el-select>
<input-error :message="form.errors.priority" class="mt-2" />
</div>

<div class="col-12">
<label class="form-label">Subject</label>
<input v-model="form.subject" type="text" class="form-control" placeholder="Brief title of your issue" />
              <input-error :message="form.errors.subject" class="mt-2" />
</div>

<div class="col-12">
<label class="form-label">Description</label>
<textarea v-model="form.description" class="form-control" rows="6" placeholder="Describe the issue, when it started, affected areas, and what you already tried."></textarea>
              <input-error :message="form.errors.description" class="mt-2" />
</div>

<div class="col-12 col-md-4">
<label class="form-label">Preferred Channel</label>
<el-select v-model="form.preferred_channel" style="width: 100%">
<el-option label="Email" value="email" />
<el-option label="Phone Call" value="phone" />
<el-option label="In-app Message" value="inapp" />
</el-select>
<input-error :message="form.errors.preferred_channel" class="mt-2" />
</div>

<div class="col-12 col-md-4">
<label class="form-label">Contact Name</label>
<input v-model="form.contact_name" type="text" class="form-control" placeholder="Responsible person" />
<input-error :message="form.errors.contact_name" class="mt-2" />
</div>

<div class="col-12 col-md-4">
<label class="form-label">Contact Email</label>
<input v-model="form.contact_email" type="email" class="form-control" placeholder="you@cooperative.org" />
<input-error :message="form.errors.contact_email" class="mt-2" />
</div>

<div class="col-12 col-md-12">
<label class="form-label">Contact Phone</label>
<input v-model="form.contact_phone" type="tel" class="form-control" placeholder="+256..." />
<input-error :message="form.errors.contact_phone" class="mt-2" />
</div>

<div class="col-5">
<submit-button :title="'Submit Help Request'" :status="form.processing" />
</div>
</form>
</div>
</div>
</div>










<div class="col-12 col-xl-4">
<div class="card card-bordered mb-3">
<div class="card-inner border-bottom">
<h6 class="title mb-1">Support Contacts</h6>
<p class="sub-text mb-0">Direct channels to admins and stakeholders.</p>
</div>
<div class="card-inner">
<div class="contact-item">
<em class="icon ni ni-headphone"></em>
<div>
<strong>System Admin Desk</strong>
<p class="sub-text mb-0">support@beanorigin.com</p>
</div>
</div>
<div class="contact-item">
<em class="icon ni ni-building"></em>
<div>
<strong>Operations Stakeholder Team</strong>
<p class="sub-text mb-0">operations@beanorigin.com</p>
</div>
</div>
<div class="contact-item">
<em class="icon ni ni-coins"></em>
<div>
<strong>Finance Stakeholder Team</strong>
<p class="sub-text mb-0">finance@beanorigin.com</p>
</div>
</div>
</div>
</div>

<div class="card card-bordered">
<div class="card-inner border-bottom">
<h6 class="title mb-1">Help Guidelines</h6>
<p class="sub-text mb-0">Tips for faster resolution.</p>
</div>
<div class="card-inner">
<ul class="help-guidelines">
<li>Choose the most accurate category and priority.</li>
<li>Include exact error messages and timestamps.</li>
<li>Attach account/transaction references when relevant.</li>
<li>Provide one active contact person for follow-up.</li>
</ul>
</div>
</div>
</div>






</div>
</div>
</CooperativeLayout>
</template>

<style scoped>
.help-header {
border-radius: 14px;
background: linear-gradient(135deg, #ffffff 0%, #f8fafc 58%, #eef2ff 100%);
}

.contact-item {
display: flex;
align-items: flex-start;
gap: 10px;
padding: 10px 0;
border-bottom: 1px solid #edf2f7;
}

.contact-item:last-child {
border-bottom: 0;
}

.contact-item .icon {
font-size: 1rem;
margin-top: 3px;
}

.help-guidelines {
margin: 0;
padding-left: 16px;
}

.help-guidelines li {
margin-bottom: 8px;
}

.help-guidelines li:last-child {
margin-bottom: 0;
}
</style>
