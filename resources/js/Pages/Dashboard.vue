<script setup>
import { ref,computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { Calendar } from '@element-plus/icons-vue'

const showModal = ref(true);
const props = defineProps({
  title: String,
  response: Object,
});

const user = computed(() => {
  return props.response.user;
});



const gender = ref([
{label:'Male',value:'male'},
{label:'Female',value:'female'},
{label:'Other',value:'other'}
]);





const form=useForm({
gender:'',
dob:'',
tel:'',
address:''
});


const submit =()=>{
alert();
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
<div class="modal-content responsive-modal-content">

<div class="modal-body p-0 responsive-modal-body">
<div class="row g-0 modal-split-row">
<div class="col-12 col-md-5 pt-4 pt-md-5 modal-left-pane">


<div>
<div class="card-inner">
<div class="team">
<div class="user-card user-card-s2">
<el-avatar :size="50" src="https://cube.elemecdn.com/9/c2/f0ee8a3c7c9638a54940382568c9dpng.png" />
<div class="user-info">
<h6>
{{ user.fname+' '+user.lname}}
</h6>
<span class="">Logged in as user</span>
<span class="contact-line">
<em class="icon ni ni-mail contact-icon"></em>
<span>{{ user.email }}</span>
</span>
<span class="contact-line">
<!-- <em class="icon ni ni-call contact-icon"></em>
<span>+88 01713-123656</span> -->
</span>
</div>
</div>
<div class="team-view">
<!-- <a href="html/user-details-regular.html" class="btn btn-block btn-dim btn-primary"><span>View Profile</span></a> -->
</div>
</div><!-- .team -->
</div><!-- .card-inner -->
</div>





</div>
<div class="col-12 col-md-7 bg-white modal-right-pane">
<div class="card h-100 border-0 rounded-0">
<div class="card-inner p-3 p-md-4">

<div class="card-title-group mb-1">
<div class="card-title">
<h5 class="title">
Create your profile
</h5>
</div>
</div>



<form class="mt-3 mt-md-4 card border p-3 p-md-4 profile-form-card">
<div class="form-group">
<label class="form-label" for="default-01">
Gender
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
<label class="form-label" for="default-dob">Date of Birth</label>
<div class="form-control-wrap">
<input type="date" class="form-control" id="default-dob" placeholder="Enter date of birth"/>
</div>
</div>





<div class="form-group">
<label class="form-label" for="default-02">Telephone Number</label>
<div class="form-control-wrap">
<input type="text" class="form-control" id="default-02" placeholder="Enter telephone number" v-model="form.tel"/>
</div>
</div>




<div class="form-group">
<label class="form-label" for="default-04">Address</label>
<div class="form-control-wrap">
<input type="text" class="form-control" id="default-04" placeholder="Input placeholder" v-model="form.address"/>
</div>
</div>



<div class="form-group">

<div class="form-control-wrap">
<input type="submit" class="btn btn-lg btn-primary btn-block" value="Save">
</div>
</div>


</form>











</div>
</div>







</div>
</div>











</div>
</div>
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
