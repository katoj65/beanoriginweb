<script setup>
import { computed, ref } from 'vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';
import InputError from '@/Components/InputError.vue';
import { EditPen } from '@element-plus/icons-vue';
import { ElNotification } from 'element-plus';

const page = usePage();
const farm = computed(() => page.props.farm?.data ?? page.props.farm ?? {});
const owner = computed(() => page.props.owner?.data ?? page.props.owner ?? {});
const sustainabilityMetadata = computed(() => page.props.sustainability_metadata ?? []);
const farmSustainabilityData = computed(() => page.props.farm_sustainability_data ?? []);
const sustainabilityCount = computed(() => {
  return Array.isArray(farmSustainabilityData.value) ? farmSustainabilityData.value.length : 0;
});
const latestSustainabilityDate = computed(() => {
  if (!Array.isArray(farmSustainabilityData.value) || farmSustainabilityData.value.length === 0) return 'N/A';
  return farmSustainabilityData.value[0]?.created_at || 'N/A';
});

const sustainabilityForm = useForm({
  activity: '',
  value: '',
});
const showSustainabilityModal = ref(false);

const ownerFullName = computed(() => {
  if (owner.value?.full_name) return owner.value.full_name;
  return [owner.value?.first_name, owner.value?.last_name].filter(Boolean).join(' ') || 'N/A';
});

const ownerLocation = computed(() => {
  return [owner.value?.village, owner.value?.sub_county, owner.value?.district].filter(Boolean).join(', ') || 'N/A';
});

const goToFarmUpdatePage = () => {
  const id = farm.value?.id;
  if (!id) return;
  router.get(route('farmer.farms.update.page', { id }));
};

const submitFarmSustainabilityData = () => {
  const id = farm.value?.id;
  if (!id) return;

  sustainabilityForm.post(route('farmer.farms.sustainability.store', { id }), {
    preserveScroll: true,
    onSuccess: () => {
      sustainabilityForm.reset('activity', 'value');
      sustainabilityForm.clearErrors();
      showSustainabilityModal.value = false;
      ElNotification({
        title: 'Success',
        message: page.props.flash?.success || 'Farm sustainability data saved successfully.',
        type: 'success',
      });
    },
  });
};

const destroySustainabilityData = (row) => {
  const id = farm.value?.id;
  const sustainabilityId = Number(row?.id ?? 0);
  if (!id || !sustainabilityId) return;

  router.delete(route('farmer.farms.sustainability.destroy', { id, sustainabilityId }), {
    preserveScroll: true,
    onSuccess: () => {
      ElNotification({
        title: 'Success',
        message: page.props.flash?.success || 'Farm sustainability data deleted successfully.',
        type: 'success',
      });
    },
  });
};

const openSustainabilityModal = () => {
  showSustainabilityModal.value = true;
};

const closeSustainabilityModal = () => {
  showSustainabilityModal.value = false;
};

const formatSustainabilityDate = (value) => {
  if (!value) return 'N/A';
  const date = new Date(value);
  if (Number.isNaN(date.getTime())) return value;
  return date.toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: '2-digit' });
};
</script>

