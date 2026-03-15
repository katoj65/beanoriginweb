<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';

const page = usePage();
const purchases = computed(() => page.props.purchases ?? []);
const shippingInfo = computed(() => page.props.shipping_info ?? {});
const buyer = computed(() => page.props.buyer ?? {});

const firstPurchase = computed(() => purchases.value[0] ?? null);

const paymentMethod = computed(() => {
  const key = String(firstPurchase.value?.payment_method ?? '').trim().toLowerCase();
  if (key === 'mobile_money') return 'Mobile Money';
  if (key === 'bank_transfer') return 'Bank Transfer';
  if (key === 'card') return 'Card Payment';
  return key ? key.replaceAll('_', ' ') : 'N/A';
});

const statusLabel = computed(() => {
  const key = String(firstPurchase.value?.status ?? 'completed').trim().toLowerCase();
  if (key === 'completed' || key === 'complete') return 'Completed';
  if (key === 'pending') return 'Pending';
  if (key === 'failed') return 'Failed';
  if (key === 'rejected') return 'Rejected';
  if (key === 'banned') return 'Banned';
  return key || 'Completed';
});

const statusClass = computed(() => {
  const key = String(firstPurchase.value?.status ?? 'completed').trim().toLowerCase();
  return key === 'completed' || key === 'complete' ? 'status-complete' : 'status-other';
});

const shoppingSession = computed(() => String(firstPurchase.value?.shopping_cart_session ?? 'N/A'));
const transactionReference = computed(() => String(firstPurchase.value?.transaction_reference ?? 'N/A'));
const currency = computed(() => String(firstPurchase.value?.currency ?? 'UGX').trim() || 'UGX');
const shippingAddress = computed(() => String(shippingInfo.value?.address ?? firstPurchase.value?.address ?? 'N/A'));
const buyerName = computed(() => {
  const first = firstPurchase.value?.buyer;
  const full = `${first?.fname ?? ''} ${first?.lname ?? ''}`.trim();
  const fallback = `${buyer.value?.fname ?? ''} ${buyer.value?.lname ?? ''}`.trim();
  return full || shippingInfo.value?.name || fallback || 'N/A';
});
const buyerEmail = computed(() =>
  String(firstPurchase.value?.buyer?.email ?? shippingInfo.value?.email ?? buyer.value?.email ?? 'N/A')
);

const issuedAt = computed(() => {
  const raw = firstPurchase.value?.created_at;
  if (!raw) {
    return new Date().toLocaleString(undefined, {
      year: 'numeric',
      month: 'short',
      day: '2-digit',
      hour: '2-digit',
      minute: '2-digit',
    });
  }

  const date = new Date(raw);
  if (Number.isNaN(date.getTime())) return String(raw);

  return date.toLocaleString(undefined, {
    year: 'numeric',
    month: 'short',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
  });
});

const receiptNumber = computed(() => {
  const session = shoppingSession.value;
  if (session && session !== 'N/A') return `REC-${session.slice(0, 10).toUpperCase()}`;
  return firstPurchase.value?.id ? `REC-${String(firstPurchase.value.id).padStart(6, '0')}` : 'REC-N/A';
});

const numberOfItems = computed(() => purchases.value.length);
const totalQuantity = computed(() =>
  purchases.value.reduce((sum, item) => sum + (Number(item?.quantity) || 0), 0)
);
const totalAmount = computed(() =>
  purchases.value.reduce((sum, item) => sum + (Number(item?.total_price) || 0), 0)
);

const formatAmount = (value) => {
  const amount = Number(value ?? 0);
  if (Number.isNaN(amount)) return `${currency.value} 0.00`;
  return `${currency.value} ${amount.toLocaleString(undefined, {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  })}`;
};

const formatNumber = (value) => {
  const amount = Number(value ?? 0);
  if (Number.isNaN(amount)) return '0';
  return amount.toLocaleString(undefined, { maximumFractionDigits: 2 });
};

const printReceipt = () => window.print();
</script>

