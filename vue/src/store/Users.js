import { defineStore } from "pinia";
import {ref} from "vue";
import api from "../axios";
export const useUsersStore = defineStore("UsersStore",()=>{
  const users = {
    data: ref([]),
    loading: ref(false)
  }

  const initUsers = async ()=>{
    try {
      users.loading.value = true
      const { data } = await api.get('/users')
      users.data.value = data.data
      users.loading.value = false
    } catch (e) {
      throw e.response.data.errors
    }
  }

  const storeUser = async (payload)=>{
    try {
      await api.post('/users',payload);
    } catch (e) {
      throw e.response.data.errors
    }
  }

  const updateUser = async (payload)=>{
    try {
      await api.put('/users/'+payload.id,payload);
      // Echo.private(`MainEvents`).whisper('typing');
    } catch (e) {
      throw e.response.data.errors
    }
  }

  const changePassword = async (payload)=>{
    try {
      await api.put('/changePassword/'+payload.userId,payload);
    } catch (e) {
      throw e.response.data.errors
    }
  }

  const distroyUser = async (payload)=>{
    try {
      await api.delete('/users/'+payload.id);
    } catch (e) {
      throw e.response.data.errors
    }
  }

  return {
    users,
    initUsers,
    storeUser,
    updateUser,
    distroyUser,
    changePassword
  }
})
