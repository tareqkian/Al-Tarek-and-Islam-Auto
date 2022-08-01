<template>
  <PageLayoutVue :pageTitle="this.$route.meta.pageTitle">
    <button class="btn btn-primary mb-2" @click="resetMenu(true)">
      <i class="fe fe-plus"></i>
      Add New Menu
    </button>
    <div class="row">
      <div class="col-xl-3">
        <div class="card">
          <div class="nav flex-column admisetting-tabs" role="tablist" aria-orientation="vertical">
              <div v-if="menus.loading" class="spinner4 my-3">
                <div class="bounce1"></div>
                <div class="bounce2"></div>
                <div class="bounce3"></div>
              </div>
              <a v-else
                 class="nav-link d-flex justify-content-between"
                 data-bs-toggle="pill"
                 v-for="menu in menus.data"
                 :key="menu.id"
                 @click="menusBuilder(menu)"
                 role="tab">
                <div>
                  {{ menu.name }}
                  <small class="text-muted ms-3">{{ menu.importedComponent.split('/').at(-1).replace(/.vue/g,"") }}</small>
                </div>
                <div>
                  <i class="fa fa-edit text-info mx-1" @click="resetMenu();selectedMenu = menu;"></i>
                  <i class="fa fa-trash text-danger mx-1" @click="removeMenu($event,menu)"></i>
                </div>
              </a>
          </div>
        </div>
      </div>
      <div v-if="selectedMenu.name" class="col">
        <div class="card">
          <div class="card-body p-3 text-end">
            <div class="card-header border-bottom-0 py-1 px-3 justify-content-between">
              <h3 class="card-title">{{ selectedMenu.name }}</h3>
              <button class="btn btn-primary" @click="showEditModal(null)">
                <i class="fe fe-plus"></i>
                Add New Item
              </button>
            </div>
          </div>
        </div>
        <div class="card">
          <div class="card-body dd" id="#dd">
            <menu-builder :items="items"
                          :MenuID="selectedMenu.id"
                          :parentMenu="selectedMenu.name"
                          @permissionsGenerator="generatePermissions"
                          @edit_click="showEditModal"
                          @remove_click="removeMenuItem"
                          @order_change="reOrder" />
          </div>
        </div>
      </div>
      <div v-else class="col text-center mt-5 h3">
        Please Select Menu To Build
      </div>
    </div>
    <!-- menuAddModal -->
    <Dialog
      modal
      dismissableMask
      class="modal-content modal-lg"
      content-class="modal-body"
      :showHeader="false"
      v-model:visible="menuModalShow">
      <form @submit.prevent="handelMenu">
        <div class="form-floating my-2">
          <input class="form-control"
                 v-model="newMenu.name"
                 :class="[errors.name ? 'is-invalid' : '']"
                 name="menuName"
                 placeholder="Menu Name">
          <label> Menu Name </label>
          <div class="invalid-feedback">
            <ul>
              <li v-for="err in errors.name" :key="err"> {{err}} </li>
            </ul>
          </div>
        </div>
        <div class="form-floating my-2">
          <Dropdown
            v-model="newMenu.importedComponent"
            :options="leyouts"
            filter
            filter-placeholder="Search"
            option-value="componentImported"
            option-label="layout"
            :virtualScrollerOptions="( leyouts.length > 6 ? { itemSize: 38 } : false)"
            class="form-control d-flex align-items-stretch"
            placeholder="Select a layout" />
          <label> Layout </label>
          <div class="invalid-feedback">
            <ul>
              <li v-for="err in errors.importedComponent" :key="err"> {{err}} </li>
            </ul>
          </div>
        </div>
        <div class="modal-footer d-flex justify-content-center pb-0">
          <button type="submit" class="btn btn-primary" :class="[loading ? 'btn-loading' : '']">Save</button>
          <button type="button" class="btn btn-secondary" @click="menuModalShow = !menuModalShow">Close</button>
        </div>
      </form>
    </Dialog>
    <!-- menuItemAddModal -->
    <Dialog
      modal
      dismissableMask
      class="modal-content modal-lg"
      content-class="modal-body"
      :showHeader="false"
      v-model:visible="menuItemModalShow">
      <div class="card">
        <div class="card-body p-3 text-end">
          <div class="card-header border-bottom-0 py-1 px-3">
            <h3 class="card-title">{{ selectedMenu.name }} </h3>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-body">
          <form @submit.prevent="handelMenuItem">
            <div class="row">
              <div class="col-12">
                <div class="form-floating">
                  <input type="text"
                         v-model="newMenuItem.title"
                         class="form-control my-2"
                         :class="[errors.title ? 'is-invalid' : '']"
                         placeholder="Title">
                  <label>Title</label>
                  <div class="invalid-feedback">
                    <ul>
                      <li v-for="err in errors.title" :key="err"> {{err}} </li>
                    </ul>
                  </div>
                </div>
                <div class="form-floating">
                  <input type="text" v-model="newMenuItem.route"
                         class="form-control my-2"
                         :class="[errors.route ? 'is-invalid' : '']"
                         placeholder="Route">
                  <label>Route</label>
                  <div class="invalid-feedback">
                    <ul>
                      <li v-for="err in errors.route" :key="err"> {{err}} </li>
                    </ul>
                  </div>
                </div>
                <div class="form-floating">
                  <Dropdown v-model="newMenuItem.selectedComponent"
                            :options="routes"
                            filter
                            filter-placeholder="Search"
                            class="form-control d-flex align-items-stretch"
                            :virtualScrollerOptions="( routes.length > 6 ? { itemSize: 38 } : false)"
                            :optionLabel="op => `${op.route} ( ${op.parent} )`"
                            placeholder="Select a layout" />
                  <label>Route Module</label>
                  <div class="invalid-feedback">
                    <ul>
                      <li v-for="err in errors.selectedComponent" :key="err"> {{err}} </li>
                    </ul>
                  </div>
                </div>
                <div class="form-floating">
                  <input type="text" v-model="newMenuItem.icon_class"
                         class="form-control my-2"
                         :class="[errors.icon_class ? 'is-invalid' : '']"
                         placeholder="Icon">
                  <label>Icon</label>
                  <div class="invalid-feedback">
                    <ul>
                      <li v-for="err in errors.icon_class" :key="err"> {{err}} </li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="col">
                <button type="confirm" class="btn btn-success mx-2" :class="[loading ? 'btn-loading' : '']">
                  Confirm
                </button>
                <button class="btn btn-danger mx-2" type="button" @click="menuItemModalShow = !menuItemModalShow">
                  Back
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </Dialog>
    <ConfirmPopup>
      <template #message="data">
        <div class="popover-arrow" style="position: absolute; left: 0px; transform: translate(129px, 0px);"></div>
        <h3 class="popover-header">{{ data.message.message.header }}</h3>
        <div class="popover-body d-flex align-items-center">
          <i class="fs-5 me-3 text-danger" :class="[data.message.icon]"></i>
          {{ data.message.message.body }}
        </div>
      </template>
    </ConfirmPopup>
  </PageLayoutVue>
