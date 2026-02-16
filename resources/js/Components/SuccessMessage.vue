<script setup>
import { usePage } from '@inertiajs/vue3';
const props=defineProps({
title:String,
message:String
});


const page=usePage();



</script>


<template>
<div class="alert alert-pro alert-success">
<div class="alert-text">
<h6>
{{ props.title?props.title:'Successful' }}
</h6>
<p>
{{ props.message }}
</p>
</div>
</div>
</template>
