import {createRouter, createWebHistory} from "vue-router";

const routes = [
    {
        path: '/',
        name: 'summary.manage',
        component: () => import(/* webpackChunkName: "SummaryManage" */'../components/SummaryManage.vue')
    },
    {
        path: '/add',
        name: 'summary.add',
        component: () => import(/* webpackChunkName: "SummaryGenerator" */'../components/SummaryGenerator.vue')
    },
    {
        path: '/edit/:id',
        name: 'summary.edit',
        component: () => import(/* webpackChunkName: "SummaryGenerator" */'../components/SummaryGenerator.vue')
    },
    {
        path: '/:pathMatch(.*)*',
        name: 'notFound',
        component: () => import(/* webpackChunkName: "NotFound" */'../components/NotFound.vue')
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
    linkActiveClass: 'my-active-link'
});

export default router;