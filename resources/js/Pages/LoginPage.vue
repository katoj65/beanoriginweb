<script setup>
import { ref } from 'vue';


const form=ref({
email:'',
password:''
});





const submit=()=>{
form.transform(data => ({
...data,
remember: form.remember ? 'on' : '',
})).post(route('login'), {
onFinish: () => form.reset('password'),
});
}


</script>
<template>
<div class="nk-body bg-white npc-general pg-auth">
<div class="nk-app-root">
<div class="nk-split nk-split-page nk-split-lg">
<div class="nk-split-content nk-block-area nk-block-area-column nk-auth-container bg-white w-lg-45">
<div class="absolute-top-right d-lg-none p-3 p-sm-5">
<a href="#" class="toggle btn btn-white btn-icon btn-light" data-target="athPromo"><em class="icon ni ni-info"></em></a>
</div>
<div class="nk-block nk-block-middle nk-auth-body">
<div class="brand-logo pb-0">
<a href="html/index.html" class="logo-link">
<img class="" src="../../images/logo.png" srcset="" alt="logo-dark" style="width:200px;">
</a>
</div>
<div class="nk-block-head">
<div class="nk-block-head-content">
<h5 class="nk-block-title">Join Bean Origin</h5>
<div class="nk-block-des">
<p>Create your account and start your coffee journey</p>
</div>
</div>
</div>









<!-- .nk-block-head -->
<form @submit.prevent="submit">
<div class="form-group">
<label class="form-label" for="email">Email</label>
<div class="form-control-wrap">
<input type="text" class="form-control form-control-lg" id="email" placeholder="Enter your email address" v-model="form.email">
</div>
</div>
<div class="form-group">
<label class="form-label" for="password">Password</label>
<div class="form-control-wrap">
<input type="password" class="form-control form-control-lg" id="password" placeholder="Enter your password" v-model="form.password">
</div>
</div>
<div class="form-group">
<button class="btn btn-lg btn-primary btn-block">Login</button>
</div>
</form>
<!-- form -->





<div class="form-note-s2 pt-4"> Already have an account ? <a href="html/pages/auths/auth-login.html"><strong>Sign in instead</strong></a>
</div>
<div class="text-center pt-4 pb-3">
<h6 class="overline-title overline-title-sap"><span>OR</span></h6>
</div>
<ul class="nav justify-center gx-8">
<li class="nav-item"><a class="link link-primary fw-normal py-2 px-3" href="#">Facebook</a></li>
<li class="nav-item"><a class="link link-primary fw-normal py-2 px-3" href="#">Google</a></li>
</ul>
</div><!-- .nk-block -->
<div class="nk-block nk-auth-footer">
<div class="nk-block-between">
<ul class="nav nav-sm">
<li class="nav-item">
<a class="link link-primary fw-normal py-2 px-3" href="#">Terms & Condition</a>
</li>
<li class="nav-item">
<a class="link link-primary fw-normal py-2 px-3" href="#">Privacy Policy</a>
</li>
<li class="nav-item">
<a class="link link-primary fw-normal py-2 px-3" href="#">Help</a>
</li>
<li class="nav-item dropup">
<a class="dropdown-toggle dropdown-indicator has-indicator link link-primary fw-normal py-2 px-3" data-bs-toggle="dropdown" data-offset="0,10"><small>English</small></a>
<div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">

</div>
</li>
</ul><!-- nav -->
</div>
<div class="mt-3">
<p>&copy; 2026 Bean Origin. All Rights Reserved.</p>
</div>
</div><!-- nk-block -->
</div><!-- nk-split-content -->


<div class="nk-split-content nk-split-stretch bg-lighter d-flex toggle-break-lg toggle-slide toggle-slide-right" data-toggle-body="true" data-content="athPromo" data-toggle-screen="lg" data-toggle-overlay="true" id="background">
<div class="slider-wrap w-100 w-max-550px p-3 p-sm-5 m-auto">
<div class="slider-init" data-slick='{"dots":true, "arrows":false}'>
<div class="slider-item">
<div class="nk-feature nk-feature-center">
<div class="nk-feature-content py-4 p-sm-5">
<h4>☕ Where Every Bean Has a Story</h4>
<p>Journey through the fertile highlands of Uganda, where the source of the Nile meets ancient volcanic soils. Bean Origin connects you directly to the passionate Ugandan farmers who cultivate exceptional Arabica beans at altitudes of 1,200 to 2,300 meters, crafting liquid gold one bean at a time.</p>
</div>
</div>
</div><!-- .slider-item -->

</div><!-- .slider-init -->
<div class="slider-dots"></div>
<div class="slider-arrows"></div>
</div><!-- .slider-wrap -->
</div><!-- nk-split-content -->
</div><!-- nk-split -->
</div>
</div>
</template>



<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700;800&display=swap');

