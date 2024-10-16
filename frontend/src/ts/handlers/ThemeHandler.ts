import { ref, computed, Ref, ComputedRef } from 'vue';

import { getSavedTheme, setSavedTheme, getSystemTheme, convertThemeToGrayscale, onSystemThemeChange } from '../helpers/ThemeHelper';

import { ThemeGrayscale } from "../types/ThemeGrayscale";


export class ThemeHandler 
{
    static #instance: ThemeHandler;
    private currentTheme : Ref<string>;
    private currentThemeGrayscale : Ref<ThemeGrayscale>;

    private constructor() 
    {
        this.currentTheme = ref(getSavedTheme() || getSystemTheme());
        this.currentThemeGrayscale = ref(convertThemeToGrayscale(this.currentTheme.value));

        this.applyTheme(this.currentTheme.value);

        onSystemThemeChange((newTheme) => 
        {
            if(!getSavedTheme()) 
            {
                this.changeTheme(newTheme);
            }
        });
    }

    public static get instance(): ThemeHandler 
    {
        if(!ThemeHandler.#instance) 
        {
            ThemeHandler.#instance = new ThemeHandler();
        }

        return ThemeHandler.#instance;
    }

    public applyTheme(themeName : string) 
    {
        document.documentElement.setAttribute('data-theme', `${themeName}`);
    }

    public changeTheme(themeName : string) 
    {
        if (this.currentTheme.value !== themeName) 
        {
            this.currentTheme.value = themeName;
            this.currentThemeGrayscale.value = convertThemeToGrayscale(this.currentTheme.value);
            
            setSavedTheme(themeName);
            this.applyTheme(themeName);
        }
    }

    get getCurrentTheme(): ComputedRef<string>
    {
        return computed(() => this.currentTheme.value);
    }

    get getCurrentThemeGrayscale(): ComputedRef<ThemeGrayscale>
    {
        return computed(() => this.currentThemeGrayscale.value);
    }
}