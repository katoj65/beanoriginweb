<script setup>
import { computed, ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Search } from '@element-plus/icons-vue';
import BuyerLayout from '@/Layouts/BuyerLayout.vue';

const page = usePage();
const search = ref('');

const suppliers = computed(() => {
  const source = page.props.response?.suppliers ?? [];
  return Array.isArray(source) ? source : [];
});

const total = computed(() => Number(page.props.response?.total ?? suppliers.value.length));

const filteredSuppliers = computed(() => {
  const query = search.value.trim().toLowerCase();
  if (!query) return suppliers.value;

  return suppliers.value.filter((item) => {
    const haystack = [
      item.legal_name,
      item.name,
      item.reg_num,
      item.district,
      item.email,
      item.tel,
    ]
      .join(' ')
      .toLowerCase();

    return haystack.includes(query);
  });
});

</script>

<template>
  <buyer-layout>
    <div class="container py-0">
      <div class="card card-preview modern-panel">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
          <div>
            <h6 class="mb-0">Suppliers</h6>
            <span class="sub-text">Registered cooperatives available for sourcing</span>
          </div>
          <div class="suppliers-controls">
            <span class="sub-text total-chip">{{ total }} total</span>
            <el-input
              v-model="search"
              placeholder="Search cooperative, district, contact..."
              clearable
              class="suppliers-search"
              :prefix-icon="Search"
            />
          </div>
        </div>

        <div class="card-body p-0">
          <el-table :data="filteredSuppliers" height="460" style="width: 100%">
            <el-table-column prop="legal_name" min-width="220" label="Cooperative" />
            <el-table-column prop="district" width="140" label="District" />
            <el-table-column prop="reg_num" width="170" label="Reg Number" />
            <el-table-column prop="email" min-width="220" label="Email" />
            <el-table-column prop="tel" width="150" label="Telephone" />
          </el-table>
        </div>
      </div>
    </div>
  </buyer-layout>
</template>

<style scoped>
.modern-panel {
  border-radius: 12px;
  box-shadow: 0 6px 22px rgba(15, 23, 42, 0.05);
  overflow: hidden;
}

.modern-panel .card-header,
.modern-panel .card-inner,
.modern-panel .card-body {
  border-radius: inherit;
}

.suppliers-controls {
  display: flex;
  align-items: center;
  gap: 0.6rem;
}

.suppliers-search {
  width: 320px;
}

.total-chip {
  padding: 4px 10px;
  border-radius: 999px;
  background: #f1f5f9;
  color: #334155;
  font-weight: 600;
}

@media (max-width: 991px) {
  .card-header {
    flex-direction: column;
    align-items: flex-start !important;
    gap: 0.75rem;
  }

  .suppliers-controls {
    width: 100%;
  }

  .suppliers-search {
    width: 100%;
  }
}
</style>
