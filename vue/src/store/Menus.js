import { defineStore } from "pinia";
import api from "../axios";
import {ref} from "vue";
export const useMenus = defineStore("Menus",()=>{
  const menuRoutes = ref([]);
  const menus = {
    loading: ref(false),
    data: ref([])
  }

  const initROUTES = async ()=>{
    try {
      const { data } = await api.get("/routes")
      let menus = data.data;
      menus = menus.filter(x=>(x.importedComponent || '').length)
      let routes = [];
      for (let index = 0; index < menus.length; index++) {
        const x = menus[index];
        let z = {}
        z.path = x.name
        z.name = x.name
        z.redirect = `${x.name === '/' ? '' : x.name}/dashboard`
        z.meta = {
          reqAuth: true,
          menu: x.id,
        }
        z.component = async () => await import(/* @vite-ignore */x.importedComponent);
        x.items = x.items.filter(y=>(y.importedComponent || '').length)
        z.children = x.items.map(y=>{
          let c = {}
          c.path = y.route
          c.name = y.title
          c.component = () => import(/* @vite-ignore */y.importedComponent)
          c.meta = {
            realTime: `${y.importedComponent.split('/').pop().replace(/.vue/g,'')}Event`,
            pageTitle: c.name,
            component: y.importedComponent,
            permissionsLayout: `${y.importedComponent.split('/')[y.importedComponent.split('/').length-2].toLowerCase()}_${y.importedComponent.split('/').pop().replace(/.vue/g,'').toLowerCase()}`
          }
          return c;
        })
        routes = [...routes,{...z}]
      }
      menuRoutes.value = routes
    } catch (e) {
      throw e.response.data
    }
  }
  const initMENUs = async ()=>{
    try {
      menus.loading.value = true
      const { data } = await api.get("/menu")
      menus.data.value = data.data.filter(x=>x.importedComponent)
      menus.loading.value = false
    } catch (e) {
      throw e.response.data.errors
    }
  }

  // Broadcasting
  const updateMenu = async (payload)=>{
    try {
      await api.put('/menu/'+payload.id,payload)
    } catch (e) {
      throw e.response.data.errors
    }
  }
  const addMenu = async (payload)=>{
    try {
      await api.post('/menu',payload);
    } catch (error) {
      throw error.response.data
    }
  }
  const deleteMENU = async (menu)=>{
    try {
      await api.delete('/menu/'+menu.id)
    } catch (error) {
      throw error.response.data
    }
  }

  return {
    menuRoutes,
    initROUTES,
    menus,
    initMENUs,
    updateMenu,
    addMenu,
    deleteMENU,
  }
})
