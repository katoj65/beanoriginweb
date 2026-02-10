<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import TableDefault from '@/Tables/TableDefault.vue';
import { reactive,ref } from 'vue';

const tabs=ref([
{title:'Farmers',subtitle:'Farmers registered',stats:'100',icon:'ni-users'},
{title:'Coffee Available',subtitle:'Batches for sale',stats:'200',icon:'ni-package'},
{title:'Coffee Sold',subtitle:'Batches sold',stats:'300',icon:'ni-tranx'},
{title:'Buyers',subtitle:'Active buyers',stats:'400',icon:'ni-user-circle'}
]);




const payment_status=ref([
{item:'Revenue Generated',value:'Shs 50 M',icon:'ni-coins'},
{item:'Pending Payment',value:'50/2000',icon:'ni-clock'},
{item:'Farmers Paid',value:'1000',icon:'ni-user-check'},
{item:'Coffee Bags Sold',value:'2,000,000',icon:'ni-bag'},
{item:'Carbon Credit',value:'850 Tons CO2',icon:'ni-globe'},
{item:'Trees Planted',value:'15,000',icon:'ni-growth'},
{item:'Loans Issued',value:'320',icon:'ni-wallet'},
]);











</script>

<template>
<app-layout>







<div class="row">
<div class="col-12 col-md-3" v-for="(t,key) in tabs" :key="key">
<div class="nk-order-ovwg-data card border">
<div class="amount">
{{ t.title }}
</div>
<div class="info"><strong>{{ t.stats }}</strong></div>
<div class="title"><em :class="`icon ni ${t.icon}`"></em>
{{ t.subtitle }}
</div>
</div>
</div>
</div>








<div class="row mt-4">
<div class="col-12 col-md-8">

<div class="card card-bordered card-preview">
<div class="card-header bg-white border-bottom">
<h6>Coffee Price Offerings</h6>
</div>
<table-default/>
</div>







</div>
<div class="col-12 col-md-4">


<div class="card card-bordered pricing">
<div class="pricing-head">
<div class="pricing-title">
<h4 class="card-title title">Payments</h4>
<p class="sub-text">Enjoy entry level of invest &amp; earn.</p>
</div>
<div class="card-text">
<div class="row">
<div class="col-6">
<span class="h4 fw-500">1.67%</span>
<span class="sub-text">Daily Interest</span>
</div>
<div class="col-6">
<span class="h4 fw-500">30</span>
<span class="sub-text">Term Days</span>
</div>
</div>
</div>
</div>


<div class="pricing-body mb-0 pb-0">
<ul class="pricing-features">
<li v-for="(p,key) in payment_status" :key="key">
<em :class="`icon ni ${p.icon}`" style="margin-right: 8px; color: #000000;"></em>
<span class="w-50">
{{ p.item }}
</span> - <span class="ms-auto">
{{ p.value }}
</span></li>

</ul>
</div>


</div>







</div>
</div>






<div class="row mt-4">
<div class="col-12 col-md-4">

<div class="card card-bordered h-100">
<div class="card-inner">
<div class="card-title-group align-start mb-3">
<div class="card-title">
<h6 class="title">Coffee Market Overview</h6>
<p>In last 15 days buy and sells overview. <a href="#" class="link link-sm">Detailed Stats</a></p>
</div>
</div><!-- .card-title-group -->







</div><!-- .card-inner -->
</div>








</div>



<div class="col-12 col-md-4">
<div class="card card-bordered card-full">
<div class="card-inner-group">
<div class="card-inner">
<div class="card-title-group">
<div class="card-title">
<h6 class="title">Orders Overview</h6>
<p>In last 15 days buy and sells overview.</p>
</div>
</div>
</div>
</div>
<div class="card-body">
<div class="text-align">
<el-progress type="circle" :percentage="25" />
<el-progress type="circle" :percentage="80" class="ml-5" status="success" />
</div>







</div>
</div>
















</div>
<div class="col-12 col-md-4">

