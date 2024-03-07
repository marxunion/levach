import { createRouter, createWebHashHistory, RouteRecordRaw } from 'vue-router';

import { isAdmin } from './ts/AdminHandler';

// Article
import articleView from './views/article/articleView.vue';
import articleEdit from './views/article/articleEdit.vue';
import articleNew from './views/article/articleNew.vue';

// Articles
import Articles from './views/Articles.vue';

// Admin
import articleAdminEdit from './views/article/articleAdminEdit.vue';
import AdminComments from './views/admin/AdminComments.vue';

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
    },
    {
        path: '/article',
        children: [
            { path: 'new', component: articleNew },
            { path: 'edit/:id', component: articleEdit },
            { path: ':id', component:  articleView },
        ], 
    },
    {
        path: '/articles',
        children: [
            { path: '', component: Articles, props: {currentRoute: "editoriallyArticles"} },
            { path: 'editorially', component: Articles, props: {currentRoute: "editoriallyArticles"} },
            { path: 'approvedEditorially', component: Articles, props: {currentRoute: "editoriallyApprovedArticles"} },
            { path: 'abyss', component: Articles, props: {currentRoute: "abyssArticles"} },
        ],
    },
    {
        path: '/rules',
        component: Rules,
    },
    {
        path: '/sponsoring',
        component: Sponsoring,
    },

    {
        path: '/aboutProject',
        component: aboutProject,
    },
    {
        path: '/faq',
        component: aboutProject,
    },
    {
        path: '/aboutProjectAndFAQ',
        component: aboutProject,
    },


    {
        path: '/:catchAll(.*)',
        name: 'NotFound',
        component: NotFound
    }
];

if (isAdmin) 
{
    routes.push({
        path: '/admin',
        children: [
            { path: '', component: AdminComments },
            { path: 'article/edit/:id', component: articleAdminEdit },
            { path: 'articles/waitingApproval/', component: Articles, props: {currentRoute: "articlesWaitingApproval"} },
            { path: 'articles/waitingPremoderate/', component: Articles, props: {currentRoute: "articlesWaitingPremoderate"} },
            { path: 'articles/edit/', component: AdminComments },
        ],
    })
}

const router = createRouter({
    history: createWebHashHistory(),
    routes,
});

export default router;