export function getSavedTheme() 
{
    return localStorage.getItem('theme');
}

export function setSavedTheme(themeName : string) 
{
    localStorage.setItem('theme', themeName);
}
  
export function getSystemTheme() 
{
    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
}
  
export function onSystemThemeChange(callback: (theme: string) => void) 
{
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => 
    {
        const systemTheme = e.matches ? 'dark' : 'light';
        callback(systemTheme);
    });
}  