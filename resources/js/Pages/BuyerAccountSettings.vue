<script setup>
import { computed, reactive, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import BuyerLayout from '@/Layouts/BuyerLayout.vue';

const page = usePage();
const user = computed(() => page.props?.response?.user ?? page.props?.auth?.user ?? null);
const profileData = computed(() => page.props?.response?.profile ?? null);

const profile = reactive({
  first_name: '',
  last_name: '',
  email: '',
  phone: '',
  address: '',
  gender: '',
  dob: '',
});

const security = reactive({
  current_password: '',
  new_password: '',
  confirm_password: '',
  two_factor: false,
});

const notifications = reactive({
  email_updates: true,
  bid_alerts: true,
  market_updates: true,
  weekly_report: false,
});

const preferences = reactive({
  language: 'English',
  timezone: 'Africa/Kampala',
  currency: 'UGX',
});

const saveProfile = () => {
  // TODO: connect to backend endpoint
};

const saveSecurity = () => {
  // TODO: connect to backend endpoint
};

const saveNotifications = () => {
  // TODO: connect to backend endpoint
};

const savePreferences = () => {
  // TODO: connect to backend endpoint
};

watch(
  [user, profileData],
  ([u, p]) => {
    profile.first_name = u?.fname ?? '';
    profile.last_name = u?.lname ?? '';
    profile.email = u?.email ?? '';
    profile.phone = p?.tel ?? '';
    profile.address = p?.address ?? '';
    profile.gender = p?.gender ?? '';
    profile.dob = p?.dob ?? '';
  },
  { immediate: true }
);
</script>

<template>
  <BuyerLayout>
    <div class="container">
      <div class="row g-gs">
        <div class="col-12 col-xl-8">
          <div class="card settings-card mb-3">
            <div class="card-inner border-bottom">
              <h6 class="title mb-1">Buyer Profile Settings</h6>
              <p class="sub-text mb-0">Update your account identity and contact details.</p>
            </div>
            <div class="card-inner">
              <form class="row g-3" @submit.prevent="saveProfile">
                <div class="col-12 col-md-6">
                  <label class="form-label">First Name</label>
                  <input v-model="profile.first_name" type="text" class="form-control" placeholder="Enter first name" />
                </div>
                <div class="col-12 col-md-6">
                  <label class="form-label">Last Name</label>
                  <input v-model="profile.last_name" type="text" class="form-control" placeholder="Enter last name" />
                </div>
                <div class="col-12 col-md-6">
                  <label class="form-label">Email</label>
                  <input v-model="profile.email" type="email" class="form-control" placeholder="you@example.com" />
                </div>
                <div class="col-12 col-md-6">
                  <label class="form-label">Phone</label>
                  <input v-model="profile.phone" type="tel" class="form-control" placeholder="+256 ..." />
                </div>
                <div class="col-12 col-md-6">
                  <label class="form-label">Gender</label>
                  <input v-model="profile.gender" type="text" class="form-control" placeholder="Male/Female" />
                </div>
                <div class="col-12 col-md-6">
                  <label class="form-label">Date of Birth</label>
                  <input v-model="profile.dob" type="date" class="form-control" />
                </div>
                <div class="col-12">
                  <label class="form-label">Address</label>
                  <input v-model="profile.address" type="text" class="form-control" placeholder="Enter address" />
                </div>
                <div class="col-12">
                  <button type="submit" class="btn btn-primary">Save Profile</button>
                </div>
              </form>
            </div>
          </div>

          <div class="card settings-card">
            <div class="card-inner border-bottom">
              <h6 class="title mb-1">Security Settings</h6>
              <p class="sub-text mb-0">Control account access and password policy.</p>
            </div>
            <div class="card-inner">
              <form class="row g-3" @submit.prevent="saveSecurity">
                <div class="col-12">
                  <label class="form-label">Current Password</label>
                  <input
                    v-model="security.current_password"
                    type="password"
                    class="form-control"
                    placeholder="Enter current password"
                  />
                </div>
                <div class="col-12 col-md-6">
                  <label class="form-label">New Password</label>
                  <input
                    v-model="security.new_password"
                    type="password"
                    class="form-control"
                    placeholder="Enter new password"
                  />
                </div>
                <div class="col-12 col-md-6">
                  <label class="form-label">Confirm Password</label>
                  <input
                    v-model="security.confirm_password"
                    type="password"
                    class="form-control"
                    placeholder="Confirm password"
                  />
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
          <div class="card settings-card mb-3">
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
                <span>Bid Alerts</span>
                <el-switch v-model="notifications.bid_alerts" />
              </div>
              <div class="setting-row">
                <span>Market Updates</span>
                <el-switch v-model="notifications.market_updates" />
              </div>
              <div class="setting-row mb-3">
                <span>Weekly Summary Report</span>
                <el-switch v-model="notifications.weekly_report" />
              </div>
              <button class="btn btn-primary" @click="saveNotifications">Save Notifications</button>
            </div>
          </div>

          <div class="card settings-card">
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
  </BuyerLayout>
</template>

<style scoped>
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
</style>
