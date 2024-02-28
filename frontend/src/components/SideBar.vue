<script setup lang="ts">
    import { ref, computed } from 'vue';
    import { useRoute } from 'vue-router';
    import { LangDataHandler, KeyData } from './../ts/LangDataHandler';
    import langsData from './locales/SideBar.json';
    
    import './scss/SideBar.scss';

    const langData = ref(LangDataHandler.initLangDataHandler("SideBar", langsData).langData);

    const route = useRoute();

    console.log(route);
    
    const links = computed(() => [
        { uri: '/articles/editorially', checkUris: ['/','/articles/editorially'], text: (langData.value['links'] as KeyData)['editoriallyArticles'] },
        { uri: '/articles/approvedEditorially', checkUris: ['/articles/approvedEditorially'], text: (langData.value['links'] as KeyData)['editoriallyApprovedArticles'], },
        { uri: '/articles/abyss', checkUris: ['/articles/abyss'], text: (langData.value['links'] as KeyData)['abyss'] },
    ]);

    const linksfooter = computed(() => [
        { uri: '/faq', text: (langData.value['linksfooter'] as KeyData)['aboutProjectAndFAQ'] },
        { uri: '/rules', text: (langData.value['linksfooter'] as KeyData)['rules'], },
        { uri: '/donate', text: (langData.value['linksfooter'] as KeyData)['donate'] },
        { uri: '/admin', text: (langData.value['linksfooter'] as KeyData)['admin'] },
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