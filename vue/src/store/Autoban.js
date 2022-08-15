import {defineStore} from "pinia";
import {ref} from "vue";
import api from "../axios";

export const useAutobanStore = defineStore("Autoban",()=>{
  const autobanBrands = {
    loading: ref(false),
    data: ref([])
  }

  const initAutobanBrands = async () => {
    try {
      autobanBrands.loading.value = true
      const {data} = await api.get('/autobanBrands')
      autobanBrands.data.value = data.data
      autobanBrands.loading.value = false
    } catch (e) {
      throw e.response.data.errors
    }
  }

  const handleAutobanBrands = async (payload)=>{
    try {
      if ( !payload.id ) await api.post('/autobanBrands',payload)
      else await api.put('/autobanBrands/'+payload.id,payload)
    } catch (e) {
      throw e.response.data.errors
    }
  }

  const distroyAutobanBrand = async (payload)=>{
    try {
      await api.delete('/autobanBrands/'+payload.id)
    } catch (e) {
      throw e.response.data.errors
    }
  }

  return {
    autobanBrands,
    initAutobanBrands,
    handleAutobanBrands,
    distroyAutobanBrand
  }
})
