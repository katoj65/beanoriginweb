<script setup>
import { computed } from 'vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';
import InputError from '@/Components/InputError.vue';
import SubmitButton from '@/Components/SubmitButton.vue';

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
  primary_crop: '',
  soil_type: '',
  water_source_type: '',
  soil_conservation: '',
  water_management: '',
  organic_practices: '',
  shade_tree_coverage: '',
  sustainability_notes: '',
  credit_score: '',
  loan_eligibility: '',
  recommended_loan_limit: '',
  last_credit_review: '',
  credit_notes: '',
});

form.cooperative_farmer_id = preselectedFarmer;

const submit = () => {
  form.post(route('cooperative.farms.store'));
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
                <p class="sub-text mb-0">Capture farm, sustainability, and credit profile details.</p>
              </div>
              <Link :href="route('cooperative.farmers')" class="btn btn-light btn-sm">Back to Farmers</Link>
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

                <div class="col-12 col-md-4">
                  <label class="form-label">Primary Crop</label>
                  <input v-model="form.primary_crop" type="text" class="form-control" placeholder="Coffee, Maize..." />
                  <InputError :message="form.errors.primary_crop" class="mt-2" />
                </div>

                <div class="col-12 col-md-6">
                  <label class="form-label">Soil Type</label>
                  <input v-model="form.soil_type" type="text" class="form-control" placeholder="Loamy, Clay, Sandy..." />
                  <InputError :message="form.errors.soil_type" class="mt-2" />
                </div>

                <div class="col-12 col-md-6">
                  <label class="form-label">Water Source Type</label>
                  <input v-model="form.water_source_type" type="text" class="form-control" placeholder="Rainfed, Borehole, River..." />
                  <InputError :message="form.errors.water_source_type" class="mt-2" />
                </div>

                <div class="col-12">
                  <h6 class="title mb-1">Sustainability</h6>
                </div>

                <div class="col-12 col-md-4">
                  <label class="form-label">Soil Conservation</label>
                  <select v-model="form.soil_conservation" class="form-control">
                    <option value="">Select</option>
                    <option :value="1">Yes</option>
                    <option :value="0">No</option>
                  </select>
                  <InputError :message="form.errors.soil_conservation" class="mt-2" />
                </div>

                <div class="col-12 col-md-4">
                  <label class="form-label">Water Management</label>
                  <select v-model="form.water_management" class="form-control">
                    <option value="">Select</option>
                    <option :value="1">Yes</option>
                    <option :value="0">No</option>
                  </select>
                  <InputError :message="form.errors.water_management" class="mt-2" />
                </div>

                <div class="col-12 col-md-4">
                  <label class="form-label">Organic Practices</label>
                  <select v-model="form.organic_practices" class="form-control">
                    <option value="">Select</option>
                    <option :value="1">Yes</option>
                    <option :value="0">No</option>
                  </select>
                  <InputError :message="form.errors.organic_practices" class="mt-2" />
                </div>

                <div class="col-12 col-md-6">
                  <label class="form-label">Shade Tree Coverage (%)</label>
                  <input v-model="form.shade_tree_coverage" type="number" min="0" max="100" step="0.01" class="form-control" placeholder="0 - 100" />
                  <InputError :message="form.errors.shade_tree_coverage" class="mt-2" />
                </div>

                <div class="col-12 col-md-6">
                  <label class="form-label">Sustainability Notes</label>
                  <textarea v-model="form.sustainability_notes" class="form-control" rows="2"></textarea>
                  <InputError :message="form.errors.sustainability_notes" class="mt-2" />
                </div>

                <div class="col-12">
                  <h6 class="title mb-1">Credit & Loan</h6>
                </div>

                <div class="col-12 col-md-4">
                  <label class="form-label">Credit Score</label>
                  <input v-model="form.credit_score" type="number" min="0" max="1000" step="1" class="form-control" />
                  <InputError :message="form.errors.credit_score" class="mt-2" />
                </div>

                <div class="col-12 col-md-4">
                  <label class="form-label">Loan Eligibility</label>
                  <select v-model="form.loan_eligibility" class="form-control">
                    <option value="">Select</option>
                    <option value="eligible">Eligible</option>
                    <option value="not_eligible">Not Eligible</option>
                    <option value="pending">Pending</option>
                  </select>
                  <InputError :message="form.errors.loan_eligibility" class="mt-2" />
                </div>

                <div class="col-12 col-md-4">
                  <label class="form-label">Recommended Loan Limit</label>
                  <input v-model="form.recommended_loan_limit" type="number" min="0" step="0.01" class="form-control" />
                  <InputError :message="form.errors.recommended_loan_limit" class="mt-2" />
                </div>

                <div class="col-12 col-md-6">
                  <label class="form-label">Last Credit Review</label>
                  <input v-model="form.last_credit_review" type="date" class="form-control" />
                  <InputError :message="form.errors.last_credit_review" class="mt-2" />
                </div>

                <div class="col-12 col-md-6">
                  <label class="form-label">Credit Notes</label>
                  <textarea v-model="form.credit_notes" class="form-control" rows="2"></textarea>
                  <InputError :message="form.errors.credit_notes" class="mt-2" />
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
