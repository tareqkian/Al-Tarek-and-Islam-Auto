import {createApp} from 'vue'
import pinia from "./store";
import router from "./router"
import App from './App.vue'
// import i18n from './translator';
import PrimeVue from 'primevue/config';
import ToastService from 'primevue/toastservice';
import ConfirmationService from 'primevue/confirmationservice';
import "primevue/resources/primevue.min.css";
import 'primeicons/primeicons.css';
import BadgeDirective from 'primevue/badgedirective';

import { abilitiesPlugin } from "@casl/vue";
import { ability } from "./services/abilities"
import Echo from 'laravel-echo'
import Pusher from "pusher-js"
import api from "./axios";

import { appSettings } from "./appSettings";
import t from "./plugins/translator"

window.Pusher = Pusher;
window.Echo = new Echo({
  broadcaster: 'pusher',
  key: import.meta.env.VITE_APP_PUSHER_KEY,
  cluster: "mt1",
  wsHost: import.meta.env.VITE_APP_PUSHER_SERVER,
  wsPort: `6001`,
  enabledTransports: ['ws'],
  forceTLS: false,
  enableStats: true,
  authorizer: (channel, options) => {
    return {
      authorize: (socketId, callback) => {
        api.post('/broadcasting/auth', {
          socket_id: socketId,
          channel_name: channel.name
        }).then(response => {
          callback(false, response.data);
        }).catch(error => {
          callback(true, error);
        });
      }
    };
  },
});

export const app = createApp(App);
app.use(t)

app.use(abilitiesPlugin, ability, { useGlobalProperties: true });
app.use(pinia,'');
app.provide("Settings",appSettings)
app.use(router);
// app.use(i18n);
// app.provide("$i18n",i18n.global);
app.use(PrimeVue, {inputStyle: 'filled'});
app.use(ToastService);
app.use(ConfirmationService);
app.directive('badge', BadgeDirective);
app.mount('#app');
