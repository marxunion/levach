import { ref, computed, Ref, ComputedRef } from 'vue';

import { getSavedTheme, setSavedTheme, getSystemTheme, onSystemThemeChange } from '../helpers/ThemeHelper';

export class ThemeHandler 
{
    static #instance: ThemeHandler;
    private currentTheme : Ref<string>;

    private constructor() 
    {
        this.currentTheme = ref(getSavedTheme() || getSystemTheme());
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
        document.body.setAttribute('data-theme', `${themeName}-theme`);
    }

    public changeTheme(themeName : string) 
    {
        if (this.currentTheme.value !== themeName) 
        {
            this.currentTheme.value = themeName;
            setSavedTheme(themeName);
            this.applyTheme(themeName);
        }
    }

    get getCurrentTheme(): ComputedRef<string>
    {
        return computed(() => this.currentTheme.value);
    }
}