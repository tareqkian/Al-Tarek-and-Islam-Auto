import { defineStore } from "pinia";
import {ref, watch} from "vue";
import {useToast} from "primevue/usetoast";
import api from "../axios";

export const useComparisonStore = defineStore('ComparisonStore',()=>{
  const toast = useToast();

  const autobans = ref([])
  const autobansOptions = ref([])
  watch(
    ()=>autobans.value,
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
    const autobanIndex = autobans.value.indexOf(autoban)
    autobans.value = [
      ...autobans.value.slice(0,autobanIndex),
      ...autobans.value.slice(autobanIndex+1)
    ]
  }

  const initComparison = async () => {
    try {
      autobansOptions.value = []
      const { data } = await api.get(`/autobanComparison/${autobans.value.map(x=>x.id)}`)
      autobansOptions.value = data.data
    } catch (e) {
      console.log(e)
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
