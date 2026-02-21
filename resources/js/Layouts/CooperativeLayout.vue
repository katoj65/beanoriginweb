<script setup>
import { ref } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import { Search, Bell, User } from '@element-plus/icons-vue';
import { onMounted, computed } from 'vue';





const logout = () => {
router.post(route('logout'));
};




onMounted(()=>{
console.log(usePage);
});


const  app_user=computed(()=>{
const page=usePage();
const data=page.props.auth.user;
return {
id:data.id,
fname:data.fname,
lname:data.lname,
email:data.email
};
});


const cooperative=computed(()=>{
const page=usePage();
const data=page.props;
return data.cooperative;
});




</script>

<template>


<div class="nk-body npc-default has-apps-sidebar has-sidebar ">
<div class="nk-app-root">




<div class="nk-apps-sidebar is-silver">
<div class="nk-apps-brand">
<a :href="route('dashboard')" class="logo-link">
<img class="logo-light logo-img" src="../../images/logo.png"  alt="logo" style="border-radius:20px;">
<img class="logo-dark logo-img" src="../../images/logo.png"  alt="logo-dark" style="border-radius:20px;">
</a>
</div>
<div class="nk-sidebar-element">
<div class="nk-sidebar-body">
<div class="nk-sidebar-content" data-simplebar>
<div class="nk-sidebar-menu">
<!-- Menu -->
<ul class="nk-menu apps-menu">
<li class="nk-menu-item">
<Link :href="cooperative?.id ? route('cooperative.show', cooperative.id) : route('dashboard')" class="nk-menu-link" title="Cooperative Home">
<span class="nk-menu-icon"><em class="icon ni ni-home-alt"></em></span>
</Link>
</li>
<li class="nk-menu-hr"></li>
<li class="nk-menu-item">
<Link :href="route('dashboard')" class="nk-menu-link" title="Dashboard Overview">
<span class="nk-menu-icon"><em class="icon ni ni-dashboard"></em></span>
</Link>
</li>
<li class="nk-menu-item">
<Link :href="route('dashboard')" class="nk-menu-link" title="Trade Activity">
<span class="nk-menu-icon"><em class="icon ni ni-tranx"></em></span>
</Link>
</li>
<li class="nk-menu-item">
<Link :href="route('cooperative.produce')" class="nk-menu-link" title="Batches & Listings">
<span class="nk-menu-icon"><em class="icon ni ni-card-view"></em></span>
</Link>
</li>
<li class="nk-menu-item">
<Link :href="route('cooperative.farmers')" class="nk-menu-link" title="Farmers">
<span class="nk-menu-icon"><em class="icon ni ni-users"></em></span>
</Link>
</li>
<li class="nk-menu-item">
<Link :href="route('cooperative.notifications')" class="nk-menu-link" title="Notifications">
<span class="nk-menu-icon"><em class="icon ni ni-bell"></em></span>
</Link>
</li>
<li class="nk-menu-item">
<Link :href="route('cooperative.account.settings')" class="nk-menu-link" title="Compliance & Settings">
<span class="nk-menu-icon"><em class="icon ni ni-shield-check"></em></span>
</Link>
</li>
</ul>
</div>
</div>
<div class="nk-sidebar-profile nk-sidebar-profile-fixed dropdown">
<a href="#" class="toggle sidebar-profile-trigger" data-target="profileDD" aria-label="Open profile menu">
<div class="user-avatar sidebar-profile-avatar">
<span>JO</span>
</div>
<span class="sidebar-profile-caret"><em class="icon ni ni-chevron-up"></em></span>
</a>
<div class="dropdown-menu dropdown-menu-md m-1 nk-sidebar-profile-dropdown sidebar-profile-menu" data-content="profileDD">
<div class="dropdown-inner user-card-wrap d-none d-md-block">
<div class="user-card sidebar-user-card">
<div class="user-avatar sidebar-profile-avatar">
<span>JO</span>
</div>
<div class="user-info">
<span class="lead-text">Joshua Origin</span>
<span class="sub-text text-soft">joshua@commodityorigin.com</span>
</div>
</div>
</div>
<div class="dropdown-inner">
<ul class="link-list">
<li><a href="html/user-profile-regular.html"><em class="icon ni ni-user-alt"></em><span>View Profile</span></a></li>
<li><a href="html/user-profile-setting.html"><em class="icon ni ni-setting-alt"></em><span>Account Setting</span></a></li>
<li><a href="html/user-profile-activity.html"><em class="icon ni ni-activity-alt"></em><span>Login Activity</span></a></li>
</ul>
</div>
<div class="dropdown-inner">
<ul class="link-list">
<li><a href="#"><em class="icon ni ni-signout"></em><span>Sign out</span></a></li>
</ul>
</div>
</div>
</div>
</div>
</div>
</div>









<!-- main @s -->
<div class="nk-main ">
<!-- wrap @s -->
<div class="nk-wrap ">



