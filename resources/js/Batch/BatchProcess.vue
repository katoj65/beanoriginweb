<script setup>
const props = defineProps({
  batchProcessingData: {
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
  <div class="batch-process-action">
    <div class="batch-process-head">
      <div class="batch-process-copy">
        <h6 class="title mb-0"><em class="icon ni ni-setting-alt mr-1"></em>Batch Processing Data</h6>
        <p class="sub-text mb-0">Track and manage processing activities for this batch.</p>
      </div>
    </div>

    <div v-if="batchProcessingData.length" class="table-responsive process-table-wrap mt-3">
      <table class="table table-sm table-middle mb-0 process-table">
        <thead>
          <tr>
            <th><span class="head-label"><em class="icon ni ni-activity"></em>Activity</span></th>
            <th><span class="head-label"><em class="icon ni ni-tag"></em>Value</span></th>
            <th><span class="head-label"><em class="icon ni ni-calendar"></em>Date Added</span></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in batchProcessingData" :key="item.id">
            <td class="text-capitalize">
              <span class="activity-cell">
                <em class="icon ni ni-activity"></em>
                {{ item.activity ?? 'N/A' }}
              </span>
            </td>
            <td>{{ item.value ?? 'N/A' }}</td>
            <td>{{ formatDateTime(item.created_at) }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-else class="empty-process mt-3">
      No batch processing data recorded yet.
    </div>

  </div>
</template>

<style scoped>
.batch-process-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  flex-wrap: wrap;
}

.batch-process-copy {
  display: flex;
  flex-direction: column;
  gap: 2px;
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

.activity-cell {
  display: inline-flex;
  align-items: center;
  gap: 6px;
}

.activity-cell .icon {
  color: #8094ae;
  font-size: 13px;
}

.process-table-wrap {
  border: 1px solid #e5e9f2;
  border-radius: 10px;
  overflow: hidden;
  background: #fff;
}

.process-table th {
  background: #f8fafc;
  color: #526484;
  font-weight: 600;
  white-space: nowrap;
}

.process-table td {
  color: #364a63;
  white-space: nowrap;
}

.empty-process {
  border: 1px dashed #d9e2f0;
  border-radius: 10px;
  padding: 12px;
  color: #64748b;
  background: #f8fafc;
}
</style>
