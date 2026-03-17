<script setup>
import { computed, ref } from 'vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';
import InputError from '@/Components/InputError.vue';
import AddMap from '@/Farm/AddMap.vue';
import { Back, EditPen } from '@element-plus/icons-vue';
import { ElNotification } from 'element-plus';

const page = usePage();
const farm = computed(() => page.props.farm?.data ?? page.props.farm ?? {});
const owner = computed(() => page.props.owner?.data ?? page.props.owner ?? {});
const sustainabilityMetadata = computed(() => page.props.sustainability_metadata ?? []);
const farmSustainabilityData = computed(() => page.props.farm_sustainability_data ?? []);
const sustainabilityCount = computed(() => {
  return Array.isArray(farmSustainabilityData.value) ? farmSustainabilityData.value.length : 0;
});
const latestSustainabilityDate = computed(() => {
  if (!Array.isArray(farmSustainabilityData.value) || farmSustainabilityData.value.length === 0) return 'N/A';
  return farmSustainabilityData.value[0]?.created_at || 'N/A';
});
const formattedFarmArea = computed(() => {
  const value = Number(farm.value?.area_acres ?? 0);
  if (Number.isNaN(value)) return farm.value?.area_acres ?? '0';
  return value.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
});
const farmSubtitle = computed(() => {
  const parts = [];
  if (farm.value?.location) parts.push(`Located in ${farm.value.location}`);
  if (farm.value?.area_acres !== undefined && farm.value?.area_acres !== null && farm.value?.area_acres !== '') {
    parts.push(`${formattedFarmArea.value} acres under cultivation`);
  }
  return parts.join(' • ') || 'Registered farm details and field information.';
});

const sustainabilityForm = useForm({
  activity: '',
  value: '',
});
const showSustainabilityModal = ref(false);

const ownerFullName = computed(() => {
  if (owner.value?.full_name) return owner.value.full_name;
  return [owner.value?.first_name, owner.value?.last_name].filter(Boolean).join(' ') || 'N/A';
});
const ownerInitials = computed(() => {
  const first = owner.value?.first_name?.[0] ?? '';
  const last = owner.value?.last_name?.[0] ?? '';
  return (first + last || 'FR').toUpperCase();
});

const ownerLocation = computed(() => {
  return [owner.value?.village, owner.value?.sub_county, owner.value?.district].filter(Boolean).join(', ') || 'N/A';
});

const goToFarmUpdatePage = () => {
  const id = farm.value?.id;
  if (!id) return;
  router.get(route('farmer.farms.update.page', { id }));
};

const goBack = () => {
  if (owner.value?.id) {
    router.get(route('farmer.show', { id: owner.value.id }));
    return;
  }

  router.get(route('cooperative.farmers'));
};

const goToOwnerPage = () => {
  const id = owner.value?.id;
  if (!id) return;
  router.get(route('farmer.show', { id }));
};

const submitFarmSustainabilityData = () => {
  const id = farm.value?.id;
  if (!id) return;

  sustainabilityForm.post(route('farmer.farms.sustainability.store', { id }), {
    preserveScroll: true,
    onSuccess: () => {
      sustainabilityForm.reset('activity', 'value');
      sustainabilityForm.clearErrors();
      showSustainabilityModal.value = false;
      ElNotification({
        title: 'Success',
        message: page.props.flash?.success || 'Farm sustainability data saved successfully.',
        type: 'success',
      });
    },
  });
};

const destroySustainabilityData = (row) => {
  const id = farm.value?.id;
  const sustainabilityId = Number(row?.id ?? 0);
  if (!id || !sustainabilityId) return;

  router.delete(route('farmer.farms.sustainability.destroy', { id, sustainabilityId }), {
    preserveScroll: true,
    onSuccess: () => {
      ElNotification({
        title: 'Success',
        message: page.props.flash?.success || 'Farm sustainability data deleted successfully.',
        type: 'success',
      });
    },
  });
};

