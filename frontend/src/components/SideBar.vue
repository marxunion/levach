<script setup lang="ts">
    import { ref, computed, watch } from 'vue';
    import { useRoute, onBeforeRouteUpdate } from 'vue-router';
    import "./scss/SideBar.scss";
    import { LangDataHandler, KeyData } from "./../ts/LangDataHandler";
    import langsData from "./locales/SideBar.json";
    
    LangDataHandler.initLangDataHandler("SideBar", langsData);
    
    watch('$route.path', (path) => 
    {
        console.log('Route: ' + path);
    });
    const items = computed(() => 
    [
        { link: '', text: (langData.value['links'] as KeyData)['editoriallyArticles'] }, 
        { link: 'articlesApproved', text: (langData.value['links'] as KeyData)['editoriallyApprovedArticles'] }, 
        { link: 'abyss', text: (langData.value['links'] as KeyData)['abyss'] }
    ]);
</script>

<template>
    <aside class="sidebar">
        <div class="sidebar__links">
            <a class="sidebar__links__link" v-for="item in items" :href="`#/${ item.link }`">{{ item.text }}</a>
        </div>
    </aside>
</template>