<template>
  <Dialog
    modal
    dismissableMask
    class="modal-content modal-lg"
    content-class="modal-body"
    :showHeader="false"
    v-model:visible="userDialogShow">
    <form @submit.prevent="emit('handleUserForm')">
      <div class="form-floating my-2">
        <input class="form-control"
               v-model="userForm.name"
               :class="[errors.name ? 'is-invalid' : '']"
               placeholder="Name">
        <label> Name </label>
        <div class="invalid-feedback">
          <ul>
            <li v-for="err in errors.name" :key="err"> {{err}} </li>
          </ul>
        </div>
      </div>
      <div v-if="!profile" class="form-floating my-2">
        <input class="form-control"
               v-model="userForm.email"
               :class="[errors.email ? 'is-invalid' : '']"
               placeholder="Email">
        <label> Email </label>
        <div class="invalid-feedback">
          <ul>
            <li v-for="err in errors.email" :key="err"> {{err}} </li>
          </ul>
        </div>
      </div>
      <div class="my-2">
        <MyFileUpload v-model="userForm.avatar" :defaultImg="defaultImg" />
        <div class="invalid-feedback">
          <ul>
            <li v-for="err in errors.email" :key="err"> {{err}} </li>
          </ul>
        </div>
      </div>
      <div v-if="!profile" class="form-floating my-2">
        <Dropdown
          v-model="userForm.role_id"
          :options="roles.data"
          filter
          filter-placeholder="Search"
          option-value="id"
          :option-label="opt=>t(opt,'display_name')"
          class="form-control d-flex align-items-stretch"
          :class="[errors.role_id ? 'is-invalid' : '']"
          placeholder="Role" />
        <label> Role </label>
        <div class="invalid-feedback">
          <ul>
            <li v-for="err in errors.role" :key="err"> {{err}} </li>
          </ul>
        </div>
      </div>
      <div v-if="!profile" v-for="(device,index) in devices" :key="index" class="form-floating my-2">
        <input class="form-control"
               type="number"
               v-model="userForm[device.device]"
               :class="[errors[device.device] ? 'is-invalid' : '']"
               :placeholder="device.device">
        <label> {{ device.device }} </label>
        <div class="invalid-feedback">
          <ul>
            <li v-for="err in errors[device.device]" :key="err"> {{err}} </li>
          </ul>
        </div>
      </div>
      <div class="modal-footer d-flex justify-content-center pb-0">
        <button type="submit" class="btn btn-primary" :class="[!loading || 'btn-loading']">Save</button>
        <button type="button" class="btn btn-secondary" @click="userDialogShow = !userDialogShow">Close</button>
      </div>
    </form>
  </Dialog>
</template>

<script setup>
import Dialog from "primevue/dialog";
import Dropdown from "primevue/dropdown"
import MyFileUpload from "/src/components/MyFileUpload.vue"

import {computed, ref} from "vue";
import {useSystemSettings} from "/src/store/SystemSettings";
import {useRolesStore} from "/src/store/Roles";

const props = defineProps({
  profile: Boolean,
  modelValue: { type: Boolean, default: false },
  loading: Boolean,
  errors: Object,
  defaultImg: String,
  userForm: Object
})

const emit = defineEmits(['handleUserForm', 'update:modelValue' ])

const userDialogShow = computed({
  get: () => props.modelValue ,
  set: (value) => emit('update:modelValue', value)
})

const systemSettingsStore = useSystemSettings();
const devices = computed(()=>systemSettingsStore.devices)
// devices.value.length || systemSettingsStore.initDevices();
systemSettingsStore.initDevices();

const rolesStore = useRolesStore();
const roles = computed(()=> rolesStore.roles )
// roles.value.data.length || rolesStore.initROLES()
rolesStore.initROLES()

</script>

<style scoped>

</style>
