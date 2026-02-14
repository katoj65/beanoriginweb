<script setup>
import { ref,computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';
import SubmitButton from '@/Components/SubmitButton.vue';
import InputError from '@/Components/InputError.vue';
import SelectBusinessComponent from '@/Components/SelectBusinessComponent.vue';

const showModal = ref(true);
const props = defineProps({
title: String,
response: Object,
});



const user = computed(() => {
return props.response.user;
});

const profile_state = computed(() => {
return props.response.user_profile_exists;
});

const profile=computed(()=>{
return props.response.user_profile;
});



const gender = ref([
{label:'Male',value:'male'},
{label:'Female',value:'female'},
{label:'Other',value:'other'}
]);



const form = useForm({
gender:'',
dob:'',
tel:'',
address:''
});



const isLoading = ref(false);
const submit = ()=>{
isLoading.value=true;
form.post('/store/profile',{
onFinish: () =>{
isLoading.value=false;
},
});













}




</script>

<template>
<app-layout>
<div class="container py-2">

<div class="card border p-4" style="height:550px;">
 <el-skeleton :rows="10" />
</div>









<div v-if="showModal" class="modal show d-block" id="popupModal" tabindex="-1" aria-labelledby="popupModalLabel" aria-modal="true" role="dialog">
<div class="modal-dialog modal-dialog-centered custom-modal-dialog">


<div class="modal-content responsive-modal-content" v-if="profile_state==false">
<div class="modal-body p-0 responsive-modal-body">
<div class="row g-0 modal-split-row">
<div class="col-12 col-md-4 pt-4 pt-md-5 modal-left-pane">


<div class="">
<div class="card-inner" style="background:none">
<div class="team">

<div class="user-card user-card-s2">
<div class="user-avatar lg bg-danger">
<span>JM</span>
<div class="status dot dot-lg dot-success"></div>
</div>
<div class="user-info">
<h6>
{{ user.fname+' '+user.lname }}
</h6>
<span class="sub-text">User Account</span>
</div>
</div>
<ul class="team-info">
<li><span>Email</span><span>{{ user.email }} </span></li>





</ul>
<div class="team-view">

</div>
</div><!-- .team -->
</div><!-- .card-inner -->
</div>






</div>
<div class="col-12 col-md-8 bg-white modal-right-pane">
<div class="card h-100 border-0 rounded-0">
<div class="card-inner p-3 p-md-4">

<div class="card-title-group mb-1">
<div class="card-title">
<h5 class="title">
Create your profile
</h5>
</div>
</div>



<form class="mt-3 mt-md-4 card p-3 p-md-4 profile-form-card" @submit.prevent="submit">



<div class="form-group">
<label class="form-label" for="default-01">
Gender
<input-error :message="form.errors.gender"/>
</label>
<div class="form-control-wrap">
 <el-select v-model="form.gender" placeholder="Select" style="width: 100%" id="default-01">
<el-option v-for="(item,key) in gender" :key="key"
:label="item.label"
:value="item.value"
/>
 </el-select>


</div>
</div>




<div class="form-group">
<label class="form-label" for="default-dob">Date of Birth
<input-error :message="form.errors.dob"/>
</label>
<div class="form-control-wrap">
<input type="date" class="form-control" id="default-dob" placeholder="Enter date of birth" v-model="form.dob"/>
</div>
</div>





<div class="form-group">
<label class="form-label" for="default-02">Telephone Number
<input-error :message="form.errors.tel"/>
</label>
<div class="form-control-wrap">
<input type="text" class="form-control" id="default-02" placeholder="Enter telephone number" v-model="form.tel"/>
</div>
</div>




<div class="form-group">
<label class="form-label" for="default-04">Address
<input-error :message="form.errors.address"/>
</label>
<div class="form-control-wrap">
<input type="text" class="form-control" id="default-04" placeholder="Enter your address" v-model="form.address"/>
</div>
</div>



<div class="form-group">
<submit-button :title="'Save'" :status="isLoading"/>
</div>
</form>











</div>
</div>







</div>
</div>











</div>
</div>
<select-business-component v-else :user="user" :profile="profile" />















</div>
</div>
<div v-if="showModal" class="modal-backdrop fade show"></div>


</div>



</app-layout>
</template>

<style scoped>
.custom-modal-dialog {
max-width: 60%;
margin: 1.25rem auto;
}

.responsive-modal-content {
border: 0;
border-radius: 20px;
overflow: hidden;
}

.responsive-modal-body {
max-height: min(86vh, 760px);
overflow-y: auto;
}

.modal-left-pane {
padding: 0 1.25rem 1.25rem;
}

.modal-right-pane {
border-left: 1px solid #eef2f7;
}

.profile-form-card {
border-radius: 16px;
}

.contact-line {
display: flex;
align-items: center;
gap: 0.4rem;
}

.contact-icon {
width: 1rem;
text-align: left;
}

@media (max-width: 768px) {
.custom-modal-dialog {
max-width: 95%;
margin: 0.75rem auto;
}

.responsive-modal-content {
border-radius: 14px;
}

.modal-left-pane {
padding: 1rem;
}

.modal-right-pane {
border-left: 0;
border-top: 1px solid #eef2f7;
}

.profile-form-card {
border-radius: 12px;
}
}
</style>
