import { ref, Ref, computed } from 'vue';
import mainconfig from '../../configs/main.json';

import { JsonData } from '../interfaces/JsonData';

export class LangDataHandler 
{
    private langsData : JsonData;

    public static readonly langs = mainconfig['langs'];
    
    public static readonly currentLanguage : Ref<string> = ref(localStorage.getItem('language') || 'RU');
    
    private constructor(data: JsonData)
    {
        this.langsData = data;
    }

    public get langData()
    {
        return computed(() => this.langsData[LangDataHandler.currentLanguage.value] as JsonData);
    }

    public static initLangDataHandler(componentName: string, data: JsonData)
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

const componentLangDataHandlers: Record<string, LangDataHandler> = {}