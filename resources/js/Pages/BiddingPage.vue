<script setup>
import { computed, ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import { InfoFilled, Search, Tickets } from '@element-plus/icons-vue';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';

const page = usePage();
const searchQuery = ref('');

const batches = computed(() => {
  const source = page.props.batches ?? [];
  return Array.isArray(source) ? source : [];
});

const filteredBatches = computed(() => {
  const query = searchQuery.value.trim().toLowerCase();
  if (!query) return batches.value;

  return batches.value.filter((item) => {
    const haystack = [
      item.batch_code,
      item.commodity_name,
      item.commodity_type,
      item.grade,
      item.warehouse,
      item.status,
      item.market_type,
    ]
      .join(' ')
      .toLowerCase();

    return haystack.includes(query);
  });
});

const formatMoney = (value, currency = 'UGX') => {
  const amount = Number(value ?? 0);
  if (Number.isNaN(amount)) return `${currency} 0`;
  return `${currency} ${amount.toLocaleString()}`;
};

const formatQuantity = (value) => Number(value ?? 0).toLocaleString();

const formatWeight = (value) => `${Number(value ?? 0).toLocaleString()} kg`;

const openBatchDetails = (id) => {
  const batchId = Number(id ?? 0);
  if (!batchId) return;
  router.get(route('market.show', { id: batchId }));
};

</script>

<template>
  <CooperativeLayout>
    <div class="container bidding-page">
      <section class="card card-bordered table-card">
        <div class="card-inner table-top">
          <div class="table-heading">
            <div class="title-line">
              <el-icon class="title-icon"><Tickets /></el-icon>
              <h6 class="mb-0">Bidding Batches</h6>
            </div>
            <p class="subtitle mb-0">
             
              Click any row to open batch details.
            </p>
          </div>
          <el-input
            v-model="searchQuery"
            class="search-input"
            placeholder="Search..."
            :prefix-icon="Search"
            clearable
          />
        </div>

        <div class="table-responsive">
          <table class="table table-sm table-middle mb-0 bidding-table">
            <thead>
              <tr>
                <th><span class="table-head-label"><em class="icon ni ni-hash"></em>Batch</span></th>
                <th><span class="table-head-label"><em class="icon ni ni-growth"></em>Commodity</span></th>
                <th><span class="table-head-label"><em class="icon ni ni-award"></em>Grade</span></th>
                <th><span class="table-head-label"><em class="icon ni ni-package"></em>Weight</span></th>
                <th><span class="table-head-label"><em class="icon ni ni-layers"></em>Quantity</span></th>
                <th><span class="table-head-label"><em class="icon ni ni-coins"></em>Price</span></th>
                <th><span class="table-head-label"><em class="icon ni ni-building"></em>Warehouse</span></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in filteredBatches" :key="item.id" class="clickable-row" @click="openBatchDetails(item.id)">
                <td>{{ item.batch_code ?? `#${item.id}` }}</td>
                <td class="text-capitalize">{{ item.commodity_name ?? 'N/A' }} - {{ item.commodity_type ?? 'N/A' }}</td>
                <td>{{ item.grade ?? 'N/A' }}</td>
                <td>{{ formatWeight(item.weight) }}</td>
                <td>{{ formatQuantity(item.quantity) }}</td>
                <td>{{ formatMoney(item.price) }}</td>
                <td>{{ item.warehouse ?? 'N/A' }}</td>
              </tr>
              <tr v-if="!filteredBatches.length">
                <td colspan="7" class="empty-cell">No bidding batches found.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>
    </div>
  </CooperativeLayout>
</template>

<style scoped>
.bidding-page {
  display: grid;
  gap: 1rem;
}

.table-card {
  border-radius: 14px;
  overflow: hidden;
}

.table-top {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 0.75rem;
  border-bottom: 1px solid #e2e8f0;
}

.table-heading {
  display: grid;
  gap: 0.2rem;
}

.title-line {
  display: flex;
  align-items: center;
  gap: 0.35rem;
}

.title-icon {
  color: #0f766e;
}

.subtitle {
  display: inline-flex;
  align-items: center;
  gap: 0.3rem;
  font-size: 12px;
  color: #64748b;
}

.subtitle-icon {
  color: #0ea5e9;
}

.search-input {
  margin-left: auto;
  width: 250px;
  --el-input-height: 35px;
}

.search-input :deep(.el-input__wrapper) {
  height: 35px !important;
  min-height: 35px !important;
  padding-top: 0 !important;
  padding-bottom: 0 !important;
  font-size: 12px;
}

.search-input :deep(.el-input__inner) {
  height: 35px !important;
  line-height: 35px !important;
}

.bidding-table thead th {
  font-size: 0.72rem;
  text-transform: uppercase;
  letter-spacing: 0.04em;
  color: #64748b;
  background: #f8fafc;
}

.table-head-label {
  display: inline-flex;
  align-items: center;
  gap: 0.35rem;
}

.bidding-table tbody td {
  vertical-align: middle;
}

.clickable-row {
  cursor: pointer;
}

.empty-cell {
  text-align: center;
  color: #64748b;
  padding: 1rem 0.75rem !important;
}

@media (max-width: 768px) {
  .table-top {
    flex-direction: column;
    align-items: flex-start;
  }

  .search-input {
    width: 100%;
    margin-left: 0;
  }
}
</style>
