<template>
  <div>
    <div class="app-header header sticky">
      <div class="container-fluid main-container">
        <div class="d-flex">
          <router-link :to="{ name: 'Dashboard' }" class="header-brand">
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
          <!--<div class="mt-0">
            <form class="form-inline">
              <div class="search-element">
                <input type="search" class="form-control header-search" placeholder="Search…" aria-label="Search" tabindex="1">
                <button class="btn btn-primary-color" >
                  <i class="feather feather-search"></i>
                </button>
              </div>
            </form>
          </div>--> <!--SEARCH-->
          <div class="d-flex order-lg-2 my-auto ms-auto">
            <button class="navbar-toggler nav-link icon navresponsive-toggler vertical-icon ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent-4" aria-controls="navbarSupportedContent-4" aria-expanded="false" aria-label="Toggle navigation">
              <i class="fe fe-more-vertical header-icons navbar-toggler-icon"></i>
            </button>
            <div class="mb-0 navbar navbar-expand-lg navbar-nav-right responsive-navbar navbar-dark p-0">
              <div class="collapse navbar-collapse" id="navbarSupportedContent-4">
                <div class="d-flex justify-content-center">
                  <!--<a class="nav-link icon p-0 nav-link-lg d-lg-none navsearch"
                    href="javascript:void(0);" data-bs-toggle="search">
                    <i class="feather feather-search search-icon header-icon"></i>
                  </a>--> <!-- SEARCH MOBILE -->
                   <div class="d-flex align-items-center">
                    <select v-model="currentLocale" @change="changeLocale()" class="form-select">
                      <option value="en"> en </option>
                      <option value="ar"> ar </option>
                    </select>
                  </div>
                  <div class="d-flex" v-if="$can('browse_admin_dashboard')">
                    <router-link :to="{name: 'Admin Dashboard'}"
                                 active-class="active"
                                 class="nav-link icon nav-link-bg">
                      <i class="ti-crown text-yellow"></i>
                    </router-link>
                  </div>
                  <div class="d-flex">
                    <a @click="dirSwitch" class="nav-link icon nav-link-bg">
                      <i>
                        {{ ['rtl','ltr'].filter(x=>x!==DIR)[0] || 'rtl' }}
                      </i>
                    </a>
                  </div>
                  <div class="d-flex">
                    <a class="nav-link icon theme-layout nav-link-bg layout-setting">
                      <span class="dark-layout"><i class="fe fe-moon"></i></span>
                      <span class="light-layout"><i class="fe fe-sun"></i></span>
                    </a>
                  </div>
                  <!--<div class="dropdown header-fullscreen">
                    <a class="nav-link icon full-screen-link">
                      <i class="feather feather-maximize fullscreen-button fullscreen header-icons"></i>
                      <i class="feather feather-minimize fullscreen-button exit-fullscreen header-icons"></i>
                    </a>
                  </div>--> <!-- FULL SCREEN -->
                  <div class="dropdown header-message">
                    <a class="nav-link icon" data-bs-toggle="dropdown">
                      <i class="feather feather-mail header-icon"></i>
                      <span class="badge badge-success side-badge">5</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow  animated">
                      <div class="header-dropdown-list message-menu" id="message-menu">
                        <a class="dropdown-item border-bottom" href="chat.html">
                          <div class="d-flex align-items-center">
                            <div class="">
                              <span class="avatar avatar-md brround align-self-center cover-image" data-bs-image-src="/src/assets/assets/images/users/1.jpg"></span>
                            </div>
                            <div class="d-flex">
                              <div class="ps-3 text-wrap text-break">
                                <h6 class="mb-1">Jack Wright</h6>
                                <p class="fs-13 mb-1">All the best your template awesome</p>
                                <div class="small text-muted">
                                  3 hours ago
                                </div>
                              </div>
                            </div>
                          </div>
                        </a>
                        <a class="dropdown-item border-bottom" href="chat.html">
                          <div class="d-flex align-items-center">
                            <div class="">
                              <span class="avatar avatar-md brround align-self-center cover-image" data-bs-image-src="/src/assets/assets/images/users/2.jpg"></span>
                            </div>
                            <div class="d-flex">
                              <div class="ps-3 text-wrap text-break">
                                <h6 class="mb-1">Lisa Rutherford</h6>
                                <p class="fs-13 mb-1">Hey! there I'm available</p>
                                <div class="small text-muted">
                                  5 hour ago
                                </div>
                              </div>
                            </div>
                          </div>
                        </a>
                        <a class="dropdown-item border-bottom" href="chat.html">
                          <div class="d-flex align-items-center">
                            <div class="">
                              <span class="avatar avatar-md brround align-self-center cover-image" data-bs-image-src="/src/assets/assets/images/users/3.jpg"></span>
                            </div>
                            <div class="d-flex">
                              <div class="ps-3 text-wrap text-break">
                                <h6 class="mb-1">Blake Walker</h6>
                                <p class="fs-13 mb-1">Just created a new blog post</p>
                                <div class="small text-muted">
                                  45 mintues ago
                                </div>
                              </div>
                            </div>
                          </div>
                        </a>
                        <a class="dropdown-item border-bottom" href="chat.html">
                          <div class="d-flex align-items-center">
                            <div class="">
                              <span class="avatar avatar-md brround align-self-center cover-image" data-bs-image-src="/src/assets/assets/images/users/4.jpg"></span>
                            </div>
                            <div class="d-flex">
                              <div class="ps-3 text-wrap text-break">
                                <h6 class="mb-1">Fiona Morrison</h6>
                                <p class="fs-13 mb-1">Added new comment on your photo</p>
                                <div class="small text-muted">
                                  2 days ago
                                </div>
                              </div>
                            </div>
                          </div>
                        </a>
                        <a class="dropdown-item border-bottom" href="chat.html">
                          <div class="d-flex align-items-center">
                            <div class="">
                              <span class="avatar avatar-md brround align-self-center cover-image" data-bs-image-src="/src/assets/assets/images/users/6.jpg"></span>
                            </div>
                            <div class="d-flex">
                              <div class="ps-3 text-wrap text-break">
                                <h6 class="mb-1">Stewart Bond</h6>
                                <p class="fs-13 mb-1">Your payment invoice is generated</p>
                                <div class="small text-muted">
                                  3 days ago
                                </div>
                              </div>
                            </div>
                          </div>
                        </a>
                      </div>
                      <div class=" text-center p-2">
                        <a href="chat.html" class="">See All Messages</a>
                      </div>
                    </div>
                  </div>

                  <div class="dropdown header-notify">
                    <a class="nav-link icon" @click="visibleLeft = true">
                      <i class="feather feather-bell header-icon"></i>
                      <span class="badge badge-success side-badge">
                        2
                      </span>
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
                        <p class="text-center user-semi-title">
                          {{ Object.keys(user).length ? user.role.display_name : '' }}
                        </p>
                      </div>

                      <router-link :to="{name: 'Profile'}" class="dropdown-item d-flex" >
                        <i class="feather feather-user me-3 fs-16 my-auto"></i>
                        <div class="mt-1">Profile</div>
                      </router-link>
