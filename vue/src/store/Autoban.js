import {defineStore} from "pinia";
import {ref} from "vue";
import api from "../axios";

export const useAutobanStore = defineStore("Autoban",()=>{
  const autobans = {
    loading: ref(false),
    data: ref([]),
    pagination: ref({})
  }
  const autobanBrands = {
    loading: ref(false),
    data: ref([])
  }
  const autobanModels = {
    loading: ref(false),
    data: ref([]),
    pagination: ref({})
  }
  const autobanTypes = {
    loading: ref(false),
    data: ref([]),
    pagination: ref({})
  }
  const autobanYears = {
    loading: ref(false),
    data: ref([]),
    pagination: ref({})
  }
  const autobanPriceTasks = {
    loading: ref(false),
    data: ref([])
  }

  const initAutobans = async (meta={},filter = '') => {
    try {
      autobans.loading.value = true
      const {data} = await api.get(`/autoban?page=${meta.page+1 || 0}&perPage=${meta.rows || 0}&filter=${filter}`)
      autobans.data.value = data.data
      autobans.pagination.value = data.meta
      autobans.loading.value = false
    } catch (e) {
      throw e.response.data.errors
    }
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
  const initAutobanModels = async (meta={}) => {
    try {
      autobanModels.loading.value = true
      const {data} = await api.get(`/autobanModels?page=${meta.page+1 || 0}&perPage=${meta.rows || 0}`)
      autobanModels.data.value = data.data
      autobanModels.pagination.value = data.meta
      autobanModels.loading.value = false
    } catch (e) {
      throw e.response.data.errors
    }
  }
  const initAutobanTypes = async (meta={}) => {
    try {
      autobanTypes.loading.value = true
      const {data} = await api.get(`/autobanTypes?page=${meta.page+1 || 0}&perPage=${meta.rows || 0}`)
      autobanTypes.data.value = data.data
      autobanTypes.pagination.value = data.meta
      autobanTypes.loading.value = false
    } catch (e) {
      throw e.response.data.errors
    }
  }
  const initAutobanYears = async (meta={}) => {
    try {
      autobanYears.loading.value = true
      const {data} = await api.get(`/autobanYears?page=${meta.page+1 || 0}&perPage=${meta.rows || 0}`)
      autobanYears.data.value = data.data
      autobanYears.pagination.value = data.meta
      autobanYears.loading.value = false
    } catch (e) {
      throw e.response.data.errors
    }
  }
  const initAutobanPriceTask = async (payload,meta={}) => {
    try {
      autobans.loading.value = true
      const {data} = await api.get(`/autobanPriceTasks/${payload.id}?page=${meta.page+1 || 0}&perPage=${meta.rows || 0}`)
      autobans.data.value = data.data
      autobans.pagination.value = data.meta
      autobans.loading.value = false
    } catch (e) {
      throw e.response.data.errors
    }
  }
  const initAutobanPriceTasks = async () => {
    try {
      autobanPriceTasks.loading.value = true
      const {data} = await api.get('/autobanPriceTasks')
      autobanPriceTasks.data.value = data.data
      autobanPriceTasks.loading.value = false
    } catch (e) {
      throw e.response.data.errors
    }
  }

  const handleAutobans = async (payload)=>{
    try {
      if ( !payload.id ) {
        await api.post('/autoban',payload)
      }
      else {
        await api.put('/autoban/'+payload.id,payload)
      }
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
  const handleAutobanPrices = async (payload)=>{
    try {
      await api.put('/autobanPrices/'+payload.id,payload)
    } catch (e) {
      throw e.response.data.errors
    }
  }
  const handleAutobanPriceTasks = async (payload)=>{
    try {
      if ( !payload.id ) await api.post('/autobanPriceTasks',payload)
      else await api.put('/autobanPriceTasks/'+payload.id,payload)
    } catch (e) {
      throw e.response.data.errors
    }
  }


  const distroyAutoban = async (payload)=>{
    try {
      await api.delete('/autoban/'+payload.id)
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
  const distroyAutobanPriceTask = async (payload)=>{
    try {
      await api.delete('/autobanPriceTasks/'+payload.id)
    } catch (e) {
      throw e.response.data.errors
    }
  }

  const reOrder = async (payload)=>{
    try {
      await api.post('/orderAutoban',payload)
    } catch (e) {
      throw e.response.data.errors
    }
  }

  return {
    autobans,
    autobanBrands,
    autobanModels,
    autobanTypes,
    autobanYears,
    autobanPriceTasks,

    initAutobans,
    initAutobanBrands,
    initAutobanModels,
    initAutobanTypes,
    initAutobanYears,
    initAutobanPriceTask,
    initAutobanPriceTasks,

    handleAutobans,
    handleAutobanBrands,
    handleAutobanModels,
    handleAutobanTypes,
    handleAutobanYears,
    handleAutobanPrices,
    handleAutobanPriceTasks,

    distroyAutoban,
    distroyAutobanBrand,
    distroyAutobanModel,
    distroyAutobanType,
    distroyAutobanYear,
    distroyAutobanPriceTask,

    reOrder,
  }
})
