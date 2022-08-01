<template>
  <div>
    <!--<div id="global-loader">
        <img src="/src/assets/assets/images/svgs/loader.svg" alt="loader">
    </div>--> <!---Global-loader-->
    <div class="page">
      <div class="page-main">
        <Header :user="user" />
        <SideMenu :user="user" />
        <router-view></router-view>
      </div>
      <Footer> {{ settings('footer') }} </Footer>
    </div>
    <a href="#top" id="back-to-top">
      <span class="feather feather-chevrons-up"></span>
    </a> <!-- Back to top -->
  </div>
</template>

<script setup>
import Header from "../Header.vue";
import SideMenu from "../SideMenu.vue";
import Footer from "../Footer.vue";
import all from "../../assets/assets/js/sticky.js"
import {computed, inject, onMounted} from "vue"
import { useAuth } from "../../store/Auth";
const auth = useAuth();
const user = computed(()=>auth.USER);
onMounted(()=> all() )
const settings = inject("Settings");
</script>
