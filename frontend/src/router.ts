import { createRouter, createWebHashHistory, RouteRecordRaw } from 'vue-router';

import { isAdmin } from './ts/AdminHandler';

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
            { path: 'edit/:articleEditId', component: articleEdit, name: "articleEdit" },
            { path: ':articleId', component: articleView, name: "articleView" },
        ], 
    },
    {
        path: '/articles',
        children: [
            { path: '', component: Articles, props: {currentRoute: "editoriallyArticles"}, name: "editoriallyArticles"},
            { path: 'editorially', component: Articles, props: {currentRoute: "editoriallyArticles"}, name: "editoriallyArticles"},
            { path: 'approvedEditorially', component: Articles, props: {currentRoute: "editoriallyApprovedArticles"}, name: "editoriallyApprovedArticles"},
            { path: 'abyss', component: Articles, props: {currentRoute: "abyssArticles"}, name: "abyssArticles"},
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
        path: '/aboutProject',
        component: aboutProject,
        name: "aboutProject"
    },
    {
        path: '/faq',
        component: aboutProject,
        name: "aboutProject"
    },
    {
        path: '/aboutProjectAndFAQ',
        component: aboutProject,
        name: "aboutProject"
    },


    {
        path: '/:catchAll(.*)',
        component: NotFound,
        name: 'NotFound'
    }
];

if (isAdmin) 
{
    routes.push({
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

const router = createRouter({
    history: createWebHashHistory(),
    routes,
});

export default router;