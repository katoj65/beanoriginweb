<script setup>
import { computed, ref } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';
import InputError from '@/Components/InputError.vue';
import SubmitButton from '@/Components/SubmitButton.vue';

const page = usePage();
const farmer = computed(() => page.props.farmer?.data ?? page.props.farmer ?? {});

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
  number_of_gardens: '',
  primary_crop: farmer.value?.primary_crop ?? '',
  soil_type: '',
  water_source_type: '',
});

const openModal = () => {
  form.cooperative_farmer_id = farmer.value?.id ?? '';
  if (!form.primary_crop) form.primary_crop = farmer.value?.primary_crop ?? '';
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
      form.primary_crop = farmer.value?.primary_crop ?? '';
    },
  });
};
</script>

<template>
  <CooperativeLayout>
    <div class="container">
      <div class="row g-gs">
        <div class="col-12 col-lg-4">
          <div class="card card-bordered profile-card">
            <div class="card-inner text-center">
              <div class="profile-avatar">{{ initials }}</div>
              <h5 class="mb-1 mt-2">{{ farmer.full_name || 'N/A' }}</h5>
              <p class="sub-text mb-2">Registered Farmer</p>
              <span class="badge rounded-pill" :class="statusClass">{{ statusLabel }}</span>
            </div>
            <div class="card-inner">
              <div class="profile-metric">
                <span class="sub-text">Primary Crop</span>
                <strong>{{ farmer.primary_crop || 'N/A' }}</strong>
              </div>
              <div class="profile-metric">
                <span class="sub-text">Farmer ID</span>
                <strong>#{{ farmer.id || '-' }}</strong>
              </div>
              <div class="profile-metric">
                <span class="sub-text">Location</span>
                <strong>{{ locationLabel }}</strong>
              </div>
              <div class="mt-3">
                <button type="button" class="btn btn-light btn-sm w-100" @click="openModal">
                  Add Farm Details
                </button>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12 col-lg-8">
          <div class="card card-bordered">
            <div class="card-inner">
              <h6 class="title mb-1"><em class="icon ni ni-user mr-1"></em>Farmer Profile</h6>
              <p class="sub-text mb-0">All farmer details from the database record.</p>
            </div>
            <div class="card-inner border-top">
              <div class="details-grid">
                <div class="detail-item">
                  <span class="sub-text"><em class="icon ni ni-user mr-1"></em>First Name</span>
                  <strong>{{ farmer.first_name || 'N/A' }}</strong>
                </div>
                <div class="detail-item">
                  <span class="sub-text"><em class="icon ni ni-user mr-1"></em>Last Name</span>
                  <strong>{{ farmer.last_name || 'N/A' }}</strong>
                </div>
                <div class="detail-item">
                  <span class="sub-text"><em class="icon ni ni-users mr-1"></em>Gender</span>
                  <strong>{{ farmer.gender || 'N/A' }}</strong>
                </div>
                <div class="detail-item">
                  <span class="sub-text"><em class="icon ni ni-calendar mr-1"></em>Date of Birth</span>
                  <strong>{{ farmer.date_of_birth || 'N/A' }}</strong>
                </div>
                <div class="detail-item">
                  <span class="sub-text"><em class="icon ni ni-card-view mr-1"></em>National ID</span>
                  <strong>{{ farmer.national_id || 'N/A' }}</strong>
                </div>
                <div class="detail-item">
                  <span class="sub-text"><em class="icon ni ni-growth mr-1"></em>Primary Crop</span>
                  <strong>{{ farmer.primary_crop || 'N/A' }}</strong>
                </div>
                <div class="detail-item">
                  <span class="sub-text"><em class="icon ni ni-check-circle mr-1"></em>Status</span>
                  <strong>{{ farmer.status || 'N/A' }}</strong>
                </div>
                <div class="detail-item">
                  <span class="sub-text"><em class="icon ni ni-clock mr-1"></em>Created At</span>
                  <strong>{{ farmer.created_at || 'N/A' }}</strong>
                </div>
                <div class="detail-item">
                  <span class="sub-text"><em class="icon ni ni-update mr-1"></em>Updated At</span>
                  <strong>{{ farmer.updated_at || 'N/A' }}</strong>
                </div>
              </div>
            </div>

            <div class="card-inner border-top">
              <h6 class="title mb-2"><em class="icon ni ni-call mr-1"></em>Contact Information</h6>
              <div class="details-grid">
                <div class="detail-item">
                  <span class="sub-text"><em class="icon ni ni-call mr-1"></em>Phone Number</span>
                  <strong>{{ farmer.phone_number || 'N/A' }}</strong>
                </div>
                <div class="detail-item">
                  <span class="sub-text"><em class="icon ni ni-mail mr-1"></em>Email</span>
                  <strong>{{ farmer.email || 'N/A' }}</strong>
                </div>
                <div class="detail-item">
                  <span class="sub-text"><em class="icon ni ni-map-pin mr-1"></em>Village</span>
                  <strong>{{ farmer.village || 'N/A' }}</strong>
                </div>
                <div class="detail-item">
                  <span class="sub-text"><em class="icon ni ni-map mr-1"></em>Sub County</span>
                  <strong>{{ farmer.sub_county || 'N/A' }}</strong>
                </div>
                <div class="detail-item">
                  <span class="sub-text"><em class="icon ni ni-map-fill mr-1"></em>District</span>
                  <strong>{{ farmer.district || 'N/A' }}</strong>
                </div>
                <div class="detail-item detail-item-full">
                  <span class="sub-text"><em class="icon ni ni-navigation mr-1"></em>Combined Location</span>
                  <strong>{{ locationLabel }}</strong>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>






    <div v-if="showModal" class="modal show d-block farm-modal" tabindex="-1" role="dialog" aria-modal="true">
      <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable custom-modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Create Farm Profile</h5>
            <button type="button" class="btn-close" aria-label="Close" @click="closeModal"></button>
          </div>

          <div class="modal-body">
            <form class="row g-3 modal-form-grid" @submit.prevent="submit">
              <input v-model="form.cooperative_farmer_id" type="hidden" />
              <div class="col-12">
                <InputError :message="form.errors.cooperative_farmer_id" class="mt-0" />
              </div>

              <div class="col-12 col-md-6 modal-field">
                <label class="form-label">Farm/ Garden Name</label>
                <input v-model="form.farm_name" type="text" class="form-control" placeholder="Main farm name" />
                <InputError :message="form.errors.farm_name" class="mt-2" />
              </div>

              <div class="col-12 col-md-6 modal-field">
                <label class="form-label">Location</label>
                <input v-model="form.location" type="text" class="form-control" placeholder="Village, sub county, district" />
                <InputError :message="form.errors.location" class="mt-2" />
              </div>

              <div class="col-12 col-md-6 modal-field">
                <label class="form-label">Area (Acres)</label>
                <input v-model="form.area_acres" type="number" min="0" step="0.01" class="form-control" placeholder="0.00" />
                <InputError :message="form.errors.area_acres" class="mt-2" />
              </div>

              <div class="col-12 col-md-6 modal-field">
                <label class="form-label">No. of Gardens</label>
                <input v-model="form.number_of_gardens" type="number" min="0" step="1" class="form-control" placeholder="0" />
                <InputError :message="form.errors.number_of_gardens" class="mt-2" />
              </div>

              <div class="col-12 col-md-12 modal-field">
                <label class="form-label">Primary Crop</label>
                <input v-model="form.primary_crop" type="text" class="form-control" placeholder="Coffee, Maize..." />
                <InputError :message="form.errors.primary_crop" class="mt-2" />
              </div>

              <div class="col-12 col-md-6 modal-field">
                <label class="form-label">Soil Type</label>
                <input v-model="form.soil_type" type="text" class="form-control" placeholder="Loamy, Clay, Sandy..." />
                <InputError :message="form.errors.soil_type" class="mt-2" />
              </div>

              <div class="col-12 col-md-6 modal-field">
                <label class="form-label">Water Source Type</label>
                <input v-model="form.water_source_type" type="text" class="form-control" placeholder="Rainfed, Borehole, River..." />
                <InputError :message="form.errors.water_source_type" class="mt-2" />
              </div>


              <div class="col-12 d-flex  pt-2">
                <SubmitButton :title="'Save Farm'" :status="form.processing" />
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div v-if="showModal" class="modal-backdrop fade show"></div>
  </CooperativeLayout>
