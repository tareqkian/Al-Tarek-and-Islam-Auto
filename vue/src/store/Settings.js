import { defineStore } from "pinia";
import {ref} from "vue";
import api from "../axios";
export const useSettingsStore = defineStore("SettingsStore",()=>{
  const settings = ref([])

  const initSettings = async ()=>{
    try {
      const { data } = await api.get("/settings");
      settings.value = data.data
    } catch (e) {
      throw e.response.data.errors
    }
  }

  const handleSettings = async (payload)=>{
    try {
      const { data } = await api.put("/settings/0",payload)
      settings.value = data.data;
    } catch (e) {
      throw e.response.data.errors
    }
  }

  return {
    settings,
    initSettings,
    handleSettings
  }
})
