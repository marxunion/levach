<script setup lang="ts">
    import { ref } from 'vue';
    import "./scss/SideBar.scss";
    import {initLangDataHandler, getLangDataHandler, LangDataHandler, KeyData} from "./../ts/LangDataHandler";
    import langsData from "./locales/SideBar.json";
    
    initLangDataHandler("SideBar", langsData);
    
    const langDataHandler : LangDataHandler = getLangDataHandler("Header");
    
    const items = ref([{ link: '', message: (langDataHandler.langData.value['links'] as KeyData)['editoriallyArticles']}, { link: 'articlesApproved', message: 'Статьи одобренные редакцией' }, {link:'', message: 'Песочница'}])
</script>

<template>
    <aside class="sidebar">
        <div class="sidebar__links">
            <a v-for="item in items" :href="`#/${item.link}`">{{ item.message }}</a>
        </div>
    </aside>
</template>../ts/langDataHandler