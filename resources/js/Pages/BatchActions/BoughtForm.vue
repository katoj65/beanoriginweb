<script setup>
import { reactive, watch, computed } from 'vue';
import SubmitButton from '@/Components/SubmitButton.vue';
import { usePage, useForm } from '@inertiajs/vue3';


const page=usePage();
const props = defineProps({
  batch: {
    type: Object,
    default: () => ({}),
  },
  processing: {
    type: Boolean,
    default: false,
  },
  submitLabel: {
    type: String,
    default: 'Record Purchase',
  },
});

const emit = defineEmits(['submit', 'cancel']);

const form = useForm({
  batch_id: '',
  batch_number: '',
  buyer_name: '',
  buyer_phone: '',
  buyer_email: '',
  buyer_company: '',
  quantity: '',
  unit_price: '',
  total_amount: '0.00',
  purchase_date: '',
  payment_status: 'pending',
  shipping_method: '',
  shipping_provider: '',
  tracking_number: '',
  shipping_address: '',
  shipping_city: '',
  shipping_country: '',
  dispatch_date: '',
  expected_delivery_date: '',
  shipping_notes: '',
});

const shippingMethods = [
  { label: 'Road Freight', value: 'road' },
  { label: 'Sea Freight', value: 'sea' },
  { label: 'Air Freight', value: 'air' },
  { label: 'Rail Freight', value: 'rail' },
  { label: 'Courier', value: 'courier' },
];

const paymentStatuses = [
  { label: 'Pending', value: 'pending' },
  { label: 'Partially Paid', value: 'partial' },
  { label: 'Paid', value: 'paid' },
];

const computeTotal = () => {
  const qty = Number(form.quantity || 0);
  const price = Number(form.unit_price || 0);
  form.total_amount = Number.isFinite(qty * price) ? (qty * price).toFixed(2) : '0.00';
};

watch(
  () => [form.quantity, form.unit_price],
  () => computeTotal(),
  { immediate: true }
);

watch(
  () => props.batch,
  (batch) => {
    form.batch_id = batch?.id ?? '';
    form.batch_number = batch?.batch_code ?? batch?.batch_number ?? '';
  },
  { immediate: true, deep: true }
);

const submit = () => {

};

//Batch details
const batchDetails=computed(()=>page.props.batch.data);






</script>

<template>
  <form class="bought-form theme-no-highlight mt-3" @submit.prevent="submit">

{{ batch_code }}




    <div class="bought-form-head">
      <div>
        <h5 class="head-title mb-1">Batch Purchase & Shipping</h5>
        <p class="head-subtext mb-0">Capture buyer information, payment values, and logistics details in one flow.</p>
      </div>
      <div class="head-metrics">
        <div class="head-metric">
          <span>Batch</span>
          <strong>Code: {{ batchDetails.batch_code || 'Not selected' }}</strong>
        </div>
        <div class="head-metric">
          <span>Estimated Total</span>
          <strong>{{ form.total_amount }}</strong>
        </div>
      </div>
    </div>

    <div class="section-card">
      <div class="section-title-row">
        <h6 class="section-title mb-0">Batch & Buyer Details</h6>
        <span class="section-tag">Required</span>
      </div>
      <div class="row g-3 mt-1">
        <div class="col-12 col-md-6">
          <label class="form-label">Batch Number</label>
          <el-input v-model="form.batch_number" size="large" placeholder="Enter batch number" />
        </div>

        <div class="col-12 col-md-6">
          <label class="form-label">Buyer Name</label>
          <el-input v-model="form.buyer_name" size="large" placeholder="Enter buyer full name" />
        </div>

        <div class="col-12 col-md-6">
          <label class="form-label">Buyer Phone</label>
          <el-input v-model="form.buyer_phone" size="large" placeholder="Enter buyer phone" />
        </div>

        <div class="col-12 col-md-6">
          <label class="form-label">Buyer Email</label>
          <el-input v-model="form.buyer_email" size="large" placeholder="Enter buyer email" />
        </div>

        <div class="col-12 col-md-6">
          <label class="form-label">Buyer Company (optional)</label>
          <el-input v-model="form.buyer_company" size="large" placeholder="Enter buyer company" />
        </div>
      </div>
    </div>

    <div class="section-card">
      <div class="section-title-row">
        <h6 class="section-title mb-0">Purchase Details</h6>
      </div>
      <div class="row g-3 mt-1">
        <div class="col-12 col-md-4">
          <label class="form-label">Quantity (kg)</label>
          <el-input v-model="form.quantity" type="number" min="0.01" step="0.01" size="large" placeholder="0.00" />
        </div>

        <div class="col-12 col-md-4">
          <label class="form-label">Unit Price</label>
          <el-input v-model="form.unit_price" type="number" min="0.01" step="0.01" size="large" placeholder="0.00" />
        </div>

        <div class="col-12 col-md-4">
          <label class="form-label">Total Amount</label>
          <el-input :model-value="form.total_amount" size="large" readonly class="total-field" />
        </div>

        <div class="col-12 col-md-6">
          <label class="form-label">Purchase Date</label>
          <input v-model="form.purchase_date" type="date" class="form-control form-control-lg date-field" />
        </div>

        <div class="col-12 col-md-6">
          <label class="form-label">Payment Status</label>
          <el-select v-model="form.payment_status" class="w-100 theme-no-highlight-select" size="large" popper-class="theme-no-highlight-select-popper">
            <el-option v-for="item in paymentStatuses" :key="item.value" :label="item.label" :value="item.value" />
          </el-select>
        </div>
      </div>
    </div>

    <div class="section-card">
      <div class="section-title-row">
        <h6 class="section-title mb-0">Shipping Details</h6>
        <span class="section-tag section-tag-muted">Logistics</span>
      </div>
      <div class="row g-3 mt-1">
        <div class="col-12 col-md-6">
          <label class="form-label">Shipping Method</label>
          <el-select v-model="form.shipping_method" class="w-100 theme-no-highlight-select" size="large" clearable filterable popper-class="theme-no-highlight-select-popper" placeholder="Select method">
            <el-option v-for="item in shippingMethods" :key="item.value" :label="item.label" :value="item.value" />
          </el-select>
        </div>

        <div class="col-12 col-md-6">
          <label class="form-label">Shipping Provider</label>
          <el-input v-model="form.shipping_provider" size="large" placeholder="e.g. DHL, Maersk" />
        </div>

        <div class="col-12 col-md-6">
          <label class="form-label">Tracking Number</label>
          <el-input v-model="form.tracking_number" size="large" placeholder="Enter tracking number" />
        </div>

        <div class="col-12 col-md-6">
          <label class="form-label">Shipping Address</label>
          <el-input v-model="form.shipping_address" size="large" placeholder="Enter shipping address" />
        </div>

        <div class="col-12 col-md-4">
          <label class="form-label">City</label>
          <el-input v-model="form.shipping_city" size="large" placeholder="Enter city" />
        </div>

        <div class="col-12 col-md-4">
          <label class="form-label">Country</label>
          <el-input v-model="form.shipping_country" size="large" placeholder="Enter country" />
        </div>

        <div class="col-12 col-md-4">
          <label class="form-label">Dispatch Date</label>
          <input v-model="form.dispatch_date" type="date" class="form-control form-control-lg date-field" />
        </div>

        <div class="col-12 col-md-6">
          <label class="form-label">Expected Delivery Date</label>
          <input v-model="form.expected_delivery_date" type="date" class="form-control form-control-lg date-field" />
        </div>

        <div class="col-12">
          <label class="form-label">Shipping Notes (optional)</label>
          <el-input v-model="form.shipping_notes" type="textarea" :rows="3" placeholder="Add shipping instructions or notes" />
        </div>
      </div>
    </div>

    <div class="action-row">
      <SubmitButton :title="submitLabel" :status="processing" />
    </div>
  </form>
