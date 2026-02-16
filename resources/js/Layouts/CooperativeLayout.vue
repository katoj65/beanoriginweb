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
<a href="html/cms/index.html" class="nk-menu-link" title="CMS Panel">
<span class="nk-menu-icon"><em class="icon ni ni-template"></em></span>
</a>
</li><!-- .nk-menu-item -->
<li class="nk-menu-hr"></li>
<li class="nk-menu-item">
<a :href="route('dashboard')" class="nk-menu-link" title="Analytics Dashboard">
<span class="nk-menu-icon"><em class="icon ni ni-dashboard"></em></span>
</a>
</li>
<li class="nk-menu-item">
<a :href="route('dashboard')" class="nk-menu-link" title="Sales Dashboard">
<span class="nk-menu-icon"><em class="icon ni ni-speed"></em></span>
</a>
</li>
<li class="nk-menu-item">
<a :href="route('dashboard')" class="nk-menu-link" title="Crypto Dashboard">
<span class="nk-menu-icon"><em class="icon ni ni-bitcoin-cash"></em></span>
</a>
</li>
<li class="nk-menu-item">
<a :href="route('dashboard')" class="nk-menu-link" title="Invest Dashboard">
<span class="nk-menu-icon"><em class="icon ni ni-invest"></em></span>
</a>
</li>
<li class="nk-menu-hr"></li>
<li class="nk-menu-item">
<a href="html/apps/mailbox.html" class="nk-menu-link" title="Mailbox">
<span class="nk-menu-icon"><em class="icon ni ni-inbox"></em></span>
</a>
</li>
<li class="nk-menu-item">
<a href="html/apps/messages.html" class="nk-menu-link" title="Messages">
<span class="nk-menu-icon"><em class="icon ni ni-chat"></em></span>
</a>
</li>
<li class="nk-menu-item">
<a href="html/apps/file-manager.html" class="nk-menu-link" title="File Manager">
<span class="nk-menu-icon"><em class="icon ni ni-folder"></em></span>
</a>
</li>
<li class="nk-menu-item">
<a href="html/apps/chats.html" class="nk-menu-link" title="Chats">
<span class="nk-menu-icon"><em class="icon ni ni-chat-circle"></em></span>
</a>
</li>
<li class="nk-menu-item">
<a href="html/apps/calendar.html" class="nk-menu-link" title="Calendar">
<span class="nk-menu-icon"><em class="icon ni ni-calendar"></em></span>
</a>
</li>
<li class="nk-menu-item">
<a href="html/apps/kanban.html" class="nk-menu-link">
<span class="nk-menu-icon"><em class="icon ni ni-template"></em></span>
</a>
</li>
<li class="nk-menu-hr"></li>
<li class="nk-menu-item">
<a href="html/components.html" class="nk-menu-link" title="Go to Components">
<span class="nk-menu-icon"><em class="icon ni ni-layers"></em></span>
</a>
</li>
</ul>
</div>
<div class="nk-sidebar-footer">
<ul class="nk-menu nk-menu-md">
<li class="nk-menu-item">
<a href="#" class="nk-menu-link" title="Settings">
<span class="nk-menu-icon"><em class="icon ni ni-setting"></em></span>
</a>
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
<li class="nk-menu-item has-sub">
<a href="#" class="nk-menu-link">
<span class="nk-menu-text">Products</span>
</a>
</li><!-- .nk-menu-item -->
</ul>




</div>
</div>




