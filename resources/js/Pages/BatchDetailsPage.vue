<script setup>
import { computed, ref } from 'vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';
import SubmitButton from '@/Components/SubmitButton.vue';
import ReserveButton from '@/Components/ReserveButton.vue';
import { Back, ShoppingCart } from '@element-plus/icons-vue';

const page = usePage();
const batch = computed(() => page.props.batch?.data ?? page.props.batch ?? {});

const statusClass = computed(() => {
const status = (batch.value?.status ?? '').toLowerCase();
if (status === 'listed') return 'badge bg-success-subtle text-success';
if (status === 'sold') return 'badge bg-info-subtle text-info';
if (status === 'processing' || status === 'processed') return 'badge bg-warning-subtle text-warning';
return 'badge bg-light text-dark';
});

const chainClass = computed(() => (
batch.value?.is_on_chain
? 'badge bg-success-subtle text-success'
: 'badge bg-secondary-subtle text-secondary'
));

const formatDateTime = (value) => {
if (!value) return 'N/A';
const date = new Date(value);
if (Number.isNaN(date.getTime())) return value;
return date.toLocaleString();
};

const formatPrice = (value) => {
if (value === null || value === undefined || value === '') return 'N/A';
const amount = Number(value);
if (Number.isNaN(amount)) return value;
return amount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
};

const goBack = () => {
router.get(route('token.index'));
};

const addBatchModalVisible = ref(false);
const listOnChainForm = useForm({
price: '',
});

const goCreate = () => {
listOnChainForm.clearErrors();
listOnChainForm.price = batch.value?.price ?? '';
addBatchModalVisible.value = true;
};

const submitListOnChain = () => {
if (!batch.value?.id) return;

listOnChainForm.post(route('cooperative.batches.list.on.chain', { id: batch.value.id }), {
preserveScroll: true,
onSuccess: () => {
addBatchModalVisible.value = false;
},
});
};




</script>

<template>
<CooperativeLayout>
<div class="container">
<div class="card details-card">
<div class="card-inner border-bottom details-head">
<div>
<h6 class="title mb-1"><em class="icon ni ni-layers mr-1"></em>Batch Details</h6>
<p class="sub-text mb-0">View full details of the selected commodity batch.</p>
</div>
<el-button-group>
<el-button plain :icon="Back" @click="goBack">Back</el-button>
<reserve-button :batch="batch" title="Buy Coffee Batch" />
</el-button-group>
</div>

