import { ref } from "vue";

import { checkCookie } from "./CookiesHelper";

export const adminStatus = ref(false);

export function adminStatusReCheck() 
{
    if(checkCookie('admin_token') && checkCookie('admin_nickname') && checkCookie('admin_expiration_time'))
    {
        adminStatus.value = true;
    }
    else
    {
        adminStatus.value = false;
    }
};