<template>
  <Toast position="top-right">
    <template #message="slotProps">
      <div :class="`alert alert-${slotProps.message.severity} w-100`">
        <button class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button>
        <strong>{{ slotProps.message.summary }}</strong>
        <hr class="message-inner-separator">
        <p class="m-0"> {{ slotProps.message.detail }} </p>
      </div>
    </template>
  </Toast>
</template>

<script>
import Toast from "primevue/toast";
export default {
  name: "MyToast",
  components: {Toast}
}
</script>
