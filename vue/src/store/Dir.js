import { defineStore } from "pinia";
import {ref} from "vue";

export const useDir = defineStore("Dir",()=>{
  const currentDIR = ref(localStorage.getItem("DIR"))
  const changeDIR = async (DIR)=>{
    localStorage.setItem("DIR",DIR)
    document.querySelector('body').classList.remove('rtl');
    document.querySelector('body').classList.remove('ltr');
    document.querySelector('body').classList.add(DIR);
    document.querySelector('html[lang=en]').setAttribute('dir',DIR);
    (document.getElementById("style")?.setAttribute("href", "../assets/plugins/bootstrap/css/bootstrap"+(DIR === 'rtl' ? '.rtl' : '')+".css"));
    currentDIR.value = localStorage.getItem("DIR")
  }
  return {
    changeDIR,
    currentDIR
  }
})
