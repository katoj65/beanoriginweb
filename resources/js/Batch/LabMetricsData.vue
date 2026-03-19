<script setup>
const props = defineProps({
  metrics: {
    type: Array,
    default: () => [],
  },
  metadata: {
    type: Array,
    default: () => [],
  },
});

const formatDateTime = (value) => {
  if (!value) return 'N/A';
  const date = new Date(value);
  if (Number.isNaN(date.getTime())) return value;
  return date.toLocaleString();
};
</script>

<template>
  <div class="lab-metrics-data">
    <div class="lab-metrics-head">
      <div class="lab-metrics-copy">
        <h6 class="title mb-0"><em class="icon ni ni-bar-chart-fill mr-1"></em>Lab Metrics Data</h6>
        <p class="sub-text mb-0">Recorded lab metrics and available metadata options for this batch.</p>
      </div>
      <span v-if="metadata.length" class="metrics-count">
        {{ metadata.length }} metadata options
      </span>
    </div>

    <div v-if="metrics.length" class="table-responsive metrics-table-wrap mt-3">
      <table class="table table-sm table-middle mb-0 metrics-table">
        <thead>
          <tr>
            <th><span class="head-label"><em class="icon ni ni-activity"></em>Metric</span></th>
            <th><span class="head-label"><em class="icon ni ni-tag"></em>Value</span></th>
            <th><span class="head-label"><em class="icon ni ni-notes"></em>Notes</span></th>
            <th><span class="head-label"><em class="icon ni ni-calendar"></em>Date Added</span></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in metrics" :key="item.id">
            <td class="text-capitalize">
              <span class="metric-cell">
                <em class="icon ni ni-activity"></em>
                {{ item.metric ?? 'N/A' }}
              </span>
            </td>
            <td>{{ item.value ?? 'N/A' }}</td>
            <td>{{ item.notes ?? 'N/A' }}</td>
            <td>{{ formatDateTime(item.created_at) }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-else class="empty-state mt-3">
      No batch lab metrics recorded yet.
    </div>
  </div>
</template>

<style scoped>
.lab-metrics-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  flex-wrap: wrap;
}

.lab-metrics-copy {
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.metrics-count {
  display: inline-flex;
  align-items: center;
  padding: 4px 10px;
  border-radius: 999px;
  border: 1px solid #dbe7ff;
  background: #f3f7ff;
  color: #1e40af;
  font-size: 11px;
  font-weight: 600;
  letter-spacing: 0.02em;
  text-transform: uppercase;
}

.metrics-table-wrap {
  border: 1px solid #e5e9f2;
  border-radius: 10px;
  overflow: hidden;
  background: #fff;
}

.metrics-table th {
  background: #f8fafc;
  color: #526484;
  font-weight: 600;
  white-space: nowrap;
}

.metrics-table td {
  color: #364a63;
  white-space: nowrap;
}

.head-label {
  display: inline-flex;
  align-items: center;
  gap: 6px;
}

.head-label .icon {
  color: #8094ae;
  font-size: 13px;
}

.metric-cell {
  display: inline-flex;
  align-items: center;
  gap: 6px;
}

.metric-cell .icon {
  color: #8094ae;
  font-size: 13px;
}

.empty-state {
  border: 1px dashed #d9e2f0;
  border-radius: 10px;
  padding: 12px;
  color: #64748b;
  background: #f8fafc;
}
</style>
