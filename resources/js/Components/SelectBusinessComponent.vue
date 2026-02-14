<script setup>
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';


const props=defineProps({
user: Object,
profile:Object
});



const user=computed(()=>{
return props.user;
});

const profile=computed(()=>{
return props.profile;
});





const list=ref([
{name:'Farmer',value:'Produces crops and raw farm produce.',url:'/services/farmer'},
{name:'Cooperative',value:'Groups farmers for shared services and sales.',url:'/services/cooperative'},
{name:'Investor',value:'Provides capital for agribusiness growth.',url:'/services/investor'},
{name:'Financier',value:'Offers loans and financial support.',url:'/services/financier'},
{name:'Exporter',value:'Ships products to international markets.',url:'/services/exporter'},
]);


const getInitials = (name) => {
  if (!name) return 'NA';
  return name
    .split(' ')
    .map((part) => part[0])
    .join('')
    .slice(0, 2)
    .toUpperCase();
};






const form=useForm({
item:''
});


const submit=(item)=>{
form.item=item;
form.post('/update/user-account-status');
}



















</script>
<template>
<div class="modal-content responsive-modal-content">
<div class="modal-body p-0 responsive-modal-body">
<div class="row g-0 modal-split-row">
<div class="col-12 col-md-4 pt-3 modal-left-pane">


<div class="">
<div class="card-inner">
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
<li><span>Gender</span><span>
{{ profile.gender }}
</span></li>
<li><span>
DOB
</span><span>
{{ profile.dob }}
</span></li>
<li><span>Email</span><span>{{ user.email }} </span></li>
<li><span>Tel</span><span>{{ profile.tel }} </span></li>




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
Select a Service
</h5>
</div>
</div>



<div class="card-body">



<div class="card card-full">
<div class="card-inner-group">

<a
v-for="(l,key) in list"
:key="key"
href="#"
class="card-inner card-inner-md border-0 service-item"
@click.prevent="submit(l.name)"
>
<div class="user-card service-card">
<div class="user-avatar bg-primary-dim service-icon-avatar">
<em class="icon ni ni-briefcase-fill service-leading-icon"></em>
</div>
<div class="user-info service-info">
<span class="lead-text">
{{ l.name }}
</span>
<span class="sub-text">
{{ l.value }}
</span>
</div>
<em class="icon ni ni-arrow-right service-arrow"></em>
</div>
</a>






</div>
</div>




</div>









</div>
</div>







</div>
</div>











</div>
</div>
</template>

<style scoped>
.service-item {
  text-decoration: none;
  color: inherit;
  border-radius: 12px;
  border-bottom: 1px solid #eef2f7;
  transition: background-color 0.2s ease, transform 0.2s ease;
}

.service-item:last-child {
  border-bottom: 0;
}

.service-item:hover {
  background-color: #f8fafc;
  transform: translateY(-1px);
}

.service-card {
  align-items: center;
}

.service-icon-avatar {
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

.service-leading-icon {
  font-size: 1.1rem;
  color: #000000;
  line-height: 1;
}

.service-info {
  flex: 1;
}

.service-info .lead-text {
  font-size: 1rem;
}

.service-info .sub-text {
  font-size: 0.9rem;
}

.service-arrow {
  font-size: 1rem;
  color: #64748b;
}
</style>
