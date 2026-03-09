<script setup>
import { computed, ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import { Search, Tickets } from '@element-plus/icons-vue';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';

const page = usePage();
const searchQuery = ref('');
const activeGroup = ref('bids');

const batches = computed(() => {
  const source = page.props.batches ?? [];
  return Array.isArray(source) ? source : [];
});

const filteredBatches = computed(() => {
  if (activeGroup.value === 'bids') return batches.value;

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

const getBidUsers = (item) => {
  const users = Array.isArray(item?.bid_users) ? item.bid_users : [];
  const seen = new Set();

  return users.filter((user) => {
    const key = user?.id ?? user?.email ?? '';
    if (!key || seen.has(key)) return false;
    seen.add(key);
    return true;
  });
};

const formatBidderName = (user) => {
  const fullName = [user?.fname, user?.lname].filter(Boolean).join(' ').trim();
  return fullName || user?.email || `User #${user?.id ?? ''}`;
};

const visibleBidderNames = (item) => getBidUsers(item).slice(0, 2).map(formatBidderName);

const hiddenBidderCount = (item) => Math.max(getBidUsers(item).length - 2, 0);

const hasBidders = (item) => getBidUsers(item).length > 0;

const openBatchDetails = (id) => {
  const batchId = Number(id ?? 0);
  if (!batchId) return;
  router.get(`/market/batch-bidding/${batchId}`);
};

// Open the logged-in user's bids page from the Bids button.
const openMyBids = () => {
  router.get(route('market.bids.user'));
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
          <div class="table-actions">
            <el-button-group class="batch-group">
              <el-button
              
                :icon="Tickets"
                :type="activeGroup === 'bids' ? 'primary' : 'default'"
                @click="openMyBids"
              >
                Bids
              </el-button>
              <el-button

                :icon="Search"
                :type="activeGroup === 'search' ? 'primary' : 'default'"
                @click="activeGroup = 'search'"
              >
                Search
              </el-button>
            </el-button-group>
            <!-- <el-input
              v-model="searchQuery"
              class="search-input"
              placeholder="Search..."
              :prefix-icon="Search"
              clearable
            /> -->
          </div>
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
                <th><span class="table-head-label"><em class="icon ni ni-users"></em>Bidders</span></th>
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
                <td>
                  <div class="bidders-cell">
                    <template v-if="hasBidders(item)">
                      <span v-for="name in visibleBidderNames(item)" :key="name" class="bidder-chip text-capitalize">
                        {{ name }}
                      </span>
                      <span v-if="hiddenBidderCount(item)" class="bidders-more">+{{ hiddenBidderCount(item) }} more</span>
                    </template>
                    <span v-else class="bidders-empty">No bids</span>
                  </div>
                </td>
              </tr>
              <tr v-if="!filteredBatches.length">
                <td colspan="8" class="empty-cell">No bidding batches found.</td>
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

.bidders-cell {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 0.35rem;
}

.bidder-chip {
  display: inline-flex;
  align-items: center;
  max-width: 155px;
  padding: 0.14rem 0.5rem;
  border-radius: 999px;
  border: 1px solid #dbe3ef;
  color: #334155;
  background: #ffffff;
  font-size: 0.71rem;
  line-height: 1.25;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.bidders-more {
  font-size: 0.7rem;
  color: #64748b;
}

.bidders-empty {
  font-size: 0.72rem;
  color: #94a3b8;
}

.clickable-row {
  cursor: pointer;
}

.empty-cell {
  text-align: center;
  color: #64748b;
  padding: 1rem 0.75rem !important;
}

.table-actions {
  margin-left: auto;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

@media (max-width: 768px) {
  .table-top {
    flex-direction: column;
    align-items: flex-start;
  }

  .table-actions {
    width: 100%;
    margin-left: 0;
    flex-direction: column;
    align-items: stretch;
  }

  .search-input {
    width: 100%;
    margin-left: 0;
  }
}
</style>
