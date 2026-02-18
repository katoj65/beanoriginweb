<script setup>
import { computed, watchEffect, ref } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import CooperativeLayout from '@/Layouts/CooperativeLayout.vue';
import InputError from '@/Components/InputError.vue';
import SubmitButton from '@/Components/SubmitButton.vue';

const page = usePage();
const farms = computed(() => page.props.farms ?? []);

const form=useForm({
last_name:'kato',
phone_number:'+256752567534'
});


watchEffect(() => {
if (!form.farm_id && farms.value.length) {
form.farm_id = farms.value[0].id;
}
});





const submit = () => {
form.post(route('verification.farmer.store'), {
preserveScroll: true,
// onSuccess: () => {
// form.reset();
// form.status = 'listed';
// form.farm_id = farms.value[0]?.id ?? '';
// },
});
};

const crops = computed(() => page.props.crops?.data ?? []);
const crop_type = computed(() => page.props.crop_type?.data ?? []);
const process_methods = computed(() => page.props.process_method?.data ?? []);
const crop_grade = computed(() => page.props.crop_grade?.data ?? []);
const showModal = ref(true);
const flashData=computed(()=>{
const f=page.props.flash;
return f;
});



</script>

<template>
<CooperativeLayout>

<div class="container">
<div class="card card-bordered">
<div class="card-inner border-bottom">
<h6 class="title mb-1"><em class="icon ni ni-plus mr-1"></em>Add Batch</h6>
<p class="sub-text mb-0">Create a new coffee harvest batch for sale.</p>
</div>

<div class="card-inner">
<div class="row g-3">
<div class="col-12 col-md-4">
<label class="form-label">Crop Name</label>
 <el-select  placeholder="Select" class="form-control-like">
    <el-option
      v-for="item in crops"
      :key="item.name"
      :label="item.name"
      :value="item.name"
    />
  </el-select>
<InputError class="mt-2" />
</div>

<div class="col-12 col-md-4">
<label class="form-label">Crop Type</label>
<el-select v-model="form.crop_type" placeholder="Select" class="form-control-like">
    <el-option
      v-for="item in crop_type"
      :key="item.name"
      :label="item.name"
      :value="item.name"
    />
  </el-select>
<InputError :message="form.errors.crop_type" class="mt-2" />
</div>

<div class="col-12 col-md-4">
<label class="form-label">Quantity</label>
<input  type="number" min="0" step="0.01" class="form-control" />
<InputError class="mt-2" />
</div>

<div class="col-12 col-md-4">
<label class="form-label">Price</label>
<input  type="number" min="0" step="0.01" class="form-control" />
<InputError class="mt-2" />
</div>

<div class="col-12 col-md-4">
<label class="form-label">Location</label>
<input  type="text" class="form-control" />
<InputError class="mt-2" />
</div>

<div class="col-12 col-md-4">
<label class="form-label">Date Of Havest</label>
<input  type="date" class="form-control" />
<InputError class="mt-2" />
</div>

<div class="col-12 col-md-6">
<label class="form-label">Crop Grade</label>
<el-select  placeholder="Select" class="form-control-like">
    <el-option
      v-for="item in crop_grade"
      :key="item.name"
      :label="item.name"
      :value="item.name"
    />
  </el-select>
<InputError  class="mt-2" />
</div>

<div class="col-12 col-md-6">
<label class="form-label">Process Method</label>
<el-select  placeholder="Select" class="form-control-like">
    <el-option
      v-for="item in process_methods"
      :key="item.name"
      :label="item.name"
      :value="item.name"
    />
  </el-select>
<InputError class="mt-2" />
</div>


<div class="col-12 col-md-4 d-flex align-items-end">
<SubmitButton :title="'Save Produce'" />
</div>
</div>
</div>
</div>
</div>












<div v-if="showModal" class="modal show d-block" id="popupModal" tabindex="-1" aria-labelledby="popupModalLabel" aria-modal="true" role="dialog">
<div class="modal-dialog modal-dialog-centered custom-modal-dialog">
<div class="modal-content">
 <div class="card security-card border-0" style="border-radius: 7px;">


            <div class="card-inner">
              <div class="security-head">

<div class="row">
<div class="col-1">
<span class="security-badge">
<em class="icon ni ni-shield-check"></em>
</span>
</div>
<div class="col-11">
<h5 class="title mb-0" style="font-size:20px">Farmer Security Verification</h5>
<p class="mt-2">
Verify the farmer using last name and telephone before creating a batch. This secures identity and preserves commodity-chain traceability from farm origin to sale.
</p>
</div>
</div>
</div>
</div>
            <div class="card-inner mt-0 pt-0">
              <form class="row g-3" @submit.prevent="submit">
                <div class="col-12">
                  <label class="form-label"><em class="icon ni ni-user mr-1"></em>Last Name</label>
                  <input v-model="form.last_name" type="text" class="form-control" placeholder="Enter your last name" />
                  <InputError :message="form.errors.last_name" class="mt-2" />
                </div>

                <div class="col-12">
                  <label class="form-label"><em class="icon ni ni-call mr-1"></em>Telephone Number</label>
                  <input
                    v-model="form.phone_number"
                    type="text"
                    class="form-control"
                    placeholder="Enter your telephone number"
                  />
                  <InputError :message="form.errors.phone_number" class="mt-2" />
                </div>





<div class="alert alert-danger alert-icon alert-dismissible border-0" v-if="flashData.success">
<em class="icon ni ni-cross-circle"></em> <strong>Verification Failed</strong>! {{ flashData.success.message }}
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
<div v-if="showModal" class="modal-backdrop fade show"></div>

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
