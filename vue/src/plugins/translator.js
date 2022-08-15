import {ref} from "vue";

const locale = ref(localStorage.getItem("locale"))
window.addEventListener('locale-changed', (event) => {
  locale.value = event.detail.storage;
});

export default {
  install: (app, options)=>{
    let t = app.config.globalProperties.t = (base,name = null) => {
      if ( typeof base === 'object' ) {
        let result = base[name];
        let trans = base.translations || [];
        if ( trans.length && trans.some(x=>x[name]&&x["locale"]===locale.value) )
          result = (base.translations.filter(x=>x.locale===locale.value)[0][name] || '')
        return result;
      }
      if ( typeof base === 'string' ) {
        return "Base is String"
      }
      return false
    }
    app.provide('t', t)
  }
}