<div class="card card-bordered card-full">
<div class="card-inner-group">
<div class="card-inner">
<div class="card-title-group">
<div class="card-title">
<h6 class="title">New Users</h6>
</div>
<div class="card-tools">
<a href="html/user-list-regular.html" class="link">View All</a>
</div>
</div>
</div>
<div class="card-inner card-inner-md">
<div class="user-card">
<div class="user-avatar bg-primary-dim">
<span>AB</span>
</div>
<div class="user-info">
<span class="lead-text">Abu Bin Ishtiyak</span>
<span class="sub-text">info@softnio.com</span>
</div>
<div class="user-action">
<div class="drodown">
<a href="#" class="dropdown-toggle btn btn-icon btn-trigger me-n1" data-bs-toggle="dropdown" aria-expanded="false"><em class="icon ni ni-more-h"></em></a>
<div class="dropdown-menu dropdown-menu-end">
<ul class="link-list-opt no-bdr">
<li><a href="#"><em class="icon ni ni-setting"></em><span>Action Settings</span></a></li>
<li><a href="#"><em class="icon ni ni-notify"></em><span>Push Notification</span></a></li>
</ul>
</div>
</div>
</div>
</div>
</div>
<div class="card-inner card-inner-md">
<div class="user-card">
<div class="user-avatar bg-pink-dim">
<span>SW</span>
</div>
<div class="user-info">
<span class="lead-text">Sharon Walker</span>
<span class="sub-text">sharon-90@example.com</span>
</div>
<div class="user-action">
<div class="drodown">
<a href="#" class="dropdown-toggle btn btn-icon btn-trigger me-n1" data-bs-toggle="dropdown" aria-expanded="false"><em class="icon ni ni-more-h"></em></a>
<div class="dropdown-menu dropdown-menu-end">
<ul class="link-list-opt no-bdr">
<li><a href="#"><em class="icon ni ni-setting"></em><span>Action Settings</span></a></li>
<li><a href="#"><em class="icon ni ni-notify"></em><span>Push Notification</span></a></li>
</ul>
</div>
</div>
</div>
</div>
</div>
<div class="card-inner card-inner-md">
<div class="user-card">
<div class="user-avatar bg-warning-dim">
<span>GO</span>
</div>
<div class="user-info">
<span class="lead-text">Gloria Oliver</span>
<span class="sub-text">gloria_72@example.com</span>
</div>
<div class="user-action">
<div class="drodown">
<a href="#" class="dropdown-toggle btn btn-icon btn-trigger me-n1" data-bs-toggle="dropdown" aria-expanded="false"><em class="icon ni ni-more-h"></em></a>
<div class="dropdown-menu dropdown-menu-end">
<ul class="link-list-opt no-bdr">
<li><a href="#"><em class="icon ni ni-setting"></em><span>Action Settings</span></a></li>
<li><a href="#"><em class="icon ni ni-notify"></em><span>Push Notification</span></a></li>
</ul>
</div>
</div>
</div>
</div>
</div>
<div class="card-inner card-inner-md">
<div class="user-card">
<div class="user-avatar bg-success-dim">
<span>PS</span>
</div>
<div class="user-info">
<span class="lead-text">Phillip Sullivan</span>
<span class="sub-text">phillip-85@example.com</span>
</div>
<div class="user-action">
<div class="drodown">
<a href="#" class="dropdown-toggle btn btn-icon btn-trigger me-n1" data-bs-toggle="dropdown" aria-expanded="false"><em class="icon ni ni-more-h"></em></a>
<div class="dropdown-menu dropdown-menu-end">
<ul class="link-list-opt no-bdr">
<li><a href="#"><em class="icon ni ni-setting"></em><span>Action Settings</span></a></li>
<li><a href="#"><em class="icon ni ni-notify"></em><span>Push Notification</span></a></li>
</ul>
</div>
</div>
</div>
</div>
</div>
<div class="card-inner card-inner-md">
<div class="user-card">
<div class="user-avatar bg-danger-dim">
<span>TB</span>
</div>
<div class="user-info">
<span class="lead-text">Thomas Barry</span>
<span class="sub-text">thomas-85@example.com</span>
</div>
<div class="user-action">
<div class="drodown">
<a href="#" class="dropdown-toggle btn btn-icon btn-trigger me-n1" data-bs-toggle="dropdown" aria-expanded="false"><em class="icon ni ni-more-h"></em></a>
<div class="dropdown-menu dropdown-menu-end">
<ul class="link-list-opt no-bdr">
<li><a href="#"><em class="icon ni ni-setting"></em><span>Action Settings</span></a></li>
<li><a href="#"><em class="icon ni ni-notify"></em><span>Push Notification</span></a></li>
</ul>
</div>
</div>
</div>
</div>
</div>
</div>
</div>

