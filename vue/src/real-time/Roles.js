import {useRolesStore} from "../store/Roles";

export const rolesEvent = () => {
  const rolesStore = useRolesStore()
  Echo.channel("RolesEvent")
    .listen("RoleAdder",(data)=>{
      if ( rolesStore.roles.data.length ) {
        rolesStore.roles.data = [
          ...rolesStore.roles.data,
          data.role
        ]
      } else {
        rolesStore.roles.data = [
          data.role
        ]
      }
    })
    .listen("RoleEditor",(data)=>{
      if ( rolesStore.roles.data.length ) {
        const roleIndex = rolesStore.roles.data.findIndex(x => x.id === data.role.id);
        rolesStore.roles.data = [
          ...rolesStore.roles.data.slice(0,roleIndex),
          {...data.role},
          ...rolesStore.roles.data.slice(roleIndex+1)
        ]
      }
    })
    .listen("RoleDeletor",(data)=>{
      if ( rolesStore.roles.data.length ) {
        const roleIndex = rolesStore.roles.data.findIndex(x => x.id === data.role.id);
        rolesStore.roles.data = [
          ...rolesStore.roles.data.slice(0,roleIndex),
          ...rolesStore.roles.data.slice(roleIndex+1)
        ]
      }
    })
}
