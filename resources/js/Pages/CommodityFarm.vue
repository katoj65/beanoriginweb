<script setup>
import { computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';
import { Back } from '@element-plus/icons-vue';

const page = usePage();

const commodity = computed(() => page.props.commodity?.data ?? page.props.commodity ?? {});
const farm = computed(() => page.props.farm?.data ?? page.props.farm ?? {});
const owner = computed(() => page.props.owner?.data ?? page.props.owner ?? {});

const ownerFullName = computed(() => {
  if (owner.value?.full_name) return owner.value.full_name;
  return [owner.value?.first_name, owner.value?.last_name].filter(Boolean).join(' ') || 'N/A';
});

const ownerLocation = computed(() => {
  return [owner.value?.village, owner.value?.sub_county, owner.value?.district].filter(Boolean).join(', ') || 'N/A';
});

const statusClass = computed(() => {
  const status = String(commodity.value?.status ?? '').toLowerCase();
  if (status === 'listed') return 'badge bg-success-subtle text-success';
  if (status === 'sold') return 'badge bg-info-subtle text-info';
  if (status === 'negotiating') return 'badge bg-warning-subtle text-warning';
  return 'badge bg-light text-dark';
});

const formatMoney = (value) => {
  const num = Number(value);
  if (Number.isNaN(num)) return value ?? 'N/A';
  return num.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
};

const goBack = () => {
  if (!commodity.value?.id) {
    router.get(route('cooperative.produce'));
    return;
  }

  router.get(route('commodity.show', { id: commodity.value.id }));
};
</script>

<template>
  <CooperativeLayout>
    <div class="container">
      <div class="card card-bordered farm-details-shell">
        <div class="card-inner border-bottom header-row">
          <div>
            <h6 class="title mb-1"><em class="icon ni ni-map-pin mr-1"></em>Origin Farm Details</h6>
            <p class="sub-text mb-0">Farm and farmer details for commodity harvest #{{ commodity.id ?? 'N/A' }}.</p>
          </div>

          <div class="header-actions">
            <el-button :icon="Back" @click="goBack">Back to Harvest</el-button>
          </div>
        </div>

        <div class="card-inner border-bottom">
          <h6 class="title mb-3"><em class="icon ni ni-bag mr-1"></em>Harvest Summary</h6>
          <div class="details-grid">
            <div class="detail-item">
                  <span class="sub-text detail-label"><em class="icon ni ni-growth mr-1"></em>Commodity Name</span>
                  <strong>{{ commodity.commodity_name ?? 'N/A' }}</strong>
                </div>
                <div class="detail-item">
                  <span class="sub-text detail-label"><em class="icon ni ni-box-view mr-1"></em>Commodity Type</span>
                  <strong>{{ commodity.commodity_type ?? 'N/A' }}</strong>
                </div>
                <div class="detail-item">
                  <span class="sub-text detail-label"><em class="icon ni ni-award mr-1"></em>Grade</span>
                  <strong>{{ commodity.grade ?? 'N/A' }}</strong>
                </div>
                <div class="detail-item">
                  <span class="sub-text detail-label"><em class="icon ni ni-package mr-1"></em>Weight</span>
                  <strong>{{ commodity.weight ?? 'N/A' }} kg</strong>
                </div>
                <div class="detail-item">
                  <span class="sub-text detail-label"><em class="icon ni ni-coins mr-1"></em>Price</span>
                  <strong>UGX {{ formatMoney(commodity.price) }}</strong>
                </div>
                <div class="detail-item">
                  <span class="sub-text detail-label"><em class="icon ni ni-calendar mr-1"></em>Date of Harvest</span>
                  <strong>{{ commodity.harvest_date ?? 'N/A' }}</strong>
                </div>
                <div class="detail-item detail-item-full">
                  <span class="sub-text detail-label"><em class="icon ni ni-flag mr-1"></em>Status</span>
                  <strong><span :class="statusClass">{{ commodity.status ?? 'created' }}</span></strong>
                </div>
              </div>
        </div>

        <div class="card-inner">
          <div class="row g-gs">
            <div class="col-12 col-lg-7">
              <div class="panel-box">
                <h6 class="title mb-3"><em class="icon ni ni-home mr-1"></em>Farm Information</h6>
                <div class="details-grid">
                  <div class="detail-item detail-item-full">
                    <span class="sub-text detail-label"><em class="icon ni ni-home-fill mr-1"></em>Farm Name</span>
                    <strong>{{ farm.farm_name ?? 'N/A' }}</strong>
                  </div>
                  <div class="detail-item detail-item-full">
                    <span class="sub-text detail-label"><em class="icon ni ni-map-pin mr-1"></em>Location</span>
                    <strong>{{ farm.location ?? 'N/A' }}</strong>
                  </div>
                  <div class="detail-item">
                    <span class="sub-text detail-label"><em class="icon ni ni-layers mr-1"></em>Area (Acres)</span>
                    <strong>{{ farm.area_acres ?? 'N/A' }}</strong>
                  </div>
                  <div class="detail-item">
                    <span class="sub-text detail-label"><em class="icon ni ni-link-alt mr-1"></em>Latitude</span>
                    <strong>{{ farm.latitude ?? 'N/A' }}</strong>
                  </div>
                  <div class="detail-item">
                    <span class="sub-text detail-label"><em class="icon ni ni-link-alt mr-1"></em>Longitude</span>
                    <strong>{{ farm.longitude ?? 'N/A' }}</strong>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-12 col-lg-5">
              <div class="panel-box">
                <h6 class="title mb-3"><em class="icon ni ni-user mr-1"></em>Farmer Information</h6>
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
                    <span class="sub-text detail-label"><em class="icon ni ni-call mr-1"></em>Phone Number</span>
                    <strong>{{ owner.phone_number || 'N/A' }}</strong>
                  </div>
                  <div class="detail-item">
                    <span class="sub-text detail-label"><em class="icon ni ni-mail mr-1"></em>Email</span>
                    <strong>{{ owner.email || 'N/A' }}</strong>
                  </div>
                  <div class="detail-item">
                    <span class="sub-text detail-label"><em class="icon ni ni-card-view mr-1"></em>National ID</span>
                    <strong>{{ owner.national_id || 'N/A' }}</strong>
                  </div>
                  <div class="detail-item">
                    <span class="sub-text detail-label"><em class="icon ni ni-user mr-1"></em>Gender</span>
                    <strong>{{ owner.gender || 'N/A' }}</strong>
                  </div>
                  <div class="detail-item">
                    <span class="sub-text detail-label"><em class="icon ni ni-calendar mr-1"></em>Date of Birth</span>
                    <strong>{{ owner.date_of_birth || 'N/A' }}</strong>
                  </div>
                  <div class="detail-item">
                    <span class="sub-text detail-label"><em class="icon ni ni-map-pin mr-1"></em>Address</span>
                    <strong>{{ ownerLocation }}</strong>
                  </div>
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
.farm-details-shell {
  overflow: hidden;
}

.header-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  flex-wrap: wrap;
}

.details-grid {
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
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

.detail-label {
  display: inline-flex;
  align-items: center;
}

.detail-item-full {
  grid-column: span 3;
}

.panel-box {
  border: 1px solid #e5e9f2;
  border-radius: 10px;
  padding: 14px;
  height: 100%;
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

@media (max-width: 767px) {
  .header-actions {
    width: 100%;
  }

  .header-actions :deep(.el-button) {
    width: 100%;
  }

  .details-grid {
    grid-template-columns: 1fr;
  }

  .detail-item-full {
    grid-column: span 1;
  }
}
</style>
