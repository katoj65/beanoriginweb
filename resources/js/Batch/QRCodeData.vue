<script setup>
const props = defineProps({
  qrCodeData: {
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

const formatPreview = (value, length = 56) => {
  if (!value) return 'N/A';
  const text = String(value).replace(/\s+/g, ' ').trim();
  if (text.length <= length) return text;
  return `${text.slice(0, length)}...`;
};

const formatMetadata = (value) => {
  if (!value) return 'N/A';

  try {
    const serialized = typeof value === 'string' ? value : JSON.stringify(value);
    return formatPreview(serialized, 72);
  } catch {
    return 'N/A';
  }
};
</script>

<template>
  <div class="qr-code-data">
    <div class="qr-code-head">
      <div class="qr-code-copy">
        <h6 class="title mb-0"><em class="icon ni ni-qr mr-1"></em>QR Code Data</h6>
        <p class="sub-text mb-0">Stored QR payloads and linked scan destinations for this batch.</p>
      </div>
    </div>

    <div v-if="qrCodeData.length" class="table-responsive qr-table-wrap mt-3">
      <table class="table table-sm table-middle mb-0 qr-table">
        <thead>
          <tr>
            <th><span class="head-label"><em class="icon ni ni-grid-alt"></em>QR Payload</span></th>
            <th><span class="head-label"><em class="icon ni ni-link"></em>Scan URL</span></th>
            <th><span class="head-label"><em class="icon ni ni-info"></em>Metadata</span></th>
            <th><span class="head-label"><em class="icon ni ni-flag"></em>Status</span></th>
            <th><span class="head-label"><em class="icon ni ni-calendar"></em>Date Added</span></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in qrCodeData" :key="item.id">
            <td>{{ formatPreview(item.qr_code) }}</td>
            <td>{{ formatPreview(item.scan_url, 48) }}</td>
            <td>{{ formatMetadata(item.metadata) }}</td>
            <td class="text-capitalize">{{ item.status ?? 'N/A' }}</td>
            <td>{{ formatDateTime(item.created_at) }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-else class="empty-state mt-3">
      No QR code data recorded for this batch yet.
    </div>
  </div>
</template>

<style scoped>
.qr-code-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  flex-wrap: wrap;
}

.qr-code-copy {
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.qr-table-wrap {
  border: 1px solid #e5e9f2;
  border-radius: 10px;
  overflow: hidden;
  background: #fff;
}

.qr-table th {
  background: #f8fafc;
  color: #526484;
  font-weight: 600;
  white-space: nowrap;
}

.qr-table td {
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

.empty-state {
  border: 1px dashed #d9e2f0;
  border-radius: 10px;
  padding: 12px;
  color: #64748b;
  background: #f8fafc;
}
</style>
