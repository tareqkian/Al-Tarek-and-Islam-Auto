import {computed} from "vue";
import router from "./router";
import {useSettingsStore} from "./store/Settings";

const settingsStore = useSettingsStore();
(async () => {
  await settingsStore.initSettings();
})()
export const appSettings = (value)=>{
  const parentRoute = computed(()=>router.currentRoute.value.path.split('/')[1] === 'admin' ? 'admin' : 'cms');
  const settings = computed(()=>settingsStore.settings);
  const thetSett = settings.value.filter(x=>x.key===`${parentRoute.value}.${value}`)[0].value;
  return thetSett || '';
}
