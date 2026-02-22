<script setup>
import { computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';
import BoughtForm from './BatchActions/BoughtForm.vue';

const page = usePage();
const selection = computed(() => page.props.selection ?? {});
const batch = computed(() => page.props.batch?.data ?? page.props.batch ?? {});

const actionText = computed(() => {
const raw = String(selection.value?.selected_action ?? '').trim();
if (!raw) return 'N/A';
return raw.charAt(0).toUpperCase() + raw.slice(1);
});

const goBack = () => {
router.get(route('cooperative.batches.listed'));
};

const goToBatch = () => {
if (!batch.value?.id) return;
router.get(route('cooperative.batches.show', { id: batch.value.id }));
};

const formatDateTime = (value) => {
if (!value) return 'N/A';
const date = new Date(value);
if (Number.isNaN(date.getTime())) return value;
return date.toLocaleString();
};


const selectionData=computed(()=>page.props.selection);



</script>

<template>
<CooperativeLayout>
<div class="container">
<div class="card card-bordered summary-card">
<div class="card-inner border-bottom summary-head">
<div>
<h6 class="title mb-1">{{ actionText !== 'N/A' ? `${actionText} Batch Summary` : 'Batch Action Summary' }}</h6>
<p class="sub-text mb-0">Review the selected batch and intended action before processing.</p>
</div>
<el-button-group>
<el-button plain @click="goBack">Back to List</el-button>
<el-button type="default" :disabled="!batch?.id" @click="goToBatch">View Batch</el-button>
</el-button-group>
</div>

<div class="card-inner summary-table-section">
<div class="table-wrap">
<table class="table table-sm table-middle mb-0 summary-table">
<thead>
<tr>
<th>Selected Action</th>
<th>Batch Number</th>
<th>Status</th>
<th>Commodity</th>
<th>Commodity Type</th>
<th>Grade</th>
<th>Weight</th>
<th>Warehouse</th>

</tr>
</thead>
<tbody>
<tr>
<td><span class="badge bg-warning-subtle text-warning">{{ actionText }}</span></td>
<td>{{ selection.batch_number ?? batch.batch_code ?? 'N/A' }}</td>
<td>{{ batch.status ?? 'N/A' }}</td>
<td>{{ batch.commodity_name ?? 'N/A' }}</td>
<td>{{ batch.commodity_type ?? 'N/A' }}</td>
<td>{{ batch.grade ?? 'N/A' }}</td>
<td>{{ batch.weight ?? 'N/A' }} kg</td>
<td>{{ batch.warehouse ?? 'N/A' }}</td>

</tr>
</tbody>
</table>


<bought-form v-if="selectionData.selected_action=='bought'"/>


</div>
</div>
</div>
</div>
</CooperativeLayout>
</template>

<style scoped>
.summary-card {
border-radius: 12px;
}

.summary-head {
display: flex;
justify-content: space-between;
align-items: center;
gap: 12px;
}

.table-wrap {
overflow-x: auto;
width: 100%;
}

.summary-table-section {
padding: 0;
}

.summary-table th {
background: #f8fafc;
color: #526484;
font-weight: 600;
white-space: nowrap;
vertical-align: middle;
}

.summary-table td {
color: #364a63;
vertical-align: middle;
white-space: nowrap;
}

.summary-table {
width: 100%;
margin: 0;
}

@media (max-width: 768px) {
.summary-head {
flex-direction: column;
align-items: stretch;
}
}
</style>