</template>

<script setup>
import PageLayoutVue from '/src/components/Layouts/PageLayout.vue';
import MenuBuilder from "/src/components/MenuBuilder/MenuBuilder.vue"
import Dropdown from "primevue/dropdown"
import Dialog from 'primevue/dialog';
import ConfirmPopup from 'primevue/confirmpopup';
import Button from "primevue/button";

import { useConfirm } from "primevue/useconfirm";
import { useToast } from "primevue/usetoast"

import { useMenuItems } from "/src/store/MenuItems"
import { useMenus } from "/src/store/Menus"
import { useRoutesStore } from "/src/store/Routes"
import { usePermissions } from "/src/store/Permissions";

import { computed, ref, watch } from 'vue'
import { useRoute, useRouter } from "vue-router"

const confirm = useConfirm()
const toast = useToast()

const menuItemsStore = useMenuItems()
const menusStore = useMenus()
const routesStore = useRoutesStore()
const permissionsStore = usePermissions();

const menuModalShow = ref(false);
const menuItemModalShow = ref(false);

const route = useRoute()
const router = useRouter()

const items = computed(()=>menuItemsStore.selectedItems)
const menus = computed(()=>menusStore.menus)
// menus.value.data.length || menusStore.initMENUs()
menusStore.initMENUs()

const routes = computed(()=>routesStore.routes)
// routes.value.length || routesStore.initROUTES()
routesStore.initROUTES()

const leyouts = computed(()=>routesStore.layouts)
// leyouts.value.length || routesStore.initLAYOUTS()
routesStore.initLAYOUTS()

