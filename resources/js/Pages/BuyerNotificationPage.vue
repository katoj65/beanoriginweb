<script setup>
import BuyerLayout from '@/Layouts/BuyerLayout.vue';

const notifications = [
  {
    id: 1,
    name: 'Kigezi Cooperative',
    date: '12 Jan',
    title: 'Counteroffer submitted for listed coffee lot',
    message: 'A supplier has sent a counteroffer on your recent bid.',
    unread: false,
    flagged: false,
    label: 'Trade',
  },
  {
    id: 2,
    name: 'Ankole Growers',
    date: '15 Jan',
    title: 'Bid accepted and pending payment confirmation',
    message: 'Your offer was accepted. Please complete payment instructions.',
    unread: false,
    flagged: true,
    label: 'Payment',
  },
  {
    id: 3,
    name: 'Rwenzori Beans',
    date: '11 Jan',
    title: 'Additional compliance document requested',
    message: 'Please upload trade compliance proof to continue processing.',
    unread: true,
    flagged: false,
    label: 'Compliance',
  },
  {
    id: 4,
    name: 'Bugisu Exporters',
    date: '30 Dec',
    title: 'Delivery schedule updated by supplier',
    message: 'The expected delivery date has changed for your pending lot.',
    unread: false,
    flagged: false,
    label: 'Logistics',
  },
  {
    id: 5,
    name: 'Lake Basin Coop',
    date: '28 Dec',
    title: 'Quality report uploaded for your review',
    message: 'A new quality report has been attached to your selected batch.',
    unread: false,
    flagged: false,
    label: 'Quality',
  },
  {
    id: 6,
    name: 'Mountain Arabica',
    date: '26 Dec',
    title: 'Bid expires in 24 hours',
    message: 'Take action before your current bid window closes.',
    unread: false,
    flagged: false,
    label: 'Reminder',
  },
  {
    id: 7,
    name: 'West Nile Supply',
    date: '21 Dec',
    title: 'Invoice generated for accepted purchase',
    message: 'Your invoice is ready for download from the orders section.',
    unread: false,
    flagged: false,
    label: 'Billing',
  },
  {
    id: 8,
    name: 'Elgon Highlands',
    date: '16 Dec',
    title: 'Supplier profile has been verified',
    message: 'One of your watched suppliers now has a verified badge.',
    unread: false,
    flagged: false,
    label: 'System',
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
  <BuyerLayout>
    <div class="notif-page" style="margin:-25px;">
      <div class="card card-bordered notif-card">
        <div class="card-inner border-bottom notif-header">
          <h5 class="title mb-1">Notifications</h5>
          <p class="sub-text mb-0">Recent alerts, trade updates, and system messages.</p>
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
  </BuyerLayout>
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
