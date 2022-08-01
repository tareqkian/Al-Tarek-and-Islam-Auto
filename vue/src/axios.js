import { useAuth } from "./store/Auth";
import axios from "axios";

const api = axios.create({
  baseURL: 'http://localhost:8000/api',
  /*transformResponse: [function (data) {
    // Do whatever you want to transform the data
    data = JSON.parse(data);
    if ( data.data && data.data.length ) {
      console.log(data.data)
      console.log(localStorage.getItem("locale"))
      /!*data['opps'] = {
        "opps" : 'oppsie'
      };*!/
    }
    return data;
  }],*/

})
api.interceptors.request.use(config=>{
  const authStore = useAuth();
  config.headers.locale = localStorage.getItem("locale");
  config.headers.Authorization = `Bearer ${authStore.TOKEN}`;
  return config
})
api.defaults.withCredentials = true

export default api;