<!-- main header @s -->
<div class="nk-header nk-header-fixed is-light">
<div class="container-fluid">
<div class="nk-header-wrap">
<div class="nk-menu-trigger d-xl-none ms-n1">
<a href="#" class="nk-nav-toggle nk-quick-nav-icon" data-target="sidebarMenu"><em class="icon ni ni-menu"></em></a>
</div>
<div class="nk-header-app-name">
<!-- <div class="nk-header-app-logo">
<em class="icon ni ni-dashlite bg-purple-dim"></em>
</div> -->
<div class="nk-header-app-info">
<span class="sub-text header-app-subtitle">
Cooperative
</span>
<span class="lead-text header-app-title text-capitalize">
{{ cooperative.legal_name }}
</span>
</div>
</div>
<div class="nk-header-menu is-light">
<div class="nk-header-menu-inner">


<ul class="nk-menu nk-menu-main">
<li class="nk-menu-item">
<Link :href="cooperative?.id ? route('cooperative.show', cooperative.id) : '#'" class="nk-menu-link">
<span class="nk-menu-text">Home</span>
</Link>
</li>
<li class="nk-menu-item">
<Link :href="route('cooperative.farmers')" class="nk-menu-link">
<span class="nk-menu-text">Farmers</span>
</Link>
</li>
<li class="nk-menu-item">
<Link :href="route('cooperative.produce')" class="nk-menu-link">
<span class="nk-menu-text">Commodity</span>
</Link>
</li><!-- .nk-menu-item -->
<li class="nk-menu-item">
<Link :href="route('cooperative.batches.listed')" class="nk-menu-link">
<span class="nk-menu-text">Batch Listed</span>
</Link>
</li>
</ul>




</div>
</div>




<div class="nk-header-tools d-flex align-items-center">
<el-button size="medium" :icon="Search" round>Search</el-button>
<Link :href="route('cooperative.notifications')" class="ml-4 mr-4 d-inline-flex">
<el-button :icon="Bell" circle />
</Link>
<el-dropdown trigger="click" placement="bottom-end" popper-class="user-dropdown-popper" class="p-0 m-0 ml-1">
<span class="el-dropdown-link m-0 p-0 d-flex align-items-center cursor-pointer">
<el-button :icon="User" circle />
<span class="ml-2">
{{ app_user.fname }}
</span>
<em class="icon ni ni-chevron-down ml-1 header-caret-icon"></em>

</span>
<template #dropdown>
<el-dropdown-menu>
<el-dropdown-item>
<Link :href="route('cooperative.profile')" class="d-block w-100 text-dark">
View Profile
</Link>
</el-dropdown-item>
<el-dropdown-item>
<Link :href="route('cooperative.account.settings')" class="d-block w-100 text-dark">
Account Settings
</Link>
</el-dropdown-item>
<el-dropdown-item>
<Link :href="route('cooperative.help')" class="d-block w-100 text-dark">
Help
</Link>
</el-dropdown-item>
<el-dropdown-item divided>



<form @submit.prevent="logout">
<button type="submit">
Sign Out
</button>
</form>


</el-dropdown-item>
</el-dropdown-menu>
</template>
</el-dropdown>
</div>





</div>
</div>
</div>



<!-- main header @e -->
<div class="nk-sidebar" data-content="sidebarMenu">
<div class="nk-sidebar-inner" data-simplebar>
<ul class="nk-menu nk-menu-md">
<li class="nk-menu-heading">
<h6 class="overline-title text-primary-alt">Trading Operations</h6>
</li>

<li class="nk-menu-item">
<Link :href="route('dashboard')" class="nk-menu-link">
<span class="nk-menu-icon"><em class="icon ni ni-dashboard"></em></span>
<span class="nk-menu-text">Operations Dashboard</span>
</Link>
</li>


<li class="nk-menu-item">
<Link :href="route('cooperative.batches.listed')" class="nk-menu-link">
<span class="nk-menu-icon"><em class="icon ni ni-package"></em></span>
<span class="nk-menu-text">Batch Listed</span>
</Link>
</li>

<li class="nk-menu-item">
<Link :href="route('cooperative.produce')" class="nk-menu-link">
<span class="nk-menu-icon"><em class="icon ni ni-bag"></em></span>
<span class="nk-menu-text">Commodity Listings</span>
</Link>
</li>
<li class="nk-menu-item">
<Link :href="route('cooperative.produce.create')" class="nk-menu-link">
<span class="nk-menu-icon"><em class="icon ni ni-plus-circle"></em></span>
<span class="nk-menu-text">Create Commodity Batch</span>
</Link>
</li>
<li class="nk-menu-item">
<Link :href="route('dashboard')" class="nk-menu-link">
<span class="nk-menu-icon"><em class="icon ni ni-growth"></em></span>
<span class="nk-menu-text">Carbon Credits</span>
</Link>
</li>
<li class="nk-menu-item">
<Link :href="route('cooperative.notifications')" class="nk-menu-link">
<span class="nk-menu-icon"><em class="icon ni ni-bell"></em></span>
<span class="nk-menu-text">Market Notifications</span>
</Link>
</li>

