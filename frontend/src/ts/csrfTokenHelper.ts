import { ref } from "vue";

import axios from "axios";

export const csrfToken = ref(''); 

export function getCsrfToken()
{
    axios.get('/api/csrfToken')
    .then(response => 
    {
        if(response.data.token)
        {
            csrfToken.value = response.data.token;
        }
    });
}