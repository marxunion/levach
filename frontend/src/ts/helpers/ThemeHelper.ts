function getSavedTheme() 
{
    return localStorage.getItem('theme');
}
  
function getSystemTheme() 
{
    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
}
  
  