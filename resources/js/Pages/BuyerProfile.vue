<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import BuyerLayout from '@/Layouts/BuyerLayout.vue';

const page = usePage();

const user = computed(() => page.props.response?.user ?? {});
const profile = computed(() => page.props.response?.profile ?? {});
const stats = computed(() => page.props.response?.stats ?? {});

const fullName = computed(() => {
  const first = String(user.value?.fname ?? '').trim();
  const last = String(user.value?.lname ?? '').trim();
  return `${first} ${last}`.trim() || 'Buyer';
});

const initials = computed(() => {
  return fullName.value
    .split(' ')
    .filter(Boolean)
    .map((part) => part[0])
    .join('')
    .slice(0, 2)
    .toUpperCase();
});

const profileCompletion = computed(() => {
  const checks = [
    user.value?.fname,
    user.value?.lname,
    user.value?.email,
    profile.value?.tel,
    profile.value?.gender,
    profile.value?.dob,
    profile.value?.address,
  ];

  const filled = checks.filter((field) => String(field ?? '').trim() !== '').length;
  return Math.round((filled / checks.length) * 100);
});

const winRate = computed(() => {
  const total = Number(stats.value?.total_bids ?? 0);
  const won = Number(stats.value?.won_bids ?? 0);
  if (total <= 0) return 0;
  return Math.round((won / total) * 100);
});

const snapshotStats = computed(() => [
  { label: 'Total Bids', value: Number(stats.value?.total_bids ?? 0).toLocaleString(), icon: 'ni ni-growth-fill' },
  { label: 'Active Bids', value: Number(stats.value?.active_bids ?? 0).toLocaleString(), icon: 'ni ni-clock-fill' },
  { label: 'Win Rate', value: `${winRate.value}%`, icon: 'ni ni-check-circle-fill' },
  { label: 'Suppliers', value: Number(stats.value?.suppliers ?? 0).toLocaleString(), icon: 'ni ni-users-fill' },
]);

const performanceRows = computed(() => [
  { label: 'Profile completion', value: profileCompletion.value },
  { label: 'Winning momentum', value: winRate.value },
  { label: 'Active pipeline', value: Math.min(100, Number(stats.value?.active_bids ?? 0) * 12) },
  { label: 'Supplier coverage', value: Math.min(100, Number(stats.value?.suppliers ?? 0) * 10) },
]);

const recentActivity = computed(() => [
  { title: 'Arabica Lot Bid', status: 'Pending', when: 'Today' },
  { title: 'Robusta Contract', status: 'In Review', when: 'Yesterday' },
  { title: 'Washed Processed Lot', status: 'Won', when: '2 days ago' },
]);
</script>

<template>
  <buyer-layout>
    <div class="nk-block">
      <div class="row g-gs profile-grid">
        <div class="col-12 col-lg-4">
          <div class="card card-bordered h-100">
            <div class="card-inner profile-card">
              <div class="avatar-badge">{{ initials }}</div>
              <h5 class="mb-1 text-center">{{ fullName }}</h5>
              <p class="sub-text text-center mb-0">{{ user.email || 'No email found' }}</p>

              <div class="profile-meta">
                <div class="meta-row">
                  <span class="sub-text">Phone</span>
                  <strong>{{ profile.tel || 'Not provided' }}</strong>
                </div>
                <div class="meta-row">
                  <span class="sub-text">Address</span>
                  <strong>{{ profile.address || 'Not provided' }}</strong>
                </div>
                <div class="meta-row">
                  <span class="sub-text">Completion</span>
                  <strong>{{ profileCompletion }}%</strong>
                </div>
              </div>

              <div class="meter-wrap">
                <div class="meter-track">
                  <div class="meter-fill" :style="{ width: `${profileCompletion}%` }"></div>
                </div>
                <div class="meter-foot">Profile strength {{ profileCompletion }}%</div>
              </div>

              <div class="profile-actions">
                <Link :href="route('buyer.account.settings')" class="btn btn-primary btn-sm w-100">Edit Profile</Link>
                <Link :href="route('buyer.help')" class="btn btn-outline-light btn-sm w-100">Support</Link>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12 col-lg-8">
          <div class="row g-gs profile-subgrid">
            <div class="col-12">
              <div class="card card-bordered">
                <div class="card-inner snapshot-card">
                  <div class="snapshot-head">
                    <div>
                      <h5 class="mb-1">Buyer Snapshot</h5>
                      <p class="sub-text mb-0">A focused view of your trading posture.</p>
                    </div>
                    <Link :href="route('buyer.market')" class="btn btn-light btn-sm">Open Market</Link>
                  </div>

                  <div class="snapshot-grid">
                    <div v-for="item in snapshotStats" :key="item.label" class="snapshot-item">
                      <span class="snapshot-icon"><em :class="['icon', item.icon]"></em></span>
                      <div>
                        <strong>{{ item.value }}</strong>
                        <p class="sub-text mb-0">{{ item.label }}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-12">
              <div class="card card-bordered">
                <div class="card-inner combined-card">
                  <div class="combined-col">
                    <h6 class="mb-3">Performance</h6>
                    <div class="perf-list">
                      <div v-for="item in performanceRows" :key="item.label" class="perf-item">
                        <div class="perf-meta">
                          <span>{{ item.label }}</span>
                          <strong>{{ item.value }}%</strong>
                        </div>
                        <div class="perf-track">
                          <div class="perf-fill" :style="{ width: `${item.value}%` }"></div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="combined-col combined-col-right">
                    <h6 class="mb-3">Recent Activity</h6>
                    <div class="activity-list">
                      <div v-for="item in recentActivity" :key="item.title" class="activity-item">
                        <div class="activity-icon"><em class="icon ni ni-activity-round-fill"></em></div>
                        <div>
                          <strong>{{ item.title }}</strong>
                          <p class="sub-text mb-0">{{ item.when }}</p>
                        </div>
                        <span class="status-pill">{{ item.status }}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </buyer-layout>
