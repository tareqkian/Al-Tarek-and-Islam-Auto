import { defineStore } from "pinia";
import {ref} from "vue";
import api from "../axios";
export const useTranslationStore = defineStore("TranslationStore",()=>{
  const tables = {
    loading: ref(false),
    data: ref([])
  }

  const languages = ref({})

  const translation = {
    loading: ref(false),
    data: ref([])
  }

  const initTables = async () => {
    try {
      tables.loading.value = true
      const { data } = await api.get("/translation")
      tables.data.value = data.data
      tables.loading.value = false
    } catch (e) {
      throw e.response.data.error
    }
  }

  const initLanguages = async () => {
    try {
      const { data } = await api.get("/languages")
      console.log(data.data)
      languages.value = data.data
    } catch (e) {
      throw e.response.data.error
    }
  }

  const initTranslation = async (table) => {
    try {
      translation.loading.value = true
      const { data } = await api.get("/translation/"+table)
      console.log(data.data)
      translation.data.value = [{...data.data}]
      translation.loading.value = false
    } catch (e) {
      throw e.response.data.error
    }
  }

  return {
    tables,
    initTables,
    translation,
    initTranslation,
    languages,
    initLanguages
  }
})
