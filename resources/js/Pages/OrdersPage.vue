<script setup>
import { computed, ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Search } from '@element-plus/icons-vue';
import BuyerLayout from '@/Layouts/BuyerLayout.vue';

const page = usePage();
const search = ref('');

const orders = computed(() => {
  const source = page.props.response?.orders ?? [];
  return Array.isArray(source) ? source : [];
});

const total = computed(() => Number(page.props.response?.total ?? orders.value.length));

const filteredOrders = computed(() => {
  const query = search.value.trim().toLowerCase();
  if (!query) return orders.value;

  return orders.value.filter((item) => {
    const haystack = [
      item.batch_code,
      item.commodity_name,
      item.commodity_type,
      item.grade,
      item.warehouse,
      item.status,
      item.seller_name,
      item.payment_method,
    ]
      .join(' ')
      .toLowerCase();

    return haystack.includes(query);
  });
});

const formatWeight = (value) => `${Number(value ?? 0).toLocaleString()} kg`;
const formatMoney = (value, currency = 'UGX') => `${currency} ${Number(value ?? 0).toLocaleString()}`;
const formatDate = (value) => {
  if (!value) return 'N/A';
  const date = new Date(value);
  if (Number.isNaN(date.getTime())) return String(value);
  return date.toLocaleString();
};

const statusClass = (status) => {
  const key = String(status ?? '').toLowerCase();
  if (key === 'approved' || key === 'accepted' || key === 'completed') {
    return 'badge bg-success-subtle text-success';
  }
  if (key === 'rejected' || key === 'cancelled') {
    return 'badge bg-danger-subtle text-danger';
  }
  return 'badge bg-warning-subtle text-warning';
};
</script>

<template>
  <buyer-layout>
    <div class="container py-0">
      <div class="card card-bordered card-preview modern-panel">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
          <div>
            <h6 class="mb-0">Bids I Have Placed</h6>
            <span class="sub-text">Track bid status and batch details in one place</span>
          </div>
          <div class="orders-controls">
            <span class="sub-text total-chip">{{ total }} bids</span>
            <el-input
              v-model="search"
              placeholder="Search batch, commodity, status..."
              clearable
              class="orders-search"
              :prefix-icon="Search"
            />
          </div>
        </div>

        <div class="card-body p-0">
          <el-table :data="filteredOrders" height="500" style="width: 100%">
            <el-table-column prop="batch_code" width="150" label="Batch Code" />
            <el-table-column prop="commodity_name" min-width="170" label="Commodity" />
            <el-table-column prop="commodity_type" width="150" label="Type" />
            <el-table-column prop="grade" width="110" label="Grade" />
            <el-table-column prop="weight" width="130" label="Weight">
              <template #default="scope">{{ formatWeight(scope.row.weight) }}</template>
            </el-table-column>
            <el-table-column prop="ask_price" width="150" label="Ask Price">
              <template #default="scope">{{ formatMoney(scope.row.ask_price, scope.row.currency) }}</template>
            </el-table-column>
            <el-table-column prop="bid_price" width="150" label="My Bid">
              <template #default="scope">{{ formatMoney(scope.row.bid_price, scope.row.currency) }}</template>
            </el-table-column>
            <el-table-column prop="status" width="130" label="Status">
              <template #default="scope">
                <span :class="statusClass(scope.row.status)">{{ scope.row.status }}</span>
              </template>
            </el-table-column>
            <el-table-column prop="seller_name" min-width="160" label="Seller" />
            <el-table-column prop="warehouse" min-width="150" label="Warehouse" />
            <el-table-column prop="payment_method" min-width="160" label="Payment Method" />
            <el-table-column prop="created_at" min-width="190" label="Placed On">
              <template #default="scope">{{ formatDate(scope.row.created_at) }}</template>
            </el-table-column>
          </el-table>
        </div>
      </div>
    </div>
  </buyer-layout>
</template>

<style scoped>
.modern-panel {
  border-radius: 12px;
  box-shadow: 0 6px 22px rgba(15, 23, 42, 0.05);
  overflow: hidden;
}

.modern-panel .card-header,
.modern-panel .card-inner,
.modern-panel .card-body {
  border-radius: inherit;
}

.orders-controls {
  display: flex;
  align-items: center;
  gap: 0.6rem;
}

.orders-search {
  width: 320px;
}

.total-chip {
  padding: 4px 10px;
  border-radius: 999px;
  background: #f1f5f9;
  color: #334155;
  font-weight: 600;
}

@media (max-width: 991px) {
  .card-header {
    flex-direction: column;
    align-items: flex-start !important;
    gap: 0.75rem;
  }

  .orders-controls {
    width: 100%;
  }

  .orders-search {
    width: 100%;
  }
}
</style>
