import Login from "/src/Views/Auth/Login.vue";
import e401 from "/src/errorPages/401.vue"
import {useMenus} from "/src/store/Menus";
import {computed} from "vue";
let routes = [
  {path: '/', redirect: "/dashboard", meta: {isGuest: true}, component: Login},
  {path: '/login', name: 'Login',meta: {isGuest: true}, component: Login},
  {path: '/401', name: '401',meta: {isError: true}, component: e401}
];
const menus = useMenus();
await menus.initROUTES(); // Fetch Routes
const menu = computed(()=>menus.menuRoutes);
routes = [...routes,...menu.value]

export default routes
