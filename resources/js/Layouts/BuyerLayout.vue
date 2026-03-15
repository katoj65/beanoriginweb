<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { Search, Bell, User, ShoppingCart } from '@element-plus/icons-vue';
import { computed } from 'vue';

const page = usePage();

const capitalizeFirst = (value) => {
  const text = String(value ?? '').trim();
  if (!text) return '';
  return text.charAt(0).toUpperCase() + text.slice(1);
};

const logout = () => {
  router.post(route('logout'));
};

const app_user = computed(() => {
  const data = page.props.auth?.user ?? {};
  return {
    id: data.id,
    fname: capitalizeFirst(data.fname ?? 'Buyer'),
    lname: capitalizeFirst(data.lname ?? ''),
    email: data.email ?? '',
  };
});

const cartCount = computed(() => {
  return Number(page.props.shoppingCart ?? page.props.shopping_cart ?? 0) || 0;
});

const isDashboardPage = computed(() => {
  return String(page.component ?? '').toLowerCase().includes('dashboard');
});
</script>

<template>
<div class="nk-body npc-default has-apps-sidebar has-sidebar ">
<div class="nk-app-root">

<div class="nk-apps-sidebar is-silver">
<div class="nk-apps-brand">
<a :href="route('buyer.dashboard')" class="logo-link">
<img class="logo-light logo-img" src="../../images/logo.png" alt="logo" style="border-radius:20px;">
<img class="logo-dark logo-img" src="../../images/logo.png" alt="logo-dark" style="border-radius:20px;">
</a>
</div>
<div class="nk-sidebar-element">
<div class="nk-sidebar-body">
<div class="nk-sidebar-content" data-simplebar>
<div class="nk-sidebar-menu">
<ul class="nk-menu apps-menu">
<li class="nk-menu-item">
<Link :href="route('buyer.dashboard')" class="nk-menu-link" title="Buyer Home">
<span class="nk-menu-icon"><em class="icon ni ni-home-alt"></em></span>
</Link>
</li>
<li class="nk-menu-hr"></li>
<li class="nk-menu-item">
<Link :href="route('buyer.dashboard')" class="nk-menu-link" title="Dashboard Overview">
<span class="nk-menu-icon"><em class="icon ni ni-dashboard"></em></span>
</Link>
</li>
<li class="nk-menu-item">
<Link :href="route('buyer.orders')" class="nk-menu-link" title="Trade Activity">
<span class="nk-menu-icon"><em class="icon ni ni-tranx"></em></span>
</Link>
</li>
<li class="nk-menu-item">
<Link :href="route('market.index')" class="nk-menu-link" title="Marketplace Listings">
<span class="nk-menu-icon"><em class="icon ni ni-card-view"></em></span>
</Link>
</li>
<li class="nk-menu-item">
<Link :href="route('buyer.suppliers')" class="nk-menu-link" title="Suppliers">
<span class="nk-menu-icon"><em class="icon ni ni-users"></em></span>
</Link>
</li>
<li class="nk-menu-item">
<Link :href="route('buyer.notifications')" class="nk-menu-link" title="Notifications">
<span class="nk-menu-icon"><em class="icon ni ni-bell"></em></span>
</Link>
</li>
<li class="nk-menu-item">
<Link :href="route('buyer.account.settings')" class="nk-menu-link" title="Compliance & Settings">
<span class="nk-menu-icon"><em class="icon ni ni-shield-check"></em></span>
</Link>
</li>
</ul>
</div>
</div>
<div class="nk-sidebar-profile nk-sidebar-profile-fixed">
<el-dropdown trigger="click" placement="top-start" popper-class="sidebar-profile-menu" class="w-100">
<span class="sidebar-profile-trigger" aria-label="Open profile menu">
<span class="d-flex align-items-center">
<span class="user-avatar sidebar-profile-avatar">
<em class="icon ni ni-user-alt"></em>
</span>
</span>
<span class="sidebar-profile-caret"><em class="icon ni ni-chevron-up"></em></span>
</span>
<template #dropdown>
<el-dropdown-menu>
<el-dropdown-item>
<Link :href="route('buyer.profile')" class="d-block w-100 text-dark">View Profile</Link>
</el-dropdown-item>
<el-dropdown-item>
<Link :href="route('buyer.account.settings')" class="d-block w-100 text-dark">Account Settings</Link>
</el-dropdown-item>
<el-dropdown-item>
<Link :href="route('buyer.orders')" class="d-block w-100 text-dark">Purchase Activity</Link>
</el-dropdown-item>
<el-dropdown-item divided>
<form @submit.prevent="logout">
<button type="submit" class="signout-btn">Sign Out</button>
</form>
</el-dropdown-item>
</el-dropdown-menu>
</template>
</el-dropdown>
</div>
</div>
</div>
</div>

