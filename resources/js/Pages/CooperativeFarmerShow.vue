<script setup>
import { computed, ref } from 'vue';
import { Link, router, useForm, usePage } from '@inertiajs/vue3';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';
import InputError from '@/Components/InputError.vue';
import { ElNotification } from 'element-plus';

const page = usePage();
const farmer = computed(() => page.props.farmer?.data ?? page.props.farmer ?? {});
const farms = computed(() => {
const source = page.props.farms;
if (Array.isArray(source?.data)) return source.data;
if (Array.isArray(source)) return source;
return [];
});
const farmCount = computed(() => farms.value.length);
const fullName = computed(() => {
const value = farmer.value?.full_name;
if (value) return value;
return [farmer.value?.first_name, farmer.value?.last_name].filter(Boolean).join(' ') || 'N/A';
});
const totalFarmArea = computed(() =>
farms.value.reduce((sum, item) => sum + Number(item?.area_acres ?? 0), 0),
);
const formattedTotalFarmArea = computed(() =>
totalFarmArea.value.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }),
);
const averageFarmArea = computed(() => (farmCount.value > 0 ? totalFarmArea.value / farmCount.value : 0));
const formattedAverageFarmArea = computed(() =>
averageFarmArea.value.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }),
);
const profileSince = computed(() => {
const value = farmer.value?.created_at;
if (!value) return 'N/A';
return String(value).split(' ')[0];
});

const statusClass = computed(() => {
const status = farmer.value?.status;
if (status === 'active') return 'bg-success';
if (status === 'pending') return 'bg-warning text-dark';
if (status === 'suspended') return 'bg-danger';
return 'bg-secondary';
});

const statusLabel = computed(() => {
const status = farmer.value?.status;
if (!status) return 'Unknown';
return status.charAt(0).toUpperCase() + status.slice(1).replace('_', ' ');
});

const locationLabel = computed(() => {
return [farmer.value?.village, farmer.value?.sub_county, farmer.value?.district].filter(Boolean).join(', ') || 'N/A';
});

const initials = computed(() => {
const first = farmer.value?.first_name?.[0] ?? '';
const last = farmer.value?.last_name?.[0] ?? '';
return (first + last || 'FR').toUpperCase();
});

const showModal = ref(false);

const form = useForm({
cooperative_farmer_id: farmer.value?.id ?? '',
farm_name: '',
location: '',
area_acres: '',
});

const openModal = () => {
form.cooperative_farmer_id = farmer.value?.id ?? '';
showModal.value = true;
};

const closeModal = () => {
showModal.value = false;
};

const submit = () => {
form.post(route('cooperative.farms.store'), {
onSuccess: () => {
showModal.value = false;
form.reset();
form.cooperative_farmer_id = farmer.value?.id ?? '';
ElNotification({
title: 'Success',
message: page.props.flash?.success || 'Farm has been saved successfully.',
type: 'success',
});
},
});
};

const destroyFarm = (farmId) => {
if (!farmId) return;

router.delete(route('farmer.farms.destroy', { id: farmId }), {
preserveScroll: true,
onSuccess: () => {
ElNotification({
title: 'Success',
message: page.props.flash?.success || 'Farm deleted successfully.',
type: 'success',
});
},
});
};
</script>

<template>
<CooperativeLayout>
<div class="container farmer-profile-page mt-0">
<div class="nk-content-inner">
<div class="nk-content-body">


<div class="nk-block farmer-page-wrap">
<div class="row g-gs">
<div class="col-lg-4 col-xl-4 col-xxl-3">
<div class="card card-bordered farmer-summary-card">
<div class="card-inner-group">
<div class="card-inner border-0">
<div class="user-card user-card-s2">
<div class="user-avatar lg bg-secondary profile-avatar-solid">
<em class="icon ni ni-user"></em>

</div>
<div class="user-info">
<div class="badge rounded-pill ucap" :class="statusClass">{{ statusLabel }}</div>
<h5 class="text-capitalize">{{ fullName }}</h5>
<span class="sub-text summary-email">{{ farmer.email || 'N/A' }}</span>
<p class="summary-location mb-0"><em class="icon ni ni-map-pin mr-1"></em>{{ locationLabel }}</p>
</div>
</div>
</div>



