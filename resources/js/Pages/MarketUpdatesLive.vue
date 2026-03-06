<script setup>
import HomeLayout from '@/Layouts/HomeLayout.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const page = usePage();
const marketBoard = computed(() => {
    const source = page.props.marketBoard;
    if (Array.isArray(source)) return source;
    return source?.data ?? [];
});
const batchListed = computed(() => page.props.batchListed ?? { buyers: 0, batches: 0 });

const search = ref('');
const activeSignal = ref('All');
const sortBy = ref('notional');
const sortDir = ref('desc');
const topN = ref('25');

const signalFilters = ['All', 'Stable', 'Active', 'Volatile'];

const currencyFormatter = new Intl.NumberFormat('en-UG', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
});
const integerFormatter = new Intl.NumberFormat('en-UG', {
    maximumFractionDigits: 0,
});

const formatCurrency = (value) => `Shs. ${currencyFormatter.format(Number(value ?? 0))}`;
const formatInteger = (value) => integerFormatter.format(Number(value ?? 0));
const clamp = (value, min, max) => Math.min(Math.max(value, min), max);

const boardRows = computed(() =>
    marketBoard.value
        .map((row) => {
            const min = Number(row.min_price ?? 0);
            const max = Number(row.max_price ?? 0);
            const quantity = Number(row.quantity ?? 0);
            const last = min > 0 && max > 0 ? (min + max) / 2 : max || min || 0;
            const spread = Math.max(max - min, 0);
            const spreadPct = last > 0 ? (spread / last) * 100 : 0;
            const notional = quantity * last;
            const signal = spreadPct >= 8 ? 'Volatile' : spreadPct >= 3 ? 'Active' : 'Stable';
            const product = [row.commodity, row.type, row.grade].filter(Boolean).join(' · ');

            return {
                ...row,
                min,
                max,
                last,
                spread,
                spreadPct,
                quantity,
                notional,
                signal,
                product,
            };
        }),
);

const filteredRows = computed(() => {
    let rows = [...boardRows.value];

    if (search.value.trim()) {
        const query = search.value.toLowerCase();
        rows = rows.filter((row) => row.product.toLowerCase().includes(query));
    }

    if (activeSignal.value !== 'All') {
        rows = rows.filter((row) => row.signal === activeSignal.value);
    }

    const getSortValue = (row) => {
        if (sortBy.value === 'instrument') return row.product.toLowerCase();
        if (sortBy.value === 'last') return row.last;
        if (sortBy.value === 'low') return row.min;
        if (sortBy.value === 'high') return row.max;
        if (sortBy.value === 'qty') return row.quantity;
        if (sortBy.value === 'spread') return row.spreadPct;
        if (sortBy.value === 'signal') return row.signal;
        return row.notional;
    };

    rows.sort((a, b) => {
        const av = getSortValue(a);
        const bv = getSortValue(b);
        const result = typeof av === 'string' && typeof bv === 'string'
            ? av.localeCompare(bv)
            : Number(av) - Number(bv);

        return sortDir.value === 'asc' ? result : -result;
    });

    return rows;
});

const visibleRows = computed(() => {
    if (topN.value === 'all') return filteredRows.value;
    return filteredRows.value.slice(0, Number(topN.value));
});

const setSort = (column) => {
    if (sortBy.value === column) {
        sortDir.value = sortDir.value === 'asc' ? 'desc' : 'asc';
        return;
    }

    sortBy.value = column;
    sortDir.value = column === 'instrument' || column === 'signal' ? 'asc' : 'desc';
};

const sortMark = (column) => {
    if (sortBy.value !== column) return '↕';
    return sortDir.value === 'asc' ? '↑' : '↓';
};

const totalQuantity = computed(() =>
    boardRows.value.reduce((sum, row) => sum + row.quantity, 0),
);

const totalNotional = computed(() =>
    boardRows.value.reduce((sum, row) => sum + row.notional, 0),
);

const averageSpread = computed(() =>
    boardRows.value.length === 0
        ? 0
        : boardRows.value.reduce((sum, row) => sum + row.spread, 0) / boardRows.value.length,
);

