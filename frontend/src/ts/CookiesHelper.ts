export function checkCookie(cookieName : string) 
{
    return document.cookie.split(';').some(cookie => 
    {
        return cookie.trim().startsWith(cookieName + '=');
    });
}
