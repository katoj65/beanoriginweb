<script setup>
import { computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import BuyerLayout from '@/Layouts/BuyerLayout.vue';

const page = usePage();

const listing = computed(() => page.props.response?.listing ?? {});
const history = computed(() => {
  const source = page.props.response?.history ?? [];
  return Array.isArray(source) ? source : [];
});

const goBack = () => {
  router.get(route('buyer.market'));
};

const goToBuy = () => {
  const batchId = listing.value?.batch_id;
  if (!batchId) return;

  router.get(route('buyer.market.buy', { id: batchId }));
};

const formatWeight = (value) => `${Number(value ?? 0).toLocaleString()} kg`;
const formatPrice = (value) => `UGX ${Number(value ?? 0).toLocaleString()}`;
const formatDate = (value) => {
  if (!value) return 'N/A';
  const date = new Date(value);
  if (Number.isNaN(date.getTime())) return String(value);
  return date.toLocaleString();
};

const statusClass = (status) => {
  const key = String(status ?? '').toLowerCase();
  if (key === 'active') return 'badge bg-success-subtle text-success';
  if (key === 'used') return 'badge bg-secondary-subtle text-secondary';
  return 'badge bg-light text-dark';
};
</script>

<template>
  <buyer-layout>
    <div class="container py-0">
      <div class="card details-card unified-card">
        <div class="card-inner border-bottom details-head">
          <div>
            <h6 class="title mb-1"><em class="icon ni ni-layers mr-1"></em>Market Listing Details</h6>
            <p class="sub-text mb-0">Full details for the selected listed block.</p>
          </div>
          <div class="details-head-actions">
            <el-button type="primary" class="buy-btn" @click="goToBuy" :disabled="!listing.batch_id">
              <em class="icon ni ni-cart-fill mr-1"></em>
              Buy
            </el-button>
            <el-button plain @click="goBack">Back To Market</el-button>
          </div>
        </div>

        <div class="card-inner">
          <div class="details-grid">
            <div class="detail-item">
              <span class="sub-text"><em class="icon ni ni-hash mr-1"></em>Batch ID</span>
              <strong>{{ listing.batch_id ?? 'N/A' }}</strong>
            </div>
            <div class="detail-item">
              <span class="sub-text"><em class="icon ni ni-tag mr-1"></em>Batch Code</span>
              <strong>{{ listing.batch_code ?? 'N/A' }}</strong>
            </div>
            <div class="detail-item">
              <span class="sub-text"><em class="icon ni ni-growth mr-1"></em>Commodity</span>
              <strong>{{ listing.commodity_name ?? 'N/A' }}</strong>
            </div>
            <div class="detail-item">
              <span class="sub-text"><em class="icon ni ni-box-view mr-1"></em>Type</span>
              <strong>{{ listing.commodity_type ?? 'N/A' }}</strong>
            </div>
            <div class="detail-item">
              <span class="sub-text"><em class="icon ni ni-award mr-1"></em>Grade</span>
              <strong>{{ listing.grade ?? 'N/A' }}</strong>
            </div>
            <div class="detail-item">
              <span class="sub-text"><em class="icon ni ni-package mr-1"></em>Weight</span>
              <strong>{{ formatWeight(listing.weight) }}</strong>
            </div>
            <div class="detail-item">
              <span class="sub-text"><em class="icon ni ni-coins mr-1"></em>Ask Price</span>
              <strong>{{ formatPrice(listing.price) }}</strong>
            </div>
            <div class="detail-item">
              <span class="sub-text"><em class="icon ni ni-home-fill mr-1"></em>Warehouse</span>
              <strong>{{ listing.warehouse ?? 'N/A' }}</strong>
            </div>
            <div class="detail-item">
              <span class="sub-text"><em class="icon ni ni-drop mr-1"></em>Moisture</span>
              <strong>{{ listing.moisture ?? 'N/A' }}</strong>
            </div>
            <div class="detail-item">
              <span class="sub-text"><em class="icon ni ni-flag mr-1"></em>Block Status</span>
              <strong><span :class="statusClass(listing.status)">{{ listing.status ?? 'N/A' }}</span></strong>
            </div>
            <div class="detail-item detail-item-full">
              <span class="sub-text"><em class="icon ni ni-calendar mr-1"></em>Listed On</span>
              <strong>{{ formatDate(listing.created_at) }}</strong>
            </div>
            <div class="detail-item detail-item-full">
              <span class="sub-text"><em class="icon ni ni-file-text mr-1"></em>Description</span>
              <strong>{{ listing.description || 'No description available.' }}</strong>
            </div>
            <div class="detail-item detail-item-full">
              <span class="sub-text"><em class="icon ni ni-chat-fill mr-1"></em>Comments</span>
              <strong>{{ listing.comments || 'No comments available.' }}</strong>
            </div>
          </div>
        </div>

        <div class="card-inner section-divider history-head">
          <h6 class="title mb-1"><em class="icon ni ni-clock mr-1"></em>Market History</h6>
          <p class="sub-text mb-0">Latest market activity for this batch.</p>
        </div>
        <div class="history-table-wrap">
          <el-table :data="history" class="market-history-table" style="width: 100%" height="320">
            <el-table-column prop="block_index" width="120" label="Block #" />
            <el-table-column prop="event_type" width="140" label="Event" />
            <el-table-column prop="weight" width="130" label="Weight">
              <template #default="scope">{{ formatWeight(scope.row.weight) }}</template>
            </el-table-column>
            <el-table-column prop="price" width="160" label="Price">
              <template #default="scope">{{ formatPrice(scope.row.price) }}</template>
            </el-table-column>
            <el-table-column prop="status" width="120" label="Status">
              <template #default="scope"><span :class="statusClass(scope.row.status)">{{ scope.row.status }}</span></template>
            </el-table-column>
            <el-table-column prop="created_at" min-width="200" label="Created At">
              <template #default="scope">{{ formatDate(scope.row.created_at) }}</template>
            </el-table-column>
          </el-table>
        </div>
      </div>
    </div>
  </buyer-layout>
</template>

<style scoped>
.details-card {
  border-radius: 12px;
}

.unified-card {
  overflow: hidden;
}

.details-head {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 12px;
}

.details-head-actions {
  display: flex;
  align-items: center;
  gap: 8px;
}

.buy-btn {
  border-radius: 999px;
  padding: 0 18px;
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

.section-divider {
  border-top: 1px solid #e5e9f2;
}

.history-head {
  padding-top: 16px;
  padding-bottom: 12px;
}

.history-table-wrap {
  padding: 0 16px 16px;
}

:deep(.market-history-table) {
  border-radius: 10px;
  overflow: hidden;
  border: 0.5px solid #e9eef6;
  --el-table-border-color: #e9eef6;
  --el-table-border: 0.5px solid #e9eef6;
}

:deep(.market-history-table th.el-table__cell) {
  background: #f6f8fc;
  color: #364a63;
  font-weight: 600;
}

:deep(.market-history-table td.el-table__cell) {
  background: #fff;
}

:deep(.market-history-table td.el-table__cell),
:deep(.market-history-table th.el-table__cell.is-leaf) {
  border-bottom-color: #e9eef6;
}

:deep(.market-history-table tr td.el-table__cell),
:deep(.market-history-table tr th.el-table__cell) {
  border-right-color: #e9eef6;
}

@media (max-width: 768px) {
  .details-head {
    flex-direction: column;
    align-items: flex-start;
  }

  .details-head-actions {
    width: 100%;
  }

  .details-grid {
    grid-template-columns: 1fr;
  }
}
</style>
