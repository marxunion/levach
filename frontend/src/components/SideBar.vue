<script setup lang="ts">
    import { ref, computed } from 'vue';
    import { useRoute } from 'vue-router';
    import { LangDataHandler, KeyData } from './../ts/LangDataHandler';
    import langsData from './locales/SideBar.json';
    
    import './scss/SideBar.scss';

    const langData = ref(LangDataHandler.initLangDataHandler("SideBar", langsData).langData);

    const route = useRoute();

    console.log(route);
    
    const items = computed(() => [
        { link: '', text: (langData.value['links'] as KeyData)['editoriallyArticles'] },
        {
        link: 'articlesApproved',
        text: (langData.value['links'] as KeyData)['editoriallyApprovedArticles'],
        },
        { link: 'abyss', text: (langData.value['links'] as KeyData)['abyss'] },
    ]);
    
    const isCurrentLink = (link: string) => 
    {
        return route.path === `/${link}`;
    };
</script>

<template>
    <aside class="sidebar">
        <div class="sidebar__links">
            <a
                class="sidebar__links__link"
                v-for="item in items"
                :href="`#/${item.link}`"
                :class="{ active: isCurrentLink(item.link) }"
            >
            {{ item.text }}
            </a>
        </div>
    </aside>
</template>