<script setup>
import { computed } from 'vue';
import { Link, router, useForm, usePage } from '@inertiajs/vue3';
import BuyerLayout from '@/Layouts/BuyerLayout.vue';
import InputError from '@/Components/InputError.vue';

const page = usePage();

const listing = computed(() => page.props.response?.listing ?? {});
const recentBids = computed(() => {
  const source = page.props.response?.recent_bids ?? [];
  return Array.isArray(source) ? source : [];
});
const flashSuccess = computed(() => page.props.flash?.success ?? '');

const form = useForm({
  bid_price: listing.value?.price ?? '',
  currency: 'UGX',
  payment_method: 'Bank Transfer',
  message: '',
});

const goBack = () => {
  const batchId = listing.value?.batch_id;
  if (!batchId) {
    router.get(route('buyer.market'));
    return;
  }

  router.get(route('buyer.market.show', { id: batchId }));
};

const submitBid = () => {
  const batchId = listing.value?.batch_id;
  if (!batchId) return;

  form.post(route('buyer.market.bid.store', { id: batchId }), {
    preserveScroll: true,
  });
};

const formatWeight = (value) => `${Number(value ?? 0).toLocaleString()} kg`;
const formatPrice = (value, currency = 'UGX') => `${currency} ${Number(value ?? 0).toLocaleString()}`;
const formatDate = (value) => {
  if (!value) return 'N/A';
  const date = new Date(value);
  if (Number.isNaN(date.getTime())) return String(value);
  return date.toLocaleString();
};

const statusClass = (status) => {
  const key = String(status ?? '').toLowerCase();
  if (key === 'approved') return 'badge bg-success-subtle text-success';
  if (key === 'rejected') return 'badge bg-danger-subtle text-danger';
  return 'badge bg-warning-subtle text-warning';
};
</script>

