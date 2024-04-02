import { createRouter, createWebHistory } from "vue-router";
import GpfIndex from '../components/GPF/index.vue'; 
import gpfNew from '../components/GPF/new.vue';
import GpfShow from '../components/GPF/show.vue';
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
    {
        path:'/gpf/show/:id',
        component:GpfShow,
        props:true
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router