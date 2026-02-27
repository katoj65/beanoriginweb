<script setup>
import { computed } from 'vue';

const props = defineProps({
title: String,
isLoading: {
type: Boolean,
default: undefined,
},
status: {
type: Boolean,
default: false,
},
});

const loading = computed(() => {
return typeof props.isLoading === 'boolean' ? props.isLoading : props.status;
});
</script>

<template>
<div class="form-group">
<div class="form-control-wrap">
<el-button

type="primary"
native-type="submit"
:loading="loading"
:disabled="loading"
>
{{ title }}
</el-button>
</div>
</div>
</template>
<style scoped>
.submit-btn {
width: 100%;
min-height: 48px;
}
</style>