<template>
  <buyer-layout>
    <div class="container py-1">
      <div class="card card-bordered bid-page-card">
        <div class="card-inner border-bottom bid-head">
          <div>
            <h5 class="title mb-1"><em class="icon ni ni-coins mr-1"></em>Bid On Batch</h5>
            <p class="sub-text mb-0">Submit your offer for this listed batch.</p>
          </div>
          <el-button plain @click="goBack">Back To Details</el-button>
        </div>

        <div class="card-inner">
          <div v-if="flashSuccess" class="alert alert-success alert-sm mb-3">{{ flashSuccess }}</div>

          <div class="bid-layout">
            <div class="listing-panel">
              <h6 class="section-title">Listing Overview</h6>
              <div class="listing-grid">
                <div class="listing-item">
                  <span class="sub-text">Commodity</span>
                  <strong>{{ listing.commodity_name ?? 'N/A' }}</strong>
                </div>
                <div class="listing-item">
                  <span class="sub-text">Batch Code</span>
                  <strong>{{ listing.batch_code ?? 'N/A' }}</strong>
                </div>
                <div class="listing-item">
                  <span class="sub-text">Grade</span>
                  <strong>{{ listing.grade ?? 'N/A' }}</strong>
                </div>
                <div class="listing-item">
                  <span class="sub-text">Weight</span>
                  <strong>{{ formatWeight(listing.weight) }}</strong>
                </div>
                <div class="listing-item listing-item-full">
                  <span class="sub-text">Asking Price</span>
                  <strong class="ask-price">{{ formatPrice(listing.price) }}</strong>
                </div>
              </div>

              <p class="sub-text mb-0 mt-3">
                Listed:
                <strong class="text-dark">{{ formatDate(listing.created_at) }}</strong>
              </p>
            </div>

            <div class="form-panel">
              <h6 class="section-title">Your Bid</h6>
              <form @submit.prevent="submitBid">
                <div class="form-row">
                  <label class="form-label">Bid Amount</label>
                  <el-input v-model="form.bid_price" type="number" step="0.01" min="0.01" placeholder="Enter amount" />
                  <InputError :message="form.errors.bid_price" class="mt-1" />
                </div>

                <div class="form-row">
                  <label class="form-label">Currency</label>
                  <el-select v-model="form.currency" placeholder="Select currency" class="w-100">
                    <el-option label="UGX" value="UGX" />
                    <el-option label="USD" value="USD" />
                    <el-option label="EUR" value="EUR" />
                  </el-select>
                  <InputError :message="form.errors.currency" class="mt-1" />
                </div>

                <div class="form-row">
                  <label class="form-label">Payment Method</label>
                  <el-select v-model="form.payment_method" placeholder="Select payment method" class="w-100" clearable>
                    <el-option label="Bank Transfer" value="Bank Transfer" />
                    <el-option label="Mobile Money" value="Mobile Money" />
                    <el-option label="Cash" value="Cash" />
                    <el-option label="Card" value="Card" />
                  </el-select>
                  <InputError :message="form.errors.payment_method" class="mt-1" />
                </div>

                <div class="form-row">
                  <label class="form-label">Message (Optional)</label>
                  <el-input
                    v-model="form.message"
                    type="textarea"
                    :rows="4"
                    maxlength="2000"
                    show-word-limit
                    placeholder="Share your terms or timeline..."
                  />
                  <InputError :message="form.errors.message" class="mt-1" />
                </div>

                <div class="form-actions">
                  <el-button :loading="form.processing" type="primary" native-type="submit">Submit Bid</el-button>
                  <Link :href="route('buyer.market')" class="btn btn-outline-light">Cancel</Link>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      <div class="card card-bordered mt-4">
        <div class="card-inner border-bottom">
          <h6 class="title mb-1"><em class="icon ni ni-activity mr-1"></em>Recent Bids</h6>
          <p class="sub-text mb-0">Latest offers submitted for this block.</p>
        </div>
        <div class="card-body p-0">
          <el-table :data="recentBids" stripe style="width: 100%" height="280">
            <el-table-column prop="buyer_name" min-width="180" label="Buyer" />
            <el-table-column prop="purchase_price" width="170" label="Bid">
              <template #default="scope">
                {{ formatPrice(scope.row.purchase_price, scope.row.currency || 'UGX') }}
              </template>
            </el-table-column>
            <el-table-column prop="payment_method" width="170" label="Payment Method" />
            <el-table-column prop="status" width="130" label="Status">
              <template #default="scope">
                <span :class="statusClass(scope.row.status)">{{ scope.row.status }}</span>
              </template>
            </el-table-column>
            <el-table-column prop="created_at" min-width="180" label="Submitted">
              <template #default="scope">{{ formatDate(scope.row.created_at) }}</template>
            </el-table-column>
          </el-table>
        </div>
      </div>
    </div>
  </buyer-layout>
</template>

<style scoped>
.bid-page-card {
  border-radius: 12px;
}

.bid-head {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 12px;
}

.bid-layout {
  display: grid;
  grid-template-columns: 1fr 1.2fr;
  gap: 16px;
}

.listing-panel,
.form-panel {
  background: #f8fafc;
  border-radius: 12px;
  padding: 14px;
}

.section-title {
  font-size: 0.95rem;
  font-weight: 700;
  margin-bottom: 12px;
}

.listing-grid {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 10px;
}

.listing-item {
  background: #fff;
  border: 1px solid #edf2f7;
  border-radius: 10px;
  padding: 10px;
  display: flex;
  flex-direction: column;
  gap: 3px;
}

.listing-item-full {
  grid-column: 1 / -1;
}

.ask-price {
  color: #0f766e;
}

.form-row {
  margin-bottom: 12px;
}

.form-actions {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-top: 8px;
}

@media (max-width: 991px) {
  .bid-layout {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 768px) {
  .bid-head {
    flex-direction: column;
    align-items: flex-start;
  }
}
</style>
