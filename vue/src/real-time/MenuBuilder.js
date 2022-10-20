import {useMenus} from "../store/Menus";

export const menuBuilderEvent = () => {
  const menusStore = useMenus()
  Echo.channel("MenuBuilderEvent")
    .listen(".MenuAdder",(data)=>{
      if ( menusStore.menus.data.length ) {
        menusStore.menus.data = [
          ...menusStore.menus.data,
          {...data.menu}
        ]
      }
    })
    .listen(".MenuDeleter",(data)=>{
      if ( menusStore.menus.data.length ) {
        const menuIndex = menusStore.menus.data.findIndex(x => x.id === data.menu.id);
        menusStore.menus.data = [
          ...menusStore.menus.data.slice(0,menuIndex),
          ...menusStore.menus.data.slice(menuIndex+1)
        ]
      }
    })
    .listen(".MenuEditor",(data)=>{
      if ( menusStore.menus.data.length ) {
        const menuIndex = menusStore.menus.data.findIndex(x => x.id === data.menu.id);
        menusStore.menus.data = [
          ...menusStore.menus.data.slice(0,menuIndex),
          {...data.menu},
          ...menusStore.menus.data.slice(menuIndex+1)
        ]
      }
    })
}