</div>
</div>









</app-layout>
</template>

<style scoped>
.modern-stat-card {
background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
border: 1px solid rgba(111, 78, 55, 0.1);
border-radius: 16px;
padding: 1.5rem;
transition: all 0.3s ease;
position: relative;
overflow: hidden;
}

.modern-stat-card:hover {
transform: translateY(-4px);
border-color: rgba(111, 78, 55, 0.2);
}

.stat-title {
font-size: 1.1rem;
font-weight: 700;
color: #6F4E37;
margin-bottom: 0.75rem;
letter-spacing: -0.01em;
}

.stat-number {
font-size: 2.5rem;
font-weight: 800;
background: linear-gradient(135deg, #6F4E37 0%, #8B4513 100%);
-webkit-background-clip: text;
-webkit-text-fill-color: transparent;
background-clip: text;
margin-bottom: 0.5rem;
line-height: 1;
}

.stat-subtitle {
font-size: 0.875rem;
color: #64748b;
font-weight: 500;
display: flex;
align-items: center;
gap: 0.5rem;
}

.stat-icon {
color: #10b981;
font-size: 1rem;
}

/* Enhanced modern styling for existing card structure */
.nk-order-ovwg-data.card.border {
border-radius: 12px;
padding: 0.875rem;
border: none;
transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
background: linear-gradient(135deg, #ffffff 0%, #fafbfc 50%, #f1f5f9 100%);
position: relative;
overflow: hidden;
backdrop-filter: blur(10px);
}

.nk-order-ovwg-data.card.border::before {
content: '';
position: absolute;
top: 0;
left: 0;
right: 0;
height: 2px;
background: linear-gradient(90deg, transparent 0%, rgba(111, 78, 55, 0.1) 50%, transparent 100%);
}

.nk-order-ovwg-data.card.border:hover {
transform: translateY(-4px) scale(1.01);
background: linear-gradient(135deg, #ffffff 0%, #fefefe 50%, #f8fafc 100%);
}

.nk-order-ovwg-data .amount {
font-size: 0.95rem;
font-weight: 800;
color: #000000;
margin-bottom: 0.6rem;
letter-spacing: -0.02em;
position: relative;
z-index: 2;
line-height: 1.2;
text-transform: uppercase;
font-family: system-ui, -apple-system, sans-serif;
}

.nk-order-ovwg-data .info {
margin-bottom: 0.5rem;
position: relative;
z-index: 2;
}

.nk-order-ovwg-data .info strong {
font-size: 1.6rem;
font-weight: 900;
color: #000000;
line-height: 1.1;
letter-spacing: -0.03em;
display: block;
font-family: system-ui, -apple-system, sans-serif;
}

.nk-order-ovwg-data .title {
font-size: 0.8rem;
color: #000000;
font-weight: 600;
display: flex;
align-items: center;
gap: 0.4rem;
position: relative;
z-index: 2;
letter-spacing: -0.01em;
line-height: 1.3;
font-family: system-ui, -apple-system, sans-serif;
}

.nk-order-ovwg-data .title em {
color: #10b981;
font-size: 0.9rem;
transition: transform 0.2s ease;
}

.nk-order-ovwg-data.card.border:hover .title em {
transform: scale(1.1);
}

@media (max-width: 768px) {
.nk-order-ovwg-data.card.border {
padding: 0.75rem;
}

.nk-order-ovwg-data .amount {
font-size: 0.85rem;
}

.nk-order-ovwg-data .info strong {
font-size: 1.4rem;
}

.nk-order-ovwg-data .title {
font-size: 0.75rem;
}
}
</style>
