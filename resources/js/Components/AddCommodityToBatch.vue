<script setup>
import { computed, ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import { ElNotification } from 'element-plus';
import { Delete, Plus, ScaleToOriginal, Search } from '@element-plus/icons-vue';

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

const formatHarvestDate = (value) => {
  if (!value) return 'N/A';
  const date = new Date(value);
  if (Number.isNaN(date.getTime())) return value;
  return date.toLocaleDateString();
};

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
      ElNotification({
        title: 'Success',
        message: `Commodity #${form.commodity_id} was attached to batch #${props.batchId}.`,
        type: 'success',
      });
      closeModal();
    },
    onError: () => {
      ElNotification({
        title: 'Error',
        message: form.errors.commodity_id || 'Unable to attach commodity to this batch.',
        type: 'error',
      });
    },
  });
};

const destroyCommodity = (commodityId) => {
  if (!props.batchId || !commodityId) return;

  router.delete(route('batch.commodities.destroy', { id: props.batchId, commodityId }), {
    preserveScroll: true,
    onSuccess: () => {
      ElNotification({
        title: 'Success',
        message: 'Commodity removed from batch successfully.',
        type: 'success',
      });
    },
  });
};
</script>

<template>
  <div class="card-inner m-0 p-0">
    <div class="prompt-plain">
      <div class="prompt-head">
        <div class="prompt-copy">
          <h6 class="title mb-0">Harvests Data</h6>
          <p class="prompt-subtitle mb-0">Attach verified commodities to this batch.</p>
        </div>
        <el-button type="primary" :icon="Plus" @click="openModal">
          Add Commodity To Batch
        </el-button>
      </div>
    </div>

    <div v-if="linkedCommodities.length" class="table-responsive linked-table-wrap mt-3">
      <table class="table table-sm table-middle mb-0 linked-table">
        <thead>
          <tr>
            <th><span class="head-label"><em class="icon ni ni-hash"></em>ID</span></th>
            <th><span class="head-label"><em class="icon ni ni-package"></em>Commodity Name</span></th>
            <th><span class="head-label"><em class="icon ni ni-layers"></em>Type</span></th>
            <th><span class="head-label"><em class="icon ni ni-medal"></em>Grade</span></th>
            <th>
              <span class="head-label">
                <el-icon><ScaleToOriginal /></el-icon>
                Weight
              </span>
            </th>
            <th><span class="head-label"><em class="icon ni ni-growth"></em>Ripe %</span></th>
            <th><span class="head-label"><em class="icon ni ni-drop"></em>Density %</span></th>
            <th><span class="head-label"><em class="icon ni ni-calendar"></em>Date Harvested</span></th>
            <th class="text-right"><span class="head-label"><em class="icon ni ni-trash"></em></span></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in linkedCommodities" :key="item.id">
            <td>#{{ item.id }}</td>
            <td class="text-capitalize">{{ item.commodity_name ?? 'N/A' }}</td>
            <td class="text-capitalize">{{ item.commodity_type ?? 'N/A' }}</td>
            <td class="text-capitalize">{{ item.grade ?? 'N/A' }}</td>
            <td>{{ item.weight ?? 'N/A' }} kg</td>
            <td>{{ item.ripe_percentage === null || item.ripe_percentage === undefined ? 'N/A' : `${item.ripe_percentage}%` }}</td>
            <td>{{ item.density_percentage === null || item.density_percentage === undefined ? 'N/A' : `${item.density_percentage}%` }}</td>
            <td>{{ formatHarvestDate(item.harvest_date) }}</td>
            <td class="text-right">
              <el-button
                class="commodity-delete-btn"
                type="danger"
                text
                :icon="Delete"
                @click="destroyCommodity(item.id)"
              />
            </td>
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
.prompt-plain {
  padding: 0;
}

.prompt-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  flex-wrap: wrap;
  padding-bottom: 6px;
}

.prompt-copy {
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.prompt-subtitle {
  color: #8094ae;
  font-size: 12px;
  line-height: 1.35;
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

.head-label {
  display: inline-flex;
  align-items: center;
  gap: 6px;
}

.head-label .icon {
  color: #8094ae;
  font-size: 13px;
}

.commodity-delete-btn {
  padding: 4px;
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

@media (max-width: 767px) {
  .prompt-head {
    align-items: stretch;
  }

  .prompt-head :deep(.el-button) {
    width: 100%;
  }
}
</style>
