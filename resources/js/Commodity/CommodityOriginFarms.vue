<script setup>
import { computed } from 'vue';
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
    if (!farmId) return;

    router.get(route('farm.show', { id: farmId }));
};

const totalAcres = computed(() => {
    return props.originFarms.reduce((sum, farm) => {
        const acres = Number(farm?.area_acres ?? 0);

        return sum + (Number.isFinite(acres) ? acres : 0);
    }, 0);
});

const locationCount = computed(() => {
    return new Set(
        props.originFarms
            .map((farm) => String(farm?.location ?? '').trim())
            .filter(Boolean),
    ).size;
});

const farmInitials = (name) => {
    const words = String(name ?? '')
        .trim()
        .split(/\s+/)
        .filter(Boolean)
        .slice(0, 2);

    if (!words.length) return 'OF';

    return words.map((word) => word.charAt(0).toUpperCase()).join('');
};

const formatAcres = (value) => {
    const acres = Number(value);

    if (!Number.isFinite(acres)) return 'N/A acres';

    return `${acres.toLocaleString(undefined, { maximumFractionDigits: 2 })} acres`;
};
</script>

<template>
    <div class="origin-section-head mb-3">
        <div>
            <h6 class="title mb-1"><em class="icon ni ni-map-pin mr-1"></em>Farm where commodity originates</h6>
            <p class="sub-text mb-0">Verified source farms linked to this commodity lot and the production footprint behind it.</p>
        </div>
        <span class="origin-count-pill">{{ originFarms.length }} linked</span>
    </div>

    <div v-if="originFarms.length" class="origin-summary-grid mb-3">
        <div class="origin-summary-card">
            <div class="origin-summary-top">
                <span class="origin-summary-icon"><em class="icon ni ni-home"></em></span>
                <span class="origin-summary-trend">Active links</span>
            </div>
            <strong>{{ originFarms.length }}</strong>
            <span class="origin-summary-label">Linked Farms</span>
            <p class="origin-summary-copy mb-0">Verified farms currently attached to this commodity record.</p>
        </div>
        <div class="origin-summary-card">
            <div class="origin-summary-top">
                <span class="origin-summary-icon"><em class="icon ni ni-map"></em></span>
                <span class="origin-summary-trend">Geographic spread</span>
            </div>
            <strong>{{ locationCount }}</strong>
            <span class="origin-summary-label">Source Locations</span>
            <p class="origin-summary-copy mb-0">Distinct locations represented across the linked source farms.</p>
        </div>
        <div class="origin-summary-card">
            <div class="origin-summary-top">
                <span class="origin-summary-icon"><em class="icon ni ni-property"></em></span>
                <span class="origin-summary-trend">Production footprint</span>
            </div>
            <strong>{{ totalAcres.toLocaleString(undefined, { maximumFractionDigits: 2 }) }} acres</strong>
            <span class="origin-summary-label">Total Acreage</span>
            <p class="origin-summary-copy mb-0">Combined acreage behind the origin farms linked to this lot.</p>
        </div>
    </div>

    <div v-if="originFarms.length" class="origin-card-grid">
        <article
            v-for="farm in originFarms"
            :key="farm.id"
            class="origin-farm-card"
        >
            <div class="origin-farm-top">
                <div class="origin-farm-title-row">
                    <span class="origin-farm-avatar">{{ farmInitials(farm.farm_name) }}</span>
                    <div class="origin-farm-heading">
                        <span class="origin-farm-kicker">Verified Origin Farm</span>
                        <button type="button" class="farm-link-button text-capitalize" @click="goToOriginFarmDetails(farm.id)">
                            {{ farm.farm_name }}
                        </button>
                    </div>
                    <span class="origin-farm-status">Verified</span>
                </div>
            </div>

            <div class="origin-farm-meta">
                <div class="origin-meta-item">
                    <span class="origin-meta-label"><em class="icon ni ni-map-pin mr-1"></em>Location</span>
                    <strong>{{ farm.location ?? 'Location not added' }}</strong>
                </div>
                <div class="origin-meta-item">
                    <span class="origin-meta-label"><em class="icon ni ni-property mr-1"></em>Area</span>
                    <strong>{{ formatAcres(farm.area_acres) }}</strong>
                </div>
            </div>

            <p class="origin-farm-copy mb-0">
                This farm contributes verified origin data to the current commodity record.
            </p>

            <div class="origin-farm-footer">
                <button type="button" class="origin-open-btn" @click="goToOriginFarmDetails(farm.id)">
                    <em class="icon ni ni-arrow-right mr-1"></em>Open farm details
                </button>
            </div>
        </article>
    </div>
    <div v-else class="empty-origin">
        <div class="origin-empty-icon">
            <em class="icon ni ni-map-pin"></em>
        </div>
        <div>
            <p class="origin-empty-title mb-1">No origin farms linked yet.</p>
            <p class="sub-text mb-0">Attach source farms to show where this commodity was produced and verified.</p>
        </div>
    </div>
</template>

<style scoped>
.origin-section-head {
display: flex;
align-items: flex-start;
justify-content: space-between;
gap: 16px;
}

.origin-count-pill {
display: inline-flex;
align-items: center;
padding: 7px 12px;
border-radius: 999px;
background: #f8fafc;
border: 1px solid #edf2f7;
color: #526484;
font-size: 12px;
font-weight: 600;
}

.origin-summary-grid {
display: grid;
grid-template-columns: repeat(3, minmax(0, 1fr));
gap: 12px;
}

