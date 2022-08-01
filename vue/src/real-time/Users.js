import {useAuth} from "../store/Auth";
import {useUsersStore} from "../store/Users";

export const usersEvent = ()=>{
  const usersStore = useUsersStore();
/*
  Echo.channel("UsersEvent")
    .listen("UsersAdder",(data)=>{
      if ( usersStore.users.data.length ) {
        usersStore.users.data = [
          ...usersStore.users.data,
          {...data.user}
        ]
      }
    })
    .listen("UsersEditor",(data)=>{
      const AuthStore = useAuth()
      if ( AuthStore.USER.id === data.user.id ) {
        console.log(AuthStore.USER)
        AuthStore.USER = data.user
        console.log(AuthStore.USER)
      }
      if ( usersStore.users.data.length ) {
        const userIndex = usersStore.users.data.findIndex(x=>x.id === data.user.id)
        usersStore.users.data = [
          ...usersStore.users.data.slice(0,userIndex),
          {...data.user},
          ...usersStore.users.data.slice(userIndex+1),
        ]
      }
    })
    .listen("UsersDeleter",(data)=>{
      const AuthStore = useAuth()
      if ( AuthStore.USER.id === data.user.id ) {
        AuthStore.USER.value = {};
        AuthStore.TOKEN.value = null;
        localStorage.removeItem("TOKEN");
      }
      if ( usersStore.users.data.length ) {
        const userIndex = usersStore.users.data.findIndex(x=>x.id === data.user.id)
        usersStore.users.data = [
          ...usersStore.users.data.slice(0,userIndex),
          ...usersStore.users.data.slice(userIndex+1),
        ]
      }
    })
*/
}
