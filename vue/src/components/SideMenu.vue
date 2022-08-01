<template>
  <div class="sticky">
    <aside class="app-sidebar">
      <div class="app-sidebar__logo py-0 d-flex align-items-center justify-content-center">
        <router-link :to="{name: 'Dashboard'}" class="header-brand">
          <span class="h1 header-brand-img desktop-lgo">
            <img :src="settings('logo')" width="90" alt="">
          </span>
          <span class="h1 header-brand-img dark-logo">
            <img :src="settings('logo')" width="90" alt="">
          </span>
          <span class="h4 header-brand-img mobile-logo">
            <img :src="settings('logo')" width="100" alt="">
          </span>
          <span class="h4 header-brand-img darkmobile-logo">
            <img :src="settings('logo')" width="100" alt="">
          </span>
        </router-link>
      </div>
      <div class="app-sidebar3">
        <div class="main-menu">
          <div class="app-sidebar__user">
            <div class="dropdown user-pro-body text-center">
              <div class="user-pic">
                <img :src="user.avatar" alt="user-img" class="avatar-xxl rounded-circle mb-1">
              </div>
              <div class="user-info">
                <h5 class=" mb-2">{{ user.name }}</h5>
                <span class="text-muted app-sidebar__user-name text-sm"> {{ Object.keys(user).length ? user.role.display_name : '' }} </span>
              </div>
            </div>
          </div>
          <div class="slide-left disabled" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"><path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"/></svg></div>

          <!-- MENU ITEMS -->
          <side-menu :menu-items="items" />
        </div>
      </div>
    </aside>
  </div>
</template>

<script setup>
import SideMenu from "./SideMenu/SideMenu.vue";
import { useMenuItems } from "../store/MenuItems";
import { useRouter } from "vue-router";
import {computed, inject, ref, watch} from "vue";
const settings = inject('Settings')
const props = defineProps({
  user: Object,
})
const route = useRouter();
const parentRoute = computed(()=>route.currentRoute.value.path.split('/')[1] === 'admin' ? 'admin' : 'cms');
const menuItemsStore = useMenuItems();
const items = computed(()=> menuItemsStore.currentItems );
const MENUID = computed(()=> route.currentRoute.value.meta.menu)
const menuItems = async()=>{
  await menuItemsStore.initITEMS(MENUID.value);
  const { fullPath } = route.currentRoute.value
  let parentUL = document.querySelector(`[href='${fullPath}']`).closest('ul.slide-menu');
  if ( parentUL ) {
    parentUL.classList.add("open")
    parentUL.style.display = 'block'
    parentUL.closest('li.slide').classList.add('is-expanded')
  }
}
menuItems()
watch(
  () => MENUID.value,
  async () => {
    await menuItems()
  }
)

</script>

<style scoped>

</style>
