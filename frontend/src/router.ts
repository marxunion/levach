import { createRouter, createWebHashHistory, RouteRecordRaw } from 'vue-router';

import { adminStatus, adminStatusReCheck } from './ts/handlers/AdminHandler';

// Article
import ArticleView from './views/article/ArticleView.vue';
import ArticleEdit from './views/article/ArticleEdit.vue';
import ArticleNew from './views/article/ArticleNew.vue';

// Articles

import EditoriallyArticles from './views/articles/EditoriallyArticles.vue';
import EditoriallyApprovedArticles from './views/articles/EditoriallyApprovedArticles.vue';
import AbyssArticles from './views/articles/AbyssArticles.vue';
import ArticlesSearch from './views/articles/ArticlesSearch.vue';

import ArticlesWaitingApprove from './views/articles/ArticlesWaitingApprove.vue';
import ArticlesWaitingPremoderate from './views/articles/ArticlesWaitingPremoderate.vue';


// Admin
import ArticleAdminApprove from './views/article/ArticleAdminApprove.vue';
import ArticleAdminEditComments from './views/article/ArticleAdminEditComments.vue';
import AdminEditComments from './views/admin/AdminEditComments.vue';

// Other routes
import AboutProject from './views/AboutProject.vue';
import Rules from './views/Rules.vue';
import Sponsoring from './views/Sponsoring.vue';

import NotFound from './views/NotFound.vue';
import { watch } from 'vue';

const routes: RouteRecordRaw[] = 
[
    {
        path: '/',
        component: EditoriallyArticles
    },
    {
        path: '/article',
        children: [
            { path: 'new', component: ArticleNew, name: "ArticleNew" },
            { path: 'edit/:articleEditCode', component: ArticleEdit, name: "ArticleEdit" },
            { path: ':articleViewCode', component: ArticleView, name: "ArticleView" },
        ], 
    },
    {
        path: '/articles',
        children: [
            { path: 'editorially', component: EditoriallyArticles, props: {currentRoute: "editoriallyArticles"}, name: "editoriallyArticles"},
            { path: 'approvedEditorially', component: EditoriallyApprovedArticles, props: {currentRoute: "editoriallyApprovedArticles"}, name: "editoriallyApprovedArticles"},
            { path: 'abyss', component: AbyssArticles, props: {currentRoute: "abyssArticles"}, name: "abyssArticles"},
            { path: 'search/:searchData', component: ArticlesSearch, props: {currentRoute: "articlesSearch"}, name: "articlesSearch"},
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
        component: AboutProject,
        name: "AboutProject"
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
            { path: '/edit/comments', component: AdminEditComments, name: "AdminEditComments" },
            { path: 'article/editComments/:articleId', component: ArticleAdminEditComments, name: "ArticleAdminEditComments" },
            { path: 'article/approve/:articleViewCode', component: ArticleAdminApprove, name: "ArticleAdminApprove"},
            { path: 'articles/waitingApproval/', component: ArticlesWaitingApprove, props: {currentRoute: "articlesWaitingApproval"}, name: "articlesWaitingApproval" },
            { path: 'articles/waitingPremoderate/', component: ArticlesWaitingPremoderate, props: {currentRoute: "articlesWaitingPremoderate"}, name: "articlesWaitingPremoderate" },
            { path: 'articles/edit/', component: AdminEditComments, name: "adminEditComments" },
        ],
    })
}

function removeAdminRoutes() 
{
    router.removeRoute('AdminEditComments');
    router.removeRoute('ArticleAdminApprove');
    router.removeRoute('ArticleAdminEditComments');
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