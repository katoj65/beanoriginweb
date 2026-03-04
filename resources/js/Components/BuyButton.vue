<script setup>
import { computed, ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { ShoppingCart } from '@element-plus/icons-vue';

const props = defineProps({
item: {
type: Object,
required: true,
},
});

const isModalVisible = ref(false);
const quantity = ref(1);
const serverError = ref('');
const isSubmitting = ref(false);

const batchId = computed(() => Number(props.item?.id ?? 0));
const batchCode = computed(() => props.item?.batch_code ?? `#${props.item?.id ?? 'N/A'}`);
const commodityLabel = computed(
() => `${props.item?.commodity_name ?? 'N/A'} - ${props.item?.commodity_type ?? 'N/A'}`
);
const availableQuantity = computed(() => {
const value = Number(props.item?.quantity);
if (!Number.isFinite(value)) return null;
return Math.max(0, Math.floor(value));
});
const unitPrice = computed(() => {
const value = Number(props.item?.price);
if (!Number.isFinite(value)) return null;
return value;
});
const isOutOfStock = computed(() => availableQuantity.value !== null && availableQuantity.value < 1);
const clientError = computed(() => {
const requested = Number(quantity.value);
if (!batchId.value) return 'Invalid batch.';
if (!Number.isInteger(requested) || requested < 1) return 'Please enter a valid quantity (minimum 1).';
if (isOutOfStock.value) return 'This product is currently out of stock.';
if (availableQuantity.value !== null && requested > availableQuantity.value) {
return `Only ${availableQuantity.value} available for this batch.`;
}
return '';
});
const errorMessage = computed(() => serverError.value || clientError.value);
const lineTotal = computed(() => {
if (unitPrice.value === null) return null;
const requested = Number(quantity.value);
if (!Number.isInteger(requested) || requested < 1) return null;
return unitPrice.value * requested;
});
const canSubmit = computed(() => !isSubmitting.value && !errorMessage.value);

const openModal = () => {
if (!batchId.value) return;
quantity.value = 1;
serverError.value = '';
isModalVisible.value = true;
};

const closeModal = () => {
isModalVisible.value = false;
quantity.value = 1;
serverError.value = '';
};

const submitBuy = () => {
if (!batchId.value || clientError.value) return;

const requested = Number(quantity.value);
isSubmitting.value = true;
router.post(
route('market.cart.store'),
{
batch_id: batchId.value,
quantity: requested,
},
{
preserveScroll: true,
onSuccess: () => {
closeModal();
},
onError: (errors) => {
serverError.value = errors?.quantity ?? errors?.batch_id ?? 'Unable to add batch to cart.';
},
onFinish: () => {
isSubmitting.value = false;
},
}
);
};

watch(quantity, () => {
serverError.value = '';
});

const formatMoney = (value) => {
if (value === null || value === undefined || value === '') return 'N/A';
const amount = Number(value);
if (Number.isNaN(amount)) return 'N/A';
return amount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
};

const formatWeight = (value) => {
if (value === null || value === undefined || value === '') return 'N/A';
const amount = Number(value);
if (Number.isNaN(amount)) return `${value} kg`;
return `${amount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })} kg`;
};
</script>

<template>
<div class="buy-button">
<el-button plain size="small" :icon="ShoppingCart" :disabled="isOutOfStock" @click.stop="openModal">
{{ isOutOfStock ? 'Unavailable' : 'Buy' }}
</el-button>

<el-dialog
v-model="isModalVisible"
title="Buy Product"
width="420px"
append-to-body
@closed="closeModal"
>
<div class="buy-modal-body">
<p class="buy-title text-capitalize mb-1">{{ commodityLabel }}</p>
<div class="buy-details mb-3">
<div class="buy-detail">
<span class="buy-detail-label">Batch Code</span>
<strong class="buy-detail-value">{{ batchCode }}</strong>
</div>
<div class="buy-detail">
<span class="buy-detail-label">Grade</span>
<strong class="buy-detail-value text-capitalize">{{ props.item?.grade ?? 'N/A' }}</strong>
</div>
<div class="buy-detail">
<span class="buy-detail-label">Weight</span>
<strong class="buy-detail-value">{{ formatWeight(props.item?.weight) }}</strong>
</div>
<div class="buy-detail">
<span class="buy-detail-label">Available</span>
<strong class="buy-detail-value">{{ availableQuantity ?? 'N/A' }}</strong>
</div>
<div class="buy-detail">
<span class="buy-detail-label">Unit Price</span>
<strong class="buy-detail-value">{{ formatMoney(unitPrice) }}</strong>
</div>
<div class="buy-detail">
<span class="buy-detail-label">Status</span>
<span class="stock-pill" :class="isOutOfStock ? 'stock-pill--out' : 'stock-pill--in'">
{{ isOutOfStock ? 'Out of stock' : 'In stock' }}
</span>
</div>
</div>

<label class="buy-label mb-1 d-block">Quantity to buy</label>
<el-input-number
v-model="quantity"
:min="1"
:step="1"
step-strictly
class="buy-qty-input"
@keyup.enter="submitBuy"
/>
<p class="buy-total mt-3 mb-0">
Estimated total: <strong>{{ formatMoney(lineTotal) }}</strong>
</p>
<p v-if="errorMessage" class="buy-error mt-2 mb-0">{{ errorMessage }}</p>
</div>

<template #footer>
<el-button type="primary" :icon="ShoppingCart" :loading="isSubmitting" :disabled="!canSubmit" @click="submitBuy">
Add to Cart
</el-button>
</template>
</el-dialog>
</div>
</template>

<style scoped>
.buy-modal-body {
display: block;
}

.buy-title {
font-size: 16px;
font-weight: 600;
color: #101828;
}

.buy-details {
display: grid;
grid-template-columns: repeat(2, minmax(0, 1fr));
gap: 10px;
background: #f8fafc;
border: 1px solid #e2e8f0;
border-radius: 8px;
padding: 10px 12px;
}

.buy-detail {
background: #ffffff;
border-radius: 8px;
padding: 8px 10px;
display: flex;
flex-direction: column;
gap: 4px;
}

.buy-detail-label {
font-size: 11px;
letter-spacing: 0.04em;
text-transform: uppercase;
color: #64748b;
}

.buy-detail-value {
font-size: 13px;
line-height: 1.2;
color: #0f172a;
}

.stock-pill {
display: inline-flex;
align-items: center;
justify-content: center;
width: fit-content;
padding: 2px 8px;
border-radius: 999px;
font-size: 11px;
font-weight: 600;
}

.stock-pill--in {
background: #ecfdf3;
color: #067647;
}

.stock-pill--out {
background: #fef2f2;
color: #b42318;
}

.buy-label {
color: #526484;
font-weight: 600;
}

.buy-qty-input {
width: 100%;
}

.buy-total {
color: #334155;
font-size: 14px;
}

.buy-error {
color: #dc2626;
font-size: 13px;
}

@media (max-width: 520px) {
.buy-details {
grid-template-columns: 1fr;
}
}
</style>
