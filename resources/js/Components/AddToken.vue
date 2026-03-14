<script setup>
import { computed, ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import { ElNotification } from 'element-plus';
import { Plus } from '@element-plus/icons-vue';

const page = usePage();
const batch = computed(() => page.props.batch?.data ?? page.props.batch ?? {});
const batchTokens = computed(() => page.props.batch_tokens ?? []);
const tokenModalVisible = ref(false);
const tokenizing = ref(false);

const isTokenizeDisabled = computed(() => {
  const status = String(batch.value?.status ?? '').toLowerCase();
  return status === 'listed';
});

const openTokenModal = () => {
  if (isTokenizeDisabled.value) return;
  tokenModalVisible.value = true;
};

const closeTokenModal = () => {
  if (tokenizing.value) return;
  tokenModalVisible.value = false;
};

const formatDateTime = (value) => {
  if (!value) return 'N/A';
  const date = new Date(value);
  if (Number.isNaN(date.getTime())) return value;
  return date.toLocaleString();
};

const parseMetadata = (value) => {
  if (value === null || value === undefined || value === '') return {};
  if (typeof value === 'string') {
    try {
      return JSON.parse(value);
    } catch {
      return value;
    }
  }
  return value;
};

const formatMetadataValue = (value) => {
  if (value === null || value === undefined || value === '') return 'N/A';
  if (typeof value === 'object') return JSON.stringify(value);
  return String(value);
};

const metadataItems = (value) => {
  const parsed = parseMetadata(value);

  if (Array.isArray(parsed)) {
    return parsed.map((item, index) => ({
      key: `item_${index + 1}`,
      value: formatMetadataValue(item),
    }));
  }

  if (parsed && typeof parsed === 'object') {
    return Object.entries(parsed).map(([key, itemValue]) => ({
      key,
      value: formatMetadataValue(itemValue),
    }));
  }

  return [
    {
      key: 'value',
      value: formatMetadataValue(parsed),
    },
  ];
};

const submitToken = () => {
  const batchId = batch.value?.id ?? null;
  if (!batchId || tokenizing.value) return;

  router.post(route('batch.token.store', { id: batchId }), {}, {
    preserveScroll: true,
    onStart: () => {
      tokenizing.value = true;
    },
    onSuccess: (response) => {
      const message = response?.props?.flash?.success || 'Batch tokenized successfully.';
      ElNotification({
        title: 'Successful',
        message,
        type: 'success',
      });
      tokenModalVisible.value = false;
    },
    onFinish: () => {
      tokenizing.value = false;
    },
  });
};
</script>

<template>
  <div>
    <div class="token-action-head">
      <div class="token-action-copy">
        <h6 class="title mb-0"><em class="icon ni ni-coins mr-1"></em>Batch Token</h6>
        <p class="sub-text mb-0">Create token for this batch after final verification.</p>
      </div>
      <el-button type="primary" :icon="Plus" :disabled="isTokenizeDisabled" @click="openTokenModal">
        Create Token
      </el-button>
    </div>

    <div v-if="batchTokens.length" class="table-responsive token-table-wrap mt-3">
      <table class="table table-sm table-middle mb-0 token-table">
        <thead>
          <tr>
            <th><span class="head-label"><em class="icon ni ni-hash"></em>Token #</span></th>
            <th><span class="head-label"><em class="icon ni ni-activity"></em>Event</span></th>
            <th><span class="head-label"><em class="icon ni ni-file-text"></em>Metadata</span></th>
            <th><span class="head-label"><em class="icon ni ni-shield-check"></em>Status</span></th>
            <th><span class="head-label"><em class="icon ni ni-calendar"></em>Created</span></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="token in batchTokens" :key="token.id">
            <td>{{ token.token_index ?? 'N/A' }}</td>
            <td class="text-capitalize">{{ token.event_type ?? 'N/A' }}</td>
            <td>
              <div class="token-meta-list">
                <span
                  v-for="item in metadataItems(token.metadata)"
                  :key="`${token.id}-${item.key}-${item.value}`"
                  class="badge token-meta-badge"
                >
                  {{ item.key }}: {{ item.value }}
                </span>
              </div>
            </td>
            <td class="text-capitalize">{{ token.status ?? 'N/A' }}</td>
            <td>{{ formatDateTime(token.created_at) }}</td>
          </tr>
        </tbody>
      </table>
    </div>
    <div v-else class="empty-token mt-3">
      No token records created yet.
    </div>

    <el-dialog
      v-model="tokenModalVisible"
      width="520"
      align-center
      destroy-on-close
      :close-on-click-modal="!tokenizing"
      :close-on-press-escape="!tokenizing"
      :show-close="!tokenizing"
      @close="closeTokenModal"
    >
      <template #header>
        <div class="token-dialog-head">
          <h6 class="title mb-1">Create Batch Token</h6>
          <p class="sub-text mb-0">Confirm token creation for this batch before listing it on the market.</p>
        </div>
      </template>

      <div class="token-dialog-note mb-3">
        <em class="icon ni ni-alert-circle"></em>
        <span>Once submitted, this batch status will be updated to <strong>listed</strong>.</span>
      </div>

      <div class="token-batch-details">
        <div class="token-detail-row">
          <span class="sub-text">Batch ID</span>
          <strong>{{ batch.id ?? 'N/A' }}</strong>
        </div>
        <div class="token-detail-row">
          <span class="sub-text">Batch Code</span>
          <strong>{{ batch.batch_code ?? 'N/A' }}</strong>
        </div>
        <div class="token-detail-row">
          <span class="sub-text">Commodity</span>
          <strong class="text-capitalize">{{ batch.commodity_name ?? 'N/A' }}</strong>
        </div>
        <div class="token-detail-row">
          <span class="sub-text">Weight</span>
          <strong>{{ batch.weight ?? 'N/A' }} kg</strong>
        </div>
        <div class="token-detail-row">
          <span class="sub-text">Status</span>
          <strong class="text-capitalize">{{ batch.status ?? 'created' }}</strong>
        </div>
      </div>

      <p class="sub-text token-confirm-copy mt-3 mb-0">
        Proceed to create a token record for this batch?
      </p>

      <template #footer>
        <div class="token-dialog-actions">
          <el-button @click="closeTokenModal" :disabled="tokenizing">Cancel</el-button>
          <el-button type="primary" :loading="tokenizing" @click="submitToken">
            Create Token
          </el-button>
        </div>
      </template>
    </el-dialog>
  </div>
</template>

<style scoped>
.token-action-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  flex-wrap: wrap;
}

