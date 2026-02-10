<script setup>
import HeaderComponent from "@/Components/HeaderComponent.vue";
import SidebarComponent from "@/Components/SidebarComponent.vue";


</script>
<template>
<div class="nk-body bg-white has-sidebar ">
<div class="nk-app-root">
<!-- main @s -->
<div class="nk-main">
<!-- sidebar @s -->
<sidebar-component/>
<!-- sidebar @e -->
<!-- wrap @s -->
<div class="nk-wrap">

<!-- main header @s -->
<HeaderComponent/>

<!-- main header @e -->
<!-- content @s -->
<div class="nk-content nk-content-fluid">
<div class="container-xl wide-lg">
<div class="nk-content-body">



<slot></slot>


</div>
</div>
</div>
<!-- content @e -->
<!-- footer @s -->

<div class="nk-footer">
<div class="container-fluid">
<div class="nk-footer-wrap">
<div class="nk-footer-copyright"> &copy; 2024 DashLite. Template by <a href="https://softnio.com" target="_blank">Softnio</a>
</div>
<div class="nk-footer-links">
<ul class="nav nav-sm">
<li class="nav-item">
<a data-bs-toggle="modal" href="#region" class="nav-link"><em class="icon ni ni-globe"></em><span class="ms-1">Select Region</span></a>
</li>
</ul>
</div>
</div>
</div>
</div>



<!-- footer @e -->
</div>
<!-- wrap @e -->
</div>
<!-- main @e -->
</div>

</div>
</template>
