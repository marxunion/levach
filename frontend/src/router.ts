import { createRouter, createWebHashHistory, RouteRecordRaw } from 'vue-router';

import Main from './views/Main.vue';
import Article from './views/Article.vue';
import ArticleEdit from './views/ArticleEdit.vue';
import articlesApproved from './views/articlesApproved.vue';
import Abyss from './views/Abyss.vue';
import createArticle from './views/createArticle.vue';
import Support from './views/Support.vue';
import Donate from './views/Donate.vue';
import AboutUs from './views/AboutUs.vue';
import NotFound from './views/NotFound.vue';


const routes: RouteRecordRaw[] = 
[
    
    {
        path: '/',
        name: 'Main',
        component: Main,
    },
    {
        path: '/article/:id',
        name: 'Article',
        component: Article
    },
    {
        path: '/articleEdit/:id',
        name: 'ArticleEdit',
        component: ArticleEdit
    },
    {
        path: '/articlesApproved',
        name: 'articlesApproved',
        component: articlesApproved,
    },
    {
        path: '/abyss',
        name: 'Abyss',
        component: Abyss,
    },
    {
        path: '/createArticle',
        name: 'createArticle',
        component: createArticle,
    },
    {
        path: '/support',
        name: 'Support',
        component: Support,
    },
    {
        path: '/donate',
        name: 'Donate',
        component: Donate,
    },
    {
        path: '/about',
        name: 'About',
        component: AboutUs,
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