<script setup>
import { computed } from 'vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';
import InputError from '@/Components/InputError.vue';
import SubmitButton from '@/Components/SubmitButton.vue';
import { Back } from '@element-plus/icons-vue';

const page = usePage();
const farmers = computed(() => page.props.farmers ?? []);
const preselectedFarmer = new URLSearchParams(window.location.search).get('farmer_id') ?? '';

const form = useForm({
  cooperative_farmer_id: '',
  farm_name: '',
  garden_name: '',
  location: '',
  area_acres: '',
  number_of_gardens: '',
});

form.cooperative_farmer_id = preselectedFarmer;

const submit = () => {
  form.post(route('cooperative.farms.store'));
};

const goBack = () => {
  router.get(route('cooperative.farmers'));
};
</script>

<template>
  <CooperativeLayout>
    <div class="container">
      <div class="row g-gs">
        <div class="col-12">
          <div class="card card-bordered">
            <div class="card-inner border-bottom d-flex justify-content-between align-items-center">
              <div>
                <h6 class="title mb-1">Create Farm</h6>
                <p class="sub-text mb-0">Capture farm profile details.</p>
              </div>
              <el-button :icon="Back" @click="goBack">Back</el-button>
            </div>

            <div class="card-inner">
              <form class="row g-3" @submit.prevent="submit">
                <div class="col-12 col-md-6">
                  <label class="form-label">Farmer</label>
                  <select v-model="form.cooperative_farmer_id" class="form-control">
                    <option value="">Select farmer</option>
                    <option v-for="farmer in farmers" :key="farmer.id" :value="farmer.id">
                      {{ farmer.name }}
                    </option>
                  </select>
                  <InputError :message="form.errors.cooperative_farmer_id" class="mt-2" />
                </div>

                <div class="col-12 col-md-6">
                  <label class="form-label">Farm Name</label>
                  <input v-model="form.farm_name" type="text" class="form-control" placeholder="Main farm name" />
                  <InputError :message="form.errors.farm_name" class="mt-2" />
                </div>

                <div class="col-12 col-md-6">
                  <label class="form-label">Garden Name</label>
                  <input v-model="form.garden_name" type="text" class="form-control" placeholder="Garden section name" />
                  <InputError :message="form.errors.garden_name" class="mt-2" />
                </div>

                <div class="col-12 col-md-6">
                  <label class="form-label">Location</label>
                  <input v-model="form.location" type="text" class="form-control" placeholder="Village, sub county, district" />
                  <InputError :message="form.errors.location" class="mt-2" />
                </div>

                <div class="col-12 col-md-4">
                  <label class="form-label">Area (Acres)</label>
                  <input v-model="form.area_acres" type="number" min="0" step="0.01" class="form-control" placeholder="0.00" />
                  <InputError :message="form.errors.area_acres" class="mt-2" />
                </div>

                <div class="col-12 col-md-4">
                  <label class="form-label">Number of Gardens</label>
                  <input v-model="form.number_of_gardens" type="number" min="0" step="1" class="form-control" placeholder="0" />
                  <InputError :message="form.errors.number_of_gardens" class="mt-2" />
                </div>

                <div class="col-12">
                  <SubmitButton :title="'Save Farm'" :status="form.processing" />
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </CooperativeLayout>
</template>