.token-action-copy {
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.token-table-wrap {
  border: 1px solid #e5e9f2;
  border-radius: 10px;
  overflow: hidden;
  background: #fff;
}

.token-table th {
  background: #f8fafc;
  color: #526484;
  font-weight: 600;
  white-space: nowrap;
}

.token-table td {
  color: #364a63;
  white-space: nowrap;
}

.token-meta-list {
  display: flex;
  flex-wrap: wrap;
  gap: 6px;
  max-width: 360px;
}

.token-meta-badge {
  background: #eff6ff;
  color: #1e3a8a;
  border: 1px solid #bfdbfe;
  font-size: 11px;
  font-weight: 500;
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

.empty-token {
  border: 1px dashed #d9e2f0;
  border-radius: 10px;
  padding: 12px;
  color: #64748b;
  background: #f8fafc;
}

.token-batch-details {
  border: 1px solid #e5e9f2;
  border-radius: 10px;
  background: #f8fafc;
  padding: 12px;
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.token-dialog-note {
  border: 1px solid #fde68a;
  border-radius: 10px;
  background: #fffbeb;
  color: #92400e;
  padding: 10px 12px;
  display: flex;
  align-items: flex-start;
  gap: 8px;
  font-size: 13px;
  line-height: 1.35;
}

.token-confirm-copy {
  color: #475569;
}

.token-detail-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 10px;
}

.token-dialog-actions {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
}

@media (max-width: 767px) {
  .token-action-head :deep(.el-button) {
    width: 100%;
  }
}
</style>
