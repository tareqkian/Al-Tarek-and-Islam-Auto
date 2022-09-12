<template>
  <div class="form-floating my-2">
    <Dropdown
      v-if="!multiple"
      v-model="vModel"
      :options="autobanBrands.data"
      filter filter-placeholder="Search"
      option-value="id"
      :option-label="opt=>t(opt,'brand_title')"
      class="form-control d-flex align-items-stretch"
      :class="[!error || 'is-invalid']"
      placeholder="Brand" />
    <MultiSelect
      v-if="multiple"
      v-model="vModel"
      :selectAll="false"
      :options="autobanBrands.data"
      :option-label="opt=>t(opt,'brand_title')"
      placeholder="Brands"
      class="form-control d-flex align-items-stretch"
      :class="[!error || 'is-invalid']"
      filter filter-placeholder="Search" >
      <template #value="slotProps">
        <div class="row">
          <div style="width: fit-content"
               v-for="option of slotProps.value"
               :key="option.id">
            <img :src="option.brand_image" class="mr-2" width="27" />
            {{option.brand_title}}
          </div>
        </div>
        <template v-if="!slotProps.value || slotProps.value.length === 0">
          Brand
        </template>
      </template>
      <template #option="slotProps">
        <div class="country-item d-flex justify-content-start align-items-center">
          <img :src="slotProps.option.brand_image" class="mx-3" width="50" />
          {{slotProps.option.brand_title}}
        </div>
      </template>
    </MultiSelect>
    <label> Brand </label>
    <div class="invalid-feedback">
      <ul>
        <li v-for="err in error" :key="err"> {{err}} </li>
      </ul>
    </div>
  </div>
</template>

<script setup>
import Dropdown from "primevue/dropdown";
import MultiSelect from 'primevue/multiselect';

import {computed} from "vue";
import {useAutobanStore} from "../../store/Autoban";
const props = defineProps([
  'multiple',
  'modelValue',
  'error'
])
const emits = defineEmits([
  'update:modelValue',
])
const vModel = computed({
  get: () => props.modelValue,
  set: (value) => emits('update:modelValue', value)
})
const AutobanStore = useAutobanStore()
const autobanBrands = computed(()=>AutobanStore.autobanBrands)
AutobanStore.initAutobanBrands()


</script>

<style scoped>

</style>