const openSustainabilityModal = () => {
  sustainabilityForm.clearErrors();
  showSustainabilityModal.value = true;
};

const closeSustainabilityModal = () => {
  sustainabilityForm.clearErrors();
  showSustainabilityModal.value = false;
};

const formatSustainabilityDate = (value) => {
  if (!value) return 'N/A';
  const date = new Date(value);
  if (Number.isNaN(date.getTime())) return value;
  return date.toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: '2-digit' });
};
</script>

<template>
  <CooperativeLayout>
<div class="container">



<div class="card farm-top-card">
<div class="card-inner border-bottom farm-topbar">
<div>
<h4 class="mb-1 font-large text-capitalize">
{{ farm.farm_name || 'N/A' }}
</h4>
<p class="sub-text mb-0">
<template v-if="farm.location">
<em class="icon ni ni-map-fill mr-1"></em>{{ farm.location }}
</template>
<template v-if="farm.location && farm.area_acres !== undefined && farm.area_acres !== null && farm.area_acres !== ''">
<span class="mx-1">•</span>
</template>
<template v-if="farm.area_acres !== undefined && farm.area_acres !== null && farm.area_acres !== ''">
<em class="icon ni ni-property mr-1"></em>{{ formattedFarmArea }} acres
</template>
<template v-if="!farm.location && (farm.area_acres === undefined || farm.area_acres === null || farm.area_acres === '')">
<em class="icon ni ni-info mr-1"></em>{{ farmSubtitle }}
</template>
</p>
</div>
<el-button-group class="farm-head-actions">
<el-button :icon="EditPen" @click="goToFarmUpdatePage">Edit Farm</el-button>
<el-button type="primary" @click="openSustainabilityModal">
<em class="icon ni ni-plus mr-1"></em>Add Data
</el-button>
</el-button-group>
</div>

<div class="card-inner">


<div class="row g-gs">
<div class="col-12 col-lg-8">
<div class="card farm-map-card-shell">

<div class="card-inner">
<AddMap
:farm-id="farm.id"
:farm-name="farm.farm_name"
:location="farm.location"
:area-acres="farm.area_acres"
:latitude="farm.latitude"
:longitude="farm.longitude"
:map-data="page.props.map_data"
/>
</div>

<div class="card-inner border-top">
<div class="sustainability-header">
<div class="sustainability-copy">
<h6 class="title mb-1">
<em class="icon ni ni-leaf mr-1"></em>
Farm Sustainability Data </h6>
<p class="sub-text mb-0">Capture and track sustainability activity records for this farm.</p>
</div>
<div class="sustainability-overview">
<span class="sustainability-stat-pill">
<em class="icon ni ni-list-check mr-1"></em>{{ sustainabilityCount }} Record(s)
</span>
<span class="sustainability-stat-pill">
<em class="icon ni ni-calendar mr-1"></em>{{ formattedLatestSustainabilityDate }}
</span>
</div>
</div>

<div class="sustainability-list">
<div v-if="farmSustainabilityData.length === 0" class="sustainability-empty">
<div class="sustainability-empty-icon"><em class="icon ni ni-leaf"></em></div>
<strong>No sustainability data added yet.</strong>
<p class="mb-0">Use the add data action to start documenting sustainability activity for this farm.</p>
</div>
<div v-else class="sustainability-grid">
<div
v-for="row in farmSustainabilityData"
:key="row.id"
class="sustainability-list-item"
>
<div class="sustainability-list-top">
<span class="sustainability-activity-badge text-capitalize">
<em class="icon ni ni-check-circle mr-1"></em>{{ row.activity || 'N/A' }}
</span>
<el-button type="danger" text class="sustainability-delete-btn" @click="destroySustainabilityData(row)">
<em class="icon ni ni-trash"></em>
</el-button>
</div>
<div class="sustainability-meta">
<span class="sustainability-date">
<em class="icon ni ni-calendar mr-1"></em>{{ formatSustainabilityDate(row.created_at) }}
</span>
</div>
</div>
</div>
</div>
</div>
</div>
</div>





















