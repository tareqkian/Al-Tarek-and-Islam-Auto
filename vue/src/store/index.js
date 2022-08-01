import { createPinia } from "pinia";

import { useAuth } from "./Auth";
import { usePermissions } from "./Permissions";

const pinia = createPinia();


const auth = useAuth(pinia);
!auth.TOKEN || auth.initUSER(); // Fetch User's Data

const permissions = usePermissions(pinia);
!auth.TOKEN || permissions.initCurrentPermissions(); // Fetch Permissions Data


export default pinia
