<script setup>
import { computed } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Money, CreditCard } from '@element-plus/icons-vue';

const page = usePage();
const cartItems = computed(() => page.props.cart_items ?? []);
const cartTotal = computed(() => Number(page.props.cart_total ?? 0) || 0);

const totalItems = computed(() =>
  cartItems.value.reduce((sum, item) => sum + (Number(item?.quantity) || 0), 0)
);

const totalWeight = computed(() =>
  cartItems.value.reduce(
    (sum, item) => sum + ((Number(item?.weight) || 0) * (Number(item?.quantity) || 0)),
    0,
  )
);

const totalAmountToPay = computed(() => {
  const fromItems = cartItems.value.reduce((sum, item) => sum + (Number(item?.line_total) || 0), 0);
  return fromItems || cartTotal.value;
});

const checkoutForm = useForm({
  shipping_name: '',
  shipping_phone: '',
  shipping_email: '',
  shipping_country: '',
  shipping_city: '',
  shipping_address: '',
  shipping_notes: '',
  payment_method: 'mobile_money',
  payer_name: '',
  payment_reference: '',
});

const submitCheckout = () => {
  checkoutForm.post(route('market.checkout.store'), {
    preserveScroll: true,
  });
};

const formatMoney = (value) => {
  const amount = Number(value ?? 0);
  if (Number.isNaN(amount)) return 'N/A';
  return amount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
};
</script>

<template>
  <CooperativeLayout>
    <div class="container">
      <div class="card card-bordered checkout-page-card">
        <div class="card-inner border-bottom checkout-head">
          <div>
            <h6 class="title mb-1"><em class="icon ni ni-wallet mr-1"></em>Checkout</h6>
            <p class="sub-text mb-0">Review your order, shipping details, and payment details.</p>
          </div>

          <div class="checkout-head-actions">
            <div class="checkout-meta">
              <span class="meta-pill"><strong>{{ totalItems }}</strong> Items</span>
              <span class="meta-pill"><strong>{{ totalWeight.toLocaleString() }}</strong> kg</span>
              <span class="meta-pill"><strong>UGX {{ formatMoney(totalAmountToPay) }}</strong> Total</span>
            </div>
          </div>
        </div>

        <div class="card-inner checkout-table-body">
          <div v-if="cartItems.length" class="checkout-table-wrap">
            <div class="table-responsive">
              <table class="table table-sm table-middle mb-0 checkout-table">
                <thead>
                  <tr>
                    <th><span class="table-head-label"><em class="icon ni ni-hash"></em>Batch</span></th>
                    <th><span class="table-head-label"><em class="icon ni ni-growth"></em>Commodity</span></th>
                    <th><span class="table-head-label"><em class="icon ni ni-box-view"></em>Type</span></th>
                    <th class="cell-right"><span class="table-head-label"><em class="icon ni ni-package"></em>Qty</span></th>
                    <th class="cell-right"><span class="table-head-label"><em class="icon ni ni-coins"></em>Unit Price</span></th>
                    <th class="cell-right"><span class="table-head-label"><em class="icon ni ni-wallet"></em>Total</span></th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="item in cartItems" :key="item.id">
                    <td>{{ item.batch_code ?? `#${item.batch_id}` }}</td>
                    <td class="text-capitalize">{{ item.commodity_name ?? 'N/A' }}</td>
                    <td class="text-capitalize">{{ item.commodity_type ?? 'N/A' }}</td>
                    <td class="cell-right">{{ item.quantity ?? 1 }}</td>
                    <td class="cell-right">UGX {{ formatMoney(item.unit_price) }}</td>
                    <td class="fw-semibold cell-right">UGX {{ formatMoney(item.line_total) }}</td>
                  </tr>
                </tbody>
                <tfoot>
                  <tr>
                    <td colspan="5" class="tfoot-label">Grand Total</td>
                    <td class="tfoot-total cell-right">UGX {{ formatMoney(totalAmountToPay) }}</td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
          <div v-else class="empty-state">No items available for checkout.</div>
        </div>

        <div class="card-inner checkout-form-body">












            