<template>
  <CooperativeLayout>
    <div class="container">


      <div class="row g-gs">
        <div class="col-12 col-lg-8">
          <div class="card card-bordered h-100">
            <div class="card-inner border-bottom d-flex justify-content-between align-items-center">
              <div>
                <h6 class="title mb-1"><em class="icon ni ni-home mr-1"></em>Farm Information</h6>
                <p class="sub-text mb-0">Core details about the farm.</p>
              </div>
              <el-button  :icon="EditPen" @click="goToFarmUpdatePage">Edit Farm</el-button>
            </div>
            <div class="card-inner">
              <div class="details-grid">
                <div class="detail-item">
                  <span class="sub-text"><em class="icon ni ni-home mr-1"></em>Farm Name</span>
                  <strong>{{ farm.farm_name || 'N/A' }}</strong>
                </div>
                <div class="detail-item">
                  <span class="sub-text"><em class="icon ni ni-map-pin mr-1"></em>Location</span>
                  <strong>{{ farm.location || 'N/A' }}</strong>
                </div>
                <div class="detail-item detail-item-full">
                  <span class="sub-text"><em class="icon ni ni-grid-alt mr-1"></em>Area (Acres)</span>
                  <strong>{{ farm.area_acres || '0' }}</strong>
                </div>
              </div>
            </div>
            <div class="card-inner">
              <div class="sustainability-header">
                <div>
                  <h6 class="title mb-1"><em class="icon ni ni-leaf mr-1"></em>Farm Sustainability Data</h6>
                  <p class="sub-text mb-2">Capture and track sustainability activity records for this farm.</p>
                  <div class="sustainability-stats">
                    <span class="sustainability-stat-pill">
                      <em class="icon ni ni-list-check mr-1"></em>{{ sustainabilityCount }} Record(s)
                    </span>
                    <span class="sustainability-stat-pill">
                      <em class="icon ni ni-calendar mr-1"></em>Latest: {{ latestSustainabilityDate }}
                    </span>
                  </div>
                </div>
                <el-button v-if="!showSustainabilityModal" type="primary" @click="openSustainabilityModal">
                  <em class="icon ni ni-plus mr-1"></em>Add Data
                </el-button>
                <el-button v-else @click="closeSustainabilityModal">
                  <em class="icon ni ni-cross mr-1"></em>Hide Form
                </el-button>
              </div>

              <form
                v-if="showSustainabilityModal"
                class="sustainability-form-shell mt-3"
                @submit.prevent="submitFarmSustainabilityData"
              >
                <el-row :gutter="12" class="sustainability-form-grid">
                  <el-col :xs="24" :md="9" class="sustainability-form-col">
                    <label class="form-label">Activity</label>
                    <el-select
                      v-model="sustainabilityForm.activity"
                      class="w-100"
                      clearable
                      allow-create
                      default-first-option
                      placeholder="Select or type activity"
                    >
                      <el-option
                        v-for="item in sustainabilityMetadata"
                        :key="item"
                        :label="item"
                        :value="item"
                      />
                    </el-select>
                    <div class="sustainability-feedback">
                      <small v-if="!sustainabilityForm.errors.activity" class="sustainability-hint">Select existing or type a new activity.</small>
                      <InputError :message="sustainabilityForm.errors.activity" class="sustainability-error" />
                    </div>
                  </el-col>

                  <el-col :xs="24" :md="9" class="sustainability-form-col">
                    <label class="form-label">Value</label>
                    <el-input v-model="sustainabilityForm.value" placeholder="Enter value" />
                    <div class="sustainability-feedback">
                      <small v-if="!sustainabilityForm.errors.value" class="sustainability-hint">Example: Shade trees planted, Drip irrigation, Organic compost.</small>
                      <InputError :message="sustainabilityForm.errors.value" class="sustainability-error" />
                    </div>
                  </el-col>

                  <el-col :xs="24" :md="6" class="sustainability-form-col sustainability-form-actions">
                    <label class="form-label sustainability-action-label">Action</label>
                    <el-button type="primary" native-type="submit" :loading="sustainabilityForm.processing" class="w-100">
                      Save
                    </el-button>
                    <div class="sustainability-feedback"></div>
                  </el-col>
                </el-row>
              </form>

              <div class="sustainability-table-wrap mt-3">
                <el-table
                  :data="farmSustainabilityData"
                  row-key="id"
                  class="sustainability-table"
                  empty-text="No sustainability data added yet."
                  size="small"
                  table-layout="fixed"
                  :fit="true"
                  :header-cell-style="{ background: '#f8fafc', color: '#526484', fontWeight: '600' }"
                >
                  <el-table-column width="56" align="center">
                    <template #header>
                      <span class="sustainability-table-head">
                        <em class="icon ni ni-hash mr-1"></em>#
                      </span>
                    </template>
                    <template #default="{ $index }">
                      <span class="sustainability-row-index">{{ $index + 1 }}</span>
                    </template>
                  </el-table-column>

                  <el-table-column prop="activity" min-width="170" show-overflow-tooltip>
                    <template #header>
                      <span class="sustainability-table-head">
                        <em class="icon ni ni-list mr-1"></em>Activity
                      </span>
                    </template>
                    <template #default="{ row }">
                      <span class="sustainability-activity-badge text-capitalize">
                        <em class="icon ni ni-check-circle mr-1"></em>{{ row.activity || 'N/A' }}
                      </span>
                    </template>
                  </el-table-column>

                  <el-table-column prop="value" min-width="150" show-overflow-tooltip>
                    <template #header>
                      <span class="sustainability-table-head">
                        <em class="icon ni ni-pen2 mr-1"></em>Value
                      </span>
                    </template>
                    <template #default="{ row }">
                      <span class="sustainability-value-chip">{{ row.value || 'N/A' }}</span>
                    </template>
                  </el-table-column>

                  <el-table-column prop="created_at" width="140" show-overflow-tooltip>
                    <template #header>
                      <span class="sustainability-table-head">
                        <em class="icon ni ni-calendar mr-1"></em>Date
                      </span>
                    </template>
                    <template #default="{ row }">
                      <span class="sustainability-date">{{ formatSustainabilityDate(row.created_at) }}</span>
                    </template>
                  </el-table-column>

                  <el-table-column width="74" align="center">
                    <template #header>
                      <span class="sustainability-table-head">
                        <em class="icon ni ni-trash"></em>
                      </span>
                    </template>
                    <template #default="{ row }">
                      <el-button type="danger" text class="sustainability-delete-btn" @click="destroySustainabilityData(row)">
                        <em class="icon ni ni-trash"></em>
                      </el-button>
                    </template>
                  </el-table-column>
                </el-table>
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

