import { useRouter } from "vue-router";
import { defineStore } from "pinia";
import { ref } from "vue";
import api from "../axios";
export const useMenuItems = defineStore("MenuItems",()=>{
  let router = useRouter()
  const currentItems = {
    loading: ref(false),
    data: ref({})
  }
  const initITEMS = async(MENUID)=>{
    try {
      currentItems.loading.value = true
      const { data } = await api.get("/items/"+MENUID)
      currentItems.data.value = data.data
      currentItems.loading.value = false
    } catch (e) {
      throw e.response.data.errors
    }
  }

  const selectedItems = {
    loading: ref(false),
    data: ref({})
  }
  const initSelectedItems = async (MENUID)=>{
    try {
      selectedItems.loading.value = true
      const { data } = await api.get("/items/"+MENUID)
      selectedItems.data.value = data.data
      selectedItems.loading.value = false
    } catch (e) {
      throw e.response.data
    }
  }

  const editItems = async (payload)=>{
    try {
      const { data } = await api.put('/items/'+payload.id,payload)
      let item = data.data;
      let routes = router.getRoutes()
      let link = routes.some(el=>el.name === item.title)
      if ( !link && item.importedComponent ) {
        router.addRoute(payload.menu_name, {
          path: payload.menu_name+'/'+item.route,
          name: item.title,
          component: () => import(/* @vite-ignore */item.importedComponent),
          meta: {
            pageTitle: item.title,
            component: item.importedComponent,
            permissionsLayout: `${item.importedComponent.split('/')[item.importedComponent.split('/').length-2].toLowerCase()}_${item.importedComponent.split('/').pop().replace(/.vue/g,'').toLowerCase()}`
          }
        })
      } else {
        router.removeRoute(item.title)
        router.addRoute(payload.menu_name, {
          path: payload.menu_name+'/'+item.route,
          name: item.title,
          component: () => import(/* @vite-ignore */item.importedComponent),
          meta: {
            pageTitle: item.title,
            component: item.importedComponent,
            permissionsLayout: `${item.importedComponent.split('/')[item.importedComponent.split('/').length-2].toLowerCase()}_${item.importedComponent.split('/').pop().replace(/.vue/g,'').toLowerCase()}`
          }
        })
      }
      selectedItems.data.value = selectedItems.data.value.map(x=>{
        if ( item.id !== x.id ) return x;
        return item;
      });
      currentItems.data.value = currentItems.data.value.map(x=>{
        if ( item.id !== x.id ) return x;
        return item;
      })
    } catch (error) {
      console.log(error)
      throw error.response.data.errors
    }
  }

  const addItems = async (routeMenuID,payload)=>{
    try {
      const { data } = await api.post('/items',payload)
      let item = data.data;
      let MENUID = (payload.menu_id === routeMenuID || false);
      const routes = router.getRoutes()
      const link = routes.some(el=>el.name === item.title)
      if ( !link && item.importedComponent ) {
        router.addRoute(payload.menu_name, {
          path: payload.menu_name+'/'+item.route,
          name: item.title,
          component: () => import(/* @vite-ignore */item.importedComponent),
          meta: {
            pageTitle: item.title,
            component: item.importedComponent,
            permissionsLayout: `${item.importedComponent.split('/')[item.importedComponent.split('/').length-2].toLowerCase()}_${item.importedComponent.split('/').pop().replace(/.vue/g,'').toLowerCase()}`
          }
        })
      }
      selectedItems.data.value.push(item)
      if ( MENUID ) currentItems.data.value.push(item)
    } catch (error) {
      throw error.response.data.errors
    }
  }

  const orderItems = async (MENUID,payload)=>{
    try {
      await api.put('/orderItems/'+MENUID,payload)
    } catch (error) {
      throw error.response.data
    }
  }

  const deleteItems = async (payload)=>{
    try {
      await api.delete('/items/'+payload.id)
      selectedItems.data.value = selectedItems.data.value.filter(x=>{
        return payload.id !== x.id
      });
      currentItems.data.value = currentItems.data.value.filter(x=>{
        return payload.id !== x.id
      });
    } catch (error) {
      throw error.response.data.errors
    }
  }

  return {
    // currentItems
    currentItems,
    initITEMS,
    // SelectedItems
    selectedItems,
    initSelectedItems,

    editItems,
    addItems,

    orderItems,
    deleteItems,
  }
})
