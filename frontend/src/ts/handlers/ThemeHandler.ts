import { getSavedTheme, setSavedTheme, getSystemTheme, onSystemThemeChange } from '../helpers/ThemeHelper';

export class ThemeHandler 
{
    static #instance: ThemeHandler;
    private themeName : string;

    private constructor() 
    {
        this.themeName = getSavedTheme() || getSystemTheme();
        this.applyTheme(this.themeName);

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
        if (this.themeName !== themeName) 
        {
            this.themeName = themeName;
            setSavedTheme(themeName);
            this.applyTheme(themeName);
        }
    }

    public getCurrentTheme()
    {
        return this.themeName;
    }
}