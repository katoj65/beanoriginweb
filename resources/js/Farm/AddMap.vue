<script setup>
import { computed, ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { ElNotification } from 'element-plus';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
  farmId: {
    type: [Number, String],
    required: true,
  },
  farmName: {
    type: String,
    default: '',
  },
  location: {
    type: String,
    default: '',
  },
  areaAcres: {
    type: [Number, String],
    default: '',
  },
  latitude: {
    type: [Number, String, null],
    default: null,
  },
  longitude: {
    type: [Number, String, null],
    default: null,
  },
  mapData: {
    type: Object,
    default: null,
  },
});

const showModal = ref(false);

const form = useForm({
  latitude: props.latitude ?? '',
  longitude: props.longitude ?? '',
});

const coordinateSummary = computed(() => {
  const lat = props.latitude ?? '';
  const lng = props.longitude ?? '';
  if (lat === '' || lat === null || lng === '' || lng === null) return 'No coordinates added yet';
  return `${lat}, ${lng}`;
});

const mapAddress = computed(() => {
  return props.mapData?.address || 'Address details will appear after the map lookup succeeds.';
});

const mapEmbedUrl = computed(() => {
  return props.mapData?.embed_url || null;
});

const mapStatusMessage = computed(() => {
  return props.mapData?.message || null;
});

const coordinateNote = computed(() => {
  if (hasCoordinates.value) {
    return 'This map area is ready for farm location display and coordinate-based reference.';
  }

  return 'Add accurate latitude and longitude to prepare this section for farm map display.';
});

const hasCoordinates = computed(() => {
  return !(props.latitude === '' || props.latitude === null || props.longitude === '' || props.longitude === null);
});

const latitudeLabel = computed(() => {
  return props.latitude === '' || props.latitude === null ? 'Not added' : props.latitude;
});

const longitudeLabel = computed(() => {
  return props.longitude === '' || props.longitude === null ? 'Not added' : props.longitude;
});

const openModal = () => {
  form.defaults({
    latitude: props.latitude ?? '',
    longitude: props.longitude ?? '',
  });
  form.reset();
  form.clearErrors();
  showModal.value = true;
};

const closeModal = () => {
  form.clearErrors();
  showModal.value = false;
};

const updateLocation = () => {
  form.clearErrors();

  const latitude = String(form.latitude ?? '').trim();
  const longitude = String(form.longitude ?? '').trim();

  if (!latitude || !longitude) {
    if (!latitude) {
      form.setError('latitude', 'Latitude is required.');
    }
    if (!longitude) {
      form.setError('longitude', 'Longitude is required.');
    }
    return;
  }

  form.latitude = latitude;
  form.longitude = longitude;

  form.put(route('farmer.farms.location.update', { id: props.farmId }), {
    preserveScroll: true,
    onSuccess: () => {
      closeModal();
      ElNotification({
        title: 'Success',
        message: 'Farm coordinates updated successfully.',
        type: 'success',
      });
    },
  });
};
</script>

