import { ref, Ref, computed } from 'vue';
import mainConfig from '../../configs/main.json';

import { JsonData } from '../interfaces/JsonData';

export class LangDataHandler 
{
    private langsData : JsonData;

    public static readonly langs = mainConfig['langs'];
    
    public static readonly currentLanguage : Ref<string> = ref(localStorage.getItem('language') || 'RU');
    
    private constructor(data: JsonData)
    {
        this.langsData = data;
    }

    public get langData()
    {
        return computed(() => this.langsData[LangDataHandler.currentLanguage.value] as JsonData);
    }

    public clearLangData()
    {
        this.langsData = {} as JsonData;
    }

    public static initLangDataHandler(componentName: string, data: JsonData)
    {
        if(!componentLangDataHandlers[componentName])
        {
            componentLangDataHandlers[componentName] = new LangDataHandler(data);
        }
        
        return componentLangDataHandlers[componentName];
    }

    public static destroyLangDataHandler(componentName: string)
    {
        if(componentLangDataHandlers[componentName]) 
        {
            componentLangDataHandlers[componentName].clearLangData();
            delete componentLangDataHandlers[componentName];
        }
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