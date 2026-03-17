<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const token = computed(() => page.props.batch_tokens.data ?? []);

const formatDateTime = (value) => {
  if (!value) return 'N/A';
  const date = new Date(value);
  if (Number.isNaN(date.getTime())) return value;
  return date.toLocaleString();
};

const metadataItems = (value) => {
  if (value === null || value === undefined || value === '') return [];

  let parsed = value;
  if (typeof value === 'string') {
    try {
      parsed = JSON.parse(value);
    } catch {
      parsed = { value };
    }
  }

  if (Array.isArray(parsed)) {
    return parsed.map((item, index) => ({
      key: `item_${index + 1}`,
      value: String(item),
    }));
  }

  if (parsed && typeof parsed === 'object') {
    const hiddenKeys = new Set(['event_type', 'event_time', 'event_time_at', 'timestamp', 'created_at', 'updated_at']);
    return Object.entries(parsed)
      .filter(([key]) => !hiddenKeys.has(String(key).toLowerCase()))
      .map(([key, item]) => ({
        key,
        value: typeof item === 'object' && item !== null ? JSON.stringify(item) : String(item),
      }));
  }

  return [{ key: 'value', value: String(parsed) }];
};
</script>

<template>
  <div>
 
    <div v-if="token.length" class="table-responsive token-table-wrap">
      <table class="table table-sm table-middle mb-0 token-table">
        <thead>
          <tr>
            <th><span class="head-label"><em class="icon ni ni-hash"></em>Token #</span></th>
            <th><span class="head-label"><em class="icon ni ni-shield-check"></em>Status</span></th>
            <th><span class="head-label"><em class="icon ni ni-file-text"></em>Metadata</span></th>
            <th><span class="head-label"><em class="icon ni ni-calendar"></em>Created</span></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in token" :key="item.id">
            <td>{{ item.token_index ?? 'N/A' }}</td>
            <td class="text-capitalize">{{ item.status ?? 'N/A' }}</td>
            <td>
              <div class="token-meta-list">
                <span
                  v-for="meta in metadataItems(item.metadata)"
                  :key="`${item.id}-${meta.key}-${meta.value}`"
                  class="badge token-meta-badge"
                >
                  {{ meta.key }}: {{ meta.value }}
                </span>
              </div>
            </td>
            <td>{{ formatDateTime(item.created_at) }}</td>
          </tr>
        </tbody>
      </table>
    </div>
    <div v-else class="empty-token">
      No token records found for this batch.
    </div>
  </div>
</template>

<style scoped>
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

.head-label {
  display: inline-flex;
  align-items: center;
  gap: 6px;
}

.head-label .icon {
  color: #8094ae;
  font-size: 13px;
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

.empty-token {
  border: 1px dashed #d9e2f0;
  border-radius: 10px;
  padding: 12px;
  color: #64748b;
  background: #f8fafc;
}
</style>