<div class="card-inner border-0">
<div class="row text-center stats-row">
<div class="col-4">
<div class="profile-stats">
<span class="amount">{{ farmCount }}</span>
<span class="sub-text">Total Farms</span>
</div>
</div>
<div class="col-4">
<div class="profile-stats">
<span class="amount">{{ formattedTotalFarmArea }}</span>
<span class="sub-text">Area (Acres)</span>
</div>
</div>
<div class="col-4">
<div class="profile-stats">
<span class="amount text-capitalize">{{ farmer.primary_crop || 'N/A' }}</span>
<span class="sub-text">Primary Crop</span>
</div>
</div>
</div>
</div>





<div class="card-inner short-details">
<h6 class="overline-title mb-2"><em class="icon ni ni-list mr-1"></em>Profile Snapshot</h6>
<div class="row g-3">
<div class="col-sm-6 col-md-4 col-lg-12 short-detail-item">
<span class="sub-text"><em class="icon ni ni-calendar mr-1"></em>Date of Birth:</span>
<span>{{ farmer.date_of_birth || 'N/A' }}</span>
</div>
<div class="col-sm-6 col-md-4 col-lg-12 short-detail-item">
<span class="sub-text"><em class="icon ni ni-users mr-1"></em>Gender:</span>
<span class="text-capitalize">{{ farmer.gender || 'N/A' }}</span>
</div>
<div class="col-sm-6 col-md-4 col-lg-12 short-detail-item">
<span class="sub-text"><em class="icon ni ni-card-view mr-1"></em>National ID:</span>
<span>{{ farmer.national_id || 'N/A' }}</span>
</div>
<div class="col-sm-6 col-md-4 col-lg-12 short-detail-item">
<span class="sub-text"><em class="icon ni ni-tag mr-1"></em>Farmer ID:</span>
<span>#{{ farmer.id || '-' }}</span>
</div>
<div class="col-sm-6 col-md-4 col-lg-12 short-detail-item">
<span class="sub-text"><em class="icon ni ni-call mr-1"></em>Phone Number:</span>
<span>{{ farmer.phone_number || 'N/A' }}</span>
</div>
<div class="col-sm-6 col-md-4 col-lg-12 short-detail-item">
<span class="sub-text"><em class="icon ni ni-map-fill mr-1"></em>District:</span>
<span class="text-capitalize">{{ farmer.district || 'N/A' }}</span>
</div>
<div class="col-sm-6 col-md-4 col-lg-12 short-detail-item">
<span class="sub-text"><em class="icon ni ni-map mr-1"></em>Sub County:</span>
<span class="text-capitalize">{{ farmer.sub_county || 'N/A' }}</span>
</div>
<div class="col-sm-6 col-md-4 col-lg-12 short-detail-item">
<span class="sub-text"><em class="icon ni ni-map-pin mr-1"></em>Village:</span>
<span class="text-capitalize">{{ farmer.village || 'N/A' }}</span>
</div>
<div class="col-sm-6 col-md-4 col-lg-12 short-detail-item">
<span class="sub-text"><em class="icon ni ni-calendar mr-1"></em>Registered At:</span>
<span>{{ farmer.created_at || 'N/A' }}</span>
</div>
</div>
</div>
</div>
</div>
</div>

<div class="col-lg-8 col-xl-8 col-xxl-9">
<div class="card card-bordered farmer-main-card h-100">
<div class="card-inner">
<div class="nk-block">
<div class="profile-head-actions">
<div class="overline-title-alt mb-2 mt-2 section-head"><em class="icon ni ni-user mr-1"></em>Farmer Profile</div>
<el-button
v-if="farmer.id"
class="farmer-edit-btn"
@click="router.get(route('farmer.update.page', { id: farmer.id }))"
>
<em class="icon ni ni-edit mr-1"></em>Edit Farmer
</el-button>
</div>
<div class="in-profile-grid">
<div class="in-profile-card">
<span class="in-profile-label"><em class="icon ni ni-home mr-1"></em>Total Farms</span>
<strong class="in-profile-value">{{ farmCount }}</strong>
</div>
<div class="in-profile-card">
<span class="in-profile-label"><em class="icon ni ni-package mr-1"></em>Total Land</span>
<strong class="in-profile-value">{{ formattedTotalFarmArea }} acres</strong>
</div>
<div class="in-profile-card">
<span class="in-profile-label"><em class="icon ni ni-bar-chart mr-1"></em>Average Farm Size</span>
<strong class="in-profile-value">{{ formattedAverageFarmArea }} acres</strong>
</div>
<div class="in-profile-card">
<span class="in-profile-label"><em class="icon ni ni-check-circle mr-1"></em>Status</span>
<strong class="in-profile-value text-capitalize">{{ statusLabel }}</strong>
</div>
</div>
<div class="in-profile-meta">
<span><em class="icon ni ni-calendar mr-1"></em>Member Since: {{ profileSince }}</span>
<span><em class="icon ni ni-map-pin mr-1"></em>Location: {{ locationLabel }}</span>
</div>
</div>

