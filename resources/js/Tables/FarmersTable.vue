<template>
<el-table :data="tableData" style="width: 100%;height:300px;">
<el-table-column prop="fname" label="Names" />
<el-table-column prop="lname" label=""/>
<el-table-column prop="farm" label="Farm" />
<el-table-column prop="produce" label="Produce" />
<el-table-column prop="location" label="Location" />
</el-table>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  farmers: {
    type: Array,
    default: () => [],
  },
});

const tableData = computed(() => props.farmers ?? []);
</script>
