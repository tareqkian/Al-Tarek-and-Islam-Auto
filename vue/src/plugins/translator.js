import {ref} from "vue";
import { convertNumbers2Arabic, convertNumbers2English } from "to-arabic-numbers";
import numeral from "numeral";

const locale = ref(localStorage.getItem("locale") || 'en')
window.addEventListener('locale-changed', (event) => {
  locale.value = event.detail.storage;
});

export default {
  install: (app, options = null )=>{
    let t = app.config.globalProperties.t = (base,name = null,defaultLocal = null,price = false,currency = true,priceFormat = '0,0') => {
      let result = false;
      if ( typeof base === 'object' && Object.keys(base).length ) {
        result = base[name];
      } else if ( name === 'self' ) {
        result = base
      }
      let trans = base.translations || [];
      if ( trans.length && trans.some(x=>x[name]&&x["locale"]===(defaultLocal || (locale.value || 'en'))) ) {
        result = (base.translations.filter(x=>x.locale===(defaultLocal || (locale.value || 'en')))[0][name] || '')
      }
      if ( price ) {
        result = numeral(result).format(priceFormat)+(currency?(locale.value==='en'?' EGP':' ج.م'):'')
        // result = numeral(result).format(priceFormat)+(currency?`<small class="text-muted">${(locale.value==='en'?' EGP':' ج.م')}</small>`:'')
      }
      if ( (locale.value === 'ar' && /\d/.test(result)) || (defaultLocal && defaultLocal == 'ar') ) {
        if ( typeof result === 'number' ) result = result.toString()
        result = convertNumbers2Arabic(result)
      }
      if ( (locale.value === 'en' && /\d/.test(result)) || (defaultLocal && defaultLocal == 'en') ) {
        if ( typeof result === 'number' ) result = result.toString()
        result = convertNumbers2English(result)
      }
/*
      if ( typeof base === 'string' ) {
        return "Base is String"
      }
*/
      return result;
    }
    app.provide('t', t)
    app.provide('currentLocale', locale)
  }
}
