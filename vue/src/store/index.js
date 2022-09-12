import { createPinia } from "pinia";
import translator from "../plugins/translator";

import { useAuth } from "./Auth";
import { usePermissions } from "./Permissions";

function t (){
  return { translator }
}

const pinia = createPinia();
pinia.use(t)

const auth = useAuth(pinia);
!auth.TOKEN || auth.initUSER(); // Fetch User's Data

const permissions = usePermissions(pinia);
!auth.TOKEN || permissions.initCurrentPermissions(); // Fetch Permissions Data


export default pinia