<template>
  <CooperativeLayout>
    <div class="confirmation-shell">
      <div class="receipt-card card">
        <div class="receipt-top">
          <div class="success-mark" aria-hidden="true">
            <em class="icon ni ni-check-circle-fill"></em>
          </div>
          <p class="mb-1 overline">Payment Success</p>
          <h3 class="mb-1">Purchase Confirmation</h3>
          <p class="sub-text mb-0">Your payment was received successfully. Keep this receipt as proof of payment.</p>
          <span class="status-pill" :class="statusClass">{{ statusLabel }}</span>
          <div class="top-metrics">
            <span class="metric-chip"><em class="icon ni ni-layers"></em><span>{{ numberOfItems }} Items</span></span>
            <span class="metric-chip"><em class="icon ni ni-package"></em><span>{{ formatNumber(totalQuantity) }} Qty</span></span>
            <span class="metric-chip"><em class="icon ni ni-wallet"></em><span>{{ formatAmount(totalAmount) }}</span></span>
          </div>
        </div>

        <div v-if="firstPurchase" class="receipt-content">
          <div class="meta-grid">
            <div class="meta-card">
              <p class="label label-row mb-1"><em class="icon ni ni-hash"></em><span>Receipt No</span></p>
              <p class="value mb-0">{{ receiptNumber }}</p>
            </div>
            <div class="meta-card">
              <p class="label label-row mb-1"><em class="icon ni ni-calendar"></em><span>Issued On</span></p>
              <p class="value mb-0">{{ issuedAt }}</p>
            </div>
            <div class="meta-card">
              <p class="label label-row mb-1"><em class="icon ni ni-user"></em><span>Buyer</span></p>
              <p class="value mb-0">{{ buyerName }}</p>
              <p class="sub-text mb-0">{{ buyerEmail }}</p>
            </div>
            <div class="meta-card">
              <p class="label label-row mb-1"><em class="icon ni ni-wallet"></em><span>Transaction Ref</span></p>
              <p class="value mb-0">{{ transactionReference }}</p>
            </div>
            <div class="meta-card meta-card-wide">
              <p class="label label-row mb-1"><em class="icon ni ni-map-pin"></em><span>Address</span></p>
              <p class="value mb-0 address-value">{{ shippingAddress }}</p>
            </div>
          </div>

          <div class="shipping-box">
            <div class="shipping-head">
              <em class="icon ni ni-truck" aria-hidden="true"></em>
              <p class="label mb-0">Shipping Information</p>
            </div>
            <div class="shipping-grid">
              <div class="shipping-item">
                <p class="shipping-label shipping-label-row mb-1"><em class="icon ni ni-user"></em><span>Recipient Name</span></p>
                <p class="shipping-value mb-0">{{ shippingInfo.name || buyerName }}</p>
              </div>
              <div class="shipping-item">
                <p class="shipping-label shipping-label-row mb-1"><em class="icon ni ni-user"></em><span>Recipient Email</span></p>
                <p class="shipping-value mb-0">{{ shippingInfo.email || buyerEmail }}</p>
              </div>
              <div class="shipping-item">
                <p class="shipping-label shipping-label-row mb-1"><em class="icon ni ni-call"></em><span>Contact Phone</span></p>
                <p class="shipping-value mb-0">{{ shippingInfo.phone || 'N/A' }}</p>
              </div>
              <div class="shipping-item">
                <p class="shipping-label shipping-label-row mb-1"><em class="icon ni ni-map-pin"></em><span>Delivery Address</span></p>
                <p class="shipping-value mb-0">{{ shippingInfo.address || 'N/A' }}</p>
              </div>
            </div>
            <div class="shipping-note">
              <p class="shipping-label shipping-label-row mb-1"><em class="icon ni ni-file-text"></em><span>Delivery Notes</span></p>
              <p class="shipping-value mb-0">{{ shippingInfo.notes || 'N/A' }}</p>
            </div>
            <div class="table-responsive details-table-wrap">
              <table class="table table-sm details-table mb-0">
                <thead>
                  <tr>
                    <th><span class="th-label"><em class="icon ni ni-hash"></em>#</span></th>
                    <th><span class="th-label"><em class="icon ni ni-package"></em>Batch</span></th>
                    <th class="text-right"><span class="th-label th-label-right"><em class="icon ni ni-layers"></em>Quantity</span></th>
                    <th class="text-right"><span class="th-label th-label-right"><em class="icon ni ni-coins"></em>Unit Price</span></th>
                    <th class="text-right"><span class="th-label th-label-right"><em class="icon ni ni-wallet"></em>Total</span></th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in purchases" :key="item.id ?? index">
                    <td><span class="row-index">{{ index + 1 }}</span></td>
                    <td><span class="batch-pill">B-{{ item.batch_id ?? 'N/A' }}</span></td>
                    <td class="text-right">{{ formatNumber(item.quantity) }}</td>
                    <td class="text-right">{{ formatAmount(item.unit_price) }}</td>
                    <td class="text-right">{{ formatAmount(item.total_price) }}</td>
                  </tr>
                </tbody>
                <tfoot>
                  <tr>
                    <td colspan="4" class="text-right fw-600">Items</td>
                    <td class="text-right">{{ numberOfItems }}</td>
                  </tr>
                  <tr>
                    <td colspan="4" class="text-right fw-600">Total Quantity</td>
                    <td class="text-right">{{ formatNumber(totalQuantity) }}</td>
                  </tr>
                  <tr class="grand-total-row">
                    <td colspan="4" class="text-right">Total Amount Paid</td>
                    <td class="text-right">{{ formatAmount(totalAmount) }}</td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>

          <div class="bottom-row">
            <p class="sub-text mb-0 method-pill"><em class="icon ni ni-wallet"></em><span>Payment Method: <strong>{{ paymentMethod }}</strong></span></p>
            <div class="actions-row">
              <button class="btn btn-outline-light border action-btn" type="button" @click="printReceipt"><em class="icon ni ni-printer"></em><span>Print Receipt</span></button>
              <Link :href="route('market.index')" class="btn btn-primary action-btn"><em class="icon ni ni-arrow-right"></em><span>Continue Trading</span></Link>
            </div>
          </div>
        </div>

        <div v-else class="empty-state">
          <p class="mb-0">No receipt details found for your latest payment.</p>
        </div>
      </div>
    </div>
  </CooperativeLayout>
