<script setup>
import { ref,computed } from 'vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { ElNotification } from 'element-plus';
import { Delete, Plus } from '@element-plus/icons-vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
  batchId: {
    type: [Number, String],
    default: null,
  },
});

const page = usePage();
const batchProcessingMetadata = computed(() =>page.props.batch_processing_metadata ?? []);
const batchProcessingData = computed(() => page.props.batch_processing_data ?? []);
const batchId = computed(() => props.batchId ?? page.props.batch?.id ?? page.props.batch?.data?.id ?? null);



const addProcessModalVisible = ref(false);
const form = useForm({
  activity: '',
  value: '',
});

const openModal = () => {
  form.clearErrors();
  addProcessModalVisible.value = true;
};

const closeModal = () => {
  addProcessModalVisible.value = false;
  form.reset();
  form.clearErrors();
};

const submit = () => {
  if (!batchId.value) return;

  form.post(route('batch.processing.store', { id: batchId.value }), {
    preserveScroll: true,
    onSuccess: () => {
      ElNotification({
        title: 'Success',
        message: 'Batch processing data saved successfully.',
        type: 'success',
      });
      closeModal();
    },
  });
};

const destroyProcess = (processingId) => {
  if (!batchId.value || !processingId) return;

  router.delete(route('batch.processing.destroy', { id: batchId.value, processingId }), {
    preserveScroll: true,
    onSuccess: () => {
      ElNotification({
        title: 'Success',
        message: 'Batch processing data deleted successfully.',
        type: 'success',
      });
    },
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
  <div class="batch-process-action">
    <div class="batch-process-head">
      <div class="batch-process-copy">
        <h6 class="title mb-0"><em class="icon ni ni-setting-alt mr-1"></em>Batch Processing Data</h6>
        <p class="sub-text mb-0">Track and manage processing activities for this batch.</p>
      </div>
      <el-button type="primary" :icon="Plus" @click="openModal">
        Add Batch Processing
      </el-button>
    </div>


    <div v-if="batchProcessingData.length" class="table-responsive process-table-wrap mt-3">
      <table class="table table-sm table-middle mb-0 process-table">
        <thead>
          <tr>
            <th><span class="head-label"><em class="icon ni ni-activity"></em>Activity</span></th>
            <th><span class="head-label"><em class="icon ni ni-tag"></em>Value</span></th>
            <th><span class="head-label"><em class="icon ni ni-calendar"></em>Date Added</span></th>
            <th class="text-right"><span class="head-label"><em class="icon ni ni-trash"></em></span></th>
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
            <td class="text-right">
              <el-button
                class="process-delete-btn"
                type="danger"
                text
                :icon="Delete"
                @click="destroyProcess(item.id)"
              />
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-else class="empty-process mt-3">
      No batch processing data recorded yet.
    </div>

    <el-dialog
      v-model="addProcessModalVisible"
      width="520"
      align-center
      destroy-on-close
    >
      <template #header>
        <div class="batch-process-dialog-head">
          <h6 class="title mb-1">Add Batch Processing</h6>
          <p class="sub-text mb-0">Record one processing activity for this batch.</p>
        </div>
      </template>

      <form @submit.prevent="submit">
        <div class="mb-3">
          <label class="form-label">Activity</label>
          <el-select
            v-if="batchProcessingMetadata.length"
            v-model="form.activity"
            clearable
            placeholder="Select activity"
            class="w-100"
          >
            <el-option
              v-for="item in batchProcessingMetadata"
              :key="item"
              :label="item"
              :value="item"
            />
          </el-select>
          <el-input
            v-else
            v-model="form.activity"
            placeholder="e.g. drying"
          />
          <InputError :message="form.errors.activity" class="mt-1" />
        </div>

        <div>
          <label class="form-label">Value</label>
          <el-input
            v-model="form.value"
            placeholder="e.g. 12 hours"
          />
          <InputError :message="form.errors.value" class="mt-1" />
        </div>

        <div class="batch-process-dialog-actions mt-4">
          <el-button @click="closeModal">Cancel</el-button>
          <el-button type="primary" native-type="submit" :loading="form.processing">
            Save
          </el-button>
        </div>
      </form>
    </el-dialog>





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

.process-delete-btn {
  padding: 4px;
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

.batch-process-dialog-actions {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
}

@media (max-width: 767px) {
  .batch-process-head :deep(.el-button) {
    width: 100%;
  }
}
</style>