</template>

<style scoped>
.profile-grid {
  --bs-gutter-x: calc(0.75rem + 5px);
  --bs-gutter-y: calc(0.75rem + 5px);
}

.profile-subgrid {
  --bs-gutter-x: calc(0.6rem + 5px);
  --bs-gutter-y: calc(0.6rem + 5px);
}

.nk-block {
  margin-top: 0;
  margin-bottom: 0;
}

.nk-block .card-inner {
  padding: 17px;
}

.profile-card {
  display: grid;
  gap: 8px;
}

.avatar-badge {
  width: 78px;
  height: 78px;
  border-radius: 999px;
  margin: 0 auto;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: 24px;
  font-weight: 800;
  color: #fff;
  background: linear-gradient(135deg, var(--app-coffee-700), var(--app-coffee-800));
}

.profile-meta {
  display: grid;
  gap: 6px;
}

.meta-row {
  display: flex;
  justify-content: space-between;
  gap: 8px;
  border: 1px solid var(--app-border);
  border-radius: var(--app-radius-md);
  padding: 7px 8px;
  background: var(--app-surface-soft);
}

.meta-row strong {
  color: #102a43;
  font-size: 13px;
  text-align: right;
}

.meter-wrap {
  display: grid;
  gap: 6px;
}

.meter-track {
  width: 100%;
  height: 8px;
  border-radius: 999px;
  background: var(--app-border);
  overflow: hidden;
}

.meter-fill {
  height: 100%;
  border-radius: 999px;
  background: linear-gradient(135deg, var(--app-coffee-700), var(--app-coffee-800));
}

.meter-foot {
  font-size: 12px;
  color: #627d98;
}

.profile-actions {
  display: grid;
  gap: 6px;
}

.snapshot-card {
  display: grid;
  gap: 8px;
}

.snapshot-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 8px;
}

.snapshot-grid {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 6px;
}

.snapshot-item {
  display: flex;
  align-items: center;
  gap: 8px;
  border: 1px solid var(--app-border);
  border-radius: var(--app-radius-md);
  padding: 7px 8px;
  background: var(--app-surface-soft);
}

.snapshot-item strong {
  display: block;
  line-height: 1.1;
  color: #102a43;
}

.snapshot-icon {
  width: 32px;
  height: 32px;
  border-radius: 10px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  color: var(--app-coffee-700);
  background: rgba(111, 78, 55, 0.14);
}

.combined-card {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 10px;
}

.combined-col-right {
  border-left: 1px solid var(--app-border);
  padding-left: 10px;
}

.perf-list,
.activity-list {
  display: grid;
  gap: 6px;
}

.perf-item {
  display: grid;
  gap: 4px;
}

.perf-meta {
  display: flex;
  justify-content: space-between;
  font-size: 12px;
}

.perf-meta span {
  color: #486581;
}

.perf-meta strong {
  color: #102a43;
}

.perf-track {
  width: 100%;
  height: 7px;
  border-radius: 999px;
  background: var(--app-border);
  overflow: hidden;
}

.perf-fill {
  height: 100%;
  border-radius: 999px;
  background: linear-gradient(135deg, var(--app-coffee-700), var(--app-coffee-800));
}

.activity-item {
  display: grid;
  grid-template-columns: 32px 1fr auto;
  align-items: center;
  gap: 6px;
  border: 1px solid var(--app-border);
  border-radius: var(--app-radius-md);
  padding: 7px;
  background: var(--app-surface-soft);
}

.activity-item strong {
  color: #102a43;
  font-size: 13px;
}

.activity-icon {
  width: 28px;
  height: 28px;
  border-radius: 8px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  color: var(--app-coffee-700);
  background: rgba(111, 78, 55, 0.14);
}

.status-pill {
  padding: 3px 6px;
  border-radius: 999px;
  font-size: 11px;
  font-weight: 700;
  color: var(--app-coffee-800);
  background: rgba(111, 78, 55, 0.14);
}

@media (max-width: 991px) {
  .combined-card {
    grid-template-columns: 1fr;
  }

  .combined-col-right {
    border-left: 0;
    border-top: 1px solid var(--app-border);
    padding-left: 0;
    padding-top: 8px;
  }
}

@media (max-width: 640px) {
  .snapshot-head {
    flex-direction: column;
    align-items: flex-start;
  }

  .snapshot-grid {
    grid-template-columns: 1fr;
  }

  .activity-item {
    grid-template-columns: 1fr;
  }
}
</style>
