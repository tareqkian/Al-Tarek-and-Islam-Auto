import { defineStore } from "pinia";
import api from "../axios";
import {ref} from "vue";
import {ability} from "../services/abilities";
export const useAuth = defineStore('Auth',()=>{
  const TOKEN = ref(localStorage.getItem('TOKEN'))
  const USER = ref({})

  const setUser = async (data,token)=>{
    try {
      TOKEN.value = token || localStorage.getItem('TOKEN')
      USER.value = data
      localStorage.setItem("TOKEN",TOKEN.value)
    } catch (e) {
      throw e.response.data
    }
  }

  const initUSER = async ()=>{
    try {
      const {data, token} = await api.get('/user');
      await ability.update([{
        "action": [...data.role.permissions.map(x=>x.key)],
        "subject": "all"
      }]);
      await setUser(data,token)
    } catch (e) {
      throw e.response.data;
    }
  }
  const checkEmailExists = async (payload)=>{
    try {
      const {data} = await api.post("/checkEmail",payload)
      return data;
    } catch (e) {
      throw e.response.data.errors
    }
  }
  const login = async (payload)=>{
    try {
      const {data} = await api.post("/login",payload)
      await setUser(data.data,data.token)
      await ability.update([{
        "action": [...data.permissions],
        "subject": "all"
      }]);
    } catch (e) {
      throw e.response.data.errors
    }
  }
  const register = async (payload)=>{
    try {
      const {data} = await api.put("/register/"+payload.confirmedUser.id,payload)
      console.log(data)
      await setUser(data.data,data.token)
      await ability.update([{
        "action": [...data.permissions],
        "subject": "all"
      }]);
    } catch (e) {
      throw e.response.data.errors
    }
  }
  const logout = async ()=>{
    try {
      await api.post("/logout")
      USER.value = {};
      TOKEN.value = null;
      localStorage.removeItem("TOKEN");
      return "loggedOut!";
    } catch (e) {
      throw e.response.data.errors
    }
  }

  return {
    TOKEN,
    USER,
    initUSER,
    checkEmailExists,
    login,
    register,
    logout
  }
})
