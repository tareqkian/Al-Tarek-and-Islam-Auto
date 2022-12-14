import {defineStore} from "pinia";
import {reactive, ref} from "vue";
import api from "../axios";

export const usePricelistStore = defineStore("Pricelist",()=>{
  const pricelistBrands = {
    loading: ref(false),
    data: ref([])
  }
  const pricelistModels = {
    loading: ref(false),
    data: ref([])
  }
  const pricelistDetails = reactive({
    loading: false,
    data: []
  })
  const pricelistAutobans = {
    loading: ref(false),
    data: ref([])
  }

  const initPricelistBrands = async () => {
    try {
      pricelistBrands.loading.value = true
      const {data} = await api.get('/pricelist')
      pricelistBrands.data.value = data.data
      pricelistBrands.loading.value = false
    } catch (e) {
      throw e.response.data.errors
    }
  }
  const initPricelistModels = async (payload) => {
    try {
      pricelistModels.loading.value = true
      const {data} = await api.get('/pricelist/'+payload.id)
      pricelistModels.data.value = data.data
      pricelistModels.loading.value = false
    } catch (e) {
      throw e.response.data.errors
    }
  }
  const initPricelistDetails = async (payload) => {
    try {
      pricelistDetails.loading = true
      const {data} = await api.get('/pricelistDetails/'+payload.id)
      pricelistDetails.data = data.data
      pricelistDetails.loading = false
    } catch (e) {
      throw e.response.data.errors
    }
  }
  const initPricelistAutobans = async (payload) => {
    try {
      pricelistAutobans.loading.value = true
      const {data} = await api.get('/autoban/'+payload.id)
      pricelistAutobans.data.value = data.data
      pricelistAutobans.loading.value = false
    } catch (e) {
      throw e.response.data.errors
    }
  }

  return {
    pricelistBrands,
    pricelistModels,
    pricelistDetails,
    pricelistAutobans,

    initPricelistBrands,
    initPricelistModels,
    initPricelistDetails,
    initPricelistAutobans,
  }
})
