<script setup>

</script>

<style scoped>
.custom-search {
  position: relative;
  width: 320px;
  max-width: 100%;
}

.custom-search-input {
  width: 100%;
  height: 44px;
  padding: 0 50px 0 20px;
  font-size: 0.875rem;
  color: #374151;
  background: #ffffff;
  border: 1px solid #E5E7E9;
  border-radius: 22px;
  outline: none;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  backdrop-filter: blur(10px);
  font-family: system-ui, -apple-system, sans-serif;
}

.custom-search-input::placeholder {
  color: #9CA3AF;
  font-weight: 400;
}

.custom-search-input:focus {
  background: #ffffff;
  border-color: #A8A8A8;
  box-shadow: 0 0 0 3px rgba(192, 192, 192, 0.2);
  transform: translateY(-1px);
}

.custom-search-input:hover:not(:focus) {
  border-color: #B8B8B8;
  background: #ffffff;
}

.custom-search-icon {
  position: absolute;
  right: 16px;
  top: 50%;
  transform: translateY(-50%);
  color: #6F4E37;
  font-size: 1rem;
  pointer-events: none;
  transition: color 0.3s ease;
}

.custom-search-input:focus + .custom-search-icon {
  color: #8B4513;
}

@media (max-width: 768px) {
  .custom-search {
    width: 280px;
  }

  .custom-search-input {
    height: 40px;
    font-size: 0.8rem;
  }
}
</style>
<template>
<div class="nk-header nk-header-fixed is-light border-0">
<div class="container-fluid">
<div class="nk-header-wrap">
<div class="nk-menu-trigger d-xl-none ms-n1">
<a href="#" class="nk-nav-toggle nk-quick-nav-icon" data-target="sidebarMenu"><em class="icon ni ni-menu"></em></a>
</div>
<div class="nk-header-brand d-xl-none">
<a href="html/index.html" class="logo-link">
<img class="logo-light logo-img" src="" srcset="" alt="logo">
<img class="logo-dark logo-img" src="" srcset="" alt="logo-dark">
</a>
</div>



<!-- .nk-header-brand -->
<div class="nk-header-news d-none d-xl-block">
<div class="nk-news-list">





<div class="custom-search">
<input type="text" class="custom-search-input"
placeholder="Search coffee, farmers, orders..." autocomplete="off">
<em class="icon ni ni-search custom-search-icon"></em>
</div>





</div>
</div>
<!-- .nk-header-news -->




<div class="nk-header-tools">
<ul class="nk-quick-nav">
<li class="dropdown language-dropdown d-none d-sm-block me-n1">






<a href="#" class="dropdown-toggle nk-quick-nav-icon mr-3" data-bs-toggle="dropdown">
<em class="icon ni ni-home"></em>
</a>


<a href="#" class="dropdown-toggle nk-quick-nav-icon mr-3" data-bs-toggle="dropdown">
<em class="icon ni ni-msg"></em>
</a>


<a href="#" class="dropdown-toggle nk-quick-nav-icon mr-3" data-bs-toggle="dropdown">
<em class="icon ni ni-bell"></em>
</a>


<div class="dropdown-menu dropdown-menu-end dropdown-menu-s1">
<ul class="language-list">
    <li>
        <a href="#" class="language-item">
            <img src="" alt="" class="language-flag">
            <span class="language-name">English</span>
        </a>
    </li>
    <li>
        <a href="#" class="language-item">
            <img src="" alt="" class="language-flag">
            <span class="language-name">Español</span>
        </a>
    </li>
    <li>
        <a href="#" class="language-item">
            <img src="" alt="" class="language-flag">
            <span class="language-name">Français</span>
        </a>
    </li>
    <li>
        <a href="#" class="language-item">
            <img src="" alt="" class="language-flag">
            <span class="language-name">Türkçe</span>
        </a>
    </li>
</ul>
</div>
</li>

<!-- .dropdown -->
<li class="dropdown user-dropdown">
<a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">
<div class="user-toggle">
<div class="mr-3">
<el-avatar :size="35" src="https://cube.elemecdn.com/3/7c/3ea6beec64369c2642b92c6726f1epng.png" />
</div>
<div class="user-info d-none d-md-block">
<div class="user-status">Administrator</div>
<div class="user-name dropdown-indicator">Abu Bin Ishityak</div>
</div>
</div>
</a>
<div class="dropdown-menu dropdown-menu-md dropdown-menu-end dropdown-menu-s1">
<div class="dropdown-inner user-card-wrap bg-lighter d-none d-md-block">
    <div class="user-card">
        <div class="user-avatar">
            <span>AB</span>
        </div>
        <div class="user-info">
            <span class="lead-text">Abu Bin Ishtiyak</span>
            <span class="sub-text">info@softnio.com</span>
        </div>
    </div>
</div>
<div class="dropdown-inner">
    <ul class="link-list">
        <li><a href="html/user-profile-regular.html"><em class="icon ni ni-user-alt"></em><span>View Profile</span></a></li>
        <li><a href="html/user-profile-setting.html"><em class="icon ni ni-setting-alt"></em><span>Account Setting</span></a></li>
        <li><a href="html/user-profile-activity.html"><em class="icon ni ni-activity-alt"></em><span>Login Activity</span></a></li>
        <li><a class="dark-switch" href="#"><em class="icon ni ni-moon"></em><span>Dark Mode</span></a></li>
    </ul>
</div>
<div class="dropdown-inner">
    <ul class="link-list">
        <li><a href="#"><em class="icon ni ni-signout"></em><span>Sign out</span></a></li>
    </ul>
</div>
</div>
</li><!-- .dropdown -->


</ul><!-- .nk-quick-nav -->
</div><!-- .nk-header-tools -->
</div><!-- .nk-header-wrap -->
</div><!-- .container-fliud -->
</div>

</template>
