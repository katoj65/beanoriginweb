<script setup>
import { computed, ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import { Search } from '@element-plus/icons-vue';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';

const page = usePage();
const search = ref('');
const activityFilter = ref('all');

const requests = computed(() => {
  const source = page.props.response?.requests ?? [];
  return Array.isArray(source) ? source : [];
});

const total = computed(() => Number(page.props.response?.total ?? requests.value.length));

const normalizeActivity = (value) => {
  const key = String(value ?? '').toLowerCase().trim();
  if (['request', 'requested'].includes(key)) return 'pending';
  if (['accepted', 'approved'].includes(key)) return 'accepted';
  if (['rejected', 'declined'].includes(key)) return 'rejected';
  return key || 'pending';
};

const filteredRequests = computed(() => {
  const query = search.value.trim().toLowerCase();

  return requests.value.filter((item) => {
    const normalizedActivity = normalizeActivity(item.activity);

    if (activityFilter.value !== 'all' && normalizedActivity !== activityFilter.value) {
      return false;
    }

    if (!query) return true;

    const haystack = [
      item.batch_code,
      item.commodity_name,
      item.commodity_type,
      item.grade,
      item.buyer_name,
      item.buyer_email,
      item.status,
      item.activity,
      item.warehouse,
    ]
      .join(' ')
      .toLowerCase();

    return haystack.includes(query);
  });
});

const formatWeight = (value) => `${Number(value ?? 0).toLocaleString()} kg`;
const formatQuantity = (value) => Number(value ?? 0).toLocaleString();
const formatMoney = (value, currency = 'UGX') => `${currency} ${Number(value ?? 0).toLocaleString()}`;
const formatDate = (value) => {
  if (!value) return 'N/A';
  const date = new Date(value);
  if (Number.isNaN(date.getTime())) return String(value);
  return date.toLocaleString();
};

const formatRelativeTime = (value) => {
  if (!value) return 'N/A';
  const date = new Date(value);
  if (Number.isNaN(date.getTime())) return 'N/A';
  const diffMs = date.getTime() - Date.now();
  const absSec = Math.round(Math.abs(diffMs) / 1000);
  const rtf = new Intl.RelativeTimeFormat(undefined, { numeric: 'auto' });

  if (absSec < 60) return rtf.format(Math.round(diffMs / 1000), 'second');
  if (absSec < 3600) return rtf.format(Math.round(diffMs / 60000), 'minute');
  if (absSec < 86400) return rtf.format(Math.round(diffMs / 3600000), 'hour');
  return rtf.format(Math.round(diffMs / 86400000), 'day');
};

const pendingCount = computed(() =>
  requests.value.filter((item) => normalizeActivity(item.activity) === 'pending').length
);
const acceptedCount = computed(() =>
  requests.value.filter((item) => normalizeActivity(item.activity) === 'accepted').length
);
const rejectedCount = computed(() =>
  requests.value.filter((item) => normalizeActivity(item.activity) === 'rejected').length
);
const activeLotsCount = computed(() => new Set(requests.value.map((item) => item.batch_id)).size);

const statTiles = computed(() => [
  { label: 'Total Requests', value: total.value, tone: 'neutral', icon: 'ni ni-list-check' },
  { label: 'Pending Review', value: pendingCount.value, tone: 'warning', icon: 'ni ni-clock' },
  { label: 'Accepted', value: acceptedCount.value, tone: 'success', icon: 'ni ni-check-circle' },
  { label: 'Rejected', value: rejectedCount.value, tone: 'danger', icon: 'ni ni-cross-circle' },
  { label: 'Active Lots', value: activeLotsCount.value, tone: 'info', icon: 'ni ni-package' },
]);

const statusClass = (status) => {
  const key = String(status ?? '').toLowerCase();
  if (['approved', 'accepted', 'bought', 'completed'].includes(key)) {
    return 'badge bg-success-subtle text-success';
  }
  if (['rejected', 'cancelled'].includes(key)) {
    return 'badge bg-danger-subtle text-danger';
  }
  return 'badge bg-warning-subtle text-warning';
};

const activityClass = (activity) => {
  const key = normalizeActivity(activity);
  if (key === 'pending') {
    return 'badge bg-info-subtle text-info';
  }
  if (key === 'accepted') {
    return 'badge bg-success-subtle text-success';
  }
  if (key === 'rejected') {
    return 'badge bg-danger-subtle text-danger';
  }
  return 'badge bg-light text-muted';
};

const activityLabel = (activity) => {
  const key = normalizeActivity(activity);
  if (key === 'pending') return 'Pending';
  if (key === 'accepted') return 'Accepted';
  if (key === 'rejected') return 'Rejected';
  return key || 'Pending';
};

const setActivityFilter = (value) => {
  activityFilter.value = value;
};

const clearFilters = () => {
  search.value = '';
  activityFilter.value = 'all';
};

const openBatchDetails = (row) => {
  const batchId = Number(row?.batch_id ?? 0);
  if (!batchId) return;
  router.get(route('market.show', { id: batchId }));
};
</script>

<template>
  <CooperativeLayout>
    <div class="container py-0 bidding-page">
      <div class="stats-grid">
        <article v-for="tile in statTiles" :key="tile.label" class="stats-tile" :data-tone="tile.tone">
          <div class="stats-icon-wrap">
            <em class="icon stats-icon" :class="tile.icon"></em>
          </div>
          <div class="stats-content">
            <span class="stats-label">{{ tile.label }}</span>
            <h5 class="stats-value mb-0">{{ tile.value }}</h5>
          </div>
        </article>
      </div>

      <div class="card card-bordered card-preview modern-panel">
        <div class="card-header bg-white border-bottom table-head">
          <div class="filter-tabs">
            <button type="button" class="filter-tab" :class="{ active: activityFilter === 'all' }" @click="setActivityFilter('all')">
              All
            </button>
            <button
              type="button"
              class="filter-tab"
              :class="{ active: activityFilter === 'pending' }"
              @click="setActivityFilter('pending')"
            >
              Pending
            </button>
            <button
              type="button"
              class="filter-tab"
              :class="{ active: activityFilter === 'accepted' }"
              @click="setActivityFilter('accepted')"
            >
              Accepted
            </button>
            <button
              type="button"
              class="filter-tab"
              :class="{ active: activityFilter === 'rejected' }"
              @click="setActivityFilter('rejected')"
            >
              Rejected
            </button>
          </div>
          <div class="bidding-controls">
            <el-input
              v-model="search"
              placeholder="Search batch, commodity, buyer..."
              clearable
              class="bidding-search"
              :prefix-icon="Search"
            />
            <el-button plain @click="clearFilters">Reset</el-button>
          </div>
        </div>

        <div class="card-body p-0">
          <el-table :data="filteredRequests" height="560" style="width: 100%" row-key="id">
            <el-table-column prop="batch_code" width="140" label="Batch Code" />
            <el-table-column prop="commodity_name" min-width="160" label="Commodity" />
            <el-table-column prop="commodity_type" width="150" label="Type" />
            <el-table-column prop="grade" width="110" label="Grade" />
            <el-table-column prop="weight" width="130" label="Weight">
              <template #default="scope">{{ formatWeight(scope.row.weight) }}</template>
            </el-table-column>
            <el-table-column prop="quantity" width="120" label="Quantity">
              <template #default="scope">{{ formatQuantity(scope.row.quantity) }}</template>
            </el-table-column>
            <el-table-column prop="ask_price" width="150" label="Ask Price">
              <template #default="scope">{{ formatMoney(scope.row.ask_price) }}</template>
            </el-table-column>
            <el-table-column prop="buyer_name" min-width="170" label="Buyer">
              <template #default="scope">
                <div class="buyer-cell">
                  <span class="buyer-name">{{ scope.row.buyer_name }}</span>
                  <small>{{ scope.row.buyer_email }}</small>
                </div>
              </template>
            </el-table-column>
            <el-table-column prop="status" width="120" label="Batch Status">
              <template #default="scope">
                <span :class="statusClass(scope.row.status)">{{ scope.row.status }}</span>
              </template>
            </el-table-column>
            <el-table-column prop="activity" width="120" label="Activity">
              <template #default="scope">
                <span :class="activityClass(scope.row.activity)">{{ activityLabel(scope.row.activity) }}</span>
              </template>
            </el-table-column>
            <el-table-column prop="requested_at" min-width="180" label="Requested At">
              <template #default="scope">
                <div class="date-cell">
                  <span>{{ formatDate(scope.row.requested_at) }}</span>
                  <small>{{ formatRelativeTime(scope.row.requested_at) }}</small>
                </div>
              </template>
            </el-table-column>
            <el-table-column width="120" label="Action" fixed="right">
              <template #default="scope">
                <el-button link type="primary" @click="openBatchDetails(scope.row)">View Lot</el-button>
              </template>
            </el-table-column>
          </el-table>

          <div v-if="!filteredRequests.length" class="empty-state">
            No bidding requests match the current filters.
          </div>
        </div>
      </div>
    </div>
  </CooperativeLayout>
</template>

<style scoped>
.modern-panel {
  border-radius: 12px;
  box-shadow: 0 6px 22px rgba(15, 23, 42, 0.05);
  overflow: hidden;
}

.bidding-page {
  display: grid;
  gap: 1rem;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(5, minmax(0, 1fr));
  gap: 0.75rem;
}

.stats-tile {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  background: #fff;
  border: 1px solid var(--bs-border-color, #dbdfea);
  border-radius: 12px;
  padding: 0.9rem 0.95rem;
  box-shadow: 0 4px 18px rgba(15, 23, 42, 0.04);
}

.stats-icon-wrap {
  width: 34px;
  height: 34px;
  border-radius: 10px;
  display: grid;
  place-items: center;
  background: #f1f5f9;
}

.stats-icon {
  font-size: 1rem;
}

.stats-content {
  min-width: 0;
}

.stats-label {
  display: block;
  font-size: 0.74rem;
  color: #8094ae;
}

.stats-value {
  color: #364a63;
  font-weight: 700;
  line-height: 1.05;
}

.stats-tile[data-tone='warning'] .stats-icon-wrap {
  background: #fff7ed;
  color: #b45309;
}

.stats-tile[data-tone='success'] .stats-icon-wrap {
  background: #ecfdf3;
  color: #15803d;
}

.stats-tile[data-tone='danger'] .stats-icon-wrap {
  background: #fff1f2;
  color: #be123c;
}

.stats-tile[data-tone='info'] .stats-icon-wrap {
  background: #eff6ff;
  color: #1d4ed8;
}

.table-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 0.8rem;
  flex-wrap: wrap;
}

.filter-tabs {
  display: flex;
  align-items: center;
  gap: 0.45rem;
  flex-wrap: wrap;
}

.filter-tab {
  border: 1px solid var(--bs-border-color, #dbdfea);
  background: #fff;
  color: #526484;
  border-radius: 999px;
  padding: 5px 12px;
  font-size: 0.78rem;
  font-weight: 600;
  line-height: 1.15;
  cursor: pointer;
  transition: all 0.16s ease;
}

.filter-tab:hover {
  border-color: #8094ae;
  color: #364a63;
}

.filter-tab.active {
  background: #e9f2ff;
  border-color: #b5d2ff;
  color: #1e429f;
}

.bidding-controls {
  display: flex;
  align-items: center;
  gap: 0.55rem;
}

.bidding-search {
  width: 340px;
}

.buyer-cell {
  display: grid;
  gap: 1px;
}

.buyer-name {
  color: #364a63;
  font-weight: 600;
}

.buyer-cell small,
.date-cell small {
  color: #8094ae;
}

.date-cell {
  display: grid;
  gap: 1px;
}

.empty-state {
  padding: 1.2rem;
  text-align: center;
  color: #8094ae;
}

@media (max-width: 991px) {
  .table-head {
    flex-direction: column;
    align-items: flex-start !important;
  }

  .stats-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }

  .bidding-controls {
    width: 100%;
  }

  .bidding-search {
    width: 100%;
  }
}

@media (max-width: 640px) {
  .stats-grid {
    grid-template-columns: 1fr;
  }
}
</style>
