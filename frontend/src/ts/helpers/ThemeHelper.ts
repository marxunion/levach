import mainConfig from "../../configs/main.json";

import { ThemeGrayscale } from "../types/ThemeGrayscale";


export function getSavedTheme() 
{
    return localStorage.getItem('theme');
}

export function convertThemeToGrayscale(themeName : string) : ThemeGrayscale
{
    if(themeName in mainConfig["themesGrayscale"]["light"])
    {
        return "light";
    }
    else if(themeName in mainConfig["themesGrayscale"]["dark"])
    {
        return "dark";
    }
    else
    {
        return "light";
    }
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