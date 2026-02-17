<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';

const page = usePage();
const produces = computed(() => page.props.produces?.data ?? page.props.produces ?? []);

const totalBatches = computed(() => produces.value.length);
const listedCount = computed(() => produces.value.filter((b) => b.status === 'listed').length);
const soldCount = computed(() => produces.value.filter((b) => b.status === 'sold').length);
const totalVolume = computed(() => produces.value.reduce((sum, b) => sum + Number(b.quantity || 0), 0));

const statusClass = (status) => {
  if (status === 'listed') return 'badge bg-success-subtle text-success';
  if (status === 'negotiating') return 'badge bg-warning-subtle text-warning';
  if (status === 'sold') return 'badge bg-info-subtle text-info';
  return 'badge bg-light text-dark';
};
</script>

<template>
  <CooperativeLayout>
    <div class="container">
    
      <div class="row g-3 mb-3">
        <div class="col-12 col-md-6 col-lg-3">
          <div class="stat-tile">
            <em class="icon ni ni-layers stat-icon"></em>
            <span class="sub-text">Total Batches</span>
            <h5 class="mb-0">{{ totalBatches }}</h5>
          </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
          <div class="stat-tile">
            <em class="icon ni ni-bag stat-icon"></em>
            <span class="sub-text">Listed For Sale</span>
            <h5 class="mb-0">{{ listedCount }}</h5>
          </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
          <div class="stat-tile">
            <em class="icon ni ni-check-circle stat-icon"></em>
            <span class="sub-text">Sold</span>
            <h5 class="mb-0">{{ soldCount }}</h5>
          </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
          <div class="stat-tile">
            <em class="icon ni ni-growth stat-icon"></em>
            <span class="sub-text">Total Volume</span>
            <h5 class="mb-0">{{ totalVolume }} kg</h5>
          </div>
        </div>
      </div>

      <div class="card card-bordered">
        <div class="card-inner border-bottom d-flex justify-content-between align-items-center">
          <div>
            <h6 class="title mb-1"><em class="icon ni ni-bag mr-1"></em>Batch Listings</h6>
            <p class="sub-text mb-0">Current coffee lots available to buyers.</p>
          </div>
          <button type="button" class="btn btn-primary btn-sm">
            <em class="icon ni ni-plus mr-1"></em>New Batch
          </button>
        </div>

        <div class="table-wrap">
          <table class="table table-sm table-middle mb-0 batch-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Farm ID</th>
                <th>User ID</th>
                <th>Crop Name</th>
                <th><em class="icon ni ni-tree-view mr-1"></em>Crop Type</th>
                <th><em class="icon ni ni-package mr-1"></em>Quantity</th>
                <th><em class="icon ni ni-coins mr-1"></em>Price</th>
                <th><em class="icon ni ni-map-pin mr-1"></em>Location</th>
                <th><em class="icon ni ni-calendar mr-1"></em>Date Of Havest</th>
                <th><em class="icon ni ni-award mr-1"></em>Crop Grade</th>
                <th>Process Method</th>
                <th><em class="icon ni ni-flag mr-1"></em>Status</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="produce in produces" :key="produce.id">
                <td>{{ produce.id }}</td>
                <td>{{ produce.farm_id }}</td>
                <td>{{ produce.user_id }}</td>
                <td>{{ produce.crop_name }}</td>
                <td>{{ produce.crop_type }}</td>
                <td>{{ produce.quantity }}</td>
                <td>{{ produce.price }}</td>
                <td>{{ produce.location }}</td>
                <td>{{ produce.date_of_havest }}</td>
                <td>{{ produce.crop_grade }}</td>
                <td>{{ produce.process_method }}</td>
                <td>
                  <span :class="statusClass(produce.status)">{{ produce.status }}</span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </CooperativeLayout>
</template>

<style scoped>
.page-head {
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.stat-tile {
  background: #ffffff;
  border: 1px solid var(--app-border);
  border-radius: 10px;
  padding: 12px;
}

.stat-icon {
  display: inline-flex;
  margin-bottom: 8px;
  color: #6f4e37;
  font-size: 1rem;
}

.table-wrap {
  overflow-x: auto;
}

.batch-table th {
  background: #f8fafc;
  color: #526484;
  font-weight: 600;
  white-space: nowrap;
}

.batch-table td {
  color: #364a63;
  vertical-align: middle;
  white-space: nowrap;
}
</style>
