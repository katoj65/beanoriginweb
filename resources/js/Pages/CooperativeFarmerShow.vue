<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';
import { ElMessageBox } from 'element-plus';

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

const promptAddFarmDetails = () => {
  ElMessageBox.confirm(
    'Would you like to add farm and garden details for this farmer?',
    'Add Farm Details',
    {
      confirmButtonText: 'Yes, Add Details',
      cancelButtonText: 'Cancel',
      type: 'info',
    }
  );
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
                <span class="sub-text">Registered At</span>
                <strong>{{ farmer.created_at || 'N/A' }}</strong>
              </div>
              <div class="mt-3">
                <button type="button" class="btn btn-light btn-sm w-100" @click="promptAddFarmDetails">
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
              <p class="sub-text mb-0">Personal, contact, and farm details.</p>
            </div>

            <div class="card-inner section-block">
              <h6 class="section-title"><em class="icon ni ni-id-card mr-1"></em>Personal Information</h6>
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
              </div>
            </div>

            <div class="card-inner border-top section-block">
              <h6 class="section-title"><em class="icon ni ni-map-pin mr-1"></em>Contact & Location</h6>
              <div class="details-grid">
                <div class="detail-item">
                  <span class="sub-text"><em class="icon ni ni-call mr-1"></em>Phone Number</span>
                  <strong>{{ farmer.phone_number || 'N/A' }}</strong>
                </div>
                <div class="detail-item">
                  <span class="sub-text"><em class="icon ni ni-mail mr-1"></em>Email</span>
                  <strong>{{ farmer.email || 'N/A' }}</strong>
                </div>
                <div class="detail-item detail-item-full">
                  <span class="sub-text"><em class="icon ni ni-map mr-1"></em>Location</span>
                  <strong>{{ locationLabel }}</strong>
                </div>
              </div>
            </div>

            <div class="card-inner border-top section-block">
              <h6 class="section-title"><em class="icon ni ni-leaf mr-1"></em>Farms & Gardens</h6>
              <div class="details-grid">
                <div class="detail-item">
                  <span class="sub-text"><em class="icon ni ni-growth mr-1"></em>Primary Crop</span>
                  <strong>{{ farmer.primary_crop || 'N/A' }}</strong>
                </div>
                <div class="detail-item">
                  <span class="sub-text"><em class="icon ni ni-map-pin mr-1"></em>Main Farm/Garden Area</span>
                  <strong>{{ locationLabel }}</strong>
                </div>
                <div class="detail-item">
                  <span class="sub-text"><em class="icon ni ni-grid-alt mr-1"></em>Number of Gardens</span>
                  <strong>N/A</strong>
                </div>
                <div class="detail-item">
                  <span class="sub-text"><em class="icon ni ni-ruler mr-1"></em>Total Farm Size</span>
                  <strong>N/A</strong>
                </div>
                <div class="detail-item detail-item-full">
                  <span class="sub-text"><em class="icon ni ni-notes mr-1"></em>Farm Notes</span>
                  <strong>No additional farm or garden details recorded yet.</strong>
                </div>
              </div>
            </div>

            <div class="card-inner border-top section-block">
              <h6 class="section-title"><em class="icon ni ni-shield-check mr-1"></em>Sustainability Info</h6>
              <div class="details-grid">
                <div class="detail-item">
                  <span class="sub-text"><em class="icon ni ni-recycle mr-1"></em>Soil Conservation</span>
                  <strong>N/A</strong>
                </div>
                <div class="detail-item">
                  <span class="sub-text"><em class="icon ni ni-drop mr-1"></em>Water Management</span>
                  <strong>N/A</strong>
                </div>
                <div class="detail-item">
                  <span class="sub-text"><em class="icon ni ni-leaf-fill mr-1"></em>Organic Practices</span>
                  <strong>N/A</strong>
                </div>
                <div class="detail-item">
                  <span class="sub-text"><em class="icon ni ni-sun mr-1"></em>Shade Tree Coverage</span>
                  <strong>N/A</strong>
                </div>
                <div class="detail-item detail-item-full">
                  <span class="sub-text"><em class="icon ni ni-flag mr-1"></em>Sustainability Notes</span>
                  <strong>No sustainability assessment data recorded yet.</strong>
                </div>
              </div>
            </div>

            <div class="card-inner border-top section-block">
              <h6 class="section-title"><em class="icon ni ni-coins mr-1"></em>Credit & Loan Profile</h6>
              <div class="details-grid">
                <div class="detail-item">
                  <span class="sub-text"><em class="icon ni ni-chart-growth mr-1"></em>Farmer Credit Score</span>
                  <strong>N/A</strong>
                </div>
                <div class="detail-item">
                  <span class="sub-text"><em class="icon ni ni-check-circle mr-1"></em>Loan Eligibility</span>
                  <strong>Pending Assessment</strong>
                </div>
                <div class="detail-item">
                  <span class="sub-text"><em class="icon ni ni-wallet mr-1"></em>Recommended Loan Limit</span>
                  <strong>N/A</strong>
                </div>
                <div class="detail-item">
                  <span class="sub-text"><em class="icon ni ni-calendar-alt mr-1"></em>Last Credit Review</span>
                  <strong>N/A</strong>
                </div>
                <div class="detail-item detail-item-full">
                  <span class="sub-text"><em class="icon ni ni-note-add mr-1"></em>Credit Notes</span>
                  <strong>No credit scoring data recorded yet.</strong>
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

.section-block {
  background: #fff;
}

.section-title {
  margin-bottom: 12px;
  font-weight: 600;
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

@media (max-width: 768px) {
  .details-grid {
    grid-template-columns: 1fr;
  }

  .detail-item-full {
    grid-column: span 1;
  }
}
</style>
