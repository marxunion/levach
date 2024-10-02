class ThemeHandler 
{
    static #instance: ThemeHandler;

    private constructor() { }

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
        localStorage.setItem('theme', themeName);
        this.applyTheme(themeName);
    }
}