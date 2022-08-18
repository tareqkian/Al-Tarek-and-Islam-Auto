import {defineStore} from "pinia";
import {ref} from "vue";
import api from "../axios";

export const useAutobanStore = defineStore("Autoban",()=>{
  const autobanBrands = {
    loading: ref(false),
    data: ref([])
  }
  const autobanModels = {
    loading: ref(false),
    data: ref([])
  }
  const autobanTypes = {
    loading: ref(false),
    data: ref([])
  }
  const autobanYears = {
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
  const initAutobanModels = async () => {
    try {
      autobanModels.loading.value = true
      const {data} = await api.get('/autobanModels')
      autobanModels.data.value = data.data
      autobanModels.loading.value = false
    } catch (e) {
      throw e.response.data.errors
    }
  }
  const initAutobanTypes = async () => {
    try {
      autobanTypes.loading.value = true
      const {data} = await api.get('/autobanTypes')
      autobanTypes.data.value = data.data
      autobanTypes.loading.value = false
    } catch (e) {
      throw e.response.data.errors
    }
  }
  const initAutobanYears = async () => {
    try {
      autobanYears.loading.value = true
      const {data} = await api.get('/autobanYears')
      autobanYears.data.value = data.data
      autobanYears.loading.value = false
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
  const handleAutobanModels = async (payload)=>{
    try {
      if ( !payload.id ) await api.post('/autobanModels',payload)
      else await api.put('/autobanModels/'+payload.id,payload)
    } catch (e) {
      throw e.response.data.errors
    }
  }
  const handleAutobanTypes = async (payload)=>{
    try {
      if ( !payload.id ) await api.post('/autobanTypes',payload)
      else await api.put('/autobanTypes/'+payload.id,payload)
    } catch (e) {
      throw e.response.data.errors
    }
  }
  const handleAutobanYears = async (payload)=>{
    try {
      if ( !payload.id ) await api.post('/autobanYears',payload)
      else await api.put('/autobanYears/'+payload.id,payload)
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
  const distroyAutobanModel = async (payload)=>{
    try {
      await api.delete('/autobanModels/'+payload.id)
    } catch (e) {
      throw e.response.data.errors
    }
  }
  const distroyAutobanType = async (payload)=>{
    try {
      await api.delete('/autobanTypes/'+payload.id)
    } catch (e) {
      throw e.response.data.errors
    }
  }
  const distroyAutobanYear = async (payload)=>{
    try {
      await api.delete('/autobanYears/'+payload.id)
    } catch (e) {
      throw e.response.data.errors
    }
  }

  return {
    autobanBrands,
    autobanModels,
    autobanTypes,
    autobanYears,

    initAutobanBrands,
    initAutobanModels,
    initAutobanTypes,
    initAutobanYears,

    handleAutobanBrands,
    handleAutobanModels,
    handleAutobanTypes,
    handleAutobanYears,

    distroyAutobanBrand,
    distroyAutobanModel,
    distroyAutobanType,
    distroyAutobanYear,
  }
})
