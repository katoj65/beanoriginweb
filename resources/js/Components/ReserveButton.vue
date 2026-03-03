<script setup>
import { computed,onMounted } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { Tickets } from '@element-plus/icons-vue';


const props = defineProps({
title: {
type: String,
default: 'Reserve',
},


isLoading: {
type: Boolean,
default: undefined,
},

status: {
type: Boolean,
default: false,
},
type: {
type: String,
default: 'plain',
},
plain: {
type: Boolean,
default: false,
},
nativeType: {
type: String,
default: 'button',
},
disabled: {
type: Boolean,
default: false,
},
batch: {
type: Object,
default: null,
},
reservedByUser: {
type: Boolean,
default: false,
},
});






const buyForm = useForm({
id: null,
});

const loading = computed(() => {
const externalLoading = typeof props.isLoading === 'boolean' ? props.isLoading : props.status;
return externalLoading || buyForm.processing;
});

const submitBuy = () => {
const rawBatchId = page.props.batch?.id ?? page.props.batch?.data?.id ?? props.batch?.id ?? props.batch?.data?.id ?? null;
const batchId = typeof rawBatchId === 'object' ? Number(rawBatchId?.id ?? 0) : Number(rawBatchId);
if (!Number.isInteger(batchId) || batchId < 1) return;
buyForm.id = batchId;
buyForm.post(route('buy.batch.store', { id: batchId }), {
preserveScroll: true,
});
};

const handleClick = () => {
submitBuy();
};


const page=usePage();
const is_reserved = computed(() => {
const reserved = page.props.is_reserved_by_user ?? props.reservedByUser ?? false;
return reserved === true || reserved === 1 || reserved === '1';
});

const buttonTitle=computed(()=>{
const status=page.props.is_reserved_by_user;
let title='Reserve';
if(status==true){
title='Reserved';
}
return title;
});

const batch_ownership = computed(() => {
const ownership = page.props.batch_ownership ?? props.batch?.batch_ownership ?? false;
return ownership === true || ownership === 1 || ownership === '1';
});



const buttonDisabled = computed(() => {
if (batch_ownership.value === true) {
return true;
}
if (is_reserved.value === true) {
return true;
}
return false;
});



onMounted(() => {
console.log(batch_ownership.value);
});















</script>

<template>
<el-button
:type="type"
:plain="plain"
:native-type="nativeType"
:icon="Tickets"
:loading="loading"
:disabled="buttonDisabled"
@click="handleClick">
{{ buttonTitle }}
</el-button>
</template>
