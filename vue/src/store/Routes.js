import { defineStore } from "pinia";
import {ref} from "vue";
export const useRoutesStore = defineStore('RoutesStore',()=>{
  const routes = ref([])
  const initROUTES = async ()=>{
    try {
      routes.value = [];
      const files = import.meta.glob("../Views/**");
      for (const key in files) {
        const element = files[key];
        const el = await element()
        let fileName = key.substring(key.lastIndexOf('/') + 1);
        fileName = fileName.charAt(0).toLowerCase() + fileName.slice(1);
        routes.value = [
          ...routes.value,
          {
            route: fileName.replace(/.vue/g,''),
            parent: el.default.__file.split('/')[el.default.__file.split('/').length - 2],
            componentImported: el.default.__file.split('/vue')[1]
          }
        ]
      }
    } catch (e) {
      throw e.reponse.data.errors
    }
  }


  const layouts = ref([]);
  const initLAYOUTS = async ()=>{
    try {
      layouts.value = [];
      let files = import.meta.glob("../components/Layouts/*");
      for (const key in files) {
        const element = files[key];
        const el = await element()
        let fileName = key.substring(key.lastIndexOf('/') + 1);
        fileName = fileName.charAt(0).toLowerCase() + fileName.slice(1);
        layouts.value = [
          ...layouts.value,
          {
            layout: fileName.replace(/.vue/g,''),
            componentImported: el.default.__file.split('/vue')[1]
          }
        ]
      }
    } catch (e) {
      throw e.response.data.errors
    }
  }
  return {
    routes,
    initROUTES,

    layouts,
    initLAYOUTS,
  }

})
