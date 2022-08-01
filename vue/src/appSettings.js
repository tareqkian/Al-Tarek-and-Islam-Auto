import {computed} from "vue";
import router from "./router";
import {useSettingsStore} from "./store/Settings";

/*export default {
  async setup(){
    const settingsStore = useSettingsStore();
    await settingsStore.initSettings();
    const appSettings = (value)=>{
      const parentRoute = computed(()=>router.currentRoute.value.path.split('/')[1] === 'admin' ? 'admin' : 'cms');
      const settings = computed(()=>settingsStore.settings);
      const thetSett = settings.value.filter(x=>x.key===`${parentRoute.value}.${value}`)[0].value;
      return thetSett || '';
    }

    return {
      appSettings
    }
  }
}*/
const settingsStore = useSettingsStore();
await settingsStore.initSettings();
export const appSettings = (value)=>{
  const parentRoute = computed(()=>router.currentRoute.value.path.split('/')[1] === 'admin' ? 'admin' : 'cms');
  const settings = computed(()=>settingsStore.settings);
  const thetSett = settings.value.filter(x=>x.key===`${parentRoute.value}.${value}`)[0].value;
  return thetSett || '';
}
