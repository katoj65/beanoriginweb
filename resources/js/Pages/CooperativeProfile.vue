<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';

const page = usePage();

const cooperative = computed(() => page.props.cooperative ?? null);
const user = computed(() => page.props.user ?? null);

const initials = computed(() => {
  const name = cooperative.value?.name || cooperative.value?.legal_name || 'CO';
  return name
    .split(' ')
    .map((n) => n[0])
    .join('')
    .slice(0, 2)
    .toUpperCase();
});
</script>

<template>
<CooperativeLayout>
<div class="container">
  <div class="row g-gs">
    <div class="col-12 col-lg-8">
      <div class="card card-bordered mb-3">
        <div class="card-inner profile-hero">
          <div class="profile-hero-inner">
            <div class="profile-hero-left">
              <div class="profile-avatar">{{ initials }}</div>
              <div class="profile-hero-copy">
                <h4 class="mb-1">{{ cooperative?.legal_name || 'Cooperative Profile' }}</h4>
                <p class="sub-text mb-0">
                  {{ cooperative?.name || 'Trading name not provided' }}
                </p>
              </div>
            </div>

            <div class="profile-hero-badges">
              <span class="badge rounded-pill bg-success">Active</span>
              <span class="badge rounded-pill bg-light text-dark">Verified Cooperative</span>
            </div>
          </div>
        </div>

        <div class="card-inner">
          <h6 class="title mb-1">Organization Details</h6>
          <p class="sub-text mb-0">Core registration and location information.</p>
        </div>
        <div class="card-inner">
          <div class="details-grid">
            <div class="detail-item detail-tile">
              <span class="sub-text tile-label"><em class="icon ni ni-building mr-1"></em>Legal Name</span>
              <strong class="tile-value">{{ cooperative?.legal_name || 'N/A' }}</strong>
            </div>
            <div class="detail-item detail-tile">
              <span class="sub-text tile-label"><em class="icon ni ni-briefcase mr-1"></em>Trading Name</span>
              <strong class="tile-value">{{ cooperative?.name || 'N/A' }}</strong>
            </div>
            <div class="detail-item detail-tile">
              <span class="sub-text tile-label"><em class="icon ni ni-file-docs mr-1"></em>Registration Number</span>
              <strong class="tile-value">{{ cooperative?.reg_num || 'N/A' }}</strong>
            </div>
            <div class="detail-item detail-tile">
              <span class="sub-text tile-label"><em class="icon ni ni-calendar mr-1"></em>Registration Date</span>
              <strong class="tile-value">{{ cooperative?.reg_date || 'N/A' }}</strong>
            </div>
            <div class="detail-item detail-tile">
              <span class="sub-text tile-label"><em class="icon ni ni-map-pin mr-1"></em>District</span>
              <strong class="tile-value">{{ cooperative?.district || 'N/A' }}</strong>
            </div>
            <div class="detail-item detail-tile">
              <span class="sub-text tile-label"><em class="icon ni ni-link mr-1"></em>Website</span>
              <strong class="tile-value">{{ cooperative?.website || 'N/A' }}</strong>
            </div>
          </div>
        </div>
      </div>

      <div class="card card-bordered">
        <div class="card-inner border-bottom">
          <h6 class="title mb-1">Address Information</h6>
          <p class="sub-text mb-0">Physical and postal contact addresses.</p>
        </div>
        <div class="card-inner">
          <div class="detail-item mb-3">
            <span class="sub-text">Physical Address</span>
            <strong>{{ cooperative?.physical_address || 'N/A' }}</strong>
          </div>
          <div class="detail-item">
            <span class="sub-text">Postal Address</span>
            <strong>{{ cooperative?.postal_address || 'N/A' }}</strong>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-lg-4">
      <div class="card card-bordered mb-3">
        <div class="card-inner border-bottom">
          <h6 class="title mb-1">Contact Channels</h6>
          <p class="sub-text mb-0">Official communication details.</p>
        </div>
        <div class="card-inner">
          <div class="contact-row">
            <em class="icon ni ni-mail-fill"></em>
            <span>{{ cooperative?.email || 'N/A' }}</span>
          </div>
          <div class="contact-row">
            <em class="icon ni ni-call-fill"></em>
            <span>{{ cooperative?.tel || 'N/A' }}</span>
          </div>
          <div class="contact-row">
            <em class="icon ni ni-user-fill"></em>
            <span>{{ user?.fname }} {{ user?.lname }}</span>
          </div>
        </div>
      </div>

      <div class="card card-bordered">
        <div class="card-inner border-bottom">
          <h6 class="title mb-1">Profile Health</h6>
          <p class="sub-text mb-0">Completion and quality indicators.</p>
        </div>
        <div class="card-inner">
          <div class="health-item">
            <span>Data Completion</span>
            <span class="text-success">92%</span>
          </div>
          <el-progress :percentage="92" :stroke-width="8" color="#10b981" />

          <div class="health-item mt-3">
            <span>Verification Status</span>
            <span class="text-primary">In Review</span>
          </div>
          <el-progress :percentage="68" :stroke-width="8" color="#3b82f6" />
        </div>
      </div>
    </div>
  </div>
</div>
</CooperativeLayout>
</template>

<style scoped>
.profile-hero {
  background: linear-gradient(135deg, #ffffff 0%, #f8fafc 65%, #eef2ff 100%);
}

.profile-hero-inner {
  display: grid;
  grid-template-columns: 1fr auto;
  align-items: center;
  gap: 0.55rem 0.9rem;
}

.profile-hero-left {
  display: flex;
  align-items: center;
  gap: 0.65rem;
  min-width: 0;
}

.profile-hero-copy h4 {
  line-height: 1.2;
}

.profile-hero-badges {
  display: flex;
  flex-wrap: wrap;
  justify-content: flex-end;
  gap: 0.5rem;
}

.profile-avatar {
  width: 3rem;
  height: 3rem;
  border-radius: 999px;
  background: linear-gradient(135deg, #6f4e37, #8b5a2b);
  color: #fff;
  font-weight: 700;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

.details-grid {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 12px;
}

.detail-item {
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.detail-tile {
  background: #f8fafc;
  border: 0;
  border-radius: 12px;
  padding: 10px 12px;
}

.tile-label {
  display: inline-flex;
  align-items: center;
  color: #64748b;
}

.tile-value {
  margin-top: 2px;
  color: #0f172a;
  font-weight: 600;
}

.contact-row {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 8px 0;
  border-bottom: 1px solid #eef2f7;
}

.contact-row:last-child {
  border-bottom: 0;
}

.health-item {
  display: flex;
  justify-content: space-between;
  margin-bottom: 8px;
  font-weight: 500;
}

@media (max-width: 768px) {
  .profile-hero-inner {
    grid-template-columns: 1fr;
  }

  .profile-hero-badges {
    justify-content: flex-start;
  }

  .details-grid {
    grid-template-columns: 1fr;
  }
}
</style>
