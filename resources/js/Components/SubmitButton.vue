<script setup>
const props=defineProps({
title:String,
status:Boolean
});


</script>

<template>
<div class="form-group">
<div class="form-control-wrap">
<button class="btn btn-lg btn-primary btn-block submit-btn" v-if="status==false" type="submit">
{{ title }}
</button>
<button class="btn btn-lg btn-primary btn-block submit-btn d-flex align-items-center justify-content-center" v-else disabled>
<div class="spinner-border spinner-border-sm submit-spinner" role="status" aria-hidden="true"></div>
<span class="visually-hidden">Loading...</span>
</button>
</div>
</div>
</template>
<style scoped>
.submit-btn {
min-height: 48px;
}

.submit-spinner {
width: 1rem;
height: 1rem;
border-width: 0.14em;
}
</style>
