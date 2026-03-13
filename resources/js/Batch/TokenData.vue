<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const blocks = computed(() => page.props.batch_blocks ?? []);

const formatDateTime = (value) => {
if (!value) return 'N/A';
const date = new Date(value);
if (Number.isNaN(date.getTime())) return value;
return date.toLocaleString();
};

const formatPrice = (value) => {
if (value === null || value === undefined || value === '') return 'N/A';
const amount = Number(value);
if (Number.isNaN(amount)) return value;
return amount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
};

const formatBadgeValue = (value) => {
if (value === null || value === undefined || value === '') return 'N/A';
if (Array.isArray(value)) return value.join(', ');
if (typeof value === 'object') return JSON.stringify(value);
return String(value);
};

const eventDataBadges = (value) => {
if (!value) return [];

let payload = value;
if (typeof payload === 'string') {
try {
payload = JSON.parse(payload);
} catch (error) {
return [payload];
}
}

if (Array.isArray(payload)) {
return payload.map((item) => formatBadgeValue(item)).filter((item) => item !== '');
}

if (typeof payload === 'object') {
return Object.entries(payload).map(([key, item]) => `${key}: ${formatBadgeValue(item)}`);
}

return [formatBadgeValue(payload)];
};

const metadataCount = (value) => eventDataBadges(value).length;
const metadataPreview = (value, limit = 4) => eventDataBadges(value).slice(0, limit);
const metadataOverflow = (value, limit = 4) => Math.max(metadataCount(value) - limit, 0);
</script>

<template>
<div class="token-data-wrap">
<div class="token-table-head">
<div>
<h6 class="title mb-1"><em class="icon ni ni-tag mr-1"></em>Token Metadata</h6>
<p class="sub-text mb-0">Track blockchain events recorded for this batch.</p>
</div>
<span class="token-count">{{ blocks.length }} block<span v-if="blocks.length !== 1">s</span></span>
</div>

<div v-if="blocks.length" class="table-responsive token-table-wrap">
<table class="table table-sm table-middle mb-0 token-table">
<thead>
<tr>
<th><span class="head-label"><em class="icon ni ni-hash"></em>Block #</span></th>
<th><span class="head-label"><em class="icon ni ni-activity"></em>Event</span></th>
<th><span class="head-label"><em class="icon ni ni-coins"></em>Price</span></th>
<th><span class="head-label"><em class="icon ni ni-code"></em>Metadata</span></th>
<th><span class="head-label"><em class="icon ni ni-calendar"></em>Date</span></th>
</tr>
</thead>
<tbody>
<tr v-for="item in blocks" :key="item.id">
<td>
<span class="block-index-cell">
<em class="icon ni ni-hash"></em>
{{ item.block_index ?? 'N/A' }}
</span>
</td>
<td class="text-capitalize">
<span class="event-cell">
<em class="icon ni ni-activity"></em>
{{ item.event_type ?? 'N/A' }}
</span>
</td>
<td>
<span class="price-cell">
<em class="icon ni ni-coins"></em>
{{ formatPrice(item.price) }}
</span>
</td>
<td>
<div class="json-badge-list">
<span
v-for="(content, idx) in metadataPreview(item.event_data)"
:key="`${item.id}-json-${idx}`"
class="json-badge"
>
{{ content }}
</span>
<span v-if="metadataOverflow(item.event_data)" class="json-badge json-badge-more">
+{{ metadataOverflow(item.event_data) }}
</span>
<span v-if="!metadataCount(item.event_data)" class="json-badge json-badge-empty">
N/A
</span>
</div>
</td>
<td>
<span class="date-cell">
<em class="icon ni ni-calendar"></em>
{{ formatDateTime(item.created_at) }}
</span>
</td>
</tr>
</tbody>
</table>
</div>
<div v-else class="empty-token-table">
No block records found for this batch.
</div>
</div>
</template>

<style scoped>
.token-data-wrap {
display: flex;
flex-direction: column;
gap: 12px;
}

.token-table-head {
display: flex;
align-items: center;
justify-content: space-between;
gap: 12px;
flex-wrap: wrap;
}

.token-count {
display: inline-flex;
align-items: center;
padding: 4px 10px;
border-radius: 999px;
background: #eef2ff;
color: #334155;
font-size: 12px;
font-weight: 600;
}

.token-table-wrap {
border: 1px solid #e8edf5;
border-radius: 10px;
overflow: hidden;
background: #fff;
}

.token-table th {
background: #fbfcfe;
color: #64748b;
font-weight: 700;
font-size: 11.5px;
text-transform: uppercase;
letter-spacing: 0.04em;
white-space: nowrap;
padding: 9px 12px;
border-bottom: 1px solid #edf2f8;
}

.token-table td {
color: #364a63;
font-size: 13px;
padding: 11px 12px;
vertical-align: middle;
border-top: 1px solid #f1f5f9;
}

.token-table tbody tr:first-child td {
border-top: none;
}

.token-table tbody tr:hover {
background: #fafcff;
}

.head-label {
display: inline-flex;
align-items: center;
gap: 6px;
}

.head-label .icon {
font-size: 13px;
color: #8094ae;
}

.block-index-cell {
display: inline-flex;
align-items: center;
gap: 6px;
font-weight: 600;
}

.block-index-cell .icon {
font-size: 13px;
color: #8094ae;
}

.event-cell,
.price-cell,
.date-cell {
display: inline-flex;
align-items: center;
gap: 6px;
}

.event-cell .icon,
.price-cell .icon,
.date-cell .icon {
font-size: 13px;
color: #8094ae;
}

.json-badge-list {
display: flex;
flex-wrap: wrap;
gap: 6px;
}

.json-badge {
display: inline-flex;
align-items: center;
max-width: 100%;
padding: 4px 8px;
border-radius: 999px;
background: #f8fafc;
border: 1px solid #e8edf5;
color: #334155;
font-size: 11.5px;
line-height: 1.2;
word-break: break-word;
}

.json-badge-more {
background: #eef2ff;
border-color: #dbeafe;
color: #1e3a8a;
}

.json-badge-empty {
background: #f8fafc;
color: #64748b;
}

.empty-token-table {
border: 1px dashed #d9e2f0;
border-radius: 10px;
padding: 12px;
color: #64748b;
background: #f8fafc;
font-size: 13px;
}
</style>
