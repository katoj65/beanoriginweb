<script setup>
import { router } from '@inertiajs/vue3';

const props = defineProps({
    commodityId: {
        type: [Number, String],
        required: true,
    },
    originFarms: {
        type: Array,
        default: () => [],
    },
});

const goToOriginFarmDetails = (farmId) => {
    if (!props.commodityId || !farmId) return;

    router.get(route('commodity.origin-farms.show', {
        commodity: props.commodityId,
        farm: farmId,
    }));
};
</script>

<template>
    <div class="d-flex align-items-center justify-content-between mb-2">
        <h6 class="title mb-0"><em class="icon ni ni-map-pin mr-1"></em>Farm where commodity originates</h6>
        <span class="sub-text">{{ originFarms.length }} linked</span>
    </div>

    <div v-if="originFarms.length" class="table-responsive origin-table-wrap">
        <table class="table table-sm table-middle mb-0 origin-table border">
            <thead>
                <tr>
                    <th style="width: 100px;">Farm Name</th>
                    <th>Location</th>
                    <th>Area (Acres)</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="farm in originFarms" :key="farm.id">
                    <td>
                        <button type="button" class="farm-link-button text-capitalize" @click="goToOriginFarmDetails(farm.id)">
                            {{ farm.farm_name }}
                        </button>
                    </td>
                    <td>{{ farm.location ?? 'N/A' }}</td>
                    <td>{{ farm.area_acres ?? 'N/A' }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div v-else class="empty-origin">
        No origin farms linked yet.
    </div>
</template>

<style scoped>
.origin-table th {
display: table-cell;
background: #f8fafc;
color: #526484;
font-weight: 600;
white-space: nowrap;
border-bottom: 1px solid #f2f5f9;
padding-top: 4px;
padding-bottom: 4px;
}

.origin-table td {
color: #364a63;
vertical-align: middle;
white-space: nowrap;
border-top: 1px solid #f5f7fb;
padding-top: 4px;
padding-bottom: 4px;
}

.origin-table-wrap {
border: 1px solid #f2f5f9;
border-radius: 10px;
overflow: hidden;
background: #fff;
}

.origin-table.border {
border: 0 !important;
}

.origin-table tbody tr:hover {
background: #f8fafc;
}

.empty-origin {
border: 1px dashed #e6edf5;
border-radius: 10px;
padding: 12px;
color: #64748b;
background: #f8fafc;
}

.farm-link-button {
border: 0;
background: transparent;
padding: 0;
color: #0f766e;
font-weight: 600;
}

.farm-link-button:hover {
text-decoration: underline;
}
</style>
