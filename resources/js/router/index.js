import { createRouter, createWebHistory } from "vue-router";
import GpfIndex from '../components/GPF/index.vue'; 
import gpfNew from '../components/GPF/new.vue';
import NotFound from "../components/NotFound.vue";

const routes = [
    {
        path:'/',
        component:GpfIndex
    },
    {
        path:'/:pathMatch(.*)*',
        component: NotFound
    },
    {
        path:'/gpf/new',
        component:gpfNew
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router