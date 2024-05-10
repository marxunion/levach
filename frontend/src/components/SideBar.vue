<script setup lang="ts">
    import { nextTick, computed } from 'vue';
    import { useRoute } from 'vue-router';
    import axios from 'axios';

    import { JsonData } from '../ts/interfaces/JsonData';

    import { openModal } from "jenesius-vue-modal";
    import InfoModal from './modals/InfoModal.vue';
    import AdminModal from "./../components/modals/AdminModal.vue";

    import { adminStatus, adminStatusReCheck } from '../ts/handlers/AdminHandler';

    import { forceReload } from '../ts/helpers/ComponentsReloadHelper';

    import { LangDataHandler } from '../ts/handlers/LangDataHandler';
    import langsData from './locales/SideBar.json';

    import { searchText } from '../ts/handlers/SearchHandler';

    import { csrfTokenInput, getNewCsrfToken } from '../ts/handlers/CSRFTokenHandler';
    import { articles, articleReloading } from '../ts/handlers/ArticlesHandler';
    
    const langData = LangDataHandler.initLangDataHandler("SideBar", langsData).langData;

    const route = useRoute();

    const { isBurgerActive } = defineProps(['isBurgerActive']);
    const emit = defineEmits();

    const links = computed(() => 
        {
            if(adminStatus.value)
            {
                return [
                    { routeUri: '/articles/editorially', routeName: 'editoriallyArticles', text: (langData.value['links'] as JsonData)['editoriallyArticles'] },
                    { routeUri: '/articles/approvedEditorially', routeName: 'editoriallyApprovedArticles', text: (langData.value['links'] as JsonData)['editoriallyApprovedArticles'], },
                    { routeUri: '/articles/abyss', routeName: 'abyssArticles', text: (langData.value['links'] as JsonData)['abyssArticles'] },
                    { routeUri: '/admin/articles/waitingApproval', routeName: 'articlesWaitingApproval', text: (langData.value['links'] as JsonData)['articlesWaitingApproval'] },
                    { routeUri: '/admin/articles/waitingPremoderate', routeName: 'articlesWaitingPremoderate', text: (langData.value['links'] as JsonData)['articlesWaitingPremoderate'] },
                    { routeUri: '/admin/articles/edit', routeName: 'adminEditComments', text: (langData.value['links'] as JsonData)['adminEditComments'] }
                ]
            }
            else
            {
                return [
                    { routeUri: '/articles/editorially', routeName: 'editoriallyArticles', text: (langData.value['links'] as JsonData)['editoriallyArticles'] },
                    { routeUri: '/articles/approvedEditorially', routeName: 'editoriallyApprovedArticles', text: (langData.value['links'] as JsonData)['editoriallyApprovedArticles'], },
                    { routeUri: '/articles/abyss', routeName: 'abyssArticles', text: (langData.value['links'] as JsonData)['abyssArticles'] }
                ]
            }
        }
    );

    const linksfooter = computed(() => 
    [
        { routeUri: '/faq', text: (langData.value['linksfooter'] as JsonData)['aboutProjectAndFAQ'] },
        { routeUri: '/rules', text: (langData.value['linksfooter'] as JsonData)['rules'], },
        { routeUri: '/sponsoring', text: (langData.value['linksfooter'] as JsonData)['sponsoring'] }
    ]);

    const isCurrentRouteName = (routeName: string) => 
    {
        return routeName == route.name ? true : false;
    }

    const rejectAllApprove = async () => 
    {
        await getNewCsrfToken();

        if(csrfTokenInput.value == null)
        {
            openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['unknown']});
            return;
        }

        const data = 
        {
            csrfToken: (csrfTokenInput.value as HTMLInputElement).value
        }

        return axios.post('/api/admin/articles/rejectAllApprove', data)
        .then(async response => 
        {
            if(response.data.success)
            {
                const modal = await openModal(InfoModal, {status: true, text: langData.value['successfullyRejectAllApprove']});
                modal.onclose = function()
				{
                    articleReloading.value = true;
                }
            }
            else
            {
                openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['unknown']});
            }
        })
        .catch(error => 
        {
            openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['unknown']});
        });
    }

    const rejectAllPremoderate = async () =>
    {
        await getNewCsrfToken();

        if(csrfTokenInput.value == null)
        {
            openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['unknown']});
            return;
        }

        const data = 
        {
            csrfToken: (csrfTokenInput.value as HTMLInputElement).value
        }

        return axios.post('/api/admin/articles/rejectAllPremoderate', data)
        .then(async response => 
        {
            if(response.data.success)
            {
                const modal = await openModal(InfoModal, {status: true, text: langData.value['successfullyRejectAllPremoderate']});
                modal.onclose = function()
				{
                    articleReloading.value = true;
                }
            }
            else
            {
                openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['unknown']});
            }
        })
        .catch(error => 
        {
            openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['unknown']});
        });
    }
</script>

<template>
    <aside class="sidebar" :class="{ 'active': isBurgerActive }">
        <div class="sidebar__links">
            <a class="sidebar__links__link"
                v-for="link in links"
                    :href="`#${link.routeUri}`"
                    @click="searchText='';forceReload();emit('toggleBurger')"
                    :class="{ active: isCurrentRouteName(link.routeName) }">
                {{ link.text }}
            </a>
            
            <a v-if="adminStatus && (isCurrentRouteName('articlesWaitingApproval') && articles.length > 0)" @click="rejectAllApprove()" class="sidebar__links__button rejectAllApproveButton"> {{ langData['rejectAllApproveButton'] }} </a>
            <a v-else-if="adminStatus && (isCurrentRouteName('articlesWaitingPremoderate') && articles.length > 0)" @click="rejectAllPremoderate()" class="sidebar__links__button rejectAllPremoderateButton"> {{ langData['rejectAllPremoderateButton'] }} </a>
            <a v-else-if="adminStatus && isCurrentRouteName('ArticleAdminEditComments')" :href="'#/article/'+route.params['articleId']" class="sidebar__links__button backToArticleButton"> {{ langData['backToArticleButton'] }} </a>
            <a v-else-if="adminStatus && isCurrentRouteName('ArticleView')" :href="'#/admin/article/editComments/'+route.params['articleId']" class="sidebar__links__button articleCommentsButton"> {{ langData['articleCommentsButton'] }} </a>
            <a v-else></a>
        </div>
        
        <div class="sidebar__linksfooter">
            <a
                class="sidebar__linksfooter__link"
                v-for="link in linksfooter"
                :href="`#${link.routeUri}`"
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