<div class="nk-main ">
<div class="nk-wrap ">

<div class="nk-header nk-header-fixed is-light">
<div class="container-fluid">
<div class="nk-header-wrap">
<div class="nk-menu-trigger d-xl-none ms-n1">
<a href="#" class="nk-nav-toggle nk-quick-nav-icon" data-target="sidebarMenu"><em class="icon ni ni-menu"></em></a>
</div>
<div class="nk-header-app-name">
<div class="nk-header-app-info">
<span class="sub-text header-app-subtitle">
Buyer
</span>
<span class="lead-text header-app-title text-capitalize">
Commodity Origin
</span>
</div>
</div>
<div class="nk-header-menu is-light">
<div class="nk-header-menu-inner">

<ul class="nk-menu nk-menu-main">
<li class="nk-menu-item">
<Link :href="route('buyer.dashboard')" class="nk-menu-link">
<span class="nk-menu-text">Home</span>
</Link>
</li>
<li class="nk-menu-item">
<Link :href="route('buyer.suppliers')" class="nk-menu-link">
<span class="nk-menu-text">Suppliers</span>
</Link>
</li>
<li class="nk-menu-item">
<Link :href="route('market.index')" class="nk-menu-link">
<span class="nk-menu-text">Marketplace</span>
</Link>
</li>
<li class="nk-menu-item">
<Link :href="route('buyer.orders')" class="nk-menu-link">
<span class="nk-menu-text">Orders</span>
</Link>
</li>
</ul>

</div>
</div>

<div class="nk-header-tools d-flex align-items-center">
<el-button size="medium" :icon="Search" round>Search</el-button>
<Link :href="route('market.cart.index')" class="ml-2 d-inline-flex">
<el-badge :value="cartCount" :max="99" :hidden="!cartCount">
<el-button :icon="ShoppingCart" circle />
</el-badge>
</Link>
<Link :href="route('buyer.notifications')" class="ml-4 mr-4 d-inline-flex">
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
<Link :href="route('buyer.profile')" class="d-block w-100 text-dark">
View Profile
</Link>
</el-dropdown-item>
<el-dropdown-item>
<Link :href="route('buyer.account.settings')" class="d-block w-100 text-dark">
Account Settings
</Link>
</el-dropdown-item>
<el-dropdown-item>
<Link :href="route('buyer.help')" class="d-block w-100 text-dark">
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

<div class="nk-sidebar" data-content="sidebarMenu">
<div class="nk-sidebar-inner" data-simplebar>
<ul class="nk-menu nk-menu-md">
<li class="nk-menu-heading">
<h6 class="overline-title text-primary-alt">Trading Operations</h6>
</li>

<li class="nk-menu-item">
<Link :href="route('buyer.dashboard')" class="nk-menu-link">
<span class="nk-menu-text">Operations Dashboard</span>
</Link>
</li>

<li class="nk-menu-item">
<Link :href="route('market.index')" class="nk-menu-link">
<span class="nk-menu-text">Market Listed</span>
</Link>
</li>

<li class="nk-menu-item">
<Link :href="route('buyer.watchlist')" class="nk-menu-link">
<span class="nk-menu-text">Watchlist</span>
</Link>
</li>

<li class="nk-menu-item">
<Link :href="route('market.index')" class="nk-menu-link">
<span class="nk-menu-text">Commodity Listings</span>
</Link>
</li>
<li class="nk-menu-item">
<Link :href="route('buyer.orders')" class="nk-menu-link">
<span class="nk-menu-text">Create Purchase Order</span>
</Link>
</li>
<li class="nk-menu-item">
<Link :href="route('buyer.dashboard')" class="nk-menu-link">
<span class="nk-menu-text">Spend Trends</span>
</Link>
</li>
<li class="nk-menu-item">
<Link :href="route('buyer.notifications')" class="nk-menu-link">
<span class="nk-menu-text">Market Notifications</span>
</Link>
</li>

