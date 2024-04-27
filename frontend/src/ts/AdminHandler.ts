import { checkCookie } from "./CookiesHelper";

export const isAdmin = () => 
{
    if(checkCookie('admin_token') && checkCookie('admin_nickname') && checkCookie('admin_password'))
    {
        return true;
    }
    else
    {
        return false
    }
};