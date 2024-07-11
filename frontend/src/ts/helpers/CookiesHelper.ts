export function checkCookie(cookieName : string) : boolean
{
    return document.cookie.split(';').some(cookie => 
    {
        return cookie.trim().startsWith(cookieName + '=');
    });
}

export function getCookie(name: string): string | null 
{
    const cookies = document.cookie.split('; ');
    for (const cookie of cookies) 
    {
        const [cookieName, cookieValue] = cookie.split('=');
        if (cookieName === name) 
        {
            return decodeURIComponent(cookieValue);
        }
    }
    return null;
}

export function getIntCookie(name: string): number | null 
{
    const cookieValue = getCookie(name);
    if (cookieValue !== null)
    {
        const intValue = parseInt(cookieValue, 10);
        if (!isNaN(intValue)) 
        {
            return intValue;
        }
    }
    return null;
}