.sustainability-form-shell {
  border: 1px solid #e5e9f2;
  border-radius: 12px;
  padding: 14px;
  background: #fbfcfe;
}

.sustainability-header {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 10px;
}

.sustainability-stats {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}

.sustainability-stat-pill {
  display: inline-flex;
  align-items: center;
  border: 1px solid #e5e9f2;
  border-radius: 999px;
  padding: 4px 10px;
  background: #fff;
  font-size: 12px;
  color: #526484;
}

.sustainability-form-actions {
  justify-content: stretch;
}

.sustainability-form-grid {
  align-items: stretch;
}

.sustainability-form-col {
  display: grid;
  grid-template-rows: 20px 40px 22px;
  row-gap: 6px;
  min-width: 0;
}

.sustainability-form-col .form-label {
  margin: 0;
  line-height: 20px;
}

.sustainability-action-label {
  visibility: hidden;
}

.sustainability-feedback {
  min-height: 22px;
  margin-top: 0;
  min-width: 0;
  overflow: hidden;
}

.sustainability-table-wrap {
  border: 1px solid #e5e9f2;
  border-radius: 10px;
  overflow: hidden;
  background: #fff;
  box-shadow: inset 0 1px 0 #f5f7fb;
}

.sustainability-table-head {
  display: inline-flex;
  align-items: center;
  color: #526484;
  font-size: 12px;
  letter-spacing: 0.01em;
  white-space: nowrap;
}

.sustainability-row-index {
  color: #8094ae;
  font-size: 12px;
  font-weight: 600;
}

.sustainability-activity-badge {
  display: inline-flex;
  align-items: center;
  max-width: 100%;
  border: 1px solid #e5e9f2;
  border-radius: 999px;
  padding: 2px 8px;
  background: #f8fafc;
  color: #364a63;
  font-weight: 600;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.sustainability-value-chip {
  display: inline-block;
  max-width: 100%;
  color: #445870;
  font-weight: 500;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.sustainability-date {
  color: #8094ae;
  font-size: 12px;
}

.sustainability-delete-btn {
  padding: 0;
  width: 24px;
  height: 24px;
  min-height: 24px;
  border-radius: 6px;
}

.sustainability-hint {
  display: block;
  max-width: 100%;
  color: #8094ae;
  font-size: 12px;
  line-height: 1.25;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

:deep(.sustainability-form-shell .el-input__wrapper),
:deep(.sustainability-form-shell .el-select__wrapper),
:deep(.sustainability-form-actions .el-button) {
  min-height: 40px;
}

:deep(.sustainability-form-actions .el-button) {
  margin: 0;
}

:deep(.sustainability-error p) {
  display: block;
  max-width: 100%;
  margin: 0;
  font-size: 14px;
  line-height: 1.25;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

:deep(.sustainability-table .el-table__header th) {
  border-bottom: 1px solid #edf2f7;
  padding-top: 10px;
  padding-bottom: 10px;
}

:deep(.sustainability-table .el-table__row td) {
  border-bottom: 1px solid #f1f3f7;
  padding-top: 9px;
  padding-bottom: 9px;
}

:deep(.sustainability-table .el-table__cell) {
  font-size: 13px;
}

:deep(.sustainability-table .el-table__row:hover td) {
  background: #fafcff;
}

:deep(.sustainability-table .el-table__inner-wrapper::before) {
  height: 0;
}

@media (max-width: 768px) {
  .details-grid {
    grid-template-columns: 1fr;
  }

  .detail-item-full {
    grid-column: span 1;
  }

  .sustainability-form-shell {
    padding: 12px;
  }

  .sustainability-header {
    flex-direction: column;
    align-items: flex-start;
  }
}
</style>
