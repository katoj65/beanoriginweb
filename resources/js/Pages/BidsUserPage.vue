<script setup>
import { computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';

const page = usePage();
const bids = computed(() => {
  const source = page.props.bids ?? [];
  return Array.isArray(source) ? source : [];
});

const formatNumber = (value) => Number(value ?? 0).toLocaleString();

const formatMoney = (value, currency = 'UGX') => {
  const amount = Number(value ?? 0);
  if (Number.isNaN(amount)) return `${currency} 0`;
  return `${currency} ${amount.toLocaleString()}`;
};

const formatDate = (value) => {
  if (!value) return 'N/A';
  const date = new Date(value);
  if (Number.isNaN(date.getTime())) return String(value);
  return date.toLocaleString();
};

const statusClass = (status) => {
  const key = String(status ?? '').toLowerCase();
  if (key === 'complete' || key === 'completed' || key === 'approved') return 'badge bg-success-subtle text-success';
  if (key === 'failed' || key === 'rejected' || key === 'banned') return 'badge bg-danger-subtle text-danger';
  return 'badge bg-warning-subtle text-warning';
};

const openBatch = (batchId) => {
  const id = Number(batchId ?? 0);
  if (!id) return;
  router.get(route('bid.show', { id }));
};

const goBackToBidding = () => {
  router.get(route('market.bidding'));
};
</script>

<template>
  <CooperativeLayout>
  
    <div class="container py-0">
      <div class="card card-bordered bids-card">
        <div class="card-inner border-bottom bids-head">
          <div>
            <h6 class="title mb-1"><em class="icon ni ni-tranx mr-1"></em>My Bids</h6>
            <p class="sub-text mb-0">All bids you have submitted from the bidding market.</p>
          </div>
          <el-button @click="goBackToBidding">
            <em class="icon ni ni-arrow-left mr-1"></em>
            Back
          </el-button>
        </div>

        <div class="card-inner p-0">
          <el-table :data="bids" stripe class="bids-table" @row-click="(row) => openBatch(row.batch_id)">
            <el-table-column prop="batch_code" min-width="130" label="Batch" />
            <el-table-column min-width="220" label="Commodity / Grade">
              <template #default="{ row }">
                <span class="text-capitalize">{{ row.commodity_name ?? 'N/A' }}</span>
                <span class="text-muted"> / {{ row.grade ?? 'N/A' }}</span>
              </template>
            </el-table-column>
            <el-table-column prop="available_quantity" width="140" label="Available Qty">
              <template #default="{ row }">{{ formatNumber(row.available_quantity) }}</template>
            </el-table-column>
            <el-table-column width="220" label="Ask / Bid Price">
              <template #default="{ row }">
                <div class="price-pair">
                  <span class="ask-price">Ask: {{ formatMoney(row.ask_price, row.currency) }}</span>
                  <span class="bid-price"><em class="icon ni ni-coins mr-1"></em>Bid: {{ formatMoney(row.bid_price, row.currency) }}</span>
                </div>
              </template>
            </el-table-column>
            <el-table-column prop="bid_quantity" width="130" label="Bid Qty">
              <template #default="{ row }">{{ formatNumber(row.bid_quantity) }}</template>
            </el-table-column>
            <el-table-column prop="status" width="120" label="Status">
              <template #default="{ row }">
                <span :class="statusClass(row.status)" class="text-capitalize">{{ row.status ?? 'pending' }}</span>
              </template>
            </el-table-column>
            <el-table-column prop="warehouse" min-width="160" label="Warehouse" />
            <el-table-column prop="created_at" min-width="180" label="Placed On">
              <template #default="{ row }">{{ formatDate(row.created_at) }}</template>
            </el-table-column>
          </el-table>
          <div v-if="!bids.length" class="empty-row">No bids found.</div>
        </div>
      </div>
    </div>
  </CooperativeLayout>
</template>

<style scoped>
.bids-card {
  border-radius: 12px;
  border: 1px solid #d6e0ec;
  overflow: hidden;
}

.bids-head {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 0.8rem;
  flex-wrap: wrap;
}

.bids-table {
  width: 100%;
}

.bids-table :deep(.el-table__row) {
  cursor: pointer;
}

.price-pair {
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.ask-price {
  color: #64748b;
  font-size: 12px;
}

.bid-price {
  color: #0f172a;
  font-weight: 700;
  font-size: 13px;
  display: inline-flex;
  align-items: center;
}

.empty-row {
  padding: 0.9rem 1rem;
  color: #64748b;
  border-top: 1px solid #e2e8f0;
}

</style>
