import { defineStore } from "pinia";
import {reactive, ref} from "vue";
import api from "../axios";
export const useOptionStore = defineStore('Option',()=>{

  const optionSubClass = reactive({
    loading: false,
    data: []
  })
  const optionCategory = reactive({
    loading: false,
    data: [],
    pagination: {}
  })
  const option = reactive({
    loading: false,
    data: [],
    pagination: {}
  })

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

  const initOptionSubClass = async (optionClass)=>{
    try {
      optionSubClass.loading = true
      const { data } = await api.get('/optionClass/'+optionClass.id);
      optionSubClass.data = data.data.sub_classes
      optionSubClass.loading = false
    } catch (e) {
      throw e.response.data.errors
    }
  }
  const initOptionCategory = async (optionSubClass)=>{
    try {
      optionCategory.loading = true
      const { data } = await api.get('/optionSubClass/'+optionSubClass.id);
      optionCategory.data = data.data.option_categories
      optionCategory.loading = false
    } catch (e) {
      throw e.response.data.errors
    }
  }
  const initOption = async (optionCategory)=>{
    try {
      option.loading = true
      const { data } = await api.get(`/optionCategory/${optionCategory.id}`);
      option.data = data.data.options
      option.pagination = data.meta
      option.loading = false
    } catch (e) {
      throw e.response.data.errors
    }
  }

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
    optionSubClass,
    optionCategory,
    option,

    optionClasses,
    optionSubClasses,
    optionCategories,
    options,

    initOptionSubClass,
    initOptionCategory,
    initOption,

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
