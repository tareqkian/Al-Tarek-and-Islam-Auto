import { defineStore } from "pinia";
import {reactive} from "vue";
import api from "../axios";

export const useAutobanOptionsStore = defineStore('AutobanOptions',()=>{
  const autobanOption = reactive({
    loading: false,
    data: []
  })

  const initAutobanOption = async (autoban) => {
    try {
      autobanOption.loading = true
      const { data } = await api.get(`/autobanOption/${autoban.id}`);
      autobanOption.loading = false
      autobanOption.data = data.data
    } catch (e) {
      throw e.response.data.errors
    }
  }

  const handleAutobanOption = async (autoban,payload) => {
    try {
      await api.put(`/autobanOption/${autoban.id}`,payload);
    } catch (e) {
      throw e.response.data.errors
    }
  }

  return {
    autobanOption,
    initAutobanOption,
    handleAutobanOption
  }
})
