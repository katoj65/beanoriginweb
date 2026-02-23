<script setup>
import { computed, ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';
import { Plus, Search } from '@element-plus/icons-vue';

const page = usePage();
const search = ref('');

const rawBatches = computed(() => {
    const source = page.props.batches?.data ?? page.props.batches ?? [];

    if (Array.isArray(source)) {
        return source;
    }

    if (source && typeof source === 'object') {
        return Object.values(source);
    }

    return [];
});

const normalizedBatches = computed(() => {
    return rawBatches.value.map((item) => ({
        id: item.id,
        batchNumber: item.batch_number ?? `BATCH-${item.id ?? 'N/A'}`,
        commodityName: item.commodity_name ?? 'N/A',
        grade: item.grade ?? 'N/A',
        weight: Number(item.weight ?? 0),
        price: item.price ?? null,
        warehouse: item.warehouse ?? 'N/A',
        status: item.status ?? 'created',
        createdAt: item.created_at ?? null,
    }));
});

const filteredBatches = computed(() => {
    const q = search.value.trim().toLowerCase();

    return normalizedBatches.value.filter((batch) => {
        if (!q) return true;

        const haystack = [
            batch.batchNumber,
            batch.commodityName,
            batch.grade,
            batch.price,
            batch.warehouse,
            batch.status,
        ].join(' ').toLowerCase();

        return haystack.includes(q);
    });
});

const formatWeight = (value) => `${Number(value || 0).toLocaleString(undefined, { maximumFractionDigits: 2 })} kg`;

const formatPrice = (value) => {
    if (value === null || value === undefined || value === '') return 'N/A';
    const amount = Number(value);
    if (Number.isNaN(amount)) return value;
    return amount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
};

const formatDate = (value) => {
    if (!value) return 'N/A';
    const date = new Date(value);
    if (Number.isNaN(date.getTime())) return value;
    return date.toLocaleDateString();
};

const viewBatch = (batch) => {
    router.get(route('cooperative.batches.show', { id: batch.id }));
};

const goToCreate = () => {
    router.get(route('cooperative.batches.create'));
};
</script>

<template>
    <CooperativeLayout>
        <div class="container">
            <div class="card card-bordered">
                <div class="card-inner border-bottom market-card-header">
                    <div class="market-title">
                        <h6 class="title mb-1">Unlisted Batches</h6>
                        <p class="sub-text mb-0">
                            {{ filteredBatches.length }} batch{{ filteredBatches.length === 1 ? '' : 'es' }} with
                            status <strong>created</strong>.
                        </p>
                    </div>

                    <div class="market-actions">
                        <el-input
                            v-model="search"
                            size="large"
                            clearable
                            :prefix-icon="Search"
                            class="market-search"
                            placeholder="Search by batch number, commodity, grade..."
                        />
                        <el-button size="large" type="default" plain :icon="Plus" @click="goToCreate">
                            Add Batch
                        </el-button>
                    </div>
                </div>

                <div class="table-wrap">
                    <table class="table table-sm table-middle mb-0 batch-table">
                        <thead>
                            <tr>
                                <th>Batch No.</th>
                                <th>Commodity</th>
                                <th>Grade</th>
                                <th>Weight</th>
                                <th>Price</th>
                                <th>Warehouse</th>
                                <th>Status</th>
                                <th>Created Date</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="batch in filteredBatches" :key="batch.id" class="batch-row" @click="viewBatch(batch)">
                                <td>
                                    <strong>{{ batch.batchNumber }}</strong>
                                    <div class="sub-text">#{{ batch.id }}</div>
                                </td>
                                <td>{{ batch.commodityName }}</td>
                                <td>{{ batch.grade }}</td>
                                <td>{{ formatWeight(batch.weight) }}</td>
                                <td>{{ formatPrice(batch.price) }}</td>
                                <td>{{ batch.warehouse }}</td>
                                <td><span class="badge bg-warning-subtle text-warning">{{ batch.status }}</span></td>
                                <td>{{ formatDate(batch.createdAt) }}</td>
                                <td class="text-end">
                                    <el-button type="default" plain size="small" @click.stop="viewBatch(batch)">
                                        View
                                    </el-button>
                                </td>
                            </tr>

                            <tr v-if="!filteredBatches.length">
                                <td colspan="9" class="text-center py-4 text-muted">
                                    No unlisted batches found.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </CooperativeLayout>
</template>

<style scoped>
.table-wrap {
    overflow-x: auto;
}

.market-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
}

.market-actions {
    display: flex;
    align-items: center;
    gap: 12px;
    width: min(100%, 560px);
}

.market-search {
    flex: 1;
}

.batch-table th {
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: #64748b;
}

.batch-row {
    cursor: pointer;
}

.batch-row:hover {
    background: #f8fafc;
}

@media (max-width: 767px) {
    .market-actions {
        width: 100%;
        flex-direction: column;
        align-items: stretch;
    }
}
</style>
