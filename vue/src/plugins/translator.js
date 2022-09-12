import {ref} from "vue";
import { convertNumbers2Arabic, convertNumbers2English } from "to-arabic-numbers";
import numeral from "numeral";

const locale = ref(localStorage.getItem("locale"))
window.addEventListener('locale-changed', (event) => {
  locale.value = event.detail.storage;
});

export default {
  install: (app, options = null )=>{
    let t = app.config.globalProperties.t = (base,name = null,defaultLocal = null,price = false,priceFormat = '0,0') => {
      if ( typeof base === 'object' && Object.keys(base).length ) {
        let result = base[name];
        let trans = base.translations || [];
        if ( trans.length && trans.some(x=>x[name]&&x["locale"]===(defaultLocal || locale.value)) )
        {
          result = (base.translations.filter(x=>x.locale===(defaultLocal || locale.value))[0][name] || '')
        }

        if ( price )
        {
          result = numeral(result).format(priceFormat)
        }

        if ( (locale.value === 'ar' && /\d/.test(result)) || (defaultLocal && defaultLocal == 'ar') )
        {
          if ( typeof result === 'number' ) result = result.toString()
          result = convertNumbers2Arabic(result)
        }
        if ( (locale.value === 'en' && /\d/.test(result)) || (defaultLocal && defaultLocal == 'en') )
        {
          if ( typeof result === 'number' ) result = result.toString()
          result = convertNumbers2English(result)
        }

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
