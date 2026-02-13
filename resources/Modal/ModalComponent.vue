<script setup>
import { ref } from 'vue';
const showModal = ref(true);
</script>


<template>
<div v-if="showModal" class="modal show d-block" id="popupModal" tabindex="-1" aria-labelledby="popupModalLabel" aria-modal="true" role="dialog">
<div class="modal-dialog modal-dialog-centered custom-modal-dialog">
<div class="modal-content" style="border:solid 5px gray;border-radius: 30px;">
<div class="modal-header">
<h5 class="modal-title" id="popupModalLabel">Create Profile</h5>


<!-- <button type="button" class="btn-close" aria-label="Close" @click="showModal = false"></button> -->

</div>
<div class="modal-body" style="height:500px;">




some information






</div>
</div>
</div>
</div>
<div v-if="showModal" class="modal-backdrop fade show"></div>
</template>





<style scoped>
.custom-modal-dialog {
  max-width: 60%;
}

@media (max-width: 768px) {
  .custom-modal-dialog {
    max-width: 95%;
  }
}
</style>