<div class="nk-block">
<div class="farm-section-head">
<h6 class="lead-text mb-0 section-head"><em class="icon ni ni-home mr-1"></em>Farm Portfolio</h6>
<button type="button" class="btn btn-primary btn-sm add-farm-btn" @click="openModal">
<em class="icon ni ni-plus mr-1"></em>Add Farm
</button>
</div>
<div v-if="farms.length" class="farm-list-wrap">
<div class="nk-tb-list nk-tb-ulist is-compact farm-table-modern">
<div class="nk-tb-item nk-tb-head">
<div class="nk-tb-col"><span class="sub-text"><em class="icon ni ni-tag mr-1"></em>Farm ID</span></div>
<div class="nk-tb-col tb-col-sm"><span class="sub-text"><em class="icon ni ni-home mr-1"></em>Farm Name</span></div>
<div class="nk-tb-col"><span class="sub-text"><em class="icon ni ni-package mr-1"></em>Farm Size (Acres)</span></div>
<div class="nk-tb-col"><span class="sub-text"><em class="icon ni ni-map-pin mr-1"></em>Location</span></div>
<div class="nk-tb-col tb-col-end"><span class="sub-text"><em class="icon ni ni-trash mr-1"></em>Action</span></div>
</div>
<div class="nk-tb-item" v-for="farm in farms" :key="farm.id">
<div class="nk-tb-col">
<span class="fw-bold">#{{ farm.id }}</span>
</div>
<div class="nk-tb-col tb-col-sm">
<Link :href="route('cooperative.farms.show', farm.id)" class="farm-link text-capitalize">
{{ farm.farm_name || 'Unnamed Farm' }}
</Link>
</div>
<div class="nk-tb-col">
<span class="amount">{{ farm.area_acres || 0 }} acres</span>
</div>
<div class="nk-tb-col">
<span class="sub-text text-capitalize">{{ farm.location || 'N/A' }}</span>
</div>
<div class="nk-tb-col tb-col-end">
<el-button type="danger" plain size="small" @click="destroyFarm(farm.id)">
<em class="icon ni ni-trash mr-1"></em>Delete
</el-button>
</div>
</div>
</div>
</div>
<p v-else class="sub-text mb-0 farm-empty-state">No farms registered for this farmer yet.</p>
</div>

<div class="nk-block">
<h6 class="lead-text mb-3 section-head"><em class="icon ni ni-user mr-1"></em>Farmer Information</h6>
<div class="row g-3">
<div class="col-xl-12 col-xxl-6">
<div class="card card-bordered internal-info-card">
<div class="card-inner">
<h6 class="overline-title mb-2"><em class="icon ni ni-call mr-1"></em>Contact</h6>
<div class="detail-pairs">
<div><span class="sub-text"><em class="icon ni ni-call mr-1"></em>Phone Number:</span><strong>{{ farmer.phone_number || 'N/A' }}</strong></div>
<div><span class="sub-text"><em class="icon ni ni-mail mr-1"></em>Email:</span><strong>{{ farmer.email || 'N/A' }}</strong></div>
<div><span class="sub-text"><em class="icon ni ni-check-circle mr-1"></em>Status:</span><strong class="text-capitalize">{{ farmer.status || 'N/A' }}</strong></div>
<div><span class="sub-text"><em class="icon ni ni-update mr-1"></em>Updated At:</span><strong>{{ farmer.updated_at || 'N/A' }}</strong></div>
<div class="detail-span-2"><span class="sub-text"><em class="icon ni ni-growth mr-1"></em>Primary Crop:</span><strong class="text-capitalize">{{ farmer.primary_crop || 'N/A' }}</strong></div>
</div>
</div>
</div>
</div>
<div class="col-xl-12">
<div class="card card-bordered internal-info-card">
<div class="card-inner">
<h6 class="overline-title mb-2"><em class="icon ni ni-map mr-1"></em>Address</h6>
<div class="detail-pairs">
<div><span class="sub-text"><em class="icon ni ni-map-pin mr-1"></em>Village:</span><strong class="text-capitalize">{{ farmer.village || 'N/A' }}</strong></div>
<div><span class="sub-text"><em class="icon ni ni-map mr-1"></em>Sub County:</span><strong class="text-capitalize">{{ farmer.sub_county || 'N/A' }}</strong></div>
<div><span class="sub-text"><em class="icon ni ni-map-fill mr-1"></em>District:</span><strong class="text-capitalize">{{ farmer.district || 'N/A' }}</strong></div>
<div><span class="sub-text"><em class="icon ni ni-navigation mr-1"></em>Combined Location:</span><strong>{{ locationLabel }}</strong></div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>






