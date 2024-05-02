import { createRouter, createWebHashHistory, RouteRecordRaw } from 'vue-router';

import { adminStatus, adminStatusReCheck } from './ts/AdminHandler';

// Article
import articleView from './views/article/articleView.vue';
import articleEdit from './views/article/articleEdit.vue';
import articleNew from './views/article/articleNew.vue';

// Articles
import Articles from './views/Articles.vue';

// Admin
import articleAdminEditComments from './views/article/articleAdminEditComments.vue';
import AdminEditComments from './views/admin/AdminEditComments.vue';

// Other routes
import aboutProject from './views/aboutProject.vue';
import Rules from './views/Rules.vue';
import Sponsoring from './views/Sponsoring.vue';

import NotFound from './views/NotFound.vue';
import { watch } from 'vue';

const routes: RouteRecordRaw[] = 
[
    {
        path: '/',
        component: Articles,
        name: "Articles"
    },
    {
        path: '/article',
        children: [
            { path: 'new', component: articleNew, name: "articleNew" },
            { path: 'edit/:articleEditCode', component: articleEdit, name: "articleEdit" },
            { path: ':articleViewCode', component: articleView, name: "articleView" },
        ], 
    },
    {
        path: '/articles',
        children: [
            { path: 'editorially', component: Articles, props: {currentRoute: "editoriallyArticles"}, name: "editoriallyArticles"},
            { path: 'approvedEditorially', component: Articles, props: {currentRoute: "editoriallyApprovedArticles"}, name: "editoriallyApprovedArticles"},
            { path: 'abyss', component: Articles, props: {currentRoute: "abyssArticles"}, name: "abyssArticles"},
            { path: 'search/:searchData', component: Articles, props: {currentRoute: "articlesSearch"}, name: "articlesSearch"},
        ],
    },
    {
        path: '/rules',
        component: Rules,
        name: "Rules"
    },
    {
        path: '/sponsoring',
        component: Sponsoring,
        name: "Sponsoring"
    },
    {
        path: '/faq',
        component: aboutProject,
        name: "aboutProject"
    },
    {
        path: '/:catchAll(.*)',
        component: NotFound,
        name: 'NotFound'
    }
];

adminStatusReCheck();
setInterval(adminStatusReCheck, 1000);

const router = createRouter(
{
    history: createWebHashHistory(),
    routes,
});

function addAdminRoutes() 
{
    router.addRoute({
        path: '/admin',
        children: [
            { path: '', component: AdminEditComments, name: "adminEditComments" },
            { path: 'article/editComments/:articleId', component: articleAdminEditComments, name: "articleAdminEditComments" },
            { path: 'articles/waitingApproval/', component: Articles, props: {currentRoute: "articlesWaitingApproval"}, name: "articlesWaitingApproval" },
            { path: 'articles/waitingPremoderate/', component: Articles, props: {currentRoute: "articlesWaitingPremoderate"}, name: "articlesWaitingPremoderate" },
            { path: 'articles/edit/', component: AdminEditComments, name: "adminEditComments" },
        ],
    })
}

function removeAdminRoutes() 
{
    router.removeRoute('adminEditComments');
    router.removeRoute('articleAdminEditComments');
    router.removeRoute('articlesWaitingApproval');
    router.removeRoute('articlesWaitingPremoderate');
}

if(adminStatus)
{
    addAdminRoutes();
}

watch(adminStatus, (newVal, oldVal) => 
{
    if (newVal) 
    {
        addAdminRoutes();
    } 
    else 
    {
        removeAdminRoutes();
    }
});

export default router;