<script setup lang="ts">
    import { computed } from 'vue';
    import { useRoute } from 'vue-router';
    import { LangDataHandler, JsonData } from './../ts/LangDataHandler';
    import langsData from './locales/SideBar.json';
    
    import './scss/SideBar.scss';

    const langData = LangDataHandler.initLangDataHandler("SideBar", langsData).langData;

    const route = useRoute();

    console.log(route);
    
    const links = computed(() => [
        { uri: '/articles/editorially', checkUris: ['/','/articles/editorially'], text: (langData.value['links'] as JsonData)['editoriallyArticles'] },
        { uri: '/articles/approvedEditorially', checkUris: ['/articles/approvedEditorially'], text: (langData.value['links'] as JsonData)['editoriallyApprovedArticles'], },
        { uri: '/articles/abyss', checkUris: ['/articles/abyss'], text: (langData.value['links'] as JsonData)['abyss'] },
    ]);

    const linksfooter = computed(() => [
        { uri: '/faq', text: (langData.value['linksfooter'] as JsonData)['aboutProjectAndFAQ'] },
        { uri: '/rules', text: (langData.value['linksfooter'] as JsonData)['rules'], },
        { uri: '/sponsoring', text: (langData.value['linksfooter'] as JsonData)['sponsoring'] },
        { uri: '/admin', text: (langData.value['linksfooter'] as JsonData)['admin'] },
    ]);
    
    const isCurrentLink = (checkUris: string[]) => 
    {
        return checkUris.includes(route.path);
    };
</script>

<template>
    <aside class="sidebar">
        <div class="sidebar__links">
            <a
                class="sidebar__links__link"
                v-for="link in links"
                :href="`#${link.uri}`"
                :class="{ active: isCurrentLink(link.checkUris) }"
            >
            {{ link.text }}
            </a>
        </div>
        <div class="sidebar__linksfooter">
            <a
                class="sidebar__linksfooter__link"
                v-for="link in linksfooter"
                :href="`#${link.uri}`"
            >
            {{ link.text }}
            </a>
        </div>
    </aside>
</template>