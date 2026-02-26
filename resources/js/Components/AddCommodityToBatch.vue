<script setup>
import { computed, ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import { Plus, Search } from '@element-plus/icons-vue';

const props = defineProps({
  batchId: {
    type: [Number, String],
    required: true,
  },
  commodities: {
    type: Array,
    default: () => [],
  },
});

const addCommodityModalVisible = ref(false);
const form = useForm({
  commodity_id: '',
});

const linkedCommodities = computed(() => (Array.isArray(props.commodities) ? props.commodities : []));

const openModal = () => {
  form.clearErrors();
  addCommodityModalVisible.value = true;
};

const closeModal = () => {
  addCommodityModalVisible.value = false;
  form.reset();
  form.clearErrors();
};

const submit = () => {
  if (!props.batchId) return;

  form.post(route('commodity.batch.commodities.attach', { id: props.batchId }), {
    preserveScroll: true,
    onSuccess: () => {
      closeModal();
    },
  });
};
</script>

<template>
  <div class="card-inner">
    <div class="prompt-box">
      <h6 class="title mb-2"><em class="icon ni ni-alert-circle mr-1"></em>Next Step</h6>
      <p class="mb-3 text-muted">
        Search commodity by ID and attach it to this batch for traceability.
      </p>
      <el-button type="primary" :icon="Plus" @click="openModal">
        Add Commodity To Batch
      </el-button>
    </div>

    <div v-if="linkedCommodities.length" class="table-responsive linked-table-wrap mt-3">
      <table class="table table-sm table-middle mb-0 linked-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Commodity Name</th>
            <th>Type</th>
            <th>Grade</th>
            <th>Weight</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in linkedCommodities" :key="item.id">
            <td>#{{ item.id }}</td>
            <td class="text-capitalize">{{ item.commodity_name ?? 'N/A' }}</td>
            <td class="text-capitalize">{{ item.commodity_type ?? 'N/A' }}</td>
            <td class="text-capitalize">{{ item.grade ?? 'N/A' }}</td>
            <td>{{ item.weight ?? 'N/A' }} kg</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-else class="empty-linked mt-3">
      No commodities linked to this batch yet.
    </div>

    <el-dialog
      v-model="addCommodityModalVisible"
      class="theme-action-dialog"
      width="520"
      align-center
      destroy-on-close
    >
      <template #header>
        <div class="theme-dialog-header">
          <div class="theme-dialog-icon">
            <em class="icon ni ni-search"></em>
          </div>
          <div>
            <h6 class="theme-dialog-title">Find Commodity By ID</h6>
            <p class="theme-dialog-subtext mb-0">Enter a commodity ID to attach it to this batch.</p>
          </div>
        </div>
      </template>

      <form class="theme-no-highlight" @submit.prevent="submit">
        <label class="form-label">Commodity ID</label>
        <el-input
          v-model="form.commodity_id"
          :prefix-icon="Search"
          clearable
          size="large"
          placeholder="e.g. 1024"
        />
        <InputError :message="form.errors.commodity_id" class="mt-2" />

        <div class="sub-text mt-2">
          Only commodities from your cooperative can be attached.
        </div>

        <div class="dialog-actions mt-4">
         
          <el-button type="primary" native-type="submit" :loading="form.processing">
            Attach Commodity
          </el-button>
        </div>
      </form>
    </el-dialog>
  </div>
</template>

<style scoped>
.prompt-box {
  border: 1px dashed #d9e2f0;
  border-radius: 10px;
  background: #f8fafc;
  padding: 14px;
}

.linked-table-wrap {
  border: 1px solid #e5e9f2;
  border-radius: 10px;
  overflow: hidden;
  background: #fff;
}

.linked-table th {
  background: #f8fafc;
  color: #526484;
  font-weight: 600;
  white-space: nowrap;
}

.linked-table td {
  color: #364a63;
  white-space: nowrap;
}

.empty-linked {
  border: 1px dashed #d9e2f0;
  border-radius: 10px;
  padding: 12px;
  color: #64748b;
  background: #f8fafc;
}

.dialog-actions {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
}
</style>
