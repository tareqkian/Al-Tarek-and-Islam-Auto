import { defineStore } from "pinia";
import {reactive, ref, watch} from "vue";
import {useToast} from "primevue/usetoast";
import api from "../axios";

export const useComparisonStore = defineStore('ComparisonStore',()=>{
  const toast = useToast();

  const autobans = ref([])
  const autobansOptions = reactive({
    loading: false,
    data: []
  })
  watch(
    autobans.value,
    async (val)=>{
      if ( val.length > 3 ) {
        autobans.value.length = 3
        toast.add({
          closable: false,
          severity: "danger",
          summary: "Comparison",
          detail: "Exceeded The Limitation",
          life: 3000
        })
      }
    }
  )

  const removeAutobanFromComparison = (autoban) => {
    const autobanIndex = autobans.value.findIndex(x=>+x.id === +autoban.id)
    autobans.value = [...autobans.value.slice(0,autobanIndex), ...autobans.value.slice(autobanIndex+1)]
    const autobanOptionIndex = autobansOptions.data.findIndex(x=>+x.id === +autoban.id)
    autobansOptions.data = [...autobansOptions.data.slice(0,autobanOptionIndex), ...autobansOptions.data.slice(autobanOptionIndex+1)]
  }

  const initComparison = async (payload) => {
    try {
      autobansOptions.loading = true;
      const { data } = await api.get(`/autobanComparison/${payload.map(x=>x.id)}`)
      autobansOptions.data = data.data
      autobansOptions.loading = false
    } catch (e) {
      throw e.response.data.errors
    }
  }

  return {
    autobans,
    autobansOptions,

    removeAutobanFromComparison,

    initComparison
  }
})
