import { defineStore } from "pinia";
import {reactive, ref} from "vue";
import api from "../axios";
export const useOptionStore = defineStore('Option',()=>{
  const optionClasses = reactive({
    loading: false,
    data: []
  })
  const optionSubClasses = reactive({
    loading: false,
    data: []
  })
  const optionCategories = reactive({
    loading: false,
    data: [],
    pagination: {}
  })
  const options = reactive({
    loading: false,
    data: [],
    pagination: {}
  })

  const initOptionClasses = async ()=>{
    try {
      optionClasses.loading = true
      const { data } = await api.get('/optionClass');
      optionClasses.data = data.data
      optionClasses.loading = false
    } catch (e) {
      throw e.response.data.errors
    }
  }
  const initOptionSubClasses = async ()=>{
    try {
      optionSubClasses.loading = true
      const { data } = await api.get('/optionSubClass');
      optionSubClasses.data = data.data
      optionSubClasses.loading = false
    } catch (e) {
      throw e.response.data.errors
    }
  }
  const initOptionCategories = async (meta={})=>{
    try {
      optionCategories.loading = true
      const { data } = await api.get(`/optionCategory?page=${meta.page+1 || 0}&perPage=${meta.rows || 0}`);
      optionCategories.data = data.data
      optionCategories.pagination = data.meta
      optionCategories.loading = false
    } catch (e) {
      throw e.response.data.errors
    }
  }
  const initOptions = async (meta={})=>{
    try {
      options.loading = true
      const { data } = await api.get(`/options?page=${meta.page+1 || 0}&perPage=${meta.rows || 0}`);
      options.data = data.data
      options.pagination = data.meta
      options.loading = false
    } catch (e) {
      throw e.response.data.errors
    }
  }

  const handleOptionClasses = async (payload)=>{
    try {
      if ( !payload.id ) await api.post('/optionClass',payload);
      else await api.put('/optionClass/'+payload.id,payload);
    } catch (e) {
      throw e.response.data.errors
    }
  }
  const handleOptionSubClasses = async (payload)=>{
    try {
      if ( !payload.id ) await api.post('/optionSubClass',payload);
      else await api.put('/optionSubClass/'+payload.id,payload);

    } catch (e) {
      throw e.response.data.errors
    }
  }
  const handleOptionCategories = async (payload)=>{
    try {
      if ( !payload.id ) await api.post('/optionCategory',payload);
      else await api.put('/optionCategory/'+payload.id,payload);
    } catch (e) {
      throw e.response.data.errors
    }
  }
  const handleOptions = async (payload)=>{
    try {
      if ( !payload.id ) await api.post('/options',payload);
      else await api.put('/options/'+payload.id,payload);
    } catch (e) {
      throw e.response.data.errors
    }
  }

  const distroyOptionClasses = async (payload)=>{
    try {
      await api.delete('/optionClass/'+payload.id);
    } catch (e) {
      throw e.response.data.errors
    }
  }
  const distroyOptionSubClasses = async (payload)=>{
    try {
      await api.delete('/optionSubClass/'+payload.id);
    } catch (e) {
      throw e.response.data.errors
    }
  }
  const distroyOptionCategories = async (payload)=>{
    try {
      await api.delete('/optionCategory/'+payload.id);
    } catch (e) {
      throw e.response.data.errors
    }
  }
  const distroyOptions = async (payload)=>{
    try {
      await api.delete('/options/'+payload.id);
    } catch (e) {
      throw e.response.data.errors
    }
  }

  return {
    optionClasses,
    optionSubClasses,
    optionCategories,
    options,

    initOptionClasses,
    initOptionSubClasses,
    initOptionCategories,
    initOptions,

    handleOptionClasses,
    handleOptionSubClasses,
    handleOptionCategories,
    handleOptions,

    distroyOptionClasses,
    distroyOptionSubClasses,
    distroyOptionCategories,
    distroyOptions,
  }
})