<template>
  <div class="farm-map-card">
    <div class="farm-map-main">
      <div class="farm-map-intro">
        <div class="farm-map-icon">
          <em class="icon ni ni-map-pin-fill"></em>
        </div>
        <div class="farm-map-copy">
          <div class="farm-map-heading">
            <span class="farm-map-label">Farm Coordinates</span>
            <span :class="['farm-map-status', { active: hasCoordinates }]">
              {{ hasCoordinates ? 'Mapped' : 'Pending' }}
            </span>
          </div>
          <strong>{{ coordinateSummary }}</strong>
          <p class="farm-map-description">{{ coordinateNote }}</p>
        </div>
      </div>

      <div class="farm-map-stage">
        <iframe
          v-if="mapEmbedUrl"
          :src="mapEmbedUrl"
          class="farm-map-frame"
          loading="lazy"
          referrerpolicy="no-referrer-when-downgrade"
          allowfullscreen
        ></iframe>

        <template v-else>
          <div class="farm-map-stage-icon">
            <em class="icon ni ni-map"></em>
          </div>
          <strong>{{ hasCoordinates ? 'Map unavailable' : 'Map area reserved' }}</strong>
          <p>
            {{ hasCoordinates ? (mapStatusMessage || 'The saved coordinates could not load into Google Maps right now.') : 'No map is shown yet. Save latitude and longitude to prepare this area for map display.' }}
          </p>
        </template>
      </div>

      <div class="farm-map-values">
        <span class="farm-map-chip"><em class="icon ni ni-navigation mr-1"></em>Lat: {{ latitudeLabel }}</span>
        <span class="farm-map-chip"><em class="icon ni ni-navigation-fill mr-1"></em>Lng: {{ longitudeLabel }}</span>
        <span class="farm-map-chip farm-map-address-chip"><em class="icon ni ni-building mr-1"></em>{{ mapAddress }}</span>
      </div>
    </div>

    <div class="farm-map-action">
      <el-button type="primary" class="farm-map-open-btn" @click="openModal">
        <em class="icon ni ni-map mr-1"></em>{{ latitude && longitude ? 'Update Map' : 'Add Map' }}
      </el-button>
      <span class="farm-map-action-text">Latitude and longitude are required.</span>
    </div>
  </div>

  <el-dialog
    v-model="showModal"
    title="Add Farm Coordinates"
    width="640px"
    class="farm-map-modal"
    destroy-on-close
    @close="closeModal"
  >
    <div class="farm-map-modal-copy">
      <span class="farm-map-badge"><em class="icon ni ni-navigation mr-1"></em>Map Coordinates</span>
      <p class="farm-map-modal-text">Store the farm latitude and longitude so the location can be referenced accurately.</p>
    </div>

    <form class="farm-map-form" @submit.prevent="updateLocation">
      <el-row :gutter="12">
        <el-col :xs="24" :md="12" class="farm-map-col">
          <label class="form-label">Latitude</label>
          <el-input v-model="form.latitude" placeholder="Example: 0.347596" />
          <InputError :message="form.errors.latitude" class="mt-2" />
        </el-col>

        <el-col :xs="24" :md="12" class="farm-map-col">
          <label class="form-label">Longitude</label>
          <el-input v-model="form.longitude" placeholder="Example: 32.582520" />
          <InputError :message="form.errors.longitude" class="mt-2" />
        </el-col>
      </el-row>
    </form>

    <template #footer>
      <div class="farm-map-footer">
        <el-button @click="closeModal">Cancel</el-button>
        <el-button type="primary" :loading="form.processing" @click="updateLocation">Save Coordinates</el-button>
      </div>
    </template>
  </el-dialog>
</template>

<style scoped>
.farm-map-card {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 18px;
  margin-top: 14px;
  padding: 18px 20px;
  border: 1px solid #e5e9f2;
  border-radius: 16px;
  background: linear-gradient(180deg, #ffffff 0%, #fbfcfe 100%);
}

.farm-map-main {
  display: flex;
  flex: 1;
  flex-direction: column;
  gap: 14px;
  min-width: 0;
}

.farm-map-intro {
  display: flex;
  align-items: flex-start;
  gap: 14px;
}

.farm-map-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 44px;
  height: 44px;
  border-radius: 14px;
  background: #eef6ff;
  color: #1d4ed8;
  font-size: 18px;
  flex-shrink: 0;
}

.farm-map-copy {
  display: flex;
  flex-direction: column;
  gap: 6px;
  min-width: 0;
}

.farm-map-heading {
  display: flex;
  align-items: center;
  gap: 10px;
  flex-wrap: wrap;
}

.farm-map-label {
  color: #364a63;
  font-size: 14px;
  font-weight: 700;
  letter-spacing: 0.01em;
}

