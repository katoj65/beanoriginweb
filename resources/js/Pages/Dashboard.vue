<script setup>
import { ref,computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const showModal = ref(true);

const props = defineProps({
  title: String,
  response: Object,
});



const user = computed(() => {
  return props.response.user;
});



</script>

<template>
<app-layout>
<div class="container py-2">

<div class="card border p-4" style="height:550px;">


 <el-skeleton :rows="10" />
</div>














<div v-if="showModal" class="modal show d-block" id="popupModal" tabindex="-1" aria-labelledby="popupModalLabel" aria-modal="true" role="dialog">
<div class="modal-dialog modal-dialog-centered custom-modal-dialog">
<div class="modal-content" style="border:solid 0px #62748E;border-radius: 20px;">
<div class="modal-header border-0">
<h5 class="modal-title" id="popupModalLabel">Create Profile</h5>

<!-- <button type="button" class="btn-close" aria-label="Close" @click="showModal = false"></button> -->

</div>
<div class="modal-body">

<div class="row">
<div class="col-12 col-md-5">


<div>
<div class="card-inner">
<div class="team">
<div class="user-card user-card-s2">
<el-avatar :size="50" src="https://cube.elemecdn.com/9/c2/f0ee8a3c7c9638a54940382568c9dpng.png" />
<div class="user-info">
<h6>
{{ user.fname+' '+user.lname}}
</h6>
<span class="sub-text">Logged in as user</span>
<span class="sub-text contact-line">
<em class="icon ni ni-mail contact-icon"></em>
<span>{{ user.email }}</span>
</span>
<span class="sub-text contact-line">
<em class="icon ni ni-call contact-icon"></em>
<span>+88 01713-123656</span>
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
<div class="col-12 col-md-7">
<div class="card" style="border-radius: 20px;">
<div class="card-inner">
<div class="card-title-group mb-1">
<div class="card-title">
<!-- <h6 class="title">Investment Overview</h6> -->
</div>
</div>



<form class="p-3">

<div class="form-group">
    <label class="form-label" for="default-01">Input text label</label>
    <div class="form-control-wrap">
        <input type="text" class="form-control" id="default-01" placeholder="Input placeholder">
    </div>
</div>

<div class="form-group">
    <label class="form-label" for="default-01">Input text label</label>
    <div class="form-control-wrap">
        <input type="text" class="form-control" id="default-01" placeholder="Input placeholder">
    </div>
</div>
<div class="form-group">
    <label class="form-label" for="default-01">Input text label</label>
    <div class="form-control-wrap">
        <input type="text" class="form-control" id="default-01" placeholder="Input placeholder">
    </div>
</div>
<div class="form-group">
    <label class="form-label" for="default-01">Input text label</label>
    <div class="form-control-wrap">
        <input type="text" class="form-control" id="default-01" placeholder="Input placeholder">
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
}
}
</style>
