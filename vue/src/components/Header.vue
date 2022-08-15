<template>
  <div>
    <div class="app-header header sticky">
      <div class="container-fluid main-container">
        <div class="d-flex">
          <router-link :to="{ path: '/dashboard' }" class="header-brand">
            <span class="h1 header-brand-img desktop-lgo"> Image Logo </span>
            <span class="h1 header-brand-img dark-logo"> Image Logo </span>
            <span class="h4 header-brand-img mobile-logo"> Logo </span>
            <span class="h4 header-brand-img darkmobile-logo"> Logo </span>
          </router-link>
          <div class="app-sidebar__toggle" data-bs-toggle="sidebar">
            <a class="open-toggle" href="javascript:void(0);">
              <i class="feather feather-menu"></i>
            </a>
            <a class="close-toggle" href="javascript:void(0);">
              <i class="feather feather-x"></i>
            </a>
          </div>
          <div class="d-flex order-lg-2 my-auto ms-auto">
            <button class="navbar-toggler nav-link icon navresponsive-toggler vertical-icon ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent-4" aria-controls="navbarSupportedContent-4" aria-expanded="false" aria-label="Toggle navigation">
              <i class="fe fe-more-vertical header-icons navbar-toggler-icon"></i>
            </button>
            <div class="mb-0 navbar navbar-expand-lg navbar-nav-right responsive-navbar navbar-dark p-0">
              <div class="collapse navbar-collapse" id="navbarSupportedContent-4">
                <div class="d-flex justify-content-center">
                  <div class="d-flex align-items-center">
                    <div class="btn-group">
                      <a class="nav-link icon" data-bs-toggle="dropdown">
                        <img :src="`/src/assets/assets/images/flags/${currentLocale || 'en'}.svg`" alt="img" class="h-24">
                      </a>
                      <ul class="dropdown-menu" style="width: 57px !important;min-width: 10px !important;">
                        <li v-for="(lang,index) in languages"
                            :key="index"
                            @click.prevent="currentLocale = index;changeLocale()"
                            class="py-2 px-1 text-center">
                          <img :src="`/src/assets/assets/images/flags/${index}.svg`" alt="img" class="h-24">
                        </li>
                      </ul>
                    </div>
                  </div>
                  <div class="d-flex" v-if="$can('browse_admin_dashboard')">
                    <router-link :to="{path: '/admin'}"
                                 active-class="active"
                                 class="nav-link icon nav-link-bg">
                      <i class="ti-crown text-yellow"></i>
                    </router-link>
                  </div>
                  <div class="d-flex">
                    <a class="nav-link icon theme-layout nav-link-bg layout-setting">
                      <span class="dark-layout"><i class="fe fe-moon"></i></span>
                      <span class="light-layout"><i class="fe fe-sun"></i></span>
                    </a>
                  </div>
                  <div class="dropdown header-notify">
                    <a class="nav-link icon"
                       data-bs-toggle="sidebar-right"
                       data-bs-target=".sidebar-right">
                      <i class="feather feather-bell header-icon"></i>
                      <span class="badge badge-success side-badge"> 2 </span>
                    </a>
                  </div>
                  <div class="dropdown profile-dropdown">
                    <a href="javascript:void(0);" class="nav-link pe-1 ps-0 leading-none" data-bs-toggle="dropdown">
                        <span>
                          <img :src="user.avatar" alt="img" class="avatar avatar-md bradius">
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow animated">
                      <div class="p-3 text-center border-bottom">
                        <a href="javascript: void(0);"
                           class="text-center user pb-0 font-weight-bold">
                          {{ user.name }}
                        </a>
                        <p class="text-center user-semi-title mb-0">
                          {{ t(user.role,"display_name") }}
                        </p>
                      </div>
                      <router-link :to="{name: 'Profile'}" class="dropdown-item d-flex" >
                        <i class="feather feather-user me-3 fs-16 my-auto"></i>
                        <div class="mt-1">Profile</div>
                      </router-link>
                      <a class="dropdown-item d-flex"
                         @click="logout"
                         href="javascript: void(0);">
                        <i class="feather feather-power me-3 fs-16 my-auto"></i>
                        <div class="mt-1">Sign Out</div>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!--sidebar-right-->
    <div class="sidebar sidebar-right sidebar-animate">
      <div class="card-header border-bottom pb-5">
        <h4 class="card-title">Notifications </h4>
        <div class="card-options">
          <a  href="javascript:void(0);" class="btn btn-sm btn-icon btn-light  text-primary"  data-bs-toggle="sidebar-right" data-bs-target=".sidebar-right"><i class="feather feather-x"></i> </a>
        </div>
      </div>
      <div class="">
        <div class="list-group-item align-items-center">
          <div class="d-flex">
            <span class="avatar avatar-lg brround me-3"></span>
            <div class="mt-1">
              <a href="javascript:void(0);" class="font-weight-semibold fs-16">
                Liam
                <span class="text-muted font-weight-normal">Sent Message</span>
              </a>
              <span class="clearfix"></span>
              <span class="text-muted fs-12 ms-auto">
                <i class="mdi mdi-clock me-1"></i>30 mins ago
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--/sidebar-right-->

  </div>
</template>

<script setup>
import Sidebar from 'primevue/sidebar';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';

import { useRouter } from "vue-router";
import { useDir } from "../store/Dir";
import { useAuth } from "../store/Auth";
import {computed, ref} from "vue";
import {useTranslationStore} from "../store/Translation";

const visibleLeft = ref(false);
const props = defineProps({
  user: Object
})
const { push, go } = useRouter();
const logout = async ()=>{
  try {
    const auth = useAuth()
    await auth.logout()
    await push({name: "Login"})
  } catch (e) {
    console.log(e)
  }
}

const translationStore = useTranslationStore();
const languages = computed(()=>translationStore.languages)
translationStore.initLanguages()

const currentLocale = ref(localStorage.getItem("locale"))
const changeLocale = ()=>{
  localStorage.setItem("locale",currentLocale.value)
  window.dispatchEvent(new CustomEvent('locale-changed', {
    detail: { storage: localStorage.getItem("locale") }
  }));
  const DIRTXT = languages.value[currentLocale.value].dir
  const Dir = useDir()
  Dir.changeDIR(DIRTXT)
}
</script>

<style lang="scss" scoped>
.active{
  color: #e8d492 !important;
  background: var(--primary-bg-color) !important;
}
</style>
