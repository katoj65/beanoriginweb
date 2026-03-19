<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';

const page = usePage();
const farms = computed(() => page.props.farms ?? []);
const can = computed(() => page.props.can ?? {});

const formatArea = (value) => {
  const number = Number(value ?? 0);
  if (Number.isNaN(number)) return value || '0';
  return number.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
};

const formatDate = (value) => {
  if (!value) return 'N/A';
  const date = new Date(value);
  if (Number.isNaN(date.getTime())) return value;
  return date.toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: '2-digit' });
};
</script>

<template>
  <CooperativeLayout>

    <div class="container">
      <div class="card farm-page-card">
        <div class="card-inner border-bottom farm-page-head">
          <div>
            <h4 class="mb-1 font-large text-capitalize">Farms</h4>
            <p class="sub-text mb-0">Registered farms for your cooperative.</p>
          </div>
          <Link v-if="can.is_cooperative" :href="route('farm.create')" class="btn btn-primary">
            <em class="icon ni ni-plus mr-1"></em>Add Farm
          </Link>
        </div>

        <div class="card-inner">
          <div v-if="farms.length === 0" class="farm-empty">
            <div class="farm-empty-icon"><em class="icon ni ni-map-pin"></em></div>
            <h6 class="mb-1">No farms found.</h6>
            <p class="sub-text mb-0">Create a farm to start building your farm directory.</p>
          </div>

          <div v-else class="farm-grid">
            <Link
              v-for="farm in farms"
              :key="farm.id"
              :href="route('farm.show', { id: farm.id })"
              class="farm-card-item"
            >
              <div class="farm-card-top">
                <h6 class="mb-0 text-capitalize">{{ farm.farm_name || 'N/A' }}</h6>
                <span :class="['farm-status-badge', { active: farm.latitude && farm.longitude }]">
                  {{ farm.latitude && farm.longitude ? 'Mapped' : 'Pending' }}
                </span>
              </div>

              <div class="farm-card-meta">
                <span><em class="icon ni ni-location mr-1"></em>{{ farm.location || 'Location not added' }}</span>
                <span><em class="icon ni ni-property mr-1"></em>{{ formatArea(farm.area_acres) }} acres</span>
                <span><em class="icon ni ni-user mr-1"></em>{{ farm.farmer_name }}</span>
              </div>

              <div class="farm-card-footer">
                <span><em class="icon ni ni-calendar mr-1"></em>{{ formatDate(farm.created_at) }}</span>
                <span class="farm-card-link">Open Farm <em class="icon ni ni-arrow-right"></em></span>
              </div>
            </Link>
          </div>
        </div>
      </div>
    </div>
  </CooperativeLayout>
</template>

<style scoped>
.farm-page-card {
  border: 1px solid #e7edf5 !important;
  border-radius: 18px;
  background: linear-gradient(180deg, #ffffff 0%, #fbfcfe 100%);
}

.farm-page-head {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 14px;
}

.farm-empty {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 10px;
  min-height: 280px;
  border: 1px dashed #dbe4f0;
  border-radius: 18px;
  background: #fbfcfe;
  text-align: center;
}

.farm-empty-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 52px;
  height: 52px;
  border-radius: 16px;
  background: #eef4ff;
  color: #2563eb;
  font-size: 20px;
}

.farm-grid {
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: 14px;
}

.farm-card-item {
  display: flex;
  flex-direction: column;
  gap: 14px;
  padding: 16px;
  border: 1px solid #e7edf5;
  border-radius: 18px;
  background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
  color: inherit;
  text-decoration: none;
  transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
}

.farm-card-item:hover {
  color: inherit;
  text-decoration: none;
  transform: translateY(-2px);
  border-color: #d7e3f4;
  box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
}

.farm-card-top {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 10px;
}

.farm-status-badge {
  display: inline-flex;
  align-items: center;
  padding: 5px 10px;
  border-radius: 999px;
  background: #fef3c7;
  color: #92400e;
  font-size: 11px;
  font-weight: 700;
  flex-shrink: 0;
}

.farm-status-badge.active {
  background: #ecfdf3;
  color: #166534;
}

.farm-card-meta {
  display: grid;
  gap: 8px;
  color: #526484;
  font-size: 13px;
}

.farm-card-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 10px;
  padding-top: 10px;
  border-top: 1px solid #eef2f7;
  color: #8094ae;
  font-size: 12px;
}

.farm-card-link {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  color: #2563eb;
  font-weight: 700;
}

@media (max-width: 991px) {
  .farm-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}

@media (max-width: 767px) {
  .farm-page-head {
    flex-direction: column;
  }

  .farm-grid {
    grid-template-columns: 1fr;
  }

  .farm-card-footer {
    flex-direction: column;
    align-items: flex-start;
  }
}
</style>
