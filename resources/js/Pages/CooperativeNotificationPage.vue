<script setup>
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';

const notifications = [
  {
    id: 1,
    name: 'Abu Bin Ishtiyak',
    date: '12 Jan',
    title: 'Unable to select currency when ordering',
    message: 'I cannot select currency on the buy order page.',
    unread: false,
    flagged: false,
    label: 'Issue',
  },
  {
    id: 2,
    name: 'Jackelyn Dugas',
    date: '15 Jan',
    title: 'Have not received payment yet',
    message: 'I recently completed a transaction but have not received funds yet.',
    unread: false,
    flagged: true,
    label: 'Payment',
  },
  {
    id: 3,
    name: 'Mayme Johnston',
    date: '11 Jan',
    title: 'Cannot submit KYC application',
    message: 'I cannot upload my documents in the KYC flow.',
    unread: true,
    flagged: false,
    label: 'Compliance',
  },
  {
    id: 4,
    name: 'Jake Smith',
    date: '30 Dec',
    title: 'Follow-up on pending transfer',
    message: 'Please check why the transfer has not reflected yet.',
    unread: false,
    flagged: false,
    label: 'Trade',
  },
  {
    id: 5,
    name: 'Amanda Moore',
    date: '28 Dec',
    title: 'Wallet still shows verify warning',
    message: 'I already verified my wallet but warning is still visible.',
    unread: false,
    flagged: false,
    label: 'System',
  },
  {
    id: 6,
    name: 'Rebecca Valdez',
    date: '26 Dec',
    title: 'Subscription cancellation request',
    message: 'I need assistance with cancellation and refund process.',
    unread: false,
    flagged: false,
    label: 'Support',
  },
  {
    id: 7,
    name: 'Charles Greene',
    date: '21 Dec',
    title: 'Payment not received for completed order',
    message: 'Completed trade is confirmed but payout is still pending.',
    unread: false,
    flagged: false,
    label: 'Payment',
  },
  {
    id: 8,
    name: 'Ethan Anderson',
    date: '16 Dec',
    title: 'Order form validation issue',
    message: 'Currency field fails to save when placing an order.',
    unread: false,
    flagged: false,
    label: 'Issue',
  },
];

const initials = (name) => {
  const parts = name.trim().split(/\s+/);
  const first = parts[0]?.charAt(0) ?? '';
  const second = parts[1]?.charAt(0) ?? '';
  return `${first}${second}`.toUpperCase();
};
</script>

<template>
  <CooperativeLayout>
    <div class="notif-page" style="margin:-25px;">
      <div class="card card-bordered notif-card">
        <div class="card-inner border-bottom notif-header">
          <h5 class="title mb-1">Notifications</h5>
          <p class="sub-text mb-0">Recent alerts, requests, and system updates.</p>
        </div>

        <div class="card-inner p-0 notif-body">
          <div class="notif-list">
            <article
              v-for="item in notifications"
              :key="item.id"
              class="notif-item"
              :class="{ unread: item.unread }"
            >
              <div class="notif-avatar">{{ initials(item.name) }}</div>

              <div class="notif-content">
                <div class="notif-top">
                  <div class="sender-wrap">
                    <h6 class="sender mb-0">{{ item.name }}</h6>
                    <span class="label-tag">{{ item.label }}</span>
                  </div>
                  <div class="meta-wrap">
                    <em v-if="item.flagged" class="icon ni ni-star-fill flagged"></em>
                    <span class="date">{{ item.date }}</span>
                  </div>
                </div>

                <h6 class="notif-title mb-1">{{ item.title }}</h6>
                <p class="notif-message mb-0">{{ item.message }}</p>
              </div>
            </article>
          </div>
        </div>
      </div>
    </div>
  </CooperativeLayout>
</template>

<style scoped>
.notif-page {
  margin-top: -1rem;
  margin-right: calc(var(--bs-gutter-x, 1.5rem) * -0.5);
  margin-left: calc(var(--bs-gutter-x, 1.5rem) * -0.5);
  padding: 0;
  height: calc(100vh - 68px);
  display: flex;
}

.notif-card {
  border-radius: 0;
  margin: 0;
  width: 100%;
  height: 100%;
  min-height: 0;
  display: flex;
  flex-direction: column;
  border-left: 0;
  border-right: 0;
}

.notif-header {
  flex: 0 0 auto;
}

.notif-body {
  flex: 1 1 auto;
  min-height: 0;
}

.notif-list {
  display: grid;
  height: 100%;
  overflow-y: auto;
}

.notif-item {
  display: grid;
  grid-template-columns: auto 1fr;
  gap: 12px;
  padding: 14px 18px;
  border-bottom: 1px solid #edf2f7;
  transition: background-color 0.2s ease;
}

.notif-item:last-child {
  border-bottom: 0;
}

.notif-item:hover {
  background: #fafafa;
}

.notif-item.unread {
  background: #f8fbff;
}

.notif-avatar {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: 0.78rem;
  font-weight: 700;
  color: #334155;
  background: #e2e8f0;
}

.notif-content {
  min-width: 0;
}

.notif-top {
  display: flex;
  justify-content: space-between;
  gap: 10px;
  align-items: flex-start;
  margin-bottom: 4px;
}

.sender-wrap {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  flex-wrap: wrap;
}

.sender {
  font-size: 0.92rem;
  color: #111827;
}

.label-tag {
  font-size: 0.66rem;
  font-weight: 700;
  letter-spacing: 0.04em;
  text-transform: uppercase;
  color: #1e40af;
  background: #e0e7ff;
  border-radius: 999px;
  padding: 2px 7px;
}

.meta-wrap {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  color: #64748b;
}

.flagged {
  color: #ca8a04;
}

.date {
  font-size: 0.75rem;
  white-space: nowrap;
}

.notif-title {
  font-size: 0.9rem;
  color: #111827;
  line-height: 1.35;
}

.notif-message {
  font-size: 0.83rem;
  color: #64748b;
  line-height: 1.5;
}

@media (max-width: 640px) {
  .notif-page {
    margin-top: -1rem;
    height: calc(100vh - 64px);
  }

  .notif-item {
    grid-template-columns: 1fr;
    gap: 10px;
  }

  .notif-avatar {
    width: 32px;
    height: 32px;
  }

  .notif-top {
    flex-direction: column;
    align-items: flex-start;
  }
}
</style>
