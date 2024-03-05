<script setup lang="ts">
    import { computed } from 'vue';
    import { useRoute } from 'vue-router';
    import { LangDataHandler } from './../ts/LangDataHandler';

    import { JsonData } from '../ts/JsonHandler';

    import { openModal } from "jenesius-vue-modal";
    import AdminModal from "./../components/modals/AdminModal.vue";

    import langsData from './locales/SideBar.json';

    const langData = LangDataHandler.initLangDataHandler("SideBar", langsData).langData;

    const route = useRoute();

    const { isBurgerActive } = defineProps(['isBurgerActive']);
    const emit = defineEmits();

    const links = computed(() => 
    [
        { uri: '/articles/editorially', checkUris: ['/','/articles/editorially'], text: (langData.value['links'] as JsonData)['editoriallyArticles'] },
        { uri: '/articles/approvedEditorially', checkUris: ['/articles/approvedEditorially'], text: (langData.value['links'] as JsonData)['editoriallyApprovedArticles'], },
        { uri: '/articles/abyss', checkUris: ['/articles/abyss'], text: (langData.value['links'] as JsonData)['abyss'] }
    ]);

    const linksfooter = computed(() => 
    [
        { uri: '/faq', text: (langData.value['linksfooter'] as JsonData)['aboutProjectAndFAQ'] },
        { uri: '/rules', text: (langData.value['linksfooter'] as JsonData)['rules'], },
        { uri: '/sponsoring', text: (langData.value['linksfooter'] as JsonData)['sponsoring'] }
    ]);
    
    const isCurrentLink = (checkUris: string[]) => 
    {
        return checkUris.includes(route.path);
    };
</script>

<template>
    <aside class="sidebar" :class="{ 'active': isBurgerActive }">
        <div class="sidebar__links">
            <a class="sidebar__links__link"
                v-for="link in links"
                    :href="`#${link.uri}`"
                    @click="emit('toggleBurger')"
                    :class="{ active: isCurrentLink(link.checkUris) }">
                {{ link.text }}
            </a>
        </div>
        <div class="sidebar__linksfooter">
            <a
                class="sidebar__linksfooter__link"
                v-for="link in linksfooter"
                :href="`#${link.uri}`"
                @click="emit('toggleBurger')"
            >
            {{ link.text }}
            </a>
            <p class="sidebar__linksfooter__link" @click="openModal(AdminModal)">
                {{ (langData['linksfooter'] as JsonData)['admin'] }}
            </p>
        </div>
    </aside>
</template>

<style lang="scss" scoped src="./scss/SideBar.scss"></style>