<!--
                      <a class="dropdown-item d-flex" >
                        <i class="feather feather-user me-3 fs-16 my-auto"></i>
                        <div class="mt-1">Profile</div>
                      </a>
-->

                      <!-- <a class="dropdown-item d-flex" href="editprofile.html">
                        <i class="feather feather-settings me-3 fs-16 my-auto"></i>
                        <div class="mt-1">Settings</div>
                      </a>
                      <a class="dropdown-item d-flex" href="chat.html">
                        <i class="feather feather-mail me-3 fs-16 my-auto"></i>
                        <div class="mt-1">Messages</div>
                      </a>
                      <a class="dropdown-item d-flex" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#changepasswordnmodal">
                        <i class="feather feather-edit-2 me-3 fs-16 my-auto"></i>
                        <div class="mt-1">Change Password</div>
                      </a>-->
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




    <Sidebar v-model:visible="visibleLeft"
             :baseZIndex="1000"
             position="right"
             class="sidebar sidebar-animate" style="overflow: visible !important;">
      <template #header>
        <div class="card-header border-bottom pb-5 w-100">
          <h4 class="card-title">Notifications </h4>
        </div>
      </template>

<!--      <div>-->
<!--        <div class="list-group-item align-items-center border-0">-->
<!--          <div class="d-flex">-->
<!--            <span class="avatar avatar-lg brround me-3" style="background-image: url(/src/assets/assets/images/users/4.jpg)"></span>-->
<!--            <div class="mt-1">-->
<!--              <a href="javascript:void(0);" class="font-weight-semibold fs-16">-->
<!--                Liam-->
<!--                <span class="text-muted font-weight-normal">-->
<!--                  Sent Message-->
<!--                </span>-->
<!--              </a>-->
<!--              <span class="clearfix"></span>-->
<!--              <span class="text-muted fs-13 ms-auto">-->
<!--                <i class="mdi mdi-clock text-muted me-1"></i>-->
<!--                30 mins ago-->
<!--              </span>-->
<!--            </div>-->
<!--          </div>-->
<!--        </div>-->
<!--      </div>-->


      <TabView :scrollable="true">
        <TabPanel v-for="tab in 15" :key="tab" :header="tab">
          <div class="list-group-item align-items-center border-0">
            <div class="d-flex">
              <span class="avatar avatar-lg brround me-3" style="background-image: url(/src/assets/assets/images/users/4.jpg)"></span>
              <div class="mt-1">
                <a href="javascript:void(0);" class="font-weight-semibold fs-16">
                  Liam
                  <span class="text-muted font-weight-normal">
                  Sent Message
                </span>
                </a>
                <span class="clearfix"></span>
                <span class="text-muted fs-13 ms-auto">
                  <i class="mdi mdi-clock text-muted me-1"></i>
                  30 mins ago
                </span>
              </div>
            </div>
          </div>
        </TabPanel>
      </TabView>

    </Sidebar>

  </div>
</template>

<script setup>
import Sidebar from 'primevue/sidebar';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import { useRouter } from "vue-router";
import { useDir } from "../store/Dir";
import { useAuth } from "../store/Auth";
import { ref } from "vue";

const visibleLeft = ref(false);
const props = defineProps({
  user: Object
})
let DIR = ref(localStorage.getItem("DIR"));
const dirSwitch = (e)=>{
  const DIRTXT = e.currentTarget.innerText
  const Dir = useDir()
  Dir.changeDIR(DIRTXT)
  DIR.value = Dir.currentDIR
}
const { push, go } = useRouter();
const logout = async ()=>{
  try {
    const auth = useAuth()
    await auth.logout()
    await push({name: "Login"})
  } catch (e) {
    console.log("opps",e)
  }
}


const currentLocale = ref(localStorage.getItem("locale"))
const changeLocale = ()=>{
  localStorage.setItem("locale",currentLocale.value)
  window.dispatchEvent(new CustomEvent('locale-changed', {
    detail: {
      storage: localStorage.getItem("locale")
    }
  }));

}

</script>

<style lang="scss" scoped>
.active{
  color: #e8d492 !important;
  background: var(--primary-bg-color) !important;
}
</style>
