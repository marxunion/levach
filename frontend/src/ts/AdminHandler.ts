import { ref } from "vue";
import axios from "axios";

import { getIntCookie, checkCookie } from "./CookiesHelper";
import { csrfTokenInput, getNewCsrfToken } from "./csrfTokenHelper";
import { useRouter } from "vue-router";

export const adminStatus = ref(false);

export async function adminStatusReCheck() 
{
   
    if(checkCookie('admin_token') && checkCookie('admin_nickname') && checkCookie('admin_expiration_time'))
    {
        const expirationTime : number | null = getIntCookie('admin_expiration_time');
        const currentTime : number = new Date().getTime() / 1000;

        if(expirationTime != null)
        {
            
            if(currentTime > expirationTime - 2)
            {
                await getNewCsrfToken();

                if(csrfTokenInput.value == null)
                {
                    return;
                }
            
                const data = 
                {
                    csrfToken: (csrfTokenInput.value as HTMLInputElement).value
                }
            
                await axios.post('/api/admin/quit', data)
                .then(async response => 
                {
                    if(response.data.success)
                    {
                        window.location.href = "/";
                    }
                })
            }
            else
            {
                adminStatus.value = true;
            }
        }
        else
        {
            adminStatus.value = false;
        }
    }
    else
    {
        adminStatus.value = false;
    }
}