<li class="nk-menu-heading">
<h6 class="overline-title text-primary-alt">Buyer Services</h6>
</li>
<li class="nk-menu-item">
<Link :href="route('buyer.suppliers')" class="nk-menu-link">
<span class="nk-menu-icon"><em class="icon ni ni-users"></em></span>
<span class="nk-menu-text">Supplier Directory</span>
</Link>
</li>
<li class="nk-menu-item">
<Link :href="route('buyer.orders')" class="nk-menu-link">
<span class="nk-menu-icon"><em class="icon ni ni-user"></em></span>
<span class="nk-menu-text">Order Workflow</span>
</Link>
</li>
<li class="nk-menu-item">
<Link :href="route('buyer.watchlist')" class="nk-menu-link">
<span class="nk-menu-icon"><em class="icon ni ni-growth"></em></span>
<span class="nk-menu-text">Saved Watchlist</span>
</Link>
</li>
<li class="nk-menu-item">
<Link :href="route('buyer.profile')" class="nk-menu-link">
<span class="nk-menu-icon"><em class="icon ni ni-building"></em></span>
<span class="nk-menu-text">Buyer Profile</span>
</Link>
</li>

<li class="nk-menu-heading">
<h6 class="overline-title text-primary-alt">Risk & Support</h6>
</li>
<li class="nk-menu-item">
<Link :href="route('buyer.account.settings')" class="nk-menu-link">
<span class="nk-menu-icon"><em class="icon ni ni-shield-check"></em></span>
<span class="nk-menu-text">Trade Compliance</span>
</Link>
</li>
<li class="nk-menu-item">
<Link :href="route('buyer.help')" class="nk-menu-link">
<span class="nk-menu-icon"><em class="icon ni ni-help-alt"></em></span>
<span class="nk-menu-text">Help Center</span>
</Link>
</li>
</ul>
</div>
</div>

<div :class="['nk-content', 'app-page-shell', { 'app-page-shell-flat': !isDashboardPage }]">
<div class="container-fluid">
<div class="nk-content-inner">
<div class="nk-content-body">
<div class="page-content-frame">
<slot></slot>
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
  justify-content: space-between;
  gap: 0.4rem;
  padding: 0.45rem 0.75rem;
  width: 100%;
  cursor: pointer;
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

.sidebar-profile-avatar .icon {
  font-size: 0.95rem;
  line-height: 1;
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

:deep(.sidebar-profile-menu.el-popper) {
  margin-left: 8px;
}

.signout-btn {
  border: 0;
  background: transparent;
  width: 100%;
  text-align: left;
  display: inline-flex;
  align-items: center;
  gap: 0.45rem;
  color: #364a63;
}

.app-page-shell {
  padding: 0 !important;
  margin: 0 !important;
}

.app-page-shell > .container-fluid,
.app-page-shell .nk-content-inner,
.app-page-shell .nk-content-body {
  width: 100%;
  max-width: none;
  padding: 0 !important;
  margin: 0 !important;
}

.app-page-shell-flat .page-content-frame {
  width: 100%;
}

.app-page-shell-flat :deep(.card),
.app-page-shell-flat :deep(.card-bordered),
.app-page-shell-flat :deep(.verification-shell) {
  border: 0 !important;
  border-radius: 0 !important;
  box-shadow: none !important;
}

.app-page-shell-flat :deep(.border) {
  border: var(--bs-border-width) var(--bs-border-style) var(--bs-border-color) !important;
}

.app-page-shell-flat :deep(.border-top) {
  border-top: var(--bs-border-width) var(--bs-border-style) var(--bs-border-color) !important;
}

.app-page-shell-flat :deep(.border-end) {
  border-right: var(--bs-border-width) var(--bs-border-style) var(--bs-border-color) !important;
}

.app-page-shell-flat :deep(.border-bottom) {
  border-bottom: var(--bs-border-width) var(--bs-border-style) var(--bs-border-color) !important;
}

.app-page-shell-flat :deep(.border-start) {
  border-left: var(--bs-border-width) var(--bs-border-style) var(--bs-border-color) !important;
}

.app-page-shell-flat :deep(.card-bordered) {
  border: 1px solid #dbdfea !important;
}
</style>
