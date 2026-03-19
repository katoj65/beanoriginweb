<script setup>
import { computed, ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import { ElNotification } from 'element-plus';

const props = defineProps({
    commodityId: {
        type: [Number, String],
        required: true,
    },
    canIsOwner: {
        type: Boolean,
        default: false,
    },
    qualityMetadata: {
        type: Array,
        default: () => [],
    },
    commodityQualityData: {
        type: Array,
        default: () => [],
    },
});

const qualityForm = useForm({
    activity: '',
    value: '',
});

const showQualityForm = ref(false);

const sortedCommodityQualityData = computed(() => {
    const rows = Array.isArray(props.commodityQualityData) ? [...props.commodityQualityData] : [];

    return rows.sort((a, b) => {
        const aTime = new Date(a?.created_at ?? '').getTime() || 0;
        const bTime = new Date(b?.created_at ?? '').getTime() || 0;

        return bTime - aTime;
    });
});

const formatDate = (value) => {
    if (!value) return 'No date';

    const parsed = new Date(value);

    if (Number.isNaN(parsed.getTime())) {
        return value;
    }

    return parsed.toLocaleString(undefined, {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

const resetQualityForm = () => {
    qualityForm.reset('activity', 'value');
    qualityForm.clearErrors();
};

const openQualityForm = () => {
    resetQualityForm();
    showQualityForm.value = true;
};

const hideQualityForm = () => {
    resetQualityForm();
    showQualityForm.value = false;
};

const submitCommodityQualityData = () => {
    if (!props.commodityId) return;

    qualityForm.post(route('commodity.quality-data.store', { id: props.commodityId }), {
        preserveScroll: true,
        onSuccess: () => {
            ElNotification({
                title: 'Success',
                message: 'Commodity quality data saved successfully.',
                type: 'success',
            });
            resetQualityForm();
            showQualityForm.value = false;
        },
    });
};

const destroyCommodityQualityData = (row) => {
    const qualityId = Number(row?.id ?? 0);

    if (!props.commodityId || !qualityId) return;

    router.delete(route('commodity.quality-data.destroy', {
        id: props.commodityId,
        qualityId,
    }), {
        preserveScroll: true,
        onSuccess: () => {
            ElNotification({
                title: 'Success',
                message: 'Commodity quality data deleted successfully.',
                type: 'success',
            });
        },
    });
};
</script>

<template>
    <div class="quality-section-shell mb-3">
        <div class="quality-section-head">
            <div>
                <h6 class="title mb-1"><em class="icon ni ni-check-circle mr-1"></em>Quality information about commodity/ harvest</h6>
                <p class="sub-text mb-0">Track cupping, grading, moisture, and other quality checkpoints for this lot.</p>
            </div>
            <div class="quality-head-actions">
                <span class="quality-count-pill">{{ commodityQualityData.length }} record(s)</span>
                <el-button v-if="canIsOwner" type="primary" @click="openQualityForm" class="quality-add-btn">
                    <em class="icon ni ni-plus mr-1"></em>Add Quality Metric
                </el-button>
            </div>
        </div>
    </div>

    <el-dialog
        v-model="showQualityForm"
        title="Add Quality Metric"
        width="680px"
        class="quality-form-dialog"
        :close-on-click-modal="false"
        @closed="hideQualityForm"
    >
        <div class="quality-form-panel">
            <div class="quality-form-head">
                <div class="quality-form-title-wrap">
                    <p class="quality-form-title mb-1"><em class="icon ni ni-edit mr-1"></em>Add Quality Metric</p>
                    <span class="sub-text">Record one quality activity and value at a time for this commodity.</span>
                </div>
            </div>

            <form class="quality-form-row row g-2 align-items-start" @submit.prevent="submitCommodityQualityData">
                <div class="col-12 col-md-6 quality-col">
                    <div class="quality-field-card">
                        <label class="form-label"><em class="icon ni ni-list mr-1"></em>Quality Metric</label>
                        <el-select
                            v-model="qualityForm.activity"
                            class="w-100"
                            filterable
                            clearable
                            allow-create
                            default-first-option
                            placeholder="Select or enter a quality metric"
                        >
                            <el-option
                                v-for="item in qualityMetadata"
                                :key="item"
                                :label="item"
                                :value="item"
                            />
                        </el-select>
                        <small class="quality-field-hint">Choose a saved metric or type a new one. {{ qualityMetadata.length }} available.</small>
                        <div class="quality-field-feedback">
                            <small v-if="qualityForm.errors.activity" class="text-danger d-block">{{ qualityForm.errors.activity }}</small>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 quality-col">
                    <div class="quality-field-card">
                        <label class="form-label"><em class="icon ni ni-pen2 mr-1"></em>Value</label>
                        <el-input v-model="qualityForm.value" clearable placeholder="Enter quality value" />
                        <small class="quality-field-hint">Example: 11.5%, Grade A, Cup score 84</small>
                        <div class="quality-field-feedback">
                            <small v-if="qualityForm.errors.value" class="text-danger d-block">{{ qualityForm.errors.value }}</small>
                        </div>
                    </div>
                </div>

                <div class="col-12 quality-dialog-actions">
                    <el-button type="primary" native-type="submit" :loading="qualityForm.processing" class="quality-save-btn">
                        Save
                    </el-button>
                </div>
            </form>
        </div>
    </el-dialog>

    <div v-if="sortedCommodityQualityData.length" class="quality-grid-wrap mb-3">
        <div
            v-for="row in sortedCommodityQualityData"
            :key="row.id"
            class="quality-grid-item"
        >
            <div class="quality-grid-main">
                <div class="quality-value-wrap">
                    <div class="quality-value-cell">{{ row.value }}</div>
                </div>
                <div class="text-capitalize quality-activity-cell quality-metric-title">
                    {{ row.activity }}
                </div>
                <p class="quality-card-copy mb-0">
                    Recorded quality checkpoint for this commodity lot.
                </p>
            </div>

            <el-button
                v-if="canIsOwner"
                text
                class="quality-row-delete-btn"
                @click="destroyCommodityQualityData(row)"
                aria-label="Delete quality item"
            >
                <em class="icon ni ni-trash"></em>
            </el-button>
        </div>
    </div>

    <div v-else class="quality-empty-state mb-3">
        <div class="quality-empty-icon">
            <em class="icon ni ni-check-circle"></em>
        </div>
        <div>
            <p class="quality-empty-title mb-1">No quality metrics recorded yet.</p>
            <p class="sub-text mb-0">Add the first quality entry to start building the commodity quality history.</p>
        </div>
    </div>
</template>

<style scoped>
.quality-section-shell {
padding: 0;
border-radius: 0;
background: transparent;
border: 0;
}

.quality-section-head {
display: flex;
align-items: flex-start;
justify-content: space-between;
gap: 16px;
}

.quality-head-actions {
display: inline-flex;
align-items: center;
gap: 10px;
}

.quality-count-pill {
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

.quality-add-btn {
color: #ffffff !important;
}

.quality-form-row {
margin: 0;
}

.quality-form-dialog :deep(.el-dialog__body) {
padding-top: 8px;
padding-bottom: 18px;
}

.quality-form-dialog :deep(.el-dialog__header) {
padding-bottom: 12px;
border-bottom: 1px solid #f6f8fb;
}

.quality-form-dialog :deep(.el-dialog__title) {
font-weight: 700;
color: #1f2a37;
}

.quality-col {
display: flex;
flex-direction: column;
}

.quality-form-panel {
border: 1px solid #f3f6fa;
border-radius: 14px;
padding: 16px;
background: #fbfcfe;
max-width: 100%;
}

.quality-field-card {
padding: 12px;
border-radius: 12px;
background: #ffffff;
border: 1px solid #f5f7fb;
}

.quality-form-head {
display: flex;
align-items: flex-start;
justify-content: space-between;
gap: 10px;
margin-bottom: 14px;
}

.quality-form-title-wrap {
display: flex;
flex-direction: column;
gap: 2px;
min-width: 0;
}

.quality-form-title {
display: inline-flex;
align-items: center;
font-size: 14px;
font-weight: 700;
color: #1f2a37;
}

.quality-field-hint {
display: block;
color: #64748b;
font-size: 11px;
margin-top: 4px;
}

.quality-field-feedback {
min-height: 18px;
margin-top: 4px;
}

.quality-dialog-actions {
display: flex;
justify-content: flex-end;
gap: 10px;
padding-top: 12px;
margin-top: 4px;
border-top: 1px solid #f6f8fb;
}

.quality-save-btn {
height: 40px;
min-height: 40px;
padding-inline: 16px;
display: inline-flex;
justify-content: center;
font-weight: 600;
}

.quality-grid-wrap {
 display: grid;
 grid-template-columns: repeat(4, minmax(0, 1fr));
 gap: 10px;
}

.quality-grid-item {
 position: relative;
 display: flex;
 flex-direction: column;
 gap: 8px;
 padding: 14px 42px 12px 14px;
 border: 1px solid #e9eff6;
 border-radius: 14px;
 background:
  linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
 box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.98);
 min-width: 0;
 overflow: hidden;
 transition: transform 0.18s ease, border-color 0.2s ease, background-color 0.2s ease, box-shadow 0.2s ease;
}

.quality-grid-item::before {
 content: "";
 position: absolute;
 inset: 0 0 auto 0;
 height: 56px;
 background:
  linear-gradient(90deg, rgba(99, 102, 241, 0.045) 0%, rgba(59, 130, 246, 0.02) 50%, rgba(16, 185, 129, 0.03) 100%);
 pointer-events: none;
}

.quality-grid-item:hover {
 border-color: #d7e2ed;
 background: linear-gradient(180deg, #ffffff 0%, #f9fbff 100%);
 box-shadow: inset 0 1px 0 rgba(255, 255, 255, 1);
 transform: translateY(-1px);
}

.quality-grid-main {
 position: relative;
 z-index: 1;
 min-width: 0;
 display: flex;
 flex-direction: column;
 gap: 12px;
 align-items: center;
 text-align: center;
}

.quality-activity-cell {
font-weight: 600;
line-height: 1.35;
}

.quality-metric-title {
font-size: 14px;
font-weight: 700;
color: #162033;
line-height: 1.45;
letter-spacing: -0.01em;
max-width: 100%;
text-wrap: balance;
padding-inline: 6px;
}

.quality-card-copy {
font-size: 11px;
line-height: 1.5;
color: #7c8aa5;
max-width: 100%;
padding-inline: 10px;
}

.quality-value-wrap {
display: inline-flex;
align-items: center;
justify-content: center;
min-width: 0;
max-width: 100%;
padding: 9px 16px;
border-radius: 9999px;
background:
 linear-gradient(180deg, #ffffff 0%, #f7fbff 100%);
border: 1px solid #dde7f1;
box-shadow:
 inset 0 1px 0 rgba(255, 255, 255, 0.96),
 0 6px 12px rgba(148, 163, 184, 0.08);
 backdrop-filter: blur(4px);
}

.quality-value-cell {
color: #0f172a;
line-height: 1.3;
font-size: 13px;
font-weight: 700;
white-space: normal;
text-align: center;
letter-spacing: -0.015em;
}

.quality-row-delete-btn {
position: absolute;
top: 12px;
right: 12px;
color: #7c8aa5;
padding: 0;
min-height: auto;
height: 28px;
width: 28px;
border-radius: 999px;
background: rgba(255, 255, 255, 0.92);
border: 1px solid #eef3f8;
backdrop-filter: blur(6px);
 z-index: 2;
 opacity: 0.72;
}

.quality-row-delete-btn:hover {
color: #334155;
background: #f8fafc;
border-color: #e2e8f0;
 opacity: 0.98;
}

.quality-row-delete-btn .icon {
font-size: 12px;
}

.quality-field-feedback .text-danger {
font-size: 13px;
font-weight: 500;
line-height: 1.3;
}

.quality-empty-state {
display: flex;
align-items: center;
gap: 14px;
padding: 18px;
border: 1px dashed #e6edf5;
border-radius: 16px;
background: linear-gradient(180deg, #fbfcfe 0%, #f8fafc 100%);
}

.quality-empty-icon {
width: 40px;
height: 40px;
border-radius: 12px;
display: inline-flex;
align-items: center;
justify-content: center;
background: #eefbf3;
color: #16a34a;
flex: 0 0 40px;
}

.quality-empty-icon .icon {
font-size: 18px;
}

.quality-empty-title {
font-size: 14px;
font-weight: 700;
color: #0f172a;
}

@media (max-width: 767px) {
.quality-section-head {
flex-direction: column;
}

.quality-head-actions {
width: 100%;
justify-content: space-between;
}

.quality-form-head {
align-items: center;
}

.quality-grid-wrap {
grid-template-columns: 1fr;
}

.quality-grid-item {
padding: 14px;
}

.quality-empty-state {
align-items: flex-start;
}
}

@media (min-width: 768px) and (max-width: 1199px) {
.quality-grid-wrap {
grid-template-columns: repeat(2, minmax(0, 1fr));
}
}
</style>
