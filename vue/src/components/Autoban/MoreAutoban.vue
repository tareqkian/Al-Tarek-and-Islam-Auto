<template>
  <div v-for="autoban in array" :key="autoban.id"
       class="py-3 border-bottom position-relative">
    <div @click="emits('getNewDetails',autoban)"
         class="row justify-content-center mx-3" style="cursor: pointer">
      <div class="col-7 text-center">
        <Image width="150" :src="autoban.model.model_image" />
        <span class="d-block mt-2 font-weight-bold">{{ t(autoban.model,'model_title') }}</span>
      </div>
      <div class="col-5 text-start d-flex align-items-center">
        <div>
          <div class="m-1">
            <i class="fa fa-calendar fs-18"></i>
            {{ t(autoban.year,'year_number') }}
          </div>
          <div class="m-1">
            <small class="text-muted">Official</small>
            <br> {{ t(autoban.price,'official',null,1) }}
          </div>
          <div class="m-1">
            {{ t(autoban.type,'type_title') }}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import Image from "primevue/image";
const props = defineProps({
  "array": Array
})
const emits = defineEmits(['getNewDetails'])
</script>

<style scoped>

</style>