<li class="nk-menu-heading">
<h6 class="overline-title text-primary-alt">Cooperative Services</h6>
</li>
<li class="nk-menu-item">
<Link :href="route('cooperative.farmers')" class="nk-menu-link">
<span class="nk-menu-icon"><em class="icon ni ni-users"></em></span>
<span class="nk-menu-text">Farmer Directory</span>
</Link>
</li>
<li class="nk-menu-item">
<Link :href="route('cooperative.farmers.create')" class="nk-menu-link">
<span class="nk-menu-icon"><em class="icon ni ni-user"></em></span>
<span class="nk-menu-text">Farmer Onboarding</span>
</Link>
</li>
<li class="nk-menu-item">
<Link :href="route('cooperative.farms.create')" class="nk-menu-link">
<span class="nk-menu-icon"><em class="icon ni ni-growth"></em></span>
<span class="nk-menu-text">Farm Registration</span>
</Link>
</li>
<li class="nk-menu-item">
<Link :href="route('cooperative.profile')" class="nk-menu-link">
<span class="nk-menu-icon"><em class="icon ni ni-building"></em></span>
<span class="nk-menu-text">Organization Profile</span>
</Link>
</li>

<li class="nk-menu-heading">
<h6 class="overline-title text-primary-alt">Risk & Support</h6>
</li>
<li class="nk-menu-item">
<Link :href="route('cooperative.account.settings')" class="nk-menu-link">
<span class="nk-menu-icon"><em class="icon ni ni-shield-check"></em></span>
<span class="nk-menu-text">Trade Compliance</span>
</Link>
</li>
<li class="nk-menu-item">
<Link :href="route('cooperative.help')" class="nk-menu-link">
<span class="nk-menu-icon"><em class="icon ni ni-help-alt"></em></span>
<span class="nk-menu-text">Help Center</span>
</Link>
</li>
</ul>
</div>
</div>



<!-- content @s -->
<div class="nk-content pt-3">
<div class="container-fluid pt-0">
<div class="nk-content-inner pt-0">
<div class="nk-content-body pt-0">

<slot></slot>
</div>
</div>
</div>
</div>
<!-- content @e -->
</div>
<!-- wrap @e -->
</div>
<!-- main @e -->
</div>
<!-- app-root @e -->


</div>


</template>

<style scoped>
.nk-apps-sidebar.is-silver {
  background: linear-gradient(180deg, #f3f4f6 0%, #e5e7eb 100%) !important;
  border-right: 1px solid #d1d5db;
}

.nk-apps-sidebar.is-silver .nk-menu-link {
  color: #4b5563;
}

.nk-apps-sidebar.is-silver .nk-menu-link:hover,
.nk-apps-sidebar.is-silver .nk-menu-item.active .nk-menu-link {
  color: #111827;
  background-color: rgba(255, 255, 255, 0.65);
}

.nk-apps-sidebar.is-silver .nk-menu-icon .icon {
  color: #6b7280;
}

.nk-apps-sidebar.is-silver .nk-menu-hr {
  border-color: #d1d5db;
}

.nk-apps-sidebar.is-silver .nk-sidebar-profile,
.nk-apps-sidebar.is-silver .nk-sidebar-profile-fixed {
  background: linear-gradient(180deg, #f3f4f6 0%, #e5e7eb 100%) !important;
  border-top: 1px solid #d1d5db;
}

.sidebar-profile-trigger {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.4rem;
  padding: 0.45rem 0.25rem;
}

.sidebar-profile-avatar {
  width: 2rem;
  height: 2rem;
  border-radius: 999px;
  background: linear-gradient(135deg, #9ca3af, #6b7280);
  color: #ffffff;
  font-weight: 700;
  font-size: 0.72rem;
}

.sidebar-profile-caret .icon {
  color: #6b7280;
  font-size: 0.75rem;
}

.sidebar-profile-menu {
  border: 1px solid #d1d5db;
  box-shadow: 0 10px 24px rgba(17, 24, 39, 0.12);
  border-radius: 0.65rem;
  overflow: hidden;
}

.sidebar-user-card {
  background: #f9fafb;
}

.nk-header-app-info {
  display: flex;
  flex-direction: column;
  gap: 0.1rem;
  line-height: 1.1;
}

.header-app-subtitle {
  text-transform: uppercase;
  letter-spacing: 0.06em;
  font-size: 0.7rem;
  color: #8b5e3c;
}

.header-app-title {
  font-weight: 700;
  font-size: 1rem;
  color: #2f2016;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

.nk-header-tools {
  gap: 0.25rem;
}

.header-tool-btn {
  width: 2.25rem;
  height: 2.25rem;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

.header-tool-icon {
  font-size: 1.45rem;
  line-height: 1;
}

.header-caret-icon {
  font-size: 0.95rem;
  line-height: 1;
}

:deep(.user-dropdown-popper.el-popper) {
  margin-left: 8px;
}
</style>
