import { defineStore } from "pinia";
import {ref} from "vue";
import api from "../axios";

export const useLogStore = defineStore("LogStore",()=>{
  const logFiles = {
    loading: ref(false),
    data: ref([])
  }
  const log = {
    loading: ref(false),
    data: ref([])
  }

  const initFiles = async ()=>{
    try {
      logFiles.loading.value = true;
      const {data} = await api.get("/log")
      logFiles.data.value = data;
      logFiles.loading.value = false;
    } catch (e) {
      throw e.response.data.errors
    }
  }
  const initLog = async (payload,folder)=>{
    try {
      log.loading.value = true
      const { data } = await api.get("/log/"+[payload,folder])
      log.data.value = data
      log.loading.value = false
    } catch (e) {
      throw e.response.data.errors
    }
  }

  return {
    logFiles,
    initFiles,

    log,
    initLog
  }
})
