<script setup>
import { computed, reactive, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';

const page = usePage();
const cooperative = computed(() => page.props?.response?.cooperative ?? page.props?.cooperative ?? null);

const profile = reactive({
  legal_name: '',
  trading_name: '',
  email: '',
  phone: '',
  physical_address: '',
  postal_address: '',
  website: '',
});

const security = reactive({
  current_password: '',
  new_password: '',
  confirm_password: '',
  two_factor: false,
});

const notifications = reactive({
  email_updates: true,
  payment_alerts: true,
  product_activity: true,
  weekly_report: false,
});

const preferences = reactive({
  language: 'English',
  timezone: 'Africa/Kampala',
  currency: 'UGX',
});

const saveProfile = () => {
  // TODO: connect to backend
};

const saveSecurity = () => {
  // TODO: connect to backend
};

const saveNotifications = () => {
  // TODO: connect to backend
};

const savePreferences = () => {
  // TODO: connect to backend
};

watch(
  cooperative,
  (item) => {
    profile.legal_name = item?.legal_name ?? '';
    profile.trading_name = item?.name ?? '';
    profile.email = item?.email ?? '';
    profile.phone = item?.tel ?? '';
    profile.physical_address = item?.physical_address ?? '';
    profile.postal_address = item?.postal_address ?? '';
    profile.website = item?.website ?? '';
  },
  { immediate: true }
);
</script>

<template>
<CooperativeLayout>
<div class="container">
  <div class="row g-gs">
    <div class="col-12 col-xl-8">
      <div class="card card-bordered settings-card mb-3">
        <div class="card-inner border-bottom">
          <h6 class="title mb-1">Organization Profile</h6>
          <p class="sub-text mb-0">Update your cooperative identity and official contacts.</p>
        </div>
        <div class="card-inner">
          <form class="row g-3" @submit.prevent="saveProfile">
            <div class="col-12 col-md-6">
              <label class="form-label">Legal Cooperative Name</label>
              <input v-model="profile.legal_name" type="text" class="form-control" placeholder="Enter legal name" />
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label">Trading Name</label>
              <input v-model="profile.trading_name" type="text" class="form-control" placeholder="Enter trading name" />
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label">Official Email</label>
              <input v-model="profile.email" type="email" class="form-control" placeholder="you@cooperative.org" />
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label">Official Phone</label>
              <input v-model="profile.phone" type="tel" class="form-control" placeholder="+256 ..." />
            </div>
            <div class="col-12">
              <label class="form-label">Physical Address</label>
              <input v-model="profile.physical_address" type="text" class="form-control" placeholder="Enter physical address" />
            </div>
            <div class="col-12">
              <label class="form-label">Postal Address</label>
              <input v-model="profile.postal_address" type="text" class="form-control" placeholder="Enter postal address" />
            </div>
            <div class="col-12">
              <label class="form-label">Website</label>
              <input v-model="profile.website" type="url" class="form-control" placeholder="https://example.com" />
            </div>
            <div class="col-12">
              <button type="submit" class="btn btn-primary">Save Profile</button>
            </div>
          </form>
        </div>
      </div>

      <div class="card card-bordered settings-card">
        <div class="card-inner border-bottom">
          <h6 class="title mb-1">Security Settings</h6>
          <p class="sub-text mb-0">Control account access and password policy.</p>
        </div>
        <div class="card-inner">
          <form class="row g-3" @submit.prevent="saveSecurity">
            <div class="col-12">
              <label class="form-label">Current Password</label>
              <input v-model="security.current_password" type="password" class="form-control" placeholder="Enter current password" />
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label">New Password</label>
              <input v-model="security.new_password" type="password" class="form-control" placeholder="Enter new password" />
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label">Confirm Password</label>
              <input v-model="security.confirm_password" type="password" class="form-control" placeholder="Confirm password" />
            </div>
            <div class="col-12">
              <div class="setting-row">
                <div>
                  <strong>Two-Factor Authentication</strong>
                  <p class="sub-text mb-0">Require a second verification step on login.</p>
                </div>
                <el-switch v-model="security.two_factor" />
              </div>
            </div>
            <div class="col-12">
              <button type="submit" class="btn btn-primary">Update Security</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-12 col-xl-4">
      <div class="card card-bordered settings-card mb-3">
        <div class="card-inner border-bottom">
          <h6 class="title mb-1">Notification Preferences</h6>
          <p class="sub-text mb-0">Choose what updates you receive.</p>
        </div>
        <div class="card-inner">
          <div class="setting-row">
            <span>Email Updates</span>
            <el-switch v-model="notifications.email_updates" />
          </div>
          <div class="setting-row">
            <span>Payment Alerts</span>
            <el-switch v-model="notifications.payment_alerts" />
          </div>
          <div class="setting-row">
            <span>Product Activity</span>
            <el-switch v-model="notifications.product_activity" />
          </div>
          <div class="setting-row mb-3">
            <span>Weekly Summary Report</span>
            <el-switch v-model="notifications.weekly_report" />
          </div>
          <button class="btn btn-primary" @click="saveNotifications">Save Notifications</button>
        </div>
      </div>

      <div class="card card-bordered settings-card">
        <div class="card-inner border-bottom">
          <h6 class="title mb-1">Workspace Preferences</h6>
          <p class="sub-text mb-0">Configure your default operational options.</p>
        </div>
        <div class="card-inner">
          <div class="form-group mb-3">
            <label class="form-label">Language</label>
            <input v-model="preferences.language" type="text" class="form-control" />
          </div>
          <div class="form-group mb-3">
            <label class="form-label">Timezone</label>
            <input v-model="preferences.timezone" type="text" class="form-control" />
          </div>
          <div class="form-group mb-3">
            <label class="form-label">Currency</label>
            <input v-model="preferences.currency" type="text" class="form-control" />
          </div>
          <button class="btn btn-primary" @click="savePreferences">Save Preferences</button>
        </div>
      </div>
    </div>
  </div>
</div>
</CooperativeLayout>
</template>

<style scoped>
.settings-head {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.settings-card {
  border-radius: 14px;
}

.setting-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  padding: 10px 0;
  border-bottom: 1px solid #edf2f7;
}

.setting-row:last-child {
  border-bottom: 0;
}

@media (max-width: 768px) {
  .settings-head {
    align-items: flex-start;
  }
}
</style>
