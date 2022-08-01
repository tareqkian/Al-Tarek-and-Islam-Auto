import {useAuth} from "../store/Auth";
import {ability} from "../services/abilities";
import {useRouter} from "vue-router";
import {usePermissions} from "../store/Permissions";
export const permissonsEvent = ()=>{
  const router = useRouter();
  Echo.channel("permissionsEvent")
    .listen(".RolePermissionsChange",async (data) => {
      const authStore = useAuth();
      if ( data.role.name === authStore.USER.role.name ) {
        await ability.update([{
          "action": [...data.data],
          "subject": "all"
        }]);
      }
      ability.can(`browse_${router.currentRoute.value.meta.permissionsLayout}`) || router.push({name: '401'})
    })
    .listen("PermissionsGenerator",(data)=>{
      const permissionsStore = usePermissions();
      if ( permissionsStore.permissions.data.length ) {
        permissionsStore.permissions.data = [
          ...permissionsStore.permissions.data,
          ...data.generatedPermissions
        ]
      }
    })
}