</template>

<style scoped>
.profile-card {
  overflow: hidden;
}

.profile-avatar {
  width: 72px;
  height: 72px;
  border-radius: 50%;
  margin: 0 auto;
  background: linear-gradient(135deg, #6f4e37, #8b5a2b);
  color: #fff;
  font-weight: 700;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: 1.25rem;
  text-transform: uppercase;
}

.profile-metric {
  display: flex;
  flex-direction: column;
  gap: 2px;
  padding: 10px 0;
  border-bottom: 1px solid #edf2f7;
}

.profile-metric:last-child {
  border-bottom: 0;
}

.details-grid {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 12px;
}

.detail-item {
  display: flex;
  flex-direction: column;
  gap: 4px;
  background: #f8fafc;
  border-radius: 10px;
  padding: 12px;
}

.detail-item-full {
  grid-column: span 2;
}

.custom-modal-dialog {
  max-width: 50%;
}

.modal-content {
  border: 1px solid #dbe3ed;
  border-radius: 14px;
}

.modal-body {
  height: 400px;
  overflow-y: auto;
  padding: 1rem 1.25rem;
}

.section-divider {
  padding-top: 6px;
  border-top: 1px solid #edf2f7;
}

.modal-form-grid .form-label {
  margin-bottom: 0.35rem;
}

.modal-field {
  display: flex;
  flex-direction: column;
}

@media (max-width: 991px) {
  .custom-modal-dialog {
    max-width: 95%;
  }
}

@media (max-width: 768px) {
  .details-grid {
    grid-template-columns: 1fr;
  }

  .detail-item-full {
    grid-column: span 1;
  }
}
</style>