<div class="col-12 col-lg-4">
<div class="card farm-owner-card h-100">
<div class="card-inner">
<div class="heading">
<div>
<h6 class="title mb-1"><em class="icon ni ni-user mr-1"></em>Farmer Profile</h6>
<p class="sub-text mb-0">Registered farmer details linked to this farm.</p>
</div>

</div>
</div>
<div class="card-inner">
<div class="owner-hero owner-hero-link mb-3" @click="goToOwnerPage">
<div class="owner-avatar">
{{ ownerInitials }}
</div>
<div>
<h6 class="mb-1">{{ ownerFullName }}</h6>
<p class="sub-text mb-0 text-capitalize">{{ owner.status || 'N/A' }}</p>
</div>
</div>

<div class="details-grid details-grid-single">
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-call mr-1"></em>Phone Number</span>
<strong>{{ owner.phone_number || 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-mail mr-1"></em>Email</span>
<strong>{{ owner.email || 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-card-view mr-1"></em>National ID</span>
<strong>{{ owner.national_id || 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-users mr-1"></em>Gender</span>
<strong>{{ owner.gender || 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-calendar mr-1"></em>Date of Birth</span>
<strong>{{ owner.date_of_birth || 'N/A' }}</strong>
</div>
<div class="detail-item">
<span class="sub-text"><em class="icon ni ni-map-fill mr-1"></em>Address</span>
<strong>{{ ownerLocation }}</strong>
</div>
</div>
</div>
</div>
</div>
</div>


</div>
</div>

<el-dialog
v-model="showSustainabilityModal"
title="Add Farm Sustainability Data"
width="760px"
class="sustainability-modal"
destroy-on-close
@close="closeSustainabilityModal"
>
<div class="sustainability-modal-copy">
<span class="sustainability-modal-badge"><em class="icon ni ni-leaf mr-1"></em>Farm Sustainability</span>
<p class="sustainability-modal-text">Capture a sustainability activity and its recorded value for this farm.</p>
</div>
<form
class="sustainability-form-shell"
@submit.prevent="submitFarmSustainabilityData"
>
<el-row :gutter="12" class="sustainability-form-grid">
<el-col :xs="24" :md="12" class="sustainability-form-col">
<label class="form-label">Activity</label>
<el-select
    v-model="sustainabilityForm.activity"
    class="w-100"
    clearable
    allow-create
    default-first-option
    placeholder="Select or type activity"
>
    <el-option
    v-for="item in sustainabilityMetadata"
    :key="item"
    :label="item"
    :value="item"
    />
</el-select>
<div class="sustainability-feedback">
    <small v-if="!sustainabilityForm.errors.activity" class="sustainability-hint">Select existing or type a new activity.</small>
    <InputError :message="sustainabilityForm.errors.activity" class="sustainability-error" />
</div>
</el-col>

<el-col :xs="24" :md="12" class="sustainability-form-col">
<label class="form-label">Value</label>
<el-input v-model="sustainabilityForm.value" placeholder="Enter value" />
<div class="sustainability-feedback">
    <small v-if="!sustainabilityForm.errors.value" class="sustainability-hint">Example: Shade trees planted, Drip irrigation, Organic compost.</small>
    <InputError :message="sustainabilityForm.errors.value" class="sustainability-error" />
</div>
</el-col>
</el-row>
</form>

<template #footer>
<div class="sustainability-modal-footer">
<el-button @click="closeSustainabilityModal">Cancel</el-button>
<el-button type="primary" :loading="sustainabilityForm.processing" @click="submitFarmSustainabilityData">
Save Data
</el-button>
</div>
</template>
</el-dialog>











    </div>
  </CooperativeLayout>
</template>

<style scoped>
.farm-top-card {
  border-radius: 18px;
  border: 1px solid #e7edf5 !important;
  background: linear-gradient(180deg, #ffffff 0%, #fbfcfe 100%);
  margin-bottom: 8px;
}

.farm-topbar {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 14px;
  background:
    linear-gradient(135deg, rgba(111, 78, 55, 0.08), rgba(255, 255, 255, 0) 38%),
    linear-gradient(180deg, #ffffff 0%, #fbfcfe 100%);
}

.farm-head-actions {
  display: inline-flex;
  flex-wrap: wrap;
}

.farm-header-pills {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}

.farm-header-pill {
  display: inline-flex;
  align-items: center;
  padding: 6px 10px;
  border-radius: 999px;
  border: 1px solid #e5e9f2;
  background: rgba(255, 255, 255, 0.92);
  color: #526484;
  font-size: 12px;
  font-weight: 600;
}

.farm-back-link {
  color: inherit;
  text-decoration: none;
}

.farm-back-link:hover {
  color: #364a63;
  text-decoration: none;
}

.farm-map-card-shell,
.farm-profile-card,
.farm-owner-card {
  border-radius: 18px;
  border: 1px solid #e7edf5 !important;
  background: linear-gradient(180deg, #ffffff 0%, #fbfcfe 100%);
}

.heading {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 12px;
}

.details-grid {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 8px;
}

.details-grid-single {
  grid-template-columns: 1fr;
}

.detail-item {
  background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
  border-radius: 14px;
  padding: 10px 12px;
  display: flex;
  flex-direction: column;
  gap: 4px;
  border: 1px solid #e7edf5;
  box-shadow: 0 10px 24px rgba(15, 23, 42, 0.04);
}

.detail-item-full {
  grid-column: span 2;
}

.farm-section-badge {
  display: inline-flex;
  align-items: center;
  padding: 6px 10px;
  border-radius: 999px;
  background: #ecfdf3;
  color: #166534;
  font-size: 12px;
  font-weight: 700;
}

.owner-hero {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 12px;
  border: 1px solid #e7edf5;
  border-radius: 16px;
  background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
}

.owner-hero-link {
  cursor: pointer;
  transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
}

.owner-hero-link:hover {
  transform: translateY(-1px);
  border-color: #d7e3f4;
  box-shadow: 0 12px 28px rgba(15, 23, 42, 0.08);
}

.owner-avatar {
  width: 52px;
  height: 52px;
  border-radius: 50%;
  background: linear-gradient(135deg, #6f4e37, #8b5a2b);
  color: #fff;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: 1rem;
  box-shadow: 0 12px 24px rgba(111, 78, 55, 0.22);
}

.sustainability-form-shell {
  border: 1px solid #eef2f7;
  border-radius: 18px;
  padding: 18px;
  background: linear-gradient(180deg, #ffffff 0%, #fbfcfe 100%);
}

.sustainability-modal-copy {
  margin-bottom: 14px;
}

.sustainability-modal-badge {
  display: inline-flex;
  align-items: center;
  padding: 6px 12px;
  border-radius: 999px;
  background: #ecfdf3;
  color: #166534;
  font-size: 12px;
  font-weight: 700;
}

.sustainability-modal-text {
  margin: 12px 0 0;
  color: #6b7a90;
  font-size: 14px;
  line-height: 1.6;
}

.sustainability-modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
}

.sustainability-header {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 10px;
  margin-bottom: 10px;
}

.sustainability-copy {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.sustainability-overview {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}

.sustainability-stats {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}

.sustainability-stat-pill {
  display: inline-flex;
  align-items: center;
  border: 1px solid #e5e9f2;
  border-radius: 999px;
  padding: 4px 10px;
  background: #fff;
  font-size: 12px;
  color: #526484;
}

.sustainability-form-grid {
  align-items: stretch;
}

.sustainability-form-col {
  display: grid;
  grid-template-rows: 20px 40px 22px;
  row-gap: 10px;
  min-width: 0;
}

.sustainability-form-col .form-label {
  margin: 0;
  line-height: 20px;
}

.sustainability-feedback {
  min-height: 22px;
  margin-top: 2px;
  min-width: 0;
  overflow: hidden;
}

.sustainability-list {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.sustainability-grid {
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: 12px;
}

.sustainability-empty {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 8px;
  padding: 18px;
  border: 1px dashed #dbe4f0;
  border-radius: 16px;
  background: linear-gradient(180deg, #ffffff 0%, #fbfcfe 100%);
  color: #8094ae;
  font-size: 13px;
}

.sustainability-empty strong {
  color: #364a63;
  font-size: 14px;
}

.sustainability-empty-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  border-radius: 12px;
  background: #ecfdf3;
  color: #166534;
  font-size: 18px;
}

.sustainability-list-item {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 10px;
  padding: 12px 14px;
  border: 1px solid #e7edf5;
  border-radius: 16px;
  background: linear-gradient(180deg, #ffffff 0%, #fbfcfe 100%);
  box-shadow: 0 10px 24px rgba(15, 23, 42, 0.04);
}

.sustainability-list-top {
  display: grid;
  grid-template-columns: minmax(0, 1fr) auto;
  align-items: start;
  gap: 10px;
  margin-bottom: 6px;
  width: 100%;
}

.sustainability-activity-badge {
  display: inline-flex;
  align-items: center;
  max-width: none;
  border: 1px solid #e5e9f2;
  border-radius: 14px;
  padding: 6px 10px;
  background: #f8fafc;
  color: #364a63;
  font-weight: 600;
  white-space: normal;
  overflow: visible;
  text-overflow: unset;
  line-height: 1.45;
  word-break: break-word;
}

.sustainability-meta {
  display: flex;
  align-items: center;
  gap: 8px;
}

.sustainability-value-chip {
  display: inline-flex;
  align-items: center;
  max-width: 100%;
  color: #445870;
  font-weight: 600;
  padding: 4px 10px;
  border-radius: 999px;
  background: #f8fafc;
  border: 1px solid #e5e9f2;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.sustainability-date {
  color: #8094ae;
  font-size: 12px;
  white-space: nowrap;
}

.sustainability-delete-btn {
  flex-shrink: 0;
  align-self: start;
}

.sustainability-hint {
  display: block;
  max-width: 100%;
  color: #8094ae;
  font-size: 12px;
  line-height: 1.25;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

:deep(.sustainability-form-shell .el-input__wrapper),
:deep(.sustainability-form-shell .el-select__wrapper) {
  min-height: 40px;
}

:deep(.sustainability-error p) {
  display: block;
  max-width: 100%;
  margin: 0;
  font-size: 14px;
  line-height: 1.25;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.sustainability-delete-btn {
  padding: 0;
  width: 36px;
  height: 36px;
  min-height: 36px;
  border-radius: 8px;
  font-size: 18px;
  flex-shrink: 0;
}

:deep(.sustainability-modal .el-dialog) {
  border-radius: 18px;
  overflow: hidden;
}

:deep(.sustainability-modal .el-dialog__header) {
  padding: 18px 22px 0;
}

:deep(.sustainability-modal .el-dialog__body) {
  padding: 14px 22px 8px;
}

:deep(.sustainability-modal .el-dialog__footer) {
  padding: 0 22px 20px;
}

@media (max-width: 768px) {
  .farm-topbar {
    flex-direction: column;
    align-items: flex-start;
  }

  .farm-head-actions {
    width: 100%;
  }

  .heading {
    flex-direction: column;
    align-items: flex-start;
  }

  .details-grid {
    grid-template-columns: 1fr;
  }

  .detail-item-full {
    grid-column: span 1;
  }

  .sustainability-form-shell {
    padding: 12px;
  }

  .sustainability-modal-footer {
    flex-direction: column-reverse;
  }

  .sustainability-header {
    flex-direction: column;
    align-items: flex-start;
  }

  .sustainability-grid {
    grid-template-columns: 1fr;
  }

  .sustainability-list-top {
    grid-template-columns: 1fr;
  }

  .sustainability-overview {
    width: 100%;
  }
}

@media (max-width: 1200px) {
  .sustainability-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}
</style>
