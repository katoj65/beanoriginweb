<script setup>
import { computed, ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { ElNotification } from 'element-plus';
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
const ownership=computed(()=>props.item.ownership);

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
if (!Number.isInteger(requested) || requested < 0) return null;
if (requested === 0) return 0;
return unitPrice.value * requested;
});
const quantityHint = computed(() => {
if (availableQuantity.value === null) return 'Enter a quantity to continue.';
if (availableQuantity.value < 1) return 'No stock available for this batch.';
return `Enter a quantity between 1 and ${availableQuantity.value}.`;
});

const openModal = () => {
if (!batchId.value || ownership.value) return;
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
if (!batchId.value || ownership.value || clientError.value) return;
const requested = Number(quantity.value);
isSubmitting.value = true;
router.post(
route('cart.store'),
{
batch_id: batchId.value,
quantity: requested,
},
{
preserveScroll: true,
onSuccess: () => {
ElNotification({
title: 'Success',
message: 'Batch added to cart successfully.',
type: 'success',
});
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

const formatWhole = (value) => {
if (value === null || value === undefined || value === '') return 'N/A';
const amount = Number(value);
if (!Number.isFinite(amount)) return String(value);
return Math.floor(amount).toLocaleString();
};
</script>

<template>
<div class="buy-button">

<el-button plain size="small" :icon="ShoppingCart" :disabled="isOutOfStock || ownership" @click.stop="openModal">
{{ isOutOfStock ? 'Unavailable' : 'Buy' }}
</el-button>

<el-dialog
v-model="isModalVisible"
title="Buy Batch"
width="420px"
append-to-body
@closed="closeModal"
>
<div class="buy-modal-body">
<p class="buy-title text-capitalize mb-1">{{ commodityLabel }}</p>
<p class="buy-subtitle mb-3">
Review batch details, choose quantity, and add this item to your cart.
</p>
<div class="buy-simple-summary mb-3">
<div class="buy-simple-row">
<span>Batch</span>
<strong>{{ batchCode }}</strong>
</div>
<div class="buy-simple-row">
<span>Grade</span>
<strong class="text-capitalize">{{ props.item?.grade ?? 'N/A' }}</strong>
</div>
<div class="buy-simple-row">
<span>Weight</span>
<strong>{{ formatWeight(props.item?.weight) }}</strong>
</div>
<div class="buy-simple-row">
<span>Available</span>
<strong>{{ formatWhole(availableQuantity) }}</strong>
</div>
<div class="buy-simple-row">
<span>Unit Price</span>
<strong>{{ formatMoney(unitPrice) }}</strong>
</div>
<div class="buy-simple-row">
<span>Status</span>
<span class="stock-pill" :class="isOutOfStock ? 'stock-pill--out' : 'stock-pill--in'">
{{ isOutOfStock ? 'Out of stock' : 'In stock' }}
</span>
</div>
</div>

<label class="buy-label mb-1 d-block">Quantity to buy</label>
<el-input-number
v-model="quantity"
:min="0"
:step="1"
step-strictly
class="buy-qty-input"
@keyup.enter="submitBuy"
/>
<p class="buy-hint mt-2 mb-0">{{ quantityHint }}</p>

<p class="buy-total mt-3 mb-0">
Estimated total: <strong>{{ formatMoney(lineTotal) }}</strong>
</p>
<p v-if="errorMessage" class="buy-error mt-2 mb-0">{{ errorMessage }}</p>
</div>




<template #footer>
<el-button type="primary" :icon="ShoppingCart" :loading="isSubmitting" :disabled="isSubmitting || !batchId || ownership" @click="submitBuy">
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
font-size: 18px;
font-weight: 600;
color: #101828;
}

.buy-subtitle {
font-size: 14px;
color: #64748b;
line-height: 1.35;
}

.buy-simple-summary {
background: #f8fafc;
border: 1px solid #e2e8f0;
border-radius: 8px;
padding: 10px 12px;
}

.buy-simple-row {
display: flex;
align-items: center;
justify-content: space-between;
gap: 8px;
padding: 4px 0;
font-size: 14px;
color: #334155;
}

.buy-simple-row + .buy-simple-row {
border-top: 1px dashed #dbe5f0;
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
font-size: 14px;
}

.buy-qty-input {
width: 100%;
}

.buy-hint {
color: #64748b;
font-size: 13px;
}

.buy-total {
color: #334155;
font-size: 14px;
}

.buy-error {
color: #dc2626;
font-size: 14px;
}

</style>
