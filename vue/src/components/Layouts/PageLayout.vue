<template>
  <div class="app-content main-content">
    <div class="side-app main-container">
      <!--Page header-->
      <div class="page-header d-lg-flex d-block my-3">
        <div class="page-leftheader">
          <div class="page-title">{{ t(meta,"title") }}</div>
        </div>
      </div>
      <!--End Page header-->
      <slot></slot>
    </div>
  </div>
</template>
<script setup>
const props = defineProps({
  meta: Object
})
</script>
<style scoped>
.app-content{
  overflow: unset !important ;
}
</style>
