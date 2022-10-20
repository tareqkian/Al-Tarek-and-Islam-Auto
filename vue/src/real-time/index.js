import {MainEvent} from "./Main";
import {permissonsEvent} from "./Permissions";
import {menuBuilderEvent} from "./MenuBuilder";
import {rolesEvent} from "./Roles";
/*import {usersEvent} from "./Users";*/

export const realTime = ()=> {
  MainEvent()
  permissonsEvent()
  /*usersEvent()*/
  menuBuilderEvent()
  rolesEvent()
}
