<script setup>
import { ref, computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';
import SubmitButton from '@/Components/SubmitButton.vue';
import InputError from '@/Components/InputError.vue';
import SelectBusinessComponent from '@/Components/SelectBusinessComponent.vue';

const showModal = ref(true);
const props = defineProps({
  title: String,
  response: Object,
});

const user = computed(() => {
  return props.response?.user ?? {};
});

const capitalizeFirst = (value) => {
  const text = String(value ?? '').trim();
  if (!text) return '';
  return text.charAt(0).toUpperCase() + text.slice(1);
};

const displayFirstName = computed(() => capitalizeFirst(user.value?.fname));
const displayLastName = computed(() => capitalizeFirst(user.value?.lname));
const displayFullName = computed(() => `${displayFirstName.value} ${displayLastName.value}`.trim() || 'User Account');

const userInitials = computed(() => {
  const first = displayFirstName.value;
  const last = displayLastName.value;
  return `${first.charAt(0)}${last.charAt(0)}`.replace(/\s/g, '').toUpperCase() || 'UA';
});

const profile_state = computed(() => {
  return props.response?.user_profile_exists;
});

const profile = computed(() => {
  return props.response?.user_profile;
});

const gender = ref([
  { label: 'Male', value: 'male' },
  { label: 'Female', value: 'female' },
  { label: 'Other', value: 'other' },
]);

const form = useForm({
  gender: '',
  dob: '',
  tel: '',
  address: '',
});

const isLoading = ref(false);
const submit = () => {
  isLoading.value = true;
  form.post('/store/profile', {
    onFinish: () => {
      isLoading.value = false;
    },
  });
};
</script>

<template>
  <app-layout>
    <div class="nk-block">
      <div class="card border p-4" style="height: 550px;">
        <el-skeleton :rows="10" />
      </div>

      <div
        v-if="showModal"
        class="modal show d-block profile-modal-wrap"
        id="popupModal"
        tabindex="-1"
        aria-labelledby="popupModalLabel"
        aria-modal="true"
        role="dialog"
      >
        <div class="modal-dialog modal-dialog-centered custom-modal-dialog">
          <div v-if="profile_state == false" class="modal-content responsive-modal-content">
            <div class="modal-body p-0 responsive-modal-body">
              <div class="row g-0 modal-split-row">
                <div class="col-12 col-md-4 modal-left-pane">
                  <div class="left-pane-shell">
                    <div class="user-card user-card-s2 mb-3">
                      <div class="user-avatar lg profile-user-avatar">
                        <span>{{ userInitials }}</span>
                        <div class="status dot dot-lg dot-success"></div>
                      </div>
                      <div class="user-info">
                        <h6 class="mb-0">{{ displayFullName }}</h6>
                        <span class="sub-text">User Account</span>
                      </div>
                    </div>

                    <ul class="team-info modal-team-info">
                      <li>
                        <span>Email</span>
                        <span>{{ user.email }}</span>
                      </li>
                    </ul>

                    <div class="profile-tip-card">
                      <h6 class="title mb-1">Complete Your Profile</h6>
                      <p class="sub-text mb-0">This helps us personalize your dashboard and unlock business setup.</p>
                    </div>
                  </div>
                </div>

                <div class="col-12 col-md-8 modal-right-pane">
                  <div class="card h-100 border-0 rounded-0 modal-form-shell">
                    <div class="card-inner p-3 p-md-4">
                      <div class="card-title-group mb-2">
                        <div class="card-title">
                          <h5 class="title mb-1 profile-modal-title">Create your profile</h5>
                          <p class="sub-text mb-0">Add your basic details to continue.</p>
                        </div>
                      </div>

                      <form class="mt-3 profile-form-card" @submit.prevent="submit">
                        <div class="form-group">
                          <label class="form-label" for="default-01">
                            Gender
                            <input-error :message="form.errors.gender" />
                          </label>
                          <div class="form-control-wrap">
                            <el-select v-model="form.gender" placeholder="Select your gender" style="width: 100%" id="default-01">
                              <el-option
                                v-for="(item, key) in gender"
                                :key="key"
                                :label="item.label"
                                :value="item.value"
                              />
                            </el-select>
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="form-label" for="default-dob">
                            Date of Birth
                            <input-error :message="form.errors.dob" />
                          </label>
                          <div class="form-control-wrap">
                            <input
                              type="date"
                              class="form-control"
                              id="default-dob"
                              placeholder="Enter date of birth"
                              v-model="form.dob"
                            />
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="form-label" for="default-02">
                            Telephone Number
                            <input-error :message="form.errors.tel" />
                          </label>
                          <div class="form-control-wrap">
                            <input
                              type="text"
                              class="form-control"
                              id="default-02"
                              placeholder="Enter telephone number"
                              v-model="form.tel"
                            />
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="form-label" for="default-04">
                            Address
                            <input-error :message="form.errors.address" />
                          </label>
                          <div class="form-control-wrap">
                            <input
                              type="text"
                              class="form-control"
                              id="default-04"
                              placeholder="Enter your address"
                              v-model="form.address"
                            />
                          </div>
                        </div>

                        <div class="form-group mb-0 mt-4">
                          <submit-button :title="'Save'" :status="isLoading" />
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <select-business-component v-else :user="user" :profile="profile" />
        </div>
      </div>

      <div v-if="showModal" class="modal-backdrop fade show"></div>
    </div>
  </app-layout>
</template>

<style scoped>
.custom-modal-dialog {
  max-width: 62%;
  margin: 1.2rem auto;
}

.profile-modal-wrap {
  backdrop-filter: blur(1.5px);
}

.responsive-modal-content {
  border: 0;
  border-radius: 18px;
  overflow: hidden;
  box-shadow: 0 22px 60px rgba(15, 23, 42, 0.18);
}

.responsive-modal-body {
  max-height: min(86vh, 760px);
  overflow-y: auto;
}

.modal-left-pane {
  background: linear-gradient(160deg, #f8f1e8 0%, #f5f7fb 100%);
  border-right: 1px solid #e9edf5;
}

.left-pane-shell {
  height: 100%;
  padding: 1.7rem 1.2rem 1.2rem;
  display: grid;
  align-content: start;
  gap: 0.9rem;
}

.profile-user-avatar {
  background: linear-gradient(135deg, var(--app-coffee-700), var(--app-coffee-800));
}

.modal-team-info {
  margin-bottom: 0;
}

.modal-team-info li {
  font-size: 0.85rem;
}

.profile-tip-card {
  border: 1px solid #e6ebf3;
  background: #fff;
  border-radius: 12px;
  padding: 0.8rem 0.9rem;
}

.modal-right-pane {
  background: #fff;
}

.modal-form-shell {
  background: transparent;
}

.profile-form-card {
  border: 1px solid #edf2f7;
  border-radius: 14px;
  padding: 0.95rem 1rem;
  background: #fcfdff;
}

.profile-form-card .form-group + .form-group {
  margin-top: 0.9rem;
}

.profile-form-card .form-label {
  font-weight: 600;
}

.profile-modal-title {
  font-size: 1.45rem;
  line-height: 1.2;
}

@media (max-width: 768px) {
  .custom-modal-dialog {
    max-width: 95%;
    margin: 0.7rem auto;
  }

  .responsive-modal-content {
    border-radius: 14px;
  }

  .modal-left-pane {
    border-right: 0;
    border-bottom: 1px solid #e9edf5;
  }

  .left-pane-shell {
    padding: 1rem;
  }

  .profile-form-card {
    padding: 0.85rem;
    border-radius: 12px;
  }
}
</style>
