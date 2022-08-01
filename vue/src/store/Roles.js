import { defineStore } from "pinia";
import {ref} from "vue";
import api from "../axios";


export const useRolesStore = defineStore("RolesStore",()=>{
  const roles = {
    data: ref([]),
    loading: ref(false)
  }
  const rolePermissions = {
    data: ref({}),
    loading: ref(false)
  }

  const initROLES = async ()=>{
    try {
      roles.loading.value = true
      const { data } = await api.get('/roles')
      console.log(data.data)
      roles.data.value = data.data
      roles.loading.value = false
    } catch (e) {
      console.log(e)
      throw e.response.data.errors
    }
  }
  const initROLE = async (role)=>{
    try {
      rolePermissions.loading.value = true
      const { data } = await api.get('/roles/'+role.id)
      data.data.permissions = data.data.permissions
        .reduce((a,v)=>{
          return {...a,[v.id]:true}
        }, {})
      rolePermissions.data.value = data.data
      rolePermissions.loading.value = false
    } catch (e) {
      console.log(e)
      throw e.response.data.errors;
    }
  }

  // Broadcasting
  const updatePERMISSIONS = async (payload) =>{
    try {
      await api.put("/roles/"+payload.id,payload)
    } catch (error) {
      throw error.response.data.errors
    }
  }
  const handleRole = async (payload)=>{
    try {
      if ( payload.id ) {
        await api.put("/updateRole/"+payload.id,payload);
      }
      else {
        await api.post("/roles",payload);
      }
    } catch (e) {
      throw e.response.data.errors
    }
  }
  const distroyRole = async (payload)=>{
    try {
      await api.delete('/roles/'+payload.id)
    } catch (e) {
      throw e.response.data.errors
    }
  }

  return {
    roles,
    initROLES,
    rolePermissions,
    initROLE,
    updatePERMISSIONS,
    handleRole,
    distroyRole
  }
})