.farm-map-status {
  display: inline-flex;
  align-items: center;
  padding: 4px 10px;
  border-radius: 999px;
  background: #fef3c7;
  color: #92400e;
  font-size: 12px;
  font-weight: 700;
}

.farm-map-status.active {
  background: #ecfdf3;
  color: #166534;
}

.farm-map-copy strong {
  color: #364a63;
  font-size: 15px;
  font-weight: 700;
}

.farm-map-description {
  margin: 0;
  color: #6b7a90;
  font-size: 13px;
  line-height: 1.6;
}

.farm-map-stage {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 8px;
  min-height: 160px;
  padding: 18px;
  border: 1px solid #e5e9f2;
  border-radius: 16px;
  background:
    linear-gradient(135deg, rgba(238, 246, 255, 0.9) 0%, rgba(251, 252, 254, 0.95) 100%);
  text-align: center;
}

.farm-map-frame {
  width: 100%;
  min-height: 320px;
  border: 0;
  border-radius: 12px;
  background: #f8fafc;
}

.farm-map-stage-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 52px;
  height: 52px;
  border-radius: 16px;
  background: #ffffff;
  color: #1d4ed8;
  font-size: 20px;
  box-shadow: inset 0 0 0 1px #dbe4f0;
}

.farm-map-stage strong {
  color: #364a63;
  font-size: 15px;
  font-weight: 700;
}

.farm-map-stage p {
  max-width: 440px;
  margin: 0;
  color: #6b7a90;
  font-size: 13px;
  line-height: 1.7;
}

.farm-map-values {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}

.farm-map-chip {
  display: inline-flex;
  align-items: center;
  padding: 6px 10px;
  border-radius: 999px;
  background: #f8fafc;
  border: 1px solid #e5e9f2;
  color: #526484;
  font-size: 12px;
  font-weight: 600;
}

.farm-map-address-chip {
  max-width: 100%;
  border-radius: 12px;
  white-space: normal;
}

:deep(.farm-map-open-btn) {
  color: #fff;
  min-width: 132px;
}

.farm-map-action {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  gap: 8px;
  min-width: 132px;
}

.farm-map-action-text {
  color: #8094ae;
  font-size: 11px;
  line-height: 1.5;
  text-align: right;
}

.farm-map-modal-copy {
  margin-bottom: 16px;
}

.farm-map-badge {
  display: inline-flex;
  align-items: center;
  padding: 6px 12px;
  border-radius: 999px;
  background: #eef6ff;
  color: #1d4ed8;
  font-size: 12px;
  font-weight: 700;
}

.farm-map-modal-text {
  margin: 12px 0 0;
  color: #6b7a90;
  font-size: 14px;
  line-height: 1.6;
}

.farm-map-form {
  border: 1px solid #eef2f7;
  border-radius: 18px;
  padding: 18px;
  background: linear-gradient(180deg, #ffffff 0%, #fbfcfe 100%);
}

.farm-map-col .form-label {
  display: inline-block;
  margin-bottom: 10px;
}

.farm-map-footer {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
}

:deep(.farm-map-modal .el-dialog) {
  border-radius: 18px;
  overflow: hidden;
}

:deep(.farm-map-modal .el-dialog__header) {
  padding: 18px 22px 0;
}

:deep(.farm-map-modal .el-dialog__body) {
  padding: 14px 22px 8px;
}

:deep(.farm-map-modal .el-dialog__footer) {
  padding: 0 22px 20px;
}

@media (max-width: 768px) {
  .farm-map-card {
    flex-direction: column;
    align-items: flex-start;
  }

  .farm-map-intro {
    width: 100%;
  }

  .farm-map-action {
    width: 100%;
    align-items: stretch;
  }

  :deep(.farm-map-open-btn) {
    width: 100%;
  }

  .farm-map-action-text {
    text-align: left;
  }

  .farm-map-footer {
    flex-direction: column-reverse;
  }
}
</style>
