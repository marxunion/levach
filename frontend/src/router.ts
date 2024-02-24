import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router';

import Main from './views/Main.vue';
import createArticle from './views/createArticle.vue';
import Support from './views/Support.vue';
import Donate from './views/Donate.vue';
import About from './views/About.vue';
import NotFound from './views/NotFound.vue';


const routes: RouteRecordRaw[] = [
    {
        path: '/',
        name: 'Main',
        component: () => Main,
    },
    {
        path: '/createArticle',
        name: 'createArticle',
        component: () => createArticle,
    },
    {
        path: '/support',
        name: 'Support',
        component: () => Support,
    },
    {
        path: '/donate',
        name: 'Donate',
        component: () => Donate,
    },
    {
        path: '/about',
        name: 'About',
        component: () => About,
    },
    {
        path: '/:catchAll(.*)',
        name: 'NotFound',
        component: NotFound
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;