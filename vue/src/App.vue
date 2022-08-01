<script setup>
import ConfirmPopup from "primevue/confirmpopup";
import MyToast from "./components/CustomPrimeVUE/MyToast.vue";
import {realTime} from "./real-time";
realTime();
</script>
<template>
  <ConfirmPopup>
    <template #message="data">
      <div class="popover-arrow" style="position: absolute; left: 0px; transform: translate(129px, 0px);"></div>
      <h3 class="popover-header">{{ data.message.message.header }}</h3>
      <div class="popover-body d-flex align-items-center">
        <i class="fs-5 me-3 text-danger" :class="[data.message.icon]"></i>
        {{ data.message.message.body }}
      </div>
    </template>
  </ConfirmPopup>

  <MyToast />
  <router-view />
</template>

