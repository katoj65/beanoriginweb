<script setup>
import { useForm } from '@inertiajs/vue3';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';
import InputError from '@/Components/InputError.vue';
import SubmitButton from '@/Components/SubmitButton.vue';

const form = useForm({
  last_name: '',
  telephone_number: '',
});

const submit = () => {
  form.post(route('cooperative.verification.store'));
};
</script>

<template>
  <CooperativeLayout>
    <div class="container py-4">
      <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
          <div class="card card-bordered security-card">
            <div class="card-inner border-bottom">
              <div class="security-head">
                <span class="security-badge">
                  <em class="icon ni ni-shield-check"></em>
                </span>
                <h5 class="title mb-1">Security Verification</h5>
              </div>
              <p class="sub-text mb-0">Verify your identity using your last name and telephone number.</p>
            </div>
            <div class="card-inner">
              <form class="row g-3" @submit.prevent="submit">
                <div class="col-12">
                  <label class="form-label"><em class="icon ni ni-user mr-1"></em>Last Name</label>
                  <input v-model="form.last_name" type="text" class="form-control" placeholder="Enter your last name" />
                  <InputError :message="form.errors.last_name" class="mt-2" />
                </div>

                <div class="col-12">
                  <label class="form-label"><em class="icon ni ni-call mr-1"></em>Telephone Number</label>
                  <input
                    v-model="form.telephone_number"
                    type="text"
                    class="form-control"
                    placeholder="Enter your telephone number"
                  />
                  <InputError :message="form.errors.telephone_number" class="mt-2" />
                </div>

                <div class="col-12">
                  <SubmitButton :title="'Verify'" :status="form.processing" />
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </CooperativeLayout>
</template>

<style scoped>
.security-card {
  border-radius: 12px;
  border: 1px solid #dbe4ee;
  box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
  background: linear-gradient(180deg, #ffffff 0%, #f9fbfd 100%);
}

.security-head {
  display: flex;
  align-items: center;
  gap: 10px;
}

.security-badge {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  background: #e6f4ea;
  color: #198754;
}

.security-card .form-control {
  min-height: 42px;
  border-radius: 10px;
  border-color: #d6dee8;
}

.security-card .form-label {
  font-weight: 600;
  color: #344357;
}
</style>
