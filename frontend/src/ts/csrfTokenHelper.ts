import { Ref, ref } from "vue";

import axios from "axios";

export const csrfTokenInput : Ref<HTMLInputElement | null> = ref(null); 

export async function getNewCsrfToken()
{
    await axios.get('/api/csrfToken')
    .then(async (response) => 
    {
        if(response.data.token)
        {
            if(csrfTokenInput.value != null)
            {
                csrfTokenInput.value.value = response.data.token;
            }
        }
    });
}