<form @submit.prevent="submitCheckout">
            <div class="row g-3">
            <div class="col-12 col-md-6">
              <div class="section-panel">
                <h6 class="mb-2"><em class="icon ni ni-map-pin mr-1"></em>Shipping Details</h6>

                <div class="form-group mb-2">
                  <label class="form-label">Full Name</label>
                  <el-input v-model="checkoutForm.shipping_name" size="large" placeholder="Enter recipient name" />
                  <InputError :message="checkoutForm.errors.shipping_name" class="mt-1" />
                </div>

                <div class="form-group mb-2">
                  <label class="form-label">Phone Number</label>
                  <el-input v-model="checkoutForm.shipping_phone" size="large" placeholder="Enter shipping phone" />
                  <InputError :message="checkoutForm.errors.shipping_phone" class="mt-1" />
                </div>

                <div class="form-group mb-2">
                  <label class="form-label">Email</label>
                  <el-input v-model="checkoutForm.shipping_email" size="large" placeholder="Enter shipping email" />
                  <InputError :message="checkoutForm.errors.shipping_email" class="mt-1" />
                </div>

                <div class="row g-2">
                  <div class="col-12 col-md-6">
                    <label class="form-label">Country</label>
                    <el-input v-model="checkoutForm.shipping_country" size="large" placeholder="Country" />
                    <InputError :message="checkoutForm.errors.shipping_country" class="mt-1" />
                  </div>
                  <div class="col-12 col-md-6">
                    <label class="form-label">City</label>
                    <el-input v-model="checkoutForm.shipping_city" size="large" placeholder="City" />
                    <InputError :message="checkoutForm.errors.shipping_city" class="mt-1" />
                  </div>
                </div>

                <div class="form-group mt-2">
                  <label class="form-label">Address</label>
                  <el-input
                    v-model="checkoutForm.shipping_address"
                    type="textarea"
                    :rows="3"
                    placeholder="Enter detailed delivery address"
                  />
                  <InputError :message="checkoutForm.errors.shipping_address" class="mt-1" />
                </div>

                <div class="form-group mt-2">
                  <label class="form-label">Shipping Notes (Optional)</label>
                  <el-input
                    v-model="checkoutForm.shipping_notes"
                    type="textarea"
                    :rows="2"
                    placeholder="Any additional notes"
                  />
                  <InputError :message="checkoutForm.errors.shipping_notes" class="mt-1" />
                </div>
              </div>
            </div>

            <div class="col-12 col-md-6">
              <div class="section-panel">
                <h6 class="mb-2 section-title"><el-icon class="mr-1"><CreditCard /></el-icon>Payment Details</h6>

                <div class="form-group mb-2">
                  <label class="form-label">Payment Method</label>
                  <el-select v-model="checkoutForm.payment_method" class="w-100" size="large" placeholder="Select payment method">
                    <el-option label="Mobile Money" value="mobile_money" />
                    <el-option label="Bank Transfer" value="bank_transfer" />
                    <el-option label="Card Payment" value="card" />
                  </el-select>
                  <InputError :message="checkoutForm.errors.payment_method" class="mt-1" />
                </div>

                <div class="form-group mb-2">
                  <label class="form-label">Payer Name</label>
                  <el-input v-model="checkoutForm.payer_name" size="large" placeholder="Enter payer/account name" />
                  <InputError :message="checkoutForm.errors.payer_name" class="mt-1" />
                </div>

                <div class="form-group mb-2">
                  <label class="form-label">Payment Reference</label>
                  <el-input v-model="checkoutForm.payment_reference" size="large" placeholder="Transaction reference / account number" />
                  <InputError :message="checkoutForm.errors.payment_reference" class="mt-1" />
                </div>

                <div class="amount-box mt-3">
                  <div class="amount-row">
                    <span>Items ({{ totalItems }})</span>
                    <strong>UGX {{ formatMoney(totalAmountToPay) }}</strong>
                  </div>
                  <div class="amount-row total-row">
                    <span>Total Amount To Pay</span>
                    <strong>UGX {{ formatMoney(totalAmountToPay) }}</strong>
                  </div>
                </div>

                <div class="mt-3">
                  <el-button
                    type="primary"
                    class="checkout-btn"
                    :icon="Money"
                    native-type="submit"
                    :disabled="!cartItems.length || checkoutForm.processing"
                  >
                    Confirm Checkout
                  </el-button>
                </div>
              </div>
            </div>
          </div>
</form>










        </div>
      </div>
    </div>
  </CooperativeLayout>
</template>

<style scoped>
.checkout-page-card {
  border-radius: 12px;
}

.checkout-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  flex-wrap: wrap;
  background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
}

.checkout-head-actions {
  display: flex;
  flex-direction: column;
  gap: 10px;
  align-items: flex-end;
}

.checkout-meta {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
}

.meta-pill {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  border: 1px solid #dbe5f1;
  border-radius: 999px;
  padding: 4px 10px;
  font-size: 12px;
  color: #526484;
  background: #ffffff;
}

.checkout-table-body {
  padding: 0 16px 16px !important;
}

.checkout-table-wrap {
  border: 1px solid #dbe5f1;
  border-radius: 12px;
  overflow: hidden;
  background: #ffffff;
  box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04);
  margin-top: 12px;
}

.checkout-table {
  width: 100%;
  margin: 0;
  min-width: 780px;
}

.checkout-table th {
  background: #f8fafc;
  color: #526484;
  font-weight: 600;
  white-space: nowrap;
  border-bottom: 1px solid #dbe5f1;
}

.checkout-table td {
  white-space: nowrap;
  border-top: 1px solid #edf2f7;
}

.checkout-table tbody tr:nth-child(even) {
  background: #fcfdff;
}

.checkout-table tbody tr:hover {
  background: #f3f8ff;
}

.checkout-table tfoot td {
  background: #f8fafc;
  border-top: 1px solid #dbe5f1;
  font-weight: 700;
}

.table-head-label {
  display: inline-flex;
  align-items: center;
  gap: 6px;
}

.cell-right {
  text-align: right;
  font-variant-numeric: tabular-nums;
}

.tfoot-label {
  color: #334155;
}

.tfoot-total {
  color: #0f172a;
}

.checkout-form-body {
  border-top: 1px solid #e5e9f2;
  padding: 18px !important;
}

.section-panel {
  border: 1px solid #dbe5f1;
  border-radius: 12px;
  background: #ffffff;
  padding: 14px;
}

.form-label {
  font-weight: 600;
  color: #334155;
  margin-bottom: 6px;
}

.section-title {
  display: inline-flex;
  align-items: center;
}

.amount-box {
  border: 1px solid #dbe5f1;
  border-radius: 10px;
  padding: 10px 12px;
  background: #f8fafc;
}

.amount-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  color: #526484;
}

.total-row {
  margin-top: 8px;
  padding-top: 8px;
  border-top: 1px solid #dbe5f1;
  color: #1e293b;
}

.checkout-btn {
  width: 100%;
}

.empty-state {
  border: 1px dashed #d9e2f0;
  border-radius: 10px;
  background: #f8fafc;
  padding: 16px 24px;
  color: #64748b;
}

@media (max-width: 768px) {
  .checkout-head-actions {
    width: 100%;
    align-items: stretch;
  }
}
</style>
