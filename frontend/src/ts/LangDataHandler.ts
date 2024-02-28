import { ref, computed } from 'vue';
import mainconfig from '../configs/main.json';

export interface KeyData 
{
  [key: string]: string | KeyData;
}

export class LangDataHandler 
{
    private langsData: KeyData = {};

    public static readonly langs = mainconfig['langs'];
    public static currentLanguage = ref(localStorage.getItem('language') || 'RU');
    
    private constructor(data: KeyData)
    {
        this.langsData = data;
    }

    public get langData()
    {
        return computed(() => this.langsData[LangDataHandler.currentLanguage.value] as KeyData);
    }

    public static initLangDataHandler(componentName: string, data: KeyData)
    {
        componentLangDataHandlers[componentName] = new LangDataHandler(data);
        return componentLangDataHandlers[componentName];
    }

    public static getLangDataHandler(componentName: string)
    {
        return componentLangDataHandlers[componentName];
    }

    public static changeLanguage(newLanguage: string) 
    {
        LangDataHandler.currentLanguage.value = newLanguage;
        localStorage.setItem('language', newLanguage);
    }
}

const componentLangDataHandlers: Record<string, LangDataHandler> = {};