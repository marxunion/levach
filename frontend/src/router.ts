import { createRouter, createWebHashHistory, RouteRecordRaw } from 'vue-router';

// Article
import articleView from './views/article/articleView.vue';
import articleEdit from './views/article/articleEdit.vue';
import articleNew from './views/article/articleNew.vue';

// Articles
import articlesEditorially from './views/articles/articlesEditorially.vue';
import articlesApprovedEditorially from './views/articles/articlesApprovedEditorially.vue';
import articlesAbyss from './views/articles/articlesAbyss.vue';

// Other routes
import aboutProject from './views/aboutProject.vue';
import Rules from './views/Rules.vue';
import Support from './views/Support.vue';
import Donate from './views/Donate.vue';


import NotFound from './views/NotFound.vue';


const routes: RouteRecordRaw[] = 
[
    {
        path: '/',
        component: articlesEditorially,
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
          { path: 'editorially', component: articlesEditorially },
          { path: 'approvedEditorially', component: articlesApprovedEditorially },
          { path: 'abyss', component: articlesAbyss },
        ], 
    },

    {
        path: '/rules',
        component: Rules,
    },
    {
        path: '/support',
        component: Support,
    },
    {
        path: '/donate',
        component: Donate,
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

const router = createRouter({
    history: createWebHashHistory(),
    routes,
});

export default router;