<el-dialog
v-model="showModal"
class="farm-create-dialog"
width="680px"
align-center
destroy-on-close
:close-on-click-modal="false"
>
<template #header>
<div class="farm-dialog-head">
<h5 class="farm-dialog-title mb-1"><em class="icon ni ni-home mr-1"></em>Create Farm Profile</h5>
<p class="farm-dialog-subtext mb-0">Add the farm details linked to this farmer.</p>
</div>
</template>

<form class="row g-3 modal-form-grid" @submit.prevent="submit">
<input v-model="form.cooperative_farmer_id" type="hidden" />
<div class="col-12">
<InputError :message="form.errors.cooperative_farmer_id" class="mt-0" />
</div>

<div class="col-12 col-md-6 modal-field">
<label class="form-label"><em class="icon ni ni-home mr-1"></em>Farm/ Garden Name</label>
<el-input v-model="form.farm_name" placeholder="Main farm name" />
<InputError :message="form.errors.farm_name" class="mt-2" />
</div>

<div class="col-12 col-md-6 modal-field">
<label class="form-label"><em class="icon ni ni-map-pin mr-1"></em>Location</label>
<el-input v-model="form.location" placeholder="Village, sub county, district" />
<InputError :message="form.errors.location" class="mt-2" />
</div>

<div class="col-12 modal-field modal-field-full">
<label class="form-label"><em class="icon ni ni-package mr-1"></em>Acre Area</label>
<el-input v-model="form.area_acres" type="text" inputmode="decimal" placeholder="e.g. 2.50" />
<span class="field-hint">Enter acreage as a numeric value.</span>
<InputError :message="form.errors.area_acres" class="mt-2" />
</div>

<div class="col-12 dialog-actions">
<el-button @click="closeModal">Cancel</el-button>
<el-button type="primary" native-type="submit" :loading="form.processing">Save Farm</el-button>
</div>
</form>
</el-dialog>



</CooperativeLayout>
</template>

<style scoped>
.farmer-profile-page {
padding-top: 0.5rem;
padding-bottom: 0.75rem;
}

.farmer-page-wrap {
margin-bottom: 0.25rem;
}

.farmer-summary-card {
border-radius: 14px;
overflow: hidden;
}

.farmer-main-card {
border-radius: 14px;
}

.profile-avatar-solid span {
font-weight: 700;
}

.profile-avatar-solid {
display: flex;
flex-direction: column;
align-items: center;
justify-content: center;
gap: 1px;
}

.profile-avatar-solid .icon {
font-size: 24px;
line-height: 1;
}

.avatar-initials {
font-size: 12px;
font-weight: 700;
line-height: 1;
}

.profile-stats {
background: #f8fafc;
border: 1px solid #edf2f7;
border-radius: 8px;
padding: 10px 8px;
min-height: 88px;
height: 100%;
display: flex;
flex-direction: column;
justify-content: center;
}

.summary-email {
display: block;
margin-bottom: 0.3rem;
}

.summary-location {
font-size: 12px;
color: #8094ae;
display: flex;
align-items: center;
gap: 2px;
}

.stats-row .amount {
font-size: 18px;
font-weight: 700;
}

.short-details .sub-text {
display: block;
}

.short-detail-item {
padding: 8px 10px;
border-radius: 8px;
background: #f8fafc;
}

.farm-list-wrap {
overflow-x: auto;
}

.farm-table-modern {
border: 1px solid #e3e8ef;
border-radius: 12px;
overflow: hidden;
}

.farm-table-modern .nk-tb-head {
background: #f7f9fc;
}

.farm-table-modern .nk-tb-item {
border-bottom: 1px solid #edf2f7;
}

.farm-table-modern .nk-tb-item:last-child {
border-bottom: none;
}

.farm-table-modern .nk-tb-col {
padding-top: 0.85rem;
padding-bottom: 0.85rem;
}

.farm-table-modern .nk-tb-head .sub-text {
font-size: 12px;
letter-spacing: 0.02em;
color: #526484;
font-weight: 700;
}

