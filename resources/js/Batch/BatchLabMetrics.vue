<script setup>
import { computed, ref, watch } from 'vue';
import { ElNotification } from 'element-plus';
import { Delete, Plus } from '@element-plus/icons-vue';

const props = defineProps({
  batch: {
    type: Object,
    default: () => ({}),
  },
  canEdit: {
    type: Boolean,
    default: false,
  },
});

const buildMetricRows = (batch) => {
  const candidates = [
    {
      id: 'grade',
      metric: 'Grade',
      value: batch?.grade ?? null,
      notes: 'Quality grading result',
    },
    {
      id: 'moisture',
      metric: 'Moisture',
      value: batch?.moisture === null || batch?.moisture === undefined || batch?.moisture === '' ? null : `${batch.moisture}%`,
      notes: 'Recorded moisture content',
    },
    {
      id: 'density',
      metric: 'Density',
      value: batch?.density === null || batch?.density === undefined || batch?.density === '' ? null : `${batch.density}%`,
      notes: 'Density reading',
    },
    {
      id: 'screen-size',
      metric: 'Screen Size',
      value: batch?.screen_size ?? null,
      notes: 'Screen sizing result',
    },
    {
      id: 'defects',
      metric: 'Defects',
      value: batch?.defects ?? null,
      notes: 'Observed defect count',
    },
  ];

  return candidates
    .filter((item) => item.value !== null && item.value !== undefined && item.value !== '')
    .map((item, index) => ({
      ...item,
      created_at: batch?.created_at ?? null,
      localKey: `${item.id}-${index}`,
    }));
};

const labMetrics = ref(buildMetricRows(props.batch));
const addMetricModalVisible = ref(false);
const form = ref({
  metric: '',
  value: '',
  unit: '',
  notes: '',
});

watch(
  () => props.batch,
  (nextBatch) => {
    labMetrics.value = buildMetricRows(nextBatch);
  },
  { deep: true }
);

const sortedLabMetrics = computed(() => (
  Array.isArray(labMetrics.value) ? [...labMetrics.value] : []
));

const openModal = () => {
  form.value = {
    metric: '',
    value: '',
    unit: '',
    notes: '',
  };
  addMetricModalVisible.value = true;
};

const closeModal = () => {
  addMetricModalVisible.value = false;
};

const submit = () => {
  const metric = form.value.metric.trim();
  const value = form.value.value.trim();
  const unit = form.value.unit.trim();
  const notes = form.value.notes.trim();

  if (!metric || !value) {
    ElNotification({
      title: 'Missing data',
      message: 'Metric name and value are required.',
      type: 'warning',
    });
    return;
  }

  const metricId = `custom-${Date.now()}`;

  labMetrics.value.unshift({
    id: metricId,
    localKey: metricId,
    metric,
    value: unit ? `${value} ${unit}` : value,
    notes: notes || 'Manual entry',
    created_at: new Date().toISOString(),
  });

  closeModal();

  ElNotification({
    title: 'Success',
    message: 'Lab metric added to the table.',
    type: 'success',
  });
};

const destroyMetric = (metricId) => {
  labMetrics.value = labMetrics.value.filter((item) => item.id !== metricId);

  ElNotification({
    title: 'Success',
    message: 'Lab metric removed from the table.',
    type: 'success',
  });
};

const formatDateTime = (value) => {
  if (!value) return 'N/A';
  const date = new Date(value);
  if (Number.isNaN(date.getTime())) return value;
  return date.toLocaleString();
};
</script>

<template>
  <div class="batch-lab-metrics">
    <div class="batch-lab-metrics-head">
      <div class="batch-lab-metrics-copy">
        <h6 class="title mb-0"><em class="icon ni ni-bar-chart-fill mr-1"></em>Batch Lab Metrics</h6>
        <p class="sub-text mb-0">Review available quality readings and add extra lab observations.</p>
      </div>

      <el-button v-if="canEdit" type="primary" :icon="Plus" @click="openModal">
        Add Lab Metric
      </el-button>
    </div>

    <div v-if="sortedLabMetrics.length" class="table-responsive metrics-table-wrap mt-3">
      <table class="table table-sm table-middle mb-0 metrics-table">
        <thead>
          <tr>
            <th><span class="head-label"><em class="icon ni ni-activity"></em>Metric</span></th>
            <th><span class="head-label"><em class="icon ni ni-tag"></em>Value</span></th>
            <th><span class="head-label"><em class="icon ni ni-notes"></em>Notes</span></th>
            <th><span class="head-label"><em class="icon ni ni-calendar"></em>Date Added</span></th>
            <th v-if="canEdit" class="text-right"><span class="head-label"><em class="icon ni ni-trash"></em></span></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in sortedLabMetrics" :key="item.localKey">
            <td class="text-capitalize">
              <span class="metric-cell">
                <em class="icon ni ni-activity"></em>
                {{ item.metric ?? 'N/A' }}
              </span>
            </td>
            <td>{{ item.value ?? 'N/A' }}</td>
            <td>{{ item.notes ?? 'N/A' }}</td>
            <td>{{ formatDateTime(item.created_at) }}</td>
            <td v-if="canEdit" class="text-right">
              <el-button
                class="metric-delete-btn"
                type="danger"
                text
                :icon="Delete"
                @click="destroyMetric(item.id)"
              />
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-else class="empty-metrics mt-3">
      No batch lab metrics recorded yet.
    </div>

    <el-dialog
      v-model="addMetricModalVisible"
      width="520"
      align-center
      destroy-on-close
    >
      <template #header>
        <div class="batch-lab-dialog-head">
          <h6 class="title mb-1">Add Batch Lab Metric</h6>
          <p class="sub-text mb-0">Capture one quality reading or lab observation for this batch.</p>
        </div>
      </template>

      <form @submit.prevent="submit">
        <div class="mb-3">
          <label class="form-label">Metric</label>
          <el-input
            v-model="form.metric"
            placeholder="e.g. moisture"
          />
        </div>

        <div class="batch-lab-form-grid">
          <div>
            <label class="form-label">Value</label>
            <el-input
              v-model="form.value"
              placeholder="e.g. 11.8"
            />
          </div>

          <div>
            <label class="form-label">Unit</label>
            <el-input
              v-model="form.unit"
              placeholder="e.g. %"
            />
          </div>
        </div>

        <div class="mt-3">
          <label class="form-label">Notes</label>
          <el-input
            v-model="form.notes"
            type="textarea"
            :rows="3"
            placeholder="Optional notes about the reading"
          />
        </div>

        <div class="batch-lab-dialog-actions mt-4">
          <el-button @click="closeModal">Cancel</el-button>
          <el-button type="primary" native-type="submit">
            Save
          </el-button>
        </div>
      </form>
    </el-dialog>
  </div>
</template>

<style scoped>
.batch-lab-metrics-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  flex-wrap: wrap;
}

.batch-lab-metrics-copy {
  display: flex;
  flex-direction: column;
  gap: 2px;
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

.metric-delete-btn {
  padding: 4px;
}

.empty-metrics {
  border: 1px dashed #d9e2f0;
  border-radius: 10px;
  padding: 12px;
  color: #64748b;
  background: #f8fafc;
}

.batch-lab-form-grid {
  display: grid;
  grid-template-columns: minmax(0, 1fr) minmax(0, 160px);
  gap: 12px;
}

.batch-lab-dialog-actions {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
}

@media (max-width: 767px) {
  .batch-lab-metrics-head :deep(.el-button) {
    width: 100%;
  }

  .batch-lab-form-grid {
    grid-template-columns: 1fr;
  }
}
</style>
