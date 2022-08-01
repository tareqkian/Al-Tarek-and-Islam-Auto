import { defineStore } from "pinia";
import {ref} from "vue";

export const useSystemSettings = defineStore("SystemSettings",()=>{
  const devices = ref([])

  const initDevices = async ()=>{
    try {
      devices.value = [
        {"device": "Desktop", "count": 1},
        {"device": "Mobile", "count": 1}
      ]
    } catch (e) {
      throw e.response.data.errors
    }
  }

  return {
    devices,
    initDevices
  }
})