</template>

<style scoped>
.bought-form {
  background: #f8fafc;
  border-radius: 18px;
  padding: 18px;
}

.bought-form-head {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 16px;
  margin-bottom: 16px;
}

.head-title {
  font-size: 22px;
  font-weight: 700;
  color: #0f172a;
  letter-spacing: -0.01em;
}

.head-subtext {
  color: #475569;
  font-size: 13px;
}

.head-metrics {
  display: flex;
  gap: 10px;
}

.head-metric {
  min-width: 170px;
  border: 1px solid #cbd5e1;
  background: #ffffff;
  border-radius: 12px;
  padding: 10px 12px;
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 2px;
}

.head-metric span {
  font-size: 12px;
  color: #64748b;
  font-weight: 600;
}

.head-metric strong {
  font-size: 16px;
  color: #0b1f3a;
  word-break: break-word;
}

.section-card {
  background: #ffffff;
  border: 1px solid #dbe3ee;
  border-radius: 14px;
  padding: 16px;
  margin-bottom: 14px;
  box-shadow: 0 8px 24px rgba(15, 23, 42, 0.04);
}

.section-title-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 10px;
}

.section-title {
  font-size: 15px;
  font-weight: 700;
  color: #0b1f3a;
}

.section-tag {
  background: #e2e8f0;
  color: #334155;
  border-radius: 999px;
  padding: 4px 10px;
  font-size: 11px;
  font-weight: 600;
}

.section-tag-muted {
  background: #f1f5f9;
  color: #475569;
}

.form-label {
  font-weight: 600;
  color: #1e293b;
  margin-bottom: 8px;
  font-size: 13px;
}

.total-field :deep(.el-input__wrapper) {
  background: #f1f5f9 !important;
  font-weight: 700;
}

.date-field {
  border-color: #dcdfe6;
  min-height: 44px;
  border-radius: 10px;
  background: #ffffff;
}

.action-row {
  display: flex;
  justify-content: flex-end;
  align-items: center;
  gap: 10px;
  padding-top: 12px;
  border-top: 1px solid #dbe3ee;
}

.cancel-btn {
  min-height: 48px;
}

.bought-form :deep(.el-input__wrapper),
.bought-form :deep(.el-select__wrapper) {
  border-radius: 10px;
  min-height: 44px !important;
  box-shadow: 0 0 0 1px #dcdfe6 inset !important;
}

.action-row :deep(.form-group) {
  margin-bottom: 0;
}

.action-row :deep(.submit-btn) {
  min-width: 220px;
}

@media (max-width: 767px) {
  .bought-form {
    padding: 14px;
  }

  .bought-form-head {
    flex-direction: column;
    align-items: stretch;
  }

  .head-metrics {
    flex-direction: column;
  }

  .head-metric {
    width: 100%;
  }

  .action-row {
    flex-direction: column-reverse;
    align-items: stretch;
  }

  .action-row :deep(.submit-btn) {
    min-width: 100%;
  }
}
</style>