.origin-summary-card {
display: flex;
flex-direction: column;
gap: 4px;
padding: 12px;
border-radius: 16px;
background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
border: 1px solid #e6edf5;
box-shadow: 0 1px 0 rgba(255, 255, 255, 0.95) inset;
}

.origin-summary-top {
display: flex;
align-items: center;
justify-content: space-between;
gap: 10px;
margin-bottom: 0;
}

.origin-summary-icon {
width: 30px;
height: 30px;
border-radius: 12px;
display: inline-flex;
align-items: center;
justify-content: center;
background: linear-gradient(180deg, #f8fafc 0%, #eef4fb 100%);
border: 1px solid #e3ebf5;
color: #334155;
font-size: 14px;
flex: 0 0 30px;
}

.origin-summary-trend {
display: inline-flex;
align-items: center;
padding: 4px 8px;
border-radius: 999px;
background: #f8fafc;
border: 1px solid #edf2f7;
color: #64748b;
font-size: 10px;
font-weight: 600;
white-space: nowrap;
}

.origin-summary-card strong {
font-size: 22px;
line-height: 1.1;
letter-spacing: -0.02em;
color: #0f172a;
}

.origin-summary-label {
font-size: 12px;
font-weight: 700;
letter-spacing: 0.06em;
text-transform: uppercase;
color: #94a3b8;
display: inline-flex;
align-items: center;
}

.origin-summary-copy {
font-size: 12px;
line-height: 1.5;
color: #7c8aa5;
}

.origin-card-grid {
display: grid;
grid-template-columns: repeat(2, minmax(0, 1fr));
gap: 12px;
}

.origin-farm-card {
display: flex;
flex-direction: column;
gap: 12px;
padding: 16px;
border-radius: 18px;
background: linear-gradient(180deg, #ffffff 0%, #fbfcfe 100%);
border: 1px solid #e6edf5;
box-shadow: 0 8px 18px rgba(15, 23, 42, 0.03);
transition: transform 0.18s ease, border-color 0.2s ease, box-shadow 0.2s ease;
}

.origin-farm-card:hover {
transform: translateY(-1px);
border-color: #d7e2ed;
box-shadow: 0 12px 22px rgba(15, 23, 42, 0.05);
}

.origin-farm-top {
display: flex;
flex-direction: column;
gap: 6px;
}

.origin-farm-title-row {
display: flex;
align-items: flex-start;
gap: 12px;
}

.origin-farm-avatar {
width: 42px;
height: 42px;
border-radius: 14px;
display: inline-flex;
align-items: center;
justify-content: center;
background: linear-gradient(180deg, #f8fafc 0%, #eef4fb 100%);
border: 1px solid #e3ebf5;
color: #334155;
font-size: 13px;
font-weight: 700;
flex: 0 0 42px;
}

.origin-farm-heading {
display: flex;
flex-direction: column;
gap: 4px;
min-width: 0;
flex: 1 1 auto;
}

.origin-farm-kicker {
font-size: 11px;
font-weight: 700;
letter-spacing: 0.08em;
text-transform: uppercase;
color: #94a3b8;
}

.origin-farm-status {
display: inline-flex;
align-items: center;
padding: 5px 10px;
border-radius: 999px;
background: #eefbf3;
border: 1px solid #d7f3e3;
color: #15803d;
font-size: 11px;
font-weight: 700;
white-space: nowrap;
}

.origin-farm-meta {
display: grid;
grid-template-columns: repeat(2, minmax(0, 1fr));
gap: 10px;
}

.origin-meta-item {
display: flex;
flex-direction: column;
gap: 4px;
padding: 10px 12px;
border-radius: 12px;
background: #f8fafc;
border: 1px solid #edf2f7;
}

.origin-meta-label {
font-size: 10px;
font-weight: 700;
letter-spacing: 0.06em;
text-transform: uppercase;
color: #94a3b8;
display: inline-flex;
align-items: center;
}

.origin-farm-copy {
font-size: 12px;
line-height: 1.6;
color: #7c8aa5;
}

.origin-farm-footer {
padding-top: 2px;
}

.empty-origin {
display: flex;
align-items: center;
gap: 14px;
border: 1px dashed #e6edf5;
border-radius: 16px;
padding: 18px;
color: #64748b;
background: linear-gradient(180deg, #fbfcfe 0%, #f8fafc 100%);
}

.origin-empty-icon {
width: 40px;
height: 40px;
border-radius: 12px;
display: inline-flex;
align-items: center;
justify-content: center;
background: #eef6ff;
color: #3b82f6;
flex: 0 0 40px;
}

.origin-empty-icon .icon {
font-size: 18px;
}

.origin-empty-title {
font-size: 14px;
font-weight: 700;
color: #0f172a;
}

.farm-link-button {
border: 0;
background: transparent;
padding: 0;
color: #162033;
font-size: 16px;
font-weight: 700;
line-height: 1.35;
text-align: left;
width: fit-content;
}

.farm-link-button:hover {
text-decoration: underline;
}

.origin-open-btn {
display: inline-flex;
align-items: center;
gap: 6px;
padding: 0;
border: 0;
background: transparent;
color: #475569;
font-size: 13px;
font-weight: 600;
}

.origin-open-btn:hover {
color: #162033;
}

@media (max-width: 767px) {
.origin-section-head {
flex-direction: column;
}

.origin-summary-grid {
grid-template-columns: 1fr;
}

.origin-card-grid {
grid-template-columns: 1fr;
}

.origin-farm-meta {
grid-template-columns: 1fr;
}

.origin-farm-title-row {
flex-wrap: wrap;
}

.empty-origin {
align-items: flex-start;
}
}
</style>