#background{
    background-image:
        linear-gradient(135deg, rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.6)),
        url('../../images/bg2.jpg');
    background-size: cover;
    background-position: center center;
    background-repeat: no-repeat;
    background-attachment: fixed;
    position: relative;
}

#background::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(74, 144, 226, 0.1), rgba(80, 200, 120, 0.1));
    pointer-events: none;
}

#background .slider-wrap {
    position: relative;
    z-index: 2;
}

#background .nk-feature-content h4 {
    color: #ffffff;
    font-family: 'Playfair Display', serif;
    font-weight: 700;
    font-size: 2.5rem;
    text-shadow: 3px 3px 8px rgba(0, 0, 0, 0.7);
    margin-bottom: 1.5rem;
    letter-spacing: -0.5px;
    line-height: 1.2;
    text-align: center;
}

#background .nk-feature-content p {
    color: #f1f3f4;
    font-family: 'Inter', sans-serif;
    font-size: 1.25rem;
    line-height: 1.7;
    text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.8);
    font-weight: 300;
    letter-spacing: 0.3px;
    text-align: center;
    max-width: 500px;
    margin: 0 auto;
}

/* Modern slider dots styling */
#background .slider-dots .slick-dots {
    bottom: 20px;
}

#background .slider-dots .slick-dots li button:before {
    color: rgba(255, 255, 255, 0.7);
    font-size: 12px;
}

#background .slider-dots .slick-dots li.slick-active button:before {
    color: #ffffff;
}

/* Enhanced content container with better spacing */
#background .nk-feature-content {
    padding: 3.5rem 2.5rem;
    border-radius: 20px;
    backdrop-filter: blur(15px);
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.15);
    box-shadow: 0 25px 70px rgba(0, 0, 0, 0.4);
    transition: all 0.4s ease;
    max-width: 550px;
    margin: 0 auto;
}

#background .nk-feature-content:hover {
    transform: translateY(-5px);
    box-shadow: 0 35px 90px rgba(0, 0, 0, 0.5);
    background: rgba(255, 255, 255, 0.12);
}

/* Form typography improvements */
.nk-block-title {
    font-family: 'Playfair Display', serif !important;
    font-weight: 600 !important;
    color: #6F4E37 !important;
    font-size: 2rem !important;
    letter-spacing: -0.3px !important;
}

.nk-block-des p {
    font-family: 'Inter', sans-serif !important;
    font-weight: 400 !important;
    color: #6c757d !important;
    font-size: 1.1rem !important;
    letter-spacing: 0.2px !important;
}

/* Add subtle animation with improved timing */
#background .nk-feature {
    animation: elegantFadeIn 0.8s ease-out;
}

@keyframes elegantFadeIn {
    from {
        opacity: 0;
        transform: translateY(30px) scale(0.98);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* Additional typography refinements */
#background .nk-feature-content h4::after {
    content: '';
    display: block;
    width: 60px;
    height: 3px;
    background: linear-gradient(90deg, #ffd700, #ffed4e);
    margin: 1rem auto;
    border-radius: 2px;
    box-shadow: 0 2px 8px rgba(255, 215, 0, 0.3);
}

/* Improve text selection styling */
#background .nk-feature-content h4::selection,
#background .nk-feature-content p::selection {
    background: rgba(255, 215, 0, 0.3);
    color: #ffffff;
}

/* Coffee-colored button styling */
.btn-primary {
    background: linear-gradient(135deg, #6F4E37, #8B5A2B) !important;
    border-color: #6F4E37 !important;
    font-family: 'Inter', sans-serif !important;
    font-weight: 600 !important;
    letter-spacing: 0.5px !important;
    transition: all 0.3s ease !important;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5A3E2B, #6F4E37) !important;
    transform: translateY(-2px) !important;
    border-color: #5A3E2B !important;
}

.btn-primary:active,
.btn-primary:focus {
    background: linear-gradient(135deg, #4A2E1B, #5A3E2B) !important;
    border-color: #4A2E1B !important;
}

/* Responsive improvements */
@media (max-width: 768px) {
    #background {
        background-attachment: scroll;
    }

    #background .nk-feature-content {
        padding: 2.5rem 1.5rem;
        border-radius: 16px;
    }

    #background .nk-feature-content h4 {
        font-size: 2rem;
        line-height: 1.3;
    }

    #background .nk-feature-content p {
        font-size: 1.1rem;
        line-height: 1.6;
    }

    .nk-block-title {
        font-size: 1.75rem !important;
    }

    .nk-block-des p {
        font-size: 1rem !important;
    }
}

@media (max-width: 480px) {
    #background .nk-feature-content {
        padding: 2rem 1.25rem;
    }

    #background .nk-feature-content h4 {
        font-size: 1.75rem;
    }

    #background .nk-feature-content p {
        font-size: 1rem;
    }

    .nk-block-title {
        font-size: 1.5rem !important;
    }
}
</style>