<div class="nk-header-tools d-flex align-items-center">
<el-button size="medium" :icon="Search" round>Search</el-button>
<el-button :icon="Bell" circle class="mr-2" />
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
<h6 class="overline-title text-primary-alt">Dashboards</h6>
</li><!-- .nk-menu-heading -->
<li class="nk-menu-item">
<a :href="route('dashboard')" class="nk-menu-link">
<span class="nk-menu-icon"><em class="icon ni ni-dashboard"></em></span>
<span class="nk-menu-text">Default Dashboard</span>
</a>
</li><!-- .nk-menu-item -->
<li class="nk-menu-item">
<a :href="route('dashboard')" class="nk-menu-link">
<span class="nk-menu-icon"><em class="icon ni ni-speed"></em></span>
<span class="nk-menu-text">Sales Dashboard</span>
</a>
</li><!-- .nk-menu-item -->
<li class="nk-menu-item">
<a :href="route('dashboard')" class="nk-menu-link">
<span class="nk-menu-icon"><em class="icon ni ni-bitcoin-cash"></em></span>
<span class="nk-menu-text">Crypto Dashboard</span>
</a>
</li><!-- .nk-menu-item -->
<li class="nk-menu-item">
<a :href="route('dashboard')" class="nk-menu-link">
<span class="nk-menu-icon"><em class="icon ni ni-coins"></em></span>
<span class="nk-menu-text">Invest Dashboard</span>
</a>
</li><!-- .nk-menu-item -->
<li class="nk-menu-heading">
<h6 class="overline-title text-primary-alt">Pre-Built Pages</h6>
</li><!-- .nk-menu-heading -->
<li class="nk-menu-item has-sub">
<a href="#" class="nk-menu-link nk-menu-toggle">
<span class="nk-menu-icon"><em class="icon ni ni-tile-thumb"></em></span>
<span class="nk-menu-text">Projects</span>
</a>
<ul class="nk-menu-sub">
<li class="nk-menu-item">
<a href="html/project-card.html" class="nk-menu-link"><span class="nk-menu-text">Project Cards</span></a>
</li>
<li class="nk-menu-item">
<a href="html/project-list.html" class="nk-menu-link"><span class="nk-menu-text">Project List</span></a>
</li>
</ul><!-- .nk-menu-sub -->
</li><!-- .nk-menu-item -->
<li class="nk-menu-item has-sub">
<a href="#" class="nk-menu-link nk-menu-toggle">
<span class="nk-menu-icon"><em class="icon ni ni-users"></em></span>
<span class="nk-menu-text">User Manage</span>
</a>
<ul class="nk-menu-sub">
<li class="nk-menu-item">
<a href="html/user-list-regular.html" class="nk-menu-link"><span class="nk-menu-text">User List - Regular</span></a>
</li>
<li class="nk-menu-item">
<a href="html/user-list-compact.html" class="nk-menu-link"><span class="nk-menu-text">User List - Compact</span></a>
</li>
<li class="nk-menu-item">
<a href="html/user-details-regular.html" class="nk-menu-link"><span class="nk-menu-text">User Details - Regular</span></a>
</li>
<li class="nk-menu-item">
<a href="html/user-profile-regular.html" class="nk-menu-link"><span class="nk-menu-text">User Profile - Regular</span></a>
</li>
<li class="nk-menu-item">
<a href="html/user-card.html" class="nk-menu-link"><span class="nk-menu-text">User Contact - Card</span></a>
</li>
</ul><!-- .nk-menu-sub -->
</li><!-- .nk-menu-item -->
<li class="nk-menu-item has-sub">
<a href="#" class="nk-menu-link nk-menu-toggle">
<span class="nk-menu-icon"><em class="icon ni ni-user-list"></em></span>
<span class="nk-menu-text">Customers</span>
</a>
<ul class="nk-menu-sub">
<li class="nk-menu-item">
<a href="html/customer-list.html" class="nk-menu-link"><span class="nk-menu-text">Customer List</span></a>
</li>
<li class="nk-menu-item">
<a href="html/customer-details.html" class="nk-menu-link"><span class="nk-menu-text">Customer Details</span></a>
</li>
</ul><!-- .nk-menu-sub -->
</li><!-- .nk-menu-item -->
<li class="nk-menu-item has-sub">
<a href="#" class="nk-menu-link nk-menu-toggle">
<span class="nk-menu-icon"><em class="icon ni ni-file-docs"></em></span>
<span class="nk-menu-text">AML / KYCs</span>
</a>
<ul class="nk-menu-sub">
<li class="nk-menu-item">
<a href="html/kyc-list-regular.html" class="nk-menu-link"><span class="nk-menu-text">KYC List - Regular</span></a>
</li>
<li class="nk-menu-item">
<a href="html/kyc-details-regular.html" class="nk-menu-link"><span class="nk-menu-text">KYC Details - Regular</span></a>
</li>
</ul><!-- .nk-menu-sub -->
</li><!-- .nk-menu-item -->
<li class="nk-menu-item has-sub">
<a href="#" class="nk-menu-link nk-menu-toggle">
<span class="nk-menu-icon"><em class="icon ni ni-tranx"></em></span>
<span class="nk-menu-text">Transactions</span>
</a>
<ul class="nk-menu-sub">
<li class="nk-menu-item">
<a href="html/transaction-basic.html" class="nk-menu-link"><span class="nk-menu-text">Tranx List - Basic</span></a>
</li>
<li class="nk-menu-item">
<a href="html/transaction-crypto.html" class="nk-menu-link"><span class="nk-menu-text">Tranx List - Crypto</span></a>
</li>
</ul><!-- .nk-menu-sub -->
</li><!-- .nk-menu-item -->
<li class="nk-menu-item has-sub">
<a href="#" class="nk-menu-link nk-menu-toggle">
<span class="nk-menu-icon"><em class="icon ni ni-file-docs"></em></span>
<span class="nk-menu-text">Invoice</span>
</a>
<ul class="nk-menu-sub">
<li class="nk-menu-item">
<a href="html/invoice-list.html" class="nk-menu-link"><span class="nk-menu-text">Invoice List</span></a>
</li>
<li class="nk-menu-item">
<a href="html/invoice-details.html" class="nk-menu-link"><span class="nk-menu-text">Invoice Details</span></a>
</li>
</ul><!-- .nk-menu-sub -->
</li><!-- .nk-menu-item -->
<li class="nk-menu-item has-sub">
<a href="#" class="nk-menu-link nk-menu-toggle">
<span class="nk-menu-icon"><em class="icon ni ni-card-view"></em></span>
<span class="nk-menu-text">Products</span>
</a>
<ul class="nk-menu-sub">
<li class="nk-menu-item">
<a href="html/product-list.html" class="nk-menu-link"><span class="nk-menu-text">Product List</span></a>
</li>
<li class="nk-menu-item">
<a href="html/product-card.html" class="nk-menu-link"><span class="nk-menu-text">Product Card</span></a>
</li>
<li class="nk-menu-item">
<a href="html/product-details.html" class="nk-menu-link"><span class="nk-menu-text">Product Details</span></a>
</li>
</ul><!-- .nk-menu-sub -->
</li><!-- .nk-menu-item -->
<li class="nk-menu-item">
<a href="html/pricing-table.html" class="nk-menu-link">
<span class="nk-menu-icon"><em class="icon ni ni-view-col"></em></span>
<span class="nk-menu-text">Pricing Table</span>
</a>
</li><!-- .nk-menu-item -->
<li class="nk-menu-item">
<a href="html/gallery.html" class="nk-menu-link">
<span class="nk-menu-icon"><em class="icon ni ni-img"></em></span>
<span class="nk-menu-text">Image Gallery</span>
</a>
</li><!-- .nk-menu-item -->
<li class="nk-menu-heading">
<h6 class="overline-title text-primary-alt">Misc Pages</h6>
</li><!-- .nk-menu-heading -->
<li class="nk-menu-item has-sub">
<a href="#" class="nk-menu-link nk-menu-toggle">
<span class="nk-menu-icon"><em class="icon ni ni-signin"></em></span>
<span class="nk-menu-text">Auth Pages</span>
</a>
<ul class="nk-menu-sub">
<li class="nk-menu-item">
<a href="html/pages/auths/auth-login.html" class="nk-menu-link" target="_blank"><span class="nk-menu-text">Login / Signin</span></a>
</li>
<li class="nk-menu-item">
<a href="html/pages/auths/auth-register.html" class="nk-menu-link" target="_blank"><span class="nk-menu-text">Register / Signup</span></a>
</li>
<li class="nk-menu-item">
<a href="html/pages/auths/auth-reset.html" class="nk-menu-link" target="_blank"><span class="nk-menu-text">Forgot Password</span></a>
</li>
<li class="nk-menu-item">
<a href="html/pages/auths/auth-success.html" class="nk-menu-link" target="_blank"><span class="nk-menu-text">Success / Confirm</span></a>
</li>
<li class="nk-menu-item">
<a href="#" class="nk-menu-link nk-menu-toggle"><span class="nk-menu-text">Classic Version - v2</span></a>
<ul class="nk-menu-sub">
<li class="nk-menu-item">
<a href="html/pages/auths/auth-login-v2.html" class="nk-menu-link" target="_blank"><span class="nk-menu-text">Login / Signin</span></a>
</li>
<li class="nk-menu-item">
<a href="html/pages/auths/auth-register-v2.html" class="nk-menu-link" target="_blank"><span class="nk-menu-text">Register / Signup</span></a>
</li>
<li class="nk-menu-item">
<a href="html/pages/auths/auth-reset-v2.html" class="nk-menu-link" target="_blank"><span class="nk-menu-text">Forgot Password</span></a>
</li>
<li class="nk-menu-item">
<a href="html/pages/auths/auth-success-v2.html" class="nk-menu-link" target="_blank"><span class="nk-menu-text">Success / Confirm</span></a>
</li>
</ul>
</li>
<li class="nk-menu-item">
<a href="#" class="nk-menu-link nk-menu-toggle"><span class="nk-menu-text">No Slider Version - v3</span></a>
<ul class="nk-menu-sub">
<li class="nk-menu-item">
<a href="html/pages/auths/auth-login-v3.html" class="nk-menu-link" target="_blank"><span class="nk-menu-text">Login / Signin</span></a>
</li>
<li class="nk-menu-item">
<a href="html/pages/auths/auth-register-v3.html" class="nk-menu-link" target="_blank"><span class="nk-menu-text">Register / Signup</span></a>
</li>
<li class="nk-menu-item">
<a href="html/pages/auths/auth-reset-v3.html" class="nk-menu-link" target="_blank"><span class="nk-menu-text">Forgot Password</span></a>
</li>
<li class="nk-menu-item">
<a href="html/pages/auths/auth-success-v3.html" class="nk-menu-link" target="_blank"><span class="nk-menu-text">Success / Confirm</span></a>
</li>
</ul>
</li>
</ul><!-- .nk-menu-sub -->
</li><!-- .nk-menu-item -->
<li class="nk-menu-item has-sub">
<a href="#" class="nk-menu-link nk-menu-toggle">
<span class="nk-menu-icon"><em class="icon ni ni-files"></em></span>
<span class="nk-menu-text">Error Pages</span>
</a>
<ul class="nk-menu-sub">
<li class="nk-menu-item">
<a href="html/pages/errors/404-classic.html" target="_blank" class="nk-menu-link"><span class="nk-menu-text">404 Classic</span></a>
</li>
<li class="nk-menu-item">
<a href="html/pages/errors/504-classic.html" target="_blank" class="nk-menu-link"><span class="nk-menu-text">504 Classic</span></a>
</li>
<li class="nk-menu-item">
<a href="html/pages/errors/404-s1.html" target="_blank" class="nk-menu-link"><span class="nk-menu-text">404 Modern</span></a>
</li>
<li class="nk-menu-item">
<a href="html/pages/errors/504-s1.html" target="_blank" class="nk-menu-link"><span class="nk-menu-text">504 Modern</span></a>
</li>
</ul><!-- .nk-menu-sub -->
</li><!-- .nk-menu-item -->
<li class="nk-menu-item has-sub">
<a href="#" class="nk-menu-link nk-menu-toggle">
<span class="nk-menu-icon"><em class="icon ni ni-files"></em></span>
<span class="nk-menu-text">Other Pages</span>
</a>
<ul class="nk-menu-sub">
<li class="nk-menu-item">
<a href="html/_blank.html" class="nk-menu-link"><span class="nk-menu-text">Blank / Startup</span></a>
</li>
<li class="nk-menu-item">
<a href="html/pages/faqs.html" class="nk-menu-link"><span class="nk-menu-text">Faqs / Help</span></a>
</li>
<li class="nk-menu-item">
<a href="html/pages/terms-policy.html" class="nk-menu-link"><span class="nk-menu-text">Terms / Policy</span></a>
</li>
<li class="nk-menu-item">
<a href="html/pages/regular-v1.html" class="nk-menu-link"><span class="nk-menu-text">Regular Page - v1</span></a>
</li>
<li class="nk-menu-item">
<a href="html/pages/regular-v2.html" class="nk-menu-link"><span class="nk-menu-text">Regular Page - v2</span></a>
</li>
</ul><!-- .nk-menu-sub -->
</li><!-- .nk-menu-item -->
<li class="nk-menu-heading">
<h6 class="overline-title text-primary-alt">Components</h6>
</li><!-- .nk-menu-heading -->
<li class="nk-menu-item has-sub">
<a href="#" class="nk-menu-link nk-menu-toggle">
<span class="nk-menu-icon"><em class="icon ni ni-layers"></em></span>
<span class="nk-menu-text">Ui Elements</span>
</a>
<ul class="nk-menu-sub">
<li class="nk-menu-item">
<a href="html/components/elements/alerts.html" class="nk-menu-link"><span class="nk-menu-text">Alerts</span></a>
</li>
<li class="nk-menu-item">
<a href="html/components/elements/accordions.html" class="nk-menu-link"><span class="nk-menu-text">Accordions</span></a>
</li>
<li class="nk-menu-item">
<a href="html/components/elements/avatar.html" class="nk-menu-link"><span class="nk-menu-text">Avatar</span></a>
</li>
<li class="nk-menu-item">
<a href="html/components/elements/badges.html" class="nk-menu-link"><span class="nk-menu-text">Badges</span></a>
</li>
<li class="nk-menu-item">
<a href="html/components/elements/buttons.html" class="nk-menu-link"><span class="nk-menu-text">Buttons</span></a>
</li>
<li class="nk-menu-item">
<a href="html/components/elements/buttons-group.html" class="nk-menu-link"><span class="nk-menu-text">Button Group</span></a>
</li>
<li class="nk-menu-item">
<a href="html/components/elements/breadcrumb.html" class="nk-menu-link"><span class="nk-menu-text">Breadcrumb</span></a>
</li>
<li class="nk-menu-item">
<a href="html/components/elements/cards.html" class="nk-menu-link"><span class="nk-menu-text">Cards</span></a>
</li>
<li class="nk-menu-item">
<a href="html/components/elements/carousel.html" class="nk-menu-link"><span class="nk-menu-text">Carousel</span></a>
</li>
<li class="nk-menu-item">
<a href="html/components/elements/list-dropdown.html" class="nk-menu-link"><span class="nk-menu-text">List Dropdown</span></a>
</li>
<li class="nk-menu-item">
<a href="html/components/elements/modals.html" class="nk-menu-link"><span class="nk-menu-text">Modals</span></a>
</li>
<li class="nk-menu-item">
<a href="html/components/elements/pagination.html" class="nk-menu-link"><span class="nk-menu-text">Pagination</span></a>
</li>
<li class="nk-menu-item">
<a href="html/components/elements/popover.html" class="nk-menu-link"><span class="nk-menu-text">Popovers</span></a>
</li>
<li class="nk-menu-item">
<a href="html/components/elements/progress.html" class="nk-menu-link"><span class="nk-menu-text">Progress</span></a>
</li>
<li class="nk-menu-item">
<a href="html/components/elements/preloader.html" class="nk-menu-link"><span class="nk-menu-text">Preloader</span> <span class="nk-menu-badge">New</span></a>
</li>
<li class="nk-menu-item">
<a href="html/components/elements/placeholders.html" class="nk-menu-link"><span class="nk-menu-text">Placeholders</span> <span class="nk-menu-badge">New</span></a>
</li>
<li class="nk-menu-item">
<a href="html/components/elements/spinner.html" class="nk-menu-link"><span class="nk-menu-text">Spinner</span></a>
</li>
<li class="nk-menu-item">
<a href="html/components/elements/tabs.html" class="nk-menu-link"><span class="nk-menu-text">Tabs</span></a>
</li>
<li class="nk-menu-item">
<a href="html/components/elements/toast.html" class="nk-menu-link"><span class="nk-menu-text">Toasts</span></a>
</li>
<li class="nk-menu-item">
<a href="html/components/elements/tooltip.html" class="nk-menu-link"><span class="nk-menu-text">Tooltip</span></a>
</li>
<li class="nk-menu-item">
<a href="html/components/elements/typography.html" class="nk-menu-link"><span class="nk-menu-text">Typography</span></a>
</li>
<li class="nk-menu-item">
<a href="#" class="nk-menu-link nk-menu-toggle"><span class="nk-menu-text">Utilities</span></a>
<ul class="nk-menu-sub">
<li class="nk-menu-item"><a href="html/components/elements/util-border.html" class="nk-menu-link"><span class="nk-menu-text">Border</span></a></li>
<li class="nk-menu-item"><a href="html/components/elements/util-colors.html" class="nk-menu-link"><span class="nk-menu-text">Colors</span></a></li>
<li class="nk-menu-item"><a href="html/components/elements/util-display.html" class="nk-menu-link"><span class="nk-menu-text">Display</span></a></li>
<li class="nk-menu-item"><a href="html/components/elements/util-embeded.html" class="nk-menu-link"><span class="nk-menu-text">Embeded</span></a></li>
<li class="nk-menu-item"><a href="html/components/elements/util-flex.html" class="nk-menu-link"><span class="nk-menu-text">Flex</span></a></li>
<li class="nk-menu-item"><a href="html/components/elements/util-text.html" class="nk-menu-link"><span class="nk-menu-text">Text</span></a></li>
<li class="nk-menu-item"><a href="html/components/elements/util-sizing.html" class="nk-menu-link"><span class="nk-menu-text">Sizing</span></a></li>
<li class="nk-menu-item"><a href="html/components/elements/util-spacing.html" class="nk-menu-link"><span class="nk-menu-text">Spacing</span></a></li>
<li class="nk-menu-item"><a href="html/components/elements/util-others.html" class="nk-menu-link"><span class="nk-menu-text">Others</span></a></li>
</ul><!-- .nk-menu-sub -->
</li>
</ul><!-- .nk-menu-sub -->
</li><!-- .nk-menu-item -->
<li class="nk-menu-item has-sub">
<a href="#" class="nk-menu-link nk-menu-toggle">
<span class="nk-menu-icon"><em class="icon ni ni-dot-box"></em></span>
<span class="nk-menu-text">Crafted Icons</span>
</a>
<ul class="nk-menu-sub">
<li class="nk-menu-item">
<a href="html/components/misc/svg-icons.html" class="nk-menu-link">
<span class="nk-menu-text">SVG Icon - Exclusive</span>
</a>
</li>
<li class="nk-menu-item">
<a href="html/components/misc/nioicon.html" class="nk-menu-link">
<span class="nk-menu-text">Nioicon - HandCrafted</span>
</a>
</li>
</ul><!-- .nk-menu-sub -->
</li><!-- .nk-menu-item -->
<li class="nk-menu-item">
<a href="html/components/misc/icons.html" class="nk-menu-link">
<span class="nk-menu-icon"><em class="icon ni ni-menu-circled"></em></span>
<span class="nk-menu-text">Icon Libraries</span>
</a>
</li><!-- .nk-menu-item -->
<li class="nk-menu-item has-sub">
<a href="#" class="nk-menu-link nk-menu-toggle">
<span class="nk-menu-icon"><em class="icon ni ni-table-view"></em></span>
<span class="nk-menu-text">Tables</span>
</a>
<ul class="nk-menu-sub">
<li class="nk-menu-item">
<a href="html/components/tables/table-basic.html" class="nk-menu-link"><span class="nk-menu-text">Basic Tables</span></a>
</li>
<li class="nk-menu-item">
<a href="html/components/tables/table-special.html" class="nk-menu-link"><span class="nk-menu-text">Special Tables</span></a>
</li>
<li class="nk-menu-item">
<a href="html/components/tables/table-datatable.html" class="nk-menu-link"><span class="nk-menu-text">DataTables</span></a>
</li>
</ul><!-- .nk-menu-sub -->
</li><!-- .nk-menu-item -->
<li class="nk-menu-item has-sub">
<a href="#" class="nk-menu-link nk-menu-toggle">
<span class="nk-menu-icon"><em class="icon ni ni-card-view"></em></span>
<span class="nk-menu-text">Forms</span>
</a>
<ul class="nk-menu-sub">
<li class="nk-menu-item">
<a href="html/components/forms/form-elements.html" class="nk-menu-link"><span class="nk-menu-text">Form Elements</span></a>
</li>
<li class="nk-menu-item">
<a href="html/components/forms/checkbox-radio.html" class="nk-menu-link"><span class="nk-menu-text">Checkbox Radio</span></a>
</li>
<li class="nk-menu-item">
<a href="html/components/forms/advanced-controls.html" class="nk-menu-link"><span class="nk-menu-text">Advanced Controls</span></a>
</li>
<li class="nk-menu-item">
<a href="html/components/forms/input-group.html" class="nk-menu-link"><span class="nk-menu-text">Input Group</span></a>
</li>
<li class="nk-menu-item">
<a href="html/components/forms/form-upload.html" class="nk-menu-link"><span class="nk-menu-text">Form Upload</span></a>
</li>
<li class="nk-menu-item">
<a href="html/components/forms/datetime-picker.html" class="nk-menu-link"><span class="nk-menu-text">Date &amp; Time Picker</span></a>
</li>
<li class="nk-menu-item">
<a href="html/components/forms/number-spinner.html" class="nk-menu-link"><span class="nk-menu-text">Number Spinner</span></a>
</li>
<li class="nk-menu-item">
<a href="html/components/forms/nouislider.html" class="nk-menu-link"><span class="nk-menu-text">noUiSlider</span></a>
</li>
<li class="nk-menu-item">
<a href="html/components/forms/form-layouts.html" class="nk-menu-link"><span class="nk-menu-text">Form Layouts</span></a>
</li>
<li class="nk-menu-item">
<a href="html/components/forms/form-validation.html" class="nk-menu-link"><span class="nk-menu-text">Form Validation</span></a>
</li>
<li class="nk-menu-item">
<a href="#" class="nk-menu-link nk-menu-toggle"><span class="nk-menu-text">Wizard</span></a>
<ul class="nk-menu-sub">
<li class="nk-menu-item"><a href="html/components/forms/form-wizard.html" class="nk-menu-link"><span class="nk-menu-text">Basic Demo</span></a></li>
<li class="nk-menu-item"><a href="html/components/forms/wizard/create-project.html" class="nk-menu-link"><span class="nk-menu-text">Create Project</span></a></li>
<li class="nk-menu-item"><a href="html/components/forms/wizard/create-profile.html" class="nk-menu-link"><span class="nk-menu-text">Create Profile</span></a></li>
<li class="nk-menu-item"><a href="html/components/forms/wizard/two-factor-auth.html" class="nk-menu-link"><span class="nk-menu-text">Two Factor Auth</span></a></li>
<li class="nk-menu-item"><a target="_blank" href="html/components/forms/wizard/survey-v1.html" class="nk-menu-link"><span class="nk-menu-text">Survey</span></a></li>
<li class="nk-menu-item"><a target="_blank" href="html/components/forms/wizard/survey-v2.html" class="nk-menu-link"><span class="nk-menu-text">Survey v2</span></a></li>
</ul><!-- .nk-menu-sub -->
</li>
<li class="nk-menu-item">
<a href="#" class="nk-menu-link nk-menu-toggle"><span class="nk-menu-text">Rich Editor</span></a>
<ul class="nk-menu-sub">
<li class="nk-menu-item"><a href="html/components/forms/form-summernote.html" class="nk-menu-link"><span class="nk-menu-text">Summernote</span></a></li>
<li class="nk-menu-item"><a href="html/components/forms/form-quill.html" class="nk-menu-link"><span class="nk-menu-text">Quill</span></a></li>
<li class="nk-menu-item"><a href="html/components/forms/form-tinymce.html" class="nk-menu-link"><span class="nk-menu-text">Tinymce</span></a></li>
</ul><!-- .nk-menu-sub -->
</li>
</ul><!-- .nk-menu-sub -->
</li><!-- .nk-menu-item -->
<li class="nk-menu-item has-sub">
<a href="#" class="nk-menu-link nk-menu-toggle">
<span class="nk-menu-icon"><em class="icon ni ni-pie"></em></span>
<span class="nk-menu-text">Charts</span>
</a>
<ul class="nk-menu-sub">
<li class="nk-menu-item">
<a href="html/components/charts/chartjs.html" class="nk-menu-link"><span class="nk-menu-text">Chart JS</span></a>
</li>
<li class="nk-menu-item">
<a href="html/components/charts/knob.html" class="nk-menu-link"><span class="nk-menu-text">Knob JS</span></a>
</li>
</ul><!-- .nk-menu-sub -->
</li><!-- .nk-menu-item -->
<li class="nk-menu-item has-sub">
<a href="#" class="nk-menu-link nk-menu-toggle">
<span class="nk-menu-icon"><em class="icon ni ni-puzzle"></em></span>
<span class="nk-menu-text">Widgets</span>
</a>
<ul class="nk-menu-sub">
<li class="nk-menu-item">
<a href="html/components/widgets/cards.html" class="nk-menu-link"><span class="nk-menu-text">Card Widgets</span></a>
</li><!-- .nk-menu-item -->
<li class="nk-menu-item">
<a href="html/components/widgets/charts.html" class="nk-menu-link"><span class="nk-menu-text">Chart Widgets</span></a>
</li><!-- .nk-menu-item -->
<li class="nk-menu-item">
<a href="html/components/widgets/ratings.html" class="nk-menu-link"><span class="nk-menu-text">Ratings Widget</span></a>
</li><!-- .nk-menu-item -->
</ul><!-- .nk-menu-sub -->
</li><!-- .nk-menu-item -->
<li class="nk-menu-item has-sub">
<a href="#" class="nk-menu-link nk-menu-toggle">
<span class="nk-menu-icon"><em class="icon ni ni-block-over"></em></span>
<span class="nk-menu-text">Miscellaneous</span>
</a>
<ul class="nk-menu-sub">
<li class="nk-menu-item">
<a href="html/components/misc/slick-sliders.html" class="nk-menu-link"><span class="nk-menu-text">Slick Slider</span></a>
</li><!-- .nk-menu-item -->
<li class="nk-menu-item">
<a href="html/components/misc/toastr.html" class="nk-menu-link"><span class="nk-menu-text">Toastr</span></a>
</li><!-- .nk-menu-item -->
<li class="nk-menu-item">
<a href="html/components/misc/sweet-alert.html" class="nk-menu-link"><span class="nk-menu-text">Sweet Alert</span></a>
</li><!-- .nk-menu-item -->
<li class="nk-menu-item">
<a href="html/components/misc/js-tree.html" class="nk-menu-link"><span class="nk-menu-text">jsTree</span></a>
</li><!-- .nk-menu-item -->
<li class="nk-menu-item">
<a href="html/components/misc/dual-listbox.html" class="nk-menu-link"><span class="nk-menu-text">Dual Listbox</span></a>
</li><!-- .nk-menu-item -->
<li class="nk-menu-item">
<a href="html/components/misc/dragula.html" class="nk-menu-link"><span class="nk-menu-text">Dragula</span></a>
</li><!-- .nk-menu-item -->
<li class="nk-menu-item">
<a href="html/components/misc/map.html" class="nk-menu-link"><span class="nk-menu-text">Google Map</span></a>
</li><!-- .nk-menu-item -->
</ul><!-- .nk-menu-sub -->
</li><!-- .nk-menu-item -->
<li class="nk-menu-item">
<a href="html/email-templates.html" class="nk-menu-link">
<span class="nk-menu-icon"><em class="icon ni ni-text-rich"></em></span>
<span class="nk-menu-text">Email Template</span>
</a>
</li>
</ul><!-- .nk-menu -->
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