</template>

<style scoped>
.confirmation-shell {
  padding: 0;
  background: transparent;
}

.receipt-card {
  width: 100%;
  border-radius: 14px;
  overflow: hidden;
  border: 1px solid #d7e3f0;
  border-top: 0;
  box-shadow: 0 8px 24px rgba(31, 43, 58, 0.06);
  background: #fff;
}

.receipt-top {
  text-align: center;
  padding: 30px 20px 24px;
  background: linear-gradient(180deg, #ffffff 0%, #f6fbf8 100%);
}

.success-mark {
  width: 94px;
  height: 94px;
  margin: 0 auto 12px;
  border-radius: 999px;
  display: grid;
  place-items: center;
  background: #eaf9f0;
  color: #0f9d58;
  font-size: 56px;
}

.overline {
  font-size: 0.72rem;
  text-transform: uppercase;
  letter-spacing: 0.12em;
  font-weight: 700;
  color: #6b7b92;
}

.status-pill {
  margin-top: 10px;
  display: inline-flex;
  border-radius: 999px;
  padding: 0.24rem 0.75rem;
  font-size: 0.74rem;
  font-weight: 700;
  border: 1px solid transparent;
}

.top-metrics {
  margin-top: 12px;
  display: flex;
  justify-content: center;
  gap: 8px;
  flex-wrap: wrap;
}

.metric-chip {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  border: 1px solid #dbe8f7;
  border-radius: 999px;
  background: #ffffff;
  padding: 5px 10px;
  color: #314861;
  font-size: 0.8rem;
  font-weight: 600;
}

.metric-chip .icon {
  color: #3b6db1;
}

.status-complete {
  color: #0f9d58;
  background: #edf9f1;
  border-color: #bde6ca;
}

.status-other {
  color: #9a6700;
  background: #fff6e9;
  border-color: #f2d7a6;
}

.receipt-content {
  padding: 18px;
}

.meta-grid {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 12px;
  margin-bottom: 14px;
}

.meta-card {
  border: 1px solid #e1eaf5;
  border-radius: 12px;
  background: #fbfdff;
  padding: 11px 12px;
}

.meta-card-wide {
  grid-column: 1 / -1;
}

.label {
  font-size: 0.72rem;
  text-transform: uppercase;
  letter-spacing: 0.08em;
  color: #8094ae;
}

.label-row {
  display: inline-flex;
  align-items: center;
  gap: 6px;
}

.label-row .icon {
  font-size: 0.85rem;
  color: #5f7898;
}

.value {
  color: #24344e;
  font-weight: 600;
}

.address-value {
  line-height: 1.35;
  word-break: break-word;
}

.receipt-code {
  font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, 'Liberation Mono', 'Courier New', monospace;
  font-size: 0.82rem;
}

.details-table-wrap {
  border: 1px solid #d7e5f4;
  border-radius: 12px;
  overflow: hidden;
  width: 100%;
  margin-top: 12px;
  background: #ffffff;
}

.shipping-box {
  border: 1px solid #dde8f4;
  border-radius: 12px;
  background: linear-gradient(180deg, #fbfdff 0%, #f6faff 100%);
  padding: 14px;
  margin-bottom: 14px;
  color: #47586f;
  box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.9);
}

.shipping-head {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 10px;
}

.shipping-head .icon {
  color: #3273dc;
  font-size: 1rem;
}

.shipping-grid {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 10px;
}

.shipping-item {
  border: 1px solid #e3edf8;
  border-radius: 10px;
  background: #ffffff;
  padding: 10px;
}

.shipping-label {
  font-size: 0.7rem;
  text-transform: uppercase;
  letter-spacing: 0.08em;
  color: #7a8ea8;
  font-weight: 700;
}

.shipping-label-row {
  display: inline-flex;
  align-items: center;
  gap: 6px;
}

.shipping-label-row .icon {
  color: #4a6e97;
  font-size: 0.86rem;
}

.shipping-value {
  color: #22324a;
  font-size: 0.9rem;
  font-weight: 600;
  word-break: break-word;
}

.shipping-note {
  margin-top: 10px;
  border: 1px dashed #c9daed;
  border-radius: 10px;
  background: #f7fbff;
  padding: 10px;
}

.details-table {
  margin: 0;
}

.details-table th {
  background: #f8fafc;
  color: #5a6e89;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  font-size: 0.69rem;
  border-bottom: 1px solid #e0eaf6;
  font-weight: 700;
}

.th-label {
  display: inline-flex;
  align-items: center;
  gap: 5px;
}

.th-label-right {
  justify-content: flex-end;
  width: 100%;
}

.th-label .icon {
  font-size: 0.78rem;
  color: #5a6e89;
}

.details-table td,
.details-table th {
  padding: 0.5rem 0.56rem;
}

.details-table td {
  font-size: 0.84rem;
}

.row-index {
  display: inline-grid;
  place-items: center;
  min-width: 22px;
  height: 22px;
  border-radius: 999px;
  background: #f0f6ff;
  color: #355a8d;
  font-size: 0.74rem;
  font-weight: 700;
}

.batch-pill {
  display: inline-block;
  border: 1px solid #d7e5f4;
  background: #f8fbff;
  border-radius: 999px;
  padding: 2px 9px;
  color: #304864;
  font-weight: 600;
  font-size: 0.78rem;
}

.details-table tbody tr:nth-child(even) td {
  background: #fcfdff;
}

.details-table tbody tr:hover td {
  background: #f3f9ff;
}

.details-table tfoot td {
  background: #f8fbff;
  border-top: 1px solid #dbe6f4;
}

.grand-total-row td {
  font-weight: 700;
  color: #1f2b3a;
  background: #eef7f2 !important;
}

.bottom-row {
  margin-top: 12px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 10px;
  flex-wrap: wrap;
}

.actions-row {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
}

.method-pill {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  border: 1px solid #dce7f5;
  border-radius: 999px;
  background: #f7faff;
  padding: 6px 11px;
}

.method-pill .icon {
  color: #316dc2;
}

.action-btn {
  display: inline-flex;
  align-items: center;
  gap: 6px;
}

.empty-state {
  padding: 28px;
  text-align: center;
  color: #5a6e89;
}

@media (max-width: 767px) {
  .meta-grid {
    grid-template-columns: 1fr;
  }

  .shipping-grid {
    grid-template-columns: 1fr;
  }

  .success-mark {
    width: 84px;
    height: 84px;
    font-size: 50px;
  }

  .top-metrics {
    justify-content: flex-start;
  }
}
</style>