const selectedMenu = ref({});
const menusBuilder = async (menu)=>{
  selectedMenu.value = menu
  await menuItemsStore.initSelectedItems(menu.id)
}
const loading = ref(false);
const errors = ref({})
let newMenu = ref({
  name: null,
  importedComponent: null,
})
const resetMenu = (neww = false)=>{
  menuModalShow.value = !menuModalShow.value
  if ( neww ) {
    newMenu.value = {
      name: null,
      importedComponent: null,
    }
  }
  errors.value = {};
}
watch(
  ()=>selectedMenu.value,
  (val)=>{
    if ( Object.keys(val).length ) {
      newMenu.value = {...val}
    }
  }
)
let newMenuItem = ref({
  menu_id: null,
  title: null,
  route: null,
  selectedComponent: null,
  icon_class: null,
})
const handelMenu = async ()=>{
  errors.value = {}
  loading.value = true
  try {
    if ( newMenu.value.id ) await menusStore.updateMenu(newMenu.value)
    else await menusStore.addMenu(newMenu.value)
    resetMenu()
  } catch (error) {
    errors.value = error.errors
  }
  loading.value = false
}
const removeMenu = (event,menu)=>{
  confirm.require({
    target: event.currentTarget,
    message: {
      header: 'Are you sure?',
      body: "You won't be able to revert this!"
    },
    icon: 'pi pi-exclamation-triangle',
    acceptLabel: "Yes, delete it!",
    acceptClass: 'btn btn-sm btn-danger mx-1',
    rejectLabel: "Cancel",
    rejectClass: 'btn btn-sm btn-secondary mx-1',
    accept: () => {
      //callback to execute when user confirms the action
      menusStore.deleteMENU(menu);
      toast.add({
        closable: false,
        severity: 'success',
        summary: 'Menu',
        detail: 'Deleted Successfully',
        life: 3000
      })
    },
    reject: () => { /* callback */ }
  });
}
const showEditModal = (item)=>{
  errors.value = {};
  newMenuItem.value = {}
  menuItemModalShow.value = !menuItemModalShow.value
  if (item) {
    let componentImported = (item.importedComponent || ''),
      parent = (componentImported.split('/')[componentImported.split('/').length - 2] || ''),
      route = (componentImported.split('/')[componentImported.split('/').length - 1].replace(/.vue/g,'') || '');
    route = route.charAt(0).toLowerCase() + route.slice(1);
    newMenuItem.value.id = item.id
    newMenuItem.value.title = item.title
    newMenuItem.value.route = item.route
    newMenuItem.value.icon_class = item.icon_class
    newMenuItem.value.selectedComponent = {route, parent, componentImported}
  }
}
const handelMenuItem = async ()=>{
  errors.value = {}
  loading.value = true
  let menu_name = selectedMenu.value.name
  let url = `${import.meta.env.VITE_API_BASE_URL}`;
  try {
    newMenuItem.value.menu_id = selectedMenu.value.id
    newMenuItem.value.menu_name = selectedMenu.value.name
    if ( newMenuItem.value.id ) {
      if ( !(newMenuItem.value.route || '').length ) newMenuItem.value.selectedComponent = null
      await menuItemsStore.editItems(newMenuItem.value);
    } else {
      await menuItemsStore.addItems(route.meta.menu,newMenuItem.value);
    }
    menuItemModalShow.value = !menuItemModalShow.value
  } catch (error) {
    console.log(error)
    errors.value = error.errors
  }
  loading.value = false
}
const reOrder = async (MenuID,builderArray)=>{
  // await dispatch("MenuItems/orderItems",[MenuID,builderArray])
  await menuItemsStore.orderItems(MenuID,builderArray)
  toast.add({
    closable: false,
    severity: 'success',
    summary: 'Menu Items',
    detail: 'Ordered Successuflly',
    life: 3000
  })
  if ( +router.currentRoute.value.meta.menu === +MenuID ){
    await menuItemsStore.initITEMS(MenuID)
  }
}
const removeMenuItem = (item)=>{
  confirm.require({
    target: event.currentTarget,
    message: {
      header: 'Are you sure?',
      body: "You won't be able to revert this!"
    },
    icon: 'pi pi-exclamation-triangle',
    acceptLabel: "Yes, delete it!",
    acceptClass: 'btn btn-sm btn-danger mx-1',
    rejectLabel: "Cancel",
    rejectClass: 'btn btn-sm btn-secondary mx-1',
    accept: async () => {
      //callback to execute when user confirms the action
      await menuItemsStore.deleteItems(item);
      toast.add({
        closable: false,
        severity: 'success',
        summary: 'Menu Item',
        detail: 'Deleted Successfully',
        life: 3000
      })
    },
    reject: () => { /* callback */ }
  });
}

const generatePermissions = async (item,type="all")=>{
  try {
    const pathArr = item.importedComponent.split('/');
    const parent = pathArr[pathArr.length-2];
    const child = pathArr.pop().replace(/.vue/g,'');
    await permissionsStore.generatePermissions(parent.toLowerCase()+"_"+child.toLowerCase())
    toast.add({
      closable: false,
      severity: 'success',
      summary: 'Permissions',
      detail: 'Generated Successfully',
      life: 3000
    })
  } catch (e) {
    toast.add({
      closable: false,
      severity: 'danger',
      summary: 'Permissions',
      detail: 'Already Been Generated :)',
      life: 3000
    })
  }
}



Echo.channel("MenuBuilderEvent")
  .listen(".MenuAdder",(data)=>{
    if ( menusStore.menus.data.length ) {
      menusStore.menus.data = [
        ...menusStore.menus.data,
        {...data.menu}
      ]
    }
  })
  .listen(".MenuDeleter",(data)=>{
    if ( menusStore.menus.data.length ) {
      const menuIndex = menusStore.menus.data.findIndex(x => x.id === data.menu.id);
      menusStore.menus.data = [
        ...menusStore.menus.data.slice(0,menuIndex),
        ...menusStore.menus.data.slice(menuIndex+1)
      ]
    }
  })
  .listen(".MenuEditor",(data)=>{
    if ( menusStore.menus.data.length ) {
      const menuIndex = menusStore.menus.data.findIndex(x => x.id === data.menu.id);
      menusStore.menus.data = [
        ...menusStore.menus.data.slice(0,menuIndex),
        {...data.menu},
        ...menusStore.menus.data.slice(menuIndex+1)
      ]
    }
  })
</script>
