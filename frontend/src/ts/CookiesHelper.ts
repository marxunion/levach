export function checkCookie(cookieName : string) : boolean
{
    return document.cookie.split(';').some(cookie => 
    {
        return cookie.trim().startsWith(cookieName + '=');
    });
}
