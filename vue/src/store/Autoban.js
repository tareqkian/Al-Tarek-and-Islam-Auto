import {defineStore} from "pinia";
import {reactive, ref} from "vue";
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
  const autobansByBrand = reactive({
    loading: false,
    data: [],
  })
  const fullAutoban = reactive({
    loading: false,
    data: []
  })

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
      /*?page=${meta.page+1 || 0}&perPage=${meta.rows || 0}*/
      const {data} = await api.get(`/autobanModels`)
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
  const initAutobansByBrand = async (autoban) => {
    try {
      autobansByBrand.loading = true
      const {data} = await api.get(`/autobanByBrand/${autoban.id}`)
      autobansByBrand.data = data.data
      autobansByBrand.loading = false
    } catch (e) {
      throw e.response.data.errors
    }
  }
  const initAutoban = async (autoban) => {
    try {
      fullAutoban.loading = true
      const {data} = await api.get(`/showAutoban/${autoban.id}`)
      fullAutoban.data = data.data
      fullAutoban.loading = false
    } catch (e) {
      throw e.response.data.errors
    }
  }

  const handleAutobans = async (payload)=>{
    try {
      if ( !payload.id ) {
        await api.post('/autoban',payload)
        initAutobans()
      }
      else {
        const {data} = await api.put('/autoban/'+payload.id,payload)
        const autobanIndex = autobans.data.value.findIndex(x => x.id === data.data.id);
        autobans.data.value = [
          ...autobans.data.value.slice(0,autobanIndex),
          {...data.data},
          ...autobans.data.value.slice(autobanIndex+1)
        ]
      }
    } catch (e) {
      throw e.response.data.errors
    }
  }
  const handleAutobanBrands = async (payload)=>{
    try {
      if ( !payload.id ) {
        const {data} = await api.post('/autobanBrands',payload)
        autobanBrands.data.value = [...autobanBrands.data.value, data.data]
        return data.data;
      }
      else {
        const {data} = await api.put('/autobanBrands/'+payload.id,payload)
        const brandIndex = autobanBrands.data.value.findIndex(x => x.id === data.data.id);
        autobanBrands.data.value = [
          ...autobanBrands.data.value.slice(0,brandIndex),
          {...data.data},
          ...autobanBrands.data.value.slice(brandIndex+1)
        ]
        autobanModels.data.value = autobanModels.data.value.map(x=>{
          if ( x.brand.id === data.data.id ) {
            x.brand = {...data.data}
          }
          return x;
        })
      }
    } catch (e) {
      throw e.response.data.errors
    }
  }
  const handleAutobanModels = async (payload)=>{
    try {
      if ( !payload.id ) {
        const {data} = await api.post('/autobanModels', payload)
        autobanModels.data.value = [...autobanModels.data.value, ...data.data]
      }
      else {
        const {data} = await api.put('/autobanModels/' + payload.id, payload)
        const modelIndex = autobanModels.data.value.findIndex(x => x.id === data.data.id);
        autobanModels.data.value = [
          ...autobanModels.data.value.slice(0,modelIndex),
          {...data.data},
          ...autobanModels.data.value.slice(modelIndex+1)
        ]
      }
    } catch (e) {
      throw e.response.data.errors
    }
  }
  const handleAutobanTypes = async (payload)=>{
    try {
      if ( !payload.id ) {
        const {data} = await api.post('/autobanTypes', payload)
        /*autobanTypes.data.value = [...autobanTypes.data.value, data.data]*/
        initAutobanTypes()
      }
      else {
        const {data} = await api.put('/autobanTypes/' + payload.id, payload)
        const typeIndex = autobanTypes.data.value.findIndex(x => x.id === data.data.id);
        autobanTypes.data.value = [
          ...autobanTypes.data.value.slice(0,typeIndex),
          {...data.data},
          ...autobanTypes.data.value.slice(typeIndex+1)
        ]
        autobanModels.data.value = autobanModels.data.value.map(x=>{
          if ( x.type.id === data.data.id ) {
            x.type = {...data.data}
          }
          return x;
        })
      }
    } catch (e) {
      throw e.response.data.errors
    }
  }
  const handleAutobanYears = async (payload)=>{
    try {
      if ( !payload.id ) {
        const {data} = await api.post('/autobanYears', payload)
        /*autobanYears.data.value = [...autobanYears.data.value, data.data]*/
        initAutobanYears()
      }
      else {
        const {data} = await api.put('/autobanYears/' + payload.id, payload)
        const yearIndex = autobanYears.data.value.findIndex(x => x.id === data.data.id);
        autobanYears.data.value = [
          ...autobanYears.data.value.slice(0,yearIndex),
          {...data.data},
          ...autobanYears.data.value.slice(yearIndex+1)
        ]
        autobanModels.data.value = autobanModels.data.value.map(x=>{
          if ( x.year.id === data.data.id ) {
            x.year = {...data.data}
          }
          return x;
        })
      }
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
      if ( !payload.id ) {
        const {data} = await api.post('/autobanPriceTasks', payload)
        autobanPriceTasks.data.value = [...autobanPriceTasks.data.value, ...data.data]
      }
      else {
        const {data} = await api.put('/autobanPriceTasks/' + payload.id, payload)
        const taskIndex = autobanPriceTasks.data.value.findIndex(x => x.id === data.data.id);
        autobanPriceTasks.data.value = [
          ...autobanPriceTasks.data.value.slice(0,taskIndex),
          {...data.data},
          ...autobanPriceTasks.data.value.slice(taskIndex+1)
        ]
      }
    } catch (e) {
      throw e.response.data.errors
    }
  }

  const distroyAutoban = async (payload)=>{
    try {
      await api.delete('/autoban/'+payload.id)
      const autobanIndex = autobans.data.value.findIndex(x => x.id === payload.id);
      autobans.data.value = [
        ...autobans.data.value.slice(0,autobanIndex),
        ...autobans.data.value.slice(autobanIndex+1)
      ]
    } catch (e) {
      throw e.response.data.errors
    }
  }
  const distroyAutobanBrand = async (payload)=>{
    try {
      await api.delete('/autobanBrands/'+payload.id)
      const brandIndex = autobanBrands.data.value.findIndex(x => +x.id === +payload.id);
      autobanBrands.data.value = [
        ...autobanBrands.data.value.slice(0,brandIndex),
        ...autobanBrands.data.value.slice(brandIndex+1)
      ]
      autobanModels.data.value = autobanModels.data.value.filter(x=>+x.brand.id !== +payload.id)
    } catch (e) {
      throw e.response.data.errors
    }
  }
  const distroyAutobanModel = async (payload)=>{
    try {
      await api.delete('/autobanModels/'+payload.id)
      const modelIndex = autobanModels.data.value.findIndex(x => x.id === payload.id);
      autobanModels.data.value = [
        ...autobanModels.data.value.slice(0,modelIndex),
        ...autobanModels.data.value.slice(modelIndex+1)
      ]
    } catch (e) {
      throw e.response.data.errors
    }
  }
  const distroyAutobanType = async (payload)=>{
    try {
      await api.delete('/autobanTypes/'+payload.id)
      const typeIndex = autobanTypes.data.value.findIndex(x => x.id === payload.id);
      autobanTypes.data.value = [
        ...autobanTypes.data.value.slice(0,typeIndex),
        ...autobanTypes.data.value.slice(typeIndex+1)
      ]
      autobanModels.data.value = autobanModels.data.value.filter(x=>x.type.id!==payload.id)
    } catch (e) {
      throw e.response.data.errors
    }
  }
  const distroyAutobanYear = async (payload)=>{
    try {
      await api.delete('/autobanYears/'+payload.id)
      const yearIndex = autobanYears.data.value.findIndex(x => x.id === year.id);
      autobanYears.data.value = [
        ...autobanYears.data.value.slice(0,yearIndex),
        ...autobanYears.data.value.slice(yearIndex+1)
      ]
      autobanModels.data.value = autobanModels.data.value.filter(x=>x.year.id!==year.id)
    } catch (e) {
      throw e.response.data.errors
    }
  }
  const distroyAutobanPriceTask = async (payload)=>{
    try {
      await api.delete('/autobanPriceTasks/'+payload.id)
      const taskIndex = autobanPriceTasks.data.value.findIndex(x => x.id === payload.id);
      autobanPriceTasks.data.value = [
        ...autobanPriceTasks.data.value.slice(0,taskIndex),
        ...autobanPriceTasks.data.value.slice(taskIndex+1)
      ]
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
    autobansByBrand,
    fullAutoban,

    initAutobans,
    initAutobanBrands,
    initAutobanModels,
    initAutobanTypes,
    initAutobanYears,
    initAutobanPriceTask,
    initAutobanPriceTasks,
    initAutobansByBrand,
    initAutoban,

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
