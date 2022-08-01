/*
import { createI18n } from "vue-i18n/index";
// import { messages } from "vite-i18n-resources";
import store from "./store";
import router from "./router"
import { computed } from "vue";
const { dispatch, state } = store;
const roro = await computed(()=>router.currentRoute);
await dispatch('Trans/initTRANS',roro.value);
const messages = computed(()=>state.Trans.trans)
const i18n = createI18n({
    // legacy: false,
    locale: "en",
    fallbackLocale: "en",
    messages,
});
/!* Only if you want hot module replacement when translation message file change *!/
if (import.meta.hot) {
    import.meta.hot.on("locales-update", (data) => {
            Object.keys(data).forEach((lang) => {
            i18n.global.setLocaleMessage(lang, data[lang]);
        });
    });
}
export default i18n;*/

import {ref} from "vue";

const locale = ref(localStorage.getItem("locale"))
window.addEventListener('locale-changed', (event) => {
  locale.value = event.detail.storage;
});

export default {
  install: (app, options)=>{
    app.config.globalProperties.t = (base,name) => {
      if ( base.translations && base.translations.length
        && base.translations.some(x=>x[name]&&x["locale"]===locale.value) )
      {
        return base.translations.filter(x=>x.locale===locale.value)[0][name] || ''
      }
      return base[name];
    }
  }
}
