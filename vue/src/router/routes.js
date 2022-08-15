import Login from "/src/Views/Auth/Login.vue";
import e401 from "/src/errorPages/401.vue"
import {useMenus} from "/src/store/Menus";
let routes = [
  {path: '/', redirect: "/dashboard", meta: {isGuest: true}, component: Login},
  {path: '/login', name: 'Login',meta: {isGuest: true}, component: Login},
  {path: '/401', name: '401',meta: {isError: true}, component: e401}
];
const menus = useMenus();
await menus.initROUTES(); // Fetch Routes
let menu = menus.menuRoutes;
routes = [...routes,...menu]

export default routes
