import { useAuth } from "./store/Auth";
import axios from "axios";
const api = axios.create({
  baseURL: `${import.meta.env.VITE_API_BASE_URL}/api`,
})
api.interceptors.request.use(config=>{
  const authStore = useAuth();
  config.headers.locale = localStorage.getItem("locale");
  config.headers.Authorization = `Bearer ${authStore.TOKEN}`;
  return config
})
api.defaults.withCredentials = true
api.defaults.headers.common = {
  'X-Requested-With': 'XMLHttpRequest',
  'Content-Type':'application/json',
  'Accept':'application/json'
};
export default api;
