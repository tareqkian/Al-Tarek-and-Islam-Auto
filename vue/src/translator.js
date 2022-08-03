import {ref} from "vue";

const locale = ref(localStorage.getItem("locale"))
window.addEventListener('locale-changed', (event) => {
  locale.value = event.detail.storage;
});

export default {
  install: (app, options)=>{
    let t = app.config.globalProperties.t = (base,name = null) => {
      let result = base[name];
      if ( base.translations && base.translations.length
        && base.translations.some(x=>x[name]&&x["locale"]===locale.value) )
      {
        result = (base.translations.filter(x=>x.locale===locale.value)[0][name] || '')
      }
      return result;
    }
    app.provide('t', t)
  }
}
