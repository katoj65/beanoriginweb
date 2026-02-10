<script setup>



</script>
<template>
<form action="html/pages/auths/auth-success.html">
<div class="form-group">
<label class="form-label" for="email">Email or Username</label>
<div class="form-control-wrap">
<input type="text" class="form-control form-control-lg" id="email" placeholder="Enter your email address or username">
</div>
</div>
<div class="form-group">
<label class="form-label" for="password">Password</label>
<div class="form-control-wrap">
<a tabindex="-1" href="#" class="form-icon form-icon-right passcode-switch lg" data-target="password">
<em class="passcode-icon icon-show icon ni ni-eye"></em>
<em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
</a>
<input type="password" class="form-control form-control-lg" id="password" placeholder="Enter your passcode">
</div>
</div>
<div class="form-group">
<button class="btn btn-lg btn-primary btn-block">Register</button>
</div>
</form>

</template>
