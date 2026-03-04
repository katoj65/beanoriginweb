<script setup>
import { computed, onMounted } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';
import { Back, ShoppingCart, Money } from '@element-plus/icons-vue';

const page = usePage();
const cartItems = computed(() => page.props.cart_items ?? []);

const goBack = () => {
  router.get(route('market.index'));
};

const openBatch = (batchId) => {
  if (!batchId) return;
  router.get(route('market.show', { id: batchId }));
};

const formatMoney = (value) => {
  const amount = Number(value ?? 0);
  if (Number.isNaN(amount)) return 'N/A';
  return amount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
};

const formatDateTime = (value) => {
  if (!value) return 'N/A';
  const date = new Date(value);
  if (Number.isNaN(date.getTime())) return String(value);
  return date.toLocaleString();
};

const cartTotal = computed(() =>
  cartItems.value.reduce((sum, item) => sum + (Number(item?.line_total) || 0), 0)
);

const checkout = () => {
  // Checkout flow route/service can be wired here when ready.
};



onMounted(()=>{
console.log(page.props);
});









</script>







<template>
  <CooperativeLayout>
    <div class="container">
      <div class="card card-bordered cart-page-card">
        <div class="card-inner border-bottom cart-head">
          <div>
            <h6 class="title mb-1"><em class="icon ni ni-cart mr-1"></em>Shopping Cart</h6>
            <p class="sub-text mb-0">Items you selected from Batch Market.</p>
          </div>
          <el-button-group>
            <el-button plain :icon="Back" @click="goBack">Back</el-button>
            <el-button plain :icon="ShoppingCart">Total: {{ formatMoney(cartTotal) }}</el-button>
            <el-button type="primary" :icon="Money" @click="checkout" :disabled="!cartItems.length">Checkout</el-button>
          </el-button-group>
        </div>

        <div class="card-inner cart-table-body">
          <div v-if="cartItems.length" class="table-responsive">
            <table class="table table-sm table-middle mb-0 cart-table">
              <thead>
                <tr>
                  <th>Batch</th>
                  <th>Commodity</th>
                  <th>Type</th>
                  <th>Grade</th>
                  <th>Qty</th>
                  <th>Unit Price</th>
                  <th>Total</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in cartItems" :key="item.id">
                  <td>{{ item.batch_code ?? `#${item.batch_id}` }}</td>
                  <td class="text-capitalize">{{ item.commodity_name ?? 'N/A' }}</td>
                  <td class="text-capitalize">{{ item.commodity_type ?? 'N/A' }}</td>
                  <td class="text-capitalize">{{ item.grade ?? 'N/A' }}</td>
                  <td>{{ item.quantity ?? 1 }}</td>
                  <td>{{ formatMoney(item.unit_price) }}</td>
                  <td>{{ formatMoney(item.line_total) }}</td>
                  <td>
                    <el-button plain size="small" @click.stop="openBatch(item.batch_id)">View</el-button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div v-else class="empty-state">Your cart is empty.</div>
        </div>
      </div>
    </div>
  </CooperativeLayout>
</template>

<style scoped>
.cart-page-card {
  border-radius: 12px;
}

.cart-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  flex-wrap: wrap;
}

.cart-table-body {
  padding: 0 !important;
}

.cart-table {
  width: 100%;
  margin: 0;
}

.cart-table th {
  background: #f8fafc;
  color: #526484;
  font-weight: 600;
  white-space: nowrap;
}

.cart-table td {
  white-space: nowrap;
}

.empty-state {
  border: 1px dashed #d9e2f0;
  border-radius: 10px;
  background: #f8fafc;
  padding: 16px 24px;
  color: #64748b;
}
</style>
