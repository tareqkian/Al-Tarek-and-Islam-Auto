<template>
  <div class="myFileUpload position-relative" style="height: 200px !important;">
    <div class="myFileUploadWrapper position-absolute w-100 top-0 bottom-0 border">
      <label class="myFileUploadLabel w-100 h-100 d-flex align-items-center justify-content-center">
        <input type="file"
               ref="myFile"
               @change="handleFile"
               class="myFileUploadFile opacity-0 position-absolute"
               accept="image/*">
        <div v-if="!preview" class="text-center">
          <i class="fe fe-upload-cloud fs-50"></i>
          <p class="m-0"><strong>Drag and Drop File</strong></p>
          <p class="m-0"><small class="text-muted">Your File Will be Added Automaticlly</small></p>
          <span class="btn btn-sm btn-light"> Or Select File </span>
          <br>
          Or Leave Empty For Default
          <img style="width: 60px !important;" :src="(defaultImg || 'http://localhost:8000/users/default.png')" class="brround">
        </div>
        <img v-else :src="preview" class="h-100">
      </label>
    </div>
  </div>
</template>

<script setup>
import {ref} from "vue";
const props = defineProps([
  'modelValue',
  'defaultImg'
])
const emits = defineEmits([
  'update:modelValue'
])
const preview = ref(null)
const handleFile = async (e)=>{
  preview.value = null
  const file = e.target.files[0]
  const reader = new FileReader()
  reader.onload = ()=>{
    preview.value = reader.result
    emits('update:modelValue',reader.result)
  }
  reader.readAsDataURL(file)
}
</script>

<style>
.myFileUploadWrapper{
  background: repeating-linear-gradient(
    45deg,
    #606dbc30,
    #606dbc30 10px,
    #46529830 10px,
    #46529830 20px
  ) !important;
}
</style>