const topMovers = computed(() =>
    [...boardRows.value].sort((a, b) => b.spreadPct - a.spreadPct).slice(0, 6),
);

const widestSpread = computed(() =>
    boardRows.value.reduce((best, row) => {
        if (!best) return row;
        return row.spread > best.spread ? row : best;
    }, null),
);

const signalSummary = computed(() => {
    const total = boardRows.value.length || 1;
    const counts = {
        Stable: boardRows.value.filter((row) => row.signal === 'Stable').length,
        Active: boardRows.value.filter((row) => row.signal === 'Active').length,
        Volatile: boardRows.value.filter((row) => row.signal === 'Volatile').length,
    };

    return Object.entries(counts).map(([label, count]) => ({
        label,
        count,
        percent: (count / total) * 100,
    }));
});

const liveStatus = computed(() => (boardRows.value.length > 0 ? 'Open' : 'No Data'));

const maxQuantity = computed(() =>
    Math.max(1, ...boardRows.value.map((row) => row.quantity)),
);

const maxSpreadPct = computed(() =>
    Math.max(1, ...boardRows.value.map((row) => row.spreadPct)),
);

const quantityWidth = (row) =>
    `${clamp((row.quantity / maxQuantity.value) * 100, 4, 100)}%`;

const spreadWidth = (row) =>
    `${clamp((row.spreadPct / maxSpreadPct.value) * 100, 4, 100)}%`;

const rangePosition = (row) => {
    if (row.max <= row.min) return '50%';
    return `${clamp(((row.last - row.min) / (row.max - row.min)) * 100, 0, 100)}%`;
};

const resetBoard = () => {
    search.value = '';
    activeSignal.value = 'All';
    sortBy.value = 'notional';
    sortDir.value = 'desc';
    topN.value = '25';
};

const quickViewVolatile = () => {
    activeSignal.value = 'Volatile';
    sortBy.value = 'spread';
    sortDir.value = 'desc';
    topN.value = '25';
};

const quickViewActive = () => {
    activeSignal.value = 'Active';
    sortBy.value = 'notional';
    sortDir.value = 'desc';
    topN.value = '25';
};

const quickViewDepth = () => {
    activeSignal.value = 'All';
    sortBy.value = 'qty';
    sortDir.value = 'desc';
    topN.value = '50';
};

const lastUpdated = computed(() =>
    new Intl.DateTimeFormat('en-UG', {
        dateStyle: 'medium',
        timeStyle: 'short',
    }).format(new Date()),
);
</script>

<template>
<home-layout>
<div class="market-live">
<section class="tape-strip mb-3">
<div class="tape-track">
<span v-for="(item, idx) in topMovers" :key="`${item.product}-${idx}`">
{{ item.product }} · Last {{ formatCurrency(item.last) }} ·
<strong :class="item.spreadPct >= 5 ? 'text-danger' : 'text-success'">
{{ item.spreadPct.toFixed(2) }}%
</strong>
</span>
<span v-if="topMovers.length === 0">No instruments in market tape.</span>
</div>
</section>

<div class="row g-3 mb-3">
<div class="col-6 col-xl-3">
<div class="pulse-card">
<small>Active Buyers</small>
<strong>{{ batchListed.buyers }}</strong>
</div>
</div>
<div class="col-6 col-xl-3">
<div class="pulse-card">
<small>Listed Batches</small>
<strong>{{ batchListed.batches }}</strong>
</div>
</div>
<div class="col-6 col-xl-3">
<div class="pulse-card">
<small>Market Depth (Kgs)</small>
<strong>{{ formatInteger(totalQuantity) }}</strong>
</div>
</div>
<div class="col-6 col-xl-3">
<div class="pulse-card">
<small>Notional (UGX)</small>
<strong>{{ formatInteger(totalNotional) }}</strong>
</div>
</div>
</div>

