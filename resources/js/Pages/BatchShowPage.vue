<script setup>
import { computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';
import { Edit, Back } from '@element-plus/icons-vue'

const page = usePage();
const batch = computed(() => page.props.produce.data);
const farmer = computed(() => page.props.farmer?.data ?? page.props.farmer ?? {});

const latestPayment = computed(() => {
const raw = batch.value?.latest_payment;
return raw?.data ?? raw ?? null;
});

const paymentHistory = computed(() => {
const raw = batch.value?.payments;
const items = raw?.data ?? raw ?? [];
return Array.isArray(items) ? items : [];
});

const statusClass = computed(() => {
const status = batch.value?.status;
if (status === 'listed') return 'badge bg-success-subtle text-success';
if (status === 'expired') return 'badge bg-danger-subtle text-danger';
if (status === 'sold') return 'badge bg-info-subtle text-info';
return 'badge bg-light text-dark';
});

const paymentStatusClass = (status) => {
if (status === 'completed') return 'badge bg-success-subtle text-success';
if (status === 'pending') return 'badge bg-warning-subtle text-warning';
if (status === 'cancelled') return 'badge bg-danger-subtle text-danger';
if (status === 'refunded') return 'badge bg-info-subtle text-info';
return 'badge bg-light text-dark';
};

const batchTitle = computed(() => `Batch #${batch.value?.id ?? 'N/A'}`);

const formatDate = (value) => {
if (!value) return 'N/A';
if (typeof value === 'string') {
const dateOnlyMatch = value.match(/^(\d{4})-(\d{2})-(\d{2})$/);
if (dateOnlyMatch) {
const [, year, month, day] = dateOnlyMatch;
const date = new Date(Number(year), Number(month) - 1, Number(day));
return date.toLocaleDateString();
}

const dateTimeMatch = value.match(/^(\d{4})-(\d{2})-(\d{2})[ T](\d{2}):(\d{2})(?::(\d{2}))?/);
if (dateTimeMatch) {
const [, year, month, day, hour, minute, second] = dateTimeMatch;
const date = new Date(
Number(year),
Number(month) - 1,
Number(day),
Number(hour),
Number(minute),
Number(second ?? 0),
);
return date.toLocaleDateString();
}
}

const date = new Date(value);
if (Number.isNaN(date.getTime())) return value;
return date.toLocaleDateString();
};

const formatMoney = (value) => {
if (value === null || value === undefined || value === '') return 'N/A';
const num = Number(value);
if (Number.isNaN(num)) return value;
return num.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
};

const goBackToList = () => {
router.get(route('cooperative.produce'));
};

const payment = computed(() => {
const raw = page.props.payment;
return raw?.data ?? raw ?? latestPayment.value;
});












</script>

<template>
<CooperativeLayout>
<div class="container">
<div class="card card-bordered">
<div class="card-inner border-bottom d-flex justify-content-between align-items-center">
<div>
<h6 class="title mb-1"><em class="icon ni ni-bag mr-1"></em>{{ batchTitle }}</h6>
<p class="sub-text mb-0">Commodity batch traceability details from farm origin to listing.</p>
</div>


  <el-button-group>
    <el-button :icon="Back" @click="goBackToList" />
    <el-button :icon="Edit" />
  </el-button-group>


</div>

<div class="card-inner">
<div class="details-grid">
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-hash mr-1"></em>Batch ID</span>
<strong>{{ batch.id }}</strong>
</div>

<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-growth mr-1"></em>Crop Name</span>
<strong>{{ batch.commodity_name }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-growth mr-1"></em>Crop Type</span>
<strong>{{ batch.commodity_type }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-package mr-1"></em>Quantity</span>
<strong>{{ batch.weight }} kg</strong>
</div>

<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-calendar mr-1"></em>Date of Harvest</span>
<strong>{{ batch.harvest_date }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-award mr-1"></em>Crop Grade</span>
<strong>{{ batch.grade }}</strong>
</div>
<div class="detail-item detail-item-full">
<span class="sub-text"><em class="icon ni ni-flag mr-1"></em>Status</span>
<strong><span :class="statusClass">{{ batch.status }}</span></strong>
</div>
</div>
</div>






<div class="card-inner border-top">
<h6 class="title mb-3"><em class="icon ni ni-user mr-1"></em>Farmer Details</h6>
<div class="details-grid">
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-user mr-1 label-icon"></em>Name</span>
<strong>{{ [farmer.first_name, farmer.last_name].filter(Boolean).join(' ') || 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-users mr-1 label-icon"></em>Gender</span>
<strong>{{ farmer.gender ?? 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-growth mr-1 label-icon"></em>Primary Crop</span>
<strong>{{ farmer.crop ?? 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-map-pin mr-1 label-icon"></em>Location</span>
<strong>{{ farmer.location ?? 'N/A' }}</strong>
</div>

<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-call mr-1 label-icon"></em>Telephone</span>
<strong>{{ farmer.telephone ?? 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-mail mr-1 label-icon"></em>Email</span>
<strong>{{ farmer.email ?? 'N/A' }}</strong>
</div>


<div class="detail-item detail-item-full">
<span class="sub-text"><em class="icon ni ni-check-circle mr-1 label-icon"></em>Status</span>
<strong>{{ farmer.status ?? 'N/A' }}</strong>
</div>

</div>
</div>









<div class="card-inner border-top">
<h6 class="title mb-3"><em class="icon ni ni-coins mr-1"></em>Payment Details</h6>
<div class="details-grid">
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-hash mr-1 label-icon"></em>Payment ID</span>
<strong>{{ payment?.id ?? 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-flag mr-1 label-icon"></em>Status</span>
<strong><span :class="paymentStatusClass(payment?.status)">
    {{ payment?.status ?? 'N/A' }}</span></strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-package mr-1 label-icon"></em>Quantity</span>
<strong>{{ payment?.quantity ?? 'N/A' }} kg</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-coins mr-1 label-icon"></em>Unit Price</span>
<strong>{{ formatMoney(payment?.unit_price) }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-wallet mr-1 label-icon"></em>Total Amount</span>
<strong>{{ formatMoney(payment?.total_amount) }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-user mr-1 label-icon"></em>Buyer</span>
<strong>{{ payment?.buyer?.name ?? 'N/A' }}</strong>
</div>
<div class="detail-item detail-item-full">
<span class="sub-text"><em class="icon ni ni-file-text mr-1 label-icon"></em>Notes</span>
<strong>{{ payment?.notes ?? 'N/A' }}</strong>
</div>
</div>

<div class="payment-history mt-3">
<h6 class="title title-sm mb-2">Payment History</h6>
<div v-if="paymentHistory.length" class="table-responsive">
<table class="table table-sm align-middle mb-0">
<thead>
<tr>
<th>ID</th>
<th>Quantity</th>
<th>Unit Price</th>
<th>Total</th>
<th>Status</th>
<th>Date</th>
</tr>
</thead>
<tbody>
<tr v-for="item in paymentHistory" :key="item.id">
<td>#{{ item.id }}</td>
<td>{{ item.quantity }}</td>
<td>{{ formatMoney(item.unit_price) }}</td>
<td>{{ formatMoney(item.total_amount) }}</td>
<td><span :class="paymentStatusClass(item.status)">{{ item.status }}</span></td>
<td>{{ formatDate(item.created_at) }}</td>
</tr>
</tbody>
</table>
</div>
<div v-else class="empty-payments">
No payment records yet.
</div>
</div>
</div>
</div>
</div>
</CooperativeLayout>
</template>

<style scoped>
.details-grid {
display: grid;
grid-template-columns: repeat(2, minmax(0, 1fr));
gap: 12px;
}

.detail-item {
background: #f8fafc;
border-radius: 10px;
padding: 12px;
display: flex;
flex-direction: column;
gap: 4px;
}

.detail-item-full {
grid-column: span 2;
}

.payment-history {
border-top: 1px dashed #dbe3ee;
padding-top: 14px;
}

.empty-payments {
background: #f8fafc;
border: 1px dashed #dbe3ee;
border-radius: 10px;
padding: 12px;
color: #64748b;
}

.label-icon {
color: currentColor;
font-size: 14px;
opacity: 1;
}

@media (max-width: 768px) {
.details-grid {
grid-template-columns: 1fr;
}

.detail-item-full {
grid-column: span 1;
}
}
</style>
