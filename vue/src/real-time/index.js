import {MainEvent} from "./Main";
import {permissonsEvent} from "./Permissions";
/*import {usersEvent} from "./Users";*/

export const realTime = ()=> {
  MainEvent()
  permissonsEvent()
  /*usersEvent()*/
}
