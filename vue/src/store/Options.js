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
  const optionTree = reactive({
    loading: false,
    data: []
  })

  const optionCars = reactive({
    loading: false,
    data: [],
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
  const initOptionSubClassWithChildrens = async (optionClass)=>{
    try {
      optionSubClass.loading = true
      const { data } = await api.get('/optionClassWithChildrens/'+optionClass.id);
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
  const initOptionCategories = async ()=>{
    try {
      optionCategories.loading = true
      const { data } = await api.get(`/optionCategory`);
      optionCategories.data = data.data
      /*optionCategories.pagination = data.meta*/
      optionCategories.loading = false
    } catch (e) {
      throw e.response.data.errors
    }
  }
  const initOptions = async (meta={})=>{
    try {
      options.loading = true
      const { data } = await api.get(`/options`);
      options.data = data.data
      options.pagination = data.meta
      options.loading = false
    } catch (e) {
      throw e.response.data.errors
    }
  }
  const initOptionTree = async ()=>{
    try {
      optionTree.loading = true
      const { data } = await api.get('/optionTree');
      optionTree.data = data.data
      optionTree.loading = false
    } catch (e) {
      throw e.response.data.errors
    }
  }

  const initOptionClassCars = async (payload)=>{
    try {
      optionCars.loading = true
      const { data } = await api.get(`/optionClassCars/${payload.id}`)
      optionCars.data = data.data
      optionCars.loading = false
    } catch (e) {
      throw e.response.data.errors
    }
  }
  const initOptionSubClassCars = async (payload)=>{
    try {
      optionCars.loading = true
      const { data } = await api.get(`/optionSubClassCars/${payload.id}`)
      optionCars.data = data.data
      optionCars.loading = false
    } catch (e) {
      throw e.response.data.errors
    }
  }
  const initOptionCategoryCars = async (payload)=>{
    try {
      optionCars.loading = true
      const { data } = await api.get(`/optionCategoryCars/${payload.id}`)
      optionCars.data = data.data
      optionCars.loading = false
    } catch (e) {
      throw e.response.data.errors
    }
  }
  const initOptionCars = async (payload)=>{
    try {
      optionCars.loading = true
      const { data } = await api.get(`/optionCars/${payload.id}`)
      optionCars.data = data.data
      optionCars.loading = false
    } catch (e) {
      throw e.response.data.errors
    }
  }

  const handleOptionClasses = async (payload)=>{
    try {
      if ( !payload.id ) {
        const {data} = await api.post('/optionClass', payload);
        optionClasses.data = [
          ...optionClasses.data,
          {...data.data}
        ]
      }
      else {
        const {data} = await api.put('/optionClass/' + payload.id, payload);
        const classIndex = optionClasses.data.findIndex(x => x.id === data.data.id);
        optionClasses.data[classIndex] = {...data.data}
      }
    } catch (e) {
      throw e.response.data.errors
    }
  }
  const handleOptionSubClasses = async (payload)=>{
    try {
      if ( !payload.id ) {
        const {data} = await api.post('/optionSubClass', payload);
        optionSubClasses.data = [...optionSubClasses.data, {...data.data}]
        if ( optionSubClass.data.length && +optionSubClass.data[0].option_class_id === +data.data.option_class_id )
          optionSubClass.data = [...optionSubClass.data, {...data.data}]
      }
      else {
        const {data} = await api.put('/optionSubClass/' + payload.id, payload);
        if ( optionSubClasses.data.length ) {
          const optionIndex = options.data.findIndex(x => x.id === data.data.id);
          optionSubClasses.data = [
            ...optionSubClasses.data.slice(0,optionIndex),
            {...data.data},
            ...optionSubClasses.data.slice(optionIndex+1)
          ]
        }
        if ( optionSubClass.data.length ) {
          const optionIndex = optionSubClass.data.findIndex(x => x.id === data.data.id);
          optionSubClass.data[optionIndex] = {...data.data}
        }
      }
    } catch (e) {
      throw e.response.data.errors
    }
  }
  const handleOptionCategories = async (payload)=>{
    try {
      if ( !payload.id ) {
        const {data} = await api.post('/optionCategory', payload);
        optionCategories.data = [...optionCategories.data, {...data.data}]
        if ( optionCategory.data.length && +optionCategory.data[0].option_sub_class_id === +data.data.option_sub_class_id )
          optionCategory.data = [...optionCategory.data, {...data.data}]
      }
      else {
        const {data} = await api.put('/optionCategory/' + payload.id, payload);
        if ( optionCategories.data.length ) {
          const optionIndex = optionCategories.data.findIndex(x => x.id === data.data.id);
          optionCategories.data[optionIndex] = {...data.data}
        }
        if ( optionCategory.data.length ) {
          const optionIndex = optionCategory.data.findIndex(x => x.id === data.data.id);
          optionCategory.data[optionIndex] = {...data.data}
        }
      }
    } catch (e) {
      throw e.response.data.errors
    }
  }
  const handleOptions = async (payload)=>{
    try {
      if ( !payload.id ) {
        const {data} = await api.post('/options', payload);
        options.data = [...options.data, data.data]
        if ( option.data.length && +option.data[0].option_category_id === +data.data.option_category_id )
          option.data = [...option.data, data.data]
      }
      else {
        const {data} = await api.put('/options/' + payload.id, payload);
        if ( options.data.length ) {
          const optionIndex = options.data.findIndex(x => x.id === data.data.id);
          options.data[optionIndex] = {...data.data}
        }
        if ( option.data.length ) {
          const optionIndex = option.data.findIndex(x => x.id === data.data.id);
          option.data[optionIndex] = {...data.data}
        }
      }
    } catch (e) {
      throw e.response.data.errors
    }
  }

  const distroyOptionClasses = async (payload)=>{
    try {
      await api.delete('/optionClass/'+payload.id);
      optionClasses.data = optionClasses.data.filter(x=> +x.id !== +payload.id)
    } catch (e) {
      throw e.response.data.errors
    }
  }
  const distroyOptionSubClasses = async (payload)=>{
    try {
      await api.delete('/optionSubClass/'+payload.id);
      optionSubClasses.data = optionSubClasses.data.filter(x=> +x.id !== +payload.id)
      optionSubClass.data = optionSubClass.data.filter(x=> +x.id !== +payload.id)
    } catch (e) {
      throw e.response.data.errors
    }
  }
  const distroyOptionCategories = async (payload)=>{
    try {
      await api.delete('/optionCategory/'+payload.id);
      optionCategories.data = optionCategories.data.filter(x=> +x.id !== +payload.id)
      optionCategory.data = optionCategory.data.filter(x=> +x.id !== +payload.id)
    } catch (e) {
      throw e.response.data.errors
    }
  }
  const distroyOptions = async (payload)=>{
    try {
      await api.delete('/options/'+payload.id);

      options.data = options.data.filter(x=> +x.id !== +payload.id)
      option.data = option.data.filter(x=> +x.id !== +payload.id)
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
    optionTree,

    optionCars,

    initOptionSubClass,
    initOptionSubClassWithChildrens,
    initOptionCategory,
    initOption,

    initOptionClasses,
    initOptionSubClasses,
    initOptionCategories,
    initOptions,
    initOptionTree,

    initOptionClassCars,
    initOptionSubClassCars,
    initOptionCategoryCars,
    initOptionCars,

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
