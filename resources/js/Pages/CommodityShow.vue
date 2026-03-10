<script setup>
import { computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';
import { Back } from '@element-plus/icons-vue';

const page = usePage();

const commodity = computed(() => page.props.commodity?.data ?? page.props.commodity ?? {});
const originFarms = computed(() => page.props.origin_farms ?? []);

const formatMoney = (value) => {
  const num = Number(value);
  if (Number.isNaN(num)) return value ?? 'N/A';
  return num.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
};

const statusClass = computed(() => {
  const status = String(commodity.value?.status ?? '').toLowerCase();
  if (status === 'listed') return 'badge bg-success-subtle text-success';
  if (status === 'sold') return 'badge bg-info-subtle text-info';
  if (status === 'negotiating') return 'badge bg-warning-subtle text-warning';
  return 'badge bg-light text-dark';
});

const goBack = () => {
  router.get(route('cooperative.produce'));
};

const goToOriginFarms = () => {
  if (!commodity.value?.id) return;
  router.get(route('commodity.origin-farms.create', { id: commodity.value.id }));
};

const goToOriginFarmDetails = (farmId) => {
  if (!commodity.value?.id || !farmId) return;
  router.get(route('commodity.origin-farms.show', {
    commodity: commodity.value.id,
    farm: farmId,
  }));
};
</script>

<template>
  <CooperativeLayout>
    <div class="container">
      <div class="card card-bordered commodity-show-shell">
        <div class="card-inner border-bottom header-row">
          <div>
            <h6 class="title mb-1"><em class="icon ni ni-bag mr-1"></em>Commodity #{{ commodity.id }}</h6>
            <p class="sub-text mb-0">Detailed commodity information and linked origin farms.</p>
          </div>

          <div class="header-actions">

            <el-button-group>
              <el-button :icon="Back" @click="goBack">Back</el-button>
              <el-button  @click="goToOriginFarms" v-if="originFarms.length==0"> Manage Origin Farms</el-button>
            </el-button-group>
          </div>
        </div>

        <div class="card-inner">
          <div class="details-grid">
            <div class="detail-item">
              <span class="sub-text">Commodity Name</span>
              <strong>{{ commodity.commodity_name ?? 'N/A' }}</strong>
            </div>
            <div class="detail-item">
              <span class="sub-text">Commodity Type</span>
              <strong>{{ commodity.commodity_type ?? 'N/A' }}</strong>
            </div>
            <div class="detail-item">
              <span class="sub-text">Grade</span>
              <strong>{{ commodity.grade ?? 'N/A' }}</strong>
            </div>
            <div class="detail-item">
              <span class="sub-text">Weight</span>
              <strong>{{ commodity.weight ?? 'N/A' }} kg</strong>
            </div>
            <div class="detail-item">
              <span class="sub-text">Price</span>
              <strong>UGX {{ formatMoney(commodity.price) }}</strong>
            </div>
            <div class="detail-item">
              <span class="sub-text">Date of Harvest</span>
              <strong>{{ commodity.harvest_date ?? 'N/A' }}</strong>
            </div>
            <div class="detail-item detail-item-full">
              <span class="sub-text">Status</span>
              <strong><span :class="statusClass">{{ commodity.status ?? 'created' }}</span></strong>
            </div>
          </div>
        </div>

        <div class="card-inner border-top">
          <div class="d-flex align-items-center justify-content-between mb-2">
            <h6 class="title mb-0"><em class="icon ni ni-map-pin mr-1"></em>Origin Farms</h6>
            <span class="sub-text">{{ originFarms.length }} linked</span>
          </div>

          <div v-if="originFarms.length" class="table-responsive origin-table-wrap">
            <table class="table table-sm table-middle mb-0 origin-table border">
              <thead>
                <tr>
                  <th style="width: 100px;">Farm Name</th>
                  <th>Location</th>
                  <th>Area (Acres)</th>
                  <th>Primary Crop</th>
                  <th>Soil Type</th>
                  <th>Water Source</th>
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
                  <td>{{ farm.primary_crop ?? 'N/A' }}</td>
                  <td>{{ farm.soil_type ?? 'N/A' }}</td>
                  <td>{{ farm.water_source_type ?? 'N/A' }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div v-else class="empty-origin">
            No origin farms linked yet.
          </div>
        </div>
      </div>
    </div>
  </CooperativeLayout>
</template>

<style scoped>
.commodity-show-shell {
  overflow: hidden;
}

.header-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  flex-wrap: wrap;
}

.header-actions {
  display: flex;
  align-items: center;
  gap: 8px;
}

.details-grid {
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: 12px;
}

.detail-item {
  background: #f8fafc;
  border-radius: 10px;
  padding: 12px;
  display: flex;
  flex-direction: column;
  gap: 3px;
}

.detail-item-full {
  grid-column: span 3;
}

.origin-table th {
  background: #f8fafc;
  color: #526484;
  font-weight: 600;
  white-space: nowrap;
  border-bottom: 1px solid #e5e9f2;
  padding-top: 10px;
  padding-bottom: 10px;
}

.origin-table td {
  color: #364a63;
  vertical-align: middle;
  white-space: nowrap;
  border-top: 1px solid #edf1f7;
  padding-top: 10px;
  padding-bottom: 10px;
}

.origin-table-wrap {
  border: 1px solid #e5e9f2;
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
  border: 1px dashed #d9e2f0;
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

@media (max-width: 767px) {
  .header-actions {
    width: 100%;
  }

  .header-actions :deep(.el-button-group) {
    display: flex;
    width: 100%;
  }

  .header-actions :deep(.el-button) {
    flex: 1;
  }

  .details-grid {
    grid-template-columns: 1fr;
  }

  .detail-item-full {
    grid-column: span 1;
  }
}
</style>