<div class="row g-3">
<div class="col-lg-4">
<div class="card hero-panel h-100 session-pulse-card">
<div class="card-body">
<div class="d-flex justify-content-between align-items-center mb-2">
<h6 class="mb-0">Session Pulse</h6>
<span class="badge badge-live">{{ liveStatus }}</span>
</div>
<div class="pulse-item">
<small>Average Spread</small>
<strong>{{ formatCurrency(averageSpread) }}</strong>
</div>
<div class="pulse-item">
<small>Widest Spread</small>
<strong class="text-capitalize">{{ widestSpread?.commodity ?? 'N/A' }}</strong>
<small class="text-muted">{{ widestSpread ? formatCurrency(widestSpread.spread) : '-' }}</small>
</div>
<div class="pulse-item">
<small>Last Updated</small>
<strong>{{ lastUpdated }}</strong>
</div>

<div class="distribution-list mt-2">
<div v-for="item in signalSummary" :key="item.label" class="distribution-row">
<div class="d-flex justify-content-between">
<small>{{ item.label }}</small>
<small>{{ item.count }}</small>
</div>
<div class="distribution-bar">
<span :style="{ width: `${item.percent}%` }"></span>
</div>
</div>
</div>

<div class="pulse-actions mt-3">
<button type="button" class="pulse-action-btn" @click="quickViewVolatile">Volatile Focus</button>
<button type="button" class="pulse-action-btn" @click="quickViewActive">Active Book</button>
<button type="button" class="pulse-action-btn" @click="quickViewDepth">Depth View</button>
<button type="button" class="pulse-action-btn pulse-action-ghost" @click="resetBoard">Reset Board</button>
</div>

<div class="pulse-cta-row mt-3">
<Link href="/register" class="btn btn-brand btn-sm pulse-cta-btn">Create Trading Account</Link>
<Link href="/login" class="btn btn-soft btn-sm pulse-cta-btn">Open Trading Desk</Link>
</div>
</div>
</div>
</div>

<div class="col-lg-8">
<div class="card hero-panel exchange-board-card">
<div class="card-body p-0">
<div class="board-head">
<div>
<h5 class="mb-0">Exchange Board</h5>
<small class="text-muted">Live commodity index by instrument and grade</small>
</div>
<small class="board-count">{{ filteredRows.length }} / {{ boardRows.length }} instruments</small>
</div>

<div class="board-controls">
<div class="search-wrap">
<input v-model="search" type="text" class="search-input" placeholder="Search instrument, type, or grade" />
</div>
<div class="signal-chips">
<button
v-for="filter in signalFilters"
:key="filter"
type="button"
class="chip-btn"
:class="{ active: activeSignal === filter }"
@click="activeSignal = filter"
>
{{ filter }}
</button>
</div>
<select v-model="sortBy" class="sort-select">
<option value="notional">Sort: Notional</option>
<option value="spread">Sort: Spread %</option>
<option value="qty">Sort: Quantity</option>
<option value="last">Sort: Last</option>
<option value="instrument">Sort: Instrument</option>
</select>
<select v-model="topN" class="sort-select">
<option value="25">Rows: 25</option>
<option value="50">Rows: 50</option>
<option value="100">Rows: 100</option>
<option value="all">Rows: All</option>
</select>
<button type="button" class="clear-btn" @click="resetBoard">Reset</button>
</div>

