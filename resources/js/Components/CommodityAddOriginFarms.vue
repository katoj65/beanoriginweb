<script setup>
import { useForm } from '@inertiajs/vue3';
import { computed, watch } from 'vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
commodityId: {
type: [Number, String],
required: true,
},
farms: {
type: Array,
default: () => [],
},
selectedFarmIds: {
type: Array,
default: () => [],
},
});

const form = useForm({
farm_ids: [],
});

watch(
() => props.selectedFarmIds,
(ids) => {
form.farm_ids = Array.isArray(ids) ? [...ids] : [];
},
{ immediate: true },
);

const hasFarms = computed(() => Array.isArray(props.farms) && props.farms.length > 0);

const submit = () => {
form.post(route('commodity.origin-farms.store', { commodity: props.commodityId }), {
preserveScroll: true,
});
};
</script>

<template>
<div class="origin-form-wrap">
<div class="row g-2 align-items-end">
<div class="col-12 col-lg-9">
<label class="form-label mb-1">Origin Farms</label>
<el-select
v-model="form.farm_ids"
multiple
filterable
collapse-tags
collapse-tags-tooltip
placeholder="Select farms where this commodity originated"
class="form-control-like w-100"
:disabled="!hasFarms"
>
<el-option
v-for="farm in farms"
:key="farm.id"
:label="farm.farm_name"
:value="farm.id"
/>
</el-select>
<InputError :message="form.errors.farm_ids" class="mt-2" />
</div>
<div class="col-12 col-lg-3 d-flex">
<el-button
type="primary"
class="w-100"
:loading="form.processing"
:disabled="!hasFarms"
@click="submit"
>
Save Origin Farms
</el-button>
</div>
</div>
<p v-if="!hasFarms" class="sub-text mt-2 mb-0">No farms available for this cooperative.</p>
</div>
</template>

<style scoped>
.origin-form-wrap {
padding: 2px 0;
}
</style>