.farm-empty-state {
padding: 12px 14px;
background: #f8fafc;
border-radius: 8px;
}

.farm-section-head {
display: flex;
align-items: center;
justify-content: space-between;
gap: 10px;
margin-bottom: 0.85rem;
}

.profile-head-actions {
display: flex;
align-items: center;
justify-content: space-between;
gap: 10px;
margin-bottom: 0.65rem;
}

.farmer-edit-btn {
display: inline-flex;
align-items: center;
}

.in-profile-grid {
display: grid;
grid-template-columns: repeat(4, minmax(0, 1fr));
gap: 10px;
}

.in-profile-card {
background: #f8fafc;
border: 1px solid #edf2f7;
border-radius: 10px;
padding: 10px 12px;
display: flex;
flex-direction: column;
gap: 5px;
}

.in-profile-label {
font-size: 12px;
color: #8094ae;
font-weight: 600;
}

.in-profile-value {
font-size: 16px;
font-weight: 700;
color: #364a63;
line-height: 1.25;
}

.in-profile-meta {
display: flex;
align-items: center;
justify-content: space-between;
gap: 12px;
margin-top: 10px;
padding-top: 10px;
border-top: 1px solid #edf2f7;
font-size: 12px;
color: #8094ae;
}

.in-profile-meta span {
display: inline-flex;
align-items: center;
}

.add-farm-btn {
display: inline-flex;
align-items: center;
}

.detail-pairs {
display: grid;
grid-template-columns: repeat(2, minmax(0, 1fr));
gap: 12px 14px;
}

.detail-pairs div {
display: flex;
flex-direction: column;
gap: 3px;
padding: 10px 12px;
border-radius: 8px;
background: #f8fafc;
}

.detail-pairs strong {
color: #364a63;
font-weight: 600;
}

.detail-span-2 {
grid-column: span 2;
}

.section-head {
font-size: 13px;
letter-spacing: 0.03em;
text-transform: uppercase;
color: #526484;
}

.farm-link {
color: #364a63;
font-weight: 600;
text-decoration: none;
}

.farm-link:hover {
color: #1f2b3a;
text-decoration: none;
}

.internal-info-card {
box-shadow: none !important;
border-radius: 12px;
border: none !important;
}

.farm-dialog-head {
padding-right: 30px;
}

.farm-dialog-title {
font-size: 18px;
font-weight: 700;
color: #364a63;
}

.farm-dialog-subtext {
font-size: 13px;
color: #8094ae;
}

.modal-form-grid .form-label {
margin-bottom: 0.35rem;
font-weight: 600;
}

.modal-form-grid {
row-gap: 2px;
}

.modal-field {
display: flex;
flex-direction: column;
}

.modal-field-full {
margin-top: 2px;
}

.field-hint {
font-size: 12px;
color: #8094ae;
margin-top: 6px;
}

:deep(.farm-create-dialog .el-dialog) {
border-radius: 14px;
overflow: hidden;
}

:deep(.farm-create-dialog .el-dialog__header) {
margin-right: 0;
padding: 16px 18px 12px;
border-bottom: 1px solid #edf2f7;
}

:deep(.farm-create-dialog .el-dialog__body) {
padding: 16px 18px 18px;
max-height: 70vh;
overflow-y: auto;
}

.modal-field :deep(.el-input__wrapper) {
border-radius: 8px;
}

:deep(.farm-create-dialog .el-dialog__headerbtn) {
top: 16px;
right: 18px;
}

.dialog-actions {
display: flex;
justify-content: flex-end;
gap: 10px;
padding-top: 12px;
margin-top: 6px;
border-top: 1px solid #edf2f7;
}

@media (max-width: 991px) {
.detail-pairs {
grid-template-columns: 1fr;
}

.in-profile-grid {
grid-template-columns: repeat(2, minmax(0, 1fr));
}

.in-profile-meta {
flex-direction: column;
align-items: flex-start;
}

.detail-span-2 {
grid-column: span 1;
}

.short-detail-item {
padding: 8px 9px;
}
}

@media (max-width: 768px) {
  .nk-tb-list.is-compact {
  min-width: 760px;
  }

  .farm-section-head {
  align-items: flex-start;
  flex-direction: column;
  }

  :deep(.farm-create-dialog .el-dialog) {
  width: 92vw !important;
  }

  .farmer-profile-page {
  padding-top: 0.35rem;
  }
}
</style>
