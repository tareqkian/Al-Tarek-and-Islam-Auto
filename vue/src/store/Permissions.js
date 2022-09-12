import { defineStore } from "pinia";
import {ref} from "vue";
import api from "../axios";
import {ability} from "../services/abilities";
export const usePermissions = defineStore("Permissions",()=>{
  const permissions = {
    loading: ref(false),
    types: ref({}),
    data: ref([])
  }
  const currentPermissions = ref([])

  const initCurrentPermissions = async ()=>{
    try {
      const {data} = await api.get('/currentPermissions')
      currentPermissions.value = data;
      ability.update([{
        "action": [...data],
        "subject": ""
      }]);
    } catch (e) {
      throw e.response.data.errors
    }
  }
  const initPERMISSIONS = async ()=>{
    try {
      permissions.loading.value = true
      const { data } = await api.get('/permissions')
      permissions.data.value = data.data.filter(x=>x.table_name)
      permissions.loading.value = false
    } catch (e) {
      throw e.response.data.errors
    }
  }


  const generatePermissions = async (name)=>{
    try {
      await api.post('/permissions',{name})
    } catch (e) {
      throw e.response.data.errors
    }
  }

  return {
    permissions,
    currentPermissions,
    initCurrentPermissions,
    initPERMISSIONS,
    generatePermissions
  }
})
