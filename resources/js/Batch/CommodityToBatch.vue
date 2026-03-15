<script setup>
import { computed } from 'vue';
import { DataAnalysis, ScaleToOriginal } from '@element-plus/icons-vue';

const props = defineProps({
  commodities: {
    type: Array,
    default: () => [],
  },
});

const linkedCommodities = computed(() => (Array.isArray(props.commodities) ? props.commodities : []));

const formatHarvestDate = (value) => {
  if (!value) return 'N/A';
  const date = new Date(value);
  if (Number.isNaN(date.getTime())) return value;
  return date.toLocaleDateString();
};

const formatFarmerName = (farmDetails) => {
  const fullName = `${farmDetails?.first_name ?? ''} ${farmDetails?.last_name ?? ''}`.trim();
  return fullName || 'N/A';
};

const farmerInitials = (farmDetails) => {
  const initials = [farmDetails?.first_name, farmDetails?.last_name]
    .filter(Boolean)
    .map((value) => value.trim().charAt(0).toUpperCase())
    .join('');

  return initials || 'NA';
};
</script>

<template>
  <div class="card-inner m-0 p-0">
    <div class="prompt-plain">
      <div class="prompt-head">
        <div class="prompt-copy">
          <h6 class="title mb-0">Harvests Data</h6>
          <p class="prompt-subtitle mb-0">Verified commodities linked to this batch.</p>
        </div>
      </div>
    </div>

    <div v-if="linkedCommodities.length" class="table-responsive linked-table-wrap mt-3">
      <table class="table table-sm table-middle mb-0 linked-table">
        <thead>
          <tr>
            <th><span class="head-label"><em class="icon ni ni-package"></em>Commodity Name</span></th>
            <th><span class="head-label"><em class="icon ni ni-medal"></em>Grade</span></th>
            <th>
              <span class="head-label">
                <el-icon><ScaleToOriginal /></el-icon>
                Weight
              </span>
            </th>
            <th><span class="head-label"><em class="icon ni ni-growth"></em>Ripe %</span></th>
            <th>
              <span class="head-label">
                <el-icon><DataAnalysis /></el-icon>
                Density %
              </span>
            </th>
            <th><span class="head-label"><em class="icon ni ni-home-fill"></em>Farm</span></th>
            <th><span class="head-label"><em class="icon ni ni-user"></em>Farmer</span></th>
            <th><span class="head-label"><em class="icon ni ni-calendar"></em>Harvested</span></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in linkedCommodities" :key="item.id">
            <td class="text-capitalize">{{ item.commodity_name ?? 'N/A' }}
           -  {{ item.commodity_type ?? 'N/A' }}
            </td>

            <td class="text-capitalize">{{ item.grade ?? 'N/A' }}</td>
            <td>{{ item.weight ?? 'N/A' }} kg</td>
            <td>{{ item.ripe_percentage === null || item.ripe_percentage === undefined ? 'N/A' : `${item.ripe_percentage}%` }}</td>
            <td>{{ item.density_percentage === null || item.density_percentage === undefined ? 'N/A' : `${item.density_percentage}%` }}</td>
            <td class="text-capitalize">{{ item.farm_details?.farm_name ?? 'N/A' }}</td>
            <td class="text-capitalize">
              <span class="activity-cell">
                <span class="farmer-avatar">{{ farmerInitials(item.farm_details) }}</span>
                {{ formatFarmerName(item.farm_details) }}
              </span>
            </td>
            <td>{{ formatHarvestDate(item.harvest_date) }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-else class="empty-linked mt-3">
      No commodities linked to this batch yet.
    </div>

  </div>
</template>

<style scoped>
.prompt-plain {
  padding: 0;
}

.prompt-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  flex-wrap: wrap;
  padding-bottom: 6px;
}

.prompt-copy {
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.prompt-subtitle {
  color: #8094ae;
  font-size: 12px;
  line-height: 1.35;
}

.linked-table-wrap {
  border: 1px solid #e5e9f2;
  border-radius: 10px;
  overflow: hidden;
  background: #fff;
}

.linked-table th {
  background: #f8fafc;
  color: #526484;
  font-weight: 600;
  white-space: nowrap;
}

.head-label {
  display: inline-flex;
  align-items: center;
  gap: 6px;
}

.head-label .icon {
  color: #8094ae;
  font-size: 13px;
}

.activity-cell {
  display: inline-flex;
  align-items: center;
  gap: 6px;
}

.farmer-avatar {
  width: 24px;
  height: 24px;
  border-radius: 999px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  background: #e9f2ff;
  color: #2d6cdf;
  font-size: 10px;
  font-weight: 700;
  letter-spacing: 0.04em;
  text-transform: uppercase;
}

.linked-table td {
  color: #364a63;
  white-space: nowrap;
}

.empty-linked {
  border: 1px dashed #d9e2f0;
  border-radius: 10px;
  padding: 12px;
  color: #64748b;
  background: #f8fafc;
}
</style>
