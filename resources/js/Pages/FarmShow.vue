<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';

const page = usePage();
const farm = computed(() => page.props.farm?.data ?? page.props.farm ?? {});
const owner = computed(() => page.props.owner?.data ?? page.props.owner ?? {});

const ownerFullName = computed(() => {
  if (owner.value?.full_name) return owner.value.full_name;
  return [owner.value?.first_name, owner.value?.last_name].filter(Boolean).join(' ') || 'N/A';
});

const ownerLocation = computed(() => {
  return [owner.value?.village, owner.value?.sub_county, owner.value?.district].filter(Boolean).join(', ') || 'N/A';
});
</script>

<template>
  <CooperativeLayout>
    <div class="container">


      <div class="row g-gs">
        <div class="col-12 col-lg-8">
          <div class="card card-bordered h-100">
            <div class="card-inner border-bottom">
              <h6 class="title mb-1"><em class="icon ni ni-home mr-1"></em>Farm Information</h6>
              <p class="sub-text mb-0">Core details about the farm.</p>
            </div>
            <div class="card-inner">
              <div class="details-grid">
                <div class="detail-item detail-item-full">
                  <span class="sub-text"><em class="icon ni ni-home mr-1"></em>Farm Name</span>
                  <strong>{{ farm.farm_name || 'N/A' }}</strong>
                </div>
                <div class="detail-item detail-item-full">
                  <span class="sub-text"><em class="icon ni ni-map-pin mr-1"></em>Location</span>
                  <strong>{{ farm.location || 'N/A' }}</strong>
                </div>
                <div class="detail-item">
                  <span class="sub-text"><em class="icon ni ni-grid-alt mr-1"></em>Area (Acres)</span>
                  <strong>{{ farm.area_acres || '0' }}</strong>
                </div>
                <div class="detail-item">
                  <span class="sub-text"><em class="icon ni ni-growth mr-1"></em>Primary Crop</span>
                  <strong>{{ farm.primary_crop || 'N/A' }}</strong>
                </div>
                <div class="detail-item">
                  <span class="sub-text"><em class="icon ni ni-sun-fill mr-1"></em>Soil Type</span>
                  <strong>{{ farm.soil_type || 'N/A' }}</strong>
                </div>
                <div class="detail-item">
                  <span class="sub-text"><em class="icon ni ni-dropbox mr-1"></em>Water Source Type</span>
                  <strong>{{ farm.water_source_type || 'N/A' }}</strong>
                </div>
                <div class="detail-item">
                  <span class="sub-text"><em class="icon ni ni-navigation mr-1"></em>Latitude</span>
                  <strong>{{ farm.latitude ?? 'N/A' }}</strong>
                </div>
                <div class="detail-item">
                  <span class="sub-text"><em class="icon ni ni-navigation-fill mr-1"></em>Longitude</span>
                  <strong>{{ farm.longitude ?? 'N/A' }}</strong>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12 col-lg-4">
          <div class="card card-bordered h-100">
            <div class="card-inner border-bottom">
              <h6 class="title mb-1"><em class="icon ni ni-user mr-1"></em>Owner Information</h6>
              <p class="sub-text mb-0">Farmer details linked to this farm.</p>
            </div>
            <div class="card-inner">
              <div class="owner-hero mb-3">
                <div class="owner-avatar">
                  {{ ownerFullName.slice(0, 2).toUpperCase() }}
                </div>
                <div>
                  <h6 class="mb-1">{{ ownerFullName }}</h6>
                  <p class="sub-text mb-0">{{ owner.status || 'N/A' }}</p>
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
  </CooperativeLayout>
</template>

<style scoped>
.details-grid {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 12px;
}

.details-grid-single {
  grid-template-columns: 1fr;
}

.detail-item {
  background: #f8fafc;
  border-radius: 10px;
  padding: 12px;
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.detail-item-full {
  grid-column: span 2;
}

.owner-hero {
  display: flex;
  align-items: center;
  gap: 10px;
}

.owner-avatar {
  width: 42px;
  height: 42px;
  border-radius: 50%;
  background: linear-gradient(135deg, #6f4e37, #8b5a2b);
  color: #fff;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: 0.8rem;
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