<div class="table-responsive">
<table class="table align-middle mb-0 market-table">
<thead>
<tr>
<th><button class="th-sort" type="button" @click="setSort('instrument')">Instrument <span>{{ sortMark('instrument') }}</span></button></th>
<th><button class="th-sort" type="button" @click="setSort('last')">Last <span>{{ sortMark('last') }}</span></button></th>
<th><button class="th-sort" type="button" @click="setSort('low')">Low <span>{{ sortMark('low') }}</span></button></th>
<th><button class="th-sort" type="button" @click="setSort('high')">High <span>{{ sortMark('high') }}</span></button></th>
<th><button class="th-sort" type="button" @click="setSort('qty')">Qty (Kgs) <span>{{ sortMark('qty') }}</span></button></th>
<th><button class="th-sort" type="button" @click="setSort('spread')">Spread % <span>{{ sortMark('spread') }}</span></button></th>
<th><button class="th-sort" type="button" @click="setSort('notional')">Notional <span>{{ sortMark('notional') }}</span></button></th>
<th><button class="th-sort" type="button" @click="setSort('signal')">Signal <span>{{ sortMark('signal') }}</span></button></th>
</tr>
</thead>
<tbody>
<tr v-for="(row, index) in visibleRows" :key="`${row.product}-${index}`">
<td>
<strong class="d-block text-capitalize">{{ row.commodity }}</strong>
<small class="text-muted text-capitalize">{{ row.type ?? '-' }} · {{ row.grade ?? '-' }}</small>
</td>
<td>
<div class="cell-stack">
<span>{{ formatCurrency(row.last) }}</span>
<span class="range-track"><span class="range-dot" :style="{ left: rangePosition(row) }"></span></span>
</div>
</td>
<td>{{ formatCurrency(row.min) }}</td>
<td>{{ formatCurrency(row.max) }}</td>
<td>
<div class="cell-stack">
<span>{{ formatInteger(row.quantity) }}</span>
<span class="mini-track"><span class="mini-fill qty-fill" :style="{ width: quantityWidth(row) }"></span></span>
</div>
</td>
<td>
<div class="cell-stack">
<span :class="row.spreadPct >= 5 ? 'pill-danger' : 'pill-success'">
{{ row.spreadPct.toFixed(2) }}%
</span>
<span class="mini-track"><span class="mini-fill spread-fill" :style="{ width: spreadWidth(row) }"></span></span>
</div>
</td>
<td>{{ formatInteger(row.notional) }}</td>
<td>
<span class="signal-pill" :class="`signal-${row.signal.toLowerCase()}`">{{ row.signal }}</span>
</td>
</tr>
<tr v-if="filteredRows.length === 0">
<td colspan="8" class="text-center text-muted py-4">No instruments match the current filters.</td>
</tr>
<tr v-else class="table-foot-row">
<td colspan="8">Showing {{ visibleRows.length }} of {{ filteredRows.length }} filtered instruments.</td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
</div>
</home-layout>
</template>

<style scoped>
.market-live {
display: grid;
gap: 0.75rem;
}

.session-pulse-card,
.exchange-board-card {
border-radius: 18px;
overflow: hidden;
border: 1px solid var(--mx-border);
}

.tape-strip {
border: 1px solid var(--mx-border);
border-radius: 999px;
background: rgba(255, 255, 255, 0.9);
overflow: hidden;
}

.tape-track {
display: flex;
gap: 18px;
padding: 10px 16px;
overflow-x: auto;
white-space: nowrap;
font-size: 0.85rem;
color: #334e68;
}

.pulse-card {
border: 1px solid var(--mx-border);
border-radius: 14px;
background: rgba(255, 255, 255, 0.9);
padding: 12px 14px;
}

.pulse-card small {
display: block;
color: #627d98;
}

.pulse-card strong {
font-size: 1.1rem;
}

.pulse-item {
border: 1px solid #e3edf3;
border-radius: 10px;
padding: 10px 12px;
margin-bottom: 10px;
background: #fff;
display: grid;
gap: 2px;
}

.pulse-item small {
color: #627d98;
}

.distribution-list {
display: grid;
gap: 8px;
}

.distribution-row small {
font-size: 0.75rem;
}

.distribution-bar {
height: 8px;
background: #edf5f9;
border-radius: 999px;
overflow: hidden;
}

