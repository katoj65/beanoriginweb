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

const mapSubtitle = computed(() => {
  if (hasCoordinates.value) {
    return `Mapped location for ${props.farmName || 'this farm'}.`;
  }

  return 'Add latitude and longitude to pin this farm on the map.';
});

const farmTitle = computed(() => {
  const value = String(props.farmName || 'Farm').trim();
  if (!value) return 'Farm';
  return value.charAt(0).toUpperCase() + value.slice(1);
});

const mapAddress = computed(() => {
  return props.mapData?.address || 'Address details will appear after the map lookup succeeds.';
});

const mapEmbedUrl = computed(() => {
  return props.mapData?.embed_url || null;
});

const googleMapsUrl = computed(() => {
  return props.mapData?.google_maps_url || null;
});

const mapStatusMessage = computed(() => {
  return props.mapData?.message || null;
});

const hasCoordinates = computed(() => {
  return !(props.latitude === '' || props.latitude === null || props.longitude === '' || props.longitude === null);
});

const locationSummary = computed(() => {
  return props.location || 'Location not added yet';
});

const areaSummary = computed(() => {
  if (props.areaAcres === '' || props.areaAcres === null) return 'Area not added';
  return `${props.areaAcres} Acres`;
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
    <div class="farm-map-head">
      <div class="farm-map-copy">
        <div class="farm-map-heading">
          <div class="farm-map-icon">
            <em class="icon ni ni-map-pin-fill"></em>
          </div>
          <div class="farm-map-heading-copy">
            <span class="farm-map-label">{{ farmTitle }}</span>
            <p class="farm-map-subtitle mb-0">{{ mapSubtitle }}</p>
          </div>
          <span :class="['farm-map-status', { active: hasCoordinates }]">
            {{ hasCoordinates ? 'Mapped' : 'Pending' }}
          </span>
        </div>
      </div>
      <div class="farm-map-action">
        <el-button type="primary" class="farm-map-open-btn" @click="openModal">
          <em class="icon ni ni-map mr-1"></em>{{ latitude && longitude ? 'Update Map' : 'Add Map' }}
        </el-button>
      </div>
    </div>

    <div class="farm-map-main">
      <div class="farm-map-stage">
        <div class="farm-map-stage-overlay">
          <span class="farm-map-stage-badge"><em class="icon ni ni-map mr-1"></em>Live Map</span>
        </div>
        <a
          v-if="googleMapsUrl"
          :href="googleMapsUrl"
          target="_blank"
          rel="noopener noreferrer"
          class="farm-map-stage-link"
        >
          <em class="icon ni ni-arrow-up-right mr-1"></em>Open in Google Maps
        </a>
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

      <div class="farm-map-summary mt-1">
        <div class="farm-map-summary-card farm-map-summary-address">
          <div class="farm-map-summary-top">
            <span class="farm-map-summary-icon">
              <em class="icon ni ni-map-pin"></em>
            </span>
            <span class="farm-map-summary-label">Resolved Address</span>
          </div>
          <strong>{{ mapAddress }}</strong>
        </div>
        <div class="farm-map-summary-card">
          <div class="farm-map-summary-top">
            <span class="farm-map-summary-icon">
              <em class="icon ni ni-activity"></em>
            </span>
            <span class="farm-map-summary-label">Map Status</span>
          </div>
          <strong>{{ hasCoordinates ? 'Pinned and ready' : 'Awaiting coordinates' }}</strong>
          <span class="farm-map-summary-text">
            {{ hasCoordinates ? 'The current map is using the saved farm coordinates.' : 'Add latitude and longitude to display this farm on the map.' }}
          </span>
        </div>
        <div class="farm-map-summary-card">
          <div class="farm-map-summary-top">
            <span class="farm-map-summary-icon">
              <em class="icon ni ni-location"></em>
            </span>
            <span class="farm-map-summary-label">Farm Location</span>
          </div>
          <strong>{{ locationSummary }}</strong>
        </div>
        <div class="farm-map-summary-card">
          <div class="farm-map-summary-top">
            <span class="farm-map-summary-icon">
              <em class="icon ni ni-property"></em>
            </span>
            <span class="farm-map-summary-label">Farm Size</span>
          </div>
          <strong>{{ areaSummary }}</strong>
        </div>
      </div>
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
  flex-direction: column;
  align-items: stretch;
  gap: 16px;
  border-radius: 20px;
  background: linear-gradient(180deg, #ffffff 0%, #fbfcfe 100%);
}

.farm-map-head {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 14px;
}

.farm-map-main {
  display: flex;
  flex-direction: column;
  gap: 12px;
  min-width: 0;
}

.farm-map-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 46px;
  height: 46px;
  border-radius: 16px;
  background: linear-gradient(135deg, #eff6ff 0%, #eef2ff 100%);
  color: #2563eb;
  font-size: 18px;
  flex-shrink: 0;
  box-shadow: inset 0 0 0 1px #dbeafe;
}

.farm-map-copy {
  display: flex;
  flex-direction: column;
  gap: 4px;
  min-width: 0;
}

.farm-map-heading {
  display: flex;
  align-items: flex-start;
  gap: 12px;
  flex-wrap: wrap;
}

.farm-map-heading-copy {
  display: flex;
  flex-direction: column;
  gap: 2px;
  min-width: 0;
}

.farm-map-label {
  color: #364a63;
  font-size: 15px;
  font-weight: 800;
  letter-spacing: 0.01em;
}

.farm-map-subtitle {
  color: #8094ae;
  font-size: 13px;
  line-height: 1.5;
}

.farm-map-status {
  display: inline-flex;
  align-items: center;
  padding: 6px 11px;
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

.farm-map-stage {
  position: relative;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 8px;
  width: 100%;
  min-height: 160px;
  border: 1px solid #e5e9f2;
  border-radius: 18px;
  background:
    linear-gradient(135deg, rgba(238, 246, 255, 0.9) 0%, rgba(251, 252, 254, 0.95) 100%);
  text-align: center;
  overflow: hidden;
}

.farm-map-stage-overlay {
  position: absolute;
  top: 12px;
  left: 12px;
  z-index: 2;
}

.farm-map-stage-link {
  position: absolute;
  top: 12px;
  right: 12px;
  z-index: 2;
  display: inline-flex;
  align-items: center;
  padding: 7px 10px;
  border-radius: 999px;
  background: rgba(255, 255, 255, 0.94);
  border: 1px solid rgba(219, 228, 240, 0.95);
  color: #364a63;
  font-size: 12px;
  font-weight: 700;
  text-decoration: none;
  backdrop-filter: blur(10px);
}

.farm-map-stage-link:hover {
  color: #1d4ed8;
  text-decoration: none;
}

.farm-map-stage-badge {
  display: inline-flex;
  align-items: center;
  padding: 7px 10px;
  border-radius: 999px;
  background: rgba(255, 255, 255, 0.94);
  border: 1px solid rgba(219, 228, 240, 0.95);
  color: #364a63;
  font-size: 12px;
  font-weight: 700;
  backdrop-filter: blur(10px);
}

.farm-map-frame {
  width: 100%;
  min-height: 380px;
  display: block;
  border: 0;
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

.farm-map-summary {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 12px;
}

.farm-map-summary-card {
  display: flex;
  flex-direction: column;
  gap: 6px;
  padding: 12px 14px;
  border: 1px solid #e5e9f2;
  border-radius: 16px;
  background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
}

.farm-map-summary-top {
  display: flex;
  align-items: center;
  gap: 8px;
}

.farm-map-summary-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 28px;
  height: 28px;
  border-radius: 10px;
  background: #eef4ff;
  color: #2563eb;
  font-size: 13px;
  flex-shrink: 0;
}

.farm-map-summary-label {
  color: #8094ae;
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 0.05em;
  text-transform: uppercase;
}

.farm-map-summary-card strong {
  color: #364a63;
  font-size: 13px;
  line-height: 1.6;
  word-break: break-word;
}

.farm-map-summary-text {
  color: #8094ae;
  font-size: 12px;
  line-height: 1.55;
}

.farm-map-values {
  display: flex;
  flex-wrap: wrap;
  align-content: flex-start;
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

:deep(.farm-map-open-btn) {
  color: #fff;
  min-width: 144px;
  min-height: 40px;
  border-radius: 12px;
  font-weight: 700;
}

.farm-map-action {
  display: flex;
  justify-content: flex-end;
  gap: 8px;
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
  .farm-map-head {
    width: 100%;
    flex-direction: column;
    align-items: flex-start;
  }

  .farm-map-summary {
    grid-template-columns: 1fr;
  }

  .farm-map-stage-link {
    top: auto;
    right: 12px;
    bottom: 12px;
  }

  .farm-map-action {
    width: 100%;
    justify-content: stretch;
  }

  :deep(.farm-map-open-btn) {
    width: 100%;
  }

  .farm-map-footer {
    flex-direction: column-reverse;
  }
}
</style>
