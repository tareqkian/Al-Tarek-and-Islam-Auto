import { useAuth } from "../store/Auth";
import { ability } from "../services/abilities";
import { createRouter, createWebHistory } from "vue-router";
import routes from "./routes";
import Profile from "/src/Views/Profiles/Profile.vue"
import {appSettings} from "../appSettings";
const router = createRouter({
  history: createWebHistory(),
  routes
});
router.addRoute('/',{
  path: '/profile',
  name: 'Profile',
  meta: {
    reqAuth: true,
    isProfile: true,
    pageTitle: "Profile",
    realTime: "UsersEvent",
    translations: [
      {locale: "en", title: "Profile"},
      {locale: "ar", title: "ملفي الشخصي"}
    ]
  },
  component: Profile
})
router.beforeEach(async (to, from, next) => {
  document.title = to.meta.pageTitle;
  let link = document.querySelector("link[rel~='icon']");
  link.href = appSettings('mini.logo');

  const auth = useAuth()
  if (
    !ability.can(`browse_${to.meta.permissionsLayout}`)
    && !to.meta.isError
    && !to.meta.isGuest
    && !to.meta.isProfile
  ) next({name: '401'})
  else if ( to.meta.reqAuth && !auth.TOKEN ) next({name: 'Login'})
  else if ( auth.TOKEN && to.meta.isGuest ) next({name: 'Dashboard'})
  else next()

  /* Real Time Control */
  if ( from.meta.realTime ) Echo.leave(from.meta.realTime)
})

export default router;