<div class="card-inner">
<div class="details-grid">
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-hash mr-1"></em>Batch Code</span>
<strong>{{ batch.batch_code ?? 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-growth mr-1"></em>Commodity</span>
<strong>{{ batch.commodity_name ?? 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-box-view mr-1"></em>Commodity Type</span>
<strong>{{ batch.commodity_type ?? 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-package mr-1"></em>Weight</span>
<strong>{{ batch.weight ?? 'N/A' }} kg</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-coin-alt mr-1"></em>Price</span>
<strong>{{ formatPrice(batch.price) }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-award mr-1"></em>Grade</span>
<strong>{{ batch.grade ?? 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-drop mr-1"></em>Moisture</span>
<strong>{{ batch.moisture ?? 'N/A' }}%</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-home-fill mr-1"></em>Warehouse</span>
<strong>{{ batch.warehouse ?? 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-flag mr-1"></em>Status</span>
<strong><span :class="statusClass">{{ batch.status ?? 'N/A' }}</span></strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-link-alt mr-1"></em>On Chain</span>
<strong><span :class="chainClass">{{ batch.is_on_chain ? 'Yes' : 'No' }}</span></strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-calendar mr-1"></em>Created At</span>
<strong>{{ formatDateTime(batch.created_at) }}</strong>
</div>



<div class="detail-section detail-item-full">
<h6 class="detail-section-title"><em class="icon ni ni-user mr-1"></em>
Batch Owner Details</h6>
</div>

<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-user mr-1"></em>Owner Name</span>
<strong>{{ batch.owner?.name || 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-call mr-1"></em>Owner Phone</span>
<strong>{{ batch.owner?.phone || 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-map-pin mr-1"></em>Owner Address</span>
<strong>{{ batch.owner?.address || 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-mail mr-1"></em>Owner Email</span>
<strong>{{ batch.owner?.email || 'N/A' }}</strong>
</div>
</div>
</div>
</div>
</div>









<el-dialog
v-model="addBatchModalVisible"
class="list-on-chain-dialog"
width="560"
align-center
destroy-on-close
>
<template #header>
<div class="list-dialog-header">
<h5 class="list-dialog-title mb-1">List Batch On Chain</h5>
<p class="list-dialog-subtext mb-0">
Set your asking price to publish this batch on the trading board.
</p>
</div>
</template>

<form @submit.prevent="submitListOnChain" class="list-on-chain-form">
<div class="list-on-chain-modal">
<div class="modal-highlight">
<div class="modal-highlight-item">
<span class="sub-text">Batch Code</span>
<strong>{{ batch.batch_code ?? 'N/A' }}</strong>
</div>
<div class="modal-highlight-item">
<span class="sub-text">Commodity</span>
<strong>{{ batch.commodity_name ?? 'N/A' }}</strong>
</div>
<div class="modal-highlight-item">
<span class="sub-text">Weight</span>
<strong>{{ batch.weight ?? 'N/A' }} kg</strong>
</div>
<div class="modal-highlight-item">
<span class="sub-text">Current Price</span>
<strong>{{ formatPrice(batch.price) }}</strong>
</div>
</div>

<div class="form-group mb-1">
<label class="form-label mb-1">Listing Price (USD)</label>
<el-input
v-model="listOnChainForm.price"
type="number"
min="0.01"
step="0.01"
size="large"
clearable
class="price-input"
placeholder="Enter listing price"
>
<template #prefix>Shs. </template>
</el-input>
<div class="sub-text mt-1">This price will be visible to buyers on the chain listing.</div>
<div v-if="listOnChainForm.errors.price" class="text-danger small mt-1">
{{ listOnChainForm.errors.price }}
</div>
</div>

<div class="modal-note">
Submitting will list this batch on-chain and mark it as <strong>listed</strong>.
</div>
</div>

<div class="dialog-submit dialog-submit-single">
<SubmitButton :title="'List On Chain'" :status="listOnChainForm.processing" />
</div>
</form>
</el-dialog>




</CooperativeLayout>
</template>

<style scoped>
.details-card {
border-radius: 12px;
}

.details-head {
display: flex;
justify-content: space-between;
align-items: center;
gap: 12px;
}

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
grid-column: 1 / -1;
}

.detail-section {
border-top: 1px solid #dbe3ee;
padding-top: 10px;
margin-top: 2px;
}

.detail-section-title {
margin: 0;
}

.list-on-chain-modal {
padding: 4px 2px 6px;
}

.list-on-chain-modal .form-label {
font-weight: 600;
color: #334155;
}

:deep(.el-dialog.list-on-chain-dialog),
:deep(.list-on-chain-dialog .el-dialog) {
border-radius: var(--el-dialog-border-radius) !important;
overflow: hidden;
}

:deep(.el-dialog.list-on-chain-dialog .el-dialog__header),
:deep(.list-on-chain-dialog .el-dialog__header) {
padding: 20px 24px 14px;
border-bottom: 1px solid #e2e8f0;
background: linear-gradient(180deg, #f8fbff 0%, #ffffff 100%);
}

:deep(.el-dialog.list-on-chain-dialog .el-dialog__body),
:deep(.list-on-chain-dialog .el-dialog__body) {
padding: 22px 24px 24px;
}

.list-dialog-title {
font-size: 18px;
font-weight: 700;
color: #0f172a;
}

.list-dialog-subtext {
font-size: 13px;
color: #64748b;
}

.list-on-chain-form {
display: flex;
flex-direction: column;
gap: 16px;
}

.modal-highlight {
display: grid;
grid-template-columns: repeat(2, minmax(0, 1fr));
gap: 12px;
padding: 14px;
border: 1px solid #e2e8f0;
border-radius: 14px;
background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
margin-bottom: 12px;
}

.modal-highlight-item {
display: flex;
flex-direction: column;
gap: 2px;
}

.modal-highlight-item strong {
font-size: 14px;
color: #0f172a;
}

.modal-note {
font-size: 13px;
color: #475569;
border: 1px dashed #cbd5e1;
background: #f8fafc;
border-radius: 14px;
padding: 12px 14px;
margin-top: 10px;
}

.price-input :deep(.el-input__wrapper) {
border-radius: 20px;
padding: 0 12px;
box-shadow: 0 0 0 1px #cbd5e1 inset !important;
}

.price-input {
--el-input-focus-border-color: #cbd5e1;
--el-input-hover-border-color: #cbd5e1;
--el-input-border-color: #cbd5e1;
}

.price-input :deep(.el-input__wrapper:hover),
.price-input :deep(.el-input__wrapper.is-focus),
.price-input :deep(.el-input__wrapper:focus-within) {
box-shadow: 0 0 0 1px #cbd5e1 inset !important;
}

.price-input :deep(.el-input__inner),
.price-input :deep(.el-input__inner:focus) {
border: 0 !important;
outline: none !important;
box-shadow: none !important;
}

.dialog-submit {
display: flex;
justify-content: flex-end;
align-items: center;
gap: 10px;
}

.dialog-submit-single :deep(.form-group) {
margin-left: auto;
}

.dialog-submit :deep(.form-group) {
margin-bottom: 0;
}

.dialog-submit :deep(.submit-btn) {
width: auto !important;
min-width: 170px;
}

@media (max-width: 768px) {
.details-head {
flex-direction: column;
align-items: stretch;
}

.details-grid {
grid-template-columns: 1fr;
}

.modal-highlight {
grid-template-columns: 1fr;
}

.dialog-submit {
flex-wrap: wrap;
justify-content: stretch;
}

.dialog-submit > .el-button {
flex: 1;
}

.dialog-submit :deep(.form-group) {
flex: 1;
}

.dialog-submit :deep(.submit-btn) {
width: 100% !important;
min-width: 0;
}

}
</style>