.distribution-bar span {
display: block;
height: 100%;
background: linear-gradient(90deg, #0e8a7d, #0b6f65);
}

.pulse-actions {
display: grid;
grid-template-columns: 1fr 1fr;
gap: 8px;
}

.pulse-action-btn {
height: 34px;
border: 1px solid #cfe1eb;
border-radius: 10px;
background: #f8fcfd;
color: #334e68;
font-size: 0.76rem;
font-weight: 700;
line-height: 1;
}

.pulse-action-btn:hover {
border-color: #0e8a7d;
color: #0b6f65;
background: #eef8f6;
}

.pulse-action-ghost {
background: #fff;
}

.pulse-cta-row {
display: grid;
grid-template-columns: 1fr 1fr;
gap: 8px;
}

.pulse-cta-btn {
height: 38px;
display: inline-flex;
align-items: center;
justify-content: center;
font-size: 0.8rem;
font-weight: 700;
}

.board-head {
padding: 14px 16px;
border-bottom: 1px solid #dbe7ee;
display: flex;
justify-content: space-between;
align-items: center;
gap: 8px;
}

.board-count {
font-size: 0.8rem;
color: #627d98;
}

.board-controls {
padding: 12px 16px;
border-bottom: 1px solid #e3edf3;
display: grid;
grid-template-columns: 1.2fr 1fr 170px 150px 90px;
gap: 10px;
align-items: center;
}

.search-wrap {
min-width: 0;
}

.search-input {
width: 100%;
height: 38px;
border: 1px solid #cfe1eb;
border-radius: 10px;
padding: 0 12px;
font-size: 0.9rem;
background: #fff;
}

.search-input:focus {
outline: none;
border-color: #0e8a7d;
box-shadow: 0 0 0 3px rgba(14, 138, 125, 0.14);
}

.signal-chips {
display: flex;
gap: 6px;
flex-wrap: wrap;
}

.chip-btn {
border: 1px solid #cfe1eb;
background: #fff;
color: #486581;
font-size: 0.76rem;
font-weight: 700;
border-radius: 999px;
padding: 6px 10px;
line-height: 1;
}

.chip-btn.active {
background: #e8f4f2;
border-color: #0e8a7d;
color: #0b6f65;
}

.sort-select {
height: 38px;
border: 1px solid #cfe1eb;
border-radius: 10px;
padding: 0 10px;
font-size: 0.85rem;
background: #fff;
}

.clear-btn {
height: 38px;
border: 1px solid #cfe1eb;
border-radius: 10px;
background: #fff;
color: #486581;
font-size: 0.82rem;
font-weight: 700;
}

.clear-btn:hover {
border-color: #0e8a7d;
color: #0b6f65;
}

.th-sort {
display: inline-flex;
align-items: center;
gap: 6px;
border: 0;
background: transparent;
padding: 0;
font-size: 0.72rem;
font-weight: 700;
color: #486581;
text-transform: uppercase;
letter-spacing: 0.05em;
}

.th-sort span {
font-size: 0.75rem;
color: #7b8794;
}

.cell-stack {
display: grid;
gap: 6px;
}

.mini-track {
height: 6px;
background: #edf5f9;
border-radius: 999px;
overflow: hidden;
}

.mini-fill {
display: block;
height: 100%;
border-radius: 999px;
}

.qty-fill {
background: linear-gradient(90deg, #0e8a7d, #0b6f65);
}

.spread-fill {
background: linear-gradient(90deg, #f79009, #dc6803);
}

.range-track {
position: relative;
display: block;
height: 8px;
background: #edf5f9;
border-radius: 999px;
}

.range-dot {
position: absolute;
top: 50%;
transform: translate(-50%, -50%);
width: 10px;
height: 10px;
border-radius: 999px;
background: #0e8a7d;
border: 2px solid #fff;
box-shadow: 0 0 0 1px #0e8a7d;
}

.market-table tbody tr:hover {
background: rgba(14, 138, 125, 0.04);
}

.table-foot-row td {
font-size: 0.8rem;
color: #627d98;
background: #fafcfd;
}

.pill-success,
.pill-danger {
padding: 2px 8px;
border-radius: 999px;
font-size: 0.75rem;
font-weight: 700;
}

.pill-success {
background: #e8fff6;
color: #127353;
}

.pill-danger {
background: #fff1f1;
color: #b42318;
}

.signal-pill {
padding: 4px 8px;
border-radius: 999px;
font-size: 0.75rem;
font-weight: 700;
}

.signal-stable {
background: #eff8ff;
color: #175cd3;
}

.signal-active {
background: #fff6ed;
color: #b54708;
}

.signal-volatile {
background: #fff1f1;
color: #b42318;
}

@media (max-width: 991.98px) {
.board-controls {
grid-template-columns: 1fr;
}

.pulse-actions {
grid-template-columns: 1fr;
}

.pulse-cta-row {
grid-template-columns: 1fr;
}
}
</style>
