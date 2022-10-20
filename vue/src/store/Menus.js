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
        /*z.component = async () => await import(x.importedComponent);*/
        const impComp = x.importedComponent.split('/').pop().replace(/.vue/g,'');
        z.component = async () => await import(`../../src/components/Layouts/${impComp}.vue`);
        x.items = x.items.filter(y=>(y.importedComponent || '').length)
        z.children = x.items.map(y=>{
          let c = {}
          c.path = y.route
          if ( y.translations.length ) {
            c.name = y.translations.filter(t=>t.locale==='en')[0].title
          } else {
            c.name = y.title
          }
          /*c.component = () => import(y.importedComponent)*/
          const impoCompP = y.importedComponent.split('/')[3].replace(/.vue/g,'');
          const impoComp = y.importedComponent.split('/').pop().replace(/.vue/g,'');
          c.component = () => import(`../../src/Views/${impoCompP}/${impoComp}.vue`)
          c.meta = {
            realTime: `${y.importedComponent.split('/').pop().replace(/.vue/g,'')}Event`,
            pageTitle: c.name,
            translations: y.translations,
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
      throw e.response.data
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
